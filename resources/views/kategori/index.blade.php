@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    @isset($products)
        <h1 class="text-4xl font-bold text-gray-800 mb-6 text-center">
            🏷️ Kategori: {{ $category->name }}
        </h1>

        @if ($products->isEmpty())
            <p class="text-center text-gray-500 text-xl">Belum ada produk di kategori ini.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div class="product-card hover:shadow-md transition rounded-xl overflow-hidden bg-white">
                        <a href="{{ route('products.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover rounded-t-xl">
                        </a>

                        <div class="p-4">
                            <a href="{{ route('products.show', $product->id) }}">
                                <h3 class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition">{{ $product->name }}</h3>
                            </a>
                            <p class="text-green-600 text-sm mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500 mb-3">Stok: {{ $product->stock }}</p>
                            <p class="text-sm text-gray-400 mb-2">{{ Str::limit($product->description, 50) }}</p>

                            @auth
                                @if (auth()->user()->role === 'customer')
                                    <div class="flex items-center justify-between gap-2 mt-3">
                                        {{-- Tombol Beli --}}
                                        <form action="{{ route('products.buy', $product->id) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow transition">
                                                🛒 Beli Sekarang
                                            </button>
                                        </form>

                                        {{-- Tombol Favorite --}}
                                        @php
                                            $isFavorited = auth()->user()->favorites->contains('product_id', $product->id);
                                        @endphp
                                        <form action="{{ route('products.favorite', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="transition transform hover:scale-110">
                                                @if ($isFavorited)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="#e3342f">
                                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                                                                2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                                                                1.09-1.28 2.76-2.09 4.5-2.09C19.58 3 22 5.42
                                                                22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2">
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
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center mt-10">
                {{ $products->links() }}
            </div>
        @endif

    @else
        <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">🏷️ Jelajahi Kategori</h1>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach ($categories as $kategori)
                <a href="{{ route('kategori.show', $kategori->slug) }}"
                   class="block bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-200 border border-gray-100">
                    <h3 class="text-xl font-semibold text-blue-700">{{ $kategori->name }}</h3>
                    <p class="text-sm text-gray-500 mt-2">Lihat produk di kategori ini</p>
                </a>
            @endforeach
        </div>
    @endisset
</div>

<style>
    .product-card {
        border: 1px solid #e5e7eb;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
</style>
@endsection
