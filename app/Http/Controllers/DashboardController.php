<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // Bisa null kalau belum login

        $query = Product::query()->where('stock', '>', 0);

        // 🔍 Filter kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // ⏳ Sortir
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest();
        }

        // Ambil produk
        $products = $query->paginate(16)->withQueryString();

        // Ambil kategori unik
        $allCategories = Product::select('category')->distinct()->pluck('category');

        // Kalau user login dan punya role (customer/seller), load favorites
        if ($user && in_array($user->role, ['customer', 'seller'])) {
            $user->load('favorites');
        }

        return view('dashboard', compact('user', 'products', 'allCategories'));
    }
}
