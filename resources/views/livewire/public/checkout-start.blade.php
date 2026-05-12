<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    <div class="mb-8">
        <a href="{{ route('events.show', $event->slug) }}"
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to event
        </a>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Complete your order</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $event->title }}</p>
    </div>

    {{-- ── Success state ───────────────────────────────────────────────────── --}}
    @if($state === 'success')
        <div class="rounded-2xl border border-green-100 bg-green-50 p-8 text-center">
            <svg class="mx-auto mb-4 h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="text-xl font-bold text-green-800">Payment confirmed!</h2>
            <p class="mt-2 text-sm text-green-700">Your tickets are ready.</p>

            @if($order)
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <a href="{{ route('orders.confirmation', $order->uuid) }}"
                       class="inline-flex justify-center rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                        View tickets
                    </a>
                    <a href="{{ route('my.tickets') }}"
                       class="inline-flex justify-center rounded-lg border border-green-300 bg-white px-6 py-2.5 text-sm font-semibold text-green-700 hover:bg-green-50">
                        My tickets
                    </a>
                </div>
            @endif
        </div>

    {{-- ── Failed state ────────────────────────────────────────────────────── --}}
    @elseif($state === 'failed')
        <div class="rounded-2xl border border-red-100 bg-red-50 p-8 text-center">
            <svg class="mx-auto mb-4 h-16 w-16 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="text-xl font-bold text-red-800">Payment failed</h2>
            <p class="mt-2 text-sm text-red-700">{{ $errorMessage }}</p>

            <button wire:click="retry"
                    class="mt-6 inline-flex items-center rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                Try again
            </button>
        </div>

    {{-- ── Polling state ───────────────────────────────────────────────────── --}}
    @elseif($state === 'polling')
        <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-8 text-center"
             wire:poll.3000ms="poll">
            <svg class="mx-auto mb-4 h-16 w-16 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <h2 class="text-xl font-bold text-indigo-800">Waiting for payment</h2>
            <p class="mt-2 text-sm text-indigo-700">
                Check your phone for the M-Pesa PIN prompt and enter your PIN to complete payment.
            </p>
            <p class="mt-4 text-xs text-indigo-500">This page will update automatically…</p>
        </div>

    {{-- ── Processing state ────────────────────────────────────────────────── --}}
    @elseif($state === 'processing')
        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-8 text-center">
            <svg class="mx-auto mb-4 h-16 w-16 animate-pulse text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="text-xl font-bold text-gray-700">Initiating payment…</h2>
            <p class="mt-2 text-sm text-gray-500">Please wait while we contact M-Pesa.</p>
        </div>

    {{-- ── Idle (default) state ────────────────────────────────────────────── --}}
    @else
        <div class="space-y-6">

            {{-- Order summary --}}
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Order summary</h2>
                </div>
                <div class="divide-y divide-gray-50 px-6">
                    @foreach($itemSummary as $line)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <span class="text-sm font-medium text-gray-800">{{ $line['name'] }}</span>
                                <span class="ml-2 text-sm text-gray-400">× {{ $line['quantity'] }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">
                                KES {{ number_format($line['subtotal'], 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-2 rounded-b-2xl bg-gray-50 px-6 py-4">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>{{ $currency }} {{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($fee > 0)
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>Platform fee</span>
                        <span>{{ $currency }} {{ number_format($fee, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                        <span class="font-semibold text-gray-800">Total</span>
                        <span class="text-lg font-bold text-indigo-600">
                            {{ $currency }} {{ number_format($total, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Phone number input --}}
            <div class="rounded-2xl border border-gray-100 bg-white px-6 py-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">M-Pesa payment</h2>

                <label for="phone" class="block text-sm font-medium text-gray-700">
                    M-Pesa phone number
                </label>
                <div class="mt-1.5 flex rounded-lg shadow-sm ring-1 ring-gray-300 focus-within:ring-2 focus-within:ring-indigo-500">
                    <span class="inline-flex items-center rounded-l-lg border-r border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">
                        +254
                    </span>
                    <input
                        id="phone"
                        type="tel"
                        wire:model="phone"
                        placeholder="7XXXXXXXX"
                        class="block w-full rounded-r-lg border-0 bg-transparent py-2.5 pl-3 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:ring-0"
                    />
                </div>
                @error('phone')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-2 text-xs text-gray-400">
                    You will receive a PIN prompt on this number. Enter your M-Pesa PIN to pay.
                </p>
            </div>

            {{-- Pay button --}}
            <button
                wire:click="pay"
                wire:loading.attr="disabled"
                wire:target="pay"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow hover:bg-indigo-700 disabled:opacity-60"
            >
                <span wire:loading.remove wire:target="pay">
                    Pay {{ $currency }} {{ number_format($total, 2) }} via M-Pesa
                </span>
                <span wire:loading wire:target="pay" class="inline-flex items-center gap-2">
                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Processing…
                </span>
            </button>

            <p class="text-center text-xs text-gray-400">
                Secured by Safaricom M-Pesa. Your payment is encrypted and safe.
            </p>
        </div>
    @endif

</div>
