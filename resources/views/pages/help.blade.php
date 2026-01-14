@extends('layouts.app')
@section('title','Help — Tsania Craft')

@section('content')

<section class="page-hero">
  <div style="text-align:center;">
    <h1 class="h1">Help</h1>
    <div style="margin-top:6px; font-size:14px; color:var(--muted);">
      Home <span style="opacity:.6;">/</span> Help
    </div>
    <p style="margin:14px auto 0; max-width:760px;" class="p-muted">
      FAQ singkat dan jam Customer Support agar belanja lebih nyaman.
    </p>
  </div>
</section>

<section class="section">
  <div style="display:grid; grid-template-columns:1fr; gap:18px;">
    <div class="card" style="padding:18px;">
      <div style="display:flex; align-items:end; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
          <div class="h2">FAQ</div>
          <div style="margin-top:6px;" class="p-muted">Pertanyaan yang paling sering ditanyakan.</div>
        </div>
        <span class="badge badge-terracotta">Bantuan</span>
      </div>

      <div style="margin-top:14px; display:grid; gap:10px;">
        <div class="card-soft" style="padding:14px;">
          <div style="font-weight:800;">1) Apakah bisa custom order?</div>
          <div style="margin-top:6px;" class="p-muted">
            Bisa. Kamu dapat request nama/warna/tema. Estimasi produksi custom biasanya 2–5 hari.
          </div>
        </div>

        <div class="card-soft" style="padding:14px;">
          <div style="font-weight:800;">2) Metode pembayaran apa saja?</div>
          <div style="margin-top:6px;" class="p-muted">
            Transfer bank (BCA/BRI) dan COD untuk wilayah tertentu (sesuai ketentuan checkout).
          </div>
        </div>

        <div class="card-soft" style="padding:14px;">
          <div style="font-weight:800;">3) Bagaimana cek ongkir?</div>
          <div style="margin-top:6px;" class="p-muted">
            Ongkir akan dihitung otomatis saat checkout setelah kamu memilih kurir dan mengisi alamat.
          </div>
        </div>

        <div class="card-soft" style="padding:14px;">
          <div style="font-weight:800;">4) Apakah bisa retur?</div>
          <div style="margin-top:6px;" class="p-muted">
            Retur opsional. Buat aturan: retur diterima jika barang rusak saat diterima (sertakan video unboxing).
          </div>
        </div>
      </div>
    </div>

    <div class="card" style="padding:18px;">
      <div class="h2">Customer Support</div>
      <div style="margin-top:10px;" class="p-muted">
        <div><b>Jam CS:</b> Senin–Sabtu 09.00–17.00</div>
        <div><b>Respon cepat:</b> WhatsApp</div>
      </div>

      @php $wa = env('WHATSAPP_NUMBER','6281234567890'); @endphp
      <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn btn-dark" href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Tsania Craft, saya butuh bantuan.') }}">
          Chat WhatsApp
        </a>
        <a class="btn btn-outline" href="{{ route('products.index') }}">Belanja Sekarang</a>
      </div>

      <div style="margin-top:12px; font-size:13px; color:var(--muted);">
        (Opsional) Tambahkan live chat widget.
      </div>
    </div>
  </div>

  <style>
    @media(min-width:1024px){
      section.section > div[style*="grid-template-columns:1fr"]{
        grid-template-columns: 1.2fr .8fr !important;
        align-items: start !important;
      }
    }
  </style>
</section>

@endsection
