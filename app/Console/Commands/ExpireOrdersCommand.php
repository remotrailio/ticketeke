<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Console\Command;

class ExpireOrdersCommand extends Command
{
    protected $signature = 'app:expire-orders';

    protected $description = 'Mark pending orders past their expiry time as expired';

    public function handle(): int
    {
        $orders = Order::query()
            ->where('status', OrderStatus::PENDING)
            ->where('expires_at', '<', now())
            ->get();

        foreach ($orders as $order) {
            $order->markExpired();
        }

        $this->info("Expired {$orders->count()} order(s).");

        return self::SUCCESS;
    }
}
