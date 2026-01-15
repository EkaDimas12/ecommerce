@extends('admin.layout')

@section('title', 'Kategori')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kategori</h1>
        <a href="{{ route('admin.categories.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Nama</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Slug</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $category->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                                class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                onsubmit="return confirm('Yakin hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Belum ada kategori. <a href="{{ route('admin.categories.create') }}"
                                class="text-blue-600 hover:underline">Tambah sekarang →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
