@extends('admin.layout')

@section('title', $testimonial ? 'Edit Testimoni' : 'Tambah Testimoni')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.testimonials.index') }}" class="text-indigo-600 hover:underline">← Kembali ke Testimoni</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 max-w-xl">
        <form
            action="{{ $testimonial ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
            method="POST">
            @csrf
            @if ($testimonial)
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama *</label>
                <input type="text" name="name" value="{{ old('name', $testimonial?->name) }}"
                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Rating *</label>
                <select name="rating" class="w-full border border-slate-300 rounded-lg px-4 py-2.5" required>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}"
                            {{ old('rating', $testimonial?->rating) == $i ? 'selected' : '' }}>
                            {{ $i }} ⭐
                        </option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Pesan *</label>
                <textarea name="message" rows="4" class="w-full border border-slate-300 rounded-lg px-4 py-2.5" required>{{ old('message', $testimonial?->message) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $testimonial?->is_active ?? true) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 rounded border-slate-300">
                    <span class="ml-2 text-sm text-slate-700">Aktif (tampil di website)</span>
                </label>
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium">
                {{ $testimonial ? 'Simpan Perubahan' : 'Simpan Testimoni' }}
            </button>
        </form>
    </div>
@endsection
