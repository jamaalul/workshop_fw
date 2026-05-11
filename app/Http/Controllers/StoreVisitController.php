<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreVisit;
use Illuminate\Http\Request;

class StoreVisitController extends Controller
{
    public function index()
    {
        $stores = Store::orderBy('created_at', 'desc')->get();
        $visits = StoreVisit::with(['store', 'user'])->orderBy('visited_at', 'desc')->paginate(10);

        return view('dashboard.kunjungan.index', compact('stores', 'visits'));
    }

    public function history()
    {
        $stores = Store::orderBy('created_at', 'desc')->get();
        $visits = StoreVisit::with(['store', 'user'])->orderBy('visited_at', 'desc')->paginate(10);

        return view('dashboard.kunjungan.index', compact('stores', 'visits'))->with('historyTab', true);
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'sales_latitude' => 'required|numeric|between:-90,90',
            'sales_longitude' => 'required|numeric|between:-180,180',
            'sales_accuracy' => 'required|numeric|min:0',
        ]);

        $store = Store::findOrFail($validated['store_id']);

        $distance = $this->haversine(
            $store->latitude,
            $store->longitude,
            $validated['sales_latitude'],
            $validated['sales_longitude']
        );

        $baseThreshold = config('geolocation.base_threshold', 300);
        $effectiveThreshold = $baseThreshold + $store->accuracy + $validated['sales_accuracy'];
        $status = $distance <= $effectiveThreshold ? 'diterima' : 'ditolak';

        StoreVisit::create([
            'store_id' => $store->id,
            'user_id' => auth()->id(),
            'sales_latitude' => $validated['sales_latitude'],
            'sales_longitude' => $validated['sales_longitude'],
            'sales_accuracy' => $validated['sales_accuracy'],
            'distance_meters' => $distance,
            'status' => $status,
            'visited_at' => now(),
        ]);

        return response()->json([
            'status' => $status,
            'distance_meters' => round($distance, 2),
            'effective_threshold' => round($effectiveThreshold, 2),
            'store' => [
                'id' => $store->id,
                'nama_toko' => $store->nama_toko,
                'latitude' => $store->latitude,
                'longitude' => $store->longitude,
                'accuracy' => $store->accuracy,
            ],
        ]);
    }

    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }
}
