@extends('layouts.app')

@section('content')

<div id="page-content" class="max-w-5xl mx-auto mt-10 opacity-0 transition-opacity duration-500">
 {{-- 🔘 PROFILE HEADER --}}
<div class="bg-blue-50 border border-blue-200 shadow-lg rounded-2xl p-6 flex justify-between items-start mb-10 text-blue-800">
    {{-- 📸 FOTO + INFO KIRI --}}
    <div class="flex items-start gap-6">
        <img src="{{ $user->profile_photo_url ?? asset('default-avatar.png') }}"
             class="w-24 h-24 rounded-full object-cover ring-2 ring-blue-400">

        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-500">{{ $user->email }}</p>

            {{-- 🧩 Badge Role --}}
            <span class="mt-2 inline-block px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full uppercase tracking-wide font-semibold">
                {{ ucfirst($user->role) }}
            </span>

            {{-- 🔄 SWITCH ROLE --}}
                        <form method="post" action="{{ route('profile.update') }}" id="role-form">
                @csrf
                @method('patch')

                <input type="hidden" name="role" id="hidden-role" value="{{ auth()->user()->role }}">

                <div class="flex items-center gap-4 mt-2">
                    <span id="label-customer" class="text-sm font-semibold cursor-pointer transition-colors duration-300">Customer</span>

                    <!-- Switch Toggle -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="role-switch" class="sr-only peer" {{ auth()->user()->role === 'seller' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-blue-600 rounded-full peer-checked:bg-blue-600 transition-colors"></div>
                        <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-full"></div>
                    </label>

                    <span id="label-seller" class="text-sm font-semibold cursor-pointer transition-colors duration-300">Seller</span>
                </div>
            </form>

        </div>
    </div>

    {{-- ✏️ TOMBOL EDIT KANAN ATAS --}}
    <a href="{{ route('profile.edit') }}"
       class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition self-start">
       ✏️ Edit Profil
    </a>
</div>




{{-- CUSTOMER VIEW --}}
@if ($user->role === 'customer')
    {{-- 🛍️ Riwayat Pembelian --}}
    <div class="bg-emerald-50 border border-emerald-100 shadow-md rounded-xl p-5 mb-8">
        <h3 class="text-lg font-semibold mb-4 text-emerald-700">🛍️ Riwayat Pembelian</h3>
        @forelse ($orders as $order)
    <div class="flex items-center gap-4 bg-white border border-emerald-200 rounded-lg p-4 shadow-sm mb-3 hover:shadow transition">
        <img src="{{ $order->product->image ? asset('storage/' . $order->product->image) : asset('default-product.png') }}"
             alt="{{ $order->product->name }}"
             class="w-20 h-20 object-cover rounded-md border">
        <div class="flex-1">
            <div class="text-lg font-semibold text-gray-800">{{ $order->product->name }}</div>
            <div class="text-sm text-gray-500">Jumlah: {{ $order->quantity }} | Rp{{ number_format($order->product->price, 0, ',', '.') }}</div>
            <div class="text-xs text-gray-400 mt-1">Dibeli pada {{ $order->created_at->format('d M Y H:i') }}</div>
        </div>
    </div>
@empty
    <p class="text-emerald-400 text-sm">Belum ada riwayat pembelian.</p>
@endforelse

{{-- ⏩ Pagination --}}
<div class="mt-4">
    {{ $orders->links('vendor.pagination.custom') }}
</div>

    </div>


    {{-- ❤️ Favorit --}}
    <div class="bg-pink-50 border border-pink-100 shadow-md rounded-xl p-5">
    <h3 class="text-lg font-semibold mb-4 text-pink-700">❤️ Produk Favorit</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse ($favorites as $fav)
            @php $product = $fav->product; @endphp

            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('default-product.png') }}"
                         alt="{{ $product->name }}"
                         class="w-full h-40 object-cover">
                </a>
                <div class="p-4 flex flex-col gap-2">
                    <a href="{{ route('products.show', $product->id) }}" class="text-gray-800 font-semibold hover:text-pink-600 transition">
                        {{ $product->name }}
                    </a>
                    <p class="text-green-600 text-sm">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

                    <div class="flex justify-between items-center mt-2">
                        <form action="{{ route('products.buy', $product->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-pink-600 hover:bg-pink-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition">
                                🛒 Beli Sekarang
                            </button>
                        </form>

                        <form action="{{ route('products.favorite', $product->id) }}" method="POST" class="ml-2">
                            @csrf
                            @php
                                $isFavorited = auth()->user()->favorites->contains('product_id', $product->id);
                            @endphp
                            <button type="submit" class="text-xl transition transform hover:scale-110"
                                    style="color: {{ $isFavorited ? '#e3342f' : '#cbd5e0' }};">
                                ❤
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <p class="text-pink-400 text-sm col-span-full">Belum ada produk favorit.</p>
        @endforelse
    </div>
</div>


{{-- SELLER VIEW --}}
@elseif ($user->role === 'seller')

    {{-- 📈 Statistik Penjualan --}}
    <div class="bg-yellow-50 border border-yellow-100 shadow-md rounded-xl p-5 mb-8">
        <h3 class="text-lg font-semibold mb-2 text-yellow-700">📈 Statistik Penjualan</h3>
        <ul class="text-sm text-gray-600 space-y-1 mt-2">
            <li>Jumlah Produk: <span class="font-semibold text-gray-800">{{ $products->count() }}</span></li>
            <li>Total Produk Terjual: <span class="font-semibold text-gray-800">{{ $salesCount }}</span></li>
            <li>Total Pendapatan: <span class="font-semibold text-green-700">Rp{{ number_format($salesTotal, 0, ',', '.') }}</span></li>
        </ul>
    </div>

    {{-- 📦 Produk Dijual --}}
    <div class="bg-indigo-50 border border-indigo-100 shadow-md rounded-xl p-5 mb-8">
        <h3 class="text-lg font-semibold mb-4 text-indigo-700">📦 Produk yang Dijual</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
           @forelse ($products as $product)
    <div class="bg-white rounded-xl shadow hover:shadow-lg hover:bg-indigo-50 transition overflow-hidden relative">
    <div class="aspect-w-1 aspect-h-1 w-full">
        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('default-product.png') }}"
             alt="{{ $product->name }}"
             class="w-full h-40 object-cover">
    </div>
    <div class="p-4">
        <p class="font-semibold text-gray-800">{{ $product->name }}</p>
        <p class="text-green-600 text-sm mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-500">Stok: {{ $product->stock }}</p>


        {{-- Tombol Aksi --}}
        <div class="flex justify-between gap-2 border-t border-gray-200 pt-3 mt-auto">
            <a href="{{ route('products.edit', $product->id) }}"
               class="flex-1 text-center text-sm bg-blue-50 text-blue-700 border border-blue-200 py-1.5 rounded-lg hover:bg-blue-100 transition font-semibold">
                ✏️ Edit
            </a>

            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin hapus produk ini?')" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full text-sm bg-red-50 text-red-600 border border-red-200 py-1.5 rounded-lg hover:bg-red-100 transition font-semibold">
                    🗑️ Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@empty


                <p class="text-indigo-400 text-sm col-span-full">Belum ada produk.</p>
            @endforelse
        </div>
         {{-- 🔗 Pagination Produk Dijual --}}
            @if ($user->role === 'seller')
                <div class="mt-6 flex justify-center">
                    {{ $products->links('vendor.pagination.custom') }}
                </div>
            @endif
    </div>


@endif

</div>

<script>
    const pageContent = document.getElementById('page-content');
    const switchInput = document.getElementById('role-switch');
    const hiddenInput = document.getElementById('hidden-role');
    const labelCustomer = document.getElementById('label-customer');
    const labelSeller = document.getElementById('label-seller');
    const form = document.getElementById('role-form');

    // 🎨 Update warna label customer/seller sesuai switch
    function updateLabelColors() {
        if (switchInput.checked) {
            labelCustomer.classList.remove('text-blue-600');
            labelCustomer.classList.add('text-gray-400');
            labelSeller.classList.remove('text-gray-400');
            labelSeller.classList.add('text-blue-600');
            hiddenInput.value = 'seller';
        } else {
            labelCustomer.classList.remove('text-gray-400');
            labelCustomer.classList.add('text-blue-600');
            labelSeller.classList.remove('text-blue-600');
            labelSeller.classList.add('text-gray-400');
            hiddenInput.value = 'customer';
        }
    }

    // ⏳ Saat halaman udah dimuat, fade-in
    window.addEventListener('DOMContentLoaded', () => {
        pageContent.classList.remove('opacity-0');
        pageContent.classList.add('opacity-100');
    });

    // 🔁 Saat switch diubah, fade-out dulu baru submit
    switchInput.addEventListener('change', () => {
        updateLabelColors();

        // Fade-out dulu biar transisi smooth
        pageContent.classList.remove('opacity-100');
        pageContent.classList.add('opacity-0');

        // Setelah 300ms (durasi animasi), submit form
        setTimeout(() => {
            form.submit();
        }, 300);
    });

    // Inisialisasi warna label
    updateLabelColors();
</script>

<style>
/* Aktif (current page) */
[aria-current="page"] {
    background-color: #2563eb !important;
    color: white !important;
    font-weight: 600;
    border-radius: 0.5rem;
}

/* Tombol page biasa */
nav[aria-label="Pagination Navigation"] a {
    background-color: white !important;
    color: #2563eb !important;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: 0.2s;
    border: 1px solid #cbd5e0;
}

/* Hover */
nav[aria-label="Pagination Navigation"] a:hover {
    background-color: #ebf8ff !important;
}
</style>




@endsection
