@extends('layouts.app')
@section('title','About — Tsania Craft')

@section('content')

<section class="page-hero">
  <div style="text-align:center;">
    <h1 class="h1">About</h1>
    <div style="margin-top:6px; font-size:14px; color:var(--muted);">
      Home <span style="opacity:.6;">/</span> About
    </div>
    <p style="margin:14px auto 0; max-width:760px;" class="p-muted">
      Mengenal Tsania Craft: brand kerajinan handmade dengan gaya modern-minimal, fokus kualitas dan layanan ramah.
    </p>
  </div>
</section>

<section class="section">
  <div class="card" style="padding:18px;">
    <div class="h2">Tentang Tsania Craft</div>
    <p style="margin-top:10px;" class="p-muted">
      Tsania Craft memproduksi kerajinan handmade seperti resin floral, totebag custom, makrame, hingga paket souvenir.
      Kami menerima custom order untuk kebutuhan hadiah, acara, maupun souvenir pernikahan.
    </p>
  </div>
</section>

<section class="section">
  <div style="display:grid; grid-template-columns:1fr; gap:18px;">
    <div class="card" style="padding:18px;">
      <div class="h2">Visi</div>
      <p style="margin-top:10px;" class="p-muted">
        Menjadi pilihan utama kerajinan handmade yang modern, berkualitas, dan terpercaya bagi UMKM lokal.
      </p>
    </div>

    <div class="card" style="padding:18px;">
      <div class="h2">Misi</div>
      <ul style="margin-top:10px; padding-left:18px;" class="p-muted">
        <li>Menghadirkan produk handmade dengan finishing rapi dan konsisten.</li>
        <li>Memberi pengalaman belanja online yang mudah & responsif.</li>
        <li>Menerima custom order sesuai kebutuhan pelanggan.</li>
        <li>Mendukung pemberdayaan UMKM melalui pemasaran digital.</li>
      </ul>
    </div>
  </div>

  <style>
    @media(min-width:1024px){
      section.section > div[style*="grid-template-columns:1fr"]{
        grid-template-columns: 1fr 1fr !important;
      }
    }
  </style>
</section>

<section class="section">
  <div class="card" style="padding:18px;">
    <div class="h2">Alamat & Jam Operasional</div>
    <div style="margin-top:10px;" class="p-muted">
      <div><b>Alamat:</b> (isi alamat lengkap Tsania Craft)</div>
      <div><b>Jam operasional:</b> Senin–Sabtu, 09.00–17.00</div>
      <div><b>Catatan:</b> Untuk custom order, estimasi produksi bisa 2–5 hari.</div>
    </div>
  </div>
</section>

@endsection
