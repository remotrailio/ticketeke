<article class="group flex flex-col overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition">
    <a href="{{ route('events.show', $event->slug) }}" class="block shrink-0">
        @if($event->banner_url)
            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}"
                 class="h-48 w-full object-cover group-hover:opacity-90 transition">
        @else
            <div class="flex h-48 w-full items-center justify-center bg-indigo-50 text-indigo-300">
                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-4">
        @if($event->category)
            <span class="mb-1 text-xs font-medium uppercase tracking-wide text-indigo-600">
                {{ $event->category->name }}
            </span>
        @endif

        <a href="{{ route('events.show', $event->slug) }}" class="mt-1 block">
            <h3 class="font-semibold text-gray-900 leading-snug group-hover:text-indigo-600 line-clamp-2">
                {{ $event->title }}
            </h3>
        </a>

        <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>{{ $event->start_at->format('D, d M Y · H:i') }}</span>
        </div>

        @if(!$event->is_online && $event->city)
        <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="truncate">{{ $event->venue_name ? $event->venue_name . ', ' : '' }}{{ $event->city }}</span>
        </div>
        @elseif($event->is_online)
        <div class="mt-1 flex items-center gap-2 text-xs text-indigo-500">
            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
            </svg>
            <span>Online</span>
        </div>
        @endif

        <div class="mt-auto pt-4 flex items-center justify-between">
            @if($event->organizer)
            <span class="text-xs text-gray-400">by {{ $event->organizer->display_name }}</span>
            @endif
            <a href="{{ route('events.show', $event->slug) }}"
               class="ml-auto rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700 transition">
                View
            </a>
        </div>
    </div>
</article>
