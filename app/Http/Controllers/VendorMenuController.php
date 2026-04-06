<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Vendor;

class VendorMenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('vendor')->get(); // Dummy all menus
        return view('vendor.menus.index', compact('menus'));
    }

    public function create()
    {
        $vendors = Vendor::all();
        return view('vendor.menus.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|numeric',
            'path_gambar' => 'required',
            'idvendor' => 'required|exists:vendor,idvendor',
        ]);
        Menu::create($request->all());
        return redirect()->route('menus.index')->with('success', 'Menu created!');
    }

    public function edit(Menu $menu)
    {
        $vendors = Vendor::all();
        return view('vendor.menus.edit', compact('menu', 'vendors'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|numeric',
            'idvendor' => 'required|exists:vendor,idvendor',
        ]);
        $menu->update($request->all());
        return redirect()->route('menus.index')->with('success', 'Menu updated!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted!');
    }
}
