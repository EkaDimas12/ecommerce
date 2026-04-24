<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Tsania Craft</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            800: '#3730a3', 900: '#312e81',
                        },
                        sidebar: { DEFAULT: '#0f172a', hover: '#1e293b', active: '#334155' }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-scrollbar::-webkit-scrollbar { width: 4px; }
        .sidebar-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .toast-animate { animation: slideIn 0.3s ease-out; }
        .nav-link { position: relative; transition: all 0.2s ease; }
        .nav-link::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 3px; height: 0; background: #818cf8; border-radius: 0 4px 4px 0; transition: height 0.2s ease; }
        .nav-link.active::before { height: 60%; }
    </style>
</head>

<body class="bg-slate-50">
    <div class="min-h-screen flex">
        {{-- ─── SIDEBAR ─── --}}
        <aside class="w-[260px] bg-sidebar fixed h-full overflow-y-auto sidebar-scrollbar flex flex-col z-30">
            {{-- Brand --}}
            <div class="px-5 py-5 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-brand-400 to-brand-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg shadow-brand-500/30">
                        🎨
                    </div>
                    <div>
                        <h1 class="font-bold text-[15px] text-white tracking-tight">Tsania Craft</h1>
                        <p class="text-[11px] text-slate-500 font-medium">Admin Panel</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-6">
                {{-- Menu Utama --}}
                <div>
                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.1em] mb-2 px-3">Overview</p>
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 {{ request()->routeIs('admin.dashboard') ? 'active bg-white/[0.08] text-white' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                        <span class="text-[13px] font-medium">Dashboard</span>
                    </a>
                </div>

                {{-- Katalog --}}
                <div>
                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.1em] mb-2 px-3">Katalog</p>
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 {{ request()->routeIs('admin.categories.*') ? 'active bg-white/[0.08] text-white' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/></svg>
                        <span class="text-[13px] font-medium">Kategori</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 {{ request()->routeIs('admin.products.*') ? 'active bg-white/[0.08] text-white' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        <span class="text-[13px] font-medium">Produk</span>
                    </a>
                </div>

                {{-- Penjualan --}}
                <div>
                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.1em] mb-2 px-3">Penjualan</p>
                    <a href="{{ route('admin.orders.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 {{ request()->routeIs('admin.orders.*') ? 'active bg-white/[0.08] text-white' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                        <span class="text-[13px] font-medium">Pesanan</span>
                        @php $pending = \App\Models\Order::where('payment_status', 'pending')->count(); @endphp
                        @if ($pending > 0)
                            <span class="ml-auto min-w-[20px] h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1.5">{{ $pending }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.transaction-logs.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 {{ request()->routeIs('admin.transaction-logs.*') ? 'active bg-white/[0.08] text-white' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                        <span class="text-[13px] font-medium">Log Transaksi</span>
                    </a>
                    <a href="{{ route('admin.coupons.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 {{ request()->routeIs('admin.coupons.*') ? 'active bg-white/[0.08] text-white' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                        <span class="text-[13px] font-medium">Kupon Diskon</span>
                    </a>
                </div>

                {{-- Konten --}}
                <div>
                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.1em] mb-2 px-3">Konten</p>
                    <a href="{{ route('admin.testimonials.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 {{ request()->routeIs('admin.testimonials.*') ? 'active bg-white/[0.08] text-white' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        <span class="text-[13px] font-medium">Testimoni</span>
                    </a>
                    <a href="{{ route('admin.messages.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 {{ request()->routeIs('admin.messages.*') ? 'active bg-white/[0.08] text-white' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        <span class="text-[13px] font-medium">Pesan Masuk</span>
                    </a>
                </div>

                {{-- Pengaturan --}}
                <div>
                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.1em] mb-2 px-3">Pengaturan</p>
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 {{ request()->routeIs('admin.users.*') ? 'active bg-white/[0.08] text-white' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        <span class="text-[13px] font-medium">Users</span>
                    </a>
                </div>
            </nav>

            {{-- Bottom --}}
            <div class="px-3 py-4 border-t border-white/5 space-y-1">
                <a href="{{ route('home') }}" target="_blank"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:bg-white/[0.04] hover:text-slate-300 transition-all">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    <span class="text-[13px] font-medium">Lihat Website</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl w-full text-left text-slate-500 hover:bg-red-500/10 hover:text-red-400 transition-all">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        <span class="text-[13px] font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ─── MAIN CONTENT ─── --}}
        <main class="flex-1 ml-[260px]">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-lg border-b border-slate-200/60">
                <div class="flex items-center justify-between px-8 py-4">
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 tracking-tight">@yield('title', 'Dashboard')</h1>
                        <p class="text-slate-400 text-[13px] mt-0.5">@yield('subtitle', '')</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <div class="text-[13px] font-semibold text-slate-700">{{ auth()->user()->name ?? 'Admin' }}</div>
                            <div class="text-[11px] text-slate-400">Administrator</div>
                        </div>
                        <div class="w-9 h-9 bg-gradient-to-br from-brand-400 to-brand-600 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-md shadow-brand-500/20">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="p-8">
                {{-- Toast messages --}}
                @if (session('success'))
                    <div class="toast-animate mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="toast-animate mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="toast-animate mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                            </div>
                            <span class="font-semibold text-sm">Terjadi kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside ml-11 space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    {{-- ─── CUSTOM CONFIRM MODAL ─── --}}
    <div id="confirmModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        {{-- Backdrop --}}
        <div id="confirmBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
        {{-- Dialog --}}
        <div id="confirmDialog" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[400px] overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
            {{-- Top accent --}}
            <div class="h-1 bg-gradient-to-r from-red-500 via-rose-500 to-pink-500"></div>
            <div class="p-6">
                {{-- Icon --}}
                <div class="mx-auto w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-4 ring-4 ring-red-100/60">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                {{-- Title --}}
                <h3 id="confirmTitle" class="text-lg font-bold text-slate-800 text-center mb-1">Konfirmasi Hapus</h3>
                {{-- Message --}}
                <p id="confirmMessage" class="text-sm text-slate-500 text-center leading-relaxed">Apakah Anda yakin? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            {{-- Actions --}}
            <div class="flex gap-3 px-6 pb-6">
                <button id="confirmCancel" type="button"
                    class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                    Batal
                </button>
                <button id="confirmOk" type="button"
                    class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white text-sm font-semibold rounded-xl transition-all shadow-sm shadow-red-500/25 focus:outline-none focus:ring-2 focus:ring-red-400">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        /**
         * Custom Confirm Modal
         * Usage: Add data-confirm="message" to any <form> tag.
         */
        (function() {
            const modal     = document.getElementById('confirmModal');
            const backdrop  = document.getElementById('confirmBackdrop');
            const dialog    = document.getElementById('confirmDialog');
            const titleEl   = document.getElementById('confirmTitle');
            const msgEl     = document.getElementById('confirmMessage');
            const btnCancel = document.getElementById('confirmCancel');
            const btnOk     = document.getElementById('confirmOk');
            let pendingForm = null;

            function openModal(title, message) {
                titleEl.textContent = title || 'Konfirmasi Hapus';
                msgEl.textContent   = message || 'Apakah Anda yakin? Tindakan ini tidak dapat dibatalkan.';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                requestAnimationFrame(() => {
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                    dialog.classList.remove('scale-95', 'opacity-0');
                    dialog.classList.add('scale-100', 'opacity-100');
                });
            }

            function closeModal() {
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
                dialog.classList.remove('scale-100', 'opacity-100');
                dialog.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    pendingForm = null;
                }, 300);
            }

            // Intercept all forms with data-confirm
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('form[data-confirm]');
                if (!form) return;
                if (form.dataset._confirmed === 'true') {
                    form.dataset._confirmed = '';
                    return; // allow submit
                }
                e.preventDefault();
                pendingForm = form;
                openModal(form.dataset.confirmTitle, form.dataset.confirm);
            });

            btnOk.addEventListener('click', function() {
                if (pendingForm) {
                    pendingForm.dataset._confirmed = 'true';
                    pendingForm.requestSubmit();
                }
                closeModal();
            });

            btnCancel.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        })();
    </script>
</body>

</html>
