<?php
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🧑‍💼 Buat user seller dummy dulu
        User::factory()->create([
            'name' => 'Seller Satu',
            'email' => 'seller@example.com',
            'role' => 'seller',
        ]);

        // Lanjut seeding kategori & produk
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
