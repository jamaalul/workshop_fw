@extends('layouts.dashboard')

@push('styles')
    <style>
        #barangTable tbody tr {
            cursor: pointer;
        }
    </style>
@endpush

@section('title', 'JQuery HTML Table')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> JQuery HTML Table </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">JQuery HTML Table</li>
            </ol>
        </nav>
    </div>

    {{-- Form Card --}}
    <div class="row">
        <div class="grid-margin col-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Barang</h4>
                    <p class="card-description"> Tambah barang baru (session only) </p>
                    <form class="forms-sample" id="formBarang">
                        <div class="form-group">
                            <label for="nama">Nama Barang</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan nama barang" required>
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga Barang</label>
                            <input type="number" step="0.01" class="form-control" id="harga" name="harga"
                                placeholder="Masukkan harga barang" required>
                        </div>
                    </form>
                    <button type="button" class="me-2 btn btn-gradient-primary btn-submit">Submit</button>
                    <button type="button" class="btn btn-light btn-reset">Reset</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="row">
        <div class="grid-margin col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="mb-0 card-title">Data Barang (HTML Table)</h4>
                        <span class="badge badge-gradient-info" id="rowCount">0 data</span>
                    </div>

                    <div class="py-5 text-center" id="emptyState">
                        <i class="mdi-folder-open-outline text-muted mdi" style="font-size: 48px;"></i>
                        <h5 class="mt-3 text-muted">Tidak ada data</h5>
                        <p class="text-muted small">Silakan tambah data melalui form di atas.</p>
                    </div>

                    <div class="table-responsive" id="tableContainer" style="display: none;">
                        <table class="table table-striped" id="barangTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">ID Barang</th>
                                    <th class="text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Nama</th>
                                    <th class="text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Harga</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Modal --}}
    <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="actionModalLabel">Edit / Hapus Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modalForm">
                        <div class="form-group mb-3">
                            <label for="modal_id">ID Barang</label>
                            <input type="text" class="form-control" id="modal_id" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="modal_nama">Nama Barang</label>
                            <input type="text" class="form-control" id="modal_nama" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="modal_harga">Harga Barang</label>
                            <input type="number" step="0.01" class="form-control" id="modal_harga" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm btn-hapus">Hapus</button>
                    <button type="button" class="btn btn-primary btn-sm btn-ubah">Ubah</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var idCounter = 0;

            function updateView() {
                var count = $('#tableBody tr').length;
                $('#rowCount').text(count + ' data');
                if (count > 0) {
                    $('#emptyState').hide();
                    $('#tableContainer').show();
                } else {
                    $('#emptyState').show();
                    $('#tableContainer').hide();
                }
            }

            $('.btn-submit').on('click', function() {
                var $btn = $(this);
                var $form = $btn.closest('.card-body').find('form');

                if (!$form[0].checkValidity()) {
                    $form[0].reportValidity();
                    return;
                }

                var nama = $('#nama').val().trim();
                var harga = parseFloat($('#harga').val());
                idCounter++;

                var escapedNama = $('<span>').text(nama).html();
                var row = '<tr data-id="' + idCounter + '" data-nama="' + escapedNama + '" data-harga="' + harga + '">' +
                    '<td>' + idCounter + '</td>' +
                    '<td>' + escapedNama + '</td>' +
                    '<td>' + harga.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) + '</td>' +
                    '</tr>';

                $('#tableBody').append(row);

                // Clear form
                $('#nama').val('');
                $('#harga').val('');
                $('#nama').focus();

                updateView();
            });

            $('.btn-reset').on('click', function() {
                $('#nama').val('');
                $('#harga').val('');
                $('#nama').focus();
            });

            var currentRow;

            // Row click event
            $('#tableBody').on('click', 'tr', function() {
                currentRow = $(this);
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var harga = $(this).data('harga');

                $('#modal_id').val(id);
                $('#modal_nama').val(nama);
                $('#modal_harga').val(harga);

                $('#actionModal').modal('show');
            });

            // Ubah (Update) event
            $('.btn-ubah').on('click', function() {
                var $form = $('#modalForm');
                if (!$form[0].checkValidity()) {
                    $form[0].reportValidity();
                    return;
                }

                var updateNama = $('#modal_nama').val().trim();
                var updateHarga = parseFloat($('#modal_harga').val());
                var id = $('#modal_id').val();

                currentRow.data('nama', updateNama);
                currentRow.data('harga', updateHarga);

                var escapedNama = $('<span>').text(updateNama).html();
                currentRow.html(
                    '<td>' + id + '</td>' +
                    '<td>' + escapedNama + '</td>' +
                    '<td>' + updateHarga.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) + '</td>'
                );

                $('#actionModal').modal('hide');
            });

            // Hapus (Delete) event
            $('.btn-hapus').on('click', function() {
                if (currentRow) {
                    currentRow.remove();
                    updateView();
                    $('#actionModal').modal('hide');
                }
            });

            updateView();
        });
    </script>
@endpush
