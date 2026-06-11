<div>
    <div class="bg-gray-50 border-b border-gray-100 py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="mt-1 text-sm text-gray-500">Your order history.</p>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        @if($orders->isNotEmpty())
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $order->event?->title ?? 'Unknown Event' }}</p>
                        <p class="mt-0.5 text-xs text-gray-400">
                            Order #{{ $order->id }}
                            · {{ $order->created_at->format('d M Y') }}
                        </p>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="font-semibold text-gray-900">
                            {{ strtoupper($order->currency) }} {{ number_format($order->total, 2) }}
                        </p>
                        <span @class([
                            'inline-block mt-1 rounded-full px-2.5 py-0.5 text-xs font-medium',
                            'bg-green-50 text-green-700'   => $order->payment_status->value === 'paid',
                            'bg-amber-50 text-amber-700'   => $order->payment_status->value === 'unpaid',
                            'bg-red-50 text-red-600'       => $order->payment_status->value === 'failed',
                            'bg-gray-100 text-gray-600'    => $order->payment_status->value === 'refunded',
                        ])>
                            {{ ucfirst($order->payment_status->value) }}
                        </span>
                    </div>
                </div>

                @if($order->items->isNotEmpty())
                <div class="mt-3 border-t border-gray-50 pt-3 space-y-1">
                    @foreach($order->items as $item)
                    <p class="text-sm text-gray-600">
                        {{ $item->ticketType?->name }} × {{ $item->quantity }}
                        <span class="text-gray-400">— {{ strtoupper($order->currency) }} {{ number_format($item->subtotal, 2) }}</span>
                    </p>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $orders->links() }}</div>
        @else
        <div class="rounded-xl border border-dashed border-gray-200 py-20 text-center text-gray-400">
            <p class="text-sm font-medium">No orders yet.</p>
            <a href="{{ route('events.index') }}" class="mt-3 inline-block text-xs text-indigo-600 hover:underline">
                Browse events →
            </a>
        </div>
        @endif
    </div>
</div>
