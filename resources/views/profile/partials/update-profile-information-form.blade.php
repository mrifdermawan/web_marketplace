<section>
    <header class="mb-8">
    </header>

    {{-- Form verifikasi email (jika diperlukan) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- 👤 Nama --}}
        <div>
            <x-input-label for="name" value="Nama Lengkap" class="text-lg" />
            <x-text-input id="name" name="name" type="text"
                class="mt-3 block w-full text-lg px-5 py-3 border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-base" :messages="$errors->get('name')" />
        </div>

         {{-- 📸 Foto Profil --}}
        <div>
            <x-input-label for="photo" value="Foto Profil" class="text-lg" />
            <input type="file" id="photo" name="photo" accept="image/*"
                class="mt-3 block w-full text-lg px-5 py-3 border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500" />
            <x-input-error :messages="$errors->get('photo')" class="mt-2 text-base" />
        </div>

        {{-- 📧 Email --}}
        <div>
            <x-input-label for="email" value="Email" class="text-lg" />
            <x-text-input id="email" name="email" type="email"
                class="mt-3 block w-full text-lg px-5 py-3 border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error class="mt-2 text-base" :messages="$errors->get('email')" />
        </div>

{{-- ✅ Tombol Simpan --}}
        <div class="flex items-center gap-6 mt-6">
            <x-primary-button class="text-lg px-6 py-3">💾 Simpan Perubahan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="text-base text-gray-700">Perubahan disimpan.</p>
            @endif
        </div>

        {{-- ✅ Notifikasi berhasil --}}
        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="text-green-800 bg-green-100 border border-green-300 rounded-lg px-4 py-2 text-base shadow font-semibold">
                ✅ Profil berhasil diperbarui!
            </p>
        @endif



    </form>
</section>
