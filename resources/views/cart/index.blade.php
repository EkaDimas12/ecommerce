@extends('layouts.app')
@section('title','Keranjang — Tsania Craft')

@section('content')

<section class="page-hero">
  <div style="text-align:center;">
    <h1 class="h1">Keranjang</h1>
    <p class="p-muted" style="margin-top:6px;">Cek item sebelum melanjutkan ke pembayaran.</p>
  </div>
</section>

<section class="section">
  @if(empty($cart))
    <div class="card" style="padding:20px; text-align:center;">
      <p class="p-muted">Keranjang masih kosong.</p>
      <a href="{{ route('products.index') }}" class="btn btn-dark" style="margin-top:12px;">
        Belanja Sekarang
      </a>
    </div>
  @else
    @php
      $subtotal = collect($cart)->sum(fn($i) => (int)($i['price'] ?? 0) * (int)($i['qty'] ?? 1));
    @endphp

    <div style="display:grid; grid-template-columns:1fr; gap:18px; align-items:start;">
      {{-- LIST ITEM --}}
      <div class="card" style="padding:18px;">
        <div class="h2">Daftar Produk</div>
        <div class="divider" style="margin:12px 0;"></div>

        <div style="display:grid; gap:14px;">
          @foreach($cart as $id => $item)
            <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap;">
              <div style="display:flex; gap:12px; align-items:center;">
                @if(!empty($item['image']))
                  <img
                    src="{{ asset('storage/'.$item['image']) }}"
                    alt="{{ $item['name'] }}"
                    style="width:68px;height:68px;object-fit:cover;border-radius:14px;border:1px solid rgba(0,0,0,.10);"
                  >
                @else
                  <div style="width:68px;height:68px;border-radius:14px;border:1px solid rgba(0,0,0,.10); background:rgba(0,0,0,.05);"></div>
                @endif

                <div>
                  <div style="font-weight:800;">{{ $item['name'] ?? 'Produk' }}</div>
                  <div class="p-muted" style="font-size:13px;">
                    Rp{{ number_format((int)($item['price'] ?? 0),0,',','.') }}
                    <span style="opacity:.7;">×</span>
                    {{ (int)($item['qty'] ?? 1) }}
                  </div>
                </div>
              </div>

              <div style="display:flex; gap:10px; align-items:center;">
                <div class="badge">
                  Total: Rp{{ number_format(((int)($item['price'] ?? 0))*((int)($item['qty'] ?? 1)),0,',','.') }}
                </div>

                {{-- HAPUS ITEM --}}
                <form method="POST" action="{{ route('cart.remove') }}">
                  @csrf
                  <input type="hidden" name="id" value="{{ $id }}">
                  <button type="submit" class="btn btn-outline" style="padding:10px 14px;">
                    Hapus
                  </button>
                </form>
              </div>
            </div>

            <div class="divider"></div>
          @endforeach
        </div>

        {{-- CLEAR CART --}}
        <form method="POST" action="{{ route('cart.clear') }}" style="margin-top:14px;">
          @csrf
          <button type="submit" class="btn btn-outline" style="padding:10px 14px;">
            Kosongkan Keranjang
          </button>
        </form>
      </div>

      {{-- SUMMARY --}}
      <aside class="card" style="padding:18px;">
        <div class="h2">Ringkasan</div>
        <div class="divider" style="margin:12px 0;"></div>

        <div style="display:flex; justify-content:space-between; gap:10px;">
          <span class="p-muted">Subtotal</span>
          <span style="font-weight:800;">Rp{{ number_format($subtotal,0,',','.') }}</span>
        </div>

        <div class="p-muted" style="margin-top:10px; font-size:13px;">
          Ongkir akan dihitung di halaman checkout setelah memilih kurir & layanan.
        </div>

        {{-- CHECKOUT -> masuk ke flow pembayaran sebelumnya --}}
        <a href="{{ route('checkout.index') }}" class="btn btn-dark" style="width:100%; margin-top:14px;">
          Checkout
        </a>

        <a href="{{ route('products.index') }}" class="btn btn-outline" style="width:100%; margin-top:10px;">
          Lanjut Belanja
        </a>
      </aside>
    </div>

    <style>
      @media(min-width:1024px){
        section.section > div[style*="grid-template-columns:1fr"]{
          grid-template-columns: 1.35fr .65fr !important;
        }
      }
    </style>
  @endif
</section>

@endsection
