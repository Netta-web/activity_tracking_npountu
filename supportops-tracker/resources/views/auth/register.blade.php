<x-guest-layout>
    {{-- Registration is admin-only. This page is disabled for public access. --}}
    <h2 class="text-xl font-bold text-white mb-1">Registration Restricted</h2>
    <p class="text-sm text-white/50 mb-6">
        New accounts are created by system administrators only.
        Please contact your team lead or admin.
    </p>
    <a href="{{ route('login') }}"
        class="block w-full py-3 text-center text-sm font-semibold text-white bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-500 hover:to-brand-600 rounded-xl transition-all duration-200">
        Back to Login
    </a>
</x-guest-layout>
