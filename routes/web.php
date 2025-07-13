<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🏠 Homepage — redirect langsung ke dashboard
Route::get('/', fn () => redirect()->route('dashboard'));

// 🔍 Search produk (public)
Route::get('/search', [SearchController::class, 'index'])->name('search');

// 🛍 Produk (lihat semua & detail — public)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 📦 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ⛔ Harus urut: create dulu, lalu show paling bawah (biar nggak bentrok)
Route::middleware('auth')->group(function () {
    // 🛍 CRUD Produk (khusus seller)
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products/create', 'create')->name('products.create');
        Route::post('/products', 'store')->name('products.store');
        Route::get('/products/{product}/edit', 'edit')->name('products.edit');
        Route::put('/products/{product}', 'update')->name('products.update');
        Route::delete('/products/{product}', 'destroy')->name('products.destroy');
    });

    // 🛍 Order & Beli
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/buy/{product}', [ProductController::class, 'buy'])->name('products.buy');

    // ❤️ Favorit produk
    Route::post('/products/{product}/favorite', [FavoriteController::class, 'toggle'])->name('products.favorite');

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 📦 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// 🧩 Kategori (public)
Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori.index');
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('kategori.show');

// 💬 Chatbot (akses publik)
Route::post('/chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');

// 🛍 Produk show (paling bawah biar gak nutup route lain)
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// 🔐 Auth routes (Laravel Breeze/Fortify/etc)
require __DIR__.'/auth.php';
