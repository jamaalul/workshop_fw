@extends('layouts.dashboard')

@section('title', 'Riwayat Absensi NFC')

@push('styles')
<style>
    /* ── Stats Cards ── */
    .stat-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .stat-number {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1.2;
    }

    /* ── Table Card ── */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .table-card .table {
        margin-bottom: 0;
    }
    .table-card .table thead th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 14px 16px;
    }
    .table-card .table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-card .table tbody tr:hover {
        background: #f8fafc;
    }
    .table-card .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ── Status badges ── */
    .badge-hadir {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.8rem;
    }
    .badge-terlambat {
        background: rgba(239, 68, 68, 0.12);
        color: #dc2626;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.8rem;
    }

    /* ── Serial number mono ── */
    .serial-mono {
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 6px;
        letter-spacing: 0.5px;
        color: #475569;
    }

    /* ── Empty state ── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 3rem;
        display: block;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
            <i class="mdi mdi-history"></i>
        </span> Riwayat Absensi
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/nfc">NFC Scanner</a></li>
            <li class="breadcrumb-item active" aria-current="page">Riwayat</li>
        </ol>
    </nav>
</div>

{{-- Summary Stats --}}
@php
    $totalHariIni  = $attendances->where('tanggal', \Carbon\Carbon::today()->toDateString())->count();
    $hadirHariIni  = $attendances->where('tanggal', \Carbon\Carbon::today()->toDateString())->where('status', 'hadir')->count();
    $terlambatHariIni = $attendances->where('tanggal', \Carbon\Carbon::today()->toDateString())->where('status', 'terlambat')->count();
@endphp

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: rgba(99,102,241,0.1); color:#6366f1;">
                    <i class="mdi mdi-account-check"></i>
                </div>
                <div>
                    <small class="text-muted fw-semibold text-uppercase">Total Hari Ini</small>
                    <div class="stat-number text-dark">{{ $totalHariIni }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: rgba(16,185,129,0.1); color:#10b981;">
                    <i class="mdi mdi-check-circle"></i>
                </div>
                <div>
                    <small class="text-muted fw-semibold text-uppercase">Hadir</small>
                    <div class="stat-number" style="color:#059669;">{{ $hadirHariIni }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: rgba(239,68,68,0.1); color:#ef4444;">
                    <i class="mdi mdi-clock-alert"></i>
                </div>
                <div>
                    <small class="text-muted fw-semibold text-uppercase">Terlambat</small>
                    <div class="stat-number" style="color:#dc2626;">{{ $terlambatHariIni }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <h5 class="mb-0 fw-bold">
        <i class="mdi mdi-table text-primary me-1"></i> Data Absensi
    </h5>
    <div class="d-flex gap-2">
        <a href="/nfc" class="btn btn-outline-primary btn-sm" style="border-radius:8px;">
            <i class="mdi mdi-nfc me-1"></i>Scanner
        </a>
        <a href="/nfc/register" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
            <i class="mdi mdi-card-plus me-1"></i>Daftarkan Kartu
        </a>
    </div>
</div>

{{-- Table --}}
<div class="card table-card">
    @if($attendances->isEmpty())
        <div class="empty-state">
            <i class="mdi mdi-calendar-blank-outline"></i>
            <h5 class="fw-bold text-muted">Belum Ada Data Absensi</h5>
            <p>Data akan muncul setelah mahasiswa melakukan scan kartu NFC.</p>
            <a href="/nfc" class="btn btn-gradient-primary mt-2">
                <i class="mdi mdi-nfc me-1"></i>Mulai Scan
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Mahasiswa</th>
                        <th>Serial Number</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $i => $att)
                        <tr>
                            <td class="fw-semibold text-muted">{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $att->nfcCard->nama_mahasiswa }}</td>
                            <td><span class="serial-mono">{{ $att->nfcCard->serial_number }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($att->tanggal)->format('d M Y') }}</td>
                            <td>{{ $att->waktu }}</td>
                            <td>
                                <span class="{{ $att->status === 'hadir' ? 'badge-hadir' : 'badge-terlambat' }}">
                                    <i class="mdi {{ $att->status === 'hadir' ? 'mdi-check-circle' : 'mdi-clock-alert' }} me-1"></i>
                                    {{ ucfirst($att->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
