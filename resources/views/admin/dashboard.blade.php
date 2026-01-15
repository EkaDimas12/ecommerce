@extends('admin.layout')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan data toko Anda')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">📁</div>
                <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-1 rounded-full">Katalog</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $categoryCount }}</div>
            <div class="text-slate-500 text-sm mb-3">Kategori</div>
            <a href="{{ route('admin.categories.index') }}"
                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Kelola →</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-2xl">🛍️</div>
                <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-1 rounded-full">Katalog</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $productCount }}</div>
            <div class="text-slate-500 text-sm mb-3">Produk</div>
            <a href="{{ route('admin.products.index') }}"
                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Kelola →</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-2xl">🛒</div>
                @if ($pendingOrders > 0)
                    <span
                        class="text-xs font-medium text-amber-600 bg-amber-100 px-2 py-1 rounded-full">{{ $pendingOrders }}
                        pending</span>
                @endif
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $orderCount }}</div>
            <div class="text-slate-500 text-sm mb-3">Pesanan</div>
            <a href="{{ route('admin.orders.index') }}"
                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Kelola →</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl">👥</div>
                <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-1 rounded-full">Users</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $userCount }}</div>
            <div class="text-slate-500 text-sm mb-3">Pengguna</div>
            <a href="{{ route('admin.users.index') }}"
                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Kelola →</a>
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
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">📈 Pesanan 7 Hari Terakhir</h3>
            <div style="height: 250px; position: relative;">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">📊 Status Pembayaran</h3>
            <div style="height: 250px; position: relative;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">⚡ Aksi Cepat</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors font-medium">
                ➕ Tambah Produk
            </a>
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors font-medium">
                📁 Tambah Kategori
            </a>
            <a href="{{ route('admin.orders.index') }}?payment_status=pending"
                class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors font-medium">
                ⏳ Pesanan Pending
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">🛒 Pesanan Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse(\App\Models\Order::latest()->take(5)->get() as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="font-mono text-indigo-600 hover:underline font-medium">{{ $order->code }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-800">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-800">
                                Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full 
                            @if ($order->payment_status === 'paid') bg-emerald-100 text-emerald-700
                            @elseif($order->payment_status === 'pending') bg-amber-100 text-amber-700
                            @else bg-slate-100 text-slate-600 @endif">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada pesanan</td>
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
                    backgroundColor: '#6366f1',
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Status Pie Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Pending', 'Paid', 'COD'],
                datasets: [{
                    data: [{{ $statusCounts['pending'] }}, {{ $statusCounts['paid'] }},
                        {{ $statusCounts['cod'] }}
                    ],
                    backgroundColor: ['#f59e0b', '#10b981', '#6366f1'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection
