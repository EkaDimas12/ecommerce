<?php

namespace App\Http\Controllers;

use App\Services\KomerceService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected KomerceService $komerce;

    public function __construct(KomerceService $komerce)
    {
        $this->komerce = $komerce;
    }

    /**
     * Get list of provinces
     */
    public function provinces()
    {
        $provinces = $this->komerce->getProvinces();
        return response()->json(['ok' => true, 'data' => $provinces]);
    }

    /**
     * Search destination (city, district, subdistrict)
     */
    public function searchDestination(Request $request)
    {
        $keyword = $request->query('q', '');
        
        if (strlen($keyword) < 3) {
            return response()->json([
                'ok' => false, 
                'message' => 'Minimal 3 karakter untuk pencarian',
                'data' => []
            ]);
        }

        try {
            $destinations = $this->komerce->searchDestination($keyword);
            return response()->json(['ok' => true, 'data' => $destinations]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Debug endpoint to check API configuration
     */
    public function debug()
    {
        return response()->json([
            'ok' => true,
            'config' => [
                'cost_api_key_set' => !empty(config('services.komerce.cost_api_key')),
                'tracking_api_key_set' => !empty(config('services.komerce.tracking_api_key')),
                'base_url' => config('services.komerce.base_url'),
                'origin_id' => env('ORIGIN_DESTINATION_ID'),
            ],
            'test' => 'API configuration loaded'
        ]);
    }

    /**
     * Get available couriers
     */
    public function couriers()
    {
        $couriers = $this->komerce->getAvailableCouriers();
        return response()->json(['ok' => true, 'data' => $couriers]);
    }

    /**
     * Check shipping cost
     */
    public function check(Request $request)
    {
        $request->validate([
            'origin' => 'required|integer',
            'destination' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
        ]);

        $costs = $this->komerce->checkCost(
            (int) $request->origin,
            (int) $request->destination,
            (int) $request->weight,
            $request->courier
        );

        if (empty($costs)) {
            return response()->json([
                'ok' => false,
                'message' => 'Tidak dapat mengambil data ongkir. Coba kurir lain.',
                'services' => [],
            ]);
        }

        return response()->json(['ok' => true, 'services' => $costs]);
    }

    /**
     * Track shipment by AWB
     */
    public function track(Request $request)
    {
        $request->validate([
            'awb' => 'required|string',
            'courier' => 'required|string',
        ]);

        $tracking = $this->komerce->trackShipment($request->awb, $request->courier);

        if (!$tracking) {
            return response()->json([
                'ok' => false,
                'message' => 'Tidak dapat melacak pengiriman.',
            ], 404);
        }

        return response()->json(['ok' => true, 'data' => $tracking]);
    }
}
