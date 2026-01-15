<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code',
        'user_id',
        'customer_name',
        'phone',
        'email',
        'address',
        'city_id',
        'postal_code',
        'courier',
        'service',
        'tracking_number',
        'shipping_cost',
        'subtotal',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
