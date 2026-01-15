@extends('layouts.app')
@section('title', 'Pembayaran — Tsania Craft')

@section('content')

    <section class="hero-gradient rounded-3xl p-8 md:p-12 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bubble/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-almost/50 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <span class="badge badge-pink mb-4">💳 Pembayaran</span>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-ink">Selesaikan Pembayaran</h1>
            <p class="mt-3 text-ink/70 max-w-lg mx-auto">
                Kode Pesanan: <strong class="text-bubble">{{ $order->code }}</strong>
            </p>
        </div>
    </section>

    <section class="mt-8 max-w-xl mx-auto">
        <div class="card-static p-8 text-center">
            <div class="text-6xl mb-6">💳</div>
            <h2 class="text-2xl font-bold text-ink mb-2">Total Pembayaran</h2>
            <div class="text-4xl font-extrabold text-inlove mb-6">
                Rp{{ number_format($order->total, 0, ',', '.') }}
            </div>

            <p class="text-ink/60 mb-6">
                Klik tombol di bawah untuk melanjutkan pembayaran via Midtrans.
            </p>

            <button id="pay-button"
                class="btn-primary w-full py-4 text-lg font-bold shadow-xl hover:scale-[1.02] transition-transform">
                🚀 Bayar Sekarang
            </button>

            <div class="mt-6 text-sm text-ink/50">
                Pembayaran diamankan oleh Midtrans
            </div>
        </div>

        <div class="mt-6 bg-pinkbg border border-bubble/20 rounded-xl p-4">
            <div class="flex gap-3">
                <span class="text-xl">ℹ️</span>
                <div>
                    <div class="font-bold text-ink text-sm">Metode Pembayaran</div>
                    <p class="text-xs text-ink/60 mt-1">
                        Anda dapat membayar dengan Transfer Bank, GoPay, ShopeePay,
                        Kartu Kredit/Debit, atau Indomaret/Alfamart.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Midtrans Snap JS --}}
    <script src="{{ $midtransSnapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = '{{ route('payment.finish') }}?order_id={{ $order->code }}';
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route('payment.finish') }}?order_id={{ $order->code }}';
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    window.location.href = '{{ route('payment.error') }}';
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // User closed the popup without completing payment
                    alert(
                        'Pembayaran belum selesai. Silakan lanjutkan pembayaran dari halaman pesanan Anda.');
                    window.location.href = '{{ route('order.show', $order->code) }}';
                }
            });
        });
    </script>

@endsection
