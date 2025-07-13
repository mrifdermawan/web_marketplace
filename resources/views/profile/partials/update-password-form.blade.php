<section>
    <header class="mb-6">
        <p class="mt-1 text-base text-gray-600">
            Pastikan akun kamu aman dengan password yang panjang dan unik.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- 🔑 Current Password --}}
        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" class="text-lg" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-2 block w-full text-lg px-5 py-3 rounded-xl shadow-md border-gray-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm" />
        </div>

        {{-- 🆕 New Password --}}
        <div>
            <x-input-label for="update_password_password" :value="__('Password Baru')" class="text-lg" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-2 block w-full text-lg px-5 py-3 rounded-xl shadow-md border-gray-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm" />
        </div>

        {{-- ✅ Confirm Password --}}
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password')" class="text-lg" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-2 block w-full text-lg px-5 py-3 rounded-xl shadow-md border-gray-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-sm" />
        </div>

        {{-- 💾 Button --}}
        <div class="flex items-center gap-4">
            <x-primary-button class="text-base px-6 py-3 rounded-xl">
                {{ __('Simpan Perubahan') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-base text-green-600"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
