@extends('layouts.app')
@section('title', 'Pesanan Saya — Tsania Craft')

@section('content')

    {{-- HERO --}}
    <section
        style="background: linear-gradient(135deg, #fff 0%, #FEF0F5 50%, #FCE8EF 100%); border-radius: 24px; padding: 48px 32px; text-align: center; border: 1px solid rgba(74,32,42,0.08);">
        <span
            style="display: inline-block; padding: 8px 16px; background: #FCD6E3; border: 1px solid #E4879E; border-radius: 999px; font-size: 12px; font-weight: 600; color: #4A202A; margin-bottom: 16px;">
            📦 Riwayat Pesanan
        </span>
        <h1 style="font-size: 36px; font-weight: 800; color: #2b0f16; margin: 0;">Pesanan Saya</h1>
        <p style="margin-top: 16px; color: rgba(43,15,22,0.7); max-width: 500px; margin-left: auto; margin-right: auto;">
            Lihat status dan riwayat pesanan kamu di sini.
        </p>
    </section>

    <section style="margin-top: 40px;">
        @if ($orders->isEmpty())
            <div
                style="background: white; border: 1px solid rgba(74,32,42,0.08); border-radius: 20px; padding: 48px 32px; text-align: center; box-shadow: 0 4px 20px rgba(74,32,42,0.08);">
                <div style="font-size: 48px; margin-bottom: 16px; opacity: 0.6;">📦</div>
                <div style="font-size: 20px; font-weight: 800; color: #2b0f16;">Belum Ada Pesanan</div>
                <p style="margin-top: 8px; color: rgba(43,15,22,0.6);">Kamu belum membuat pesanan apapun.</p>
                <a href="{{ route('products.index') }}"
                    style="display: inline-block; margin-top: 24px; padding: 14px 28px; background: #4A202A; color: white; font-weight: 700; font-size: 14px; border-radius: 999px; text-decoration: none; box-shadow: 0 4px 14px rgba(74,32,42,0.2);">
                    🛍️ Mulai Belanja
                </a>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach ($orders as $order)
                    <div
                        style="background: white; border: 1px solid rgba(74,32,42,0.08); border-radius: 20px; padding: 24px; box-shadow: 0 4px 20px rgba(74,32,42,0.08);">
                        <div
                            style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                            <div>
                                <div style="font-size: 12px; color: rgba(43,15,22,0.5);">Order Code</div>
                                <div style="font-size: 18px; font-weight: 800; color: #2b0f16;">{{ $order->code }}</div>
                                <div style="font-size: 13px; color: rgba(43,15,22,0.6); margin-top: 4px;">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            <div style="text-align: right;">
                                @php
                                    $statusColors = [
                                        'pending' => '#F59E0B',
                                        'paid' => '#10B981',
                                        'processing' => '#3B82F6',
                                        'shipped' => '#8B5CF6',
                                        'delivered' => '#10B981',
                                        'cancelled' => '#EF4444',
                                    ];
                                    $color = $statusColors[$order->order_status] ?? '#6B7280';
                                @endphp
                                <span
                                    style="display: inline-block; padding: 6px 14px; background: {{ $color }}15; border: 1px solid {{ $color }}40; color: {{ $color }}; font-size: 12px; font-weight: 600; border-radius: 999px; text-transform: uppercase;">
                                    {{ $order->order_status }}
                                </span>
                                <div style="font-size: 18px; font-weight: 800; color: #83394A; margin-top: 8px;">
                                    Rp{{ number_format($order->total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div
                            style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(74,32,42,0.08); display: flex; gap: 12px; flex-wrap: wrap;">
                            <a href="{{ route('order.show', $order->code) }}"
                                style="display: inline-block; padding: 10px 20px; background: #FEF0F5; color: #4A202A; font-weight: 600; font-size: 14px; border-radius: 12px; text-decoration: none;">
                                Lihat Detail →
                            </a>

                            {{-- Tombol Batalkan (hanya untuk order baru yang belum diproses) --}}
                            @if ($order->order_status === 'new' && in_array($order->payment_status, ['pending', 'cod']))
                                <form action="{{ route('order.cancel', $order->code) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin membatalkan pesanan {{ $order->code }}?');">
                                    @csrf
                                    <button type="submit"
                                        style="padding: 10px 20px; background: #FEE2E2; color: #DC2626; font-weight: 600; font-size: 14px; border-radius: 12px; border: 1px solid #FECACA; cursor: pointer;">
                                        ❌ Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 24px;">
                {{ $orders->links() }}
            </div>
        @endif
    </section>

@endsection
