@extends('layouts.dashboard')

@section('title', 'Tambah Buku')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Tambah Buku </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('buku') }}">Buku</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Buku</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="grid-margin col-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Buku</h4>
                    <p class="card-description"> Tambah buku baru </p>
                    <form class="forms-sample" action="{{ route('buku.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="kode">Kode Buku</label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                id="kode" name="kode" placeholder="Masukkan kode buku"
                                value="{{ old('kode') }}">
                            @error('kode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="judul">Judul Buku</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                id="judul" name="judul" placeholder="Masukkan judul buku"
                                value="{{ old('judul') }}">
                            @error('judul')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="pengarang">Pengarang</label>
                            <input type="text" class="form-control @error('pengarang') is-invalid @enderror"
                                id="pengarang" name="pengarang" placeholder="Masukkan nama pengarang"
                                value="{{ old('pengarang') }}">
                            @error('pengarang')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select class="form-select form-control @error('kategori_id') is-invalid @enderror"
                                id="kategori_id" name="kategori_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="me-2 btn btn-gradient-primary">Submit</button>
                        <a href="{{ route('buku') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- javascript --}}
@endpush
