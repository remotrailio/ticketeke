<div>
    <div class="bg-gray-50 border-b border-gray-100 py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">My Tickets</h1>
            <p class="mt-1 text-sm text-gray-500">All tickets from your orders.</p>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        @if($tickets->isNotEmpty())
        <div class="space-y-4">
            @foreach($tickets as $ticket)
            @php $event = $ticket->order?->event; @endphp
            <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 truncate">{{ $event?->title ?? 'Unknown Event' }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $ticket->orderItem?->ticketType?->name }}
                        · #{{ $ticket->ticket_code }}
                    </p>
                    @if($event?->starts_at)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $event->starts_at->format('D, d M Y · H:i') }}</p>
                    @endif
                </div>
                <div class="shrink-0 text-right">
                    @php $status = $ticket->status; @endphp
                    <span @class([
                        'inline-block rounded-full px-2.5 py-0.5 text-xs font-medium',
                        'bg-green-50 text-green-700' => $status->value === 'valid',
                        'bg-gray-100 text-gray-600'  => $status->value === 'used',
                        'bg-red-50 text-red-600'     => $status->value === 'cancelled',
                    ])>
                        {{ ucfirst($status->value) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $tickets->links() }}</div>
        @else
        <div class="rounded-xl border border-dashed border-gray-200 py-20 text-center text-gray-400">
            <svg class="mx-auto mb-3 h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
            <p class="text-sm font-medium">No tickets yet.</p>
            <a href="{{ route('events.index') }}" class="mt-3 inline-block text-xs text-indigo-600 hover:underline">
                Browse events →
            </a>
        </div>
        @endif
    </div>
</div>
