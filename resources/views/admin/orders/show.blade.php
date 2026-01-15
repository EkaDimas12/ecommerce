@extends('admin.layout')

@section('title', 'Detail Pesanan')
@section('subtitle', $order->code)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:underline">← Kembali ke Pesanan</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Pesanan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-500 text-sm">Kode Pesanan</span>
                        <p class="font-mono font-medium text-slate-800">{{ $order->code }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-sm">Tanggal</span>
                        <p class="font-medium text-slate-800">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-sm">Status Pembayaran</span>
                        <p><span
                                class="px-2.5 py-1 text-xs font-medium rounded-full 
                        @if ($order->payment_status === 'paid') bg-emerald-100 text-emerald-700
                        @elseif($order->payment_status === 'pending') bg-amber-100 text-amber-700
                        @else bg-slate-100 text-slate-600 @endif">
                                {{ strtoupper($order->payment_status) }}
                            </span></p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-sm">Status Order</span>
                        <p><span
                                class="px-2.5 py-1 text-xs font-medium rounded-full 
                        @if ($order->order_status === 'delivered') bg-emerald-100 text-emerald-700
                        @elseif($order->order_status === 'shipped') bg-blue-100 text-blue-700
                        @else bg-slate-100 text-slate-600 @endif">
                                {{ ucfirst($order->order_status) }}
                            </span></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Item Pesanan</h3>
                <div class="divide-y divide-slate-200">
                    @foreach ($order->items as $item)
                        <div class="py-3 flex justify-between">
                            <div>
                                <span class="font-medium text-slate-800">{{ $item->product_name }}</span>
                                <span class="text-slate-500">× {{ $item->quantity }}</span>
                            </div>
                            <span
                                class="font-medium">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-slate-200 mt-4 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal</span>
                        <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Ongkir ({{ $order->courier }} - {{ $order->service }})</span>
                        <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                        <span>Total</span>
                        <span class="text-indigo-600">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Customer</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-slate-500 text-sm">Nama</span>
                        <p class="font-medium text-slate-800">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-sm">Telepon</span>
                        <p class="font-medium text-slate-800">{{ $order->phone }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-sm">Email</span>
                        <p class="font-medium text-slate-800">{{ $order->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Alamat Pengiriman</h3>
                <p class="text-slate-700">{{ $order->address }}</p>
                <p class="text-slate-500 text-sm mt-2">Kode Pos: {{ $order->postal_code }}</p>
                @if ($order->tracking_number)
                    <div class="mt-4 p-3 bg-indigo-50 rounded-lg">
                        <span class="text-indigo-600 text-sm font-medium">No. Resi:</span>
                        <p class="font-mono font-bold text-indigo-800">{{ $order->tracking_number }}</p>
                    </div>
                @endif
            </div>

            <a href="{{ route('admin.orders.edit', $order) }}"
                class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-medium">
                Edit Pesanan
            </a>
        </div>
    </div>
@endsection
