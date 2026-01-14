<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Tsania Craft')</title>
  <meta name="description" content="@yield('meta_description','E-commerce kerajinan Tsania Craft: modern, minimal, responsif.')">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}</style>

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body x-data="toast()"
      x-init="@if(session('toast')) fire('{{ session('toast.message') }}','{{ session('toast.type') }}') @endif">

  {{-- TOPBAR --}}
  <div class="nav-topbar">
    <div class="container-center" style="padding-top:10px; padding-bottom:10px;">
      <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
          <span>Call Us : +62-812-3456-789</span>
          <span style="opacity:.65;">|</span>
          <span>Sign up and GET 20% OFF for your first order.</span>
         <a href="{{ route('signup') }}" style="text-decoration:underline; text-underline-offset:3px;">
  Sign up now
</a>


        <div style="display:flex; align-items:center; gap:10px;">
          <a href="#" class="nav-icon" style="border-color:rgba(255,255,255,.22); background:transparent; color:#fff;">f</a>
          <a href="#" class="nav-icon" style="border-color:rgba(255,255,255,.22); background:transparent; color:#fff;">ig</a>
          <a href="#" class="nav-icon" style="border-color:rgba(255,255,255,.22); background:transparent; color:#fff;">p</a>
        </div>
      </div>
    </div>
  </div>

  {{-- NAVBAR --}}
  <header class="nav-main sticky top-0 z-40">
    <div class="container-center" style="padding-top:12px; padding-bottom:12px;">
      <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <a href="{{ route('home') }}" class="brand">
          <span class="brand-dot"></span>
          <span>Tsania <span style="color:var(--inlove)">Craft</span></span>
        </a>

        <nav class="nav-center hide-mobile" aria-label="Main navigation">
          <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
          <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Shop</a>
          <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">About</a>
          <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
          <a class="nav-link {{ request()->routeIs('help') ? 'active' : '' }}" href="{{ route('help') }}">Help</a>
        </nav>

        <div class="nav-right" x-data="{open:false}" style="position:relative;">
          <a class="nav-icon" href="{{ route('products.index') }}" aria-label="Search">🔎</a>

          <a class="cart-pill hide-mobile" href="{{ route('cart.index') }}" aria-label="Keranjang">
            🛒 Keranjang
          </a>

          <button class="nav-icon hide-desktop" @click="open=!open" aria-label="Menu">☰</button>

          <div x-show="open" x-cloak @click.outside="open=false"
               class="card fade-in hide-desktop"
               style="position:absolute; right:0; top:52px; width:280px; padding:10px;">
            <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="{{ route('home') }}">Home</a>
            <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="{{ route('products.index') }}">Shop</a>
            <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="{{ route('profile') }}">About</a>
            <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="{{ route('contact') }}">Contact</a>
            <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="{{ route('help') }}">Help</a>
            <a class="btn btn-dark w-full" style="margin-top:10px;" href="{{ route('cart.index') }}">Keranjang</a>
          </div>
        </div>
      </div>
    </div>
  </header>


  @if (session('success'))
  <div class="card-soft" style="padding:12px; margin:12px auto; max-width:980px;">
    {{ session('success') }}
  </div>
@endif

  {{-- MAIN --}}
  <main style="padding-top:28px; padding-bottom:40px;">
    <div class="container-center">
      @yield('content')
    </div>
  </main>

  {{-- FOOTER --}}
  <footer class="footer">
    <div class="container-center">
      <div class="card" style="padding:18px;">
        <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap;">
          <div style="font-weight:800;">Tsania <span style="color:var(--inlove)">Craft</span></div>
          <div style="color:var(--muted); font-size:14px;">
            Handmade modern — Aksesoris • Dekorasi • Souvenir • Custom
          </div>
        </div>
      </div>
    </div>
  </footer>

  {{-- Floating WhatsApp --}}
  @php $wa = env('WHATSAPP_NUMBER','6281234567890'); @endphp
  <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Tsania Craft, saya mau tanya produk.') }}"
     class="wa-float btn btn-primary"
     aria-label="Chat WhatsApp">
    WhatsApp
  </a>

  {{-- Toast --}}
  <div x-show="show" x-cloak class="toast"
       :style="type==='success' ? 'background:var(--humble)' : 'background:var(--bubble)'">
    <span x-text="message"></span>
  </div>

</body>
</html>
