<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function dashboard()
    {
        // Dummy vendor for demo (assuming vendor ID 1)
        $vendor = Vendor::first();
        return view('vendor.dashboard', compact('vendor'));
    }

    public function orders()
    {
        // 1=lunas
        $orders = Pesanan::with(['detailPesanan.menu' => function($q) {
            // Simplified: in a real app, filter detailPesanan by vendor ID
        }])->where('status_bayar', 1)->orderBy('timestamp', 'desc')->get();
        
        return view('vendor.orders', compact('orders'));
    }
}
