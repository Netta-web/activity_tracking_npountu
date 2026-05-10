<x-guest-layout>
    <h2 class="text-xl font-bold text-white mb-2">Verify Your Email</h2>
    <p class="text-sm text-white/50 mb-6">
        Thanks for joining! Please verify your email address by clicking the link we sent you.
    </p>

    @if(session('status') === 'verification-link-sent')
    <div class="mb-5 p-3 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-xl text-sm">
        A new verification link has been sent to your email address.
    </div>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full py-3 text-sm font-semibold text-white bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-500 hover:to-brand-600 rounded-xl transition-all">
                Resend Verification Email
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full py-2.5 text-sm text-white/50 hover:text-white/80 transition-colors">
                Sign Out
            </button>
        </form>
    </div>
</x-guest-layout>
