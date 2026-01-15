@extends('admin.layout')

@section('title', 'Testimoni')
@section('subtitle', 'Kelola testimoni customer')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div></div>
        <a href="{{ route('admin.testimonials.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg font-medium">
            + Tambah Testimoni
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Rating</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Pesan</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($testimonials as $testimonial)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $testimonial->name }}</td>
                        <td class="px-6 py-4">
                            <span class="text-amber-500">
                                @for ($i = 0; $i < $testimonial->rating; $i++)
                                    ⭐
                                @endfor
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $testimonial->message }}</td>
                        <td class="px-6 py-4">
                            @if ($testimonial->is_active)
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                            @else
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                class="text-indigo-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST"
                                class="inline" onsubmit="return confirm('Yakin hapus testimoni ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada testimoni</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
