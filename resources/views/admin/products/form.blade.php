@extends('admin.layout')

@section('title', $product ? 'Edit Produk' : 'Tambah Produk')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">← Kembali ke Produk</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-2xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            {{ $product ? 'Edit Produk' : 'Tambah Produk' }}
        </h1>

        <form action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if ($product)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select name="category_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product?->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk *</label>
                    <input type="text" name="name" value="{{ old('name', $product?->name) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug (opsional)</label>
                    <input type="text" name="slug" value="{{ old('slug', $product?->slug) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="kosongkan untuk generate otomatis">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price', $product?->price) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        min="0" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                <textarea name="description" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>{{ old('description', $product?->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Utama</label>
                @if ($product?->main_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="Current image"
                            class="w-32 h-32 object-cover rounded-lg border">
                        <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                    </div>
                @endif
                <input type="file" name="main_image" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG, GIF, WebP. Max 2MB.</p>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_best_seller" value="1"
                        {{ old('is_best_seller', $product?->is_best_seller) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 rounded border-gray-300">
                    <span class="ml-2 text-sm text-gray-700">Tandai sebagai Best Seller</span>
                </label>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                {{ $product ? 'Simpan Perubahan' : 'Simpan Produk' }}
            </button>
        </form>
    </div>
@endsection
