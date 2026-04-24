<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk menyimpan ulasan dan rating produk dari pelanggan.
 */
class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    /**
     * Relasi ke model User (Pengguna yang menulis ulasan).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Product (Produk yang diulas).
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
