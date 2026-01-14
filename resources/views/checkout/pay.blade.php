@extends('layouts.app')
@section('title','Pembayaran — Tsania Craft')

@section('content')

<section class="page-hero">
  <div style="text-align:center;">
    <h1 class="h1">Pembayaran</h1>
    <p class="p-muted" style="margin-top:6px;">
      Selesaikan pembayaran untuk pesanan <b>{{ $order->code }}</b>
    </p>
  </div>
</section>

<section class="section">
  <div class="card" style="max-width:520px; margin:auto; padding:22px; text-align:center;">
    <div style="font-weight:800; font-size:18px;">
      Total Pembayaran
    </div>

    <div style="font-size:28px; font-weight:900; margin-top:10px;">
      Rp{{ number_format($order->total,0,',','.') }}
    </div>

    <button id="pay-button" class="btn btn-dark" style="margin-top:18px; width:100%;">
      Bayar Sekarang
    </button>

    <p class="p-muted" style="margin-top:12px; font-size:13px;">
      Pembayaran aman diproses oleh Midtrans.
    </p>
  </div>
</section>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ $clientKey }}"></script>

<script>
document.getElementById('pay-button').onclick = function () {
  snap.pay('{{ $snapToken }}', {
    onSuccess: function (result) {
      window.location.href =
        "{{ route('payment.finish') }}?order_id={{ $order->code }}";
    },
    onPending: function (result) {
      window.location.href =
        "{{ route('payment.finish') }}?order_id={{ $order->code }}";
    },
    onError: function (result) {
      window.location.href = "{{ route('payment.error') }}";
    }
  });
};
</script>

@endsection
