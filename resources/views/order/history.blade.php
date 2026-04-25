@extends('profile.layout')

@section('title', 'Pesanan Saya — Tsania Craft')

@section('dashboard-content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-6">
            <h1 class="text-2xl font-black text-ink">Pesanan Saya</h1>
            <p class="text-sm text-ink/60 mt-1">Lihat status dan riwayat pesanan kamu di sini.</p>
        </div>

        @if ($orders->isEmpty())
            <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-12 text-center">
                <div class="text-5xl mb-4 opacity-50">📦</div>
                <h3 class="text-xl font-bold text-ink">Belum Ada Pesanan</h3>
                <p class="text-sm text-ink/60 mt-2">Kamu belum membuat pesanan apapun.</p>
                <a href="{{ route('products.index') }}" class="btn-primary inline-block mt-6 px-8 py-3">
                    🛍️ Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-6">
                        <div class="flex justify-between items-start gap-4 flex-wrap">
                            <div>
                                <div class="text-[10px] uppercase font-bold tracking-widest text-ink/40">Order Code</div>
                                <div class="text-lg font-black text-ink">{{ $order->code }}</div>
                                <div class="text-xs text-ink/50 mt-1">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusClasses = [
                                        'pending'    => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'paid'       => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'shipped'    => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'delivered'  => 'bg-green-100 text-green-700 border-green-200',
                                        'cancelled'  => 'bg-red-100 text-red-700 border-red-200',
                                        'new'        => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ];
                                    $class = $statusClasses[$order->order_status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="px-3 py-1 rounded-full border text-[10px] font-bold uppercase tracking-wider {{ $class }}">
                                    {{ $order->order_status }}
                                </span>
                                <div class="text-xl font-black text-inlove mt-2">
                                    Rp{{ number_format($order->total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-humble/10 flex gap-3 flex-wrap">
                            <a href="{{ route('order.show', $order->code) }}" class="px-5 py-2.5 bg-bubble/20 text-inlove font-bold rounded-xl text-sm hover:bg-bubble/30 transition">
                                Lihat Detail →
                            </a>

                            {{-- Tombol Batalkan (hanya untuk order baru yang belum diproses) --}}
                            @if ($order->order_status === 'new' && in_array($order->payment_status, ['pending', 'cod']))
                                <form action="{{ route('order.cancel', $order->code) }}" method="POST"
                                    class="confirm-cancel-form"
                                    data-confirm-msg="Yakin ingin membatalkan pesanan {{ $order->code }}?">
                                    @csrf
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl text-sm border border-red-100 hover:bg-red-100 transition">
                                        ❌ Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
