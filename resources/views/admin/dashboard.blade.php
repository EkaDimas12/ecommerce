@extends('admin.layout')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan data toko Anda')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 bg-brand-50 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/></svg>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg uppercase tracking-wider">Katalog</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $categoryCount }}</div>
            <div class="text-slate-400 text-[13px] font-medium mt-0.5 mb-3">Kategori</div>
            <a href="{{ route('admin.categories.index') }}"
                class="text-brand-600 hover:text-brand-700 text-[13px] font-semibold inline-flex items-center gap-1">
                Kelola
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg uppercase tracking-wider">Katalog</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $productCount }}</div>
            <div class="text-slate-400 text-[13px] font-medium mt-0.5 mb-3">Produk</div>
            <a href="{{ route('admin.products.index') }}"
                class="text-brand-600 hover:text-brand-700 text-[13px] font-semibold inline-flex items-center gap-1">
                Kelola
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                </div>
                @if ($pendingOrders > 0)
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg ring-1 ring-amber-200">{{ $pendingOrders }} pending</span>
                @endif
            </div>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $orderCount }}</div>
            <div class="text-slate-400 text-[13px] font-medium mt-0.5 mb-3">Pesanan</div>
            <a href="{{ route('admin.orders.index') }}"
                class="text-brand-600 hover:text-brand-700 text-[13px] font-semibold inline-flex items-center gap-1">
                Kelola
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 bg-purple-50 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg uppercase tracking-wider">Users</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $userCount }}</div>
            <div class="text-slate-400 text-[13px] font-medium mt-0.5 mb-3">Pengguna</div>
            <a href="{{ route('admin.users.index') }}"
                class="text-brand-600 hover:text-brand-700 text-[13px] font-semibold inline-flex items-center gap-1">
                Kelola
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>

    <!-- Charts Section -->
    @php
        $last7Days = collect();
        $orderCounts = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $last7Days->push($date->format('d M'));
            $orderCounts->push(\App\Models\Order::whereDate('created_at', $date->toDateString())->count());
        }

        $statusCounts = [
            'pending' => \App\Models\Order::where('payment_status', 'pending')->count(),
            'paid' => \App\Models\Order::where('payment_status', 'paid')->count(),
            'cod' => \App\Models\Order::where('payment_status', 'cod')->count(),
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Orders Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 bg-brand-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Pesanan 7 Hari Terakhir</h3>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Status Pembayaran</h3>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 mb-8">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Aksi Cepat</h3>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl transition-colors text-sm font-medium shadow-sm shadow-brand-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Tambah Produk
            </a>
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-700 hover:bg-slate-800 text-white rounded-xl transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m4.06-7.19l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/></svg>
                Tambah Kategori
            </a>
            <a href="{{ route('admin.orders.index') }}?payment_status=pending"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Pesanan Pending
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Pesanan Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse(\App\Models\Order::latest()->take(5)->get() as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3.5">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="font-mono text-[13px] font-semibold text-brand-600 hover:text-brand-700 hover:underline">{{ $order->code }}</a>
                            </td>
                            <td class="px-6 py-3.5 text-sm text-slate-700">{{ $order->customer_name }}</td>
                            <td class="px-6 py-3.5 text-sm font-semibold text-slate-800">
                                Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold rounded-lg
                                    @if ($order->payment_status === 'paid') bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200
                                    @elseif($order->payment_status === 'pending') bg-amber-50 text-amber-600 ring-1 ring-amber-200
                                    @elseif($order->payment_status === 'cod') bg-blue-50 text-blue-600 ring-1 ring-blue-200
                                    @else bg-slate-50 text-slate-500 ring-1 ring-slate-200 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        @if ($order->payment_status === 'paid') bg-emerald-500
                                        @elseif($order->payment_status === 'pending') bg-amber-500
                                        @elseif($order->payment_status === 'cod') bg-blue-500
                                        @else bg-slate-400 @endif"></span>
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-sm text-slate-400">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">Belum ada pesanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // Orders Bar Chart
        new Chart(document.getElementById('ordersChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($last7Days) !!},
                datasets: [{
                    label: 'Pesanan',
                    data: {!! json_encode($orderCounts) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    hoverBackgroundColor: '#4f46e5',
                    borderRadius: 8,
                    maxBarThickness: 36
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 11 }, color: '#94a3b8' },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#94a3b8' }
                    }
                }
            }
        });

        // Status Doughnut Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Paid', 'COD'],
                datasets: [{
                    data: [{{ $statusCounts['pending'] }}, {{ $statusCounts['paid'] }}, {{ $statusCounts['cod'] }}],
                    backgroundColor: ['#f59e0b', '#10b981', '#6366f1'],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 12 }, padding: 16, usePointStyle: true, pointStyleWidth: 8 }
                    }
                }
            }
        });
    </script>
@endsection
