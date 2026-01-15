@extends('layouts.app')
@section('title', 'Pengaturan — Tsania Craft')

@section('content')

    {{-- HERO --}}
    <section
        style="background: linear-gradient(135deg, #fff 0%, #FEF0F5 50%, #FCE8EF 100%); border-radius: 24px; padding: 48px 32px; text-align: center; border: 1px solid rgba(74,32,42,0.08);">
        <span
            style="display: inline-block; padding: 8px 16px; background: #FCD6E3; border: 1px solid #E4879E; border-radius: 999px; font-size: 12px; font-weight: 600; color: #4A202A; margin-bottom: 16px;">
            ⚙️ Akun Saya
        </span>
        <h1 style="font-size: 36px; font-weight: 800; color: #2b0f16; margin: 0;">Pengaturan</h1>
        <p style="margin-top: 16px; color: rgba(43,15,22,0.7); max-width: 500px; margin-left: auto; margin-right: auto;">
            Kelola informasi profil dan keamanan akun kamu.
        </p>
    </section>

    <section style="margin-top: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">

        {{-- PROFILE FORM --}}
        <div
            style="background: white; border: 1px solid rgba(74,32,42,0.08); border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(74,32,42,0.08); margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                <div
                    style="width: 56px; height: 56px; background: linear-gradient(135deg, #E4879E, #83394A); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; font-weight: 700;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 style="font-size: 20px; font-weight: 800; color: #2b0f16; margin: 0;">Informasi Profil</h2>
                    <p style="font-size: 14px; color: rgba(43,15,22,0.6); margin: 4px 0 0 0;">Update nama, email, dan nomor
                        telepon</p>
                </div>
            </div>

            @if (session('success'))
                <div
                    style="background: #D1FAE5; border: 1px solid #34D399; border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span>✅</span>
                    <span style="font-weight: 600; color: #065F46;">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div
                    style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                    <div style="font-weight: 700; color: #DC2626; display: flex; align-items: center; gap: 8px;">
                        ❌ Terjadi kesalahan:
                    </div>
                    <ul style="margin: 8px 0 0 24px; color: #DC2626; font-size: 14px;">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.profile') }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 16px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Nama
                        Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">No.
                        WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                        style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                </div>

                <button type="submit"
                    style="width: 100%; padding: 14px 24px; background: #4A202A; color: white; font-weight: 700; font-size: 15px; border: none; border-radius: 999px; cursor: pointer; box-shadow: 0 4px 14px rgba(74,32,42,0.2);">
                    💾 Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- PASSWORD FORM --}}
        <div
            style="background: white; border: 1px solid rgba(74,32,42,0.08); border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(74,32,42,0.08);">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                <div
                    style="width: 48px; height: 48px; background: #FEF0F5; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    🔐
                </div>
                <div>
                    <h2 style="font-size: 18px; font-weight: 800; color: #2b0f16; margin: 0;">Ubah Password</h2>
                    <p style="font-size: 14px; color: rgba(43,15,22,0.6); margin: 4px 0 0 0;">Pastikan menggunakan password
                        yang kuat</p>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.password') }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 16px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Password
                        Saat Ini</label>
                    <input type="password" name="current_password" placeholder="Masukkan password saat ini" required
                        style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Password
                        Baru</label>
                    <input type="password" name="password" placeholder="Min. 6 karakter" required
                        style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Konfirmasi
                        Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" required
                        style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                </div>

                <button type="submit"
                    style="width: 100%; padding: 14px 24px; background: #E4879E; color: white; font-weight: 700; font-size: 15px; border: none; border-radius: 999px; cursor: pointer; box-shadow: 0 4px 14px rgba(228,135,158,0.3);">
                    🔒 Ubah Password
                </button>
            </form>
        </div>

        {{-- Account Info --}}
        <div
            style="background: #FEF0F5; border: 1px solid rgba(74,32,42,0.08); border-radius: 16px; padding: 20px; margin-top: 24px; text-align: center;">
            <div style="font-size: 13px; color: rgba(43,15,22,0.6);">
                Akun dibuat pada <strong>{{ $user->created_at->format('d M Y') }}</strong>
            </div>
        </div>

    </section>

@endsection
