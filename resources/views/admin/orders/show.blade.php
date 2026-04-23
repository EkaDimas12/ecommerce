@extends('admin.layout')

@section('title', 'Detail Pesanan')
@section('subtitle', $order->code)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-brand-600 font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke Pesanan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Informasi Pesanan</h3>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kode Pesanan</span>
                        <p class="font-mono font-bold text-slate-800 mt-1">{{ $order->code }}</p>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Tanggal</span>
                        <p class="font-medium text-slate-800 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Status Pembayaran</span>
                        <p class="mt-1.5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg
                                @if ($order->payment_status === 'paid') bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200
                                @elseif($order->payment_status === 'pending') bg-amber-50 text-amber-600 ring-1 ring-amber-200
                                @elseif($order->payment_status === 'cod') bg-blue-50 text-blue-600 ring-1 ring-blue-200
                                @else bg-red-50 text-red-600 ring-1 ring-red-200 @endif">
                                <span class="w-1.5 h-1.5 rounded-full
                                    @if ($order->payment_status === 'paid') bg-emerald-500
                                    @elseif($order->payment_status === 'pending') bg-amber-500
                                    @elseif($order->payment_status === 'cod') bg-blue-500
                                    @else bg-red-500 @endif"></span>
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Status Order</span>
                        <p class="mt-1.5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg
                                @if ($order->order_status === 'delivered') bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200
                                @elseif($order->order_status === 'shipped') bg-blue-50 text-blue-600 ring-1 ring-blue-200
                                @elseif($order->order_status === 'processing') bg-amber-50 text-amber-600 ring-1 ring-amber-200
                                @elseif($order->order_status === 'cancelled') bg-red-50 text-red-600 ring-1 ring-red-200
                                @else bg-slate-50 text-slate-500 ring-1 ring-slate-200 @endif">
                                <span class="w-1.5 h-1.5 rounded-full
                                    @if ($order->order_status === 'delivered') bg-emerald-500
                                    @elseif($order->order_status === 'shipped') bg-blue-500
                                    @elseif($order->order_status === 'processing') bg-amber-500
                                    @elseif($order->order_status === 'cancelled') bg-red-500
                                    @else bg-slate-400 @endif"></span>
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Item Pesanan</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($order->items as $item)
                        <div class="py-3.5 flex justify-between items-center">
                            <div>
                                <span class="text-sm font-medium text-slate-800">{{ $item->product_name }}</span>
                                <span class="text-slate-400 text-sm ml-1">× {{ $item->quantity }}</span>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-slate-200 mt-4 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Subtotal</span>
                        <span class="text-slate-600">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Ongkir ({{ $order->courier }} - {{ $order->service }})</span>
                        <span class="text-slate-600">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-3 border-t border-slate-100">
                        <span class="text-slate-800">Total</span>
                        <span class="text-brand-600">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Customer</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Nama</span>
                        <p class="font-medium text-slate-800 mt-1">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Telepon</span>
                        <p class="font-medium text-slate-800 mt-1">{{ $order->phone }}</p>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Email</span>
                        <p class="font-medium text-slate-800 mt-1">{{ $order->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Alamat Pengiriman</h3>
                </div>
                <p class="text-sm text-slate-700 leading-relaxed">{{ $order->address }}</p>
                <p class="text-xs text-slate-400 mt-2">Kode Pos: {{ $order->postal_code }}</p>
                @if ($order->tracking_number)
                    <div class="mt-4 p-3.5 bg-brand-50 rounded-xl border border-brand-100">
                        <span class="text-[11px] font-semibold text-brand-500 uppercase tracking-wider">No. Resi</span>
                        <p class="font-mono font-bold text-brand-700 mt-0.5">{{ $order->tracking_number }}</p>
                    </div>
                @endif
            </div>

            @if ($order->notes)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Catatan</h3>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $order->notes }}</p>
                </div>
            @endif

            <a href="{{ route('admin.orders.edit', $order) }}"
                class="flex items-center justify-center gap-2 w-full bg-brand-600 hover:bg-brand-700 text-white py-3 rounded-xl font-medium text-sm transition-colors shadow-sm shadow-brand-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit Pesanan
            </a>
        </div>
    </div>
@endsection
