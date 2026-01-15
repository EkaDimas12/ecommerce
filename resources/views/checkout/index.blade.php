@extends('layouts.app')
@section('title', 'Checkout — Tsania Craft')

@section('content')

    {{-- ================= HERO ================= --}}
    <section class="hero-gradient rounded-3xl p-8 md:p-12 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bubble/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-almost/50 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <span class="badge badge-pink mb-4">🛒 Checkout</span>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-ink">Selesaikan Pesanan</h1>
            <p class="mt-3 text-ink/70 max-w-lg mx-auto">
                Lengkapi data pengiriman dan pilih metode pembayaran untuk menyelesaikan pesanan Anda.
            </p>
        </div>
    </section>

    @php $codWhitelistSafe = $codWhitelist ?? []; @endphp
    <script>
        window.__CHECKOUT_CONFIG__ = {
            subtotal: {{ (int) ($subtotal ?? 0) }},
            weight: {{ (int) ($weight ?? 1000) }},
            originId: {{ (int) env('ORIGIN_DESTINATION_ID', 4816) }},
            csrf: @json(csrf_token()),
            urls: {
                search: '/shipping/search',
                couriers: '/shipping/couriers',
                check: '/shipping/check',
            }
        };
    </script>

    <section class="mt-8" x-data="checkoutApp()" x-init="init()">

        @if (empty($items) || count($items) === 0)
            <div class="card-static p-10 text-center">
                <div class="text-5xl opacity-60 mb-4">🛒</div>
                <div class="font-extrabold text-lg text-ink">Keranjang Masih Kosong</div>
                <p class="mt-2 text-sm text-ink/60 max-w-xs mx-auto">
                    Tambahkan produk ke keranjang untuk melanjutkan checkout.
                </p>
                <a href="{{ route('products.index') }}" class="inline-block mt-4 btn-primary">
                    🛍️ Belanja Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr] gap-6 items-start">

                {{-- ========== FORM CHECKOUT ========== --}}
                <form method="POST" action="{{ route('checkout.store') }}" class="space-y-5" @submit="handleSubmit">
                    @csrf

                    {{-- Data Pembeli --}}
                    <div class="card-static p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <span
                                class="w-10 h-10 rounded-xl bg-humble flex items-center justify-center text-white text-lg">👤</span>
                            <div>
                                <h2 class="font-bold text-ink text-lg">Data Pembeli</h2>
                                <p class="text-xs text-ink/50">Informasi kontak untuk pesanan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-ink/60 uppercase tracking-wide mb-2 block">Nama
                                    Lengkap</label>
                                <input name="customer_name" required class="field" placeholder="Masukkan nama lengkap"
                                    value="{{ old('customer_name', auth()->user()->name ?? '') }}">
                            </div>

                            <div>
                                <label class="text-xs font-bold text-ink/60 uppercase tracking-wide mb-2 block">No.
                                    WhatsApp</label>
                                <input name="phone" required class="field" placeholder="08xxxxxxxxxx"
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-ink/60 uppercase tracking-wide mb-2 block">Email
                                    (Opsional)</label>
                                <input name="email" type="email" class="field" placeholder="email@example.com"
                                    value="{{ old('email', auth()->user()->email ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Alamat Pengiriman --}}
                    <div class="card-static p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <span
                                class="w-10 h-10 rounded-xl bg-bubble flex items-center justify-center text-white text-lg">📍</span>
                            <div>
                                <h2 class="font-bold text-ink text-lg">Alamat Pengiriman</h2>
                                <p class="text-xs text-ink/50">Cari lokasi tujuan pengiriman</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            {{-- Search Destination --}}
                            <div class="relative">
                                <label class="text-xs font-bold text-ink/60 uppercase tracking-wide mb-2 block">
                                    Cari Kota/Kecamatan/Kelurahan
                                </label>
                                <input type="text" class="field" placeholder="Ketik minimal 3 huruf, misal: Bandung..."
                                    x-model="searchQuery" @input.debounce.500ms="searchDestination()"
                                    @focus="showResults = true">

                                <div x-show="searching" class="absolute right-3 top-9 text-ink/50">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>

                                {{-- Search Results Dropdown --}}
                                <div x-show="showResults && searchResults.length > 0" x-transition
                                    @click.away="showResults = false"
                                    class="absolute z-50 w-full mt-1 bg-white rounded-xl shadow-lg border border-humble/20 max-h-64 overflow-y-auto">
                                    <template x-for="result in searchResults" :key="result.id">
                                        <div @click="selectDestination(result)"
                                            class="px-4 py-3 hover:bg-pinkbg cursor-pointer border-b border-humble/10 last:border-0">
                                            <div class="font-semibold text-ink text-sm" x-text="result.label"></div>
                                            <div class="text-xs text-ink/50 mt-1">
                                                <span x-text="result.city"></span> • <span x-text="result.province"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Selected Destination Display --}}
                            <div x-show="selectedDestination" class="bg-pinkbg/50 border border-bubble/20 rounded-xl p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-bold text-ink" x-text="selectedDestination?.label"></div>
                                        <div class="text-xs text-ink/60 mt-1">
                                            Kode Pos: <span x-text="selectedDestination?.zip_code || '-'"></span>
                                        </div>
                                    </div>
                                    <button type="button" @click="clearDestination()"
                                        class="text-ink/40 hover:text-inlove">
                                        ✕
                                    </button>
                                </div>
                            </div>

                            {{-- Hidden inputs for form submission --}}
                            <input type="hidden" name="destination_id" :value="selectedDestination?.id || ''">
                            <input type="hidden" name="destination_label" :value="selectedDestination?.label || ''">
                            <input type="hidden" name="postal_code" :value="selectedDestination?.zip_code || ''">

                            {{-- Alamat Detail --}}
                            <div>
                                <label class="text-xs font-bold text-ink/60 uppercase tracking-wide mb-2 block">Alamat
                                    Lengkap</label>
                                <textarea name="address" required rows="3" class="field" placeholder="Nama jalan, no. rumah, RT/RW, patokan">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Pengiriman --}}
                    <div class="card-static p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <span
                                class="w-10 h-10 rounded-xl bg-inlove flex items-center justify-center text-white text-lg">🚚</span>
                            <div>
                                <h2 class="font-bold text-ink text-lg">Metode Pengiriman</h2>
                                <p class="text-xs text-ink/50">Pilih kurir dan layanan pengiriman</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Kurir --}}
                            <div>
                                <label
                                    class="text-xs font-bold text-ink/60 uppercase tracking-wide mb-2 block">Kurir</label>
                                <select name="courier" class="field" x-model="selectedCourier"
                                    @change="checkShippingCost()" required :disabled="!selectedDestination">
                                    <option value="">-- Pilih Kurir --</option>
                                    <template x-for="(name, code) in couriers" :key="code">
                                        <option :value="code" x-text="name"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Layanan --}}
                            <div>
                                <label
                                    class="text-xs font-bold text-ink/60 uppercase tracking-wide mb-2 block">Layanan</label>
                                <select name="service" class="field" x-model="selectedService"
                                    @change="onServiceChange()" required
                                    :disabled="services.length === 0 && selectedService !== 'pickup'">
                                    <option value="pickup">📍 Ambil di Tempat (Gratis)</option>
                                    <template x-if="services.length > 0">
                                        <option value="" disabled>──────────────</option>
                                    </template>
                                    <template x-for="s in services" :key="s.service">
                                        <option :value="s.service"
                                            x-text="`${s.service} • Rp${s.cost.toLocaleString('id-ID')} • ${s.etd} hari`">
                                        </option>
                                    </template>
                                </select>
                                <div x-show="loadingCost" class="text-xs text-bubble mt-1">⏳ Mengecek ongkir...</div>
                                <div x-show="costError" class="text-xs text-red-500 mt-1" x-text="costError"></div>
                            </div>
                        </div>

                        <input type="hidden" name="shipping_cost" :value="shippingCost">
                    </div>

                    {{-- Pembayaran --}}
                    <div class="card-static p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <span
                                class="w-10 h-10 rounded-xl bg-humble flex items-center justify-center text-white text-lg">💳</span>
                            <div>
                                <h2 class="font-bold text-ink text-lg">Pembayaran</h2>
                                <p class="text-xs text-ink/50">Pilih metode pembayaran</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            {{-- Pembayaran Online (Midtrans) --}}
                            <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition"
                                :class="paymentMethod === 'transfer' ? 'border-bubble bg-pinkbg/50' :
                                    'border-humble/20 hover:border-bubble/50'">
                                <input type="radio" name="payment_method" value="transfer" x-model="paymentMethod"
                                    class="mt-1 w-5 h-5 accent-bubble">
                                <div class="flex-1">
                                    <div class="font-bold text-ink flex items-center gap-2">
                                        💳 Pembayaran Online
                                        <span class="badge badge-pink text-xs">Recommended</span>
                                    </div>
                                    <p class="text-xs text-ink/60 mt-1">GoPay, ShopeePay, OVO, Transfer Bank, Kartu
                                        Kredit/Debit, Indomaret/Alfamart</p>
                                </div>
                            </label>

                            {{-- COD --}}
                            <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition"
                                :class="paymentMethod === 'cod' ? 'border-bubble bg-pinkbg/50' :
                                    'border-humble/20 hover:border-bubble/50'">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentMethod"
                                    class="mt-1 w-5 h-5 accent-bubble">
                                <div class="flex-1">
                                    <div class="font-bold text-ink flex items-center gap-2">
                                        💵 COD (Bayar di Tempat)
                                    </div>
                                    <p class="text-xs text-ink/60 mt-1">Bayar tunai saat barang diterima. Tersedia untuk
                                        wilayah tertentu.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn-primary w-full py-4 text-lg font-bold shadow-xl"
                        :disabled="!canSubmit"
                        :class="!canSubmit ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02] transition-transform'">
                        🎉 Buat Pesanan
                    </button>
                </form>

                {{-- ========== RINGKASAN ========== --}}
                <aside class="lg:sticky lg:top-24">
                    <div class="card-static p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <span
                                class="w-10 h-10 rounded-xl bg-humble flex items-center justify-center text-white text-lg">📋</span>
                            <div>
                                <h2 class="font-bold text-ink text-lg">Ringkasan Pesanan</h2>
                                <p class="text-xs text-ink/50">{{ count($items) }} produk</p>
                            </div>
                        </div>

                        {{-- Items --}}
                        <div class="space-y-4 max-h-64 overflow-y-auto pr-2">
                            @foreach ($items as $it)
                                <div class="flex gap-4 items-start">
                                    @if (!empty($it['image']))
                                        <img src="{{ asset('storage/' . $it['image']) }}" alt="{{ $it['name'] }}"
                                            class="w-16 h-16 rounded-xl object-cover border border-humble/10">
                                    @else
                                        <div
                                            class="w-16 h-16 rounded-xl bg-gradient-to-br from-bubble/20 to-almost flex items-center justify-center text-2xl">
                                            🎁</div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-ink text-sm truncate">{{ $it['name'] }}</div>
                                        <div class="text-xs text-ink/50">Qty: {{ $it['qty'] }}</div>
                                        <div class="text-sm font-bold text-inlove mt-1">
                                            Rp{{ number_format($it['price'] * $it['qty'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="h-px bg-humble/10 my-5"></div>

                        {{-- Totals --}}
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-ink/60">Subtotal</span>
                                <span class="font-bold text-ink">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ink/60">Ongkos Kirim</span>
                                <span class="font-bold text-ink"
                                    x-text="shippingCost === 0 ? 'Gratis' : `Rp${shippingCost.toLocaleString('id-ID')}`"></span>
                            </div>
                        </div>

                        <div class="h-px bg-humble/10 my-4"></div>

                        <div class="flex justify-between items-center">
                            <span class="font-bold text-ink text-lg">Total</span>
                            <span class="font-extrabold text-2xl text-inlove"
                                x-text="`Rp${total.toLocaleString('id-ID')}`"></span>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="mt-4 bg-pinkbg border border-bubble/20 rounded-xl p-4">
                        <div class="flex gap-3">
                            <span class="text-xl">ℹ️</span>
                            <div>
                                <div class="font-bold text-ink text-sm">Butuh Bantuan?</div>
                                <p class="text-xs text-ink/60 mt-1">Hubungi kami via WhatsApp jika ada kendala saat
                                    checkout.</p>
                                <a href="{{ route('contact') }}"
                                    class="inline-block mt-2 text-xs font-bold text-inlove hover:underline">
                                    Hubungi Kami →
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        @endif
    </section>

    <script>
        function checkoutApp() {
            const cfg = window.__CHECKOUT_CONFIG__ || {};
            return {
                subtotal: cfg.subtotal || 0,
                weight: cfg.weight || 1000,
                originId: cfg.originId || 4816,
                csrf: cfg.csrf || '',
                urls: cfg.urls || {},

                // Search
                searchQuery: '',
                searchResults: [],
                showResults: false,
                searching: false,
                selectedDestination: null,

                // Shipping
                couriers: {},
                selectedCourier: '',
                services: [],
                selectedService: 'pickup',
                shippingCost: 0,
                loadingCost: false,
                costError: '',

                // Payment
                paymentMethod: 'transfer',

                async init() {
                    await this.loadCouriers();
                },

                async loadCouriers() {
                    try {
                        const res = await fetch(this.urls.couriers);
                        const data = await res.json();
                        this.couriers = data.data || {};
                    } catch (e) {
                        console.error('Failed to load couriers', e);
                        this.couriers = {
                            'jne': 'JNE',
                            'pos': 'POS Indonesia',
                            'tiki': 'TIKI'
                        };
                    }
                },

                async searchDestination() {
                    if (this.searchQuery.length < 3) {
                        this.searchResults = [];
                        return;
                    }

                    this.searching = true;
                    try {
                        const res = await fetch(`${this.urls.search}?q=${encodeURIComponent(this.searchQuery)}`);
                        const data = await res.json();
                        this.searchResults = data.data || [];
                        this.showResults = true;
                    } catch (e) {
                        console.error('Search failed', e);
                        this.searchResults = [];
                    }
                    this.searching = false;
                },

                selectDestination(dest) {
                    this.selectedDestination = dest;
                    this.searchQuery = dest.label;
                    this.showResults = false;
                    this.searchResults = [];

                    // Reset shipping
                    this.services = [];
                    this.selectedService = 'pickup';
                    this.shippingCost = 0;

                    // Auto check if courier selected
                    if (this.selectedCourier) {
                        this.checkShippingCost();
                    }
                },

                clearDestination() {
                    this.selectedDestination = null;
                    this.searchQuery = '';
                    this.services = [];
                    this.selectedService = 'pickup';
                    this.shippingCost = 0;
                },

                async checkShippingCost() {
                    this.services = [];
                    this.costError = '';

                    if (!this.selectedDestination || !this.selectedCourier) return;

                    this.loadingCost = true;
                    try {
                        const res = await fetch(this.urls.check, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                origin: this.originId,
                                destination: this.selectedDestination.id,
                                weight: this.weight,
                                courier: this.selectedCourier
                            })
                        });

                        const data = await res.json();
                        if (data.ok && data.services && data.services.length > 0) {
                            this.services = data.services;
                        } else {
                            this.costError = data.message || 'Tidak ada layanan tersedia untuk kurir ini.';
                        }
                    } catch (e) {
                        this.costError = 'Gagal mengecek ongkir. Silakan coba lagi.';
                        console.error('Failed to check shipping cost', e);
                    }
                    this.loadingCost = false;
                },

                onServiceChange() {
                    if (this.selectedService === 'pickup') {
                        this.shippingCost = 0;
                        return;
                    }
                    const s = this.services.find(x => x.service === this.selectedService);
                    this.shippingCost = s ? s.cost : 0;
                },

                get total() {
                    return this.subtotal + this.shippingCost;
                },

                get canSubmit() {
                    // Pickup doesn't need destination
                    if (this.selectedService === 'pickup') return true;
                    // For shipping, need destination and valid cost
                    return this.selectedDestination && this.shippingCost > 0;
                },

                handleSubmit(e) {
                    if (!this.canSubmit) {
                        e.preventDefault();
                        alert('Silakan lengkapi data pengiriman terlebih dahulu.');
                    }
                }
            }
        }
    </script>

@endsection
