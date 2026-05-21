<div x-data="{ filtersOpen: false }">

    {{-- Hero / Search header --}}
    <div class="border-b border-slate-200 bg-white shadow-sm">
        <div class="container mx-auto px-4 py-10 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Browse Events</h1>
            <p class="mt-1 text-sm text-slate-500">Find your next unforgettable experience in Kenya.</p>
            @include('livewire.public.partials.search-bar')
        </div>
    </div>

    {{-- Page body --}}
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex gap-8">

            {{-- Sidebar (desktop) --}}
            <aside class="hidden w-60 shrink-0 lg:block">
                <div class="sticky top-20">
                    @include('livewire.public.partials.filters-sidebar', [
                        'categories'        => $categories,
                        'activeFilterCount' => $activeFilterCount,
                    ])
                </div>
            </aside>

            {{-- Main content --}}
            <div class="min-w-0 flex-1">

                {{-- Mobile toolbar --}}
                <div class="mb-5 flex items-center justify-between lg:hidden">
                    <button @click="filtersOpen = true"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:border-blue-300 hover:bg-blue-50 transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 8h10M10 12h4"/>
                        </svg>
                        Filters
                        @if($activeFilterCount > 0)
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                                {{ $activeFilterCount }}
                            </span>
                        @endif
                    </button>

                    <select wire:model.live="sort"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="start_at">Upcoming first</option>
                        <option value="published_at">Newest</option>
                    </select>
                </div>

                {{-- Desktop sort + count --}}
                <div class="mb-5 hidden items-center justify-between lg:flex">
                    <p class="text-sm text-slate-500">
                        <span class="font-semibold text-slate-900">{{ $events->total() }}</span>
                        {{ Str::plural('event', $events->total()) }} found
                    </p>
                    <select wire:model.live="sort"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="start_at">Upcoming first</option>
                        <option value="published_at">Newest</option>
                    </select>
                </div>

                {{-- Mobile count --}}
                <p class="mb-4 text-sm text-slate-500 lg:hidden">
                    <span class="font-semibold text-slate-900">{{ $events->total() }}</span>
                    {{ Str::plural('event', $events->total()) }} found
                </p>

                {{-- Active filter chips --}}
                @if($activeFilterCount > 0)
                <div class="mb-5 flex flex-wrap gap-2">
                    @foreach($selectedCategories as $slug)
                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-medium text-blue-700">
                        {{ $categories->firstWhere('slug', $slug)?->name ?? $slug }}
                    </span>
                    @endforeach
                    @foreach($selectedCities as $city)
                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-medium text-blue-700">
                        {{ $city }}
                    </span>
                    @endforeach
                    @if($selectedDate)
                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-medium text-blue-700">
                        {{ ['today' => 'Today', 'tomorrow' => 'Tomorrow', 'this_week' => 'This week', 'this_month' => 'This month'][$selectedDate] ?? '' }}
                    </span>
                    @endif
                    <button wire:click="clearFilters" class="text-xs text-slate-400 underline underline-offset-2 hover:text-slate-600 transition-colors">
                        Clear all
                    </button>
                </div>
                @endif

                {{-- Event grid --}}
                @if($events->isNotEmpty())
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($events as $event)
                            @include('livewire.public.partials.event-card', ['event' => $event])
                        @endforeach
                    </div>

                    @if($events->hasMorePages())
                    <div class="mt-10 flex justify-center">
                        <button wire:click="nextPage"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-8 py-3 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-blue-300 hover:bg-blue-50">
                            <svg wire:loading wire:target="nextPage" class="h-4 w-4 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                            Load More Events
                        </button>
                    </div>
                    @endif
                @else
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-white py-24 text-center">
                        <svg class="mx-auto mb-4 h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-medium text-slate-500">No events match your filters.</p>
                        <p class="mt-1 text-xs text-slate-400">Try broadening your search or removing some filters.</p>
                        <button wire:click="clearFilters"
                                class="mt-4 rounded-xl bg-blue-600 px-5 py-2 text-xs font-semibold text-white hover:bg-violet-500 transition-colors">
                            Clear Filters
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Mobile filter slide-over --}}
    <div x-show="filtersOpen"
         @click="filtersOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-slate-900/30 backdrop-blur-sm lg:hidden"
         style="display: none;">

        <div @click.stop
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="flex h-full w-80 flex-col bg-white shadow-2xl shadow-slate-200">

            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4">
                <h2 class="text-base font-semibold text-slate-900">Filters</h2>
                <button @click="filtersOpen = false"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-4">
                @include('livewire.public.partials.filters-sidebar', [
                    'categories'        => $categories,
                    'activeFilterCount' => $activeFilterCount,
                ])
            </div>

            <div class="border-t border-slate-100 p-4">
                <button @click="filtersOpen = false"
                        class="w-full rounded-xl bg-blue-600 py-3 text-sm font-semibold text-white hover:bg-violet-500 transition-colors">
                    Show {{ $events->total() }} {{ Str::plural('Event', $events->total()) }}
                </button>
            </div>
        </div>
    </div>

</div>
