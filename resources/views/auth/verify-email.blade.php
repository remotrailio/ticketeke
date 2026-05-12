<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100">
            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900">Check your email</h2>
        <p class="mt-2 text-sm text-gray-500">
            We sent a verification link to your email. Click the link to verify your account.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 rounded-md bg-green-50 p-3 text-center text-sm text-green-700">
            A new verification link has been sent.
        </div>
    @endif

    <div class="mt-6 space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full rounded-lg border border-indigo-300 py-2.5 text-sm font-medium text-indigo-700 hover:bg-indigo-50 transition">
                Resend verification email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-center text-sm text-gray-500 hover:text-gray-700">
                Log out
            </button>
        </form>
    </div>
</x-guest-layout>
