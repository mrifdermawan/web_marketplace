<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Tambahkan use di atas
use App\Models\Product;
use App\Models\Order;
use App\Models\Favorite;

/**
 * @property string|null $email_verified_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 */


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ Relasi ke Produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ✅ Relasi ke Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getProfilePhotoUrlAttribute()
{
    return $this->profile_photo_path
        ? asset('storage/' . $this->profile_photo_path)
        : asset('default-avatar.png');
}

    // ✅ Relasi ke Favorit
    public function favorites()
    {
        return $this->hasMany(\App\Models\Favorite::class);
    }
}
