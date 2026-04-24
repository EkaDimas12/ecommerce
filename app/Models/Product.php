<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
  protected $fillable = [
    'category_id','name','slug','price','description',
    'is_best_seller','stock','main_image'
  ];

  public function category() {
    return $this->belongsTo(Category::class);
  }

  /**
   * Relasi ke model ProductReview.
   * Mengambil daftar ulasan untuk produk ini.
   */
  public function reviews() {
    return $this->hasMany(ProductReview::class);
  }

  /**
   * Mengambil rata-rata rating (bintang) berdasarkan ulasan pengguna.
   */
  public function getAverageRatingAttribute() {
    return $this->reviews()->avg('rating') ?? 0;
  }
}

