@extends('layouts.dashboard')

@section('title', 'POS - Axios')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Point of Sales (Axios) </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">POS (Axios)</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Penjualan</h4>
                    <p class="card-description">Masukkan kode barang dan tekan Enter untuk mencari</p>
                    
                    <form class="forms-sample mt-4" id="form-pos">
                        @csrf
                        <div class="form-group row align-items-end">
                            <div class="col-md-2">
                                <label for="kode_barang">Kode Barang</label>
                                <input type="text" class="form-control" id="kode_barang" placeholder="BRG..." autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" readonly placeholder="Nama barang">
                            </div>
                            <div class="col-md-3">
                                <label for="harga_barang">Harga</label>
                                <input type="text" class="form-control" id="harga_barang" readonly placeholder="0">
                            </div>
                            <div class="col-md-2">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah" value="1" min="1">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-gradient-primary w-100 btn-lg" id="btn-tambahkan" disabled style="height: 44px;">
                                    Tambahkan
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-striped" id="table-pos">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <!-- Items will be added here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-end">
                            <h4>Total: Rp <span id="total">0</span></h4>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-success btn-lg" id="btn-bayar" disabled>Bayar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script>
        // Set up axios defaults
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', function() {
            var cart = [];
            var foundBarang = null;

            // Format number to Indonesian Rupiah
            function formatRupiah(angka) {
                var numberString = angka.toString();
                var split = numberString.split('.');
                var suffix = split[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                return suffix + (split[1] ? ',' + split[1] : '');
            }

            // Clear form inputs
            function clearForm() {
                document.getElementById('kode_barang').value = '';
                document.getElementById('nama_barang').value = '';
                document.getElementById('harga_barang').value = '';
                document.getElementById('jumlah').value = 1;
                foundBarang = null;
                updateButtonState();
                document.getElementById('kode_barang').focus();
            }

            // Update button disabled state
            function updateButtonState() {
                var jumlahInput = document.getElementById('jumlah');
                var val = parseInt(jumlahInput.value);
                var btn = document.getElementById('btn-tambahkan');
                btn.disabled = !foundBarang || isNaN(val) || val < 1;
            }

            // Calculate total
            function calculateTotal() {
                var total = 0;
                cart.forEach(function(item) {
                    total += item.subtotal;
                });
                document.getElementById('total').textContent = formatRupiah(total);
                document.getElementById('btn-bayar').disabled = cart.length === 0;
            }

            // Render table
            function renderTable() {
                var tbody = document.getElementById('table-body');
                tbody.innerHTML = '';
                
                cart.forEach(function(item, index) {
                    var tr = document.createElement('tr');
                    tr.setAttribute('data-index', index);
                    tr.innerHTML = `
                        <td>${item.kode}</td>
                        <td>${item.nama}</td>
                        <td>Rp ${formatRupiah(item.harga)}</td>
                        <td>
                            <input type="number" class="form-control jumlah-input" value="${item.jumlah}" min="1" data-index="${index}" style="width: 80px;">
                        </td>
                        <td class="subtotal-cell">Rp ${formatRupiah(item.subtotal)}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-hapus" data-index="${index}">
                                <i class="mdi mdi-trash-can"></i> Hapus
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
                calculateTotal();
            }

            // Re-validate when jumlah changes
            document.getElementById('jumlah').addEventListener('input', function() {
                updateButtonState();
            });

            // Find barang by kode on Enter key
            document.getElementById('kode_barang').addEventListener('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    var kode = this.value.trim();
                    
                    if (kode === '') {
                        return;
                    }

                    axios.get('{{ route("pos.barang", ["kode" => "__kode__"]) }}'.replace('__kode__', kode))
                        .then(function(response) {
                            if (response.data.status === 'success') {
                                foundBarang = response.data.data;
                                document.getElementById('nama_barang').value = foundBarang.nama;
                                document.getElementById('harga_barang').value = formatRupiah(foundBarang.harga);
                                document.getElementById('jumlah').value = 1;
                                updateButtonState();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.data.message
                                });
                                clearForm();
                            }
                        })
                        .catch(function(error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal mencari barang'
                            });
                            clearForm();
                        });
                }
            });

            // Tambahkan button click
            document.getElementById('btn-tambahkan').addEventListener('click', function() {
                if (!foundBarang) {
                    return;
                }

                var kode = foundBarang.kode;
                var nama = foundBarang.nama;
                var harga = foundBarang.harga;
                var jumlah = parseInt(document.getElementById('jumlah').value);
                var subtotal = harga * jumlah;

                // Check if kode already exists in cart
                var existingIndex = cart.findIndex(function(item) {
                    return item.kode === kode;
                });

                if (existingIndex !== -1) {
                    // Update existing item
                    cart[existingIndex].jumlah += jumlah;
                    cart[existingIndex].subtotal = cart[existingIndex].harga * cart[existingIndex].jumlah;
                } else {
                    // Add new item
                    cart.push({
                        kode: kode,
                        nama: nama,
                        harga: harga,
                        jumlah: jumlah,
                        subtotal: subtotal
                    });
                }

                renderTable();
                clearForm();
                document.getElementById('kode_barang').focus();
            });

            // Delegate event for jumlah change
            document.getElementById('table-body').addEventListener('change', function(e) {
                if (e.target.classList.contains('jumlah-input')) {
                    var index = parseInt(e.target.getAttribute('data-index'));
                    var newJumlah = parseInt(e.target.value);
                    
                    if (newJumlah < 1) {
                        newJumlah = 1;
                        e.target.value = 1;
                    }

                    cart[index].jumlah = newJumlah;
                    cart[index].subtotal = cart[index].harga * cart[index].jumlah;
                    
                    e.target.closest('tr').querySelector('.subtotal-cell').textContent = 'Rp ' + formatRupiah(cart[index].subtotal);
                    calculateTotal();
                }
            });

            // Delegate event for hapus button
            document.getElementById('table-body').addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-hapus')) {
                    var index = parseInt(e.target.getAttribute('data-index'));
                    cart.splice(index, 1);
                    renderTable();
                }
            });

            // Bayar button click
            document.getElementById('btn-bayar').addEventListener('click', function() {
                if (cart.length === 0) {
                    return;
                }

                var items = cart.map(function(item) {
                    return {
                        id_barang: item.kode,
                        jumlah: item.jumlah,
                        subtotal: item.subtotal
                    };
                });

                var total = 0;
                cart.forEach(function(item) {
                    total += item.subtotal;
                });

                var btn = this;
                btn.disabled = true;
                var originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                axios.post('{{ route("pos.bayar") }}', {
                    _token: '{{ csrf_token() }}',
                    items: items,
                    total: total
                })
                .then(function(response) {
                    if (response.data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Transaksi berhasil disimpan! No. Transaksi: ' + response.data.id_penjualan
                        }).then(() => {
                            // Clear cart and table
                            cart = [];
                            renderTable();
                            clearForm();
                            document.getElementById('total').textContent = '0';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.data.message
                        });
                    }
                })
                .catch(function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal menyimpan transaksi'
                    });
                })
                .finally(function() {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            });
        });
    </script>
@endpush
@endsection
