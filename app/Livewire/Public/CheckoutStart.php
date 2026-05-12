<?php

namespace App\Livewire\Public;

use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Checkout'])]
class CheckoutStart extends Component
{
    public Event $event;

    public array $items = [];

    public function mount(string $slug): void
    {
        $this->event = Event::where('slug', $slug)->firstOrFail();

        $this->items = session('checkout_items', []);

        if (empty($this->items) || session('checkout_event_id') !== $this->event->id) {
            $this->redirect(route('events.show', $this->event->slug));
        }
    }

    public function render()
    {
        return view('livewire.public.checkout-start');
    }
}
