@extends('admin.layout')

@section('title', 'Edit Pesanan')
@section('subtitle', $order->code)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:underline">← Kembali ke Pesanan</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 max-w-2xl">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status Pembayaran *</label>
                    <select name="payment_status" class="w-full border border-slate-300 rounded-lg px-4 py-2.5" required>
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cod" {{ $order->payment_status == 'cod' ? 'selected' : '' }}>COD</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status Order *</label>
                    <select name="order_status" class="w-full border border-slate-300 rounded-lg px-4 py-2.5" required>
                        <option value="new" {{ $order->order_status == 'new' ? 'selected' : '' }}>New</option>
                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing
                        </option>
                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered
                        </option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">No. Resi / AWB</label>
                <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}"
                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5"
                    placeholder="Masukkan nomor resi untuk tracking">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Internal</label>
                <textarea name="notes" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2.5"
                    placeholder="Catatan untuk admin...">{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.orders.show', $order) }}"
                    class="border border-slate-300 hover:bg-slate-50 px-6 py-2.5 rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
