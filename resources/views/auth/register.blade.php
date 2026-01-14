@extends('layouts.app')
@section('title','Sign Up — Tsania Craft')

@section('content')

{{-- HERO --}}
<section class="page-hero">
  <div style="text-align:center;">
    <h1 class="h1">Sign Up</h1>
    <p class="p-muted" style="margin-top:10px; max-width:720px; margin-left:auto; margin-right:auto;">
      Daftar sekarang untuk mendapatkan pengalaman belanja lebih cepat dan promo khusus member.
      <b>Diskon 20%</b> untuk pesanan pertama ✨
    </p>
  </div>
</section>

<section class="section">
  <div style="max-width:520px; margin-left:auto; margin-right:auto; display:grid; gap:14px;">

    {{-- Card Form --}}
    <div class="card" style="padding:22px;">
      <div style="display:flex; justify-content:space-between; align-items:end; gap:12px; flex-wrap:wrap;">
        <div>
          <div class="h2">Buat Akun Baru</div>
          <div class="p-muted" style="margin-top:6px;">Isi data di bawah ini untuk mendaftar.</div>
        </div>
        <span class="badge badge-terracotta">Member</span>
      </div>

      <div class="divider" style="margin:14px 0;"></div>

      <form method="POST" action="{{ route('register.store') }}" style="display:grid; gap:12px;">
        @csrf

        <div class="field-wrap">
          <label class="label" for="fullname">Nama Lengkap</label>
          <input id="fullname" name="name" type="text" class="field"
                 value="{{ old('name') }}" placeholder="Nama kamu" required>
          @error('name') <div class="p-muted" style="color:#b00020;">{{ $message }}</div> @enderror
        </div>

        <div class="field-wrap">
          <label class="label" for="email">Email</label>
          <input id="email" name="email" type="email" class="field"
                 value="{{ old('email') }}" placeholder="email@example.com" required>
          @error('email') <div class="p-muted" style="color:#b00020;">{{ $message }}</div> @enderror
        </div>

        <div class="field-wrap">
          <label class="label" for="phone">No. WhatsApp</label>
          <input id="phone" name="phone" type="text" class="field"
                 value="{{ old('phone') }}" placeholder="+62xxxxxxxxxx" required>
          @error('phone') <div class="p-muted" style="color:#b00020;">{{ $message }}</div> @enderror
        </div>

        <div class="field-wrap">
          <label class="label" for="password">Password</label>
          <input id="password" name="password" type="password" class="field"
                 placeholder="••••••••" required>
          @error('password') <div class="p-muted" style="color:#b00020;">{{ $message }}</div> @enderror
        </div>

        <div class="field-wrap">
          <label class="label" for="password2">Konfirmasi Password</label>
          <input id="password2" name="password_confirmation" type="password" class="field"
                 placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-dark" style="width:100%; margin-top:6px;">
          Sign Up
        </button>

        <div class="p-muted" style="font-size:13px; text-align:center; margin-top:4px;">
          Dengan mendaftar, kamu menyetujui Syarat & Ketentuan dan Kebijakan Privasi.
        </div>
      </form>
    </div>

    {{-- Benefit --}}
    <div class="card-soft" style="padding:18px;">
      <div style="font-weight:900;">Keuntungan Member</div>
      <div class="divider" style="margin:12px 0;"></div>

      <div style="display:grid; gap:10px;">
        <div style="display:flex; gap:10px; align-items:flex-
