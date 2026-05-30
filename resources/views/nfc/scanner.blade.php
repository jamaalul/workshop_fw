@extends('layouts.dashboard')

@section('title', 'NFC Scanner - Absensi')

@push('styles')
<style>
    /* ── Scanner Card ── */
    .nfc-scanner-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        text-align: center;
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.4);
        position: relative;
        overflow: hidden;
    }
    .nfc-scanner-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        animation: pulse-bg 4s ease-in-out infinite;
    }
    @keyframes pulse-bg {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 1; }
    }

    .nfc-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .btn-scan {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        padding: 14px 40px;
        border-radius: 50px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 1;
    }
    .btn-scan:hover {
        background: rgba(255,255,255,0.35);
        border-color: rgba(255,255,255,0.7);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    .btn-scan:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* ── Status badge ── */
    .scan-status {
        margin-top: 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    /* ── Result Card ── */
    .result-card {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .result-card.result-success {
        border-left: 5px solid #10b981;
    }
    .result-card.result-warning {
        border-left: 5px solid #f59e0b;
    }
    .result-card.result-error {
        border-left: 5px solid #ef4444;
    }

    .result-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .result-icon.icon-success {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }
    .result-icon.icon-warning {
        background: rgba(245, 158, 11, 0.12);
        color: #f59e0b;
    }
    .result-icon.icon-error {
        background: rgba(239, 68, 68, 0.12);
        color: #ef4444;
    }

    /* ── Slide-in animation ── */
    .result-appear {
        animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Scan pulse ring ── */
    .scanning-ring {
        display: none;
        width: 80px;
        height: 80px;
        border: 3px solid rgba(255,255,255,0.5);
        border-radius: 50%;
        margin: 1rem auto;
        position: relative;
        z-index: 1;
    }
    .scanning-ring::after {
        content: '';
        position: absolute;
        inset: -8px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        animation: ring-pulse 1.5s ease-out infinite;
    }
    @keyframes ring-pulse {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    /* ── Quick Links ── */
    .quick-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        background: #f8fafc;
        text-decoration: none;
        color: #334155;
        font-weight: 600;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
    }
    .quick-link:hover {
        background: #f1f5f9;
        transform: translateX(4px);
        color: #6366f1;
        border-color: #c7d2fe;
    }
    .quick-link .link-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
            <i class="mdi mdi-nfc"></i>
        </span> NFC Scanner — Absensi
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">NFC Scanner</li>
        </ol>
    </nav>
</div>

<div class="row">
    {{-- Scanner --}}
    <div class="col-lg-7 mb-4">
        <div class="nfc-scanner-card">
            <i class="mdi mdi-nfc-tap nfc-icon"></i>
            <h4 class="fw-bold mb-2" style="position:relative;z-index:1;">Tap Kartu NFC</h4>
            <p class="opacity-75 mb-3" style="position:relative;z-index:1;">
                Aktifkan NFC lalu dekatkan kartu ke perangkat Android Anda
            </p>
            <button class="btn btn-scan" id="btnScan" onclick="startScan()">
                <i class="mdi mdi-nfc-search-variant me-2"></i>Aktifkan NFC
            </button>
            <div class="scanning-ring" id="scanningRing"></div>
            <p class="scan-status" id="status">Belum aktif.</p>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="mdi mdi-lightning-bolt text-warning me-1"></i> Menu NFC
                </h5>
                <div class="d-flex flex-column gap-3">
                    <a href="/nfc/register" class="quick-link">
                        <div class="link-icon bg-primary bg-opacity-10 text-primary">
                            <i class="mdi mdi-card-plus"></i>
                        </div>
                        <div>
                            <div>Daftarkan Kartu Baru</div>
                            <small class="text-muted fw-normal">Scan & daftarkan kartu NFC mahasiswa</small>
                        </div>
                    </a>
                    <a href="/nfc/history" class="quick-link">
                        <div class="link-icon" style="background:rgba(16,185,129,0.1);color:#10b981;">
                            <i class="mdi mdi-history"></i>
                        </div>
                        <div>
                            <div>Riwayat Absensi</div>
                            <small class="text-muted fw-normal">Lihat semua data absensi mahasiswa</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Result area --}}
        <div id="hasil" class="mt-3"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const scanUrl   = "{{ route('nfc.scan') }}";

    /**
     * Safely decode a single NDEFRecord to a string.
     * Returns null if the record can't be decoded as text.
     */
    function decodeRecord(record) {
        try {
            // "text" records carry an encoding + language prefix byte
            if (record.recordType === 'text') {
                const view = new DataView(record.data.buffer, record.data.byteOffset, record.data.byteLength);
                const langLen = view.getUint8(0);           // first byte = language code length
                return new TextDecoder().decode(record.data.slice(1 + langLen));
            }
            // "url" records
            if (record.recordType === 'url') {
                return new TextDecoder().decode(record.data);
            }
            // generic / unknown — try raw decode
            if (record.data && record.data.byteLength > 0) {
                return new TextDecoder().decode(record.data);
            }
        } catch (_) { /* ignore decode failures */ }
        return null;
    }

    async function startScan() {
        const statusEl = document.getElementById('status');
        const btnScan  = document.getElementById('btnScan');
        const ring     = document.getElementById('scanningRing');

        /* ── Feature check ─────────────────────────── */
        if (!('NDEFReader' in window)) {
            statusEl.textContent = 'Browser tidak mendukung Web NFC. Gunakan Chrome Android 89+.';
            return;
        }

        /* ── HTTPS check ────────────────────────────── */
        if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
            statusEl.textContent = 'Web NFC memerlukan HTTPS. Buka halaman ini melalui HTTPS.';
            return;
        }

        try {
            const ndef = new NDEFReader();
            await ndef.scan();

            // Update UI ke mode scanning
            statusEl.textContent = 'NFC aktif ✅ Dekatkan kartu...';
            btnScan.disabled = true;
            btnScan.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Menunggu Kartu...';
            ring.style.display = 'block';

            /* ── Read event ──────────────────────────── */
            ndef.addEventListener('reading', async ({ serialNumber, message }) => {
                try {
                    // Decode all readable records
                    const parts = [];
                    for (const record of message.records) {
                        const decoded = decodeRecord(record);
                        if (decoded) parts.push(decoded);
                    }
                    const isi = parts.join('');

                    // Determine the identifier to send.
                    // Many NFC tags return serialNumber = "" — use decoded content as fallback.
                    const identifier = (serialNumber && serialNumber !== '')
                        ? serialNumber
                        : isi;

                    // Debug log — check Chrome DevTools > Console via Remote Debugging
                    console.group('🔖 NFC Read');
                    console.log('serialNumber :', JSON.stringify(serialNumber));
                    console.log('records      :', message.records.length);
                    console.log('decoded isi  :', isi);
                    console.log('identifier   :', identifier);
                    console.groupEnd();

                    if (!identifier) {
                        statusEl.textContent = 'Kartu terdeteksi tapi tidak ada data. Coba lagi.';
                        return;
                    }

                    statusEl.textContent = 'Kartu terdeteksi! Memproses...';

                    // Kirim ke backend
                    const response = await fetch(scanUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            serial_number: identifier,
                            isi: isi,
                        }),
                    });

                    // Handle non-JSON responses (e.g. 419 CSRF, 302 redirect, 500)
                    const contentType = response.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', response.status, text.substring(0, 500));
                        statusEl.textContent = `Server error (${response.status}). Coba refresh halaman.`;
                        return;
                    }

                    if (!response.ok) {
                        const errData = await response.json();
                        console.error('Server error:', errData);
                        // Laravel validation errors
                        if (errData.errors) {
                            const msgs = Object.values(errData.errors).flat().join(', ');
                            statusEl.textContent = 'Validasi gagal: ' + msgs;
                        } else {
                            statusEl.textContent = errData.message || `Error ${response.status}`;
                        }
                        return;
                    }

                    const data = await response.json();
                    displayResult(data);
                    statusEl.textContent = 'NFC aktif ✅ Dekatkan kartu lain...';

                } catch (err) {
                    console.error('Error in reading handler:', err);
                    statusEl.textContent = 'Gagal memproses kartu: ' + err.message;
                }
            });

            /* ── Read-error event ────────────────────── */
            ndef.addEventListener('readingerror', (event) => {
                console.error('NFC readingerror:', event);
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
        }
    }

    function displayResult(data) {
        const hasilEl = document.getElementById('hasil');

        let iconClass = 'icon-success';
        let cardClass = 'result-success';
        let icon      = 'mdi-check-circle';

        if (data.status === 'warning') {
            iconClass = 'icon-warning';
            cardClass = 'result-warning';
            icon      = 'mdi-alert-circle';
        } else if (data.status === 'error') {
            iconClass = 'icon-error';
            cardClass = 'result-error';
            icon      = 'mdi-close-circle';
        }

        let details = '';
        if (data.nama) {
            details += `<div class="fw-bold fs-5">${data.nama}</div>`;
        }
        if (data.waktu) {
            details += `<small class="text-muted"><i class="mdi mdi-clock-outline me-1"></i>${data.waktu}</small>`;
        }

        hasilEl.innerHTML = `
            <div class="card result-card ${cardClass} result-appear">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="result-icon ${iconClass}">
                        <i class="mdi ${icon}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-semibold text-uppercase mb-1">${data.status === 'success' ? 'Berhasil' : data.status === 'warning' ? 'Peringatan' : 'Error'}</div>
                        <div class="mb-1">${data.message}</div>
                        ${details}
                    </div>
                </div>
            </div>`;
    }
</script>
@endpush
