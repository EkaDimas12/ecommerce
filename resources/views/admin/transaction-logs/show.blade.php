@extends('admin.layout')

@section('title', 'Detail Log Transaksi')
@section('subtitle', 'Log #' . $log->id . ' — ' . $log->order_code)

@section('content')
    <div class="max-w-4xl">
        <!-- Back -->
        <a href="{{ route('admin.transaction-logs.index') }}"
            class="inline-flex items-center gap-2 text-slate-600 hover:text-indigo-600 font-medium mb-6">
            ← Kembali ke Log Transaksi
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Main Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    📋 Informasi Event
                </h2>

                <div class="space-y-4">
                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase mb-1">Event</div>
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full {{ $log->event_color }}">
                            {{ $log->event_label }}
                        </span>
                    </div>

                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase mb-1">Kode Pesanan</div>
                        <a href="{{ route('admin.orders.show', $log->order_id) }}"
                            class="font-mono font-bold text-indigo-600 hover:underline text-lg">{{ $log->order_code }}</a>
                    </div>

                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase mb-1">Sumber</div>
                        <div class="font-medium text-slate-800">{{ $log->source_label }}</div>
                    </div>

                    @if ($log->payment_type)
                        <div>
                            <div class="text-xs font-medium text-slate-500 uppercase mb-1">Tipe Pembayaran</div>
                            <div class="font-medium text-slate-800">{{ $log->payment_type }}</div>
                        </div>
                    @endif

                    @if ($log->transaction_status)
                        <div>
                            <div class="text-xs font-medium text-slate-500 uppercase mb-1">Status Midtrans</div>
                            <div class="font-mono text-sm text-slate-700 bg-slate-100 px-3 py-1.5 rounded-lg inline-block">{{ $log->transaction_status }}</div>
                        </div>
                    @endif

                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase mb-1">Waktu</div>
                        <div class="text-slate-800">{{ $log->created_at->format('d M Y, H:i:s') }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</div>
                    </div>

                    @if ($log->ip_address)
                        <div>
                            <div class="text-xs font-medium text-slate-500 uppercase mb-1">IP Address</div>
                            <div class="font-mono text-sm text-slate-700">{{ $log->ip_address }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Changes -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    🔄 Perubahan Status
                </h2>

                <div class="space-y-5">
                    <!-- Payment Status -->
                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase mb-2">Status Pembayaran</div>
                        @if ($log->payment_status_from || $log->payment_status_to)
                            <div class="flex items-center gap-3">
                                @if ($log->payment_status_from)
                                    <span class="px-3 py-1.5 text-sm font-medium rounded-lg bg-slate-100 text-slate-600">
                                        {{ strtoupper($log->payment_status_from) }}
                                    </span>
                                    <span class="text-slate-400 text-xl">→</span>
                                @endif
                                @if ($log->payment_status_to)
                                    <span class="px-3 py-1.5 text-sm font-bold rounded-lg
                                        @if($log->payment_status_to === 'paid') bg-emerald-100 text-emerald-700
                                        @elseif($log->payment_status_to === 'pending') bg-amber-100 text-amber-700
                                        @elseif($log->payment_status_to === 'cod') bg-blue-100 text-blue-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ strtoupper($log->payment_status_to) }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-slate-400 text-sm">Tidak ada perubahan</span>
                        @endif
                    </div>

                    <!-- Order Status -->
                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase mb-2">Status Pesanan</div>
                        @if ($log->order_status_from || $log->order_status_to)
                            <div class="flex items-center gap-3">
                                @if ($log->order_status_from)
                                    <span class="px-3 py-1.5 text-sm font-medium rounded-lg bg-slate-100 text-slate-600">
                                        {{ ucfirst($log->order_status_from) }}
                                    </span>
                                    <span class="text-slate-400 text-xl">→</span>
                                @endif
                                @if ($log->order_status_to)
                                    <span class="px-3 py-1.5 text-sm font-bold rounded-lg
                                        @if(in_array($log->order_status_to, ['delivered', 'done'])) bg-emerald-100 text-emerald-700
                                        @elseif($log->order_status_to === 'processing') bg-amber-100 text-amber-700
                                        @elseif($log->order_status_to === 'shipped') bg-blue-100 text-blue-700
                                        @elseif($log->order_status_to === 'cancelled') bg-red-100 text-red-700
                                        @else bg-slate-100 text-slate-600
                                        @endif">
                                        {{ ucfirst($log->order_status_to) }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-slate-400 text-sm">Tidak ada perubahan</span>
                        @endif
                    </div>
                </div>

                @if ($log->order)
                    <div class="mt-6 pt-5 border-t border-slate-200">
                        <h3 class="text-sm font-bold text-slate-700 mb-3">Info Pesanan Saat Ini</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-slate-500">Customer:</span>
                                <div class="font-medium text-slate-800">{{ $log->order->customer_name }}</div>
                            </div>
                            <div>
                                <span class="text-slate-500">Total:</span>
                                <div class="font-bold text-slate-800">Rp{{ number_format($log->order->total, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <span class="text-slate-500">Payment:</span>
                                <div class="font-medium">{{ strtoupper($log->order->payment_status) }}</div>
                            </div>
                            <div>
                                <span class="text-slate-500">Order:</span>
                                <div class="font-medium">{{ ucfirst($log->order->order_status) }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Metadata -->
        @if ($log->metadata)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mt-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    🔍 Metadata
                </h2>
                <pre class="bg-slate-900 text-slate-200 rounded-xl p-5 overflow-x-auto text-sm font-mono leading-relaxed">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        @endif

        <!-- Timeline: other logs for the same order -->
        @php
            $relatedLogs = \App\Models\TransactionLog::where('order_id', $log->order_id)
                ->orderBy('created_at', 'asc')
                ->get();
        @endphp
        @if ($relatedLogs->count() > 1)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mt-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    📜 Timeline Pesanan {{ $log->order_code }}
                </h2>
                <div class="relative">
                    <div class="absolute left-[18px] top-0 bottom-0 w-0.5 bg-slate-200"></div>
                    <div class="space-y-4">
                        @foreach ($relatedLogs as $related)
                            <div class="flex items-start gap-4 relative">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm z-10 shrink-0
                                    {{ $related->id === $log->id ? 'bg-indigo-600 text-white ring-4 ring-indigo-100' : 'bg-white border-2 border-slate-300 text-slate-500' }}">
                                    @if ($related->event === 'created') 🆕
                                    @elseif ($related->event === 'payment_success') ✅
                                    @elseif ($related->event === 'payment_failed' || $related->event === 'payment_expired') ❌
                                    @elseif ($related->event === 'cancelled') 🚫
                                    @elseif ($related->event === 'status_changed') 🔄
                                    @elseif ($related->event === 'stock_restored') 📦
                                    @else ⏳
                                    @endif
                                </div>
                                <div class="flex-1 pb-2 {{ $related->id === $log->id ? 'bg-indigo-50 -mx-2 px-3 py-2 rounded-xl' : '' }}">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-medium text-slate-800 text-sm">
                                            {{ $related->event_label }}
                                            @if ($related->id === $log->id)
                                                <span class="text-xs text-indigo-500 ml-1">(ini)</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-slate-400 whitespace-nowrap">{{ $related->created_at->format('d M H:i:s') }}</span>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        via {{ $related->source_label }}
                                        @if ($related->payment_type)
                                            • {{ $related->payment_type }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
