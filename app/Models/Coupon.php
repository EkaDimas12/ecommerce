<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk merepresentasikan kupon diskon/promo.
 * Kupon dapat bertipe nominal tetap (fixed) atau persentase (percent).
 */
class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',           // Kode unik kupon (misal: MERDEKA2025)
        'type',           // Tipe diskon: 'fixed' (Rp) atau 'percent' (%)
        'value',          // Nilai diskon (misal: 10000 atau 15)
        'min_purchase',   // Syarat minimal belanja untuk bisa memakai kupon
        'max_discount',   // Batas maksimal potongan (hanya relevan untuk tipe 'percent')
        'usage_limit',    // Kuota maksimal penggunaan kupon secara global (null = tak terbatas)
        'used_count',     // Jumlah kupon ini telah berhasil digunakan dalam transaksi
        'expires_at',     // Tanggal dan waktu kupon ini tidak bisa lagi digunakan
        'is_active',      // Status apakah kupon ini aktif (true) atau dinonaktifkan (false)
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
