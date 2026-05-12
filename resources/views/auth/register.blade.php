<x-guest-layout>
    <h2 class="mb-6 text-xl font-bold text-gray-900">Create your account</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
            <input id="name" name="name" type="text" autocomplete="name" required autofocus
                   value="{{ old('name') }}"
                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input id="email" name="email" type="email" autocomplete="email" required
                   value="{{ old('email') }}"
                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('email') border-red-400 @enderror">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required
                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('password') border-red-400 @enderror">
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Create account
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-800">Sign in</a>
    </p>
</x-guest-layout>
