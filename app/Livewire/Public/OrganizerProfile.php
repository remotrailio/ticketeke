<?php

namespace App\Livewire\Public;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use App\Models\Organizer;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizerProfile extends Component
{
    use WithPagination;

    public Organizer $organizer;

    public function mount(string $slug): void
    {
        $this->organizer = Organizer::where('slug', $slug)
            ->where('verified', true)
            ->firstOrFail();
    }

    public function render()
    {
        $events = $this->organizer->events()
            ->with('category')
            ->where('status', EventStatus::PUBLISHED)
            ->where('visibility', EventVisibility::PUBLIC)
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->paginate(9);

        return view('livewire.public.organizer-profile', compact('events'))
            ->layout('layouts.app', [
                'title'       => $this->organizer->display_name . ' – ' . config('app.name'),
                'description' => $this->organizer->bio ?? "Events by {$this->organizer->display_name}",
                'ogImage'     => $this->organizer->banner ? \Illuminate\Support\Facades\Storage::disk('r2')->url($this->organizer->banner) : null,
            ]);
    }
}
