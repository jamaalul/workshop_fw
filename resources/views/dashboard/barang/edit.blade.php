@extends('layouts.dashboard')

@section('title', 'Edit Barang')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Edit Barang </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('barang') }}">Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Barang</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="grid-margin col-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Barang</h4>
                    <p class="card-description"> Edit data barang </p>
                    <form class="forms-sample" id="formBarang" action="{{ route('barang.update', $barang->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama">Nama Barang</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                id="nama" name="nama" placeholder="Masukkan nama barang"
                                value="{{ old('nama', $barang->nama) }}" required>
                            @error('nama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" step="0.01" class="form-control @error('harga') is-invalid @enderror"
                                id="harga" name="harga" placeholder="Masukkan harga barang"
                                value="{{ old('harga', $barang->harga) }}" required>
                            @error('harga')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                    <button type="button" class="me-2 btn btn-gradient-primary btn-submit">Update</button>
                    <a href="{{ route('barang') }}" class="btn btn-light">Cancel</a>
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
