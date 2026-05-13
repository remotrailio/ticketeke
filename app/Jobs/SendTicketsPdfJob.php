<?php

namespace App\Jobs;

use App\Mail\TicketsPurchased;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SendTicketsPdfJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public int $tries = 3;

    public int $backoff = 15;

    public function __construct(public readonly int $orderId) {}

    public function handle(): void
    {
        $order = Order::with([
            'tickets.orderItem.ticketType',
            'event',
            'user',
        ])->findOrFail($this->orderId);

        Log::info('SendTicketsPdfJob: generating PDF', [
            'order'   => $order->order_number,
            'email'   => $order->user->email,
            'tickets' => $order->tickets->count(),
        ]);

        $storagePath = 'temp/tickets/tickets-' . $order->order_number . '.pdf';

        $pdf = app('dompdf.wrapper')
            ->loadView('pdf.tickets', ['order' => $order])
            ->setPaper('a4', 'portrait');

        Storage::put($storagePath, $pdf->output());

        try {
            Mail::to($order->user->email, $order->user->name)
                ->send(new TicketsPurchased($order, $storagePath));

            Log::info('SendTicketsPdfJob: ticket email sent', ['order' => $order->order_number]);
        } finally {
            Storage::delete($storagePath);
        }
    }

    public function failed(Throwable $e): void
    {
        Log::error('SendTicketsPdfJob failed', [
            'order_id' => $this->orderId,
            'error'    => $e->getMessage(),
        ]);
    }
}
