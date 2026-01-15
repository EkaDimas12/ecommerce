<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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

        $order->update([
            'payment_status' => $request->payment_status,
            'order_status' => $request->order_status,
            'tracking_number' => $request->tracking_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus!');
    }
}
