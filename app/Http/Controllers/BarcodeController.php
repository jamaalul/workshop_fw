<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * Display the barcode scanner page.
     */
    public function index()
    {
        return view('barcode.scan');
    }

    /**
     * Look up an item by its barcode (kode).
     */
    public function lookup($barcode)
    {
        $item = Barang::where('id', $barcode)->first();

        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }

        return response()->json([
            'id' => $item->kode,
            'name' => $item->nama,
            'price' => $item->harga,
        ]);
    }
}
