@extends('layouts.app')
@section('title', ($product->name ?? 'Detail Produk').' — Tsania Craft')

@section('content')

<section class="page-hero" style="padding-top:0;">
  <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
    <div>
      <h1 class="h1" style="margin:0;">{{ $product->name }}</h1>
      <div class="breadcrumb" style="margin-top:6px;">
        <a href="{{ route('home') }}">Home</a>
        <span style="opacity:.6;"> / </span>
        <a href="{{ route('products.index') }}">Shop</a>
        <span style="opacity:.6;"> / </span>
        <span>{{ $product->name }}</span>
      </div>
    </div>

    <a class="btn btn-outline" href="{{ route('products.index') }}">← Kembali</a>
  </div>
</section>

<section class="section">
  <div style="display:grid; grid-template-columns:1fr; gap:18px; align-items:start;">
    
    {{-- IMAGE --}}
    <div class="card" style="padding:14px;">
      <div class="pimg-wrap" style="border-radius:18px; overflow:hidden;">
        @if($product->main_image)
          <img
            class="pimg"
            src="{{ asset('storage/'.$product->main_image) }}"
            alt="{{ $product->name }}"
            loading="lazy"
            decoding="async"
            style="width:100%; height:auto; object-fit:cover; display:block;"
          >
        @else
          <div class="pimg" style="min-height:320px;"></div>
        @endif
      </div>
    </div>

    {{-- INFO --}}
    <div class="card" style="padding:18px;">
      <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <span class="badge badge-olive">{{ $product->category->name ?? '-' }}</span>

        @if($product->is_best_seller)
          <span class="badge badge-terracotta">Best Seller</span>
        @endif
      </div>

      <div style="margin-top:10px; font-size:22px; font-weight:900;">
        Rp{{ number_format($product->price,0,',','.') }}
      </div>

      <p class="p-muted" style="margin-top:12px;">
        {{ $product->description }}
      </p>

     {{-- ACTIONS --}}
<div style="margin-top:16px; display:flex; gap:10px; flex-wrap:wrap;">

  {{-- Tambah ke Keranjang (tetap di halaman produk) --}}
  <form method="POST" action="{{ route('cart.add') }}">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="qty" value="1">

    <button type="submit" class="btn btn-dark">
      Tambah ke Keranjang
    </button>
  </form>

  {{-- Beli Sekarang (masuk keranjang lalu redirect ke checkout/keranjang) --}}
  <form method="POST" action="{{ route('cart.add') }}">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="qty" value="1">
    <input type="hidden" name="redirect_to" value="{{ route('checkout.index') }}">

    <button type="submit" class="btn btn-primary">
      Beli Sekarang
    </button>
  </form>

</div>



      <div class="card-soft" style="margin-top:16px; padding:14px;">
        <div style="font-weight:800;">Catatan</div>
        <div class="p-muted" style="margin-top:6px;">
          Untuk custom order, estimasi produksi 2–5 hari (tergantung antrian & tingkat detail).
        </div>
      </div>
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

@endsection
