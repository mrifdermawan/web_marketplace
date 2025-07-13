<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Cari seller pertama (asumsi role seller sudah ada)
        $seller = \App\Models\User::where('role', 'seller')->first();

        if (!$seller) {
            $this->command->warn('❌ Gagal seeding: User dengan role seller tidak ditemukan.');
            return;
        }

        // Buat 50 produk dummy
        for ($i = 0; $i < 50; $i++) {
            Product::create([
                'user_id' => $seller->id,
                'name' => 'Produk ' . Str::random(5),
                'description' => fake()->sentence(),
                'price' => fake()->numberBetween(10000, 500000),
                'stock' => fake()->numberBetween(1, 100),
                'category' => fake()->randomElement(['Elektronik', 'Fashion', 'Makanan', 'Aksesoris']),
                'image' => 'default-product.png', // kamu bisa ganti kalau punya file
            ]);
        }
    }
}
