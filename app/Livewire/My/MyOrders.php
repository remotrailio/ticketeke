<?php

namespace App\Livewire\My;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app', ['title' => 'My Orders'])]
class MyOrders extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = Order::with(['event', 'items.ticketType'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('livewire.my.my-orders', compact('orders'));
    }
}
