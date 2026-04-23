<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    public $timestamps = false; // We only use created_at

    protected $fillable = [
        'order_id',
        'order_code',
        'event',
        'payment_type',
        'transaction_status',
        'payment_status_from',
        'payment_status_to',
        'order_status_from',
        'order_status_to',
        'metadata',
        'source',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'created_at' => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ──────────────────────────────────────────────
    // Helper: create a log entry
    // ──────────────────────────────────────────────

    /**
     * Record a transaction event.
     *
     * @param  Order       $order
     * @param  string      $event   e.g. 'created', 'payment_success', 'payment_failed', 'cancelled'
     * @param  string      $source  e.g. 'checkout', 'midtrans_webhook', 'admin', 'user_cancel'
     * @param  array       $extra   ['payment_type', 'transaction_status', 'metadata', 'ip_address',
     *                               'payment_status_from', 'payment_status_to',
     *                               'order_status_from', 'order_status_to']
     */
    public static function record(Order $order, string $event, string $source, array $extra = []): self
    {
        return static::create([
            'order_id'             => $order->id,
            'order_code'           => $order->code,
            'event'                => $event,
            'source'               => $source,
            'payment_type'         => $extra['payment_type'] ?? null,
            'transaction_status'   => $extra['transaction_status'] ?? null,
            'payment_status_from'  => $extra['payment_status_from'] ?? null,
            'payment_status_to'    => $extra['payment_status_to'] ?? null,
            'order_status_from'    => $extra['order_status_from'] ?? null,
            'order_status_to'      => $extra['order_status_to'] ?? null,
            'metadata'             => $extra['metadata'] ?? null,
            'ip_address'           => $extra['ip_address'] ?? request()->ip(),
            'created_at'           => now(),
        ]);
    }

    // ──────────────────────────────────────────────
    // Display helpers
    // ──────────────────────────────────────────────

    /**
     * Human-readable event label.
     */
    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'created'          => 'Pesanan Dibuat',
            'payment_pending'  => 'Menunggu Pembayaran',
            'payment_success'  => 'Pembayaran Berhasil',
            'payment_failed'   => 'Pembayaran Gagal',
            'payment_expired'  => 'Pembayaran Kedaluwarsa',
            'cancelled'        => 'Pesanan Dibatalkan',
            'status_changed'   => 'Status Diperbarui',
            'stock_restored'   => 'Stok Dikembalikan',
            default            => ucfirst(str_replace('_', ' ', $this->event)),
        };
    }

    /**
     * Badge color class for the event.
     */
    public function getEventColorAttribute(): string
    {
        return match ($this->event) {
            'created'          => 'bg-blue-100 text-blue-700',
            'payment_pending'  => 'bg-amber-100 text-amber-700',
            'payment_success'  => 'bg-emerald-100 text-emerald-700',
            'payment_failed',
            'payment_expired'  => 'bg-red-100 text-red-700',
            'cancelled'        => 'bg-red-100 text-red-700',
            'status_changed'   => 'bg-purple-100 text-purple-700',
            'stock_restored'   => 'bg-sky-100 text-sky-700',
            default            => 'bg-slate-100 text-slate-600',
        };
    }

    /**
     * Source label.
     */
    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'checkout'         => 'Checkout',
            'midtrans_webhook' => 'Midtrans',
            'admin'            => 'Admin',
            'user_cancel'      => 'User',
            default            => ucfirst($this->source),
        };
    }
}
