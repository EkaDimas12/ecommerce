<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected string $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key', '');
        $this->clientKey = config('services.midtrans.client_key', '');
        $this->isProduction = config('services.midtrans.is_production', false);
        $this->baseUrl = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1' 
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    /**
     * Get Midtrans client key for frontend
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * Get Snap URL based on environment
     */
    public function getSnapUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    /**
     * Create Snap transaction token
     */
    public function createTransaction(array $order, array $items, array $customer): ?array
    {
        $itemDetails = collect($items)->map(fn($item) => [
            'id' => (string) ($item['product_id'] ?? $item['id'] ?? '0'),
            'price' => (int) $item['price'],
            'quantity' => (int) $item['qty'],
            'name' => substr($item['name'] ?? 'Produk', 0, 50),
        ])->toArray();

        // Add shipping cost as item if exists
        if (isset($order['shipping_cost']) && (int) $order['shipping_cost'] > 0) {
            $itemDetails[] = [
                'id' => 'shipping',
                'price' => (int) $order['shipping_cost'],
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $order['code'],
                'gross_amount' => (int) $order['total'],
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $customer['name'] ?? 'Customer',
                'email' => $customer['email'] ?? 'customer@example.com',
                'phone' => $customer['phone'] ?? '',
                'billing_address' => [
                    'address' => $customer['address'] ?? '',
                ],
                'shipping_address' => [
                    'address' => $customer['address'] ?? '',
                ],
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
            ],
        ];

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders(['Accept' => 'application/json'])
                ->post("{$this->baseUrl}/transactions", $payload);

            Log::info('Midtrans create transaction', [
                'order_id' => $order['code'],
                'response' => $response->json(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Midtrans transaction failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans exception', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Verify notification signature
     */
    public function verifyNotification(array $notification): bool
    {
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        return $signatureKey === $expectedSignature;
    }

    /**
     * Get transaction status from notification
     */
    public function getTransactionStatus(array $notification): string
    {
        $transactionStatus = $notification['transaction_status'] ?? '';
        $fraudStatus = $notification['fraud_status'] ?? 'accept';

        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'accept' ? 'paid' : 'pending';
        }

        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            return 'paid';
        }

        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            return 'failed';
        }

        if ($transactionStatus === 'pending') {
            return 'pending';
        }

        return 'pending';
    }
}
