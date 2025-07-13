@extends('layouts.app')

@section('content')
<div class="flex flex-col min-h-screen">
    {{-- 🛍 Judul --}}
    <div class="text-center mt-12 mb-6">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 tracking-tight">🛒 Produk Terbaru</h1>
        <p class="text-lg text-gray-500 mt-2">Temukan produk pilihan dari seller terbaik hari ini</p>
    </div>

    {{-- 🔍 Filter Kategori --}}
    <div class="max-w-7xl mx-auto mb-8 px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-4 items-center">
            <label for="category" class="text-gray-700 font-semibold">Filter Kategori:</label>
            <select name="category" id="category" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-4 py-2 text-base text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="semua" {{ request('category') == 'semua' ? 'selected' : '' }}>Semua</option>
                <option value="Elektronik" {{ request('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                <option value="Fashion" {{ request('category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                <option value="Otomotif" {{ request('category') == 'Otomotif' ? 'selected' : '' }}>Otomotif</option>
                <option value="Kecantikan" {{ request('category') == 'Kecantikan' ? 'selected' : '' }}>Kecantikan</option>
                <option value="Lainnya" {{ request('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </form>
    </div>

    {{-- 🧾 Produk --}}
    <div class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($products->isEmpty())
                <div class="text-center text-gray-400 mt-16 text-xl">Belum ada produk, stay tuned!</div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 pb-16">
                    @foreach ($products as $product)
                        <div class="bg-white rounded-3xl shadow-lg overflow-visible hover:shadow-2xl hover:-translate-y-1 transform transition-all duration-300">
                            <a href="{{ route('products.show', $product->id) }}">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-56 object-cover transition-transform hover:scale-105 duration-300">
                                @else
                                    <div class="w-full h-56 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                        Tidak ada gambar
                                    </div>
                                @endif
                            </a>

                            <div class="p-4 flex flex-col gap-4 h-full">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition">
                                        <a href="{{ route('products.show', $product->id) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1">{{ Str::limit($product->description, 60) }}</p>
                                </div>

                                <div>
                                    <span class="text-green-600 font-bold text-lg">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>

                                    @auth
                                        @php $role = auth()->user()->role ?? null; @endphp

                                        @if ($role === 'customer')
                                            <form action="{{ route('products.buy', $product->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow transition duration-200">
                                                    Beli Sekarang
                                                </button>
                                            </form>
                                            <form action="{{ route('products.favorite', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-red-500 hover:text-red-600 text-xl">
                                                    ❤️
                                                </button>
                                            </form>
                                        @elseif ($role === 'seller')
                                            <div class="flex gap-2 items-center mt-2 text-sm">
                                                <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:underline"
                                                            onclick="return confirm('Yakin mau hapus produk ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($products->hasPages())
                        <div class="flex justify-center mt-10 mb-20">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
