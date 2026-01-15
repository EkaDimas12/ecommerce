@extends('admin.layout')

@section('title', $category ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline">← Kembali ke Kategori</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            {{ $category ? 'Edit Kategori' : 'Tambah Kategori' }}
        </h1>

        <form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
            method="POST">
            @csrf
            @if ($category)
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name', $category?->name) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug (opsional)</label>
                <input type="text" name="slug" value="{{ old('slug', $category?->slug) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="kosongkan untuk generate otomatis">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                {{ $category ? 'Simpan Perubahan' : 'Simpan Kategori' }}
            </button>
        </form>
    </div>
@endsection
