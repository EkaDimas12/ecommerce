@extends('layouts.app')
@section('title', 'Pusat Bantuan — Tsania Craft')

@section('content')

    {{-- ================= HERO ================= --}}
    <section class="hero-gradient rounded-3xl p-8 md:p-12 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bubble/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-almost/50 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <span class="badge badge-pink mb-4">❓ Pusat Bantuan</span>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-ink">Pusat Bantuan</h1>
            <div class="mt-2 text-sm text-ink/50">
                <a href="{{ route('home') }}" class="hover:text-bubble">Home</a>
                <span class="opacity-50">/</span> Help
            </div>
            <p class="mt-4 text-ink/70 max-w-2xl mx-auto leading-relaxed">
                Temukan jawaban atas pertanyaan umum seputar pesanan, pembayaran, dan pengiriman.
            </p>
        </div>
    </section>

    <section class="mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- FAQ --}}
            <div class="lg:col-span-3 card-static p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-bubble to-inlove flex items-center justify-center text-white text-xl">
                        📚
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-ink">FAQ</h2>
                        <p class="text-sm text-ink/60">Pertanyaan yang paling sering ditanyakan.</p>
                    </div>
                </div>

                <div class="space-y-4" x-data="{ open: 1 }">
                    {{-- FAQ 1 --}}
                    <div class="rounded-xl border border-humble/10 overflow-hidden">
                        <button @click="open = open === 1 ? null : 1"
                            class="w-full flex items-center justify-between gap-4 p-4 text-left hover:bg-pinkbg transition">
                            <span class="font-bold text-ink flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-almost flex items-center justify-center text-sm">1</span>
                                Apakah bisa custom order?
                            </span>
                            <span class="text-bubble" x-text="open === 1 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="open === 1" x-collapse class="px-4 pb-4">
                            <p class="text-ink/70 text-sm leading-relaxed pl-11">
                                <strong>Tentu bisa!</strong> Kamu dapat request:
                            </p>
                            <ul class="text-ink/70 text-sm leading-relaxed pl-11 mt-2 list-disc list-inside space-y-1">
                                <li>Custom nama/tulisan</li>
                                <li>Pilihan warna sesuai keinginan</li>
                                <li>Tema khusus (ulang tahun, pernikahan, dll)</li>
                                <li>Ukuran custom</li>
                            </ul>
                            <p class="text-ink/70 text-sm leading-relaxed pl-11 mt-2">
                                Estimasi produksi custom: <strong>2–5 hari kerja</strong>. Hubungi CS untuk diskusi desain.
                            </p>
                        </div>
                    </div>

                    {{-- FAQ 2 --}}
                    <div class="rounded-xl border border-humble/10 overflow-hidden">
                        <button @click="open = open === 2 ? null : 2"
                            class="w-full flex items-center justify-between gap-4 p-4 text-left hover:bg-pinkbg transition">
                            <span class="font-bold text-ink flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-almost flex items-center justify-center text-sm">2</span>
                                Metode pembayaran apa saja?
                            </span>
                            <span class="text-bubble" x-text="open === 2 ? '−' : '+'">,</span>
                        </button>
                        <div x-show="open === 2" x-collapse class="px-4 pb-4">
                            <p class="text-ink/70 text-sm leading-relaxed pl-11 mb-2">
                                Kami menyediakan berbagai metode pembayaran:
                            </p>
                            <div class="pl-11 space-y-2">
                                <div class="flex items-center gap-2 text-sm">
                                    <span
                                        class="w-6 h-6 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">💳</span>
                                    <span class="text-ink"><strong>Pembayaran Online</strong> — GoPay, ShopeePay, OVO,
                                        DANA</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span
                                        class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">🏦</span>
                                    <span class="text-ink"><strong>Transfer Bank</strong> — BCA, BRI, BNI, Mandiri (Virtual
                                        Account)</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span
                                        class="w-6 h-6 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">💳</span>
                                    <span class="text-ink"><strong>Kartu Kredit/Debit</strong> — Visa, Mastercard</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span
                                        class="w-6 h-6 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">🏪</span>
                                    <span class="text-ink"><strong>Minimarket</strong> — Indomaret, Alfamart</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span
                                        class="w-6 h-6 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center">💵</span>
                                    <span class="text-ink"><strong>COD</strong> — Bayar di tempat (wilayah tertentu)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FAQ 3 --}}
                    <div class="rounded-xl border border-humble/10 overflow-hidden">
                        <button @click="open = open === 3 ? null : 3"
                            class="w-full flex items-center justify-between gap-4 p-4 text-left hover:bg-pinkbg transition">
                            <span class="font-bold text-ink flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-almost flex items-center justify-center text-sm">3</span>
                                Bagaimana cek ongkir?
                            </span>
                            <span class="text-bubble" x-text="open === 3 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="open === 3" x-collapse class="px-4 pb-4">
                            <p class="text-ink/70 text-sm leading-relaxed pl-11">
                                Ongkir dihitung otomatis saat checkout:
                            </p>
                            <ol class="text-ink/70 text-sm leading-relaxed pl-11 mt-2 list-decimal list-inside space-y-1">
                                <li>Ketik nama kota/kecamatan di kolom pencarian</li>
                                <li>Pilih lokasi dari dropdown</li>
                                <li>Pilih kurir (JNE, J&T, SiCepat, POS, TIKI, dll)</li>
                                <li>Pilih layanan pengiriman</li>
                            </ol>
                            <p class="text-ink/70 text-sm leading-relaxed pl-11 mt-2">
                                <strong>Gratis ongkir?</strong> Pilih "Ambil di Tempat" jika lokasi kamu di sekitar Bandung.
                            </p>
                        </div>
                    </div>

                    {{-- FAQ 4 --}}
                    <div class="rounded-xl border border-humble/10 overflow-hidden">
                        <button @click="open = open === 4 ? null : 4"
                            class="w-full flex items-center justify-between gap-4 p-4 text-left hover:bg-pinkbg transition">
                            <span class="font-bold text-ink flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-almost flex items-center justify-center text-sm">4</span>
                                Apakah bisa retur/refund?
                            </span>
                            <span class="text-bubble" x-text="open === 4 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="open === 4" x-collapse class="px-4 pb-4">
                            <p class="text-ink/70 text-sm leading-relaxed pl-11">
                                Retur/refund dapat dilakukan jika:
                            </p>
                            <ul class="text-ink/70 text-sm leading-relaxed pl-11 mt-2 list-disc list-inside space-y-1">
                                <li>Barang rusak saat diterima</li>
                                <li>Barang tidak sesuai pesanan</li>
                                <li>Terdapat cacat produksi</li>
                            </ul>
                            <div class="pl-11 mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-yellow-800 text-sm">
                                    ⚠️ <strong>Penting:</strong> Wajib sertakan video unboxing sebagai bukti klaim.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- FAQ 5 --}}
                    <div class="rounded-xl border border-humble/10 overflow-hidden">
                        <button @click="open = open === 5 ? null : 5"
                            class="w-full flex items-center justify-between gap-4 p-4 text-left hover:bg-pinkbg transition">
                            <span class="font-bold text-ink flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-almost flex items-center justify-center text-sm">5</span>
                                Berapa lama pengiriman?
                            </span>
                            <span class="text-bubble" x-text="open === 5 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="open === 5" x-collapse class="px-4 pb-4">
                            <p class="text-ink/70 text-sm leading-relaxed pl-11">
                                Estimasi pengiriman tergantung kurir yang dipilih:
                            </p>
                            <div class="pl-11 mt-2 grid grid-cols-2 gap-2 text-sm">
                                <div class="bg-pinkbg p-2 rounded-lg">
                                    <div class="font-semibold text-ink">Pulau Jawa</div>
                                    <div class="text-ink/60">1-3 hari</div>
                                </div>
                                <div class="bg-pinkbg p-2 rounded-lg">
                                    <div class="font-semibold text-ink">Luar Jawa</div>
                                    <div class="text-ink/60">3-7 hari</div>
                                </div>
                            </div>
                            <p class="text-ink/70 text-sm leading-relaxed pl-11 mt-2">
                                Pesanan diproses dalam <strong>1-2 hari kerja</strong> setelah pembayaran dikonfirmasi.
                            </p>
                        </div>
                    </div>

                    {{-- FAQ 6 --}}
                    <div class="rounded-xl border border-humble/10 overflow-hidden">
                        <button @click="open = open === 6 ? null : 6"
                            class="w-full flex items-center justify-between gap-4 p-4 text-left hover:bg-pinkbg transition">
                            <span class="font-bold text-ink flex items-center gap-3">
                                <span
                                    class="w-8 h-8 rounded-lg bg-almost flex items-center justify-center text-sm">6</span>
                                Bagaimana cara membatalkan pesanan?
                            </span>
                            <span class="text-bubble" x-text="open === 6 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="open === 6" x-collapse class="px-4 pb-4">
                            <p class="text-ink/70 text-sm leading-relaxed pl-11">
                                Kamu bisa membatalkan pesanan melalui menu <strong>"Pesanan Saya"</strong> selama status
                                masih "Baru" dan belum diproses.
                            </p>
                            <ol class="text-ink/70 text-sm leading-relaxed pl-11 mt-2 list-decimal list-inside space-y-1">
                                <li>Login ke akun kamu</li>
                                <li>Buka menu "Pesanan Saya"</li>
                                <li>Klik tombol "Batalkan" pada pesanan</li>
                            </ol>
                            <p class="text-ink/70 text-sm leading-relaxed pl-11 mt-2">
                                Jika pesanan sudah diproses, hubungi CS untuk bantuan lebih lanjut.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CUSTOMER SUPPORT --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="card-static p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-humble flex items-center justify-center text-white text-lg">
                            🎧
                        </div>
                        <h2 class="text-lg font-extrabold text-ink">Customer Support</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-pinkbg">
                            <span class="text-xl">🕐</span>
                            <div>
                                <div class="text-xs text-ink/50">Jam Operasional</div>
                                <div class="text-sm font-medium">Senin–Sabtu, 09.00–17.00 WIB</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-pinkbg">
                            <span class="text-xl">⚡</span>
                            <div>
                                <div class="text-xs text-ink/50">Respon Cepat</div>
                                <div class="text-sm font-medium">
                                    < 30 menit (jam kerja)</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-pinkbg">
                                <span class="text-xl">📱</span>
                                <div>
                                    <div class="text-xs text-ink/50">WhatsApp</div>
                                    <div class="text-sm font-medium">+62 812-3456-7890</div>
                                </div>
                            </div>
                        </div>

                        @php $wa = env('WHATSAPP_NUMBER','6281234567890'); @endphp
                        <div class="mt-5 flex flex-col gap-3">
                            <a class="btn-primary text-center"
                                href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Tsania Craft, saya butuh bantuan.') }}">
                                💬 Chat WhatsApp
                            </a>
                            <a class="btn-outline text-center" href="{{ route('products.index') }}">🛍️ Belanja
                                Sekarang</a>
                        </div>
                    </div>

                    {{-- Status Pesanan --}}
                    <div class="card-static p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-2xl">📦</span>
                            <h3 class="font-bold text-ink">Cek Status Pesanan</h3>
                        </div>
                        <p class="text-sm text-ink/60 mb-3">Lihat status dan riwayat pesanan kamu.</p>
                        <a href="{{ route('orders.history') }}"
                            class="block w-full text-center py-3 bg-pinkbg text-ink font-semibold rounded-xl hover:bg-bubble/10 transition">
                            Lihat Pesanan Saya →
                        </a>
                    </div>

                    {{-- Bantuan Lain --}}
                    <div class="card-static p-5 text-center">
                        <div class="text-3xl mb-2">🤝</div>
                        <div class="font-bold text-ink">Butuh Bantuan Lain?</div>
                        <p class="mt-1 text-sm text-ink/60">Tidak menemukan jawaban? Tim kami siap membantu!</p>
                        <a href="{{ route('contact') }}"
                            class="inline-block mt-3 text-sm font-semibold text-inlove hover:underline">
                            Hubungi Kami →
                        </a>
                    </div>
                </div>
            </div>
    </section>

@endsection
