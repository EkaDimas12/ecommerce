@extends('layouts.app')
@section('title', 'Keranjang — Tsania Craft')

@section('content')

    {{-- ================= HERO ================= --}}
    <section class="hero-gradient rounded-3xl p-8 md:p-10 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bubble/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-almost/50 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <span class="badge badge-pink mb-4">🛒 Keranjang Belanja</span>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-ink">Keranjang</h1>
            <p class="mt-2 text-ink/60">Cek item sebelum melanjutkan ke pembayaran.</p>
        </div>
    </section>

    <section class="mt-10">
        @if (empty($cart))
            {{-- Empty State --}}
            <div class="card-static p-10 text-center max-w-md mx-auto">
                <div class="text-6xl mb-4 opacity-75">🛒</div>
                <div class="font-extrabold text-xl text-ink">Keranjang Masih Kosong</div>
                <p class="mt-2 text-ink/60">
                    Yuk, mulai belanja produk handmade favorit kamu dan temukan barang unik di Tsania Craft!
                </p>
                <a href="{{ route('products.index') }}" class="inline-block mt-5 btn-primary">
                    🛍️ Mulai Belanja
                </a>
            </div>
        @else
            @php
                $subtotal = collect($cart)->sum(fn($i) => (int) ($i['price'] ?? 0) * (int) ($i['qty'] ?? 1));
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-6 items-start">
                {{-- LIST ITEM --}}
                <div class="card-static p-5 md:p-6">
                    <div class="flex items-center gap-2 mb-5">
                        <span
                            class="w-8 h-8 rounded-lg bg-humble flex items-center justify-center text-white text-sm">📦</span>
                        <span class="font-bold text-ink">Daftar Produk</span>
                        <span class="ml-auto badge">{{ count($cart) }} item</span>
                    </div>

                    <div class="space-y-4">
                        @foreach ($cart as $id => $item)
                            <div
                                class="flex justify-between gap-4 items-center flex-wrap p-4 rounded-2xl bg-pinkbg/50 hover:bg-pinkbg transition">
                                <div class="flex gap-4 items-center">
                                    @if (!empty($item['image']))
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                            class="w-16 h-16 object-cover rounded-xl border border-humble/10">
                                    @else
                                        <div
                                            class="w-16 h-16 rounded-xl bg-gradient-to-br from-bubble/20 to-almost flex items-center justify-center text-2xl">
                                            🎁
                                        </div>
                                    @endif

                                    <div>
                                        <div class="font-bold text-ink">{{ $item['name'] ?? 'Produk' }}</div>
                                        <div class="text-sm text-ink/60">
                                            Rp{{ number_format((int) ($item['price'] ?? 0), 0, ',', '.') }}
                                            <span class="mx-1">×</span>
                                            <span class="font-medium text-ink">{{ (int) ($item['qty'] ?? 1) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-3 items-center">
                                    <div class="font-bold text-inlove">
                                        Rp{{ number_format(((int) ($item['price'] ?? 0)) * ((int) ($item['qty'] ?? 1)), 0, ',', '.') }}
                                    </div>

                                    {{-- HAPUS ITEM --}}
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit"
                                            class="w-9 h-9 rounded-xl bg-red-50 border border-red-200 text-red-500 flex items-center justify-center hover:bg-red-100 transition">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- CLEAR CART --}}
                    <form method="POST" action="{{ route('cart.clear') }}" class="mt-5 pt-5 border-t border-humble/10">
                        @csrf
                        <button type="submit" class="btn-outline text-sm">
                            🗑️ Kosongkan Keranjang
                        </button>
                    </form>
                </div>

                {{-- SUMMARY --}}
                <aside class="card-static p-5 md:p-6 lg:sticky lg:top-24">
                    <div class="flex items-center gap-2 mb-5">
                        <span
                            class="w-8 h-8 rounded-lg bg-bubble flex items-center justify-center text-white text-sm">📋</span>
                        <span class="font-bold text-ink">Ringkasan</span>
                    </div>

                    <div class="space-y-3 mb-5">
                        <div class="flex justify-between gap-3 text-sm">
                            <span class="text-ink/60">Subtotal ({{ count($cart) }} item)</span>
                            <span class="font-bold text-ink">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between gap-3 text-sm">
                            <span class="text-ink/60">Ongkir</span>
                            <span class="text-ink/60 italic">Dihitung di checkout</span>
                        </div>
                    </div>

                    <div class="h-px bg-humble/10 my-4"></div>

                    <div class="flex justify-between gap-3 mb-5">
                        <span class="font-bold text-ink">Total</span>
                        <span class="text-xl font-extrabold text-inlove">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="block w-full btn-primary text-center">
                        ✨ Checkout
                    </a>

                    <a href="{{ route('products.index') }}" class="block w-full mt-3 btn-outline text-center">
                        🛍️ Lanjut Belanja
                    </a>

                    <p class="mt-4 text-xs text-ink/50 text-center">
                        🔒 Transaksi aman & terpercaya
                    </p>
                </aside>
            </div>
        @endif
    </section>

@endsection
