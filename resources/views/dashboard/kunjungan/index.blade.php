@extends('layouts.dashboard')

@section('title', 'Kunjungan Toko')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="bg-gradient-primary me-2 text-white page-title-icon">
            <i class="mdi mdi-storefront"></i>
        </span> Kunjungan Toko
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kunjungan Toko</li>
        </ul>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-xl-7">
        <div class="mb-4 card">
            <div class="card-body">
                <h4 class="mb-3 card-title">Daftar Toko</h4>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Nama Toko</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Accuracy</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stores as $store)
                                <tr>
                                    <td>{{ $store->barcode }}</td>
                                    <td>{{ $store->nama_toko }}</td>
                                    <td>{{ $store->latitude }}</td>
                                    <td>{{ $store->longitude }}</td>
                                    <td>{{ $store->accuracy }} m</td>
                                    <td>
                                        <button type="button" class="btn-outline-primary btn btn-sm btn-print-barcode"
                                                data-store-id="{{ $store->id }}">
                                            <i class="mdi mdi-printer"></i> Cetak Barcode
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted text-center">Belum ada toko tersimpan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mb-4 card">
            <div class="card-body">
                <h4 class="mb-3 card-title">Tambah Store Baru</h4>
                <form id="store-form" method="POST" action="{{ route('stores.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_toko" class="form-label">Nama Toko</label>
                        <input type="text" id="nama_toko" name="nama_toko" class="form-control @error('nama_toko') is-invalid @enderror"
                               value="{{ old('nama_toko') }}" placeholder="Masukkan nama toko">
                        @error('nama_toko')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 row g-2">
                        <div class="col-sm-6">
                            <button type="button" id="btn-get-store-location" class="w-100 btn btn-primary">
                                <i class="me-1 mdi mdi-map-marker-radius"></i> Ambil Lokasi
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="w-100 btn btn-success">
                                <i class="me-1 mdi-content-save mdi"></i> Simpan Toko
                            </button>
                        </div>
                    </div>

                    <div id="store-location-preview" class="mb-3 d-none">
                        <div class="mb-2 alert alert-info">
                            <strong>Koordinat toko berhasil ditangkap.</strong>
                        </div>
                        <dl class="mb-0 row">
                            <dt class="col-sm-4">Latitude</dt>
                            <dd class="col-sm-8" id="store-latitude-preview"></dd>
                            <dt class="col-sm-4">Longitude</dt>
                            <dd class="col-sm-8" id="store-longitude-preview"></dd>
                            <dt class="col-sm-4">Accuracy</dt>
                            <dd class="col-sm-8" id="store-accuracy-preview"></dd>
                        </dl>
                    </div>

                    <input type="hidden" name="latitude" id="store-latitude">
                    <input type="hidden" name="longitude" id="store-longitude">
                    <input type="hidden" name="accuracy" id="store-accuracy">

                    <div id="store-location-error" class="alert alert-danger d-none"></div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="mb-4 card">
            <div class="card-body">
                <h4 class="mb-3 card-title">Check-in Kunjungan</h4>
                <div class="mb-3">
                    <button type="button" id="btn-start-scan" class="mb-3 w-100 btn btn-primary">
                        <i class="me-1 mdi mdi-barcode-scan"></i> Scan Barcode
                    </button>
                    <div id="scanner-box" class="border rounded overflow-hidden d-none" style="min-height: 320px; position: relative;">
                        <div id="reader" style="width: 100%; height: 320px;"></div>
                        <div id="scanner-overlay" class="top-0 position-absolute d-flex align-items-center justify-content-center w-100 h-100 start-0" style="background: rgba(0,0,0,.55); color: #fff; display: none;">
                            <div class="text-center">
                                <div class="mb-3 spinner-border text-light" role="status"></div>
                                <div id="scanner-overlay-msg">Menyiapkan kamera…</div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="btn-stop-scan" class="mt-2 btn-outline-secondary w-100 btn d-none">
                        <i class="me-1 mdi-stop-circle-outline mdi"></i> Hentikan Scan
                    </button>

                    {{-- Manual barcode fallback --}}
                    <div class="mt-3">
                        <button type="button" id="btn-toggle-manual" class="btn btn-link btn-sm p-0 text-decoration-none">
                            <i class="mdi mdi-keyboard me-1"></i>
                            <span id="toggle-manual-label">Barcode sulit dipindai? Input manual</span>
                        </button>
                        <div id="manual-barcode-box" class="mt-2 d-none">
                            <div class="input-group">
                                <input type="text" id="manual-barcode-input" class="form-control"
                                       placeholder="Ketik atau tempel kode barcode…"
                                       autocomplete="off" autocorrect="off" spellcheck="false">
                                <button type="button" id="btn-manual-search" class="btn btn-primary">
                                    <i class="mdi mdi-magnify"></i> Cari Toko
                                </button>
                            </div>
                            <div class="form-text text-muted">Masukkan barcode toko secara manual lalu tekan <strong>Cari Toko</strong>.</div>
                        </div>
                    </div>
                </div>
                <div id="scan-error" class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label class="form-label">Barcode yang dipindai</label>
                    <input id="scanned-barcode" type="text" class="form-control" readonly>
                </div>
                <div id="store-details-panel" class="mb-3 p-3 border rounded d-none">
                    <h5 class="mb-3">Detail Toko</h5>
                    <div class="mb-2"><strong>Nama Toko:</strong> <span id="detail-nama-toko"></span></div>
                    <div class="mb-2"><strong>Barcode:</strong> <span id="detail-barcode"></span></div>
                    <div class="mb-2"><strong>Latitude:</strong> <span id="detail-latitude"></span></div>
                    <div class="mb-2"><strong>Longitude:</strong> <span id="detail-longitude"></span></div>
                    <div class="mb-2"><strong>Accuracy:</strong> <span id="detail-accuracy"></span> m</div>
                </div>

                <div class="mb-3">
                    <button type="button" id="btn-get-sales-location" class="w-100 btn btn-secondary">
                        <i class="me-1 mdi mdi-crosshairs-gps"></i> Ambil Lokasi Sales
                    </button>
                </div>

                <div id="sales-location-preview" class="mb-3 d-none">
                    <div class="mb-2 alert alert-info">
                        <strong>Lokasi sales berhasil ditangkap.</strong>
                    </div>
                    <dl class="mb-0 row">
                        <dt class="col-sm-5">Latitude</dt>
                        <dd class="col-sm-7" id="sales-latitude-preview"></dd>
                        <dt class="col-sm-5">Longitude</dt>
                        <dd class="col-sm-7" id="sales-longitude-preview"></dd>
                        <dt class="col-sm-5">Accuracy</dt>
                        <dd class="col-sm-7" id="sales-accuracy-preview"></dd>
                    </dl>
                </div>

                <div id="checkin-error" class="alert alert-danger d-none"></div>
                <button type="button" id="btn-submit-visit" class="w-100 btn btn-success d-none">
                    <i class="me-1 mdi mdi-check-bold"></i> Submit Kunjungan
                </button>

                <div id="visit-result" class="mt-3 border-success card d-none">
                    <div class="card-body">
                        <h5 id="visit-result-title" class="card-title"></h5>
                        <div class="row">
                            <div class="col-sm-6"><strong>Jarak</strong></div>
                            <div class="text-end col-sm-6" id="result-distance"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"><strong>Ambang Efektif</strong></div>
                            <div class="text-end col-sm-6" id="result-threshold"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"><strong>Status</strong></div>
                            <div class="text-end col-sm-6" id="result-status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 card-title">Riwayat Kunjungan</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Toko</th>
                                <th>Sales</th>
                                <th>Jarak (m)</th>
                                <th>Status</th>
                                <th>Waktu Kunjungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($visits as $visit)
                                <tr>
                                    <td>{{ $visit->store->nama_toko }}</td>
                                    <td>{{ optional($visit->user)->name ?? 'Unknown' }}</td>
                                    <td>{{ number_format($visit->distance_meters, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $visit->status === 'diterima' ? 'success' : 'danger' }}">
                                            {{ strtoupper($visit->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $visit->visited_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted text-center">Belum ada riwayat kunjungan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $visits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="barcodeModalLabel">Cetak Barcode Toko</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="text-center modal-body">
                <div id="barcode-svg"></div>
                <p class="mt-3" id="barcode-store-name"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" id="btn-print-barcode" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            return reject(new Error('Geolocation tidak didukung oleh browser ini.'));
        }

        let bestResult = null;
        const startTime = Date.now();

        const watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;

                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                }

                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }

                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    if (bestResult) resolve(bestResult);
                    else reject(new Error('Timeout: tidak dapat posisi'));
                }
            },
            (error) => {
                navigator.geolocation.clearWatch(watchId);
                reject(error);
            },
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}

function createAlert(container, message, type = 'danger') {
    container.textContent = message;
    container.className = `alert alert-${type}`;
    container.classList.remove('d-none');
}

function hideAlert(container) {
    container.classList.add('d-none');
}

function fillStorePreview(position) {
    document.getElementById('store-latitude-preview').textContent = position.coords.latitude.toFixed(8);
    document.getElementById('store-longitude-preview').textContent = position.coords.longitude.toFixed(8);
    document.getElementById('store-accuracy-preview').textContent = `${position.coords.accuracy.toFixed(1)} m`;
    document.getElementById('store-location-preview').classList.remove('d-none');
}

function fillSalesPreview(position) {
    document.getElementById('sales-latitude-preview').textContent = position.coords.latitude.toFixed(8);
    document.getElementById('sales-longitude-preview').textContent = position.coords.longitude.toFixed(8);
    document.getElementById('sales-accuracy-preview').textContent = `${position.coords.accuracy.toFixed(1)} m`;
    document.getElementById('sales-location-preview').classList.remove('d-none');
}

function updateSubmitButton() {
    const hasStore = !!document.getElementById('scanned-store-id')?.value;
    const hasSales = !!document.getElementById('sales-latitude')?.value;
    const button = document.getElementById('btn-submit-visit');

    if (hasStore && hasSales) {
        button.classList.remove('d-none');
    } else {
        button.classList.add('d-none');
    }
}

function setVisitResult(status, distance, threshold) {
    const card = document.getElementById('visit-result');
    const title = document.getElementById('visit-result-title');
    const statusEl = document.getElementById('result-status');
    const distanceEl = document.getElementById('result-distance');
    const thresholdEl = document.getElementById('result-threshold');

    const accepted = status === 'diterima';
    card.classList.remove('border-success', 'border-danger');
    card.classList.add(accepted ? 'border-success' : 'border-danger');
    title.textContent = accepted ? '✅ DITERIMA' : '❌ DITOLAK';
    statusEl.textContent = status.toUpperCase();
    distanceEl.textContent = `${distance} m`;
    thresholdEl.textContent = `${threshold} m`;
    card.classList.remove('d-none');
}

function renderBarcode(code, storeName) {
    const svgContainer = document.getElementById('barcode-svg');
    svgContainer.innerHTML = '<svg id="barcode-render"></svg>';
    JsBarcode('#barcode-render', code, {
        format: 'CODE128',
        displayValue: true,
        fontSize: 18,
        height: 70,
    });
    document.getElementById('barcode-store-name').textContent = storeName;
}

function buildUrl(path) {
    return `${window.location.origin}${path}`;
}

function startScan() {
    const scanBox = document.getElementById('scanner-box');
    const overlay = document.getElementById('scanner-overlay');
    const overlayMsg = document.getElementById('scanner-overlay-msg');
    const errorBox = document.getElementById('scan-error');

    if (!window.Html5Qrcode) {
        createAlert(errorBox, 'Library scan tidak tersedia.');
        return;
    }

    const html5QrCode = new Html5Qrcode('reader');
    const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

    scanBox.classList.remove('d-none');
    overlay.style.display = 'flex';
    overlayMsg.textContent = 'Membuka kamera...';
    hideAlert(errorBox);

    html5QrCode.start(
        { facingMode: 'environment' },
        qrConfig,
        async (decodedText) => {
            html5QrCode.stop().catch(() => {});
            scanBox.classList.add('d-none');
            document.getElementById('btn-stop-scan').classList.add('d-none');
            document.getElementById('scanned-barcode').value = decodedText;
            await loadStoreByBarcode(decodedText);
        },
        (errorMessage) => {
            overlayMsg.textContent = 'Mencari barcode...';
        }
    ).then(() => {
        document.getElementById('btn-stop-scan').classList.remove('d-none');
    }).catch((err) => {
        createAlert(errorBox, 'Tidak dapat mengakses kamera: ' + err.message);
        scanBox.classList.add('d-none');
    });

    document.getElementById('btn-stop-scan').onclick = async () => {
        await html5QrCode.stop().catch(() => {});
        scanBox.classList.add('d-none');
        document.getElementById('btn-stop-scan').classList.add('d-none');
    };
}

async function loadStoreByBarcode(barcode) {
    const errorBox = document.getElementById('scan-error');
    hideAlert(errorBox);
    const storePanel = document.getElementById('store-details-panel');

    try {
        const response = await fetch(buildUrl(`/stores/barcode/${encodeURIComponent(barcode)}`));
        if (!response.ok) {
            throw new Error('Toko tidak ditemukan');
        }

        const data = await response.json();
        document.getElementById('detail-nama-toko').textContent = data.nama_toko;
        document.getElementById('detail-barcode').textContent = data.barcode;
        document.getElementById('detail-latitude').textContent = data.latitude;
        document.getElementById('detail-longitude').textContent = data.longitude;
        document.getElementById('detail-accuracy').textContent = data.accuracy;

        if (!document.getElementById('scanned-store-id')) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.id = 'scanned-store-id';
            hidden.value = data.id;
            document.getElementById('store-details-panel').appendChild(hidden);
        } else {
            document.getElementById('scanned-store-id').value = data.id;
        }

        storePanel.classList.remove('d-none');
        updateSubmitButton();
    } catch (error) {
        createAlert(errorBox, 'Scan gagal: ' + error.message);
        storePanel.classList.add('d-none');
    }
}

async function capturePosition(target, previewFnc, errorElement) {
    hideAlert(errorElement);
    try {
        const position = await getAccuratePosition(50, 20000);
        previewFnc(position);
        if (target === 'store') {
            document.getElementById('store-latitude').value = position.coords.latitude;
            document.getElementById('store-longitude').value = position.coords.longitude;
            document.getElementById('store-accuracy').value = position.coords.accuracy;
        } else {
            document.getElementById('sales-latitude').value = position.coords.latitude;
            document.getElementById('sales-longitude').value = position.coords.longitude;
            document.getElementById('sales-accuracy').value = position.coords.accuracy;
        }
        updateSubmitButton();
    } catch (err) {
        createAlert(errorElement, err.message || 'Gagal mendapatkan lokasi.');
    }
}

async function submitVisit() {
    const errorBox = document.getElementById('checkin-error');
    hideAlert(errorBox);

    const storeId = document.getElementById('scanned-store-id')?.value;
    const latitude = document.getElementById('sales-latitude')?.value;
    const longitude = document.getElementById('sales-longitude')?.value;
    const accuracy = document.getElementById('sales-accuracy')?.value;

    if (!storeId || !latitude || !longitude || !accuracy) {
        createAlert(errorBox, 'Harap pilih toko dan tangkap lokasi sales terlebih dahulu.');
        return;
    }

    try {
        const response = await fetch(buildUrl('/kunjungan-toko/scan'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                store_id: storeId,
                sales_latitude: latitude,
                sales_longitude: longitude,
                sales_accuracy: accuracy,
            }),
        });

        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.message || 'Gagal menyimpan kunjungan.');
        }

        setVisitResult(result.status, result.distance_meters, result.effective_threshold);

        // Reload so the server-rendered Riwayat Kunjungan table shows the new visit.
        setTimeout(() => window.location.reload(), 1800);
    } catch (error) {
        createAlert(errorBox, error.message || 'Gagal submit kunjungan.');
    }
}

function toneDownResultCard() {
    const card = document.getElementById('visit-result');
    card.classList.add('d-none');
}

function openBarcodeModal(storeId) {
    const errorBox = document.getElementById('scan-error');
    hideAlert(errorBox);
    fetch(buildUrl(`/stores/${storeId}/barcode`))
        .then((response) => {
            if (!response.ok) {
                throw new Error('Gagal memuat barcode.');
            }
            return response.json();
        })
        .then((data) => {
            renderBarcode(data.barcode, data.nama_toko);
            const modal = new bootstrap.Modal(document.getElementById('barcodeModal'));
            modal.show();
        })
        .catch((error) => {
            createAlert(errorBox, error.message);
        });
}

function generatePrintableBarcode() {
    const svg = document.querySelector('#barcode-svg svg');
    if (!svg) return;
    const newWindow = window.open('', '_blank');
    newWindow.document.write(`<!doctype html><html><head><title>Print Barcode</title></head><body>${svg.outerHTML}</body></html>`);
    newWindow.document.close();
    newWindow.focus();
    newWindow.print();
    newWindow.close();
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btn-get-store-location').addEventListener('click', () => {
        capturePosition('store', fillStorePreview, document.getElementById('store-location-error'));
    });

    document.getElementById('btn-get-sales-location').addEventListener('click', () => {
        capturePosition('sales', fillSalesPreview, document.getElementById('checkin-error'));
    });

    document.getElementById('btn-submit-visit').addEventListener('click', submitVisit);

    document.getElementById('btn-start-scan').addEventListener('click', startScan);
    document.getElementById('btn-print-barcode').addEventListener('click', generatePrintableBarcode);

    document.querySelectorAll('.btn-print-barcode').forEach((button) => {
        button.addEventListener('click', () => openBarcodeModal(button.dataset.storeId));
    });

    // Manual barcode fallback
    const btnToggleManual = document.getElementById('btn-toggle-manual');
    const manualBox = document.getElementById('manual-barcode-box');
    const toggleLabel = document.getElementById('toggle-manual-label');
    const manualInput = document.getElementById('manual-barcode-input');
    const btnManualSearch = document.getElementById('btn-manual-search');

    btnToggleManual.addEventListener('click', () => {
        const isHidden = manualBox.classList.toggle('d-none');
        toggleLabel.textContent = isHidden
            ? 'Barcode sulit dipindai? Input manual'
            : 'Tutup input manual';
        if (!isHidden) {
            manualInput.focus();
        }
    });

    async function triggerManualSearch() {
        const barcode = manualInput.value.trim();
        if (!barcode) {
            manualInput.classList.add('is-invalid');
            manualInput.focus();
            return;
        }
        manualInput.classList.remove('is-invalid');
        document.getElementById('scanned-barcode').value = barcode;
        btnManualSearch.disabled = true;
        btnManualSearch.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mencari…';
        await loadStoreByBarcode(barcode);
        btnManualSearch.disabled = false;
        btnManualSearch.innerHTML = '<i class="mdi mdi-magnify"></i> Cari Toko';
    }

    btnManualSearch.addEventListener('click', triggerManualSearch);

    manualInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            triggerManualSearch();
        }
    });

    const hiddenSalesFields = ['sales-latitude', 'sales-longitude', 'sales-accuracy'];
    hiddenSalesFields.forEach((id) => {
        const field = document.createElement('input');
        field.type = 'hidden';
        field.id = id;
        document.getElementById('store-details-panel').appendChild(field);
    });

    const storeHidden = document.createElement('input');
    storeHidden.type = 'hidden';
    storeHidden.id = 'scanned-store-id';
    document.getElementById('store-details-panel').appendChild(storeHidden);
});
</script>
@endpush
