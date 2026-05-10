<x-guest-layout>
    @if(session('status'))
    <div class="mb-5 p-3 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-xl text-sm">
        {{ session('status') }}
    </div>
    @endif

    <h2 class="text-xl font-bold text-white mb-1">Reset Password</h2>
    <p class="text-sm text-white/50 mb-7">Enter your email and we'll send a reset link</p>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-white/70 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.12] text-white placeholder-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                placeholder="you@company.com">
            @error('email')
            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-500 hover:to-brand-600 text-white font-semibold rounded-xl transition-all duration-200 text-sm">
            Send Reset Link
        </button>

        <p class="text-center text-sm text-white/40">
            <a href="{{ route('login') }}" class="text-brand-400 hover:text-brand-300 font-medium">Back to login</a>
        </p>
    </form>
</x-guest-layout>
