@extends('layouts.dashboard')

@section('title', 'Data Customer')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
            <i class="mdi mdi-account-group"></i>
        </span> Data Customer
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Customer</li>
        </ol>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-3">
    <div class="col">
        <a href="{{ route('customer.create.blob') }}" class="btn btn-gradient-primary btn-sm me-2">
            <i class="mdi mdi-camera me-1"></i> Tambah Customer (Blob)
        </a>
        <a href="{{ route('customer.create.file') }}" class="btn btn-gradient-info btn-sm">
            <i class="mdi mdi-camera me-1"></i> Tambah Customer (File)
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title">Daftar Customer</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="customerTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Metode Foto</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $i => $c)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @if($c->photo_blob)
                                        <img src="{{ $c->photo_base64 }}" alt="foto" class="rounded-circle shadow-sm" width="48" height="48" style="object-fit:cover;">
                                    @elseif($c->photo_path)
                                        <img src="{{ asset($c->photo_path) }}" alt="foto" class="rounded-circle shadow-sm" width="48" height="48" style="object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center shadow-sm" style="width:48px;height:48px;">
                                            <i class="mdi mdi-account text-white fs-4"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $c->nama }}</td>
                                <td>{{ $c->email ?? '-' }}</td>
                                <td>{{ $c->telepon ?? '-' }}</td>
                                <td>
                                    @if($c->photo_blob)
                                        <span class="badge bg-gradient-primary">Blob (DB)</span>
                                    @elseif($c->photo_path)
                                        <span class="badge bg-gradient-info">File</span>
                                    @else
                                        <span class="badge bg-secondary">Tanpa Foto</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $c->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('customer.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus customer ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="mdi mdi-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="mdi mdi-account-off display-4 d-block mb-2"></i>
                                    Belum ada data customer.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
