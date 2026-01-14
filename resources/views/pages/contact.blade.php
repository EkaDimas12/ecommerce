@extends('layouts.app')
@section('title','Contact — Tsania Craft')

@section('content')

<section class="page-hero">
  <div style="text-align:center;">
    <h1 class="h1">Contact</h1>
    <div style="margin-top:6px; font-size:14px; color:var(--muted);">
      Home <span style="opacity:.6;">/</span> Contact
    </div>
    <p style="margin:14px auto 0; max-width:760px;" class="p-muted">
      Hubungi kami untuk tanya stok, custom order, atau konsultasi souvenir.
      Kamu juga bisa kirim testimoni agar tampil di Beranda.
    </p>
  </div>
</section>

<section class="section">
  <div class="shop-grid" style="grid-template-columns: 1fr;">

    {{-- FORM TESTIMONI --}}
    <div class="card" style="padding:18px; width:100%;">
      <div style="display:flex; align-items:end; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
          <div class="h2">Kirim Testimoni</div>
          <div style="margin-top:6px;" class="p-muted">
            Testimoni kamu membantu pelanggan lain lebih percaya.
          </div>
        </div>
        <span class="badge badge-terracotta">Testimonials</span>
      </div>

      {{-- ERROR VALIDASI --}}
      @if ($errors->any())
        <div class="card-soft" style="margin-top:14px; padding:14px;">
          <div style="font-weight:800;">Periksa input:</div>
          <ul style="margin-top:8px; padding-left:18px;" class="p-muted">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('testimonials.store') }}"
            style="margin-top:16px; display:grid; gap:14px; width:100%;">
        @csrf

        {{-- Nama --}}
        <div style="width:100%; display:grid; gap:6px;">
          <label for="name" style="font-size:13px; color:var(--muted); font-weight:700;">Nama</label>
          <input id="name" class="field" name="name" value="{{ old('name') }}"
                 placeholder="Contoh: Nabila" style="width:100%;">
        </div>

        {{-- Rating --}}
        <div style="width:100%; display:grid; gap:6px;">
          <label for="rating" style="font-size:13px; color:var(--muted); font-weight:700;">Rating</label>
          <select id="rating" class="field" name="rating" style="width:100%;">
            <option value="">Pilih rating</option>
            <option value="5" @selected(old('rating')==5)>★★★★★ (5)</option>
            <option value="4" @selected(old('rating')==4)>★★★★☆ (4)</option>
            <option value="3" @selected(old('rating')==3)>★★★☆☆ (3)</option>
            <option value="2" @selected(old('rating')==2)>★★☆☆☆ (2)</option>
            <option value="1" @selected(old('rating')==1)>★☆☆☆☆ (1)</option>
          </select>
        </div>

        {{-- Pesan --}}
        <div style="width:100%; display:grid; gap:6px;">
          <label for="message" style="font-size:13px; color:var(--muted); font-weight:700;">Pesan</label>
          <textarea id="message" class="field" name="message" rows="6"
                    placeholder="Tulis testimoni kamu..." style="width:100%; resize:vertical;">{{ old('message') }}</textarea>
          <div style="font-size:12px; color:var(--muted);">Maks. 600 karakter.</div>
        </div>

        {{-- Tombol --}}
        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
          <button type="submit" class="btn btn-dark">Kirim Testimoni</button>
          <a class="btn btn-outline" href="{{ route('products.index') }}">Lihat Produk</a>
        </div>

      </form>
    </div>

    {{-- INFO KONTAK --}}
    <div class="card" style="padding:18px; width:100%;">
      <div class="h2">Informasi Kontak</div>
      <div style="margin-top:10px;" class="p-muted">
        Respon tercepat via WhatsApp. Untuk custom order, sebutkan detail ukuran/warna/tema.
      </div>

      <div style="margin-top:14px; display:grid; gap:10px;" class="p-muted">
        <div><b>WhatsApp:</b> +62-812-3456-789</div>
        <div><b>Email:</b> hello@tsaniacraft.test</div>
        <div><b>Instagram:</b> @tsaniacraft</div>
        <div><b>Alamat:</b> (isi alamat lengkap)</div>
        <div><b>Jam CS:</b> Senin–Sabtu 09.00–17.00</div>
      </div>

      @php $wa = env('WHATSAPP_NUMBER','6281234567890'); @endphp
      <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn btn-primary"
           href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Tsania Craft, saya mau tanya produk / custom order.') }}">
          Chat WhatsApp
        </a>
        <a class="btn btn-outline" href="{{ route('help') }}">Bantuan / FAQ</a>
      </div>

      <div class="card-soft" style="margin-top:14px; padding:14px;">
        <div style="font-weight:800;">Catatan</div>
        <div style="margin-top:6px;" class="p-muted">
          Untuk pemesanan custom, estimasi produksi biasanya 2–5 hari (tergantung antrian & tingkat detail).
        </div>
      </div>
    </div>

  </div>

  {{-- responsive jadi 2 kolom di desktop --}}
  <style>
    @media(min-width:1024px){
      section.section .shop-grid{
        grid-template-columns: 1.15fr .85fr !important;
        align-items: start !important;
      }
    }
  </style>
</section>

@endsection
