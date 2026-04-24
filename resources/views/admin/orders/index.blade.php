@extends('admin.layout')

@section('title', 'Pesanan')
@section('subtitle', 'Kelola semua pesanan')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 mb-6">
        <form action="" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Cari</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Kode, nama, atau telepon..."
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-shadow">
                </div>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Payment</label>
                <select name="payment_status" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cod" {{ request('payment_status') == 'cod' ? 'selected' : '' }}>COD</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                <select name="order_status" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <option value="">Semua</option>
                    <option value="new" {{ request('order_status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="processing" {{ request('order_status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('order_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm shadow-brand-500/20">Filter</button>
                <a href="{{ route('admin.orders.index') }}"
                    class="border border-slate-200 hover:bg-slate-50 px-4 py-2.5 rounded-xl text-sm transition-colors">Reset</a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3.5 text-center text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="font-mono text-[13px] font-semibold text-brand-600 hover:text-brand-700 hover:underline">{{ $order->code }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-800">{{ $order->customer_name }}</div>
                                        <div class="text-[12px] text-slate-400">{{ $order->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-800">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold rounded-lg
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
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold rounded-lg
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
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600">{{ $order->created_at->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Detail --}}
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition-colors"
                                        title="Detail">
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.orders.edit', $order) }}"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                        title="Edit">
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    </a>
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                        data-confirm="Yakin ingin menghapus pesanan {{ $order->code }}? Data pesanan akan dihapus permanen."
                                        data-confirm-title="Hapus Pesanan">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                            title="Hapus">
                                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                                        <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                                    </div>
                                    <span class="text-sm text-slate-400 font-medium">Tidak ada pesanan ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
