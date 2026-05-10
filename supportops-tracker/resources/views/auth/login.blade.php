<x-guest-layout>
    @if(session('status'))
    <div class="mb-5 p-3 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-xl text-sm">
        {{ session('status') }}
    </div>
    @endif

    <h2 class="text-xl font-bold text-white mb-1">Welcome back</h2>
    <p class="text-sm text-white/50 mb-7">Sign in to your operations dashboard</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-white/70 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.12] text-white placeholder-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-sm"
                placeholder="you@company.com">
            @error('email')
            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-white/70 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.12] text-white placeholder-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-sm"
                placeholder="••••••••">
            @error('password')
            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/10 text-purple-500 focus:ring-purple-500">
                <span class="text-sm text-white/60">Remember me</span>
            </label>
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">
                Forgot password?
            </a>
            @endif
        </div>

        <button type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-500 hover:to-brand-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-brand-900/50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 focus:ring-offset-transparent text-sm">
            Sign In to Dashboard
        </button>
    </form>
</x-guest-layout>
