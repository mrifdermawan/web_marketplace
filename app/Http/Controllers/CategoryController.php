<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    // ✅ Halaman daftar kategori
    public function index()
    {
        $categories = Category::all();
        return view('kategori.index', compact('categories'));
    }

    // ✅ Tampilkan produk berdasarkan kategori
    public function show($slug)
{
    $category = Category::where('slug', $slug)->firstOrFail();

    // Filter produk berdasarkan nama kategori (bukan relasi)
    $products = Product::where('category', $category->name)->latest()->paginate(12);

    return view('kategori.index', compact('category', 'products'));
}

}
