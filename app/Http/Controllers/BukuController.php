<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    public function create()
    {
        $kategoris = Kategori::all();
        return view('dashboard.buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string',
            'judul' => 'required|string',
            'pengarang' => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        Buku::create($request->all());

        return redirect()->route('buku')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();
        return view('dashboard.buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string',
            'judul' => 'required|string',
            'pengarang' => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return redirect()->route('buku')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $buku->delete();

            return redirect()->route('buku')
                ->with('success', 'Buku berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('buku')
                ->with('error', 'Buku tidak dapat dihapus karena masih memiliki relasi dengan data lain.');
        }
    }
}
