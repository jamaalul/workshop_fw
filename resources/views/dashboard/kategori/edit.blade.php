@extends('layouts.dashboard')

@section('title', 'Edit Kategori')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Edit Kategori </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kategori') }}">Kategori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Kategori</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="grid-margin col-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Kategori</h4>
                    <p class="card-description"> Edit kategori </p>
                    <form class="forms-sample" id="formKategori" action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_kategori">Nama Kategori</label>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                id="nama_kategori" name="nama_kategori" placeholder="Masukkan nama kategori"
                                value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                            @error('nama_kategori')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                    <button type="button" class="me-2 btn btn-gradient-primary btn-submit">Save Changes</button>
                    <a href="{{ route('kategori') }}" class="btn btn-light">Cancel</a>
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
