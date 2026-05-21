@php
    $cities = ['Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret', 'Lamu', 'Diani'];
    $dates  = [
        ''           => 'Any date',
        'today'      => 'Today',
        'tomorrow'   => 'Tomorrow',
        'this_week'  => 'This week',
        'this_month' => 'This month',
    ];
@endphp

<div class="space-y-6">

    @if($activeFilterCount > 0)
    <button wire:click="clearFilters"
            class="text-xs font-medium text-blue-600 hover:text-violet-500 transition-colors">
        Clear all filters ({{ $activeFilterCount }})
    </button>
    @endif

    {{-- Category --}}
    <div>
        <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Category</h3>
        <div class="space-y-2">
            @foreach($categories as $cat)
            <label class="flex cursor-pointer items-center gap-2.5">
                <input type="checkbox"
                       wire:model.live="selectedCategories"
                       value="{{ $cat->slug }}"
                       class="h-4 w-4 cursor-pointer accent-blue-600 rounded border-slate-300">
                <span class="text-sm text-slate-700">{{ $cat->name }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <div class="border-t border-slate-100"></div>

    {{-- City --}}
    <div>
        <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">City</h3>
        <div class="space-y-2">
            @foreach($cities as $city)
            <label class="flex cursor-pointer items-center gap-2.5">
                <input type="checkbox"
                       wire:model.live="selectedCities"
                       value="{{ $city }}"
                       class="h-4 w-4 cursor-pointer accent-blue-600 rounded border-slate-300">
                <span class="text-sm text-slate-700">{{ $city }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <div class="border-t border-slate-100"></div>

    {{-- Date --}}
    <div>
        <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Date</h3>
        <div class="space-y-2">
            @foreach($dates as $value => $label)
            <label class="flex cursor-pointer items-center gap-2.5">
                <input type="radio"
                       wire:model.live="selectedDate"
                       value="{{ $value }}"
                       class="h-4 w-4 cursor-pointer accent-blue-600 border-slate-300">
                <span class="text-sm text-slate-700">{{ $label }}</span>
            </label>
            @endforeach
        </div>
    </div>

</div>
