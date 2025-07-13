<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
     use AuthorizesRequests;
    public function index(Request $request)
{
    $query = Product::query();

    // 🔍 Filter
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    if ($request->filled('min_stock')) {
        $query->where('stock', '>=', $request->min_stock);
    }

    $products = $query->latest()->paginate(20);

    // 🔁 Biar filter tetap terisi
    $categories = Product::select('category')->distinct()->pluck('category');

    return view('products.index', compact('products', 'categories'));
}



    public function create()
    {
        if (Auth::user()->role !== 'seller') {
            abort(403, 'Akses ditolak');
        }
        return view('products.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'seller') {
            abort(403, 'Hanya seller yang dapat menambahkan produk.');
        }

        $validated = $request->validate([
    'name' => 'required|string|max:255',
    'price' => 'required|numeric|min:0',
    'description' => 'required|string',
    'stock' => 'required|integer|min:0',
    'category' => 'required|string|max:100', // ⬅️ Tambahan ini
    'image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
]);


        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('dashboard')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function buy(Product $product, Request $request)
{
    $user = Auth::user();

    if ($user->role !== 'customer') {
        abort(403, 'Hanya customer yang bisa beli produk.');
    }

    if ($product->stock < 1) {
        return redirect()->back()->with('error', 'Stok produk habis.');
    }

    // Simpan ke orders
    $user->orders()->create([
        'product_id' => $product->id,
        'quantity' => 1, // opsional
    ]);

      // Kurangi stok
    $product->decrement('stock');

    // Flash message sukses
    return redirect()->back()->with('success', 'Produk berhasil dibeli!');
}



    public function show(Product $product)
    {
        $product->load('user');
        return view('products.show', compact('product'));
    }

    public function search(Request $request)
{
    $query = $request->input('query');

    $products = Product::where('name', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")
        ->get();

    return view('products.search', compact('products', 'query'));
}


    public function edit(Product $product)
{
    $this->authorize('update', $product); // Cek apakah dia pemiliknya

    return view('products.edit', compact('product'));
}


    public function update(Request $request, Product $product)
{
    $this->authorize('update', $product); // Wajib kalau pakai policy

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($validated);

    return redirect()->route('profile')->with('success', 'Produk berhasil diperbarui!');
}



    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('profile')->with('success', 'Product deleted successfully.');
    }

    public function dashboard(Request $request)
{
    $user = Auth::user();

    $query = $user->products()->newQuery();

    // Filter kategori
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // Sortir
    if ($request->sort === 'price_asc') {
        $query->orderBy('price', 'asc');
    } elseif ($request->sort === 'price_desc') {
        $query->orderBy('price', 'desc');
    } elseif ($request->sort === 'latest') {
        $query->latest();
    } else {
        $query->latest();
    }

    $products = $query->paginate(16)->withQueryString(); // biar pagination bawa filter juga
    $allCategories = Product::select('category')->distinct()->pluck('category');

    return view('dashboard', compact('products', 'user', 'allCategories'));
}




}
