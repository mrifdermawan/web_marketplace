@extends('layouts.app')

@section('content')

    @if (session('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        x-transition
        class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg shadow z-50 max-w-sm w-full text-center text-sm md:text-base"
        role="alert"
    >
        ✅ {{ session('success') }}
    </div>
    @endif


@if (session('error'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition
        class="mb-6 p-5 bg-red-100 border border-red-300 text-red-900 rounded-lg shadow text-center text-lg font-semibold"
    >
        ⚠️ {{ session('error') }}
    </div>
@endif



    <style>
        .product-detail {
            max-width: 900px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .product-image {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .product-info > div {
            background-color: #f9f9f9;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .product-info h3 {
            font-size: 1rem;
            color: #888;
            margin-bottom: 0.3rem;
        }

        .product-info p {
            font-size: 1.1rem;
            font-weight: 500;
            color: #222;
        }

        .btn-buy {
            display: inline-block;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-buy:hover {
            background-color: #218838;
        }

        .btn-back {
            display: inline-block;
            margin-top: 2rem;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-back:hover {
            text-decoration: underline;
        }
    </style>

    <div class="product-detail animate-fade-in-up">
        {{-- Gambar --}}
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
        @endif

        {{-- Detail Produk --}}
        <div class="product-info">
            <div>
                <h3>Nama Produk</h3>
                <p>{{ $product->name }}</p>
            </div>

             <div>
                <h3>Penjual</h3>
                <p>{{ $product->user->name }}</p>
            </div>

            <div>
                <h3>Kategori</h3>
                <p>{{ $product->category }}</p>
            </div>

            <div>
                <h3>Deskripsi</h3>
                <p>{{ $product->description }}</p>
            </div>

            <div>
                <h3>Harga</h3>
                <p>Rp{{ number_format($product->price, 0, ',', '.') }}</p>
            </div>

            <div>
                <h3>Stok Tersedia</h3>
                <p>{{ $product->stock }}</p>
            </div>



            @auth
                @if (Auth::check() && Auth::user()->role === 'customer')
                    <form action="{{ route('products.buy', $product->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold">
                            🛒 Beli Sekarang
                        </button>
                    </form>
                    <form action="{{ route('products.favorite', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-600 text-xl">
                            ❤️
                        </button>
                    </form>

                @endif

            @endauth
        </div>

        <a href="{{ route('products.index') }}" class="btn-back">← Kembali ke daftar produk</a>
    </div>
@endsection
