<?php

namespace App\Livewire\Public;

use App\Models\Event;
use App\Services\OrderPricingService;
use Livewire\Component;

class TicketSelector extends Component
{
    public Event $event;

    /** @var array<int, int> ticket_type_id => quantity */
    public array $quantities = [];

    public function mount(Event $event): void
    {
        $this->event = $event;

        foreach ($event->ticketTypes as $type) {
            $this->quantities[$type->id] = 0;
        }
    }

    public function increment(int $ticketTypeId): void
    {
        $type = $this->event->ticketTypes()->find($ticketTypeId);

        if (! $type) {
            return;
        }

        $isGroup  = $type->isGroupTicket();
        $step     = $isGroup ? (int) $type->group_size : 1;
        $maxUnits = ($isGroup ? 1 : (int) $type->max_per_order) * $step;

        $this->quantities[$ticketTypeId] = min(
            ($this->quantities[$ticketTypeId] ?? 0) + $step,
            $maxUnits
        );
    }

    public function decrement(int $ticketTypeId): void
    {
        $type = $this->event->ticketTypes()->find($ticketTypeId);

        if (! $type) {
            return;
        }

        $step = $type->isGroupTicket() ? (int) $type->group_size : 1;
        $this->quantities[$ticketTypeId] = max(0, ($this->quantities[$ticketTypeId] ?? 0) - $step);
    }

    public function proceedToCheckout(): void
    {
        if (! collect($this->quantities)->sum()) {
            return;
        }

        session([
            'checkout_items'    => array_filter($this->quantities),
            'checkout_event_id' => $this->event->id,
        ]);

        $this->redirect(route('checkout.start', $this->event->slug));
    }

    public function render()
    {
        $ticketTypes = $this->event->ticketTypes()->where('is_active', true)->get();
        $summary     = app(OrderPricingService::class)
            ->buildOrderSummary($this->event, $ticketTypes, $this->quantities);

        return view('livewire.public.ticket-selector', [
            'ticketTypes'  => $ticketTypes,
            'quantities'   => $this->quantities,
            'hasSelection' => collect($this->quantities)->sum() > 0,
            ...$summary,
        ]);
    }
}
