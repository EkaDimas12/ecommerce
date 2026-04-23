<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected string $snapBaseUrl;
    protected string $apiBaseUrl;

    public function __construct()
    {
        $this->serverKey   = trim((string) config('services.midtrans.server_key', ''));
        $this->clientKey   = trim((string) config('services.midtrans.client_key', ''));
        $this->isProduction = filter_var(config('services.midtrans.is_production', false), FILTER_VALIDATE_BOOLEAN);

        $this->snapBaseUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';

        $this->apiBaseUrl = $this->isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';
    }

    /**
     * Get Midtrans client key for frontend Snap.js
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * Get Snap.js URL based on environment (sandbox / production)
     */
    public function getSnapUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    /**
     * Create Snap transaction token.
     *
     * @param  array  $order    ['code' => ..., 'total' => ..., 'shipping_cost' => ...]
     * @param  array  $items    [['product_id', 'name', 'price', 'qty'], ...]
     * @param  array  $customer ['name', 'email', 'phone', 'address']
     * @return array|null       ['token' => '...', 'redirect_url' => '...'] or null on failure
     */
    public function createTransaction(array $order, array $items, array $customer): ?array
    {
        if (!$this->serverKey) {
            Log::error('Midtrans: server key not configured');
            return null;
        }

        $itemDetails = collect($items)->map(fn($item) => [
            'id'       => (string) ($item['product_id'] ?? $item['id'] ?? '0'),
            'price'    => (int) $item['price'],
            'quantity' => (int) $item['qty'],
            'name'     => mb_substr($item['name'] ?? 'Produk', 0, 50),
        ])->toArray();

        // Add shipping cost as line-item if > 0
        if (isset($order['shipping_cost']) && (int) $order['shipping_cost'] > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order['shipping_cost'],
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        // Validate: sum of items must equal gross_amount (Midtrans requirement)
        $itemSum = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $itemDetails));
        $grossAmount = (int) $order['total'];

        if ($itemSum !== $grossAmount) {
            Log::warning('Midtrans: item_details sum mismatch', [
                'item_sum'     => $itemSum,
                'gross_amount' => $grossAmount,
                'order_code'   => $order['code'],
            ]);
            // Adjust with rounding item to prevent Midtrans rejection
            $diff = $grossAmount - $itemSum;
            if (abs($diff) > 0 && abs($diff) <= 1000) {
                $itemDetails[] = [
                    'id'       => 'ADJ',
                    'price'    => $diff,
                    'quantity' => 1,
                    'name'     => 'Pembulatan',
                ];
            }
        }

        $payload = [
            'transaction_details' => [
                'order_id'     => $order['code'],
                'gross_amount' => $grossAmount,
            ],
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name' => $customer['name'] ?? 'Customer',
                'email'      => $customer['email'] ?? 'customer@example.com',
                'phone'      => $customer['phone'] ?? '',
                'billing_address' => [
                    'address' => $customer['address'] ?? '',
                ],
                'shipping_address' => [
                    'address' => $customer['address'] ?? '',
                ],
            ],
            'callbacks' => [
                'finish'   => route('payment.finish'),
                'unfinish' => route('payment.unfinish'),
                'error'    => route('payment.error'),
            ],
        ];

        try {
            $response = Http::timeout(15)
                ->withBasicAuth($this->serverKey, '')
                ->withHeaders(['Accept' => 'application/json'])
                ->post("{$this->snapBaseUrl}/transactions", $payload);

            if ($response->successful()) {
                Log::info('Midtrans: snap token created', [
                    'order_code' => $order['code'],
                ]);
                return $response->json();
            }

            Log::error('Midtrans: snap token creation failed', [
                'order_code' => $order['code'],
                'status'     => $response->status(),
                'body'       => $response->body(),
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans: exception during createTransaction', [
                'order_code' => $order['code'],
                'error'      => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Verify webhook notification signature (timing-safe).
     *
     * Midtrans signature formula:
     *   sha512( order_id + status_code + gross_amount + serverKey )
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
        return hash_equals($expected, $signatureKey);
    }

    /**
     * Map Midtrans transaction_status + fraud_status to internal payment status.
     *
     * Returns: 'paid' | 'pending' | 'failed'
     */
    public function resolvePaymentStatus(string $transactionStatus, string $fraudStatus = 'accept'): string
    {
        // Credit card: capture + accept = paid, capture + challenge = pending
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'accept' ? 'paid' : 'pending';
        }

        // Bank transfer, e-wallet, convenience store: settlement = paid
        if ($transactionStatus === 'settlement') {
            return 'paid';
        }

        // Failed states
        if (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'], true)) {
            return 'failed';
        }

        // Refund states
        if (in_array($transactionStatus, ['refund', 'partial_refund'], true)) {
            return 'refunded';
        }

        // Default: still pending
        return 'pending';
    }

    /**
     * Check transaction status directly via Midtrans API.
     * Useful for verifying payment status on-demand.
     */
    public function getTransactionStatus(string $orderId): ?array
    {
        if (!$this->serverKey) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->withBasicAuth($this->serverKey, '')
                ->withHeaders(['Accept' => 'application/json'])
                ->get("{$this->apiBaseUrl}/{$orderId}/status");

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Midtrans: getTransactionStatus failed', [
                'order_id' => $orderId,
                'status'   => $response->status(),
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans: exception during getTransactionStatus', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);
        }

        return null;
    }
}
