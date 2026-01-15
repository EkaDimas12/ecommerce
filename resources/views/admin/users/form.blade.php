@extends('admin.layout')

@section('title', $user ? 'Edit User' : 'Tambah User')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:underline">← Kembali ke Users</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 max-w-xl">
        <form action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
            @csrf
            @if ($user)
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama *</label>
                <input type="text" name="name" value="{{ old('name', $user?->name) }}"
                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user?->email) }}"
                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user?->phone) }}"
                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Password {{ $user ? '(kosongkan jika tidak ingin mengubah)' : '*' }}
                </label>
                <input type="password" name="password" class="w-full border border-slate-300 rounded-lg px-4 py-2.5"
                    {{ $user ? '' : 'required' }}>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_admin" value="1"
                        {{ old('is_admin', $user?->is_admin) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 rounded border-slate-300">
                    <span class="ml-2 text-sm text-slate-700">Admin (dapat mengakses panel admin)</span>
                </label>
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium">
                {{ $user ? 'Simpan Perubahan' : 'Simpan User' }}
            </button>
        </form>
    </div>
@endsection
