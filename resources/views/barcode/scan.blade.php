@extends('layouts.dashboard')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-barcode-scan"></i>
        </span> Item Barcode Scanner
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Inventory</a></li>
            <li class="breadcrumb-item active" aria-current="page">Scan Item</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary mb-4">
                    <i class="mdi mdi-camera-plus me-2"></i> Scan Product Barcode
                </h4>

                <div id="scanner-container" class="bg-dark rounded border" style="position:relative; min-height: 320px; overflow: hidden;">
                    <div id="reader" style="width:100%;"></div>
                    <div id="scan-overlay" style="
                        display:none; position:absolute; inset:0;
                        align-items:center; justify-content:center;
                        background:rgba(0,0,0,0.55); color:#fff; font-size:1.1rem; text-align:center; padding:1rem;">
                        <div>
                            <div class="spinner-border text-primary mb-3" role="status"></div>
                            <div id="scan-overlay-msg">Starting camera…</div>
                        </div>
                    </div>
                </div>

                <div id="camera-error" class="alert alert-warning mt-3 d-none">
                    <strong><i class="mdi mdi-camera-off me-1"></i> Camera unavailable.</strong>
                    Enter the barcode manually below.
                </div>

                {{-- Manual fallback --}}
                <div class="input-group mt-3">
                    <input id="manual-input" type="text" class="form-control"
                           placeholder="Or type barcode / item code manually…">
                    <button id="manual-submit" class="btn btn-outline-primary">
                        <i class="mdi mdi-magnify"></i> Lookup
                    </button>
                </div>

                <div id="error-message" class="alert alert-danger mt-3 d-none"></div>

                <div id="result-panel" class="mt-4 d-none">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-primary border-bottom pb-2">Item Details</h5>
                            <div class="row py-2">
                                <div class="col-sm-4 font-weight-bold">Item ID:</div>
                                <div id="res-id" class="col-sm-8 text-muted"></div>
                            </div>
                            <div class="row py-2 border-top">
                                <div class="col-sm-4 font-weight-bold">Item Name:</div>
                                <div id="res-name" class="col-sm-8 font-weight-bold text-dark"></div>
                            </div>
                            <div class="row py-2 border-top">
                                <div class="col-sm-4 font-weight-bold">Price:</div>
                                <div id="res-price" class="col-sm-8 text-success font-weight-bold fs-5"></div>
                            </div>
                            <button id="btn-scan-again" class="btn btn-gradient-primary mt-4 w-100 fw-bold py-3">
                                <i class="mdi mdi-refresh me-2"></i> SCAN AGAIN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Pinned version for stability --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const errorEl      = document.getElementById('error-message');
    const cameraErrEl  = document.getElementById('camera-error');
    const resultPanel  = document.getElementById('result-panel');
    const btnScanAgain = document.getElementById('btn-scan-again');
    const manualInput  = document.getElementById('manual-input');
    const manualSubmit = document.getElementById('manual-submit');
    const overlay      = document.getElementById('scan-overlay');
    const overlayMsg   = document.getElementById('scan-overlay-msg');

    let scanner = null;
    let scanning = false;

    // ── responsive scan box ──────────────────────────────────────────────────
    function qrboxFn(viewfinderWidth, viewfinderHeight) {
        const size = Math.floor(Math.min(viewfinderWidth, viewfinderHeight) * 0.7);
        return { width: size, height: size };
    }

    const qrConfig = { fps: 15, qrbox: qrboxFn, aspectRatio: 1.0 };

    // ── scanner lifecycle helpers ────────────────────────────────────────────
    function showOverlay(msg) {
        overlayMsg.textContent = msg;
        overlay.style.display = 'flex';
    }
    function hideOverlay() {
        overlay.style.display = 'none';
    }

    async function stopScanner() {
        if (scanner && scanning) {
            try { await scanner.stop(); } catch (_) {}
            scanning = false;
        }
    }

    // Try cameras in order: back → front → any
    async function startScanner() {
        if (scanning) return;

        showOverlay('Starting camera…');
        errorEl.classList.add('d-none');

        if (!scanner) {
            scanner = new Html5Qrcode('reader');
        }

        const attempts = [
            { facingMode: 'environment' },
            { facingMode: 'user' },
        ];

        for (const cameraConstraint of attempts) {
            try {
                await scanner.start(cameraConstraint, qrConfig, onScanSuccess, () => {});
                scanning = true;
                hideOverlay();
                cameraErrEl.classList.add('d-none');
                return;
            } catch (_) {
                // try next
            }
        }

        // All camera attempts failed
        hideOverlay();
        cameraErrEl.classList.remove('d-none');
    }

    // ── scan success ─────────────────────────────────────────────────────────
    const playBeep = () => {
        const audio = new Audio('/audio/beep.mp3');
        audio.play().catch(e => console.warn('Audio play failed:', e));
    };

    const onScanSuccess = async (decodedText) => {
        playBeep();
        await stopScanner();
        await lookupItem(decodedText.trim());
    };

    // ── API lookup ───────────────────────────────────────────────────────────
    async function lookupItem(barcode) {
        if (!barcode) return;
        showOverlay('Looking up item…');
        errorEl.classList.add('d-none');

        try {
            const response = await fetch(`/api/items/${encodeURIComponent(barcode)}`);
            const data = await response.json();
            hideOverlay();

            if (response.ok) {
                document.getElementById('res-id').textContent = data.id;
                document.getElementById('res-name').textContent = data.name;
                document.getElementById('res-price').textContent =
                    'Rp ' + new Intl.NumberFormat('id-ID').format(data.price);

                resultPanel.classList.remove('d-none');
                errorEl.classList.add('d-none');
                manualInput.value = '';
            } else {
                showError(data.message || 'Item not found');
            }
        } catch (err) {
            hideOverlay();
            showError('Could not reach server. Check your connection.');
        }
    }

    function showError(msg) {
        errorEl.textContent = msg;
        errorEl.classList.remove('d-none');
        resultPanel.classList.add('d-none');
    }

    // ── UI event handlers ────────────────────────────────────────────────────
    btnScanAgain.addEventListener('click', async () => {
        resultPanel.classList.add('d-none');
        errorEl.classList.add('d-none');
        await startScanner();
    });

    manualSubmit.addEventListener('click', async () => {
        const code = manualInput.value.trim();
        if (!code) { manualInput.focus(); return; }
        await stopScanner();
        await lookupItem(code);
    });

    manualInput.addEventListener('keydown', async (e) => {
        if (e.key === 'Enter') manualSubmit.click();
    });

    // ── boot ─────────────────────────────────────────────────────────────────
    startScanner();
});
</script>
@endpush
@endsection
