<div class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="mb-8 text-3xl font-bold text-gray-900">My Profile</h1>

    {{-- Profile info --}}
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-base font-semibold text-gray-900">Account details</h2>

        @if($saved)
        <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">Profile updated.</div>
        @endif

        <form wire:submit="saveProfile" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input wire:model="name" id="name" type="text" required
                       class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input wire:model="email" id="email" type="email" required
                       class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <button type="submit"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Save changes
            </button>
        </form>
    </div>

    {{-- Change password --}}
    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-base font-semibold text-gray-900">Change password</h2>

        @if($passwordSaved)
        <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">Password updated.</div>
        @endif

        <form wire:submit="savePassword" class="space-y-4">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current password</label>
                <input wire:model="current_password" id="current_password" type="password"
                       class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @error('current_password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">New password</label>
                <input wire:model="password" id="new_password" type="password"
                       class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="new_password_confirm" class="block text-sm font-medium text-gray-700">Confirm new password</label>
                <input wire:model="password_confirmation" id="new_password_confirm" type="password"
                       class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <button type="submit"
                    class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900 transition">
                Update password
            </button>
        </form>
    </div>

    {{-- Become an organizer --}}
    @if(auth()->user()->isAttendee())
    <div class="mt-6 rounded-xl border border-indigo-100 bg-indigo-50 p-6">
        <h2 class="text-base font-semibold text-indigo-900">Want to host events?</h2>
        <p class="mt-1 text-sm text-indigo-700">Create an organizer profile to start selling tickets.</p>
        <a href="{{ route('organizer.onboard') }}"
           class="mt-3 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Become an organizer
        </a>
    </div>
    @endif
</div>
