<?php

namespace App\Livewire\Public;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use App\Models\Category;
use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app', ['title' => 'Browse Events'])]
class BrowseEvents extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $city = '';

    #[Url]
    public string $sort = 'start_at';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedCategory(): void { $this->resetPage(); }
    public function updatedCity(): void { $this->resetPage(); }
    public function updatedSort(): void { $this->resetPage(); }

    public function render()
    {
        $query = Event::with(['organizer', 'category'])
            ->where('status', EventStatus::PUBLISHED)
            ->where('visibility', EventVisibility::PUBLIC)
            ->where('start_at', '>=', now());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('excerpt', 'like', "%{$this->search}%")
                  ->orWhere('venue_name', 'like', "%{$this->search}%");
            });
        }

        if ($this->category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->category));
        }

        if ($this->city) {
            $query->where('city', 'like', "%{$this->city}%");
        }

        $query->orderBy(
            in_array($this->sort, ['start_at', 'published_at']) ? $this->sort : 'start_at'
        );

        $events = $query->paginate(12);

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('livewire.public.browse-events', compact('events', 'categories'));
    }
}
