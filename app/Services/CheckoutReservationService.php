<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TicketType;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutReservationService
{
    public function __construct(private readonly OrderPricingService $pricing) {}

    /**
     * Number of tickets currently locked by unexpired pending orders.
     */
    public function pendingReserved(int $ticketTypeId, ?int $excludeOrderId = null): int
    {
        return (int) OrderItem::query()
            ->where('ticket_type_id', $ticketTypeId)
            ->whereHas('order', fn ($q) => $q
                ->where('status', OrderStatus::PENDING)
                ->where('expires_at', '>', now())
                ->when($excludeOrderId, fn ($q) => $q->where('id', '!=', $excludeOrderId))
            )
            ->sum('quantity');
    }

    /**
     * True available = total capacity − sold (paid only) − active reservations.
     */
    public function available(TicketType $type, ?int $excludeOrderId = null): int
    {
        return max(0, $type->quantity - $type->sold - $this->pendingReserved($type->id, $excludeOrderId));
    }

    /**
     * Pre-flight check — fast, no locks, no transaction.
     * Throws ValidationException with per-ticket messages if anything is short.
     *
     * @param  array<array{ticket_type_id: int, quantity: int}>  $items
     */
    public function validateAvailability(array $items, ?int $excludeOrderId = null): void
    {
        foreach ($items as ['ticket_type_id' => $ttId, 'quantity' => $qty]) {
            $type      = TicketType::findOrFail($ttId);
            $available = $this->available($type, $excludeOrderId);

            if ($qty > $available) {
                throw ValidationException::withMessages([
                    "tickets.{$ttId}" => $available > 0
                        ? "Only {$available} {$type->name} ticket(s) remaining."
                        : "No {$type->name} tickets are currently available.",
                ]);
            }
        }
    }

    /**
     * Create a pending order inside a serialisable transaction.
     *
     * Flow:
     *  1. Lock all ticket type rows (lockForUpdate)
     *  2. Re-validate availability with fresh numbers (never trust pre-lock state)
     *  3. Create order + items — no sold increment here
     *  4. Return loaded order
     *
     * @param  array<array{ticket_type_id: int, quantity: int}>  $items
     */
    public function createPendingOrder(User $user, Event $event, array $items): Order
    {
        return DB::transaction(function () use ($user, $event, $items) {
            // ── 1. Acquire row-level locks on every affected ticket type ──────
            $types = TicketType::whereIn('id', array_column($items, 'ticket_type_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $lineTotals = [];
            $orderItems = [];
            $currency   = 'kes';

            foreach ($items as ['ticket_type_id' => $ttId, 'quantity' => $qty]) {
                /** @var TicketType $type */
                $type = $types->get($ttId);

                if (! $type || $type->event_id !== $event->id) {
                    throw new \InvalidArgumentException(
                        "Ticket type {$ttId} not found or does not belong to this event."
                    );
                }

                if (! $type->isOnSale()) {
                    throw ValidationException::withMessages([
                        "tickets.{$ttId}" => "{$type->name} is not currently on sale.",
                    ]);
                }

                if ($type->isGroupTicket() && $qty !== $type->group_size) {
                    throw ValidationException::withMessages([
                        "tickets.{$ttId}" => "{$type->name} requires exactly {$type->group_size} attendees.",
                    ]);
                }

                if (! $type->isGroupTicket() && ($qty < $type->min_per_order || $qty > $type->max_per_order)) {
                    throw ValidationException::withMessages([
                        "tickets.{$ttId}" => "You can purchase {$type->min_per_order}–{$type->max_per_order} {$type->name} tickets per order.",
                    ]);
                }

                // ── 2. Re-validate availability inside the lock ────────────────
                $available = $this->available($type);

                if ($qty > $available) {
                    throw ValidationException::withMessages([
                        "tickets.{$ttId}" => $available > 0
                            ? "Ticket availability changed while checking out. Only {$available} {$type->name} ticket(s) remain."
                            : "{$type->name} tickets are no longer available.",
                    ]);
                }

                $lineTotal    = $type->getTotalPriceForOrder($qty);
                $lineTotals[] = $lineTotal;
                $currency     = $type->currency;

                $orderItems[] = [
                    'ticket_type_id' => $ttId,
                    'quantity'       => $qty,
                    'unit_price'     => $type->price,
                    'subtotal'       => $lineTotal,
                ];
            }

            // ── 3. Create order — sold is NOT incremented here ─────────────────
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
                'expires_at'     => now()->addMinutes(10),
            ]);

            $order->items()->createMany($orderItems);

            return $order->load('items.ticketType');
        });
    }
}
