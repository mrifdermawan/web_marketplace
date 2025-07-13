@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-12 rounded-2xl shadow-lg animate-fade-in-up">
    <h2 class="text-4xl font-bold mb-10 text-center text-blue-600">🛍️ Tambah Produk Baru</h2>

    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-8 bg-red-100 text-red-700 p-6 rounded-xl text-lg">
            <ul class="list-disc pl-6 space-y-2">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 text-lg">
        @csrf

        {{-- Nama Produk --}}
        <div>
            <label for="name" class="block font-semibold text-gray-700 mb-2">Nama Produk</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="w-full px-5 py-4 border border-gray-400 bg-white text-gray-900 rounded-lg shadow focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required>
        </div>

        {{-- Deskripsi --}}
        <div>
            <label for="description" class="block font-semibold text-gray-700 mb-2">Deskripsi</label>
            <textarea name="description" id="description" rows="6"
                      class="w-full px-5 py-4 border border-gray-400 bg-white text-gray-900 rounded-lg shadow focus:ring-2 focus:ring-blue-500 focus:outline-none"
                      required>{{ old('description') }}</textarea>
        </div>

        {{-- Harga --}}
        <div>
            <label for="price" class="block font-semibold text-gray-700 mb-2">Harga (Rp)</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}"
                   class="w-full px-5 py-4 border border-gray-400 bg-white text-gray-900 rounded-lg shadow focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required>
        </div>

        {{-- Stok --}}
        <div>
            <label for="stock" class="block font-semibold text-gray-700 mb-2">Stok</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}"
                   class="w-full px-5 py-4 border border-gray-400 bg-white text-gray-900 rounded-lg shadow focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required>
        </div>

        {{-- Kategori --}}
        <div>
            <label for="category" class="block font-semibold text-gray-700 mb-2">Kategori</label>
            <select name="category" id="category"
                class="w-full px-5 py-4 border border-gray-400 bg-white text-gray-900 rounded-lg shadow focus:ring-2 focus:ring-blue-500 focus:outline-none"
                required>
                <option value="" disabled selected>Pilih kategori</option>
                <option value="Elektronik" {{ old('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                <option value="Fashion" {{ old('category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                <option value="Otomotif" {{ old('category') == 'Otomotif' ? 'selected' : '' }}>Otomotif</option>
                <option value="Kecantikan" {{ old('category') == 'Kecantikan' ? 'selected' : '' }}>Kecantikan</option>
                <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>


        {{-- Gambar --}}
        <div>
            <label for="image" class="block font-semibold text-gray-700 mb-2">Foto Produk</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="block w-full text-lg text-gray-800 border-2 border-gray-300 rounded-xl shadow-sm cursor-pointer bg-white py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
        </div>


        {{-- Submit --}}
        <div class="flex justify-between items-center mt-10">
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline text-base">← Kembali ke daftar produk</a>
            <button type="submit"
                    class="bg-blue-600 text-white px-8 py-4 rounded-xl shadow hover:bg-blue-700 transition font-semibold text-lg">
                Simpan Produk
            </button>
        </div>
    </form>
</div>
@endsection
