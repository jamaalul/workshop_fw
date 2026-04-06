<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;

class CanteenController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('menus')->get();
        return view('canteen.index', compact('vendors'));
    }

    public function getMenu(Vendor $vendor)
    {
        return response()->json($vendor->menus);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idvendor' => 'required|exists:vendor,idvendor',
            'items' => 'required|array',
            'items.*.idmenu' => 'required|exists:menu,idmenu',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $guestCount = Pesanan::count() + 1;
            $guestName = 'Guest_' . str_pad($guestCount, 7, '0', STR_PAD_LEFT);

            $pesanan = Pesanan::create([
                'nama' => $guestName,
                'total' => 0, // Will update below
                'metode_bayar' => null,
                'status_bayar' => 0, // pending
            ]);

            foreach ($request->items as $item) {
                $menuItem = Menu::find($item['idmenu']);
                $subtotal = $menuItem->harga * $item['jumlah'];
                $totalAmount += $subtotal;

                DetailPesanan::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu' => $menuItem->idmenu,
                    'jumlah' => $item['jumlah'],
                    'harga' => $menuItem->harga,
                    'subtotal' => $subtotal,
                ]);
            }

            $pesanan->update(['total' => $totalAmount]);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('payment.show', ['pesanan' => $pesanan->idpesanan])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
