@extends('profile.layout')

@section('title', 'Pengaturan Akun — Tsania Craft')

@section('dashboard-content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-6">
            <h1 class="text-2xl font-black text-ink">Pengaturan Akun</h1>
            <p class="text-sm text-ink/60 mt-1">Kelola informasi profil dan keamanan akun kamu.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- PROFILE FORM --}}
            <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-bubble/20 rounded-xl flex items-center justify-center text-xl">👤</div>
                    <div>
                        <h2 class="font-bold text-ink text-lg">Informasi Profil</h2>
                        <p class="text-xs text-ink/50">Update nama, email, dan telepon</p>
                    </div>
                </div>

                @if (session('success') && !session('toast'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium flex items-center gap-2">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('settings.profile') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-ink/70 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                            class="field w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-ink/70 mb-1.5 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                            class="field w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-ink/70 mb-1.5 uppercase tracking-wider">No. WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                            class="field w-full">
                    </div>

                    <button type="submit" class="btn-primary w-full py-3">
                        💾 Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- PASSWORD FORM --}}
            <div class="bg-white border border-humble/10 rounded-2xl shadow-soft p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-bubble/20 rounded-xl flex items-center justify-center text-xl">🔐</div>
                    <div>
                        <h2 class="font-bold text-ink text-lg">Ubah Password</h2>
                        <p class="text-xs text-ink/50">Pastikan menggunakan password yang kuat</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-ink/70 mb-1.5 uppercase tracking-wider">Password Saat Ini</label>
                        <input type="password" name="current_password" placeholder="••••••••" required
                            class="field w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-ink/70 mb-1.5 uppercase tracking-wider">Password Baru</label>
                        <input type="password" name="password" placeholder="Min. 6 karakter" required
                            class="field w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-ink/70 mb-1.5 uppercase tracking-wider">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required
                            class="field w-full">
                    </div>

                    <button type="submit" class="btn-outline w-full py-3 border-bubble text-inlove hover:bg-bubble/10">
                        🔒 Ubah Password
                    </button>
                </form>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="bg-bubble/5 border border-humble/10 rounded-2xl p-6 text-center">
            <p class="text-xs text-ink/50">
                Akun dibuat pada <strong class="text-ink">{{ auth()->user()->created_at->format('d M Y') }}</strong>
            </p>
        </div>
    </div>
@endsection
