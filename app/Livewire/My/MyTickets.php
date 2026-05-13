<?php

namespace App\Livewire\My;

use App\Models\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app', ['title' => 'My Tickets'])]
class MyTickets extends Component
{
    use WithPagination;

    public function render()
    {
        $tickets = Ticket::with(['order.event', 'orderItem.ticketType'])
            ->whereHas('order', fn ($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(12);

        return view('livewire.my.my-tickets', compact('tickets'));
    }
}
