<div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8 text-center">
    <svg class="mx-auto mb-4 h-12 w-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
    <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
    <p class="mt-2 text-gray-500">{{ $event->title }}</p>

    <div class="mt-8 rounded-xl border border-gray-100 bg-gray-50 p-6 text-left space-y-3">
        @foreach($items as $typeId => $qty)
        <div class="flex justify-between text-sm text-gray-700">
            <span>Ticket #{{ $typeId }} × {{ $qty }}</span>
        </div>
        @endforeach
    </div>

    <p class="mt-8 text-sm text-gray-400">Full checkout flow coming soon.</p>

    <a href="{{ route('events.show', $event->slug) }}"
       class="mt-4 inline-block text-sm text-indigo-600 hover:underline">
        ← Back to event
    </a>
</div>
