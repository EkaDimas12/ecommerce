@extends('admin.layout')

@section('title', 'Produk')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Tambah Produk
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Gambar</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Nama</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Kategori</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Harga</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Best Seller</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4">
                            @if ($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}"
                                    class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div
                                    class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                    📷
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $product->category?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if ($product->is_best_seller)
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">⭐ Best Seller</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada produk. <a href="{{ route('admin.products.create') }}"
                                class="text-blue-600 hover:underline">Tambah sekarang →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
