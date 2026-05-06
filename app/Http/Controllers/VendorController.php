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

    /**
     * Display the QR code scanner page for vendors.
     */
    public function scanIndex()
    {
        return view('vendor.scan');
    }

    /**
     * Get order details for a vendor after scanning.
     */
    public function orderDetail($idpesanan)
    {
        // For demo, we assume vendor ID 1
        $vendorId = 1;

        $order = Pesanan::with(['detailPesanan.menu' => function($q) use ($vendorId) {
            $q->where('idvendor', $vendorId);
        }])->find($idpesanan);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Filter items to only show those belonging to this vendor
        $items = $order->detailPesanan->filter(function($detail) {
            return $detail->menu !== null;
        })->map(function($detail) {
            return [
                'name' => $detail->menu->nama_menu,
                'qty' => $detail->jumlah
            ];
        })->values();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'No items for your store'], 404);
        }

        $paymentStatus = match($order->status_bayar) {
            0 => 'Pending',
            1 => 'Lunas',
            2 => 'Expired',
            3 => 'Cancelled',
            default => 'Unknown'
        };

        return response()->json([
            'items' => $items,
            'payment_status' => $paymentStatus
        ]);
    }
}
