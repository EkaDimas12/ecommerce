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

            @if (!empty($hasChanges))
                <div class="mb-6 p-4 rounded-xl bg-orange-50 border border-orange-200 text-orange-700 flex items-start gap-3">
                    <span class="text-xl">⚠️</span>
                    <div>
                        <strong class="block font-bold">Keranjang Diperbarui</strong>
                        <span class="text-sm">Beberapa item di keranjang Anda telah diperbarui (harga berubah, stok habis, atau produk tidak tersedia lagi). Silakan periksa kembali sebelum checkout.</span>
                    </div>
                </div>
            @endif

            @php
                $initialSelected = array_map('strval', array_keys($cart));
            @endphp

            <form method="POST" action="{{ route('checkout.prepare') }}" id="checkout-form" class="contents">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-6 items-start"
                     x-data="{
                         selectedItems: {{ json_encode($initialSelected) }},
                         cartItems: {{ json_encode($cart) }},
                         get subtotal() {
                             let total = 0;
                             for (let id of this.selectedItems) {
                                 if (this.cartItems[id]) {
                                     total += (parseInt(this.cartItems[id].price) * parseInt(this.cartItems[id].qty));
                                 }
                             }
                             return total;
                         },
                         formatPrice(price) {
                             return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
                         }
                     }">
                    
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
                                        <input type="checkbox" name="selected_items[]" value="{{ $id }}" x-model="selectedItems"
                                               class="w-5 h-5 rounded border-humble/30 text-humble focus:ring-humble/50 cursor-pointer">
                                        
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

                                        {{-- HAPUS ITEM (Needs to be a button outside of this form's submission, but we can't easily nest forms. Since we use x-data, we can use a link with POST request or just change the button type to button and handle via JS, OR we can keep it as is, since HTML5 allows formaction) --}}
                                        <button type="submit" formaction="{{ route('cart.remove') }}" name="id" value="{{ $id }}"
                                                class="w-9 h-9 rounded-xl bg-red-50 border border-red-200 text-red-500 flex items-center justify-center hover:bg-red-100 transition">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- CLEAR CART --}}
                        <div class="mt-5 pt-5 border-t border-humble/10">
                            <button type="submit" formaction="{{ route('cart.clear') }}" class="btn-outline text-sm">
                                🗑️ Kosongkan Keranjang
                            </button>
                        </div>
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
                                <span class="text-ink/60">Subtotal (<span x-text="selectedItems.length"></span> item)</span>
                                <span class="font-bold text-ink" x-text="formatPrice(subtotal)">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between gap-3 text-sm">
                                <span class="text-ink/60">Ongkir</span>
                                <span class="text-ink/60 italic">Dihitung di checkout</span>
                            </div>
                        </div>

                        <div class="h-px bg-humble/10 my-4"></div>

                        <div class="flex justify-between gap-3 mb-5">
                            <span class="font-bold text-ink">Total</span>
                            <span class="text-xl font-extrabold text-inlove" x-text="formatPrice(subtotal)">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="block w-full btn-primary text-center" 
                                :disabled="selectedItems.length === 0" 
                                :class="selectedItems.length === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                            ✨ Checkout (<span x-text="selectedItems.length"></span>)
                        </button>

                        <a href="{{ route('products.index') }}" class="block w-full mt-3 btn-outline text-center">
                            🛍️ Lanjut Belanja
                        </a>

                        <p class="mt-4 text-xs text-ink/50 text-center">
                            🔒 Transaksi aman & terpercaya
                        </p>
                    </aside>
                </div>
            </form>
        @endif
    </section>

@endsection
