<?php

namespace App\Livewire\Public;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EventDetails extends Component
{
    public Event $event;

    public function mount(string $slug): void
    {
        $this->event = Event::with(['organizer', 'category', 'ticketTypes' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }])
            ->where('slug', $slug)
            ->where('status', EventStatus::PUBLISHED)
            ->where('visibility', EventVisibility::PUBLIC)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.event-details')
            ->layout('layouts.app', [
                'title'       => $this->event->title . ' – ' . config('app.name'),
                'description' => $this->event->excerpt ?? '',
                'ogImage'     => $this->event->banner_url,
                'canonical'   => route('events.show', $this->event->slug),
            ]);
    }
}
