<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Barang;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.main');
    }

    public function kategori()
    {
        $kategoris = Kategori::select('id', 'nama_kategori')->get()->toArray();

        return view('dashboard.kategori.table', [
            'tableData' => $kategoris,
            'model' => 'Kategori',
            'idField' => 'id',
            'title' => 'Data Kategori',
            'createRoute' => 'kategori.create',
            'editRoute' => 'kategori.edit',
            'deleteRoute' => 'kategori.destroy',
        ]);
    }

    public function buku()
    {
        $bukus = Buku::select('id', 'kode', 'judul', 'pengarang')->get()->toArray();

        return view('dashboard.buku.table', [
            'tableData' => $bukus,
            'model' => 'Buku',
            'idField' => 'id',
            'title' => 'Data Buku',
            'createRoute' => 'buku.create',
            'editRoute' => 'buku.edit',
            'deleteRoute' => 'buku.destroy',
        ]);
    }

    public function barang()
    {
        $barangs = Barang::select('id', 'nama', 'harga')->get()->toArray();

        return view('dashboard.barang.table', [
            'tableData' => $barangs,
            'model' => 'Barang',
            'idField' => 'id',
            'title' => 'Data Barang',
            'createRoute' => 'barang.create',
            'editRoute' => 'barang.edit',
            'deleteRoute' => 'barang.destroy',
            'labelRoute' => 'barang.printLabel',
        ]);
    }
}
