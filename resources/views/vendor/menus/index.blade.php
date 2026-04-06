@extends('layouts.dashboard')

@section('title', 'Manage Menus')

@section('content')
<div class="row">
    <div class="col-lg-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Menu</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Menu</th>
                                <th>Vendor</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                            <tr>
                                <td>{{ $menu->idmenu }}</td>
                                <td>{{ $menu->nama_menu }}</td>
                                <td>{{ $menu->vendor->nama_vendor }}</td>
                                <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
