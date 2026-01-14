<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Notification;

class MidtransController extends Controller
{
    /**
     * Midtrans webhook (payment notification)
     * URL: POST /midtrans/notification
     * - WAJIB dikecualikan dari CSRF di VerifyCsrfToken
     * - WAJIB set di Midtrans Dashboard (Payment Notification URL)
     */
    public function notification(Request $request)
    {
        // Pastikan config Midtrans terbaca
        $serverKey = trim((string) config('services.midtrans.server_key'));
        $isProduction = filter_var(config('services.midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);

        if (!$serverKey) {
            Log::error('Midtrans server key missing');
            return response()->json(['ok' => false, 'message' => 'Server key missing'], 500);
        }

        MidtransConfig::$serverKey = $serverKey;
        MidtransConfig::$isProduction = $isProduction;
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;

        // --- Security: verify signature key (recommended) ---
        // Midtrans signature formula:
        // sha512(order_id + status_code + gross_amount + serverKey)
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            Log::warning('Midtrans notification missing required fields', $payload);
            return response()->json(['ok' => false, 'message' => 'Invalid payload'], 400);
        }

        $computedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if (!hash_equals($computedSignature, $signatureKey)) {
            Log::warning('Midtrans signature mismatch', [
                'order_id' => $orderId,
                'expected' => $computedSignature,
                'got' => $signatureKey
            ]);
            return response()->json(['ok' => false, 'message' => 'Signature mismatch'], 401);
        }

        // Use Midtrans Notification object to normalize data
        $notif = new Notification();

        $transactionStatus = $notif->transaction_status ?? null; // settlement, pending, deny, cancel, expire, capture
        $fraudStatus = $notif->fraud_status ?? null;             // accept, challenge
        $paymentType = $notif->payment_type ?? null;

        // Cari order berdasarkan code (kita pakai order->code sebagai order_id Midtrans)
        $order = Order::where('code', $orderId)->first();
        if (!$order) {
            Log::warning('Order not found for Midtrans notification', ['order_id' => $orderId]);
            return response()->json(['ok' => false, 'message' => 'Order not found'], 404);
        }

        // Mapping status Midtrans -> status internal
        // - settlement / capture(accept) => paid
        // - pending => pending
        // - deny/cancel/expire => failed/cancelled
        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $order->payment_status = 'pending';
            } else {
                $order->payment_status = 'paid';
                $order->order_status = 'processing';
            }
        } elseif ($transactionStatus === 'settlement') {
            $order->payment_status = 'paid';
            $order->order_status = 'processing';
        } elseif ($transactionStatus === 'pending') {
            $order->payment_status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $order->payment_status = 'failed';
            $order->order_status = 'cancelled';
        } else {
            // fallback: keep as pending
            $order->payment_status = $order->payment_status ?: 'pending';
        }

        // (Opsional) kalau kamu punya kolom payment_type di orders, simpan di sini
        // $order->payment_type = $paymentType;

        $order->save();

        Log::info('Midtrans notification processed', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType,
            'payment_status_saved' => $order->payment_status,
            'order_status_saved' => $order->order_status,
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Redirect after payment finish
     * GET /payment/finish?order_id=TC-XXXX
     */
    public function finish(Request $request)
    {
        // Midtrans usually includes order_id in query (or you append it yourself)
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return redirect()->route('home')->with('toast', [
                'type' => 'success',
                'message' => 'Pembayaran selesai.',
            ]);
        }

        return redirect()->route('order.show', $orderId);
    }

    /**
     * Redirect if user closes popup / payment not finished
     */
    public function unfinish()
    {
        return redirect()->route('cart.index')->with('toast', [
            'type' => 'danger',
            'message' => 'Pembayaran belum selesai.',
        ]);
    }

    /**
     * Redirect if payment error
     */
    public function error()
    {
        return redirect()->route('cart.index')->with('toast', [
            'type' => 'danger',
            'message' => 'Terjadi kesalahan pembayaran. Silakan coba lagi.',
        ]);
    }
}
