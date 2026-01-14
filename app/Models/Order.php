<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code',
        'customer_name',
        'phone',
        'email',
        'address',
        'city_id',
        'postal_code',
        'courier',
        'service',
        'shipping_cost',
        'subtotal',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
