<div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-100 px-5 py-4">
        <h2 class="text-base font-semibold text-gray-900">Select Tickets</h2>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($event->ticketTypes->where('is_active', true) as $type)
        <div class="px-5 py-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 leading-snug">{{ $type->name }}</p>
                    @if($type->description)
                    <p class="mt-0.5 text-xs text-gray-400 line-clamp-2">{{ $type->description }}</p>
                    @endif
                    <p class="mt-1 text-sm font-semibold text-indigo-600">
                        @if($type->price > 0)
                            {{ strtoupper($type->currency) }} {{ number_format($type->price, 2) }}
                            @if($type->isGroupTicket())
                                <span class="ml-1 text-xs font-normal text-gray-400">/ group of {{ $type->group_size }}</span>
                            @endif
                        @else
                            Free
                        @endif
                    </p>
                    @if($type->isGroupTicket())
                    <span class="mt-1 inline-block rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">
                        Group ticket ({{ $type->group_size }} people)
                    </span>
                    @endif
                </div>

                {{-- Quantity controls --}}
                <div class="flex shrink-0 items-center gap-2">
                    <button wire:click="decrement({{ $type->id }})"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-900 transition disabled:opacity-40"
                            @if(($quantities[$type->id] ?? 0) <= 0) disabled @endif>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>

                    <span class="w-8 text-center text-sm font-semibold text-gray-900">
                        {{ $quantities[$type->id] ?? 0 }}
                    </span>

                    <button wire:click="increment({{ $type->id }})"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-900 transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="px-5 py-8 text-center text-sm text-gray-400">
            No tickets are available for this event yet.
        </div>
        @endforelse
    </div>

    {{-- Order summary --}}
    @if($this->hasSelection())
    <div class="border-t border-gray-100 bg-gray-50 px-5 py-4 space-y-2 rounded-b-xl">
        <div class="flex justify-between text-sm text-gray-600">
            <span>Subtotal</span>
            <span>{{ $this->currency }} {{ number_format($this->subtotal, 2) }}</span>
        </div>
        @if($this->fee > 0)
        <div class="flex justify-between text-sm text-gray-500">
            <span>Platform fee</span>
            <span>{{ $this->currency }} {{ number_format($this->fee, 2) }}</span>
        </div>
        @endif
        <div class="flex justify-between border-t border-gray-200 pt-2 text-base font-bold text-gray-900">
            <span>Total</span>
            <span>{{ $this->currency }} {{ number_format($this->total, 2) }}</span>
        </div>
    </div>
    @endif

    {{-- CTA --}}
    <div class="px-5 pb-5 @if($this->hasSelection()) pt-3 @else pt-5 @endif">
        <button wire:click="proceedToCheckout"
                @if(!$this->hasSelection()) disabled @endif
                class="w-full rounded-lg bg-indigo-600 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition disabled:cursor-not-allowed disabled:opacity-50">
            @if($this->hasSelection())
                Proceed to Checkout
            @else
                Select tickets to continue
            @endif
        </button>
    </div>
</div>
