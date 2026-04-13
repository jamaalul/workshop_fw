@extends('layouts.dashboard')

@section('title', 'Tambah Customer (Blob)')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
            <i class="mdi mdi-camera"></i>
        </span> Tambah Customer — Penyimpanan Blob
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Data Customer</a></li>
            <li class="breadcrumb-item active">Tambah Customer (Blob)</li>
        </ol>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title mb-1">Form Tambah Customer</h4>
                <p class="text-muted small mb-4">Foto akan disimpan sebagai <strong>BLOB</strong> langsung di dalam database.</p>

                <form action="{{ route('customer.store.blob') }}" method="POST" id="blobForm">
                    @csrf

                    {{-- Hidden field to carry the base64 image data --}}
                    <input type="hidden" name="photo_data" id="photo_data">

                    <div class="row">
                        {{-- ── LEFT: Customer Info ── --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                       id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="telepon" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                       id="telepon" name="telepon" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx">
                                @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- ── RIGHT: Camera ── --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto Customer (Kamera)</label>

                            {{-- Camera preview --}}
                            <div id="camera-container" class="rounded overflow-hidden border bg-dark position-relative" style="height:220px;">
                                <video id="video" autoplay playsinline class="w-100 h-100" style="object-fit:cover; display:none;"></video>
                                <canvas id="canvas" class="w-100 h-100" style="object-fit:cover; display:none;"></canvas>
                                <div id="camera-placeholder" class="d-flex flex-column align-items-center justify-content-center h-100 text-white">
                                    <i class="mdi mdi-camera-off" style="font-size:3rem;"></i>
                                    <p class="mt-2 mb-0 small">Kamera belum aktif</p>
                                </div>
                                {{-- Flash overlay --}}
                                <div id="flash-overlay" class="position-absolute top-0 start-0 w-100 h-100 bg-white" style="opacity:0;pointer-events:none;transition:opacity 0.3s;"></div>
                            </div>

                            {{-- Preview captured --}}
                            <div id="preview-container" class="mt-2 d-none text-center">
                                <img id="preview-img" src="" alt="Preview" class="img-thumbnail rounded shadow-sm" style="max-height:180px;">
                                <p class="text-success small mt-1 mb-0"><i class="mdi mdi-check-circle me-1"></i>Foto berhasil diambil</p>
                            </div>

                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                <button type="button" id="btn-start-camera" class="btn btn-outline-primary btn-sm">
                                    <i class="mdi mdi-camera me-1"></i> Aktifkan Kamera
                                </button>
                                <button type="button" id="btn-capture" class="btn btn-primary btn-sm" disabled>
                                    <i class="mdi mdi-camera-iris me-1"></i> Ambil Foto
                                </button>
                                <button type="button" id="btn-retake" class="btn btn-outline-secondary btn-sm d-none">
                                    <i class="mdi mdi-refresh me-1"></i> Ulangi
                                </button>
                            </div>
                            <p class="text-muted small mt-2">Foto opsional. Bisa disimpan tanpa foto.</p>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gradient-primary btn-lg px-5 fw-bold rounded-pill shadow-sm" id="btn-save">
                            <i class="mdi mdi-content-save me-1"></i> Simpan Customer
                        </button>
                        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const video        = document.getElementById('video');
    const canvas       = document.getElementById('canvas');
    const previewImg   = document.getElementById('preview-img');
    const photoData    = document.getElementById('photo_data');
    const btnStart     = document.getElementById('btn-start-camera');
    const btnCapture   = document.getElementById('btn-capture');
    const btnRetake    = document.getElementById('btn-retake');
    const previewCont  = document.getElementById('preview-container');
    const placeholder  = document.getElementById('camera-placeholder');
    const flashOverlay = document.getElementById('flash-overlay');

    let stream = null;

    // Start Camera
    btnStart.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
            video.srcObject = stream;
            video.style.display = 'block';
            canvas.style.display = 'none';
            placeholder.style.display = 'none';
            previewCont.classList.add('d-none');
            btnCapture.disabled = false;
            btnRetake.classList.add('d-none');
            btnStart.innerHTML = '<i class="mdi mdi-camera-check me-1"></i> Kamera Aktif';
            btnStart.disabled = true;
            photoData.value = '';
        } catch (err) {
            alert('Tidak dapat mengakses kamera: ' + err.message);
        }
    });

    // Capture Photo
    btnCapture.addEventListener('click', () => {
        const w = video.videoWidth  || 640;
        const h = video.videoHeight || 480;
        canvas.width  = w;
        canvas.height = h;
        canvas.getContext('2d').drawImage(video, 0, 0, w, h);

        // Flash effect
        flashOverlay.style.opacity = '0.8';
        setTimeout(() => { flashOverlay.style.opacity = '0'; }, 300);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
        photoData.value = dataUrl;
        previewImg.src  = dataUrl;

        // Switch views
        video.style.display   = 'none';
        canvas.style.display  = 'block';
        previewCont.classList.remove('d-none');
        btnCapture.disabled = true;
        btnRetake.classList.remove('d-none');

        // Stop stream
        if (stream) stream.getTracks().forEach(t => t.stop());
        stream = null;
    });

    // Retake
    btnRetake.addEventListener('click', () => {
        canvas.style.display = 'none';
        placeholder.style.display = 'flex';
        previewCont.classList.add('d-none');
        btnCapture.disabled = true;
        btnRetake.classList.add('d-none');
        btnStart.innerHTML = '<i class="mdi mdi-camera me-1"></i> Aktifkan Kamera';
        btnStart.disabled  = false;
        photoData.value    = '';
    });
</script>
@endpush
