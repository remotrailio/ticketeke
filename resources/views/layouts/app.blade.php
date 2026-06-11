<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? $__settings->app_name }}</title>
    <meta name="description" content="{{ $description ?? 'Discover and book events near you.' }}">

    @if($__settings->favicon_url)
    <link rel="icon" type="image/x-icon" href="{{ $__settings->favicon_url }}">
    @endif

    <meta property="og:title" content="{{ $title ?? $__settings->app_name }}">
    <meta property="og:description" content="{{ $description ?? 'Discover and book events near you.' }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    @isset($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
    @endisset

    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    @stack('schema-org')
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased flex flex-col">

    <nav class="sticky top-0 z-50 w-full border-b border-slate-200 bg-white/90 backdrop-blur-md shadow-sm"
         x-data="{ open: false }"
         x-init="$watch('open', v => document.body.classList.toggle('overflow-hidden', v))"
         @keydown.escape.window="open = false">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">

                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        @if($__settings->logo_url)
                            <img src="{{ $__settings->logo_url }}" alt="{{ $__settings->app_name }}" class="h-8 w-auto">
                        @else
                            <span class="text-xl font-bold tracking-tight text-blue-600">{{ $__settings->app_name }}</span>
                        @endif
                    </a>

                    <div class="hidden items-center gap-6 sm:flex ml-8">
                        <a href="{{ route('events.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Browse Events</a>

                        @auth
                            @if(auth()->user()->isOrganizer())
                                <a href="{{ url('/organizer') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Dashboard</a>
                            @elseif(auth()->user()->isAdmin())
                                <a href="{{ url('/admin') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Admin</a>
                            @else
                                <a href="{{ route('my.tickets') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">My Tickets</a>
                                <a href="{{ route('organizers.become') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Become an Organizer</a>
                            @endif
                        @else
                            <a href="{{ route('organizers.become') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Become an Organizer</a>
                        @endauth
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        @if(!auth()->user()->isOrganizer() && !auth()->user()->isAdmin())
                        <a href="{{ route('events.index') }}"
                           class="hidden sm:inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:text-slate-900 hover:bg-slate-100"
                           title="Browse Events">
                            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
                            </svg>
                        </a>
                        @endif

                        <div class="relative hidden sm:block" x-data="{ userMenu: false }">
                            <button @click="userMenu = !userMenu"
                                    @click.away="userMenu = false"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:text-slate-900 hover:bg-slate-100"
                                    title="Account">
                                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-4 3.582-7 8-7s8 3 8 7"/>
                                </svg>
                            </button>

                            <div x-show="userMenu"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-2 w-52 origin-top-right rounded-xl border border-slate-200 bg-white shadow-lg shadow-slate-200/60 z-50"
                                 style="display: none;">
                                <div class="border-b border-slate-100 px-4 py-3">
                                    <p class="text-xs font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="p-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h6a2 2 0 012 2v1"/>
                                            </svg>
                                            Log out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline-flex text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}"
                           class="hidden sm:inline-flex rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-violet-500 transition-colors shadow-sm">
                            Sign up
                        </a>
                    @endguest

                    <button @click="open = !open" class="sm:hidden p-2 rounded-md text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <div x-show="open"
             @click="open = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-slate-900/30 backdrop-blur-sm sm:hidden"
             style="display: none;"
             aria-hidden="true">
        </div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white shadow-2xl shadow-slate-200 sm:hidden min-h-screen border-r border-slate-100"
             style="display: none;">

            <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-100 px-4">
                <a href="{{ route('home') }}" @click="open = false">
                    @if($__settings->logo_url)
                        <img src="{{ $__settings->logo_url }}" alt="{{ $__settings->app_name }}" class="h-8 w-auto">
                    @else
                        <span class="text-lg font-bold text-blue-600">{{ $__settings->app_name }}</span>
                    @endif
                </a>
                <button @click="open = false"
                        class="flex h-8 w-8 items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <a href="{{ route('events.index') }}" @click="open = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">Browse Events</a>
                @auth
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ url('/organizer') }}" @click="open = false"
                           class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">Dashboard</a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ url('/admin') }}" @click="open = false"
                           class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">Admin</a>
                    @else
                        <a href="{{ route('my.tickets') }}" @click="open = false"
                           class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">My Tickets</a>
                        <a href="{{ route('organizers.become') }}" @click="open = false"
                           class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">Become an Organizer</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" @click="open = false"
                       class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">Log in</a>
                    <a href="{{ route('organizers.become') }}" @click="open = false"
                       class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">Become an Organizer</a>
                @endauth
            </div>

            <div class="shrink-0 border-t border-slate-100 p-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                            Log out
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}"
                       class="block w-full rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-violet-500 transition-colors">
                        Sign up
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                @if($__settings->logo_url)
                    <img src="{{ $__settings->logo_url }}" alt="{{ $__settings->app_name }}" class="h-8 w-auto">
                @else
                    <span class="text-sm font-semibold text-blue-600">{{ $__settings->app_name }}</span>
                @endif
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} {{ $__settings->app_name }}. All rights reserved.</p>
                <div class="flex gap-4 text-xs text-slate-400">
                    <a href="#" class="hover:text-slate-600 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-slate-600 transition-colors">Terms</a>
                    <a href="#" class="hover:text-slate-600 transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
