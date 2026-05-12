<div>
    {{-- Hero --}}
    <section class="relative bg-indigo-700 py-24 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                Discover Events Near You
            </h1>
            <p class="mt-4 text-lg text-indigo-200">
                Find and book the best concerts, workshops, and experiences.
            </p>
            <a href="{{ route('events.index') }}"
               class="mt-8 inline-block rounded-lg bg-white px-8 py-3 text-sm font-semibold text-indigo-700 hover:bg-indigo-50 transition">
                Browse All Events
            </a>
        </div>
    </section>

    {{-- Categories --}}
    @if($categories->isNotEmpty())
    <section class="py-12 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-6">Browse by Category</h2>
            <div class="flex flex-wrap gap-3">
                @foreach($categories as $cat)
                <a href="{{ route('events.index', ['category' => $cat->slug]) }}"
                   class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:border-indigo-400 hover:text-indigo-600 transition">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Featured Events --}}
    @if($featured->isNotEmpty())
    <section class="py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Featured Events</h2>
                <a href="{{ route('events.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View all →</a>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featured as $event)
                    @include('livewire.public.partials.event-card', ['event' => $event])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Upcoming Events --}}
    @if($upcoming->isNotEmpty())
    <section class="py-16 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Upcoming Events</h2>
                <a href="{{ route('events.index', ['sort' => 'start_at']) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View all →</a>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($upcoming as $event)
                    @include('livewire.public.partials.event-card', ['event' => $event])
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
