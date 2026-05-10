<x-guest-layout>
    <h2 class="text-xl font-bold text-white mb-1">Confirm Password</h2>
    <p class="text-sm text-white/50 mb-7">This is a secure area. Please confirm your password to continue.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf
        <div>
            <label for="password" class="block text-sm font-medium text-white/70 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.12] text-white placeholder-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                placeholder="••••••••">
            @error('password')
            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="w-full py-3 text-sm font-semibold text-white bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-500 hover:to-brand-600 rounded-xl transition-all duration-200">
            Confirm
        </button>
    </form>
</x-guest-layout>
