@extends('layouts.dashboard')

@section('title', 'Tambah Barang')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Tambah Barang </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('barang') }}">Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Barang</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="grid-margin col-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Barang</h4>
                    <p class="card-description"> Tambah barang baru </p>
                    <form class="forms-sample" action="{{ route('barang.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama Barang</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                id="nama" name="nama" placeholder="Masukkan nama barang"
                                value="{{ old('nama') }}">
                            @error('nama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" step="0.01" class="form-control @error('harga') is-invalid @enderror"
                                id="harga" name="harga" placeholder="Masukkan harga barang"
                                value="{{ old('harga') }}">
                            @error('harga')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="me-2 btn btn-gradient-primary">Submit</button>
                        <a href="{{ route('barang') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- javascript --}}
@endpush
