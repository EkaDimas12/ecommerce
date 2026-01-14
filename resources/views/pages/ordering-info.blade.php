@extends('layouts.app')
@section('title','Informasi Pemesanan — Tsania Craft')

@section('content')
<h1 class="text-2xl md:text-3xl font-semibold">Informasi Pemesanan</h1>

<div class="mt-6 card-soft p-6">
  <h2 class="text-xl font-semibold">Alur Pembelian</h2>
  <ol class="mt-3 text-slate-600 list-decimal ml-5 space-y-2">
    <li>Pilih produk di katalog, lalu klik <b>Tambah ke Keranjang</b>.</li>
    <li>Buka keranjang dan klik <b>Checkout</b>.</li>
    <li>Isi data pembeli & alamat, pilih kurir dan layanan.</li>
    <li>Sistem akan menghitung ongkir otomatis, lalu tampil total akhir.</li>
    <li>Pilih pembayaran: <b>Transfer</b> atau <b>COD</b> (wilayah tertentu).</li>
  </ol>

  <h2 class="mt-8 text-xl font-semibold">Estimasi Produksi Custom</h2>
  <p class="mt-2 text-slate-600">
    Untuk produk custom, estimasi produksi rata-rata <b>3–7 hari kerja</b> (tergantung antrian & tingkat custom).
  </p>

  <h2 class="mt-8 text-xl font-semibold">Cara Bayar</h2>
  <ul class="mt-2 text-slate-600 list-disc ml-5 space-y-1">
    <li>Transfer bank (contoh): BCA / BRI</li>
    <li>COD: aktif untuk wilayah tertentu (lihat saat checkout)</li>
  </ul>

  <h2 class="mt-8 text-xl font-semibold">Retur (Opsional)</h2>
  <p class="mt-2 text-slate-600">
    Retur dapat dipertimbangkan jika produk rusak saat pengiriman (sertakan video unboxing).
  </p>
</div>
@endsection
