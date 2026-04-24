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
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" data-confirm="Yakin ingin menghapus kupon {{ $coupon->code }}?" data-confirm-title="Hapus Kupon">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm">Hapus</button>
                            </form>
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
