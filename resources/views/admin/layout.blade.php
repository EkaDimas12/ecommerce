<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Tsania Craft</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white fixed h-full overflow-y-auto">
            <!-- Header -->
            <div class="p-5 border-b border-slate-700">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-xl shadow-lg">
                        🎨
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-white">Tsania Craft</h1>
                        <p class="text-xs text-slate-400">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">📊</span>
                    <span class="font-medium">Dashboard</span>
                </a>

                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-3 px-3">Katalog</p>

                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">📁</span>
                    <span class="font-medium">Kategori</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">🛍️</span>
                    <span class="font-medium">Produk</span>
                </a>

                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-3 px-3">Penjualan</p>

                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">🛒</span>
                    <span class="font-medium">Pesanan</span>
                    @php $pending = \App\Models\Order::where('payment_status', 'pending')->count(); @endphp
                    @if ($pending > 0)
                        <span
                            class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pending }}</span>
                    @endif
                </a>

                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-3 px-3">Konten</p>

                <a href="{{ route('admin.testimonials.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all {{ request()->routeIs('admin.testimonials.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">⭐</span>
                    <span class="font-medium">Testimoni</span>
                </a>

                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-3 px-3">Pengaturan</p>

                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">👥</span>
                    <span class="font-medium">Users</span>
                </a>

                <div class="border-t border-slate-700 my-6"></div>

                <a href="{{ route('home') }}" target="_blank"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <span class="text-lg">🌐</span>
                    <span class="font-medium">Lihat Website</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg w-full text-left text-slate-400 hover:bg-red-600 hover:text-white transition-all">
                        <span class="text-lg">🚪</span>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <!-- Top Bar -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">@yield('title', 'Dashboard')</h1>
                    <p class="text-slate-500 text-sm">@yield('subtitle', '')</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-600 font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
                    <span class="text-xl">❌</span>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xl">⚠️</span>
                        <span class="font-semibold">Terjadi kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside ml-8 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>
