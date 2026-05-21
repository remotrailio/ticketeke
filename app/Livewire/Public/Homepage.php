<?php

namespace App\Livewire\Public;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use App\Models\Category;
use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Pitisha – Discover Events'])]
class Homepage extends Component
{
    public function render()
    {
        $featured = Event::with(['organizer', 'category'])
            ->withCount('attendees')
            ->where('status', EventStatus::PUBLISHED)
            ->where('visibility', EventVisibility::PUBLIC)
            ->where('end_at', '>=', now())
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();

        $upcoming = Event::with(['organizer', 'category'])
            ->withCount('attendees')
            ->where('status', EventStatus::PUBLISHED)
            ->where('visibility', EventVisibility::PUBLIC)
            ->where('end_at', '>=', now())
            ->orderBy('start_at')
            ->limit(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $heroCategories = Category::where('is_active', true)
            ->withCount(['events' => fn ($q) => $q
                ->where('status', EventStatus::PUBLISHED)
                ->where('visibility', EventVisibility::PUBLIC)
                ->where('end_at', '>=', now())
            ])
            ->having('events_count', '>', 0)
            ->orderByDesc('events_count')
            ->limit(5)
            ->get();

        return view('livewire.public.homepage', compact('featured', 'upcoming', 'categories', 'heroCategories'));
    }
}
