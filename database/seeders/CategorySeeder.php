<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Elektronik',
            'Fashion Pria',
            'Fashion Wanita',
            'Kesehatan',
            'Kecantikan',
            'Rumah Tangga',
            'Olahraga',
            'Makanan & Minuman',
            'Bayi & Anak',
            'Hobi & Koleksi',
            'Otomotif',
            'Peralatan Kantor',
            'Alat Tulis & Buku',
            'Mainan',
            'Perlengkapan Dapur',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
