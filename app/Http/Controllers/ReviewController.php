<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user has bought this product and the order is completed (delivered or paid/shipped)
        // Adjust the status based on your flow. Let's say order_status must be 'delivered' or 'completed'
        // or just has an order containing this item.
        $hasOrdered = Order::where('user_id', auth()->id())
            ->whereIn('order_status', ['shipped', 'delivered', 'completed']) // Or whatever statuses mean they got it
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->exists();

        if (!$hasOrdered) {
            return back()->with('toast', [
                'type' => 'danger',
                'message' => 'Anda harus membeli dan menerima produk ini sebelum memberikan ulasan.',
            ]);
        }

        // Check if already reviewed
        $existingReview = ProductReview::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return back()->with('toast', [
                'type' => 'danger',
                'message' => 'Anda sudah memberikan ulasan untuk produk ini.',
            ]);
        }

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
