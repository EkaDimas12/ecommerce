@extends('admin.layout')
@section('title', 'Pesan Masuk — Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Pesan Masuk</h1>
    <p class="text-sm text-gray-500">Daftar pertanyaan dan masukan dari pelanggan.</p>
</div>

<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600 border-b">
                <tr>
                    <th class="p-3 font-semibold">Tanggal</th>
                    <th class="p-3 font-semibold">Pengirim</th>
                    <th class="p-3 font-semibold">Kontak</th>
                    <th class="p-3 font-semibold">Pesan</th>
                    <th class="p-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($messages as $msg)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 align-top whitespace-nowrap">{{ $msg->created_at->format('d M Y H:i') }}</td>
                    <td class="p-3 align-top font-medium">{{ $msg->name }}</td>
                    <td class="p-3 align-top">
                        <div class="text-gray-600">{{ $msg->email ?? '-' }}</div>
                        <div class="text-gray-500">{{ $msg->phone ?? '-' }}</div>
                    </td>
                    <td class="p-3 align-top">
                        <div class="max-h-24 overflow-y-auto pr-2" style="scrollbar-width: thin;">
                            {{ $msg->message }}
                        </div>
                    </td>
                    <td class="p-3 align-top text-right">
                        <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}" onsubmit="return confirm('Hapus pesan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-400">Belum ada pesan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($messages->hasPages())
    <div class="mt-4">
        {{ $messages->links() }}
    </div>
    @endif
</div>
@endsection
