@extends('admin.layout')
@section('title', isset($coupon) ? 'Edit Kupon' : 'Tambah Kupon')
@section('subtitle', isset($coupon) ? 'Edit data kupon diskon' : 'Buat kupon promosi baru')

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST">
            @csrf
            @if(isset($coupon)) @method('PUT') @endif

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Kupon <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" required class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm uppercase" placeholder="Contoh: PROMO2025">
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tipe Diskon <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" x-data x-model="$store.couponType.type" @change="$store.couponType.type = $event.target.value">
                            <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                            <option value="percent" {{ old('type', $coupon->type ?? '') == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nilai Diskon <span class="text-red-500">*</span></label>
                        <input type="number" name="value" value="{{ old('value', $coupon->value ?? '') }}" required min="0" step="0.01" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Contoh: 10000 atau 10">
                        @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Minimal Belanja <span class="text-red-500">*</span></label>
                        <input type="number" name="min_purchase" value="{{ old('min_purchase', $coupon->min_purchase ?? '0') }}" required min="0" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="0 untuk tanpa minimal">
                        @error('min_purchase') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div x-data="{ type: '{{ old('type', $coupon->type ?? 'fixed') }}' }" x-effect="type = $store.couponType ? $store.couponType.type : type" x-show="type === 'percent'">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Maksimal Diskon (Opsional)</label>
                        <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount ?? '') }}" min="0" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Kosongkan jika tidak ada batas">
                        @error('max_discount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Batas Penggunaan (Opsional)</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" min="1" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Contoh: 100">
                        <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika kuota tidak terbatas</p>
                        @error('usage_limit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kedaluwarsa Pada (Opsional)</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('expires_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                        <span class="text-sm font-semibold text-slate-700">Kupon Aktif</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 flex gap-3 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                    {{ isset($coupon) ? 'Simpan Perubahan' : 'Buat Kupon' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('couponType', {
                type: '{{ old('type', $coupon->type ?? 'fixed') }}'
            });
        });
    </script>
@endsection
