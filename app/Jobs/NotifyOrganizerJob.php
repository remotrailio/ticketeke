<?php

namespace App\Jobs;

use App\Mail\OrganizerTicketSold;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotifyOrganizerJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public int $tries = 3;

    public int $backoff = 15;

    public function __construct(public readonly int $orderId) {}

    public function handle(): void
    {
        $order = Order::with([
            'items.ticketType',
            'event.organizer.user',
            'user',
        ])->findOrFail($this->orderId);

        $organizerUser = $order->event?->organizer?->user;

        if (! $organizerUser?->email) {
            Log::warning('NotifyOrganizerJob: no organizer email found', [
                'order' => $order->order_number,
            ]);
            return;
        }

        Log::info('Notifying organizer of ticket sale', [
            'order'     => $order->order_number,
            'organizer' => $organizerUser->email,
        ]);

        Mail::to($organizerUser->email, $organizerUser->name)
            ->send(new OrganizerTicketSold($order));

        Log::info('Organizer notified', ['order' => $order->order_number]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('NotifyOrganizerJob failed', [
            'order_id' => $this->orderId,
            'error'    => $e->getMessage(),
        ]);
    }
}
