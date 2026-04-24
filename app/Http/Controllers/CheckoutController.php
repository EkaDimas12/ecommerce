<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TransactionLog;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

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
        $hasChanges = false;

        if (count($raw) > 0) {
            $productIds = array_keys($raw);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($raw as $key => $item) {
                // Skip if product deleted
                if (!isset($products[$key])) {
                    $hasChanges = true;
                    continue;
                }

                $product = $products[$key];
                $qty = max(1, (int)($item['qty'] ?? 1));

                // Check stock
                if ($product->stock !== null && $qty > $product->stock) {
                    $qty = max(1, $product->stock);
                    $hasChanges = true;
                }

                // Check price/name drift
                if (($item['price'] ?? 0) != $product->price || ($item['name'] ?? '') !== $product->name) {
                    $hasChanges = true;
                }

                $items[$key] = [
                    'product_id' => $product->id,
                    'name'       => $product->name,
                    'price'      => (int) $product->price,
                    'qty'        => $qty,
                    'weight'     => 200,
                    'image'      => $product->main_image,
                ];
            }

            if ($hasChanges) {
                session(['cart' => $items]);
            }
        }

        return [$items, $hasChanges];
    }

    public function index()
    {
        [$items, $hasChanges] = $this->cartItems();

        if ($hasChanges) {
            return redirect()->route('cart.index')->with('toast', [
                'type' => 'warning',
                'message' => 'Terdapat perubahan harga atau stok pada keranjang Anda. Silakan periksa kembali.',
            ]);
        }

        if (count($items) === 0) {
            return redirect()->route('cart.index')->with('toast', [
                'type' => 'danger',
                'message' => 'Keranjang masih kosong.',
            ]);
        }

        $subtotal = collect($items)->sum(fn ($i) => $i['price'] * $i['qty']);
        $weight   = collect($items)->sum(fn ($i) => $i['weight'] * $i['qty']);

        $codWhitelist = collect(explode(',', config('services.tsania.cod_city_whitelist', '')))
            ->map(fn ($v) => trim($v))
            ->filter()
            ->values();

        // Midtrans config for frontend
        $midtransClientKey = $this->midtrans->getClientKey();
        $midtransSnapUrl = $this->midtrans->getSnapUrl();

        $coupon = session('coupon');
        $discountAmount = 0;
        if ($coupon) {
            if ($coupon->type === 'fixed') {
                $discountAmount = $coupon->value;
            } else {
                $discountAmount = $subtotal * ($coupon->value / 100);
                if ($coupon->max_discount !== null && $discountAmount > $coupon->max_discount) {
                    $discountAmount = $coupon->max_discount;
                }
            }
            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }
        }

        return view('checkout.index', compact(
            'items', 
            'subtotal', 
            'weight', 
            'codWhitelist',
            'midtransClientKey',
            'midtransSnapUrl',
            'coupon',
            'discountAmount'
        ));
    }

    public function store(Request $request)
    {
        [$items, $hasChanges] = $this->cartItems();

        if ($hasChanges) {
            return redirect()->route('cart.index')->with('toast', [
                'type' => 'warning',
                'message' => 'Terdapat perubahan harga atau stok. Silakan periksa kembali keranjang Anda.',
            ]);
        }

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
            $whitelist = collect(explode(',', config('services.tsania.cod_city_whitelist', '')))
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

        // Apply coupon if exists in session
        $coupon = session('coupon');
        $discountAmount = 0;
        $couponCode = null;

        if ($coupon) {
            // Re-validate coupon
            $dbCoupon = \App\Models\Coupon::where('code', $coupon->code)->where('is_active', true)->first();
            if ($dbCoupon && (!$dbCoupon->expires_at || !$dbCoupon->expires_at->isPast()) && ($dbCoupon->usage_limit === null || $dbCoupon->used_count < $dbCoupon->usage_limit) && $subtotal >= $dbCoupon->min_purchase) {
                
                if ($dbCoupon->type === 'fixed') {
                    $discountAmount = $dbCoupon->value;
                } else {
                    $discountAmount = $subtotal * ($dbCoupon->value / 100);
                    if ($dbCoupon->max_discount !== null && $discountAmount > $dbCoupon->max_discount) {
                        $discountAmount = $dbCoupon->max_discount;
                    }
                }
                
                // Ensure discount does not exceed subtotal
                if ($discountAmount > $subtotal) {
                    $discountAmount = $subtotal;
                }

                $total = $subtotal - $discountAmount + $shippingCost;
                $couponCode = $dbCoupon->code;
            }
        }

        $code = 'TC-' . strtoupper(Str::random(8));

        // Determine initial payment status
        $paymentStatus = $request->payment_method === 'cod' ? 'cod' : 'pending';

        // Wrap in transaction to ensure consistency
        $order = DB::transaction(function () use ($request, $code, $isPickup, $shippingCost, $subtotal, $discountAmount, $couponCode, $total, $paymentStatus, $items) {
            $order = Order::create([
                'code'           => $code,
                'user_id'        => auth()->id(),
                'customer_name'  => $request->customer_name,
                'phone'          => $request->phone,
                'email'          => $request->email,

                'address'        => $request->address,
                'city_id'        => (int)($request->destination_id ?? 0),
                'postal_code'    => $request->postal_code,

                'courier'        => $request->courier ?? 'pickup',
                'service'        => $isPickup ? 'pickup' : $request->service,
                'shipping_cost'  => $shippingCost,
                'coupon_code'    => $couponCode,
                'discount_amount'=> $discountAmount,

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

                // Kurangi stok produk
                Product::where('id', $it['product_id'])
                    ->whereNotNull('stock')
                    ->decrement('stock', $it['qty']);
            }

            if ($couponCode) {
                \App\Models\Coupon::where('code', $couponCode)->increment('used_count');
            }

            return $order;
        });

        // Clear cart ONLY IF transaction is successful
        session()->forget('cart');
        session()->forget('coupon');

        // Log transaksi: pesanan baru dibuat
        TransactionLog::record($order, 'created', 'checkout', [
            'payment_type'       => $request->payment_method,
            'payment_status_to'  => $paymentStatus,
            'order_status_to'    => 'new',
            'metadata'           => [
                'payment_method' => $request->payment_method,
                'subtotal'       => $subtotal,
                'shipping_cost'  => $shippingCost,
                'total'          => $total,
                'item_count'     => count($items),
            ],
        ]);

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
