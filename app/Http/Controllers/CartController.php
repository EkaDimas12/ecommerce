<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $request->qty;
        } else {
            $cart[$product->id] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => (int) $product->price,
                'qty'   => (int) $request->qty,
                'image' => $product->main_image,
            ];
        }

        session()->put('cart', $cart);

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to);
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
        return back();
    }
}
