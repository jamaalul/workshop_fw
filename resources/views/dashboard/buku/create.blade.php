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
                    <form class="forms-sample" id="formBuku" action="{{ route('buku.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="kode">Kode Buku</label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                id="kode" name="kode" placeholder="Masukkan kode buku"
                                value="{{ old('kode') }}" required>
                            @error('kode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="judul">Judul Buku</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                id="judul" name="judul" placeholder="Masukkan judul buku"
                                value="{{ old('judul') }}" required>
                            @error('judul')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="pengarang">Pengarang</label>
                            <input type="text" class="form-control @error('pengarang') is-invalid @enderror"
                                id="pengarang" name="pengarang" placeholder="Masukkan nama pengarang"
                                value="{{ old('pengarang') }}" required>
                            @error('pengarang')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select class="form-select form-control @error('kategori_id') is-invalid @enderror"
                                id="kategori_id" name="kategori_id" required>
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
                    </form>
                    <button type="button" class="me-2 btn btn-gradient-primary btn-submit">Submit</button>
                    <a href="{{ route('buku') }}" class="btn btn-light">Cancel</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.btn-submit').on('click', function() {
                var $btn = $(this);
                var $form = $btn.closest('.card-body').find('form');

                if (!$form[0].checkValidity()) {
                    $form[0].reportValidity();
                    return;
                }

                $btn.prop('disabled', true);
                $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

                $form.submit();
            });
        });
    </script>
@endpush
