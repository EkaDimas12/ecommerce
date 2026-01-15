@extends('layouts.app')
@section('title', 'Login — Tsania Craft')

@section('content')

    {{-- HERO --}}
    <section
        style="background: linear-gradient(135deg, #fff 0%, #FEF0F5 50%, #FCE8EF 100%); border-radius: 24px; padding: 48px 32px; text-align: center; position: relative; overflow: hidden; border: 1px solid rgba(74,32,42,0.08);">
        <div style="position: relative; z-index: 10;">
            <span
                style="display: inline-block; padding: 8px 16px; background: #FCD6E3; border: 1px solid #E4879E; border-radius: 999px; font-size: 12px; font-weight: 600; color: #4A202A; margin-bottom: 16px;">
                👋 Selamat Datang
            </span>
            <h1 style="font-size: 36px; font-weight: 800; color: #2b0f16; margin: 0;">Login</h1>
            <p
                style="margin-top: 16px; color: rgba(43,15,22,0.7); max-width: 500px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                Masuk untuk melanjutkan belanja, cek status pesanan, dan nikmati promo member.
            </p>
        </div>
    </section>

    <section style="margin-top: 40px;">
        <div style="max-width: 420px; margin: 0 auto;">

            {{-- Form Card --}}
            <div
                style="background: white; border: 1px solid rgba(74,32,42,0.08); border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(74,32,42,0.08);">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                    <div
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #E4879E, #83394A); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                        🔐
                    </div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 800; color: #2b0f16; margin: 0;">Masuk ke Akun</h2>
                        <p style="font-size: 14px; color: rgba(43,15,22,0.6); margin: 4px 0 0 0;">Gunakan email dan password
                            yang sudah terdaftar.</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div
                        style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-weight: 700; color: #DC2626; display: flex; align-items: center; gap: 8px;">
                            ❌ Login gagal:
                        </div>
                        <ul style="margin: 8px 0 0 24px; color: #DC2626; font-size: 14px;">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com"
                            required
                            style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Password</label>
                        <input type="password" name="password" placeholder="••••••••" required
                            style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <label style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; cursor: pointer;">
                        <input type="checkbox" name="remember" value="1" style="width: 18px; height: 18px;">
                        <span style="font-size: 14px; color: rgba(43,15,22,0.7);">Ingat saya</span>
                    </label>

                    <button type="submit"
                        style="width: 100%; padding: 14px 24px; background: #4A202A; color: white; font-weight: 700; font-size: 15px; border: none; border-radius: 999px; cursor: pointer; box-shadow: 0 4px 14px rgba(74,32,42,0.2);">
                        🚀 Login
                    </button>
                </form>

                <div style="text-align: center; margin-top: 16px;">
                    <a href="#" style="font-size: 14px; color: #83394A; text-decoration: none;">Lupa password?</a>
                </div>
            </div>

            {{-- Register CTA --}}
            <div
                style="background: white; border: 1px solid rgba(74,32,42,0.08); border-radius: 20px; padding: 28px; margin-top: 24px; text-align: center; box-shadow: 0 4px 20px rgba(74,32,42,0.08);">
                <div style="font-size: 32px; margin-bottom: 8px;">✨</div>
                <div style="font-weight: 700; color: #2b0f16; font-size: 16px;">Belum punya akun?</div>
                <p style="font-size: 14px; color: rgba(43,15,22,0.6); margin: 8px 0 0 0;">Daftar dan dapatkan diskon 20%
                    untuk pesanan pertama!</p>
                <a href="{{ route('register') }}"
                    style="display: inline-block; margin-top: 16px; padding: 12px 28px; background: #E4879E; color: white; font-weight: 700; font-size: 14px; border-radius: 999px; text-decoration: none; box-shadow: 0 4px 14px rgba(228,135,158,0.3);">
                    Buat Akun
                </a>
            </div>

        </div>
    </section>

@endsection
