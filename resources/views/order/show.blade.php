@extends('layouts.app')
@section('title', 'Invoice ' . $order->code . ' — Tsania Craft')

@section('content')

    <section class="section" style="max-width:780px; margin:auto;">
        <div class="card" style="padding:22px;">

            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
                <div>
                    <div style="font-size:22px; font-weight:900;">Invoice</div>
                    <div class="p-muted" style="margin-top:6px;">Kode: <b>{{ $order->code }}</b></div>
                </div>

                {{-- ✅ tombol cetak menuju halaman print khusus --}}
                <a href="{{ route('order.print', $order->code) }}" target="_blank" class="btn btn-outline">
                    Cetak Struk
                </a>
            </div>

            <div class="divider" style="margin:14px 0;"></div>

            <div class="p-muted" style="line-height:1.8;">
                <div><b>Nama:</b> {{ $order->customer_name }}</div>
                <div><b>WhatsApp:</b> {{ $order->phone }}</div>
                <div><b>Alamat:</b> {{ $order->address }}</div>
                <div><b>Kurir:</b> {{ strtoupper($order->courier ?? '-') }} {{ $order->service ?? '' }}</div>
                @if ($order->tracking_number)
                    <div
                        style="margin-top: 8px; padding: 10px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px;">
                        <b>📦 No. Resi:</b>
                        <span
                            style="font-family: monospace; font-weight: 700; color: #166534;">{{ $order->tracking_number }}</span>
                        <a href="https://cekresi.com/?noresi={{ $order->tracking_number }}" target="_blank"
                            style="margin-left: 8px; font-size: 12px; color: #3b82f6; text-decoration: underline;">
                            Lacak Pengiriman →
                        </a>
                    </div>
                @endif
            </div>

            <div class="divider" style="margin:14px 0;"></div>

            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(0,0,0,.12);">
                        <th align="left" style="padding:10px 0;">Produk</th>
                        <th align="center" style="padding:10px 0;">Qty</th>
                        <th align="right" style="padding:10px 0;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $it)
                        <tr style="border-bottom:1px solid rgba(0,0,0,.06);">
                            <td style="padding:10px 0;">
                                <b>{{ $it->name_snapshot }}</b><br>
                                <span class="p-muted" style="font-size:13px;">
                                    Rp{{ number_format($it->price_snapshot, 0, ',', '.') }}
                                </span>
                            </td>
                            <td align="center">{{ $it->qty }}</td>
                            <td align="right">Rp{{ number_format($it->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="divider" style="margin:14px 0;"></div>

            <div style="display:flex; justify-content:space-between; margin-top:6px;">
                <span class="p-muted">Subtotal</span>
                <span style="font-weight:800;">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:6px;">
                <span class="p-muted">Ongkir</span>
                <span style="font-weight:800;">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:10px; font-weight:900;">
                <span>Total</span>
                <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
            </div>

            <div class="divider" style="margin:14px 0;"></div>

            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div>
                    <b>Status Pembayaran:</b>
                    @if ($order->payment_status === 'paid')
                        <span style="font-weight:900; color:#16a34a;">✅ LUNAS</span>
                    @elseif($order->payment_status === 'cod')
                        <span style="font-weight:900; color:#ea580c;">💵 COD</span>
                    @elseif($order->payment_status === 'pending')
                        <span style="font-weight:900; color:#ca8a04;">⏳ PENDING</span>
                    @else
                        <span style="font-weight:900; color:#dc2626;">❌ {{ strtoupper($order->payment_status) }}</span>
                    @endif
                </div>

                {{-- Tombol Bayar Sekarang (hanya muncul jika pending dan online payment) --}}
                @if (isset($needsPayment) && $needsPayment && isset($snapToken))
                    <button id="pay-button" class="btn-primary" style="padding:12px 24px; font-weight:700;">
                        💳 Bayar Sekarang
                    </button>
                @endif
            </div>

        </div>
    </section>

    {{-- Midtrans Snap JS (hanya jika ada token) --}}
    @if (isset($needsPayment) && $needsPayment && isset($snapToken) && isset($midtransSnapUrl))
        <script src="{{ $midtransSnapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
        <script>
            document.getElementById('pay-button').addEventListener('click', function() {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        window.location.href =
                            '{{ route('payment.finish') }}?order_id={{ $order->code }}';
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        window.location.reload();
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                    }
                });
            });
        </script>
    @endif

@endsection
