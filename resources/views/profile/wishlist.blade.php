@extends('profile.layout')

@section('title', 'Wishlist Saya — Tsania Craft')

@section('dashboard-content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-6">
            <h1 class="text-2xl font-black text-ink">Wishlist Saya</h1>
            <p class="text-sm text-ink/60 mt-1">Produk favorit yang kamu simpan untuk nanti.</p>
        </div>

        @if ($wishlistItems->isEmpty())
            <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-12 text-center">
                <div class="text-5xl mb-4 opacity-50">❤️</div>
                <h3 class="text-xl font-bold text-ink">Wishlist Kosong</h3>
                <p class="text-sm text-ink/60 mt-2">Belum ada produk yang kamu tambahkan ke wishlist.</p>
                <a href="{{ route('products.index') }}" class="btn-primary inline-block mt-6 px-8 py-3">
                    🛍️ Jelajahi Produk
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($wishlistItems as $p)
                    <div class="product-card group relative">
                        {{-- Remove from Wishlist Button --}}
                        <form action="{{ route('wishlist.toggle', $p->id) }}" method="POST" class="absolute top-4 right-4 z-10">
                            @csrf
                            <button type="submit" class="w-8 h-8 bg-white/90 backdrop-blur rounded-full shadow-lg flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition">
                                ✕
                            </button>
                        </form>

                        <a href="{{ route('products.show', $p->slug) }}" class="block p-3">
                            @if ($p->main_image)
                                <img src="{{ asset('storage/' . $p->main_image) }}" alt="{{ $p->name }}"
                                    class="w-full aspect-square object-cover rounded-2xl">
                            @else
                                <div class="w-full aspect-square rounded-2xl bg-gradient-to-br from-bubble/20 to-almost flex items-center justify-center text-4xl">
                                    🎁
                                </div>
                            @endif
                        </a>

                        <div class="px-4 pb-4">
                            <div class="text-[10px] uppercase font-bold text-ink/40 tracking-wider">
                                {{ $p->category->name ?? '-' }}
                            </div>

                            <a href="{{ route('products.show', $p->slug) }}" class="block mt-1 font-bold text-ink group-hover:text-inlove transition truncate">
                                {{ $p->name }}
                            </a>

                            <div class="mt-2 flex items-center justify-between">
                                <div class="text-lg font-black text-inlove">
                                    Rp{{ number_format($p->price, 0, ',', '.') }}
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="w-10 h-10 bg-humble text-white rounded-xl flex items-center justify-center hover:opacity-90 transition shadow-soft">
                                        🛒
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
