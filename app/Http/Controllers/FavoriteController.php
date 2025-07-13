<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Product $product)
    {
        $user = Auth::user();

        // Cek apakah sudah difavoritkan sebelumnya
        $existing = Favorite::where('user_id', $user->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($existing) {
            // Kalau sudah ada, hapus (unfavorite)
            $existing->delete();
            return back()->with('success', 'Produk dihapus dari favorit.');
        } else {
            // Kalau belum, tambahkan ke favorit
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            return back()->with('success', 'Produk ditambahkan ke favorit!');
        }
    }
}
