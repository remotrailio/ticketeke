<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\TicketGeneratorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenerateTicketsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(public readonly int $orderId) {}

    public function middleware(): array
    {
        return [new WithoutOverlapping('generate_tickets_' . $this->orderId, 30)];
    }

    public function handle(TicketGeneratorService $generator): void
    {
        $order = Order::findOrFail($this->orderId);

        Log::info('GenerateTicketsJob: starting', ['order' => $order->order_number]);

        $generator->generate($order);

        SendTicketsPdfJob::dispatch($this->orderId);
        NotifyOrganizerJob::dispatch($this->orderId);

        Log::info('GenerateTicketsJob: complete', ['order' => $order->order_number]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('GenerateTicketsJob: failed', [
            'order_id' => $this->orderId,
            'error'    => $e->getMessage(),
        ]);
    }
}
