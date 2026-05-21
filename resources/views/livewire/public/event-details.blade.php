<div>
    {{-- Banner --}}
    @if ($event->banner_url)
        <div class="relative h-72 w-full overflow-hidden sm:h-96">
            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
        </div>
    @endif

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-12">

            {{-- Main content --}}
            <div class="lg:col-span-2">
                {{-- Breadcrumb --}}
                <nav class="mb-6 flex items-center gap-2 text-sm text-slate-400">
                    <a href="{{ route('events.index') }}" class="hover:text-slate-600 transition-colors">Events</a>
                    @if ($event->category)
                        <span>/</span>
                        <a href="{{ route('events.index', ['category' => $event->category->slug]) }}"
                            class="hover:text-slate-600 transition-colors">{{ $event->category->name }}</a>
                    @endif
                    <span>/</span>
                    <span class="text-slate-600 line-clamp-1">{{ $event->title }}</span>
                </nav>

                {{-- Title --}}
                <h1 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">{{ $event->title }}</h1>

                @if ($event->excerpt)
                    <p class="mt-3 text-lg text-slate-600">{{ $event->excerpt }}</p>
                @endif

                {{-- Meta badges --}}
                <div class="mt-6 flex flex-wrap gap-3">
                    @if ($event->category)
                        <span class="inline-flex items-center rounded-full bg-indigo-50 border border-indigo-100 px-3 py-1 text-xs font-medium text-blue-700">
                            {{ $event->category->name }}
                        </span>
                    @endif
                    @if ($event->is_online)
                        <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                            Online
                        </span>
                    @endif
                </div>

                {{-- Date & Location --}}
                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3 text-sm text-slate-700">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="font-medium text-slate-900">{{ $event->start_at->format('l, d F Y') }}</p>
                            <p class="text-slate-500">
                                {{ $event->start_at->format('H:i') }}
                                @if ($event->end_at)
                                    – {{ $event->end_at->format('H:i') }}
                                @endif
                                ({{ $event->timezone ?? 'Africa/Nairobi' }})
                            </p>
                        </div>
                    </div>

                    @if ($event->is_online)
                        <div class="flex items-center gap-3 text-sm text-slate-700">
                            <svg class="h-5 w-5 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                            </svg>
                            <span class="font-medium">Online Event</span>
                        </div>
                    @elseif($event->venue_name || $event->city)
                        <div class="flex items-start gap-3 text-sm text-slate-700">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                @if ($event->venue_name)
                                    <p class="font-medium text-slate-900">{{ $event->venue_name }}</p>
                                @endif
                                @if ($event->venue_address)
                                    <p class="text-slate-500">{{ $event->venue_address }}</p>
                                @endif
                                @if ($event->city)
                                    <p class="text-slate-500">{{ $event->city }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                @if ($event->description)
                    <div class="mt-10 prose prose-sm max-w-none text-slate-700">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                @endif

                {{-- Organizer --}}
                @if ($event->organizer)
                    <div class="mt-10 rounded-2xl border border-slate-200 bg-indigo-50 p-5 flex items-center gap-4">
                        @if ($event->organizer->logo_url)
                            <img src="{{ $event->organizer->logo_url }}" alt="{{ $event->organizer->display_name }}"
                                class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm">
                        @else
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 font-bold text-lg border-2 border-white shadow-sm">
                                {{ mb_substr($event->organizer->display_name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wide">Organised by</p>
                            <a href="{{ route('organizers.show', $event->organizer->slug) }}"
                                class="font-semibold text-slate-900 hover:text-blue-600 transition-colors">
                                {{ $event->organizer->display_name }}
                            </a>
                            @if ($event->organizer->bio)
                                <p class="mt-0.5 text-xs text-slate-500 line-clamp-2">{{ $event->organizer->bio }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Ticket sidebar --}}
            <div class="mt-10 lg:mt-0">
                <div class="sticky top-6">
                    @livewire('public.ticket-selector', ['event' => $event], key($event->id))
                </div>
            </div>
        </div>
    </div>
</div>
