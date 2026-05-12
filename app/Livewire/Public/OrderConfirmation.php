<?php

namespace App\Livewire\Public;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Order Confirmed'])]
class OrderConfirmation extends Component
{
    public Order $order;

    public function mount(string $uuid): void
    {
        $this->order = Order::with(['items.ticketType', 'tickets', 'event'])
            ->where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.order-confirmation');
    }
}
