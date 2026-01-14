<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function show(string $code)
    {
        $order = Order::with('items')
            ->where('code', $code)
            ->firstOrFail();

        return view('order.show', compact('order'));
    }

    public function print(string $code)
{
    $order = \App\Models\Order::with('items')
        ->where('code', $code)
        ->firstOrFail();

    return view('order.print', compact('order'));
}

}
