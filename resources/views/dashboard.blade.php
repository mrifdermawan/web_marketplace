@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    @if (auth()->check())
    @if (auth()->user()->role === 'customer')
        <h1 class="text-3xl font-bold text-white mb-6">
            Mau Beli apa, {{ auth()->user()->name }}?
        </h1>
    @else
        <h1 class="text-3xl font-bold text-white mb-6">
            Hai, {{ auth()->user()->name }}
        </h1>
    @endif
@else
    <h1 class="text-3xl font-bold text-white mb-6">
        Selamat datang, pengunjung!
    </h1>
@endif


{{-- 🔍 Filter & Sort --}}
<form method="GET" action="{{ route('dashboard') }}" class="mb-10 bg-white p-6 rounded-2xl shadow-lg flex flex-wrap lg:flex-nowrap gap-6 items-end border border-gray-100">
    {{-- Filter Kategori --}}
    <div class="w-full lg:w-1/3">
        <label for="category" class="block text-sm font-semibold text-gray-700 mb-1">📂 Kategori</label>
        <select name="category" id="category"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm bg-white">
            <option value="">Semua Kategori</option>
            @foreach ($allCategories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Sortir --}}
    <div class="w-full lg:w-1/3">
        <label for="sort" class="block text-sm font-semibold text-gray-700 mb-1">↕️ Urutkan</label>
        <select name="sort" id="sort"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm bg-white">
            <option value="">Default</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>💸 Harga Terendah</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>💰 Harga Tertinggi</option>
            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>🕒 Terbaru</option>
        </select>
    </div>

    {{-- Tombol Submit --}}
    <div class="w-full lg:w-auto">
        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow transition duration-200 text-sm">
            🚀 Terapkan
        </button>
    </div>
</form>



    @if ($products->isEmpty())
        <p class="text-gray-500 text-center">Belum ada produk tersedia.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="product-card hover:shadow-md transition rounded-xl overflow-hidden bg-white">
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('default-product.png') }}"
                            alt="{{ $product->name }}"
                            class="w-full h-40 object-cover">
                    </a>

                    <div class="p-4">
                        <a href="{{ route('products.show', $product->id) }}">
                            <h4 class="font-semibold text-gray-800 hover:text-blue-600 transition">{{ $product->name }}</h4>
                        </a>
                        <p class="text-green-600 text-sm mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mb-3">Stok: {{ $product->stock }}</p>

                        @auth
                            <div class="flex items-center justify-between gap-2 mt-2">
                                @if (auth()->user()->role === 'customer')
                                    <form action="{{ route('products.buy', $product->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow transition duration-200">
                                            🛒 Beli Sekarang
                                        </button>
                                    </form>
                                @endif

                                @php
                                    $isFavorited = $user->favorites->contains('product_id', $product->id);
                                @endphp

                                <form action="{{ route('products.favorite', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="transition transform hover:scale-110">
                                        @if ($isFavorited)
                                            {{-- ❤️ Merah jika sudah difavoritkan --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="#e3342f" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                                                    2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                                                    1.09-1.28 2.76-2.09 4.5-2.09C19.58 3 22 5.42
                                                    22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                        @else
                                            {{-- 🤍 Abu jika belum difavoritkan --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                                 viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                                                    2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                                                    1.09-1.28 2.76-2.09 4.5-2.09C19.58 3 22 5.42
                                                    22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $products->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>

<style>
    .product-card {
        border: 1px solid #e5e7eb;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
</style>
@endsection
