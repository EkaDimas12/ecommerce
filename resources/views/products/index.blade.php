@extends('layouts.app')
@section('title', 'Shop — Tsania Craft')

@section('content')

    {{-- ================= HERO ================= --}}
    <section class="hero-gradient rounded-3xl p-8 md:p-12 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bubble/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-almost/50 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <span class="badge badge-pink mb-4">🛍️ Koleksi Produk</span>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-ink">Shop</h1>
            <div class="mt-2 text-sm text-ink/50">
                Home <span class="opacity-50">/</span> Shop
            </div>
            <p class="mt-4 text-ink/70 max-w-2xl mx-auto leading-relaxed">
                Temukan produk handmade favoritmu. Gunakan filter untuk mempercepat pencarian.
            </p>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-6">

            {{-- ========== SIDEBAR FILTER ========== --}}
            <aside class="card-static p-5 h-fit lg:sticky lg:top-24">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-humble flex items-center justify-center text-white text-sm">⚙️</span>
                    <span class="font-bold text-ink">Filter</span>
                </div>

                <div class="text-xs font-bold text-ink/50 uppercase tracking-wide mb-2">Kategori</div>
                <div class="flex flex-col gap-1 text-sm mb-5">
                    <a class="px-3 py-2.5 rounded-xl transition {{ empty($activeCategory) ? 'bg-bubble/20 text-inlove font-semibold' : 'hover:bg-pinkbg text-ink/70' }}"
                        href="{{ route('products.index', array_filter(['q' => $q ?? null, 'sort' => $activeSort ?? 'newest'])) }}">
                        Semua Produk
                    </a>

                    @foreach ($categories as $c)
                        <a class="px-3 py-2.5 rounded-xl transition {{ ($activeCategory ?? '') === $c->slug ? 'bg-bubble/20 text-inlove font-semibold' : 'hover:bg-pinkbg text-ink/70' }}"
                            href="{{ route('products.index', array_filter(['category' => $c->slug, 'q' => $q ?? null, 'sort' => $activeSort ?? 'newest'])) }}">
                            {{ $c->name }}
                        </a>
                    @endforeach
                </div>

                <div class="h-px bg-humble/10 my-4"></div>

                <div class="text-xs font-bold text-ink/50 uppercase tracking-wide mb-2">Cari</div>
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="flex flex-col gap-2">
                        <input class="field text-sm" name="q" value="{{ $q ?? '' }}"
                            placeholder="Nama produk...">
                        <input type="hidden" name="category" value="{{ $activeCategory ?? '' }}">
                        <input type="hidden" name="sort" value="{{ $activeSort ?? 'newest' }}">
                        <button class="btn-primary text-sm py-2.5">🔍 Cari</button>
                    </div>
                </form>
            </aside>

            {{-- ========== MAIN SHOP AREA ========== --}}
            <section>

                {{-- Top row: result + sorting --}}
                <div class="flex items-center justify-between gap-3 flex-wrap mb-5">
                    <div class="text-sm text-ink/60">
                        Menampilkan <strong
                            class="text-ink">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
                        dari {{ $products->total() }} produk
                    </div>

                    <form method="GET" action="{{ route('products.index') }}" class="flex gap-2 items-center">
                        <input type="hidden" name="q" value="{{ $q ?? '' }}">
                        <input type="hidden" name="category" value="{{ $activeCategory ?? '' }}">

                        <select name="sort" class="field text-sm py-2 w-auto">
                            <option value="newest" @selected(($activeSort ?? 'newest') === 'newest')>Terbaru</option>
                            <option value="cheapest" @selected(($activeSort ?? '') === 'cheapest')>Termurah</option>
                            <option value="best_seller" @selected(($activeSort ?? '') === 'best_seller')>Terlaris</option>
                        </select>

                        <button class="btn-outline text-sm py-2">Apply</button>
                    </form>
                </div>

                {{-- Active filter chips --}}
                @if (!empty($activeCategory) || !empty($q) || ($activeSort ?? 'newest') === 'best_seller')
                    <div class="mb-5 flex gap-2 flex-wrap">
                        @if (!empty($activeCategory))
                            <span class="badge badge-pink">
                                📂 {{ $categories->firstWhere('slug', $activeCategory)?->name ?? $activeCategory }}
                            </span>
                        @endif

                        @if (!empty($q))
                            <span class="badge">🔍 "{{ $q }}"</span>
                        @endif

                        @if (($activeSort ?? 'newest') === 'best_seller')
                            <span class="badge badge-pink">⭐ Terlaris</span>
                        @endif

                        <a href="{{ route('products.index') }}"
                            class="text-sm text-inlove font-semibold hover:underline ml-2">
                            ✕ Reset
                        </a>
                    </div>
                @endif

                {{-- GRID PRODUCTS --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @forelse($products as $p)
                        <a href="{{ route('products.show', $p->slug) }}" class="product-card group">
                            <div class="p-3 relative overflow-hidden">
                                @if ($p->is_best_seller)
                                    <span class="absolute top-4 left-4 z-10 badge badge-dark text-xs shadow-lg">⭐
                                        Best</span>
                                @endif

                                @if ($p->main_image)
                                    <img src="{{ asset('storage/' . $p->main_image) }}" alt="{{ $p->name }}"
                                        class="w-full aspect-square object-cover rounded-2xl">
                                @else
                                    <div
                                        class="w-full aspect-square rounded-2xl bg-gradient-to-br from-bubble/20 to-almost flex items-center justify-center text-4xl">
                                        🎁
                                    </div>
                                @endif
                            </div>

                            <div class="px-4 pb-4">
                                <div class="flex items-center justify-between gap-2 text-xs text-ink/50">
                                    <span>{{ $p->category->name ?? '-' }}</span>
                                    <span class="flex items-center gap-1 text-bubble font-medium">★ 4.9</span>
                                </div>

                                <div class="mt-2 font-bold text-ink group-hover:text-inlove transition">
                                    {{ $p->name }}
                                </div>

                                <div class="mt-2 text-lg font-extrabold text-inlove">
                                    Rp{{ number_format($p->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full card-static p-10 text-center">
                            <div class="text-5xl opacity-60 mb-4">🔍</div>
                            <div class="font-extrabold text-lg text-ink">Produk Tidak Ditemukan</div>
                            <p class="mt-2 text-sm text-ink/60 max-w-xs mx-auto">
                                Coba ubah filter atau kata kunci pencarian untuk menemukan produk yang kamu cari.
                            </p>
                            <a href="{{ route('products.index') }}" class="inline-block mt-4 btn-outline">
                                Reset Filter
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>

            </section>
        </div>
    </section>

@endsection
