@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- Sidebar --}}
        <aside class="w-full lg:w-1/4">
            <div class="bg-white border border-humble/10 rounded-2xl shadow-soft overflow-hidden">
                <div class="p-6 bg-gradient-to-br from-bubble/20 to-almost/30 border-b border-humble/10 text-center">
                    <div class="w-20 h-20 mx-auto rounded-full bg-humble text-white flex items-center justify-center text-3xl font-bold mb-3 shadow-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <h3 class="font-bold text-ink">{{ auth()->user()->name }}</h3>
                    <p class="text-xs text-ink/50">{{ auth()->user()->email }}</p>
                </div>
                
                <nav class="p-4 space-y-1">
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ Route::is('settings.index') ? 'bg-bubble/20 text-inlove font-bold' : 'text-ink/70 hover:bg-bubble/10' }}">
                        <span>👤</span> Profil & Keamanan
                    </a>
                    <a href="{{ route('orders.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ Route::is('orders.history') ? 'bg-bubble/20 text-inlove font-bold' : 'text-ink/70 hover:bg-bubble/10' }}">
                        <span>📦</span> Pesanan Saya
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ Route::is('wishlist.index') ? 'bg-bubble/20 text-inlove font-bold' : 'text-ink/70 hover:bg-bubble/10' }}">
                        <span>❤️</span> Wishlist
                    </a>
                    <div class="h-px bg-humble/10 my-2"></div>
                    <form method="POST" action="{{ route('logout') }}" data-confirm="Apakah Anda yakin ingin keluar?" data-confirm-title="Konfirmasi Keluar" data-confirm-btn="Ya, Keluar">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition text-left">
                            <span>🚪</span> Keluar
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="w-full lg:w-3/4">
            @yield('dashboard-content')
        </main>

    </div>
</div>
@endsection
