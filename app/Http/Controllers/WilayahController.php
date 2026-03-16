<?php

namespace App\Http\Controllers;

use App\Models\RegProvince;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Display the wilayah dropdown page.
     */
    public function index()
    {
        return view('dashboard.wilayah.index');
    }

    /**
     * Return all provinces as JSON.
     */
    public function getProvinsi()
    {
        try {
            $provinces = RegProvince::orderBy('name')->get();
            
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Data provinces berhasil diambil',
                'data' => $provinces
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal mengambil data provinces: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }

    /**
     * Return regencies by province_id as JSON.
     */
    public function getKota($id)
    {
        try {
            $regencies = RegRegency::where('province_id', $id)
                ->orderBy('name')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Data kota berhasil diambil',
                'data' => $regencies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal mengambil data kota: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }

    /**
     * Return districts by regency_id as JSON.
     */
    public function getKecamatan($id)
    {
        try {
            $districts = RegDistrict::where('regency_id', $id)
                ->orderBy('name')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Data kecamatan berhasil diambil',
                'data' => $districts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal mengambil data kecamatan: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }

    /**
     * Return villages by district_id as JSON.
     */
    public function getKelurahan($id)
    {
        try {
            $villages = RegVillage::where('district_id', $id)
                ->orderBy('name')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Data kelurahan berhasil diambil',
                'data' => $villages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal mengambil data kelurahan: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
}
