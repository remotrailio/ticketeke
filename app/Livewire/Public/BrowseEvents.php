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
    public array $selectedCategories = [];

    #[Url]
    public array $selectedCities = [];

    #[Url]
    public string $selectedDate = '';

    #[Url]
    public string $sort = 'start_at';

    public function updatedSearch(): void            { $this->resetPage(); }
    public function updatedSelectedCategories(): void { $this->resetPage(); }
    public function updatedSelectedCities(): void    { $this->resetPage(); }
    public function updatedSelectedDate(): void      { $this->resetPage(); }
    public function updatedSort(): void              { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search             = '';
        $this->selectedCategories = [];
        $this->selectedCities     = [];
        $this->selectedDate       = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Event::with(['organizer', 'category', 'ticketTypes'])
            ->where('status', EventStatus::PUBLISHED)
            ->where('visibility', EventVisibility::PUBLIC)
            ->where('start_at', '>=', now());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('excerpt', 'like', "%{$this->search}%")
                  ->orWhere('venue_name', 'like', "%{$this->search}%")
                  ->orWhere('city', 'like', "%{$this->search}%");
            });
        }

        if (! empty($this->selectedCategories)) {
            $query->whereHas('category', fn ($q) => $q->whereIn('slug', $this->selectedCategories));
        }

        if (! empty($this->selectedCities)) {
            $query->whereIn('city', $this->selectedCities);
        }

        match ($this->selectedDate) {
            'today'      => $query->whereDate('start_at', today()),
            'tomorrow'   => $query->whereDate('start_at', today()->addDay()),
            'this_week'  => $query->whereBetween('start_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'this_month' => $query->whereMonth('start_at', now()->month)->whereYear('start_at', now()->year),
            default      => null,
        };

        $query->orderBy(
            in_array($this->sort, ['start_at', 'published_at']) ? $this->sort : 'start_at'
        );

        $events     = $query->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $activeFilterCount = count($this->selectedCategories)
            + count($this->selectedCities)
            + ($this->selectedDate ? 1 : 0);

        return view('livewire.public.browse-events', compact(
            'events',
            'categories',
            'activeFilterCount',
        ));
    }
}
