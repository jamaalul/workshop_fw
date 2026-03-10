@extends('layouts.dashboard')

@section('title', 'JQuery DataTables')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> JQuery DataTables </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">JQuery DataTables</li>
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
                        <h4 class="mb-0 card-title">Data Barang (DataTables)</h4>
                        <span class="badge badge-gradient-info" id="rowCount">0 data</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped" id="barangTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">ID Barang</th>
                                    <th class="text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Nama</th>
                                    <th class="text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
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

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        #barangTable tbody tr {
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var idCounter = 0;

            // Initialize DataTable
            var table = $('#barangTable').DataTable({
                language: {
                    emptyTable: "Tidak ada data — silakan tambah data melalui form di atas.",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'nama' },
                    {
                        data: 'harga',
                        render: function(data) {
                            return parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                        }
                    }
                ],
                order: [[0, 'desc']]
            });

            function updateCount() {
                var count = table.rows().count();
                $('#rowCount').text(count + ' data');
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

                // Add row using DataTables API
                table.row.add({
                    id: idCounter,
                    nama: nama,
                    harga: harga
                }).draw(false);

                // Clear form
                $('#nama').val('');
                $('#harga').val('');
                $('#nama').focus();

                updateCount();
            });

            $('.btn-reset').on('click', function() {
                $('#nama').val('');
                $('#harga').val('');
                $('#nama').focus();
            });

            var currentRow;

            // Row click event
            $('#barangTable tbody').on('click', 'tr', function() {
                currentRow = table.row(this);
                // check if row actually has data (not 'empty row' indicator)
                var data = currentRow.data();
                if (!data) return;

                var id = data.id;
                var nama = data.nama;
                var harga = data.harga;

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
                var id = parseInt($('#modal_id').val());

                currentRow.data({
                    id: id,
                    nama: updateNama,
                    harga: updateHarga
                }).draw(false);

                $('#actionModal').modal('hide');
            });

            // Hapus (Delete) event
            $('.btn-hapus').on('click', function() {
                if (currentRow) {
                    currentRow.remove().draw(false);
                    updateCount();
                    $('#actionModal').modal('hide');
                }
            });

            updateCount();
        });
    </script>
@endpush
