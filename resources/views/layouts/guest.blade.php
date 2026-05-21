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
<body class="min-h-screen bg-gray-50 antialiased">

    <div class="flex min-h-screen flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="{{ route('home') }}" class="flex items-center justify-center gap-2">
                @if($__settings->logo_url)
                    <img src="{{ $__settings->logo_url }}" alt="{{ $__settings->app_name }}" class="h-10 w-auto">
                @else
                    <span class="text-2xl font-extrabold text-indigo-600">{{ $__settings->app_name }}</span>
                @endif
            </a>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white px-8 py-10 shadow-sm ring-1 ring-gray-100 sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
