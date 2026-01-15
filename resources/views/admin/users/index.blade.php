@extends('admin.layout')

@section('title', 'Users')
@section('subtitle', 'Kelola pengguna')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div></div>
        <a href="{{ route('admin.users.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg font-medium">
            + Tambah User
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Telepon</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->phone ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if ($user->is_admin)
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">Admin</span>
                            @else
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-600">User</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="text-indigo-600 hover:underline">Edit</a>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($users->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
