@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10">
    <h2 class="text-xl font-bold mb-4">Hasil Pencarian: "{{ $query }}"</h2>

    @if($products->isEmpty())
        <p class="text-gray-500">Tidak ada produk yang cocok.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($products as $product)
    <div class="bg-white rounded-xl shadow hover:shadow-lg hover:scale-[1.02] hover:bg-blue-50 transition-all duration-300 ease-in-out p-4 flex flex-col justify-between transform">
        <a href="{{ route('products.show', $product->id) }}" class="block">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-40 object-cover rounded mb-3">
            @endif
            <h3 class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition-colors duration-200">
                {{ $product->name }}
            </h3>
            <p class="text-sm text-gray-600 mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
        </a>

        @auth
            @if (auth()->user()->role === 'customer')
                <form action="{{ route('orders.store') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit"
                            class="w-full mt-auto bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg transition">
                        🛒 Beli
                    </button>
                </form>
            @endif
        @endauth
    </div>
@endforeach


        </div>
    @endif
</div>
@endsection
