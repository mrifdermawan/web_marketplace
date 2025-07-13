<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::where('id', $request->product_id)->where('stock', '>', 0)->first();

        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan atau stok habis.');
}


        // ✅ Cek role user
        if (Auth::user()->role !== 'customer') {
            return back()->with('error', 'Hanya customer yang bisa membeli produk.');
        }

        // ✅ Cek apakah stok cukup
        if ($product->stock <= 0) {
            return back()->with('error', 'Stok produk habis.');
        }

        // ✅ Kurangi stok
        $product->decrement('stock');

        // ✅ Hapus produk jika stok sudah nol
        if ($product->stock <= 1) {
            // karena decrement belum terjadi di DB, maka kita pakai kondisi stock <= 1
            $product->delete();
        }

        // ✅ Simpan order
        Order::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        return back()->with('success', 'Pembelian berhasil! 🎉');
    }
}
