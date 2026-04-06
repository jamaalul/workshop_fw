@extends('layouts.dashboard')

@section('title', 'Mini Canteen')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
            <i class="mdi mdi-food"></i>
        </span> Kantin Mini
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Pesanan Tamu</a></li>
            <li class="breadcrumb-item active" aria-current="page">POS Kantin</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Menu Selection Area -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title text-primary mb-4">Pilih Vendor & Menu</h4>
                
                <div class="form-group row mb-3 align-items-center">
                    <label class="col-sm-3 col-form-label fw-bold">Pilih Vendor</label>
                    <div class="col-sm-9">
                        <select class="form-select form-select-lg w-100" id="vendor_select">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-3 align-items-center">
                    <label class="col-sm-3 col-form-label fw-bold">Pilih Menu</label>
                    <div class="col-sm-9">
                        <select class="form-select form-select-lg w-100" id="menu_select" disabled>
                            <option value="">-- Pilih Menu --</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-4 align-items-center">
                    <label class="col-sm-3 col-form-label fw-bold">Jumlah</label>
                    <div class="col-sm-9">
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control" id="jumlah_input" value="1" min="1" aria-label="Jumlah Kuatnitas">
                            <button class="btn btn-gradient-primary px-4 fw-bold" type="button" id="add_to_cart_btn" disabled>
                                <i class="mdi mdi-cart-plus me-1"></i> Tambahkan
                            </button>
                        </div>
                    </div>
                </div>
                
                <hr class="mt-4 mb-4">
                
                <h4 class="card-title text-primary mb-3">Item Terpilih</h4>
                <div class="table-responsive bg-light p-2 rounded">
                    <table class="table table-hover table-bordered bg-white mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Jml</th>
                                <th>Subtotal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cart_body">
                            <!-- Cart items go here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary Area -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card bg-gradient-info text-white text-center shadow-sm border-0">
            <div class="card-body d-flex flex-column justify-content-center">
                <h4 class="card-title text-white mb-4"><i class="mdi mdi-cart-outline me-2"></i>Ringkasan Pesanan</h4>
                <div class="my-4 p-3 rounded" style="background-color: rgba(255, 255, 255, 0.2);">
                    <h5 class="fw-normal mb-1">Total Tagihan</h5>
                    <h1 class="display-6 font-weight-bold mb-0 text-white" id="total_display">Rp 0</h1>
                </div>
                
                <form id="orderForm" method="POST" action="{{ route('canteen.order') }}" class="mt-auto">
                    @csrf
                    <input type="hidden" name="idvendor" id="form_idvendor">
                    <!-- Cart items will be appended here as hidden inputs before submit -->
                    <div id="hidden_cart_inputs"></div>
                    
                    <button type="submit" class="btn btn-light btn-lg w-100 text-info font-weight-bold shadow-sm rounded-pill" id="checkout_btn" disabled>
                        Lanjutkan Pembayaran <i class="mdi mdi-arrow-right ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let cart = [];
    let currentVendor = null;

    // Format currency
    const formatRp = (angka) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    };

    // Load Menus when Vendor selected
    $('#vendor_select').change(function() {
        let vendorId = $(this).val();
        currentVendor = vendorId;
        $('#form_idvendor').val(vendorId);
        
        let menuSelect = $('#menu_select');
        menuSelect.empty().append('<option value="">-- Pilih Menu --</option>').prop('disabled', true);
        $('#add_to_cart_btn').prop('disabled', true);

        // Clear cart if vendor changes (optional rule, but good practice for Single-Vendor checkout)
        if(cart.length > 0 && !confirm('Ganti vendor akan menghapus keranjang saat ini. Lanjutkan?')) {
            $(this).val($('#form_idvendor').data('prev'));
            return;
        }
        $(this).data('prev', vendorId);
        cart = [];
        renderCart();

        if (vendorId) {
            $.get(`/canteen/menu/${vendorId}`, function(data) {
                if(data.length > 0) {
                    menuSelect.prop('disabled', false);
                    data.forEach(function(item) {
                        menuSelect.append(`<option value="${item.idmenu}" data-harga="${item.harga}" data-nama="${item.nama_menu}">${item.nama_menu} - ${formatRp(item.harga)}</option>`);
                    });
                } else {
                    menuSelect.append('<option value="">Menu tidak tersedia</option>');
                }
            });
        }
    });

    // Enable/disable Add btn
    $('#menu_select, #jumlah_input').on('change keyup', function() {
        if ($('#menu_select').val() && $('#jumlah_input').val() > 0) {
            $('#add_to_cart_btn').prop('disabled', false);
        } else {
            $('#add_to_cart_btn').prop('disabled', true);
        }
    });

    // Add to Cart
    $('#add_to_cart_btn').click(function() {
        let opt = $('#menu_select option:selected');
        let idmenu = opt.val();
        let nama = opt.data('nama');
        let harga = parseInt(opt.data('harga'));
        let jumlah = parseInt($('#jumlah_input').val());

        let existing = cart.find(i => i.idmenu == idmenu);
        if (existing) {
            existing.jumlah += jumlah;
        } else {
            cart.push({ idmenu, nama, harga, jumlah });
        }
        
        $('#jumlah_input').val(1);
        $('#menu_select').val('').trigger('change');
        renderCart();
    });

    // Render Cart
    window.removeCartItem = function(index) {
        cart.splice(index, 1);
        renderCart();
    };

    function renderCart() {
        let tbody = $('#cart_body');
        let hiddenInputs = $('#hidden_cart_inputs');
        tbody.empty();
        hiddenInputs.empty();
        
        let total = 0;
        
        if (cart.length === 0) {
            tbody.append('<tr><td colspan="5" class="text-center">Keranjang kosong</td></tr>');
            $('#checkout_btn').prop('disabled', true);
            $('#total_display').text('Rp 0');
            return;
        }

        cart.forEach((item, index) => {
            let subtotal = item.harga * item.jumlah;
            total += subtotal;

            tbody.append(`
                <tr>
                    <td class="align-middle">${item.nama}</td>
                    <td class="align-middle">${formatRp(item.harga)}</td>
                    <td class="align-middle">${item.jumlah}</td>
                    <td class="align-middle fw-bold text-primary">${formatRp(subtotal)}</td>
                    <td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm" onclick="removeCartItem(${index})"><i class="mdi mdi-delete"></i> Hapus</button></td>
                </tr>
            `);

            hiddenInputs.append(`
                <input type="hidden" name="items[${index}][idmenu]" value="${item.idmenu}">
                <input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">
            `);
        });

        $('#total_display').text(formatRp(total));
        $('#checkout_btn').prop('disabled', false);
    }

    // Handle Form Submit with Ajax to get the redirect url cleanly
    $('#orderForm').submit(function(e) {
        e.preventDefault();
        
        let btn = $('#checkout_btn');
        btn.prop('disabled', true).text('Memproses...');
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    window.location.href = res.redirect;
                }
            },
            error: function(err) {
                alert('Terjadi kesalahan membuat pesanan!');
                btn.prop('disabled', false).text('Lanjutkan Pembayaran');
            }
        });
    });
});
</script>
@endpush
