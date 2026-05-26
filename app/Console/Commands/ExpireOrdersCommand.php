<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExpireOrdersCommand extends Command
{
    protected $signature = 'app:expire-orders
                            {--dry-run : Preview how many orders would be expired without modifying anything}';

    protected $description = 'Mark pending orders past their expiry time as expired (bulk update)';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $lock = Cache::lock('app:expire-orders', 60);

        if (! $lock->get()) {
            $this->warn('Another instance is already running. Skipping.');
            return self::SUCCESS;
        }

        try {
            $query = Order::where('status', OrderStatus::PENDING)
                ->where('expires_at', '<', now());

            if ($dryRun) {
                $count = $query->count();
                $this->info("[dry-run] {$count} order(s) would be expired.");
                return self::SUCCESS;
            }

            $count = $query->update([
                'status'         => OrderStatus::EXPIRED,
                'payment_status' => PaymentStatus::FAILED,
            ]);

            if ($count > 0) {
                Log::info('app:expire-orders completed', ['expired' => $count]);
            }

            $this->info("Expired {$count} order(s).");

        } finally {
            $lock->release();
        }

        return self::SUCCESS;
    }
}
