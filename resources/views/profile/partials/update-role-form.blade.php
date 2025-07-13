<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Ubah Role</h2>
        <p class="mt-1 text-sm text-gray-600">Kamu bisa bebas ganti role antara seller dan customer.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="role" value="Pilih Role" />
            <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                <option value="customer" {{ old('role', auth()->user()->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="seller" {{ old('role', auth()->user()->role) === 'seller' ? 'selected' : '' }}>Seller</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Ubah Role</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
