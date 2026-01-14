<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function cartItems(): array
    {
        $raw = session('cart', []);
        $items = [];

        foreach ($raw as $key => $item) {
            $items[$key] = [
                'product_id' => $item['product_id'] ?? ($item['id'] ?? $key),
                'name'       => $item['name'] ?? 'Produk',
                'price'      => (int)($item['price'] ?? 0),
                'qty'        => max(1, (int)($item['qty'] ?? 1)),
                'weight'     => (int)($item['weight'] ?? 200),
                'image'      => $item['image'] ?? null,
            ];
        }

        session(['cart' => $items]);
        return $items;
    }

    public function index()
    {
        $items = $this->cartItems();

        if (count($items) === 0) {
            return redirect()->route('cart.index')->with('toast', [
                'type' => 'danger',
                'message' => 'Keranjang masih kosong.',
            ]);
        }

        $subtotal = collect($items)->sum(fn ($i) => $i['price'] * $i['qty']);
        $weight   = collect($items)->sum(fn ($i) => $i['weight'] * $i['qty']);

        $codWhitelist = collect(explode(',', env('COD_CITY_WHITELIST', '')))
            ->map(fn ($v) => trim($v))
            ->filter()
            ->values();

        return view('checkout.index', compact('items', 'subtotal', 'weight', 'codWhitelist'));
    }

    public function store(Request $request)
    {
        $items = $this->cartItems();

        if (count($items) === 0) {
            return redirect()->route('cart.index')->with('toast', [
                'type' => 'danger',
                'message' => 'Keranjang masih kosong.',
            ]);
        }

        // COD only: payment_method wajib cod
        $request->validate([
            'customer_name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:120',
            'address' => 'required|string|max:500',

            'destination_city_id' => 'required|integer',
            'postal_code' => 'nullable|string|max:10',

            'courier' => 'required|string|in:jne,pos,tiki',
            'service' => 'nullable|string|max:30',
            'shipping_cost' => 'required|integer|min:0',

            'payment_method' => 'required|in:cod',
        ]);

        // Support pickup
        $isPickup = ($request->service === 'pickup');
        if ($isPickup) {
            $request->merge(['shipping_cost' => 0]);
        } else {
            // Jika bukan pickup dan ongkir > 0, service harus ada
            if ((int)$request->shipping_cost > 0 && empty($request->service)) {
                return back()->withErrors([
                    'service' => 'Silakan pilih layanan pengiriman.',
                ])->withInput();
            }
        }

        // COD whitelist check
        $whitelist = collect(explode(',', env('COD_CITY_WHITELIST', '')))
            ->map(fn ($v) => (int)trim($v))
            ->filter();

        if (!$whitelist->contains((int)$request->destination_city_id)) {
            return back()->withErrors([
                'payment_method' => 'COD hanya tersedia untuk wilayah tertentu.',
            ])->withInput();
        }

        $subtotal = collect($items)->sum(fn ($i) => $i['price'] * $i['qty']);
        $shipping = (int)$request->shipping_cost;
        $total = $subtotal + $shipping;

        $code = 'TC-' . strtoupper(Str::random(8));

        $order = Order::create([
            'code' => $code,
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'email' => $request->email,

            'address' => $request->address,
            'city_id' => (int)$request->destination_city_id,
            'postal_code' => $request->postal_code,

            'courier' => $request->courier,
            'service' => $isPickup ? 'pickup' : $request->service,
            'shipping_cost' => $shipping,

            'subtotal' => $subtotal,
            'total' => $total,

            'payment_method' => 'cod',
            'payment_status' => 'cod',
            'order_status' => 'new',
        ]);

        foreach ($items as $it) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $it['product_id'],
                'name_snapshot' => $it['name'],
                'price_snapshot' => $it['price'],
                'qty' => $it['qty'],
                'subtotal' => $it['price'] * $it['qty'],
            ]);
        }

        // Kosongkan cart dan tampilkan struk
        session()->forget('cart');

// ⬇️ LANGSUNG KE HALAMAN CETAK STRUK
return redirect()->route('order.print', $order->code);

    }
}
