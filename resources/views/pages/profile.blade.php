@extends('layouts.app')
@section('title', 'About — Tsania Craft')

@section('content')

    {{-- ================= HERO ================= --}}
    <section class="hero-gradient rounded-3xl p-8 md:p-12 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bubble/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-almost/50 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <span class="badge badge-pink mb-4">ℹ️ Tentang Kami</span>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-ink">About</h1>
            <div class="mt-2 text-sm text-ink/50">
                Home <span class="opacity-50">/</span> About
            </div>
            <p class="mt-4 text-ink/70 max-w-2xl mx-auto leading-relaxed">
                Mengenal Tsania Craft: brand kerajinan handmade dengan gaya modern-minimal, fokus kualitas dan layanan
                ramah.
            </p>
        </div>
    </section>

    {{-- ================= TENTANG ================= --}}
    <section class="mt-10">
        <div class="card-static p-6 md:p-8">
            <div class="flex items-start gap-4">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-bubble to-inlove flex items-center justify-center text-white text-2xl shadow-soft flex-shrink-0">
                    🏪
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-extrabold text-ink">Tentang Tsania Craft</h2>
                    <p class="mt-3 text-ink/70 leading-relaxed">
                        Tsania Craft memproduksi kerajinan handmade seperti resin floral, totebag custom, makrame, hingga
                        paket souvenir.
                        Kami menerima custom order untuk kebutuhan hadiah, acara, maupun souvenir pernikahan.
                    </p>
                    <div class="mt-4 flex gap-2 flex-wrap">
                        <span class="badge badge-pink">🎨 Resin Art</span>
                        <span class="badge badge-pink">👜 Totebag</span>
                        <span class="badge badge-pink">🪢 Makrame</span>
                        <span class="badge badge-pink">🎁 Souvenir</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= VISI & MISI ================= --}}
    <section class="mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Visi --}}
            <div class="card-static p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-almost flex items-center justify-center text-2xl">
                        🎯
                    </div>
                    <h2 class="text-xl font-extrabold text-ink">Visi</h2>
                </div>
                <p class="text-ink/70 leading-relaxed">
                    Menjadi pilihan utama kerajinan handmade yang modern, berkualitas, dan terpercaya bagi UMKM lokal.
                </p>
            </div>

            {{-- Misi --}}
            <div class="card-static p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-almost flex items-center justify-center text-2xl">
                        🚀
                    </div>
                    <h2 class="text-xl font-extrabold text-ink">Misi</h2>
                </div>
                <ul class="space-y-3 text-ink/70">
                    <li class="flex items-start gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-bubble/20 flex items-center justify-center text-bubble text-sm flex-shrink-0 mt-0.5">✓</span>
                        <span>Menghadirkan produk handmade dengan finishing rapi dan konsisten.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-bubble/20 flex items-center justify-center text-bubble text-sm flex-shrink-0 mt-0.5">✓</span>
                        <span>Memberi pengalaman belanja online yang mudah & responsif.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-bubble/20 flex items-center justify-center text-bubble text-sm flex-shrink-0 mt-0.5">✓</span>
                        <span>Menerima custom order sesuai kebutuhan pelanggan.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-bubble/20 flex items-center justify-center text-bubble text-sm flex-shrink-0 mt-0.5">✓</span>
                        <span>Mendukung pemberdayaan UMKM melalui pemasaran digital.</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ================= WHY CHOOSE US ================= --}}
    <section class="mt-8">
        <h2 class="text-xl md:text-2xl font-extrabold text-ink text-center mb-6">✨ Kenapa Pilih Kami?</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card p-5 text-center">
                <div class="text-3xl mb-3">🎨</div>
                <div class="font-bold text-ink">Handmade</div>
                <div class="mt-1 text-xs text-ink/60">Dibuat dengan tangan</div>
            </div>
            <div class="card p-5 text-center">
                <div class="text-3xl mb-3">⭐</div>
                <div class="font-bold text-ink">Berkualitas</div>
                <div class="mt-1 text-xs text-ink/60">Finishing rapi</div>
            </div>
            <div class="card p-5 text-center">
                <div class="text-3xl mb-3">✏️</div>
                <div class="font-bold text-ink">Custom</div>
                <div class="mt-1 text-xs text-ink/60">Sesuai keinginan</div>
            </div>
            <div class="card p-5 text-center">
                <div class="text-3xl mb-3">📦</div>
                <div class="font-bold text-ink">Aman</div>
                <div class="mt-1 text-xs text-ink/60">Packing rapi</div>
            </div>
        </div>
    </section>

    {{-- ================= ALAMAT & JAM ================= --}}
    <section class="mt-8">
        <div class="card-static p-6 md:p-8">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-xl bg-humble flex items-center justify-center text-white text-xl">
                    📍
                </div>
                <h2 class="text-xl font-extrabold text-ink">Alamat & Jam Operasional</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-lg bg-almost flex items-center justify-center flex-shrink-0">🏠</span>
                    <div>
                        <div class="font-bold text-ink text-sm">Alamat</div>
                        <div class="text-ink/70 text-sm">(isi alamat lengkap Tsania Craft)</div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-lg bg-almost flex items-center justify-center flex-shrink-0">🕐</span>
                    <div>
                        <div class="font-bold text-ink text-sm">Jam Operasional</div>
                        <div class="text-ink/70 text-sm">Senin–Sabtu, 09.00–17.00</div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-lg bg-almost flex items-center justify-center flex-shrink-0">📝</span>
                    <div>
                        <div class="font-bold text-ink text-sm">Catatan</div>
                        <div class="text-ink/70 text-sm">Custom order: estimasi 2–5 hari</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= CTA ================= --}}
    <section class="mt-10 hero-gradient rounded-3xl p-8 text-center">
        <h3 class="text-xl font-extrabold text-ink">Tertarik dengan Produk Kami?</h3>
        <p class="mt-2 text-ink/70">Lihat koleksi produk atau hubungi kami untuk custom order.</p>
        <div class="mt-5 flex gap-3 justify-center flex-wrap">
            <a href="{{ route('products.index') }}" class="btn-primary">🛍️ Lihat Produk</a>
            <a href="{{ route('contact') }}" class="btn-outline">💬 Hubungi Kami</a>
        </div>
    </section>

@endsection
