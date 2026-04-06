@extends('layouts.dashboard')

@section('title', 'Pembayaran - Kantin Mini')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-info text-white me-2 shadow-sm">
            <i class="mdi mdi-credit-card"></i>
        </span> Pembayaran
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('canteen.index') }}">Kantin Mini</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Order Details -->
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title text-info mb-3">Info Pesanan</h4>
                <div class="d-flex justify-content-between text-muted fs-6 mb-3">
                    <span>ID: <strong>#{{ $pesanan->idpesanan }}</strong></span>
                    <span>Tamu: <strong>{{ $pesanan->nama }}</strong></span>
                </div>
                <hr>
                
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Jml</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->detailPesanan as $detail)
                            <tr>
                                <td class="align-middle">{{ $detail->menu->nama_menu }}</td>
                                <td class="text-center align-middle">{{ $detail->jumlah }}</td>
                                <td class="text-end align-middle">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="align-middle pt-3">Total Tagihan</th>
                                <th class="text-end text-primary fs-4 pt-3">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($pesanan->status_bayar == 1)
                    <div class="alert alert-success mt-4">
                        <i class="mdi mdi-check-circle"></i> Pesanan Lunas! Silakan tunggu pesanan Anda.
                    </div>
                @elseif($pesanan->status_bayar == 2)
                    <div class="alert alert-danger mt-4">
                        <i class="mdi mdi-close-circle"></i> Waktu pembayaran habis (Expired).
                    </div>
                @elseif($pesanan->status_bayar == 3)
                    <div class="alert alert-warning mt-4">
                        <i class="mdi mdi-alert-circle"></i> Pesanan Dibatalkan.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Section -->
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger"><i class="mdi mdi-alert-circle me-1"></i>{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-info"><i class="mdi mdi-information me-1"></i>{{ session('success') }}</div>
                @endif

                @if(!$pesanan->payment)
                    <h4 class="card-title text-primary mb-2">Pilih Metode Pembayaran</h4>
                    <p class="card-description text-muted">Silakan pilih metode pembayaran tagihan Anda di bawah ini:</p>
                    
                    <form action="{{ route('payment.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="idpesanan" value="{{ $pesanan->idpesanan }}">
                        
                        <div class="row mt-4">
                            <!-- QRIS Option -->
                            <div class="col-md-6 mb-3">
                                <label class="payment-method-card border rounded p-3 d-block w-100 position-relative shadow-sm" style="cursor: pointer; transition: 0.2s;">
                                    <input type="radio" class="d-none" name="payment_method" value="qris" required>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info-light p-2 rounded me-3 text-info">
                                            <i class="mdi mdi-qrcode fs-2"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">QRIS / GoPay</h5>
                                            <small class="text-muted">Scan menggunakan e-Wallet</small>
                                        </div>
                                    </div>
                                    <div class="check-icon position-absolute top-50 end-0 translate-middle text-primary d-none">
                                        <i class="mdi mdi-check-circle fs-3"></i>
                                    </div>
                                </label>
                            </div>
                            <!-- Virtual Accounts -->
                            @foreach(['bca' => 'BCA', 'bni' => 'BNI', 'bri' => 'BRI'] as $val => $name)
                            <div class="col-md-6 mb-3">
                                <label class="payment-method-card border rounded p-3 d-block w-100 position-relative shadow-sm" style="cursor: pointer; transition: 0.2s;">
                                    <input type="radio" class="d-none" name="payment_method" value="{{ $val }}" required>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success-light p-2 rounded me-3 text-success">
                                            <i class="mdi mdi-bank fs-2"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">{{ $name }} Virtual Account</h5>
                                            <small class="text-muted">Transfer via ATM / m-Banking</small>
                                        </div>
                                    </div>
                                    <div class="check-icon position-absolute top-50 end-0 translate-middle text-primary d-none">
                                        <i class="mdi mdi-check-circle fs-3"></i>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-2">
                            <button type="submit" class="btn btn-gradient-primary btn-lg w-100 fw-bold rounded-pill shadow-sm" id="pay_btn" disabled>
                                Lanjutkan Pembayaran <i class="mdi mdi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                @else
                    <!-- Showing Payment Instructions -->
                    @php $pymt = $pesanan->payment; @endphp
                    
                    <div class="text-center">
                        <h4 class="card-title mb-4">Instruksi Pembayaran</h4>
                        
                        @if($pymt->payment_status == 'pending')
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5 class="text-warning">Menunggu Pembayaran</h5>
                        @endif

                        <div class="p-4 border rounded mt-3 bg-light">
                            <p class="text-muted mb-1">Metode: <strong>{{ strtoupper($pymt->payment_type) }}</strong></p>
                            @if($pymt->bank)
                                <p class="text-muted mb-3">Bank: <strong>{{ strtoupper($pymt->bank) }}</strong></p>
                            @endif
                            
                            <h2 class="text-primary font-weight-bold mb-4">Rp {{ number_format($pymt->gross_amount, 0, ',', '.') }}</h2>

                            @if($pymt->va_number)
                                <p class="mb-1">Nomor Virtual Account:</p>
                                <h3 class="font-weight-bold bg-white p-3 border d-inline-block rounded">{{ $pymt->va_number }}</h3>
                                <p class="text-muted small mt-2">Silakan transfer sesuai dengan tagihan ke nomor VA di atas.</p>
                            @elseif($pymt->qr_code_url)
                                <p class="mb-2">Scan QRIS code di bawah ini:</p>
                                <img src="{{ $pymt->qr_code_url }}" alt="QR Code" class="img-fluid border rounded p-2 bg-white" style="max-width: 200px;">
                                <p class="text-muted small mt-2">Gunakan aplikasi GoPay/OVO/ShopeePay dsb untuk scan.</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-outline-info" onclick="checkStatus()">
                                <i class="mdi mdi-refresh"></i> Cek Status Pembayaran
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .payment-method-card.active {
        border-color: #b66dff !important;
        background-color: #f8f0ff;
        box-shadow: 0 4px 10px rgba(182, 109, 255, 0.2) !important;
    }
    .payment-method-card.active .check-icon {
        display: block !important;
    }
    .bg-info-light {
        background-color: rgba(54, 185, 204, 0.1);
    }
    .bg-success-light {
        background-color: rgba(27, 207, 180, 0.1);
    }
</style>
<script>
    $(document).ready(function() {
        $('input[name="payment_method"]').on('change', function() {
            $('.payment-method-card').removeClass('active border-primary').addClass('border');
            if ($(this).is(':checked')) {
                $(this).closest('.payment-method-card').addClass('active border-primary').removeClass('border');
                $('#pay_btn').prop('disabled', false);
            }
        });
    });
</script>

@if($pesanan->payment && $pesanan->status_bayar == 0)
<script>
    function checkStatus() {
        let btn = $(event.currentTarget);
        btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Mengecek...');
        
        $.get("{{ url('canteen/payment/status/' . $pesanan->idpesanan) }}", function(data) {
            if(data.status == 1) {
                window.location.href = "{{ route('canteen.payment.success', $pesanan->idpesanan) }}";
            } else if(data.status == 2) {
                alert('Waktu pembayaran habis!');
                window.location.reload();
            } else if(data.status == 3) {
                alert('Pembayaran dibatalkan!');
                window.location.reload();
            } else {
                btn.prop('disabled', false).html('<i class="mdi mdi-refresh me-1"></i> Cek Status Pembayaran sekarang');
            }
        });
    }

    // Auto-poll every 5 seconds
    setInterval(function(){
        $.get("{{ url('canteen/payment/status/' . $pesanan->idpesanan) }}", function(data) {
            if(data.status == 1) {
                window.location.href = "{{ route('canteen.payment.success', $pesanan->idpesanan) }}";
            } else if (data.status != 0) {
                window.location.reload();
            }
        });
    }, 5000);
</script>
@endif
@endpush
