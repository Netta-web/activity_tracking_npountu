<x-guest-layout>
    <h2 class="text-xl font-bold text-white mb-1">Set New Password</h2>
    <p class="text-sm text-white/50 mb-7">Choose a strong password for your account</p>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-white/70 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.12] text-white placeholder-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
            @error('email')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-white/70 mb-1.5">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.12] text-white placeholder-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                placeholder="••••••••">
            @error('password')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-white/70 mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.12] text-white placeholder-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                placeholder="••••••••">
            @error('password_confirmation')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-500 hover:to-brand-600 text-white font-semibold rounded-xl transition-all duration-200 text-sm">
            Reset Password
        </button>
    </form>
</x-guest-layout>
