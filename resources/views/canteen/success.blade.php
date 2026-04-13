@extends('layouts.dashboard')

@section('title', 'Pembayaran Berhasil - Kantin Mini')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card text-center shadow-lg border-0" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
            <div class="card-body py-5 text-white">
                <i class="mdi mdi-check-circle-outline" style="font-size:4rem;"></i>
                <h2 class="card-title text-white fw-bold mt-2">Pembayaran Berhasil!</h2>
                <h4 class="mt-3">Terima kasih, {{ $pesanan->nama }}.</h4>
                <p class="fs-5 mt-2 px-4">
                    Pesanan <strong>#{{ $pesanan->idpesanan }}</strong> telah lunas
                    sebesar <strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong>
                    dan sedang disiapkan.
                </p>

                {{-- ── QR Code Section ── --}}
                <div class="mt-4 p-3 bg-white rounded shadow-sm d-inline-block">
                    <p class="text-dark small mb-2 fw-semibold">QR Code Pesanan Anda</p>
                    <img src="{{ route('canteen.qrcode', $pesanan->idpesanan) }}"
                         alt="QR Code Pesanan #{{ $pesanan->idpesanan }}"
                         width="160" height="160"
                         class="d-block mx-auto">
                    <p class="text-muted small mt-2 mb-0">Tunjukkan QR ini kepada kasir</p>
                </div>

                <div class="mt-4">
                    <a href="{{ route('canteen.index') }}" class="btn btn-light btn-lg fw-bold text-success rounded-pill px-5 shadow-sm">
                        <i class="mdi mdi-arrow-left me-2"></i> Pesan Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

