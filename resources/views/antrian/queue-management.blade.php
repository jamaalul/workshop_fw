@extends('layouts.dashboard')

@section('title', 'Queue Management')

@push('styles')
<style>
    /* ── Number badge ── */
    .queue-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .queue-number.waiting-num {
        background: rgba(99,102,241,0.15);
        border: 1px solid rgba(99,102,241,0.3);
        color: #6366f1;
    }
    .queue-number.late-num {
        background: rgba(239,68,68,0.12);
        border: 1px solid rgba(239,68,68,0.3);
        color: #ef4444;
    }

    /* ── Queue rows ── */
    .queue-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 8px;
        transition: background 0.15s;
        border-bottom: 1px solid #f1f5f9;
    }
    .queue-row:last-child { border-bottom: none; }
    .queue-row:hover { background: #f8fafc; }

    /* ── Current called card ── */
    .current-called-card {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 16px;
        padding: 1.5rem 2rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 8px 24px rgba(99,102,241,0.35);
    }

    .current-num-big {
        font-size: 3rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -2px;
    }

    .current-badge {
        background: rgba(255,255,255,0.2);
        border-radius: 999px;
        padding: 3px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 4px;
    }

    /* ── Recall btn ── */
    .btn-recall {
        background: #ef4444;
        border: none;
        color: white;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: background 0.15s, transform 0.1s;
    }
    .btn-recall:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-1px);
    }

    /* ── Toast ── */
    .toast-container { z-index: 1060; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
            <i class="mdi mdi-ticket-outline"></i>
        </span> Queue Management
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Queue Management</li>
        </ol>
    </nav>
</div>

{{-- Toast --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="actionToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMsg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

{{-- Top action bar --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h5 class="card-title mb-1">Kontrol Antrian</h5>
                    <small class="text-muted">Panggil nomor berikutnya secara berurutan</small>
                </div>
                <button class="btn btn-gradient-primary btn-lg px-4 fw-bold shadow"
                        id="btnPanggil"
                        onclick="panggilBerikutnya()">
                    <i class="mdi mdi-bullhorn me-2"></i> Panggil Berikutnya
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Currently Called --}}
    <div class="col-12 mb-4">
        <div class="current-called-card" id="currentCard">
            @if($current)
                <div class="current-num-big">{{ str_pad($current->number, 3, '0', STR_PAD_LEFT) }}</div>
                <div class="flex-grow-1">
                    <span class="current-badge">Sedang Dipanggil</span>
                    <div class="fw-bold fs-5">{{ $current->name }}</div>
                    <small class="opacity-75">
                        <i class="mdi mdi-clock-outline me-1"></i>
                        Dipanggil: {{ $current->called_at?->format('H:i:s') ?? '-' }}
                    </small>
                </div>
                <div>
                    <button class="btn btn-light text-success fw-bold shadow-sm" onclick="selesaikanAntrian({{ $current->id }})">
                        <i class="mdi mdi-check-circle me-1"></i> Selesai
                    </button>
                </div>
            @else
                <div class="current-num-big opacity-50">---</div>
                <div>
                    <span class="current-badge">Belum Ada</span>
                    <div class="fw-bold fs-5 opacity-75">Belum ada yang dipanggil</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Waiting List --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-clock-outline text-primary me-2"></i>Antrian Menunggu
                    </h5>
                    <span class="badge bg-primary rounded-pill" id="waitingCount">{{ $waiting->count() }}</span>
                </div>

                <div id="waitingList" style="min-height: 120px;">
                    @forelse($waiting as $q)
                        <div class="queue-row" data-id="{{ $q->id }}">
                            <div class="queue-number waiting-num">{{ str_pad($q->number, 3, '0', STR_PAD_LEFT) }}</div>
                            <div class="flex-grow-1 fw-semibold">{{ $q->name }}</div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5" id="noWaiting">
                            <i class="mdi mdi-check-circle-outline fs-2 d-block mb-2"></i>
                            Tidak ada antrian menunggu
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Late List --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-alert-circle-outline text-danger me-2"></i>Daftar Terlambat
                    </h5>
                    <span class="badge bg-danger rounded-pill" id="lateCount">{{ $late->count() }}</span>
                </div>

                <div id="lateList" style="min-height: 120px;">
                    @forelse($late as $q)
                        <div class="queue-row" data-id="{{ $q->id }}">
                            <div class="queue-number late-num">{{ str_pad($q->number, 3, '0', STR_PAD_LEFT) }}</div>
                            <div class="flex-grow-1 fw-semibold text-danger">{{ $q->name }}</div>
                            <button class="btn-recall"
                                    onclick="panggilTerlambat({{ $q->id }}, '{{ addslashes($q->number) }}', '{{ addslashes($q->name) }}')">
                                <i class="mdi mdi-bullhorn me-1"></i>Panggil Ulang
                            </button>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5" id="noLate">
                            <i class="mdi mdi-check-all fs-2 d-block mb-2"></i>
                            Tidak ada antrian terlambat
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SSE connection status bar --}}
<div class="d-flex align-items-center gap-2 mt-2">
    <div id="sseDot" style="width:8px;height:8px;border-radius:50%;background:#adb5bd;transition:background .3s;"></div>
    <small class="text-muted" id="sseStatus">Menghubungkan SSE...</small>
    <a href="{{ route('antrian.board') }}" target="_blank" class="ms-auto btn btn-sm btn-outline-secondary">
        <i class="mdi mdi-open-in-new me-1"></i>Buka Papan Antrian
    </a>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const toastEl   = document.getElementById('actionToast');
    const bsToast   = new bootstrap.Toast(toastEl, { delay: 3000 });

    function showToast(msg, type = 'success') {
        toastEl.className = `toast align-items-center text-white border-0 bg-${type}`;
        document.getElementById('toastMsg').textContent = msg;
        bsToast.show();
    }

    // ── Panggil berikutnya ───────────────────────────────────────────────────
    function panggilBerikutnya() {
        const btn = document.getElementById('btnPanggil');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memanggil...';

        fetch('{{ route('antrian.panggil') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('Antrian berikutnya berhasil dipanggil!');
            } else {
                showToast(data.message ?? 'Gagal memanggil antrian.', 'danger');
            }
        })
        .catch(() => showToast('Terjadi kesalahan jaringan.', 'danger'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="mdi mdi-bullhorn me-2"></i> Panggil Berikutnya';
        });
    }

    // ── Panggil terlambat ────────────────────────────────────────────────────
    function panggilTerlambat(id, number, name) {
        if (!confirm(`Panggil ulang antrian #${number} — ${name}?`)) return;

        fetch(`/queue-management/panggil-terlambat/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(`Antrian #${number} ${name} berhasil dipanggil ulang!`);
            } else {
                showToast(data.message ?? 'Gagal memanggil ulang.', 'danger');
            }
        })
        .catch(() => showToast('Terjadi kesalahan jaringan.', 'danger'));
    }

    // ── Selesaikan Antrian ───────────────────────────────────────────────────
    function selesaikanAntrian(id) {
        if (!confirm('Tandai antrian ini sebagai selesai?')) return;

        fetch(`/queue-management/selesai/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('Antrian berhasil diselesaikan!');
            } else {
                showToast(data.message ?? 'Gagal menyelesaikan antrian.', 'danger');
            }
        })
        .catch(() => showToast('Terjadi kesalahan jaringan.', 'danger'));
    }

    // ── Helpers ─────────────────────────────────────────────────────────────
    function pad(n) { return String(n).padStart(3, '0'); }

    // ── SSE — live update management view ──────────────────────────────────
    window.addEventListener('load', function() {
        const sseDot    = document.getElementById('sseDot');
        const sseStatus = document.getElementById('sseStatus');

        const source = new EventSource('{{ route('antrian.sse') }}');

        source.addEventListener('queue-update', function (e) {
            sseDot.style.background = '#4ade80';
            sseStatus.textContent   = 'SSE Terhubung';

            let data;
            try { data = JSON.parse(e.data); } catch (_) { return; }

            // ── Update current card ──
            const card = document.getElementById('currentCard');
            if (data.current) {
                card.innerHTML = `
                    <div class="current-num-big">${pad(data.current.number)}</div>
                    <div class="flex-grow-1">
                        <span class="current-badge">Sedang Dipanggil</span>
                        <div class="fw-bold fs-5">${data.current.name}</div>
                        <small class="opacity-75"><i class="mdi mdi-clock-outline me-1"></i>Baru dipanggil</small>
                    </div>
                    <div>
                        <button class="btn btn-light text-success fw-bold shadow-sm" onclick="selesaikanAntrian(${data.current.id})">
                            <i class="mdi mdi-check-circle me-1"></i> Selesai
                        </button>
                    </div>`;
            } else {
                card.innerHTML = `
                    <div class="current-num-big opacity-50">---</div>
                    <div>
                        <span class="current-badge">Belum Ada</span>
                        <div class="fw-bold fs-5 opacity-75">Belum ada yang dipanggil</div>
                    </div>`;
            }

            // ── Update waiting list ──
            const waitingList = document.getElementById('waitingList');
            document.getElementById('waitingCount').textContent = data.waiting.length;
            if (data.waiting.length === 0) {
                waitingList.innerHTML = `<div class="text-center text-muted py-5">
                    <i class="mdi mdi-check-circle-outline fs-2 d-block mb-2"></i>
                    Tidak ada antrian menunggu
                </div>`;
            } else {
                waitingList.innerHTML = data.waiting.map(q => `
                    <div class="queue-row" data-id="${q.id}">
                        <div class="queue-number waiting-num">${pad(q.number)}</div>
                        <div class="flex-grow-1 fw-semibold">${q.name}</div>
                    </div>`).join('');
            }

            // ── Update late list ──
            const lateList = document.getElementById('lateList');
            document.getElementById('lateCount').textContent = data.late.length;
            if (data.late.length === 0) {
                lateList.innerHTML = `<div class="text-center text-muted py-5">
                    <i class="mdi mdi-check-all fs-2 d-block mb-2"></i>
                    Tidak ada antrian terlambat
                </div>`;
            } else {
                lateList.innerHTML = data.late.map(q => `
                    <div class="queue-row" data-id="${q.id}">
                        <div class="queue-number late-num">${pad(q.number)}</div>
                        <div class="flex-grow-1 fw-semibold text-danger">${q.name}</div>
                        <button class="btn-recall"
                                onclick="panggilTerlambat(${q.id}, '${q.number}', '${q.name.replace(/'/g, "\\'")}')">
                            <i class="mdi mdi-bullhorn me-1"></i>Panggil Ulang
                        </button>
                    </div>`).join('');
            }
        });

        source.onerror = function () {
            sseDot.style.background = '#f87171';
            sseStatus.textContent   = 'SSE Terputus — menyambung ulang...';
        };
    });
</script>
@endpush
