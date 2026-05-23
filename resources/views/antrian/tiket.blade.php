<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrian #{{ str_pad($queue->number, 3, '0', STR_PAD_LEFT) }} — {{ config('app.name') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    <style>
        body {
            background-color: #f2edf3;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .ticket-wrapper { width: 100%; max-width: 420px; }
        .ticket-card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            position: relative;
        }
        .ticket-header {
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
            position: relative;
        }
        .ticket-logo-ring {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            color: white;
            border: 2px solid rgba(255,255,255,0.5);
        }
        .number-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #b66dff;
            font-size: 5rem;
            font-weight: 900;
            line-height: 1;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(182, 109, 255, 0.2);
        }
        .ticket-body {
            padding: 1.5rem 2rem 2.5rem;
            text-align: center;
        }
        .dashed-divider {
            border: none;
            border-top: 2px dashed #eebefa;
            margin: 1.5rem 0;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e6f7eb;
            color: #1bcfb4;
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .blink-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #1bcfb4;
            animation: blink 1.2s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="ticket-wrapper">
        <div class="ticket-card">
            <!-- Header -->
            <div class="ticket-header bg-gradient-primary text-white">
                <div class="ticket-logo-ring">
                    <i class="mdi mdi-ticket-outline"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ config('app.name') }}</h5>
                <p class="opacity-75 mb-0 small">Sistem Antrian Digital</p>
                <hr class="dashed-divider mb-0 mt-4" style="border-top-color: rgba(255,255,255,0.3)">
            </div>

            <!-- Body -->
            <div class="ticket-body">
                <p class="text-muted small mb-3 fw-semibold text-uppercase">Nomor Antrian Anda</p>
                
                <div class="number-badge">
                    {{ str_pad($queue->number, 3, '0', STR_PAD_LEFT) }}
                </div>
                
                <h4 class="fw-bold mb-2 text-dark">{{ $queue->name }}</h4>
                
                <div class="mb-4">
                    <span class="status-badge">
                        <span class="blink-dot"></span> Menunggu Dipanggil
                    </span>
                </div>
                
                <div class="row text-center g-0 bg-light rounded p-3 mb-4">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block mb-1">Tanggal</small>
                        <span class="fw-semibold text-dark">{{ $queue->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Pukul</small>
                        <span class="fw-semibold text-dark">{{ $queue->created_at->format('H:i') }} WIB</span>
                    </div>
                </div>
                
                <p class="text-muted small mb-4">
                    <i class="mdi mdi-volume-high me-1 text-primary"></i> Perhatikan panggilan pada papan antrian
                </p>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('antrian.board') }}" target="_blank" class="btn btn-gradient-primary">
                        <i class="mdi mdi-monitor me-2"></i>Lihat Papan Antrian
                    </a>
                    <a href="{{ route('antrian.guest') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-account-plus me-1"></i>Daftarkan Orang Lain
                    </a>
                </div>
            </div>
        </div>
        <p class="text-center text-muted mt-3 small">
            <i class="mdi mdi-shield-check me-1"></i> Simpan halaman ini sebagai bukti
        </p>
    </div>
</body>
</html>
