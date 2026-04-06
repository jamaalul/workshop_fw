@extends('layouts.dashboard')

@section('title', 'Pembayaran Berhasil - Kantin Mini')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card text-center text-white bg-gradient-success shadow-lg">
            <div class="card-body py-5">
                <i class="mdi mdi-check-circle-outline display-1 mb-3"></i>
                <h2 class="card-title text-white fw-bold">Pembayaran Berhasil!</h2>
                <h4 class="mt-4">Terima kasih, {{ $pesanan->nama }}.</h4>
                <p class="fs-5 mt-3 px-4">
                    Pesanan Anda dengan nomor <strong>#{{ $pesanan->idpesanan }}</strong> 
                    telah lunas sebesar <strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong>
                    dan sedang disiapkan.
                </p>
                <div class="mt-5">
                    <a href="{{ route('canteen.index') }}" class="btn btn-light btn-lg fw-bold text-success rounded-pill px-5 shadow-sm">
                        <i class="mdi mdi-arrow-left me-2"></i> Kembali pesan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
