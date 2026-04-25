@extends('admin.layout')
@section('title', 'Kupon Diskon')
@section('subtitle', 'Kelola kupon promosi dan diskon')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div></div>
        <a href="{{ route('admin.coupons.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg font-medium shadow-sm">
            + Tambah Kupon
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nilai Diskon</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Syarat</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Penggunaan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($coupons as $coupon)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-600 text-sm">{{ $coupon->code }}</div>
                            @if($coupon->expires_at)
                                <div class="text-[11px] text-slate-400 mt-0.5">Exp: {{ $coupon->expires_at->format('d M Y') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($coupon->type === 'fixed')
                                <div class="font-semibold text-slate-800 text-sm">Rp{{ number_format($coupon->value, 0, ',', '.') }}</div>
                            @else
                                <div class="font-semibold text-slate-800 text-sm">{{ $coupon->value }}%</div>
                                @if($coupon->max_discount)
                                    <div class="text-[11px] text-slate-400 mt-0.5">Maks. Rp{{ number_format($coupon->max_discount, 0, ',', '.') }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            Min. Rp{{ number_format($coupon->min_purchase, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-700">
                                {{ $coupon->used_count }} <span class="text-slate-400 font-normal">/ {{ $coupon->usage_limit ?? '∞' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($coupon->is_active && (!$coupon->expires_at || !$coupon->expires_at->isPast()) && ($coupon->usage_limit === null || $coupon->used_count < $coupon->usage_limit))
                                <span class="px-2.5 py-1 text-xs font-medium rounded-md bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-medium rounded-md bg-rose-50 text-rose-600 ring-1 ring-rose-200">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                    title="Edit">
                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                    data-confirm="Yakin ingin menghapus kupon {{ $coupon->code }}?"
                                    data-confirm-title="Hapus Kupon">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                        title="Hapus">
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            Belum ada data kupon. <a href="{{ route('admin.coupons.create') }}" class="text-indigo-600 hover:underline">Buat kupon baru</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if ($coupons->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
@endsection
