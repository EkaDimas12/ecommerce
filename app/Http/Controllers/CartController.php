<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $updatedCart = [];
        $hasChanges = false;

        if (count($cart) > 0) {
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $id => $item) {
                // Jika produk sudah dihapus dari database, lewati (hapus dari keranjang)
                if (!isset($products[$id])) {
                    $hasChanges = true;
                    continue;
                }

                $product = $products[$id];

                // Update detail terbaru
                $updatedCart[$id] = [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'price' => (int) $product->price,
                    'qty'   => (int) $item['qty'],
                    'image' => $product->main_image,
                    'weight' => 200,
                ];

                // Sesuaikan qty jika stok kurang
                if ($product->stock !== null && $updatedCart[$id]['qty'] > $product->stock) {
                    $updatedCart[$id]['qty'] = max(1, $product->stock);
                    $hasChanges = true;
                }

                // Cek perubahan harga/nama
                if ($item['price'] !== $updatedCart[$id]['price'] || $item['name'] !== $updatedCart[$id]['name']) {
                    $hasChanges = true;
                }
            }

            if ($hasChanges) {
                session()->put('cart', $updatedCart);
                $cart = $updatedCart;
            }
        }

        return view('cart.index', compact('cart', 'hasChanges'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);
        $currentQty = isset($cart[$product->id]) ? $cart[$product->id]['qty'] : 0;
        $newQty = $currentQty + $request->qty;

        if ($product->stock !== null && $newQty > $product->stock) {
            return back()->with('toast', [
                'type' => 'danger',
                'message' => 'Stok produk tidak mencukupi. Sisa stok: ' . $product->stock,
            ]);
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] = $newQty;
        } else {
            $cart[$product->id] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => (int) $product->price,
                'qty'   => (int) $request->qty,
                'image' => $product->main_image,
                'weight' => 200, // Make sure weight is present
            ];
        }

        session()->put('cart', $cart);

        if ($request->filled('redirect_to')) {
            $url = $request->redirect_to;
            // Only allow internal redirects (relative paths)
            if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
                return redirect($url);
            }
        }

        return back();
    }

   public function remove(Request $request)
{
    $cart = session()->get('cart', []);
    unset($cart[$request->id]);
    session()->put('cart', $cart);

    return back()->with('toast', [
        'type' => 'success',
        'message' => 'Produk dihapus dari keranjang',
    ]);
}


    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon');
        return back();
    }

    /**
     * Memproses dan memvalidasi penggunaan kupon yang diinputkan pengguna.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        // Cari kupon berdasarkan kode yang dimasukkan dan pastikan aktif
        $coupon = \App\Models\Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return back()->with('toast', ['type' => 'danger', 'message' => 'Kupon tidak valid atau sudah tidak aktif.']);
        }

        // Cek apakah tanggal kedaluwarsa kupon sudah terlewat
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return back()->with('toast', ['type' => 'danger', 'message' => 'Kupon sudah kedaluwarsa.']);
        }

        // Cek apakah batas jumlah penggunaan kupon sudah penuh
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return back()->with('toast', ['type' => 'danger', 'message' => 'Batas penggunaan kupon ini sudah habis.']);
        }

        // Hitung subtotal keranjang saat ini untuk mengecek syarat minimal belanja
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }

        // Tolak kupon jika belanjaan kurang dari batas minimum
        if ($subtotal < $coupon->min_purchase) {
            return back()->with('toast', [
                'type' => 'danger',
                'message' => 'Minimal belanja untuk kupon ini adalah Rp' . number_format($coupon->min_purchase, 0, ',', '.')
            ]);
        }

        // Simpan data kupon ke dalam session pengguna
        session()->put('coupon', $coupon);

        return back()->with('toast', ['type' => 'success', 'message' => 'Kupon berhasil digunakan!']);
    }

    /**
     * Menghapus atau membatalkan penggunaan kupon dari session.
     */
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('toast', ['type' => 'success', 'message' => 'Kupon dibatalkan.']);
    }
}
