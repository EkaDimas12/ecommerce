<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\TransactionLog;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Midtrans webhook (payment notification).
     *
     * URL : POST /midtrans/notification
     * CSRF: Dikecualikan di VerifyCsrfToken
     * Setup: Set URL ini di Midtrans Dashboard → Settings → Payment Notification URL
     */
    public function notification(Request $request)
    {
        $payload      = $request->all();
        $orderId      = $payload['order_id']      ?? null;
        $statusCode   = $payload['status_code']   ?? null;
        $grossAmount  = $payload['gross_amount']   ?? null;
        $signatureKey = $payload['signature_key']  ?? null;

        // --- Validate required fields ---
        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            Log::warning('Midtrans webhook: missing required fields', [
                'ip'      => $request->ip(),
                'payload' => array_keys($payload),
            ]);
            return response()->json(['ok' => false, 'message' => 'Invalid payload'], 400);
        }

        // --- Verify signature (timing-safe) ---
        if (!$this->midtrans->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans webhook: signature mismatch', [
                'order_id' => $orderId,
                'ip'       => $request->ip(),
            ]);
            return response()->json(['ok' => false, 'message' => 'Signature mismatch'], 403);
        }

        // --- Find order ---
        $order = Order::where('code', $orderId)->first();
        if (!$order) {
            Log::warning('Midtrans webhook: order not found', ['order_id' => $orderId]);
            return response()->json(['ok' => false, 'message' => 'Order not found'], 404);
        }

        // --- Idempotency: skip if already in terminal state ---
        if (in_array($order->payment_status, ['paid', 'refunded'], true)) {
            Log::info('Midtrans webhook: order already settled, skipping', [
                'order_id'       => $orderId,
                'payment_status' => $order->payment_status,
            ]);
            return response()->json(['ok' => true, 'message' => 'Already processed']);
        }

        // --- Resolve new status ---
        $transactionStatus = $payload['transaction_status'] ?? 'pending';
        $fraudStatus       = $payload['fraud_status']       ?? 'accept';
        $paymentType       = $payload['payment_type']       ?? null;
        $newPaymentStatus  = $this->midtrans->resolvePaymentStatus($transactionStatus, $fraudStatus);

        // --- Determine event name ---
        $event = match ($newPaymentStatus) {
            'paid'     => 'payment_success',
            'failed'   => in_array($transactionStatus, ['expire'], true) ? 'payment_expired' : 'payment_failed',
            default    => 'payment_pending',
        };

        // --- Snapshot before ---
        $prevPaymentStatus = $order->payment_status;
        $prevOrderStatus   = $order->order_status;

        // --- Update order within transaction ---
        DB::transaction(function () use ($order, $newPaymentStatus, $transactionStatus, $event, $prevPaymentStatus, $prevOrderStatus, $paymentType, $payload, $request) {
            $order->payment_status = $newPaymentStatus;

            if ($newPaymentStatus === 'paid') {
                $order->order_status = 'processing';
            } elseif ($newPaymentStatus === 'failed') {
                $order->order_status = 'cancelled';

                // Kembalikan stok HANYA jika sebelumnya belum cancelled
                if ($prevPaymentStatus !== 'failed') {
                    $this->restoreStock($order);
                }
            }
            // pending → tetap status sebelumnya

            $order->save();

            // --- Log transaksi ---
            TransactionLog::record($order, $event, 'midtrans_webhook', [
                'payment_type'        => $paymentType,
                'transaction_status'  => $transactionStatus,
                'payment_status_from' => $prevPaymentStatus,
                'payment_status_to'   => $order->payment_status,
                'order_status_from'   => $prevOrderStatus,
                'order_status_to'     => $order->order_status,
                'ip_address'          => $request->ip(),
                'metadata'            => [
                    'status_code'   => $payload['status_code'] ?? null,
                    'fraud_status'  => $payload['fraud_status'] ?? null,
                    'payment_type'  => $paymentType,
                    'transaction_id' => $payload['transaction_id'] ?? null,
                ],
            ]);
        });

        Log::info('Midtrans webhook: processed', [
            'order_id'           => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status'       => $fraudStatus,
            'payment_type'       => $paymentType,
            'payment_status'     => $order->payment_status,
            'order_status'       => $order->order_status,
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Restore product stock when payment fails / expires / cancelled.
     */
    private function restoreStock(Order $order): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)
                ->whereNotNull('stock')
                ->increment('stock', $item->qty);
        }

        // Log stock restoration
        TransactionLog::record($order, 'stock_restored', 'midtrans_webhook', [
            'metadata' => [
                'items' => $order->items->map(fn ($i) => [
                    'product_id' => $i->product_id,
                    'qty'        => $i->qty,
                ])->toArray(),
            ],
        ]);

        Log::info('Midtrans: stock restored for cancelled order', [
            'order_code' => $order->code,
        ]);
    }

    /**
     * Redirect after payment finish (user completed payment).
     * GET /payment/finish?order_id=TC-XXXX
     */
    public function finish(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return redirect()->route('home')->with('toast', [
                'type'    => 'success',
                'message' => 'Pembayaran selesai.',
            ]);
        }

        return redirect()->route('order.show', $orderId);
    }

    /**
     * Redirect if user closes popup / payment not finished.
     * GET /payment/unfinish
     */
    public function unfinish(Request $request)
    {
        $orderId = $request->query('order_id');

        if ($orderId) {
            return redirect()->route('order.show', $orderId)->with('toast', [
                'type'    => 'warning',
                'message' => 'Pembayaran belum selesai. Anda bisa melanjutkan pembayaran dari halaman pesanan.',
            ]);
        }

        return redirect()->route('orders.history')->with('toast', [
            'type'    => 'warning',
            'message' => 'Pembayaran belum selesai.',
        ]);
    }

    /**
     * Redirect if payment error.
     * GET /payment/error
     */
    public function error(Request $request)
    {
        $orderId = $request->query('order_id');

        if ($orderId) {
            return redirect()->route('order.show', $orderId)->with('toast', [
                'type'    => 'danger',
                'message' => 'Terjadi kesalahan pembayaran. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('orders.history')->with('toast', [
            'type'    => 'danger',
            'message' => 'Terjadi kesalahan pembayaran. Silakan coba lagi.',
        ]);
    }
}
