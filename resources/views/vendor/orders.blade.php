@extends('layouts.dashboard')

@section('title', 'Vendor Orders - Paid Orders')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-currency-usd"></i>
        </span> Paid Orders Dashboard
    </h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Latest Paid Orders</h4>
                <p class="card-description">
                    Berikut adalah daftar pesanan yang sudah dibayar lunas (<code>status_bayar = 1</code>).
                </p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Waktu Pesanan</th>
                                <th>Items</th>
                                <th>Total Penjualan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->idpesanan }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->timestamp)->format('d M Y, H:i') }}</td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($order->detailPesanan as $detail)
                                            <li>{{ $detail->jumlah }}x {{ $detail->menu->nama_menu }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="font-weight-bold text-success">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge badge-gradient-success">Lunas</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan lunas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
