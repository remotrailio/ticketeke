<section class="py-20" style="background: linear-gradient(135deg, #1d4ed8, #2563EB, #7c3aed);">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mx-auto max-w-3xl text-center">

            {{-- Badge --}}
            <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1.5 text-sm font-medium text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
                For Event Organizers
            </div>

            {{-- Heading --}}
            <h2 class="mb-4 text-3xl font-bold leading-tight text-white md:text-4xl">
                Ready to Host Your Own Event?
            </h2>

            {{-- Subtitle --}}
            <p class="mb-10 text-xl text-white/90">
                Join organizers across Kenya using {{ $__settings->app_name }} to reach new audiences,
                sell tickets, and manage their events — all in one place.
            </p>

            {{-- Feature pills --}}
            <div class="mb-10 flex flex-wrap items-center justify-center gap-x-8 gap-y-3">
                @foreach (['Easy setup', 'Secure payments', '24 / 7 support', 'Real-time analytics'] as $feature)
                    <div class="flex items-center gap-2 text-sm font-medium text-white">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/25">
                            <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </span>
                        {{ $feature }}
                    </div>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ route('organizers.become') }}#signup"
                    class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3 text-sm font-semibold text-blue-700 shadow-lg transition-all hover:bg-indigo-50 hover:shadow-xl">
                    Become an Organizer
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7 7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
