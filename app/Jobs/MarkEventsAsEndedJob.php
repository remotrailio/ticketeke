<?php

namespace App\Jobs;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class MarkEventsAsEndedJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('low');
    }

    public function handle(): void
    {
        // end_at is stored in UTC, so comparing with now() is timezone-safe.
        // The event's timezone column is only for display purposes.
        Event::whereIn('status', [EventStatus::PUBLISHED, EventStatus::LIVE])
            ->whereNotNull('end_at')
            ->where('end_at', '<=', now())
            ->update(['status' => EventStatus::ENDED]);
    }
}
