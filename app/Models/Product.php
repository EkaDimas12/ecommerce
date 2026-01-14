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
}

