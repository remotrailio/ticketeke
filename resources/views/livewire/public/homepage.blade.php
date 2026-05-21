<div>
    {{-- Hero --}}
    <section class="relative py-24 md:py-32" style="background-image: linear-gradient(rgba(0,0,0,0.50), rgba(0,0,0,0.50)), url('https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=1600'); background-size: cover; background-position: center;">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center text-white">
                <h1 class="mb-6 font-heading text-4xl font-bold tracking-tight md:text-6xl">
                    Discover Amazing Events &amp; Experiences in Kenya
                </h1>

                <p class="mb-8 text-xl text-white/90 md:text-2xl">
                    From safaris to music festivals, explore the best events and create unforgettable memories
                </p>

                <div class="relative mx-auto mb-6">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3" />
                        </svg>
                    </div>
                    <form action="{{ route('events.index') }}" method="GET">
                        <input type="search" name="q" placeholder="Search events, experiences, safaris..."
                            class="flex w-full rounded-xl border border-slate-200 bg-white px-3 pl-12 h-12 text-base text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                    </form>
                </div>

                @if ($heroCategories->isNotEmpty())
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        @foreach ($heroCategories as $cat)
                            <a href="{{ route('events.index', ['selectedCategories[]' => $cat->slug]) }}"
                                class="rounded-lg border border-white/30 bg-white/20 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm transition-all hover:bg-white/30">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Categories --}}
    @if ($categories->isNotEmpty())
        <section class="py-12 bg-white border-b border-slate-100">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-lg font-semibold text-slate-700 mb-6">Browse by Category</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach ($categories as $cat)
                        <a href="{{ route('events.index', ['category' => $cat->slug]) }}"
                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured Events --}}
    @if ($featured->isNotEmpty())
        <section class="py-16 bg-slate-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-end justify-between">
                    <div>
                        <h2 class="font-heading text-2xl font-bold text-slate-900">Featured Events</h2>
                        <p class="mt-1 text-sm text-slate-500">Hand-picked experiences you don't want to miss</p>
                    </div>
                    <a href="{{ route('events.index') }}" class="hidden sm:inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-blue-300 hover:text-blue-600 h-9">
                        View All
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($featured as $event)
                        @include('livewire.public.partials.event-card', ['event' => $event, 'featured' => true])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Upcoming Events --}}
    @if ($upcoming->isNotEmpty())
        <section class="py-16 bg-indigo-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">Upcoming Events</h2>
                    <a href="{{ route('events.index', ['sort' => 'start_at']) }}"
                        class="hidden sm:inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-blue-300 hover:text-blue-600 h-9">
                        View All
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($upcoming as $event)
                        @include('livewire.public.partials.event-card', ['event' => $event])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Become an Organizer CTA --}}
    @auth
        @if (auth()->user()->isOrganizer() || auth()->user()->isAdmin())
            {{-- already an organizer/admin, skip --}}
        @else
            @include('livewire.public.partials.organizer-cta')
        @endif
    @else
        @include('livewire.public.partials.organizer-cta')
    @endauth
</div>
