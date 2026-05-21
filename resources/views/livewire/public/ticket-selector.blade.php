<div class="rounded-2xl border border-slate-200 bg-white shadow-lg shadow-slate-200/50">
    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="text-base font-semibold text-slate-900">Select Tickets</h2>
    </div>

    <div class="divide-y divide-slate-100">
        @forelse($ticketTypes as $type)
        <div class="px-5 py-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-slate-900 leading-snug">{{ $type->name }}</p>
                    @if($type->description)
                    <p class="mt-0.5 text-xs text-slate-400 line-clamp-2">{{ $type->description }}</p>
                    @endif
                    <p class="mt-1 text-sm font-semibold text-blue-600">
                        @if($type->price > 0)
                            {{ strtoupper($type->currency) }} {{ number_format($type->price, 2) }}
                            @if($type->isGroupTicket())
                                <span class="ml-1 text-xs font-normal text-slate-400">/ group of {{ $type->group_size }}</span>
                            @endif
                        @else
                            Free
                        @endif
                    </p>
                    @if($type->isGroupTicket())
                    <span class="mt-1 inline-block rounded-full bg-amber-50 border border-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">
                        Group ticket ({{ $type->group_size }} people)
                    </span>
                    @endif
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    <button wire:click="decrement({{ $type->id }})"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:border-blue-400 hover:text-blue-600 transition-all disabled:opacity-40"
                            @if(($quantities[$type->id] ?? 0) <= 0) disabled @endif>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>

                    <span class="w-8 text-center text-sm font-semibold text-slate-900">
                        {{ $quantities[$type->id] ?? 0 }}
                    </span>

                    <button wire:click="increment({{ $type->id }})"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:border-blue-400 hover:text-blue-600 transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="px-5 py-8 text-center text-sm text-slate-400">
            No tickets are available for this event yet.
        </div>
        @endforelse
    </div>

    @if($hasSelection)
    <div class="border-t border-slate-100 bg-slate-50 px-5 py-4 space-y-2 rounded-b-2xl">
        <div class="flex justify-between text-sm text-slate-600">
            <span>Subtotal</span>
            <span>{{ $currency }} {{ number_format($subtotal, 2) }}</span>
        </div>
        @if($fee > 0)
        <div class="flex justify-between text-sm text-slate-500">
            <span>Platform fee</span>
            <span>{{ $currency }} {{ number_format($fee, 2) }}</span>
        </div>
        @endif
        <div class="flex justify-between border-t border-slate-200 pt-2 text-base font-bold text-slate-900">
            <span>Total</span>
            <span>{{ $currency }} {{ number_format($total, 2) }}</span>
        </div>
    </div>
    @endif

    <div class="px-5 pb-5 {{ $hasSelection ? 'pt-3' : 'pt-5' }}">
        <button wire:click="proceedToCheckout"
                @if(!$hasSelection) disabled @endif
                class="w-full rounded-xl bg-blue-600 py-3 text-sm font-semibold text-white hover:bg-violet-500 transition-colors disabled:cursor-not-allowed disabled:opacity-50 shadow-sm">
            @if($hasSelection)
                Proceed to Checkout
            @else
                Select tickets to continue
            @endif
        </button>
    </div>
</div>
