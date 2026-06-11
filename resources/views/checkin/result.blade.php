@php $__settings = app_settings(); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket Check-In — {{ $__settings->app_name }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">

    <div class="w-full max-w-sm">
        {{-- Status card --}}
        <div @class([
            'rounded-2xl shadow-lg overflow-hidden',
            'ring-2 ring-green-400' => $valid,
            'ring-2 ring-red-400'   => ! $valid,
        ])>

            {{-- Top band --}}
            <div @class([
                'px-6 py-8 text-center',
                'bg-green-500' => $valid,
                'bg-red-500'   => ! $valid,
            ])>
                @if($valid)
                    {{-- Checkmark --}}
                    <svg class="mx-auto mb-3 h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <p class="text-2xl font-bold text-white">Access Granted</p>
                @else
                    {{-- X mark --}}
                    <svg class="mx-auto mb-3 h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <p class="text-2xl font-bold text-white">Access Denied</p>
                @endif
            </div>

            {{-- Detail panel --}}
            <div class="bg-white px-6 py-6 space-y-4">
                <p @class([
                    'text-center text-sm font-medium',
                    'text-green-700' => $valid,
                    'text-red-700'   => ! $valid,
                ])>{{ $message }}</p>

                @if($valid && isset($ticket))
                    <div class="divide-y divide-gray-100 rounded-xl bg-gray-50 text-sm">
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-gray-500">Event</span>
                            <span class="font-medium text-gray-900">{{ $ticket['event'] }}</span>
                        </div>
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-gray-500">Attendee</span>
                            <span class="font-medium text-gray-900">{{ $ticket['attendee_name'] ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-gray-500">Ticket</span>
                            <span class="font-mono text-xs font-semibold tracking-widest text-indigo-600">{{ $ticket['ticket_code'] }}</span>
                        </div>
                    </div>
                @endif

                @if(isset($checked_in_at))
                    <p class="text-center text-xs text-gray-400">Checked in at {{ $checked_in_at }}</p>
                @endif
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 px-6 py-3 text-center text-xs text-gray-400">
                {{ $__settings->app_name }}
            </div>
        </div>
    </div>

</body>
</html>
