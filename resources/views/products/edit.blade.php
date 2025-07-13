@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-md mt-10">
    <h2 class="text-3xl font-bold mb-8 text-blue-700">✏️ Edit Produk</h2>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-xl">
            <ul class="list-disc list-inside space-y-2">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-lg">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div>
            <label for="name" class="block font-semibold mb-2">Nama Produk</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-blue-500" required>
        </div>

        {{-- Deskripsi --}}
        <div>
            <label for="description" class="block font-semibold mb-2">Deskripsi</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-blue-500"
                      required>{{ old('description', $product->description) }}</textarea>
        </div>

        {{-- Harga --}}
        <div>
            <label for="price" class="block font-semibold mb-2">Harga (Rp)</label>
            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-blue-500" required>
        </div>

        {{-- Stok --}}
        <div>
            <label for="stock" class="block font-semibold mb-2">Stok</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-blue-500" required>
        </div>

        {{-- Kategori --}}
        <div>
            <label for="category" class="block font-semibold mb-2">Kategori</label>
            <input type="text" name="category" id="category" value="{{ old('category', $product->category) }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Gambar --}}
        <div>
            <label for="image" class="block font-semibold mb-2">Ganti Gambar (opsional)</label>
            <input type="file" name="image" id="image" accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-blue-500">
            @if ($product->image)
                <div class="mt-2 text-sm text-gray-500">
                    Gambar saat ini: <span class="text-blue-600">{{ basename($product->image) }}</span>
                </div>
            @endif
        </div>

        {{-- Tombol --}}
        <div class="flex justify-between mt-8">
            <a href="{{ route('profile') }}" class="text-gray-600 hover:text-blue-600 text-base">← Kembali ke Profil</a>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition font-semibold">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
