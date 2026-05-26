<?php

namespace App\Jobs;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class MarkEventsAsLiveJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('low');
    }

    public function handle(): void
    {
        Event::where('status', EventStatus::PUBLISHED)
            ->where('start_at', '<=', now())
            ->where(function ($q) {
                $q->where('end_at', '>', now())->orWhereNull('end_at');
            })
            ->update(['status' => EventStatus::LIVE]);
    }
}
