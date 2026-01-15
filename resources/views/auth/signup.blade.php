@extends('layouts.app')
@section('title', 'Sign Up — Tsania Craft')

@section('content')

    {{-- HERO --}}
    <section
        style="background: linear-gradient(135deg, #fff 0%, #FEF0F5 50%, #FCE8EF 100%); border-radius: 24px; padding: 48px 32px; text-align: center; position: relative; overflow: hidden; border: 1px solid rgba(74,32,42,0.08);">
        <div style="position: relative; z-index: 10;">
            <span
                style="display: inline-block; padding: 8px 16px; background: #FCD6E3; border: 1px solid #E4879E; border-radius: 999px; font-size: 12px; font-weight: 600; color: #4A202A; margin-bottom: 16px;">
                ✨ Bergabung Sekarang
            </span>
            <h1 style="font-size: 36px; font-weight: 800; color: #2b0f16; margin: 0;">Sign Up</h1>
            <p
                style="margin-top: 16px; color: rgba(43,15,22,0.7); max-width: 500px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                Daftar sekarang untuk mendapatkan pengalaman belanja lebih cepat dan promo khusus member.
                <strong style="color: #83394A;">Diskon 20%</strong> untuk pesanan pertama!
            </p>
        </div>
    </section>

    <section style="margin-top: 40px;">
        <div style="max-width: 480px; margin: 0 auto;">

            {{-- Form Card --}}
            <div
                style="background: white; border: 1px solid rgba(74,32,42,0.08); border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(74,32,42,0.08);">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                    <div
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #E4879E, #83394A); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                        👤
                    </div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 800; color: #2b0f16; margin: 0;">Buat Akun Baru</h2>
                        <p style="font-size: 14px; color: rgba(43,15,22,0.6); margin: 4px 0 0 0;">Isi data di bawah ini
                            untuk mendaftar.</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div
                        style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-weight: 700; color: #DC2626; display: flex; align-items: center; gap: 8px;">
                            ❌ Periksa input:
                        </div>
                        <ul style="margin: 8px 0 0 24px; color: #DC2626; font-size: 14px;">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Nama
                            Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama kamu" required
                            style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com"
                            required
                            style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">No.
                            WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+62xxxxxxxxxx"
                            required
                            style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Password</label>
                        <input type="password" name="password" placeholder="Min. 8 karakter" required
                            style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label
                            style="display: block; font-size: 14px; font-weight: 600; color: rgba(43,15,22,0.7); margin-bottom: 8px;">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" required
                            style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(74,32,42,0.12); border-radius: 14px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <button type="submit"
                        style="width: 100%; padding: 14px 24px; background: #4A202A; color: white; font-weight: 700; font-size: 15px; border: none; border-radius: 999px; cursor: pointer; box-shadow: 0 4px 14px rgba(74,32,42,0.2);">
                        ✨ Daftar Sekarang
                    </button>

                    <p style="text-align: center; font-size: 12px; color: rgba(43,15,22,0.5); margin-top: 16px;">
                        Dengan mendaftar, kamu menyetujui <a href="#" style="color: #83394A;">Syarat & Ketentuan</a>
                        dan <a href="#" style="color: #83394A;">Kebijakan Privasi</a>.
                    </p>
                </form>
            </div>

            {{-- Benefits --}}
            <div
                style="background: white; border: 1px solid rgba(74,32,42,0.08); border-radius: 20px; padding: 24px; margin-top: 24px; box-shadow: 0 4px 20px rgba(74,32,42,0.08);">
                <div
                    style="font-weight: 700; color: #2b0f16; display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                    <span
                        style="width: 32px; height: 32px; background: #FCD6E3; border-radius: 8px; display: flex; align-items: center; justify-content: center;">🎁</span>
                    Keuntungan Member
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div
                        style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; background: #FEF0F5; border-radius: 12px;">
                        <span
                            style="width: 32px; height: 32px; background: rgba(228,135,158,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #E4879E; flex-shrink: 0;">🏷️</span>
                        <div>
                            <div style="font-weight: 600; color: #2b0f16; font-size: 14px;">Promo Eksklusif</div>
                            <div style="font-size: 12px; color: rgba(43,15,22,0.6);">Diskon & promo khusus untuk member.
                            </div>
                        </div>
                    </div>
                    <div
                        style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; background: #FEF0F5; border-radius: 12px;">
                        <span
                            style="width: 32px; height: 32px; background: rgba(228,135,158,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #E4879E; flex-shrink: 0;">⚡</span>
                        <div>
                            <div style="font-weight: 600; color: #2b0f16; font-size: 14px;">Checkout Cepat</div>
                            <div style="font-size: 12px; color: rgba(43,15,22,0.6);">Checkout lebih cepat, data tersimpan.
                            </div>
                        </div>
                    </div>
                    <div
                        style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; background: #FEF0F5; border-radius: 12px;">
                        <span
                            style="width: 32px; height: 32px; background: rgba(228,135,158,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #E4879E; flex-shrink: 0;">📦</span>
                        <div>
                            <div style="font-weight: 600; color: #2b0f16; font-size: 14px;">Lacak Pesanan</div>
                            <div style="font-size: 12px; color: rgba(43,15,22,0.6);">Riwayat pesanan dan status pengiriman.
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('products.index') }}"
                    style="display: block; width: 100%; text-align: center; padding: 12px 20px; background: white; border: 1.5px solid rgba(74,32,42,0.15); color: #2b0f16; font-weight: 700; font-size: 14px; border-radius: 999px; margin-top: 16px; text-decoration: none; box-sizing: border-box;">
                    🛍️ Lihat Produk
                </a>
            </div>

            {{-- Login Link --}}
            <div
                style="background: white; border: 1px solid rgba(74,32,42,0.08); border-radius: 20px; padding: 20px; margin-top: 24px; text-align: center; box-shadow: 0 4px 20px rgba(74,32,42,0.08);">
                <span style="color: rgba(43,15,22,0.6);">Sudah punya akun?</span>
                <a href="{{ route('login') }}"
                    style="margin-left: 8px; font-weight: 700; color: #83394A; text-decoration: none;">Login →</a>
            </div>

        </div>
    </section>

@endsection
