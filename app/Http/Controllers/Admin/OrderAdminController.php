<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        // Filter by status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.form', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,cod,failed',
            'order_status' => 'required|in:new,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $wasNotCancelled = $order->order_status !== 'cancelled';
        $willBeCancelled = $request->order_status === 'cancelled';

        $prevPaymentStatus = $order->payment_status;
        $prevOrderStatus   = $order->order_status;

        DB::transaction(function () use ($order, $request, $wasNotCancelled, $willBeCancelled, $prevPaymentStatus, $prevOrderStatus) {
            $order->update([
                'payment_status' => $request->payment_status,
                'order_status' => $request->order_status,
                'tracking_number' => $request->tracking_number,
                'notes' => $request->notes,
            ]);

            // Jika status berubah menjadi cancelled, kembalikan stok
            if ($wasNotCancelled && $willBeCancelled) {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)
                        ->whereNotNull('stock')
                        ->increment('stock', $item->qty);
                }
            }

            // Log transaksi jika ada perubahan status
            if ($prevPaymentStatus !== $request->payment_status || $prevOrderStatus !== $request->order_status) {
                TransactionLog::record($order, 'status_changed', 'admin', [
                    'payment_status_from' => $prevPaymentStatus,
                    'payment_status_to'   => $request->payment_status,
                    'order_status_from'   => $prevOrderStatus,
                    'order_status_to'     => $request->order_status,
                    'metadata'            => [
                        'updated_by'      => auth()->user()->name ?? 'admin',
                        'tracking_number' => $request->tracking_number,
                    ],
                ]);
            }
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            // Jika pesanan belum dibatalkan, kembalikan stok terlebih dahulu
            if ($order->order_status !== 'cancelled') {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)
                        ->whereNotNull('stock')
                        ->increment('stock', $item->qty);
                }
            }

            $order->items()->delete();
            $order->delete();
        });
        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus!');
    }
}
