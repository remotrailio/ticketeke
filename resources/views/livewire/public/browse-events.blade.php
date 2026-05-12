<div>
    {{-- Page header --}}
    <div class="bg-gray-50 border-b border-gray-100 py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Browse Events</h1>
            <p class="mt-1 text-sm text-gray-500">Find your next unforgettable experience.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Filters --}}
        <div class="mb-8 flex flex-wrap gap-3">
            {{-- Search --}}
            <div class="relative flex-1 min-w-48">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="search"
                       placeholder="Search events…"
                       class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-9 pr-4 text-sm shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-1 focus:ring-indigo-400">
            </div>

            {{-- Category --}}
            <select wire:model.live="category"
                    class="rounded-lg border border-gray-200 bg-white py-2.5 px-4 text-sm shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-1 focus:ring-indigo-400">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            {{-- City --}}
            <input wire:model.live.debounce.300ms="city"
                   type="text"
                   placeholder="City…"
                   class="w-36 rounded-lg border border-gray-200 bg-white py-2.5 px-4 text-sm shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-1 focus:ring-indigo-400">

            {{-- Sort --}}
            <select wire:model.live="sort"
                    class="rounded-lg border border-gray-200 bg-white py-2.5 px-4 text-sm shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-1 focus:ring-indigo-400">
                <option value="start_at">Upcoming First</option>
                <option value="published_at">Newest</option>
            </select>
        </div>

        {{-- Results count --}}
        <p class="mb-4 text-sm text-gray-500">
            {{ $events->total() }} {{ Str::plural('event', $events->total()) }} found
        </p>

        {{-- Grid --}}
        @if($events->isNotEmpty())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($events as $event)
                @include('livewire.public.partials.event-card', ['event' => $event])
            @endforeach
        </div>

        <div class="mt-10">
            {{ $events->links() }}
        </div>
        @else
        <div class="rounded-xl border border-dashed border-gray-200 py-20 text-center text-gray-400">
            <svg class="mx-auto mb-3 h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-medium">No events match your filters.</p>
            <button wire:click="$set('search', ''); $set('category', ''); $set('city', '')"
                    class="mt-3 text-xs text-indigo-600 hover:underline">
                Clear all filters
            </button>
        </div>
        @endif
    </div>
</div>
