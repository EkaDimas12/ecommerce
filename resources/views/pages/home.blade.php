@extends('layouts.app')
@section('title','Home — Tsania Craft')

@section('content')

{{-- ================= HERO ================= --}}
<section class="page-hero">
  <div style="text-align:center;">
    <h1 class="h1">Tsania Craft</h1>
    <div style="margin-top:6px; font-size:14px; color:var(--muted);">
      Home <span style="opacity:.6;">/</span> Beranda
    </div>

    <p style="margin:16px auto 0; max-width:760px;" class="p-muted">
      Kerajinan handmade modern: aksesoris, dekorasi, souvenir,
      dan custom order untuk momen spesialmu.
    </p>

    <div style="margin-top:22px; display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
      <a href="{{ route('products.index') }}" class="btn btn-dark">Shop Now</a>
      <a href="{{ route('contact') }}" class="btn btn-dark">Hubungi Kami</a>
    </div>

    <div style="margin-top:18px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
      <span class="badge badge-terracotta">Handmade</span>
      <span class="badge badge-olive">Custom Order</span>
      <span class="badge">Packing Aman</span>
    </div>
  </div>
</section>

{{-- ================= BEST SELLER ================= --}}
<section class="section">
  <div style="display:flex; align-items:end; justify-content:space-between; gap:12px; flex-wrap:wrap;">
    <div>
      <div class="h2">Best Seller</div>
      <div style="margin-top:6px;" class="p-muted">
        Produk favorit pelanggan Tsania Craft.
      </div>
    </div>

    <a href="{{ route('products.index',['sort'=>'best_seller']) }}"
       class="btn btn-outline dark"
       style="padding:10px 14px;">
      Lihat Semua
    </a>
  </div>

  @php
    // copy collection supaya bisa dimanipulasi
    $displayBestSellers = $bestSellers;

    // jika jumlah ganjil, duplikasi produk pertama
    if ($displayBestSellers->count() % 2 !== 0) {
        $displayBestSellers = $displayBestSellers->push($displayBestSellers->first());
    }
  @endphp

  <div style="margin-top:18px;" class="product-grid">
    @foreach($displayBestSellers as $p)
      <a href="{{ route('products.show',$p->slug) }}"
         class="pcard"
         style="text-decoration:none; color:inherit;">

        <div class="pimg-wrap">
          <div class="badge-float">Best Seller</div>

          @if($p->main_image)
  <img src="{{ asset('storage/'.$p->main_image) }}"
       alt="{{ $p->name }}"
       class="pimg">
@else
  <div class="pimg"></div>
@endif

        </div>

        <div class="pbody">
          <div class="pmeta">
            <span>{{ $p->category->name ?? '-' }}</span>
            <span class="prating">
              <span class="star">★</span> 4.9
            </span>
          </div>

          <div style="margin-top:8px; font-weight:800; letter-spacing:-0.01em;">
            {{ $p->name }}
          </div>

          <div class="price">
            Rp{{ number_format($p->price,0,',','.') }}
          </div>
        </div>
      </a>
    @endforeach
  </div>
</section>


{{-- ================= CATEGORIES (FIX 8 BOXES) ================= --}}
<section class="section">
  <div style="display:flex; align-items:end; justify-content:space-between; gap:12px; flex-wrap:wrap;">
    <div>
      <div class="h2">Categories</div>
      <div style="margin-top:6px;" class="p-muted">
        Pilih kategori untuk melihat koleksi produk.
      </div>
    </div>

    <a href="{{ route('products.index') }}"
       class="btn btn-outline"
       style="padding:10px 14px;">
      View All
    </a>
  </div>

  @php
    /*
     | Tujuan:
     | - Selalu tampil 8 kotak kategori
     | - Jika kategori asli < 8, duplikasi dari awal
     | - Hanya visual (aman & profesional)
     */

    $displayCategories = collect();

    if ($categories->count() > 0) {
        while ($displayCategories->count() < 8) {
            foreach ($categories as $cat) {
                if ($displayCategories->count() < 8) {
                    $displayCategories->push($cat);
                }
            }
        }
    }
  @endphp

  <div style="margin-top:18px;" class="category-grid">
    @foreach($displayCategories as $c)
      <a href="{{ route('products.index',['category'=>$c->slug]) }}"
         class="card card-hover"
         style="
           text-decoration:none;
           color:inherit;
           padding:20px;
           display:flex;
           align-items:center;
           justify-content:space-between;
           gap:12px;
         ">

        <div>
          <div style="font-size:12px; color:var(--muted);">
            Category
          </div>
          <div style="margin-top:6px; font-weight:800;">
            {{ $c->name }}
          </div>
        </div>

        <span class="badge badge-olive">
          Explore
        </span>
      </a>
    @endforeach
  </div>
</section>



{{-- ================= TESTIMONIALS (RAPI & MODERN) ================= --}}
<section class="section">
  <div style="text-align:center;">
    <div class="h2">Apa Kata Pelanggan</div>
    <p style="margin-top:6px;" class="p-muted">
      Testimoni pelanggan yang sudah mempercayai Tsania Craft.
    </p>
  </div>

  <div style="margin-top:22px;"
       class="product-grid">

    @foreach($testimonials as $t)
      <div class="card card-hover" style="padding:20px;">
        
        {{-- header --}}
        <div style="display:flex; align-items:center; gap:12px;">
          <div style="
            width:46px;
            height:46px;
            border-radius:999px;
            background:linear-gradient(135deg,var(--bubble),var(--secrets));
            display:flex;
            align-items:center;
            justify-content:center;
            color:white;
            font-weight:800;
          ">
            {{ strtoupper(substr($t->name,0,1)) }}
          </div>

          <div>
            <div style="font-weight:800;">{{ $t->name }}</div>
            <div style="font-size:13px; color:#c24168;">
              @for($i=1;$i<=5;$i++)
                {{ $i <= $t->rating ? '★' : '☆' }}
              @endfor
            </div>
          </div>
        </div>

        {{-- message --}}
        <p style="margin-top:14px;" class="p-muted">
          “{{ $t->message }}”
        </p>
      </div>
    @endforeach

  </div>
</section>

@endsection
