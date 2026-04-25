<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = auth()->user()->wishlistedProducts()
            ->with('category')
            ->latest()
            ->get();

        return view('profile.wishlist', compact('wishlistItems'));
    }

    public function toggle(Product $product)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
            $message = 'Produk dihapus dari wishlist.';
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);
            $status = 'added';
            $message = 'Produk ditambahkan ke wishlist.';
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ]);
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => $message
        ]);
    }
}
