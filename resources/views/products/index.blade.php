@extends('layouts.app')
@section('title','Shop — Tsania Craft')

@section('content')

{{-- ================= HERO ================= --}}
<section class="shop-hero">
  <div style="text-align:center;">
    <h1 class="h1">Shop</h1>
    <div style="margin-top:6px; font-size:14px; color:var(--muted);">
      Home <span style="opacity:.6;">/</span> Shop
    </div>
    <p style="margin:14px auto 0; max-width:760px;" class="p-muted">
      Temukan produk handmade favoritmu. Gunakan filter untuk mempercepat pencarian.
    </p>
  </div>
</section>

{{-- ================= CONTENT ================= --}}
<section class="section">
  <div class="shop-grid">

    {{-- ========== SIDEBAR FILTER ========== --}}
    <aside class="filter-card">
      <div style="font-weight:800;">Filter Options</div>
      <div class="divider" style="margin:12px 0;"></div>

      <div style="font-size:13px; font-weight:800; margin-bottom:8px;">By Categories</div>
      <div style="display:flex; flex-direction:column; gap:8px; font-size:14px;">
        <a class="nav-link"
           href="{{ route('products.index', array_filter(['q'=>$q ?? null,'sort'=>$activeSort ?? 'newest'])) }}">
          All
        </a>

        @foreach($categories as $c)
          <a class="nav-link"
             href="{{ route('products.index', array_filter(['category'=>$c->slug,'q'=>$q ?? null,'sort'=>$activeSort ?? 'newest'])) }}">
            {{ $c->name }}
          </a>
        @endforeach
      </div>

      <div class="divider" style="margin:14px 0;"></div>

      <div style="font-size:13px; font-weight:800; margin-bottom:8px;">Search</div>
      <form method="GET" action="{{ route('products.index') }}" style="display:flex; gap:8px;">
        <input class="field" name="q" value="{{ $q ?? '' }}" placeholder="Cari produk...">
        <input type="hidden" name="category" value="{{ $activeCategory ?? '' }}">
        <input type="hidden" name="sort" value="{{ $activeSort ?? 'newest' }}">
        <button class="btn btn-dark" style="padding:12px 14px;">Go</button>
      </form>
    </aside>

    {{-- ========== MAIN SHOP AREA ========== --}}
    <section>

      {{-- Top row: result + sorting --}}
      <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div style="font-size:14px; color:var(--muted);">
          Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
        </div>

        <form method="GET" action="{{ route('products.index') }}"
              style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
          <input type="hidden" name="q" value="{{ $q ?? '' }}">
          <input type="hidden" name="category" value="{{ $activeCategory ?? '' }}">

          <div style="display:flex; align-items:center; gap:8px; font-size:14px; color:var(--muted);">
            Sort by:
            <select name="sort" class="field" style="padding:12px 14px; width:220px;">
              <option value="newest" @selected(($activeSort ?? 'newest')==='newest')>Newest</option>
              <option value="cheapest" @selected(($activeSort ?? '')==='cheapest')>Price: Low to High</option>
              <option value="best_seller" @selected(($activeSort ?? '')==='best_seller')>Best Seller</option>
            </select>
          </div>

          <button class="btn btn-outline" style="padding:12px 14px;">Apply</button>
        </form>
      </div>

      {{-- Active filter chips --}}
      <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
        @if(!empty($activeCategory))
          <span class="badge badge-olive">
            Category: {{ $categories->firstWhere('slug',$activeCategory)?->name ?? $activeCategory }}
          </span>
        @endif

        @if(!empty($q))
          <span class="badge">Search: "{{ $q }}"</span>
        @endif

        @if(($activeSort ?? 'newest') === 'best_seller')
          <span class="badge badge-terracotta">Best Seller</span>
        @endif
      </div>

      {{-- =====================================================
           GRID PRODUCTS: ALWAYS 8 BOXES (NO SPACE EMPTY)
           - Tidak mengubah layout sebelumnya
           - Jika produk hasil filter/pagination kurang dari 8,
             duplikasi visual dari awal sampai 8.
         ===================================================== --}}
      @php
        $displayProducts = collect();

        if ($products->count() > 0) {
            while ($displayProducts->count() < 8) {
                foreach ($products as $item) {
                    if ($displayProducts->count() < 8) {
                        $displayProducts->push($item);
                    }
                }
            }
        }
      @endphp

      <div style="margin-top:16px;" class="product-grid">
        @forelse($displayProducts as $p)
          <a href="{{ route('products.show',$p->slug) }}"
             class="pcard"
             style="text-decoration:none; color:inherit;">

            <div class="pimg-wrap">
              @if($p->is_best_seller)
                <div class="badge-float">Best Seller</div>
              @endif

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
        @empty
          <div class="card" style="padding:18px;">
            <div style="font-weight:800;">Produk tidak ditemukan</div>
            <div style="margin-top:6px;" class="p-muted">Coba ubah filter atau kata kunci pencarian.</div>
          </div>
        @endforelse
      </div>

      {{-- Pagination tetap posisi semula --}}
      <div style="margin-top:18px;">
        {{ $products->links() }}
      </div>

    </section>
  </div>
</section>

@endsection
