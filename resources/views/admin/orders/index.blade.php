@extends('admin.layout')

@section('title', 'Pesanan')
@section('subtitle', 'Kelola semua pesanan')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <form action="" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode, nama, atau telepon..."
                    class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <select name="payment_status" class="border border-slate-300 rounded-lg px-4 py-2">
                <option value="">Semua Payment</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="cod" {{ request('payment_status') == 'cod' ? 'selected' : '' }}>COD</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <select name="order_status" class="border border-slate-300 rounded-lg px-4 py-2">
                <option value="">Semua Status</option>
                <option value="new" {{ request('order_status') == 'new' ? 'selected' : '' }}>New</option>
                <option value="processing" {{ request('order_status') == 'processing' ? 'selected' : '' }}>Processing
                </option>
                <option value="shipped" {{ request('order_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">Filter</button>
            <a href="{{ route('admin.orders.index') }}"
                class="border border-slate-300 hover:bg-slate-50 px-4 py-2 rounded-lg">Reset</a>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Payment</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="font-mono font-medium text-indigo-600 hover:underline">{{ $order->code }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $order->customer_name }}</div>
                                <div class="text-sm text-slate-500">{{ $order->phone }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-800">
                                Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full 
                            @if ($order->payment_status === 'paid') bg-emerald-100 text-emerald-700
                            @elseif($order->payment_status === 'pending') bg-amber-100 text-amber-700
                            @elseif($order->payment_status === 'cod') bg-blue-100 text-blue-700
                            @else bg-red-100 text-red-700 @endif">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full 
                            @if ($order->order_status === 'delivered') bg-emerald-100 text-emerald-700
                            @elseif($order->order_status === 'shipped') bg-blue-100 text-blue-700
                            @elseif($order->order_status === 'processing') bg-amber-100 text-amber-700
                            @elseif($order->order_status === 'cancelled') bg-red-100 text-red-700
                            @else bg-slate-100 text-slate-600 @endif">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="text-indigo-600 hover:underline">Detail</a>
                                <a href="{{ route('admin.orders.edit', $order) }}"
                                    class="text-slate-600 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">Tidak ada pesanan ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
