<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Menyimpan ulasan (review) dan rating dari pengguna untuk suatu produk.
     * 
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
        // Validasi input rating (wajib, 1-5) dan komentar (opsional)
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Cek apakah user yang sedang login sudah pernah membeli produk ini
        // dan status pesanannya sudah dalam tahap pengiriman atau selesai.
        // Ini mencegah spam review dari orang yang belum pernah mencoba produk.
        $hasOrdered = Order::where('user_id', auth()->id())
            ->whereIn('order_status', ['shipped', 'delivered', 'completed'])
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->exists();

        // Jika belum pernah beli, kembalikan error
        if (!$hasOrdered) {
            return back()->with('toast', [
                'type' => 'danger',
                'message' => 'Anda harus membeli dan menerima produk ini sebelum memberikan ulasan.',
            ]);
        }

        // Cek apakah user sudah pernah memberikan ulasan untuk produk yang sama sebelumnya.
        // Satu user hanya boleh memberikan satu ulasan per produk.
        $existingReview = ProductReview::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        // Jika sudah ada ulasan, tolak permintaan
        if ($existingReview) {
            return back()->with('toast', [
                'type' => 'danger',
                'message' => 'Anda sudah memberikan ulasan untuk produk ini.',
            ]);
        }

        // Simpan data ulasan baru ke dalam database
        ProductReview::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Terima kasih atas ulasan Anda!',
        ]);
    }
}
