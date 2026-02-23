<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function bukuReport()
    {
        $buku = Buku::with('kategori')->get();

        $pdf = Pdf::loadView('reports.buku_report', compact('buku'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-buku.pdf');
    }

    public function kategoriReport()
    {
        $kategori = Kategori::withCount('buku')->get();

        $pdf = Pdf::loadView('reports.kategori_report', compact('kategori'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-kategori.pdf');
    }
}
