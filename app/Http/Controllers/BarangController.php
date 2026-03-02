<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function create()
    {
        return view('dashboard.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);

        return view('dashboard.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('barang')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->delete();

            return redirect()->route('barang')
                ->with('success', 'Barang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('barang')
                ->with('error', 'Barang tidak dapat dihapus.');
        }
    }

    public function printLabel(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'exists:barangs,id',
            'start_x' => 'required|integer|min:1|max:5',
            'start_y' => 'required|integer|min:1|max:8',
        ]);

        $barangs = Barang::whereIn('id', $request->selected_ids)->get();
        $startX = $request->start_x;
        $startY = $request->start_y;

        $pdf = Pdf::loadView('reports.barang_label', compact('barangs', 'startX', 'startY'))
            ->setPaper([0, 0, 612, 935.43], 'portrait'); // Folio (8.5 x 13 inch in points)

        return $pdf->stream('label-harga-barang.pdf');
    }
}
