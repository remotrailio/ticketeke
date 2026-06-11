<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ticket;

class TicketService
{
    public function generateForOrder(Order $order): void
    {
        if ($order->tickets()->exists()) {
            return;
        }

        $order->loadMissing('items.ticketType');

        foreach ($order->items as $item) {
            $ticketType = $item->ticketType;

            // Group tickets: generate group_size individual tickets per order item.
            // Normal tickets: generate one ticket per quantity unit.
            // Both cases resolve to $item->quantity because checkout enforces
            // quantity = group_size for group tickets.
            $ticketsToGenerate = $ticketType->isGroupTicket()
                ? (int) $ticketType->group_size
                : $item->quantity;

            for ($i = 0; $i < $ticketsToGenerate; $i++) {
                Ticket::create([
                    'order_id'      => $order->id,
                    'order_item_id' => $item->id,
                    'user_id'       => $order->user_id,
                ]);
            }
        }
    }
}
