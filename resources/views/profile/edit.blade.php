@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-12 space-y-8">

    {{-- 👤 Update Foto & Nama --}}
    <div class="bg-white p-6 shadow-md rounded-2xl border border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 mb-4">👤 Edit Profil</h2>
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- 🔒 Ganti Password --}}
    <div class="bg-white p-6 shadow-md rounded-2xl border border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 mb-4">🔒 Ganti Password</h2>
        @include('profile.partials.update-password-form')
    </div>

    {{-- 🗑️ Hapus Akun --}}
    <div class="bg-white p-6 shadow-md rounded-2xl border border-gray-100">
        <h2 class="text-xl font-bold text-red-600 mb-4">🗑️ Hapus Akun</h2>
        @include('profile.partials.delete-user-form')
    </div>

</div>
@endsection
