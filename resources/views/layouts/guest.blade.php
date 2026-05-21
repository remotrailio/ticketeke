<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? $__settings->app_name }}</title>
    @if($__settings->favicon_url)
    <link rel="icon" type="image/x-icon" href="{{ $__settings->favicon_url }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen antialiased" style="background: linear-gradient(135deg, #F8FAFC 0%, #EEF2FF 50%, #E0E7FF 100%);">

    <div class="flex min-h-screen flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="{{ route('home') }}" class="flex items-center justify-center gap-2">
                @if($__settings->logo_url)
                    <img src="{{ $__settings->logo_url }}" alt="{{ $__settings->app_name }}" class="h-10 w-auto">
                @else
                    <span class="text-2xl font-extrabold text-blue-600">{{ $__settings->app_name }}</span>
                @endif
            </a>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white px-8 py-10 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200/80 sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
