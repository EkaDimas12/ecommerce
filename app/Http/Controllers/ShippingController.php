<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'destination_city_id' => 'required|integer',
            'courier' => 'required|string|in:jne,pos,tiki',
            'weight' => 'required|integer|min:1',
        ]);

        $origin = (int) env('CITY_ID_ORIGIN', 23);

        $res = Http::withHeaders([
            'key' => config('services.rajaongkir.key'),
            'Accept' => 'application/json',
        ])->post(config('services.rajaongkir.base_url') . '/cost', [
            'origin' => $origin,
            'destination' => (int) $request->destination_city_id,
            'weight' => (int) $request->weight,
            'courier' => $request->courier,
        ]);

        if (!$res->ok()) {
            return response()->json(['ok' => false, 'message' => 'Gagal cek ongkir'], 500);
        }

        $costs = data_get($res->json(), 'rajaongkir.results.0.costs', []);

        $services = collect($costs)->map(function ($c) {
            $cost = $c['cost'][0]['value'] ?? 0;
            $etd = $c['cost'][0]['etd'] ?? '-';
            return [
                'service' => $c['service'] ?? '-',
                'description' => $c['description'] ?? '',
                'cost' => (int) $cost,
                'etd' => (string) $etd,
            ];
        })->values();

        return response()->json(['ok' => true, 'services' => $services]);
    }
}
