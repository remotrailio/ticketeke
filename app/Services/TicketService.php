<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ticket;

class TicketService
{
    public function generateForOrder(Order $order): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                Ticket::create([
                    'order_id'      => $order->id,
                    'order_item_id' => $item->id,
                    'user_id'       => $order->user_id,
                ]);
            }
        }
    }
}
