@extends('layouts.app')
@section('title', ($product->name ?? 'Detail Produk') . ' — Tsania Craft')

@section('content')

    {{-- ================= HERO/BREADCRUMB ================= --}}
    <section class="bg-gradient-to-br from-white via-almost/30 to-bubble/20 border border-humble/10 rounded-2xl p-5 md:p-8">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-ink">{{ $product->name }}</h1>
                <div class="mt-2 flex items-center gap-2 text-sm text-ink/60">
                    <a href="{{ route('home') }}" class="hover:text-inlove transition">Home</a>
                    <span class="opacity-50">/</span>
                    <a href="{{ route('products.index') }}" class="hover:text-inlove transition">Shop</a>
                    <span class="opacity-50">/</span>
                    <span class="text-ink">{{ $product->name }}</span>
                </div>
            </div>

            <a class="px-4 py-2.5 rounded-full bg-white border border-humble/20 text-ink font-bold text-sm hover:bg-bubble/20 transition"
                href="{{ route('products.index') }}">← Kembali</a>
        </div>
    </section>

    {{-- ================= PRODUCT DETAIL ================= --}}
    <section class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        {{-- IMAGE --}}
        <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-4">
            <div class="rounded-xl overflow-hidden">
                @if ($product->main_image)
                    <img class="w-full aspect-square object-cover rounded-xl border border-humble/10 bg-gradient-to-br from-bubble/20 to-almost/30"
                        src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" loading="lazy">
                @else
                    <div
                        class="w-full aspect-square rounded-xl bg-gradient-to-br from-bubble/20 to-almost/30 border border-humble/10">
                    </div>
                @endif
            </div>
        </div>

        {{-- INFO --}}
        <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-5 md:p-6">
            <div class="flex gap-2 flex-wrap">
                <span class="px-3 py-1.5 rounded-full bg-almost border border-bubble text-humble text-xs font-semibold">
                    {{ $product->category->name ?? '-' }}
                </span>

                @if ($product->is_best_seller)
                    <span
                        class="px-3 py-1.5 rounded-full bg-milk border border-bubble text-humble text-xs font-semibold">Best
                        Seller</span>
                @endif
            </div>

            <div class="mt-4 text-2xl md:text-3xl font-black text-ink">
                Rp{{ number_format($product->price, 0, ',', '.') }}
            </div>

            <p class="mt-4 text-sm text-ink/70 leading-relaxed">
                {{ $product->description }}
            </p>

            {{-- ACTIONS with Quantity Selector --}}
            <div class="mt-6" x-data="{ qty: 1 }">

                {{-- Quantity Selector --}}
                <div class="mb-4">
                    <div class="text-xs font-bold text-ink/70 mb-2">Jumlah</div>
                    <div class="inline-flex items-center border border-humble/20 rounded-xl overflow-hidden bg-white">
                        <button type="button"
                            class="w-11 h-11 flex items-center justify-center text-lg font-bold text-ink hover:bg-bubble/20 transition"
                            @click="qty > 1 ? qty-- : null" :disabled="qty <= 1">−</button>
                        <input type="number"
                            class="w-14 h-11 text-center font-bold text-ink border-x border-humble/20 bg-transparent focus:outline-none"
                            x-model.number="qty" min="1" max="99" readonly>
                        <button type="button"
                            class="w-11 h-11 flex items-center justify-center text-lg font-bold text-ink hover:bg-bubble/20 transition"
                            @click="qty < 99 ? qty++ : null" :disabled="qty >= 99">+</button>
                    </div>
                </div>

                <div class="flex gap-3 flex-wrap">
                    @auth
                        {{-- Tambah ke Keranjang --}}
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" :value="qty">

                            <button type="submit"
                                class="px-6 py-3 rounded-full bg-humble text-white font-bold shadow-soft hover:opacity-90 transition">
                                🛒 Tambah ke Keranjang
                            </button>
                        </form>

                        {{-- Beli Sekarang --}}
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" :value="qty">
                            <input type="hidden" name="redirect_to" value="{{ route('checkout.index') }}">

                            <button type="submit"
                                class="px-6 py-3 rounded-full bg-bubble text-white font-bold shadow-soft hover:opacity-90 transition">
                                ⚡ Beli Sekarang
                            </button>
                        </form>
                    @else
                        {{-- Tombol untuk Guest - Redirect ke Login --}}
                        <a href="{{ route('login', ['redirect' => url()->current()]) }}"
                            class="px-6 py-3 rounded-full bg-humble text-white font-bold shadow-soft hover:opacity-90 transition">
                            🛒 Tambah ke Keranjang
                        </a>

                        <a href="{{ route('login', ['redirect' => url()->current()]) }}"
                            class="px-6 py-3 rounded-full bg-bubble text-white font-bold shadow-soft hover:opacity-90 transition">
                            ⚡ Beli Sekarang
                        </a>

                        <div class="w-full mt-2">
                            <p class="text-xs text-ink/60">
                                <span class="text-bubble">ℹ️</span> Silakan <a href="{{ route('login') }}"
                                    class="text-inlove font-semibold hover:underline">login</a> terlebih dahulu untuk
                                menambahkan produk ke keranjang.
                            </p>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Note --}}
            <div class="mt-6 bg-pinkbg border border-humble/10 rounded-xl p-4">
                <div class="font-bold text-ink text-sm">Catatan</div>
                <p class="mt-1 text-xs text-ink/70">
                    Untuk custom order, estimasi produksi 2–5 hari (tergantung antrian & tingkat detail).
                </p>
            </div>
        </div>
    </section>

@endsection
