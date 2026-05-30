@extends('layouts.dashboard')

@section('title', 'Daftarkan Kartu NFC')

@push('styles')
<style>
    /* ── Register Card ── */
    .register-hero {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        color: white;
        box-shadow: 0 12px 40px rgba(245, 87, 108, 0.3);
        position: relative;
        overflow: hidden;
    }
    .register-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -30%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .scan-card-btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        font-weight: 700;
        padding: 12px 32px;
        border-radius: 50px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    .scan-card-btn:hover {
        background: rgba(255,255,255,0.35);
        border-color: rgba(255,255,255,0.7);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }
    .scan-card-btn:disabled {
        opacity: 0.6;
        transform: none;
    }

    /* ── Serial display ── */
    .serial-display {
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 12px 20px;
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin-top: 1rem;
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    .serial-display.has-value {
        background: rgba(255,255,255,0.25);
        border: 1px solid rgba(255,255,255,0.4);
    }

    /* ── Form Card ── */
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .form-card .form-control {
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-card .form-control:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.15);
    }
    .form-card .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 12px 32px;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .form-card .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    /* ── Success alert animation ── */
    .alert-slide {
        animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
            <i class="mdi mdi-card-plus"></i>
        </span> Daftarkan Kartu NFC
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/nfc">NFC Scanner</a></li>
            <li class="breadcrumb-item active" aria-current="page">Daftarkan Kartu</li>
        </ol>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show alert-slide" role="alert"
         style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(16,185,129,0.15);">
        <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show alert-slide" role="alert"
         style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(239,68,68,0.15);">
        <i class="mdi mdi-alert-circle me-2"></i>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    {{-- Scan Card Area --}}
    <div class="col-lg-5 mb-4">
        <div class="register-hero">
            <h5 class="fw-bold mb-2" style="position:relative;z-index:1;">
                <i class="mdi mdi-nfc me-2"></i>Langkah 1: Scan Kartu
            </h5>
            <p class="opacity-75 mb-3" style="position:relative;z-index:1;">
                Tekan tombol di bawah lalu dekatkan kartu NFC untuk membaca serial number-nya
            </p>
            <button class="scan-card-btn" id="btnScanCard" onclick="scanForRegister()">
                <i class="mdi mdi-nfc-search-variant me-2"></i>Scan Kartu
            </button>
            <div class="serial-display" id="serialDisplay">
                <span class="opacity-50" id="serialText">Belum ada kartu</span>
            </div>
            <small class="opacity-75 mt-2 d-block" id="scanStatus" style="position:relative;z-index:1;"></small>
        </div>
    </div>

    {{-- Registration Form --}}
    <div class="col-lg-7 mb-4">
        <div class="card form-card">
            <div class="card-body p-4">
                <h5 class="card-title mb-1">
                    <i class="mdi mdi-account-plus text-primary me-2"></i>Langkah 2: Isi Data
                </h5>
                <p class="text-muted small mb-4">Lengkapi nama mahasiswa lalu klik simpan</p>

                <form action="/nfc/register" method="POST">
                    @csrf
                    <input type="hidden" name="serial_number" id="serialInput" value="{{ old('serial_number') }}">

                    <div class="mb-3">
                        <label for="serialPreview" class="form-label fw-semibold">Serial Number</label>
                        <input type="text" class="form-control" id="serialPreview"
                               value="{{ old('serial_number') }}" readonly
                               placeholder="— scan kartu terlebih dahulu —"
                               style="background:#f8fafc; font-family:'Courier New',monospace; letter-spacing:1px;">
                    </div>

                    <div class="mb-4">
                        <label for="namaMahasiswa" class="form-label fw-semibold">Nama Mahasiswa</label>
                        <input type="text" class="form-control" id="namaMahasiswa"
                               name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}"
                               placeholder="Masukkan nama lengkap mahasiswa" required>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-submit" id="btnSubmit">
                            <i class="mdi mdi-content-save me-2"></i>Simpan Kartu
                        </button>
                        <a href="/nfc" class="btn btn-outline-secondary" style="border-radius:10px; padding:12px 24px;">
                            <i class="mdi mdi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /**
     * Safely decode a single NDEFRecord to a string.
     */
    function decodeRecord(record) {
        try {
            if (record.recordType === 'text') {
                const view = new DataView(record.data.buffer, record.data.byteOffset, record.data.byteLength);
                const langLen = view.getUint8(0);
                return new TextDecoder().decode(record.data.slice(1 + langLen));
            }
            if (record.recordType === 'url') {
                return new TextDecoder().decode(record.data);
            }
            if (record.data && record.data.byteLength > 0) {
                return new TextDecoder().decode(record.data);
            }
        } catch (_) { }
        return null;
    }

    async function scanForRegister() {
        const statusEl    = document.getElementById('scanStatus');
        const btnScan     = document.getElementById('btnScanCard');
        const serialDisp  = document.getElementById('serialDisplay');
        const serialText  = document.getElementById('serialText');
        const serialInput = document.getElementById('serialInput');
        const serialPrev  = document.getElementById('serialPreview');

        if (!('NDEFReader' in window)) {
            statusEl.textContent = 'Browser tidak mendukung Web NFC. Gunakan Chrome Android 89+.';
            return;
        }

        if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
            statusEl.textContent = 'Web NFC memerlukan HTTPS. Buka halaman ini melalui HTTPS.';
            return;
        }

        try {
            const ndef = new NDEFReader();
            await ndef.scan();

            btnScan.disabled = true;
            btnScan.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Menunggu...';
            statusEl.textContent = 'Dekatkan kartu NFC sekarang...';

            ndef.addEventListener('reading', ({ serialNumber, message }) => {
                let isi = '';
                for (const record of message.records) {
                    const decoded = decodeRecord(record);
                    if (decoded) isi += decoded;
                }

                const identifier = (serialNumber && serialNumber !== '') ? serialNumber : isi;

                if (!identifier) {
                    statusEl.textContent = 'Kartu terdeteksi tapi kosong. Coba kartu lain.';
                    return;
                }

                // Isi form dengan identifier
                serialInput.value    = identifier;
                serialPrev.value     = identifier;
                serialText.textContent = identifier;
                serialDisp.classList.add('has-value');

                statusEl.textContent = 'Kartu berhasil dibaca!';
                btnScan.disabled = false;
                btnScan.innerHTML = '<i class="mdi mdi-check-circle me-2"></i>Kartu Terbaca';

                // Focus ke input nama
                document.getElementById('namaMahasiswa').focus();

                // Debug
                console.log('Registered identifier:', identifier);
            }, { once: true }); // Hanya baca sekali

            ndef.addEventListener('readingerror', () => {
                statusEl.textContent = 'Gagal membaca kartu. Coba dekatkan lagi.';
            });

        } catch (err) {
            console.error('NFC scan() error:', err);
            if (err.name === 'NotAllowedError') {
                statusEl.textContent = 'Izin NFC ditolak. Periksa pengaturan browser.';
            } else if (err.name === 'NotSupportedError') {
                statusEl.textContent = 'NFC tidak tersedia di perangkat ini.';
            } else {
                statusEl.textContent = 'Error: ' + err.message;
            }
            btnScan.disabled = false;
            btnScan.innerHTML = '<i class="mdi mdi-nfc-search-variant me-2"></i>Scan Kartu';
        }
    }
</script>
@endpush
