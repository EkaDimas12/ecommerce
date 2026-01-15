<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tsania Craft')</title>
    <meta name="description" content="@yield('meta_description', 'E-commerce kerajinan Tsania Craft: modern, minimal, responsif.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="toast()" x-init="@if (session('toast')) fire('{{ session('toast.message') }}','{{ session('toast.type') }}') @endif">

    {{-- TOPBAR --}}
    <div class="bg-humble text-white text-xs">
        <div class="container-center py-2.5">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="font-medium">📞 +62 812-3456-7890</span>
                    <span class="opacity-40">|</span>
                    <span>Dapatkan <strong>diskon 20%</strong> untuk pesanan pertama!</span>
                    <a href="{{ route('signup') }}"
                        class="underline underline-offset-2 font-semibold hover:opacity-80 transition">
                        Daftar →
                    </a>
                </div>
                <div class="flex items-center gap-2">
                    <a href="#"
                        class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs hover:bg-white/20 transition">f</a>
                    <a href="#"
                        class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs hover:bg-white/20 transition">ig</a>
                    <a href="#"
                        class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs hover:bg-white/20 transition">p</a>
                </div>
            </div>
        </div>
    </div>

    {{-- NAVBAR --}}
    <header class="bg-white/95 backdrop-blur-sm border-b border-humble/5 sticky top-0 z-40">
        <div class="container-center py-3">
            <div class="flex items-center justify-between gap-4">
                {{-- Brand --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 no-underline group">
                    <span
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-bubble to-inlove flex items-center justify-center text-white text-sm font-bold shadow-soft">T</span>
                    <span class="text-lg font-extrabold text-ink">Tsania<span class="text-inlove">Craft</span></span>
                </a>

                {{-- Navigation (desktop) --}}
                <nav class="hidden md:flex items-center gap-1" aria-label="Main navigation">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Home</a>
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                        href="{{ route('products.index') }}">Shop</a>
                    <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}"
                        href="{{ route('profile') }}">About</a>
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                        href="{{ route('contact') }}">Contact</a>
                    <a class="nav-link {{ request()->routeIs('help') ? 'active' : '' }}"
                        href="{{ route('help') }}">Help</a>
                </nav>

                {{-- Right side --}}
                <div class="flex items-center gap-2" x-data="{ open: false, profileOpen: false }">
                    <a class="w-10 h-10 rounded-full bg-pinkbg flex items-center justify-center hover:bg-almost transition"
                        href="{{ route('products.index') }}" aria-label="Search">🔍</a>

                    <a class="hidden md:flex items-center gap-2 px-4 py-2.5 rounded-full bg-humble text-white font-semibold text-sm shadow-soft hover:opacity-95 transition"
                        href="{{ route('cart.index') }}">
                        🛒 <span>Keranjang</span>
                    </a>

                    {{-- User Profile / Auth --}}
                    @auth
                        <div class="relative" x-data="{ profileOpen: false }">
                            <button @click="profileOpen = !profileOpen"
                                class="hidden md:flex items-center gap-2 px-3 py-2 rounded-full bg-pinkbg hover:bg-almost transition">
                                <span
                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-bubble to-inlove flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span
                                    class="text-sm font-semibold text-ink max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                                <span class="text-xs text-ink/50">▼</span>
                            </button>

                            {{-- Profile Dropdown --}}
                            <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                class="absolute right-0 top-12 w-56 bg-white border border-humble/10 rounded-2xl shadow-hover p-2 z-50">

                                {{-- User Info --}}
                                <div class="px-3 py-3 border-b border-humble/10 mb-2">
                                    <div class="font-bold text-ink text-sm">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-ink/60 truncate">{{ auth()->user()->email }}</div>
                                </div>

                                <a href="{{ route('orders.history') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-pinkbg transition text-sm font-medium text-ink">
                                    📦 Pesanan Saya
                                </a>
                                <a href="{{ route('settings.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-pinkbg transition text-sm font-medium text-ink">
                                    ⚙️ Pengaturan
                                </a>

                                <div class="h-px bg-humble/10 my-2"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 transition text-sm font-medium text-red-600">
                                        🚪 Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="hidden md:flex items-center gap-2 px-4 py-2.5 rounded-full border border-humble/15 text-ink font-semibold text-sm hover:bg-pinkbg transition">
                            Login
                        </a>
                        <a href="{{ route('signup') }}"
                            class="hidden md:flex items-center gap-2 px-4 py-2.5 rounded-full bg-bubble text-white font-semibold text-sm shadow-soft hover:opacity-95 transition">
                            Daftar
                        </a>
                    @endauth

                    {{-- Mobile menu button --}}
                    <button class="md:hidden w-10 h-10 rounded-full bg-pinkbg flex items-center justify-center"
                        @click="open=!open" aria-label="Menu">☰</button>

                    {{-- Mobile dropdown --}}
                    <div x-show="open" x-cloak @click.outside="open=false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-4 top-16 w-64 bg-white border border-humble/10 rounded-2xl shadow-hover p-3 md:hidden">

                        @auth
                            <div class="flex items-center gap-3 px-3 py-3 border-b border-humble/10 mb-2">
                                <span
                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-bubble to-inlove flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <div>
                                    <div class="font-bold text-ink text-sm">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-ink/60">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        @endauth

                        <a class="block px-4 py-3 rounded-xl hover:bg-almost transition font-medium"
                            href="{{ route('home') }}">🏠 Home</a>
                        <a class="block px-4 py-3 rounded-xl hover:bg-almost transition font-medium"
                            href="{{ route('products.index') }}">🛍️ Shop</a>
                        <a class="block px-4 py-3 rounded-xl hover:bg-almost transition font-medium"
                            href="{{ route('profile') }}">ℹ️ About</a>
                        <a class="block px-4 py-3 rounded-xl hover:bg-almost transition font-medium"
                            href="{{ route('contact') }}">📞 Contact</a>
                        <a class="block px-4 py-3 rounded-xl hover:bg-almost transition font-medium"
                            href="{{ route('help') }}">❓ Help</a>

                        <div class="h-px bg-humble/10 my-2"></div>

                        <a class="block px-4 py-3 rounded-full bg-humble text-white font-bold text-center"
                            href="{{ route('cart.index') }}">🛒 Keranjang</a>

                        @auth
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-3 rounded-xl text-red-600 font-medium hover:bg-red-50 transition text-center">
                                    🚪 Logout
                                </button>
                            </form>
                        @else
                            <div class="mt-2 flex gap-2">
                                <a href="{{ route('login') }}"
                                    class="flex-1 px-4 py-3 rounded-xl border border-humble/15 text-ink font-medium text-center hover:bg-pinkbg transition">
                                    Login
                                </a>
                                <a href="{{ route('signup') }}"
                                    class="flex-1 px-4 py-3 rounded-xl bg-bubble text-white font-medium text-center">
                                    Daftar
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="container-center mt-4">
            <div class="card-static px-5 py-4 flex items-center gap-3">
                <span class="text-xl">✅</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- MAIN --}}
    <main class="py-8">
        <div class="container-center">
            @yield('content')
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="mt-12 pb-8">
        <div class="container-center">
            <div class="card-static p-6 md:p-8">
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-bubble to-inlove flex items-center justify-center text-white text-sm font-bold">T</span>
                            <span class="text-lg font-extrabold">Tsania<span class="text-inlove">Craft</span></span>
                        </div>
                        <p class="mt-3 text-sm text-ink/60 max-w-xs">
                            Kerajinan handmade modern untuk momen spesialmu. Aksesoris, dekorasi, souvenir & custom
                            order.
                        </p>
                    </div>
                    <div class="flex gap-8">
                        <div>
                            <div class="font-bold text-sm mb-3">Quick Links</div>
                            <div class="flex flex-col gap-2 text-sm text-ink/70">
                                <a href="{{ route('products.index') }}" class="hover:text-inlove transition">Shop</a>
                                <a href="{{ route('contact') }}" class="hover:text-inlove transition">Contact</a>
                                <a href="{{ route('help') }}" class="hover:text-inlove transition">Help</a>
                            </div>
                        </div>
                        <div>
                            <div class="font-bold text-sm mb-3">Contact</div>
                            <div class="flex flex-col gap-2 text-sm text-ink/70">
                                <span>📞 +62 812-3456-7890</span>
                                <span>📧 hello@tsaniacraft.id</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-humble/10 text-center text-xs text-ink/50">
                    © {{ date('Y') }} Tsania Craft. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    {{-- Floating WhatsApp --}}
    @php $wa = env('WHATSAPP_NUMBER','6281234567890'); @endphp
    <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Tsania Craft, saya mau tanya produk.') }}"
        class="fixed bottom-5 right-5 z-50 btn-secondary flex items-center gap-2 shadow-hover"
        aria-label="Chat WhatsApp">
        💬 <span class="hidden sm:inline">WhatsApp</span>
    </a>

    {{-- Scroll to Top Button --}}
    <button id="scrollTop" aria-label="Scroll to top" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed bottom-20 right-5 z-50 w-11 h-11 rounded-full bg-white border border-humble/10 shadow-soft flex items-center justify-center hover:bg-almost transition">
        ↑
    </button>

    {{-- Toast --}}
    <div x-show="show" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-8"
        class="fixed top-20 right-5 z-50 px-5 py-4 rounded-2xl text-white shadow-hover max-w-sm flex items-center gap-3"
        :class="type === 'success' ? 'bg-humble' : type === 'error' ? 'bg-red-500' : 'bg-bubble'">
        <span x-text="type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'"></span>
        <span x-text="message" class="font-medium"></span>
    </div>

    {{-- Scroll to Top Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollBtn = document.getElementById('scrollTop');
            if (scrollBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        scrollBtn.classList.add('visible');
                    } else {
                        scrollBtn.classList.remove('visible');
                    }
                });
            }
        });
    </script>

</body>

</html>
