<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Product extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'user_id',
        'stock',
        'category',
    ];


    // Relasi ke User (pemilik produk)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
        {
            return $this->hasMany(Order::class);
        }

    public function favorites()
        {
            return $this->hasMany(Favorite::class);
        }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }



}
