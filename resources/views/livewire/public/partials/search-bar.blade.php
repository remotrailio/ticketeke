<div class="relative mt-6 max-w-2xl">
    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
        </svg>
    </div>
    <input wire:model.live.debounce.300ms="search"
           type="search"
           placeholder="Search events, experiences, safaris..."
           class="h-12 w-full rounded-xl border border-gray-200 bg-white pl-12 pr-4 text-sm shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-400/20">
    <div wire:loading wire:target="search"
         class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
        <svg class="h-4 w-4 animate-spin text-indigo-400" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
        </svg>
    </div>
</div>
