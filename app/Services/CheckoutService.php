<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class CheckoutService
{
    /**
     * Create a pending order with reserved inventory.
     *
     * @param  array<array{ticket_type_id: int, quantity: int}>  $items
     */
    public function checkout(User $user, Event $event, array $items): Order
    {
        return DB::transaction(function () use ($user, $event, $items) {
            $subtotal   = 0.0;
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

                if (! $ticketType->canPurchase($item['quantity'])) {
                    throw new RuntimeException(
                        "Cannot purchase {$item['quantity']} of [{$ticketType->name}]: "
                        . 'check availability, purchase limits, or sale window.'
                    );
                }

                $lineTotal  = (float) $ticketType->price * $item['quantity'];
                $subtotal  += $lineTotal;
                $currency   = $ticketType->currency;

                $orderItems[] = [
                    'ticket_type_id' => $ticketType->id,
                    'quantity'       => $item['quantity'],
                    'unit_price'     => $ticketType->price,
                    'subtotal'       => $lineTotal,
                ];

                // Reserve inventory so concurrent checkouts see updated availability
                $ticketType->increment('sold', $item['quantity']);
            }

            $order = Order::create([
                'user_id'        => $user->id,
                'event_id'       => $event->id,
                'subtotal'       => $subtotal,
                'fees'           => 0,
                'discount'       => 0,
                'total'          => $subtotal,
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
