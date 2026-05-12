<x-guest-layout>
    <h2 class="mb-6 text-xl font-bold text-gray-900">Sign in to your account</h2>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input id="email" name="email" type="email" autocomplete="email" required autofocus
                   value="{{ old('email') }}"
                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('email') border-red-400 @enderror">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:text-indigo-800">Forgot password?</a>
            </div>
            <input id="password" name="password" type="password" autocomplete="current-password" required
                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('password') border-red-400 @enderror">
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" name="remember" type="checkbox"
                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="remember_me" class="text-sm text-gray-600">Remember me</label>
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Sign in
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-800">Sign up free</a>
    </p>
</x-guest-layout>
