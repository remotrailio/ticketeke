@php
    $lowestPrice = $event->ticketTypes->min('price');
    $showFeatured = $featured ?? false;
    $eventUrl = route('events.show', $event->slug);
@endphp

<a href="{{ $eventUrl }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/60">
    <div class="relative aspect-16/10 overflow-hidden">
        @if ($event->banner_url)
            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-linear-to-br from-indigo-50 to-blue-100">
                <svg class="h-12 w-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        @if ($showFeatured)
            <span class="absolute left-3 top-3 rounded-full bg-blue-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                Featured
            </span>
        @endif

        @if ($event->category)
            <span class="absolute right-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-slate-700 backdrop-blur-sm shadow-sm">
                {{ $event->category->name }}
            </span>
        @endif
    </div>

    <div class="p-4 space-y-3">
        <h3 class="line-clamp-2 min-h-12 font-semibold text-slate-900">{{ $event->title }}</h3>
        <div class="space-y-2">
            <div class="flex items-start gap-2 text-sm text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0 text-blue-400" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M8 2v4"></path>
                    <path d="M16 2v4"></path>
                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                    <path d="M3 10h18"></path>
                </svg>
                <span>{{ $event->start_at->format('D, d M Y · H:i') }}</span>
            </div>

            @if ($event->is_online)
                <div class="flex items-center gap-2 text-xs text-blue-500">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                    </svg>
                    <span>Online</span>
                </div>
            @elseif($event->city)
                <div class="flex items-start gap-2 text-sm text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0 text-blue-400" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span class="line-clamp-1">
                        {{ $event->venue_name ? $event->venue_name . ', ' : '' }}{{ $event->city }}
                    </span>
                </div>
            @endif

            @if (($event->attendees_count ?? 0) > 0)
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-blue-400" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>{{ number_format($event->attendees_count) }} attending</span>
            </div>
            @endif
        </div>

        <div class="pt-2 border-t border-slate-100">
            @if (is_null($lowestPrice) || $lowestPrice == 0)
                <span class="text-sm font-bold text-emerald-600">Free</span>
            @else
                <span class="text-sm text-slate-400">From</span>
                <span class="text-lg font-semibold text-slate-900"> KES {{ number_format($lowestPrice) }}</span>
            @endif
        </div>
    </div>
</a>
