<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketGeneratorService
{
    /**
     * Generate one Ticket row per individual attendee for every order item.
     *
     * Rules:
     *  - Regular ticket  → item.quantity tickets (e.g. qty=3 → 3 tickets)
     *  - Group ticket    → item.quantity tickets (canPurchase enforces qty === group_size,
     *                       so buying one "Group of 5" stores quantity=5 → 5 tickets)
     *  - Attendee fields default to the order owner; can be updated later per ticket.
     *  - Idempotent: safe to call more than once (M-Pesa can retry callbacks).
     */
    public function generate(Order $order): void
    {
        if ($order->tickets()->exists()) {
            Log::info('Ticket generation skipped — tickets already exist', [
                'order' => $order->order_number,
            ]);
            return;
        }

        Log::info('Ticket generation started', ['order' => $order->order_number]);

        DB::transaction(function () use ($order) {
            $locked = Order::lockForUpdate()->find($order->id);

            if ($locked->tickets()->exists()) {
                Log::info('Ticket generation skipped — duplicate prevented by lock', [
                    'order' => $order->order_number,
                ]);
                return;
            }

            $locked->loadMissing('items.ticketType', 'user');

            $defaultName  = $locked->user?->name;
            $defaultEmail = $locked->user?->email;
            $generated    = 0;

            foreach ($locked->items as $item) {
                // item.quantity is ALWAYS the number of individual attendees:
                //   - Regular: user chose qty (e.g. 3 individual tickets)
                //   - Group:   canPurchase enforces qty === group_size (e.g. "Group of 5" → qty=5)
                // Either way: one Ticket row per attendee, no sharing.
                for ($i = 0; $i < $item->quantity; $i++) {
                    Ticket::create([
                        'order_id'       => $locked->id,
                        'order_item_id'  => $item->id,
                        'user_id'        => $locked->user_id,
                        'attendee_name'  => $defaultName,
                        'attendee_email' => $defaultEmail,
                    ]);

                    $generated++;
                }
            }

            $locked->update(['status' => OrderStatus::COMPLETED]);

            Log::info('Ticket generation complete', [
                'order'   => $locked->order_number,
                'tickets' => $generated,
                'items'   => $locked->items->map(fn ($i) => [
                    'type' => $i->ticketType?->name,
                    'qty'  => $i->quantity,
                ]),
            ]);
        });
    }
}
