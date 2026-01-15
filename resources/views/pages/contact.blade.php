@extends('layouts.app')
@section('title', 'Contact — Tsania Craft')

@section('content')

    {{-- ================= HERO ================= --}}
    <section class="hero-gradient rounded-3xl p-8 md:p-12 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bubble/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-almost/50 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <span class="badge badge-pink mb-4">📞 Hubungi Kami</span>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-ink">Contact</h1>
            <div class="mt-2 text-sm text-ink/50">
                Home <span class="opacity-50">/</span> Contact
            </div>
            <p class="mt-4 text-ink/70 max-w-2xl mx-auto leading-relaxed">
                Hubungi kami untuk tanya stok, custom order, atau konsultasi souvenir.
                Kamu juga bisa kirim testimoni agar tampil di Beranda.
            </p>
        </div>
    </section>

    <section class="mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- FORM TESTIMONI --}}
            <div class="lg:col-span-3 card-static p-6 md:p-8">
                <div class="flex items-start gap-4 mb-6">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-bubble to-inlove flex items-center justify-center text-white text-xl flex-shrink-0">
                        💬
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-ink">Kirim Testimoni</h2>
                        <p class="mt-1 text-sm text-ink/60">Testimoni kamu membantu pelanggan lain lebih percaya.</p>
                    </div>
                </div>

                {{-- ERROR VALIDASI --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
                        <div class="font-bold text-red-700 flex items-center gap-2">
                            <span>❌</span> Periksa input:
                        </div>
                        <ul class="mt-2 text-red-600 text-sm space-y-1 ml-6 list-disc">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('testimonials.store') }}" class="space-y-4">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-ink/70 mb-2">Nama</label>
                        <input id="name" class="field" name="name" value="{{ old('name') }}"
                            placeholder="Contoh: Nabila">
                    </div>

                    {{-- Rating --}}
                    <div>
                        <label for="rating" class="block text-sm font-semibold text-ink/70 mb-2">Rating</label>
                        <select id="rating" class="field" name="rating">
                            <option value="">Pilih rating</option>
                            <option value="5" @selected(old('rating') == 5)>⭐⭐⭐⭐⭐ (5)</option>
                            <option value="4" @selected(old('rating') == 4)>⭐⭐⭐⭐ (4)</option>
                            <option value="3" @selected(old('rating') == 3)>⭐⭐⭐ (3)</option>
                            <option value="2" @selected(old('rating') == 2)>⭐⭐ (2)</option>
                            <option value="1" @selected(old('rating') == 1)>⭐ (1)</option>
                        </select>
                    </div>

                    {{-- Pesan --}}
                    <div>
                        <label for="message" class="block text-sm font-semibold text-ink/70 mb-2">Pesan</label>
                        <textarea id="message" class="field" name="message" rows="5" placeholder="Tulis testimoni kamu..."
                            style="resize:vertical;">{{ old('message') }}</textarea>
                        <div class="mt-1 text-xs text-ink/50">Maks. 600 karakter.</div>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex gap-3 flex-wrap pt-2">
                        <button type="submit" class="btn-primary">✨ Kirim Testimoni</button>
                        <a class="btn-outline" href="{{ route('products.index') }}">🛍️ Lihat Produk</a>
                    </div>
                </form>
            </div>

            {{-- INFO KONTAK --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="card-static p-6">
                    <h2 class="text-lg font-extrabold text-ink flex items-center gap-2">
                        <span
                            class="w-8 h-8 rounded-lg bg-humble flex items-center justify-center text-white text-sm">📱</span>
                        Informasi Kontak
                    </h2>
                    <p class="mt-3 text-sm text-ink/60">
                        Respon tercepat via WhatsApp. Untuk custom order, sebutkan detail ukuran/warna/tema.
                    </p>

                    <div class="mt-5 space-y-3">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-pinkbg">
                            <span class="text-xl">📞</span>
                            <div class="text-sm font-medium">+62 812-3456-7890</div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-pinkbg">
                            <span class="text-xl">📧</span>
                            <div class="text-sm font-medium">hello@tsaniacraft.id</div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-pinkbg">
                            <span class="text-xl">📸</span>
                            <div class="text-sm font-medium">@tsaniacraft</div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-pinkbg">
                            <span class="text-xl">🕐</span>
                            <div class="text-sm font-medium">Senin–Sabtu, 09.00–17.00</div>
                        </div>
                    </div>

                    @php $wa = env('WHATSAPP_NUMBER','6281234567890'); @endphp
                    <div class="mt-5 flex flex-col gap-3">
                        <a class="btn-secondary text-center"
                            href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Tsania Craft, saya mau tanya produk / custom order.') }}">
                            💬 Chat WhatsApp
                        </a>
                        <a class="btn-outline text-center" href="{{ route('help') }}">❓ Bantuan / FAQ</a>
                    </div>
                </div>

                <div class="card-static p-5">
                    <div class="flex items-center gap-2 text-sm font-bold text-ink">
                        <span>📝</span> Catatan
                    </div>
                    <p class="mt-2 text-sm text-ink/60">
                        Untuk pemesanan custom, estimasi produksi biasanya 2–5 hari (tergantung antrian & tingkat detail).
                    </p>
                </div>
            </div>

        </div>
    </section>

@endsection
