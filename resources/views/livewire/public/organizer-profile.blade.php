<div>
    {{-- Banner --}}
    @php
        $bannerUrl = $organizer->banner
            ? \Illuminate\Support\Facades\Storage::disk('r2')->url($organizer->banner)
            : null;
    @endphp
    <div class="relative h-56 w-full overflow-hidden sm:h-64" style="background: linear-gradient(135deg, #1d4ed8, #2563EB, #7c3aed);">
        @if($bannerUrl)
            <img src="{{ $bannerUrl }}" alt="{{ $organizer->display_name }}"
                 class="h-full w-full object-cover opacity-60">
        @endif
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Organizer header --}}
        <div class="relative -mt-16 mb-8 flex items-end gap-5">
            <div class="shrink-0">
                @if($organizer->logo_url)
                    <img src="{{ $organizer->logo_url }}" alt="{{ $organizer->display_name }}"
                         class="h-28 w-28 rounded-2xl border-4 border-white object-cover shadow-lg shadow-slate-200/60">
                @else
                    <div class="flex h-28 w-28 items-center justify-center rounded-2xl border-4 border-white bg-indigo-100 text-3xl font-bold text-blue-600 shadow-lg shadow-slate-200/60">
                        {{ mb_substr($organizer->display_name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="pb-2">
                <h1 class="text-2xl font-extrabold text-slate-900">{{ $organizer->display_name }}</h1>
                @if($organizer->website)
                <a href="{{ $organizer->website }}" target="_blank" rel="noopener"
                   class="mt-1 inline-flex items-center gap-1 text-sm text-blue-600 hover:text-violet-500 transition-colors">
                    {{ parse_url($organizer->website, PHP_URL_HOST) }}
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
                @endif
            </div>
        </div>

        @if($organizer->bio)
        <p class="mb-10 max-w-2xl text-slate-600">{{ $organizer->bio }}</p>
        @endif

        <h2 class="mb-6 text-xl font-bold text-slate-900">Upcoming Events</h2>

        @if($events->isNotEmpty())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($events as $event)
                @include('livewire.public.partials.event-card', ['event' => $event])
            @endforeach
        </div>
        <div class="mt-10">
            {{ $events->links() }}
        </div>
        @else
        <div class="rounded-2xl border border-dashed border-slate-200 bg-white py-16 text-center text-slate-400">
            <p class="text-sm">No upcoming events from this organizer.</p>
        </div>
        @endif

        <div class="pb-16"></div>
    </div>
</div>
