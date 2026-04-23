@extends('admin.layout')

@section('title', 'Log Transaksi')
@section('subtitle', 'Riwayat semua aktivitas pembayaran & pesanan')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-indigo-100 rounded-xl flex items-center justify-center text-xl">📋</div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ number_format($stats['total']) }}</div>
                    <div class="text-xs text-slate-500 font-medium">Total Log</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center text-xl">📅</div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ number_format($stats['today']) }}</div>
                    <div class="text-xs text-slate-500 font-medium">Hari Ini</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center text-xl">✅</div>
                <div>
                    <div class="text-2xl font-bold text-emerald-600">{{ number_format($stats['payment_success']) }}</div>
                    <div class="text-xs text-slate-500 font-medium">Sukses</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-red-100 rounded-xl flex items-center justify-center text-xl">❌</div>
                <div>
                    <div class="text-2xl font-bold text-red-600">{{ number_format($stats['payment_failed']) }}</div>
                    <div class="text-xs text-slate-500 font-medium">Gagal / Expired</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <form action="" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-slate-500 mb-1">Cari Kode Pesanan</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="TC-XXXXXXXX atau nama customer..."
                    class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Event</label>
                <select name="event" class="border border-slate-300 rounded-lg px-4 py-2">
                    <option value="">Semua Event</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Pesanan Dibuat</option>
                    <option value="payment_pending" {{ request('event') == 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                    <option value="payment_success" {{ request('event') == 'payment_success' ? 'selected' : '' }}>Payment Sukses</option>
                    <option value="payment_failed" {{ request('event') == 'payment_failed' ? 'selected' : '' }}>Payment Gagal</option>
                    <option value="payment_expired" {{ request('event') == 'payment_expired' ? 'selected' : '' }}>Payment Expired</option>
                    <option value="cancelled" {{ request('event') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="status_changed" {{ request('event') == 'status_changed' ? 'selected' : '' }}>Status Diubah</option>
                    <option value="stock_restored" {{ request('event') == 'stock_restored' ? 'selected' : '' }}>Stok Dikembalikan</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Sumber</label>
                <select name="source" class="border border-slate-300 rounded-lg px-4 py-2">
                    <option value="">Semua Sumber</option>
                    <option value="checkout" {{ request('source') == 'checkout' ? 'selected' : '' }}>Checkout</option>
                    <option value="midtrans_webhook" {{ request('source') == 'midtrans_webhook' ? 'selected' : '' }}>Midtrans</option>
                    <option value="admin" {{ request('source') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user_cancel" {{ request('source') == 'user_cancel' ? 'selected' : '' }}>User</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="border border-slate-300 rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="border border-slate-300 rounded-lg px-4 py-2">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">Filter</button>
                <a href="{{ route('admin.transaction-logs.index') }}"
                    class="border border-slate-300 hover:bg-slate-50 px-4 py-2 rounded-lg">Reset</a>
                <a href="{{ route('admin.transaction-logs.export', request()->query()) }}"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg font-medium inline-flex items-center gap-2">
                    📥 Export CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Kode Order</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Event</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Sumber</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Payment</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Order Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                                {{ $log->created_at->format('d M Y') }}
                                <br>
                                <span class="text-xs text-slate-400">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $log->order_id) }}"
                                    class="font-mono font-medium text-indigo-600 hover:underline">{{ $log->order_code }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $log->event_color }}">
                                    {{ $log->event_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-slate-600">{{ $log->source_label }}</span>
                                @if ($log->ip_address)
                                    <br><span class="text-xs text-slate-400">{{ $log->ip_address }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($log->payment_status_from && $log->payment_status_to)
                                    <span class="text-slate-400">{{ strtoupper($log->payment_status_from) }}</span>
                                    <span class="text-slate-300 mx-1">→</span>
                                    <span class="font-medium text-slate-700">{{ strtoupper($log->payment_status_to) }}</span>
                                @elseif ($log->payment_status_to)
                                    <span class="font-medium text-slate-700">{{ strtoupper($log->payment_status_to) }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($log->order_status_from && $log->order_status_to)
                                    <span class="text-slate-400">{{ ucfirst($log->order_status_from) }}</span>
                                    <span class="text-slate-300 mx-1">→</span>
                                    <span class="font-medium text-slate-700">{{ ucfirst($log->order_status_to) }}</span>
                                @elseif ($log->order_status_to)
                                    <span class="font-medium text-slate-700">{{ ucfirst($log->order_status_to) }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.transaction-logs.show', $log) }}"
                                    class="text-indigo-600 hover:underline text-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="text-4xl mb-3 opacity-50">📋</div>
                                Belum ada log transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection
