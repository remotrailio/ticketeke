<x-guest-layout>
    <h2 class="mb-2 text-xl font-bold text-gray-900">Reset your password</h2>
    <p class="mb-6 text-sm text-gray-500">Enter your email and we'll send you a reset link.</p>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input id="email" name="email" type="email" autocomplete="email" required autofocus
                   value="{{ old('email') }}"
                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('email') border-red-400 @enderror">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Send reset link
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-800">← Back to sign in</a>
    </p>
</x-guest-layout>
