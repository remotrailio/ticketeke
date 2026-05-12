<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Event;
use App\Models\Order;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class CheckoutService
{
    public function __construct(
        private readonly OrderPricingService $pricing,
    ) {}

    public function resolveGuestUser(string $email, string $name, ?string $phone = null): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'password' => Hash::make(Str::random(16)),
                'role'     => UserRole::ATTENDEE,
            ]
        );
    }

    /**
     * Create a pending order with reserved inventory.
     *
     * @param  array<array{ticket_type_id: int, quantity: int}>  $items
     */
    public function checkout(User $user, Event $event, array $items): Order
    {
        // Eager-load organizer so fee calculation doesn't N+1 inside the transaction
        $event->loadMissing('organizer');

        return DB::transaction(function () use ($user, $event, $items) {
            $lineTotals = [];
            $currency   = 'kes';
            $orderItems = [];

            foreach ($items as $item) {
                /** @var TicketType $ticketType */
                $ticketType = TicketType::lockForUpdate()->findOrFail($item['ticket_type_id']);

                if ($ticketType->event_id !== $event->id) {
                    throw new InvalidArgumentException(
                        "Ticket type [{$ticketType->name}] does not belong to this event."
                    );
                }

                $quantity = $item['quantity'];

                if ($ticketType->isGroupTicket() && $quantity !== $ticketType->group_size) {
                    throw new InvalidArgumentException(
                        "Group ticket [{$ticketType->name}] requires a quantity of exactly {$ticketType->group_size}."
                    );
                }

                if (! $ticketType->canPurchase($quantity)) {
                    throw new RuntimeException(
                        "Cannot purchase {$quantity} of [{$ticketType->name}]: "
                        . 'check availability, purchase limits, or sale window.'
                    );
                }

                // Group tickets: flat price per group. Normal tickets: price × quantity.
                $lineTotal    = $ticketType->getTotalPriceForOrder($quantity);
                $lineTotals[] = $lineTotal;
                $currency     = $ticketType->currency;

                $orderItems[] = [
                    'ticket_type_id' => $ticketType->id,
                    'quantity'       => $quantity,
                    'unit_price'     => $ticketType->price,
                    'subtotal'       => $lineTotal,
                ];

                // Reserve inventory — group tickets consume group_size slots
                $ticketType->increment('sold', $quantity);
            }

            $subtotal = $this->pricing->calculateSubtotal($lineTotals);
            $fee      = $this->pricing->calculatePlatformFee($event, $subtotal);
            $total    = $this->pricing->calculateTotal($subtotal, $fee);

            $order = Order::create([
                'user_id'        => $user->id,
                'event_id'       => $event->id,
                'subtotal'       => $subtotal,
                'fees'           => $fee,
                'discount'       => 0,
                'total'          => $total,
                'currency'       => $currency,
                'status'         => OrderStatus::PENDING,
                'payment_status' => PaymentStatus::UNPAID,
                'expires_at'     => now()->addMinutes(15),
            ]);

            $order->items()->createMany($orderItems);

            return $order->load('items.ticketType');
        });
    }
}
