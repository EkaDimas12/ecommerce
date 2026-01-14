@extends('layouts.app')
@section('title','Login — Tsania Craft')

@section('content')

<section class="page-hero">
  <div style="text-align:center;">
    <h1 class="h1">Login</h1>
    <p class="p-muted" style="margin-top:10px; max-width:720px; margin-left:auto; margin-right:auto;">
      Masuk untuk melanjutkan belanja, cek status pesanan, dan nikmati promo member.
    </p>
  </div>
</section>

<section class="section">
  <div style="max-width:520px; margin-left:auto; margin-right:auto; display:grid; gap:14px;">

    <div class="card" style="padding:22px;">
      <div style="display:flex; justify-content:space-between; align-items:end; gap:12px; flex-wrap:wrap;">
        <div>
          <div class="h2">Masuk ke Akun</div>
          <div class="p-muted" style="margin-top:6px;">Gunakan email dan password yang sudah terdaftar.</div>
        </div>
        <span class="badge badge-terracotta">Member</span>
      </div>

      <div class="divider" style="margin:14px 0;"></div>

      <form method="POST" action="{{ route('login.store') }}" style="display:grid; gap:12px;">
        @csrf

        <div class="field-wrap">
          <label class="label" for="email">Email</label>
          <input id="email" name="email" type="email" class="field"
                 value="{{ old('email') }}" placeholder="email@example.com" required>
          @error('email') <div class="p-muted" style="color:#b00020;">{{ $message }}</div> @enderror
        </div>

        <div class="field-wrap">
          <label class="label" for="password">Password</label>
          <input id="password" name="password" type="password" class="field"
                 placeholder="••••••••" required>
          @error('password') <div class="p-muted" style="color:#b00020;">{{ $message }}</div> @enderror
        </div>

        <label style="display:flex; gap:10px; align-items:center;">
          <input type="checkbox" name="remember" value="1">
          <span class="p-muted">Ingat saya</span>
        </label>

        <button type="submit" class="btn btn-dark" style="width:100%; margin-top:6px;">
          Login
        </button>
      </form>
    </div>

    <div class="card-soft" style="padding:18px; text-align:center;">
      <div class="p-muted">Belum punya akun?</div>
      <div style="margin-top:10px;">
        <a href="{{ route('register') }}" class="btn btn-outline" style="width:100%;">Buat Akun</a>
      </div>
    </div>

  </div>
</section>

@endsection
