<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

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

        // Midtrans config for frontend
        $midtransClientKey = $this->midtrans->getClientKey();
        $midtransSnapUrl = $this->midtrans->getSnapUrl();

        return view('checkout.index', compact(
            'items', 
            'subtotal', 
            'weight', 
            'codWhitelist',
            'midtransClientKey',
            'midtransSnapUrl'
        ));
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

        // Validate - COD or Midtrans online
        $request->validate([
            'customer_name'   => 'required|string|max:120',
            'phone'           => 'required|string|max:30',
            'email'           => 'nullable|email|max:120',
            'address'         => 'required|string|max:500',

            'destination_id'    => 'nullable|integer',
            'destination_label' => 'nullable|string|max:255',
            'postal_code'       => 'nullable|string|max:10',

            'courier'         => 'nullable|string|max:30',
            'service'         => 'nullable|string|max:50',
            'shipping_cost'   => 'required|integer|min:0',

            'payment_method'  => 'required|in:cod,transfer',
        ]);

        $isPickup = ($request->service === 'pickup');
        $shippingCost = $isPickup ? 0 : (int)$request->shipping_cost;

        // COD whitelist check (only for COD)
        if ($request->payment_method === 'cod') {
            $whitelist = collect(explode(',', env('COD_CITY_WHITELIST', '')))
                ->map(fn ($v) => strtoupper(trim($v)))
                ->filter();

            $destCity = strtoupper($request->destination_label ?? '');
            $cityMatch = $whitelist->contains(function($city) use ($destCity) {
                return str_contains($destCity, $city);
            });

            // For pickup, COD is always allowed
            if (!$isPickup && !$cityMatch) {
                return back()->withErrors([
                    'payment_method' => 'COD hanya tersedia untuk wilayah tertentu. Silakan pilih pembayaran online.',
                ])->withInput();
            }
        }

        $subtotal = collect($items)->sum(fn ($i) => $i['price'] * $i['qty']);
        $total = $subtotal + $shippingCost;

        $code = 'TC-' . strtoupper(Str::random(8));

        // Determine initial payment status
        $paymentStatus = $request->payment_method === 'cod' ? 'cod' : 'pending';

        $order = Order::create([
            'code'           => $code,
            'customer_name'  => $request->customer_name,
            'phone'          => $request->phone,
            'email'          => $request->email,

            'address'        => $request->address,
            'city_id'        => (int)($request->destination_id ?? 0),
            'postal_code'    => $request->postal_code,

            'courier'        => $request->courier ?? 'pickup',
            'service'        => $isPickup ? 'pickup' : $request->service,
            'shipping_cost'  => $shippingCost,

            'subtotal'       => $subtotal,
            'total'          => $total,

            'payment_method' => $request->payment_method,
            'payment_status' => $paymentStatus,
            'order_status'   => 'new',
        ]);

        foreach ($items as $it) {
            OrderItem::create([
                'order_id'       => $order->id,
                'product_id'     => $it['product_id'],
                'name_snapshot'  => $it['name'],
                'price_snapshot' => $it['price'],
                'qty'            => $it['qty'],
                'subtotal'       => $it['price'] * $it['qty'],
            ]);
        }

        // Clear cart
        session()->forget('cart');

        // If online payment (transfer/midtrans), return snap token for frontend
        if ($request->payment_method === 'transfer') {
            // Get items from saved order (not from session which is now cleared)
            $orderItems = $order->items->map(fn($item) => [
                'product_id' => $item->product_id,
                'name' => $item->name_snapshot,
                'price' => $item->price_snapshot,
                'qty' => $item->qty,
            ])->toArray();

            $snapResult = $this->midtrans->createTransaction(
                [
                    'code' => $order->code,
                    'total' => $order->total,
                    'shipping_cost' => $order->shipping_cost,
                ],
                $orderItems,
                [
                    'name' => $order->customer_name,
                    'email' => $order->email ?? 'customer@tsaniacraft.com',
                    'phone' => $order->phone,
                    'address' => $order->address,
                ]
            );

            if ($snapResult && isset($snapResult['token'])) {
                // Return JSON for AJAX request
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'snap_token' => $snapResult['token'],
                        'order_code' => $order->code,
                    ]);
                }

                // For regular form, redirect to payment page with token
                return view('checkout.payment', [
                    'order' => $order,
                    'snapToken' => $snapResult['token'],
                    'midtransClientKey' => $this->midtrans->getClientKey(),
                    'midtransSnapUrl' => $this->midtrans->getSnapUrl(),
                ]);
            }

            // Fallback if snap token creation fails
            Log::error('Failed to create Midtrans token', ['order' => $order->code]);
            return redirect()->route('order.show', $order->code)->with('toast', [
                'type' => 'warning',
                'message' => 'Pesanan berhasil dibuat, tapi ada masalah dengan pembayaran online. Silakan hubungi admin.',
            ]);
        }

        // COD: redirect to print page
        return redirect()->route('order.print', $order->code);
    }
}
