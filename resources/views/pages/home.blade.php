@extends('layouts.app')
@section('title', 'Home — Tsania Craft')

@section('content')

    {{-- ================= HERO ================= --}}
    <section class="hero-gradient rounded-3xl p-8 md:p-12 text-center relative overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-bubble/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-almost/50 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <span class="badge badge-pink mb-4">✨ Handmade with Love</span>

            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-ink leading-tight">
                Tsania <span class="text-inlove">Craft</span>
            </h1>

            <p class="mt-4 text-ink/70 text-base md:text-lg max-w-xl mx-auto leading-relaxed">
                Kerajinan handmade modern: aksesoris, dekorasi, souvenir,
                dan custom order untuk momen spesialmu.
            </p>

            <div class="mt-8 flex gap-4 justify-center flex-wrap">
                <a href="{{ route('products.index') }}" class="btn-primary">
                    🛍️ Shop Now
                </a>
                <a href="{{ route('contact') }}" class="btn-outline">
                    💬 Hubungi Kami
                </a>
            </div>

            <div class="mt-6 flex gap-3 justify-center flex-wrap">
                <span class="badge badge-pink">🎨 Handmade</span>
                <span class="badge badge-pink">✏️ Custom Order</span>
                <span class="badge">📦 Packing Aman</span>
            </div>
        </div>
    </section>

    {{-- ================= BEST SELLER ================= --}}
    <section class="mt-12">
        <div class="flex items-end justify-between gap-4 flex-wrap mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-ink">⭐ Best Seller</h2>
                <p class="mt-1 text-ink/60">
                    Produk favorit pelanggan Tsania Craft.
                </p>
            </div>

            <a href="{{ route('products.index', ['sort' => 'best_seller']) }}" class="btn-outline text-sm">
                Lihat Semua →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse ($bestSellers as $p)
                <a href="{{ route('products.show', $p->slug) }}" class="product-card group">
                    <div class="p-3 relative overflow-hidden">
                        <span class="absolute top-4 left-4 z-10 badge badge-dark text-xs shadow-lg">⭐ Best Seller</span>

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
                <div class="col-span-full text-center py-8 text-ink/50">
                    Belum ada produk best seller.
                </div>
            @endforelse
        </div>
    </section>


    {{-- ================= CATEGORIES ================= --}}
    <section class="mt-12">
        <div class="flex items-end justify-between gap-4 flex-wrap mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-ink">📂 Categories</h2>
                <p class="mt-1 text-ink/60">
                    Pilih kategori untuk melihat koleksi produk.
                </p>
            </div>

            <a href="{{ route('products.index') }}" class="btn-outline text-sm">
                View All →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse ($categories as $c)
                <a href="{{ route('products.index', ['category' => $c->slug]) }}"
                    class="card flex items-center justify-between gap-3 p-5 group">
                    <div>
                        <div class="text-xs text-ink/50 font-medium">Collection</div>
                        <div class="mt-1 font-bold text-ink group-hover:text-inlove transition">{{ $c->name }}</div>
                    </div>
                    <span
                        class="w-10 h-10 rounded-full bg-almost flex items-center justify-center text-inlove group-hover:bg-bubble group-hover:text-white transition">
                        →
                    </span>
                </a>
            @empty
                <div class="col-span-full text-center py-8 text-ink/50">
                    Belum ada kategori.
                </div>
            @endforelse
        </div>
    </section>


    {{-- ================= TESTIMONIALS ================= --}}
    <section class="mt-12">
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-extrabold text-ink">💬 Apa Kata Pelanggan</h2>
            <p class="mt-2 text-ink/60 max-w-lg mx-auto">
                Testimoni dari pelanggan yang sudah mempercayai Tsania Craft.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($testimonials as $t)
                <div class="card p-5">
                    {{-- Stars --}}
                    <div class="text-bubble text-sm mb-3">
                        @for ($i = 1; $i <= 5; $i++)
                            {{ $i <= $t->rating ? '★' : '☆' }}
                        @endfor
                    </div>

                    {{-- Message --}}
                    <p class="text-ink/80 text-sm leading-relaxed">
                        "{{ $t->message }}"
                    </p>

                    {{-- Author --}}
                    <div class="mt-4 pt-4 border-t border-humble/10 flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-bubble to-inlove flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($t->name, 0, 1)) }}
                        </div>
                        <div class="font-semibold text-ink">{{ $t->name }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================= CTA SECTION ================= --}}
    <section class="mt-16 hero-gradient rounded-3xl p-8 md:p-12 text-center">
        <h2 class="text-2xl md:text-3xl font-extrabold text-ink">Ingin Custom Order?</h2>
        <p class="mt-3 text-ink/70 max-w-md mx-auto">
            Konsultasikan ide kamu dengan kami. Kami siap membantu mewujudkan produk impianmu!
        </p>
        <div class="mt-6 flex gap-4 justify-center flex-wrap">
            <a href="{{ route('contact') }}" class="btn-primary">
                💬 Hubungi Kami
            </a>
            <a href="{{ route('products.index') }}" class="btn-outline">
                🛍️ Lihat Produk
            </a>
        </div>
    </section>

@endsection
