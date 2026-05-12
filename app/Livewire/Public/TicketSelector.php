<?php

namespace App\Livewire\Public;

use App\Models\Event;
use App\Models\TicketType;
use App\Services\OrderPricingService;
use Illuminate\Support\Collection;
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
        $type = $this->event->ticketTypes->firstWhere('id', $ticketTypeId);

        if (! $type) {
            return;
        }

        $max = $type->isGroupTicket() ? 1 : $type->max_per_order;
        $this->quantities[$ticketTypeId] = min(
            ($this->quantities[$ticketTypeId] ?? 0) + ($type->isGroupTicket() ? $type->group_size : 1),
            $max * ($type->isGroupTicket() ? $type->group_size : 1)
        );
    }

    public function decrement(int $ticketTypeId): void
    {
        $type = $this->event->ticketTypes->firstWhere('id', $ticketTypeId);

        if (! $type) {
            return;
        }

        $step = $type->isGroupTicket() ? $type->group_size : 1;
        $this->quantities[$ticketTypeId] = max(0, ($this->quantities[$ticketTypeId] ?? 0) - $step);
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->quantities)->reduce(function (float $carry, int $qty, int $id) {
            $type = $this->event->ticketTypes->firstWhere('id', $id);
            return $carry + ($type ? $type->getTotalPriceForOrder($qty) : 0);
        }, 0.0);
    }

    public function getFeeProperty(): float
    {
        return app(OrderPricingService::class)->calculatePlatformFee($this->event, $this->subtotal);
    }

    public function getTotalProperty(): float
    {
        return app(OrderPricingService::class)->calculateTotal($this->subtotal, $this->fee);
    }

    public function getCurrencyProperty(): string
    {
        $firstActive = $this->event->ticketTypes->firstWhere('is_active', true);
        return strtoupper($firstActive?->currency ?? 'KES');
    }

    public function hasSelection(): bool
    {
        return collect($this->quantities)->sum() > 0;
    }

    public function proceedToCheckout(): void
    {
        if (! $this->hasSelection()) {
            return;
        }

        // Redirect to checkout with quantities in session
        session(['checkout_items' => array_filter($this->quantities)]);
        session(['checkout_event_id' => $this->event->id]);

        $this->redirect(route('checkout.start', $this->event->slug));
    }

    public function render()
    {
        return view('livewire.public.ticket-selector');
    }
}
