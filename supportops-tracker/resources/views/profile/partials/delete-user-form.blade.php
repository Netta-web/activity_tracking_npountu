<section x-data="{ confirmDelete: false }">
    <p class="text-sm text-gray-600 mb-5">
        Once your account is deleted, all of its resources and data will be permanently deleted.
        Before deleting your account, please download any data you wish to retain.
    </p>

    <button type="button" @click="confirmDelete = true"
        class="px-6 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">
        Delete Account
    </button>

    {{-- Confirmation modal --}}
    <div x-show="confirmDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="confirmDelete = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full z-10">
            <h3 class="text-base font-bold text-gray-900 mb-2">Are you sure?</h3>
            <p class="text-sm text-gray-600 mb-6">This action is irreversible. Enter your password to confirm deletion.</p>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="delete_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <input id="delete_password" name="password" type="password" placeholder="Enter your password"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('password', 'userDeletion')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" @click="confirmDelete = false"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">
                        Delete My Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
