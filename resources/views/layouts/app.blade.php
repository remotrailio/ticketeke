@php $__settings = app_settings(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? $__settings->app_name }}</title>
    <meta name="description" content="{{ $description ?? 'Discover and book events near you.' }}">

    {{-- Favicon --}}
    @if($__settings->favicon_url)
    <link rel="icon" type="image/x-icon" href="{{ $__settings->favicon_url }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $title ?? $__settings->app_name }}">
    <meta property="og:description" content="{{ $description ?? 'Discover and book events near you.' }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    @isset($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
    @endisset

    {{-- Canonical --}}
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- JSON-LD hook --}}
    @stack('schema-org')
</head>
<body class="min-h-screen bg-white text-gray-900 antialiased">

    {{-- Navigation --}}
    <nav class="border-b border-gray-100 bg-white" x-data="{ open: false }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    @if($__settings->logo_url)
                        <img src="{{ $__settings->logo_url }}" alt="{{ $__settings->app_name }}" class="h-8 w-auto">
                    @else
                        <span class="text-xl font-bold tracking-tight text-indigo-600">{{ $__settings->app_name }}</span>
                    @endif
                </a>

                {{-- Desktop nav --}}
                <div class="hidden items-center gap-6 sm:flex">
                    <a href="{{ route('events.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Browse Events</a>

                    @auth
                        @if(auth()->user()->isOrganizer())
                            <a href="{{ url('/organizer') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Dashboard</a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ url('/admin') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Admin</a>
                        @else
                            <a href="{{ route('my.tickets') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">My Tickets</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-900">Log out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Sign up</a>
                    @endauth
                </div>

                {{-- Mobile toggle --}}
                <button @click="open = !open" class="sm:hidden p-2 rounded-md text-gray-500 hover:text-gray-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div x-show="open" x-transition class="sm:hidden pb-4 space-y-2">
                <a href="{{ route('events.index') }}" class="block px-2 py-1 text-sm text-gray-600">Browse Events</a>
                @auth
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ url('/organizer') }}" class="block px-2 py-1 text-sm text-gray-600">Dashboard</a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ url('/admin') }}" class="block px-2 py-1 text-sm text-gray-600">Admin</a>
                    @else
                        <a href="{{ route('my.tickets') }}" class="block px-2 py-1 text-sm text-gray-600">My Tickets</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="block px-2 py-1 text-sm text-gray-600">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-2 py-1 text-sm text-gray-600">Log in</a>
                    <a href="{{ route('register') }}" class="block px-2 py-1 text-sm text-gray-600">Sign up</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Page content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="mt-24 border-t border-gray-100 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                @if($__settings->logo_url)
                    <img src="{{ $__settings->logo_url }}" alt="{{ $__settings->app_name }}" class="h-8 w-auto">
                @else
                    <span class="text-sm font-semibold text-indigo-600">{{ $__settings->app_name }}</span>
                @endif
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} {{ $__settings->app_name }}. All rights reserved.</p>
                <div class="flex gap-4 text-xs text-gray-400">
                    <a href="#" class="hover:text-gray-600">Privacy</a>
                    <a href="#" class="hover:text-gray-600">Terms</a>
                    <a href="#" class="hover:text-gray-600">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
