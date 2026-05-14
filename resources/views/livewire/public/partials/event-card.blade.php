@php
    $lowestPrice  = $event->ticketTypes->min('price');
    $totalSold    = $event->ticketTypes->sum('sold');
    $showFeatured = $featured ?? false;
@endphp

<article class="group flex flex-col overflow-hidden rounded-xl border border-gray-100 bg-white cursor-pointer
                 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

    {{-- Image --}}
    <a href="{{ route('events.show', $event->slug) }}" class="relative block aspect-[16/10] overflow-hidden">
        @if($event->banner_url)
            <img src="{{ $event->banner_url }}"
                 alt="{{ $event->title }}"
                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-100 to-indigo-200">
                <svg class="h-12 w-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        {{-- Featured badge (top left) --}}
        @if($showFeatured)
        <span class="absolute left-3 top-3 rounded-full bg-indigo-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
            Featured
        </span>
        @endif

        {{-- Category badge (top right) --}}
        @if($event->category)
        <span class="absolute right-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-gray-700 backdrop-blur-sm shadow-sm">
            {{ $event->category->name }}
        </span>
        @endif
    </a>

    {{-- Content --}}
    <div class="flex flex-1 flex-col p-4">

        {{-- Title --}}
        <a href="{{ route('events.show', $event->slug) }}" class="mb-3 block min-h-11">
            <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-gray-900 transition-colors group-hover:text-indigo-600">
                {{ $event->title }}
            </h3>
        </a>

        {{-- Meta rows --}}
        <div class="space-y-1.5">

            {{-- Date --}}
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                    <line x1="16" x2="16" y1="2" y2="6"/>
                    <line x1="8" x2="8" y1="2" y2="6"/>
                    <line x1="3" x2="21" y1="10" y2="10"/>
                </svg>
                <span>{{ $event->start_at->format('D, d M Y · H:i') }}</span>
            </div>

            {{-- Location --}}
            @if($event->is_online)
            <div class="flex items-center gap-2 text-xs text-indigo-500">
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
                <span>Online</span>
            </div>
            @elseif($event->city)
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="truncate">{{ $event->venue_name ? $event->venue_name . ', ' : '' }}{{ $event->city }}</span>
            </div>
            @endif

            {{-- Attendees --}}
            @if($totalSold > 0)
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5.477-3.75M9 20H4v-2a4 4 0 015.477-3.75M15 8a4 4 0 11-8 0 4 4 0 018 0zm6 4a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ number_format($totalSold) }} {{ Str::plural('attendee', $totalSold) }}</span>
            </div>
            @endif

        </div>

        {{-- Price footer --}}
        <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-4">
            <div>
                @if(is_null($lowestPrice) || $lowestPrice == 0)
                    <span class="text-sm font-bold text-green-600">Free</span>
                @else
                    <span class="text-xs text-gray-400">From</span>
                    <span class="ml-1 text-sm font-bold text-gray-900">KES {{ number_format($lowestPrice) }}</span>
                @endif
            </div>
            <a href="{{ route('events.show', $event->slug) }}"
               class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-indigo-700">
                Get Tickets
            </a>
        </div>

    </div>
</article>
