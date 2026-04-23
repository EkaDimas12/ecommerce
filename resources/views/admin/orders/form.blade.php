@extends('admin.layout')

@section('title', 'Edit Pesanan')
@section('subtitle', $order->code)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.show', $order) }}"
            class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-brand-600 font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke Detail
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Update Status Pesanan</h3>
        </div>

        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Status Pembayaran *</label>
                    <select name="payment_status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cod" {{ $order->payment_status == 'cod' ? 'selected' : '' }}>COD</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Status Order *</label>
                    <select name="order_status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        <option value="new" {{ $order->order_status == 'new' ? 'selected' : '' }}>New</option>
                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">No. Resi / AWB</label>
                <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                    placeholder="Masukkan nomor resi untuk tracking">
            </div>

            <div class="mb-6">
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Catatan Internal</label>
                <textarea name="notes" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                    placeholder="Catatan untuk admin...">{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm shadow-brand-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.orders.show', $order) }}"
                    class="border border-slate-200 hover:bg-slate-50 px-6 py-2.5 rounded-xl text-sm transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
