<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug']; // sesuaikan kolom di tabel categories

    // Relasi: 1 kategori punya banyak produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
