<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display the POS page.
     */
    public function index()
    {
        return view('dashboard.pos.index');
    }

    /**
     * Find barang by kode.
     */
    public function findBarang($kode)
    {
        try {
            $barang = Barang::where('kode', $kode)->first();

            if (!$barang) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Barang tidak ditemukan',
                    'data' => null
                ]);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Barang ditemukan',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal mencari barang: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }

    /**
     * Process payment and save transaction.
     */
    public function bayar(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.id_barang' => 'required|string',
                'items.*.jumlah' => 'required|integer|min:1',
                'items.*.subtotal' => 'required|integer|min:0',
                'total' => 'required|integer|min:0',
            ]);

            DB::beginTransaction();

            // Create penjualan record
            $penjualan = Penjualan::create([
                'timestamp' => now(),
                'total' => $request->total,
            ]);

            // Create penjualan_details
            foreach ($request->items as $item) {
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Transaksi berhasil disimpan',
                'data' => [
                    'id_penjualan' => $penjualan->id,
                    'total' => $penjualan->total,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
}
