<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KomerceService
{
    protected string $costApiKey;
    protected string $trackingApiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->costApiKey = config('services.komerce.cost_api_key', '');
        $this->trackingApiKey = config('services.komerce.tracking_api_key', '');
        $this->baseUrl = config('services.komerce.base_url', 'https://rajaongkir.komerce.id/api/v1');
    }

    /**
     * Get HTTP client for cost API
     */
    protected function costClient()
    {
        return Http::withHeaders([
            'key' => $this->costApiKey,
            'Accept' => 'application/json',
        ]);
    }

    /**
     * Get HTTP client for tracking API
     */
    protected function trackingClient()
    {
        return Http::withHeaders([
            'key' => $this->trackingApiKey,
            'Accept' => 'application/json',
        ]);
    }

    /**
     * Get list of provinces
     */
    public function getProvinces(): array
    {
        return Cache::remember('komerce_provinces', 86400 * 7, function () {
            try {
                $response = $this->costClient()->get("{$this->baseUrl}/destination/province");

                if ($response->successful()) {
                    $data = $response->json();
                    $results = $data['data'] ?? [];
                    
                    return collect($results)->map(fn($p) => [
                        'id' => $p['province_id'] ?? $p['id'] ?? '',
                        'name' => $p['province'] ?? $p['name'] ?? '',
                    ])->filter(fn($p) => !empty($p['id']))->values()->toArray();
                }
            } catch (\Exception $e) {
                Log::error('Komerce provinces error', ['error' => $e->getMessage()]);
            }

            return [];
        });
    }

    /**
     * Search destination by keyword (city, district, subdistrict)
     * This is the main method for location selection
     */
    public function searchDestination(string $keyword): array
    {
        if (strlen($keyword) < 3) {
            return [];
        }

        $cacheKey = 'komerce_search_' . md5($keyword);
        
        return Cache::remember($cacheKey, 3600, function () use ($keyword) {
            try {
                $response = $this->costClient()->get("{$this->baseUrl}/destination/domestic-destination", [
                    'search' => $keyword
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $results = $data['data'] ?? [];
                    
                    return collect($results)->map(fn($d) => [
                        'id' => $d['id'] ?? '',
                        'label' => $d['label'] ?? '',
                        'province' => $d['province_name'] ?? '',
                        'city' => $d['city_name'] ?? '',
                        'district' => $d['district_name'] ?? '',
                        'subdistrict' => $d['subdistrict_name'] ?? '',
                        'zip_code' => $d['zip_code'] ?? '',
                    ])->filter(fn($d) => !empty($d['id']))->values()->toArray();
                }
            } catch (\Exception $e) {
                Log::error('Komerce search error', ['error' => $e->getMessage()]);
            }

            return [];
        });
    }

    /**
     * Get available couriers
     */
    public function getAvailableCouriers(): array
    {
        return [
            'jne' => 'JNE',
            'jnt' => 'J&T Express',
            'sicepat' => 'SiCepat',
            'pos' => 'POS Indonesia',
            'tiki' => 'TIKI',
            'anteraja' => 'AnterAja',
            'wahana' => 'Wahana',
            'ninja' => 'Ninja Express',
            'lion' => 'Lion Parcel',
            'ide' => 'ID Express',
        ];
    }

    /**
     * Check shipping cost
     * Origin and destination should be destination IDs from searchDestination
     */
    public function checkCost(int $origin, int $destination, int $weight, string $courier): array
    {
        try {
            // Komerce API requires form-urlencoded data, not JSON
            $response = $this->costClient()->asForm()->post("{$this->baseUrl}/calculate/domestic-cost", [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
            ]);

            Log::info('Komerce cost response', [
                'status' => $response->status(),
                'origin' => $origin,
                'destination' => $destination,
                'courier' => $courier,
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $costs = $data['data'] ?? [];
                
                if (!empty($costs)) {
                    return collect($costs)->map(function ($cost) {
                        return [
                            'service' => $cost['service'] ?? '-',
                            'description' => $cost['description'] ?? '',
                            'cost' => (int) ($cost['cost'] ?? $cost['price'] ?? 0),
                            'etd' => (string) ($cost['etd'] ?? '-'),
                        ];
                    })->toArray();
                }
            }
        } catch (\Exception $e) {
            Log::error('Komerce cost error', ['error' => $e->getMessage()]);
        }

        return [];
    }

    /**
     * Track shipment by AWB
     */
    public function trackShipment(string $awb, string $courier): ?array
    {
        try {
            $response = $this->trackingClient()->get("{$this->baseUrl}/tracking", [
                'awb' => $awb,
                'courier' => $courier,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('Komerce tracking error', ['error' => $e->getMessage()]);
        }

        return null;
    }
}
