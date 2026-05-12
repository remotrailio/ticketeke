<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">

    {{-- ── Success banner ─────────────────────────────────────────────────── --}}
    <div class="mb-8 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 p-8 text-center text-white shadow-lg">
        <svg class="mx-auto mb-3 h-14 w-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h1 class="text-2xl font-bold">You're going!</h1>
        <p class="mt-1 text-indigo-100">{{ $order->event->title }}</p>
        <p class="mt-3 text-xs text-indigo-200">Order #{{ $order->order_number }}</p>
    </div>

    {{-- ── Event details ───────────────────────────────────────────────────── --}}
    <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">Event details</h2>
        <div class="space-y-2 text-sm text-gray-700">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $order->event->starts_at?->format('D, d M Y · H:i') }}</span>
            </div>
            @if($order->event->venue_name)
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $order->event->venue_name }}@if($order->event->city), {{ $order->event->city }}@endif</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Tickets ─────────────────────────────────────────────────────────── --}}
    <div class="mb-6 rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                Your tickets ({{ $order->tickets->count() }})
            </h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($order->tickets as $ticket)
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $ticket->orderItem->ticketType->name }}</p>
                        <p class="mt-0.5 font-mono text-xs text-gray-400 tracking-widest">{{ $ticket->ticket_code }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                        Valid
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── Payment summary ─────────────────────────────────────────────────── --}}
    <div class="mb-8 rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Payment</h2>
        </div>
        <div class="space-y-2 px-6 py-4 text-sm text-gray-700">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>KES {{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->fees > 0)
                <div class="flex justify-between">
                    <span>Platform fee</span>
                    <span>KES {{ number_format($order->fees, 2) }}</span>
                </div>
            @endif
            @if($order->discount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Discount</span>
                    <span>− KES {{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between border-t border-gray-100 pt-2 font-semibold">
                <span>Total paid</span>
                <span class="text-indigo-600">KES {{ number_format($order->total, 2) }}</span>
            </div>
            @if($order->mpesa_receipt_number)
                <div class="flex justify-between text-xs text-gray-400 pt-1">
                    <span>M-Pesa receipt</span>
                    <span class="font-mono">{{ $order->mpesa_receipt_number }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Actions ─────────────────────────────────────────────────────────── --}}
    <div class="flex flex-col gap-3 sm:flex-row">
        <a href="{{ route('my.tickets') }}"
           class="flex flex-1 items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-700">
            View all my tickets
        </a>
        <a href="{{ route('events.show', $order->event->slug) }}"
           class="flex flex-1 items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Back to event
        </a>
    </div>

</div>
