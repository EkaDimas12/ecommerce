<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;

class OrderController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function show(string $code)
    {
        $order = Order::with('items')
            ->where('code', $code)
            ->firstOrFail();

        // Check if order needs payment (online payment with pending status)
        $needsPayment = $order->payment_method === 'transfer' && $order->payment_status === 'pending';
        $snapToken = null;
        $midtransClientKey = null;
        $midtransSnapUrl = null;

        if ($needsPayment) {
            // Get items for Midtrans
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
                $snapToken = $snapResult['token'];
                $midtransClientKey = $this->midtrans->getClientKey();
                $midtransSnapUrl = $this->midtrans->getSnapUrl();
            }
        }

        return view('order.show', compact(
            'order', 
            'needsPayment', 
            'snapToken', 
            'midtransClientKey', 
            'midtransSnapUrl'
        ));
    }

    public function print(string $code)
    {
        $order = Order::with('items')
            ->where('code', $code)
            ->firstOrFail();

        return view('order.print', compact('order'));
    }

    /**
     * Show order history for logged in user
     */
    public function history()
    {
        $orders = Order::where('email', '=', auth()->user()->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.history', compact('orders'));
    }

    /**
     * Cancel an order (only for new/pending orders)
     */
    public function cancel(string $code)
    {
        $order = Order::where('code', '=', $code)->firstOrFail();

        // Check if user owns this order
        if (auth()->user()->email !== $order->email) {
            return back()->with('toast', [
                'type' => 'danger',
                'message' => 'Anda tidak memiliki akses untuk membatalkan pesanan ini.',
            ]);
        }

        // Only allow cancellation for new or pending orders
        if (!in_array($order->order_status, ['new']) || 
            !in_array($order->payment_status, ['pending', 'cod'])) {
            return back()->with('toast', [
                'type' => 'danger',
                'message' => 'Pesanan ini tidak dapat dibatalkan.',
            ]);
        }

        $order->update([
            'order_status' => 'cancelled',
            'payment_status' => 'failed',
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Pesanan berhasil dibatalkan.',
        ]);
    }
}
