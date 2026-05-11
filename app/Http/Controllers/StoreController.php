<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric|min:0',
        ]);

        $data['barcode'] = 'STR-' . Str::upper(Str::random(8));
        Store::create($data);

        return redirect()->back()->with('success', 'Toko berhasil disimpan.');
    }

    public function findByBarcode($barcode)
    {
        $store = Store::where('barcode', $barcode)->firstOrFail();

        return response()->json([
            'id' => $store->id,
            'barcode' => $store->barcode,
            'nama_toko' => $store->nama_toko,
            'latitude' => $store->latitude,
            'longitude' => $store->longitude,
            'accuracy' => $store->accuracy,
        ]);
    }

    public function printBarcode($id)
    {
        $store = Store::findOrFail($id);

        return response()->json([
            'barcode' => $store->barcode,
            'nama_toko' => $store->nama_toko,
        ]);
    }
}
