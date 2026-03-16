@extends('layouts.dashboard')

@section('title', 'Wilayah - jQuery AJAX')

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Wilayah (jQuery AJAX) </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Wilayah (jQuery)</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Cascading Dropdown Wilayah Indonesia</h4>
                    <p class="card-description">Pilih wilayah secara berurutan: Provinsi → Kota → Kecamatan → Kelurahan</p>
                    
                    <form class="forms-sample mt-4">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="provinsi">Provinsi</label>
                                <select class="form-control" id="provinsi">
                                    <option value="0">Pilih Provinsi</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="kota">Kota/Kabupaten</label>
                                <select class="form-control" id="kota" disabled>
                                    <option value="0">Pilih Kota</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="kecamatan">Kecamatan</label>
                                <select class="form-control" id="kecamatan" disabled>
                                    <option value="0">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="kelurahan">Kelurahan</label>
                                <select class="form-control" id="kelurahan" disabled>
                                    <option value="0">Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="alert alert-info mt-4" role="alert" id="selected-info">
                        <strong>Info:</strong> Silakan pilih wilayah di atas.
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script>
        $(document).ready(function() {
            // Load provinces on page load
            loadProvinsi();

            // Province change event
            $('#provinsi').change(function() {
                var provinceId = $(this).val();
                
                if (provinceId !== '0') {
                    loadKota(provinceId);
                } else {
                    resetDropdown('#kota', 'Pilih Kota', true);
                    resetDropdown('#kecamatan', 'Pilih Kecamatan', true);
                    resetDropdown('#kelurahan', 'Pilih Kelurahan', true);
                }
                updateSelectedInfo();
            });

            // Kota change event
            $('#kota').change(function() {
                var regencyId = $(this).val();
                
                if (regencyId !== '0') {
                    loadKecamatan(regencyId);
                } else {
                    resetDropdown('#kecamatan', 'Pilih Kecamatan', true);
                    resetDropdown('#kelurahan', 'Pilih Kelurahan', true);
                }
                updateSelectedInfo();
            });

            // Kecamatan change event
            $('#kecamatan').change(function() {
                var districtId = $(this).val();
                
                if (districtId !== '0') {
                    loadKelurahan(districtId);
                } else {
                    resetDropdown('#kelurahan', 'Pilih Kelurahan', true);
                }
                updateSelectedInfo();
            });

            // Kelurahan change event
            $('#kelurahan').change(function() {
                updateSelectedInfo();
            });

            function loadProvinsi() {
                $('#provinsi').next('.text-muted').remove();
                $('#provinsi').after('<span class="text-muted">Memuat...</span>');
                
                $.ajax({
                    url: '{{ route("wilayah.provinsi") }}',
                    type: 'GET',
                    success: function(response) {
                        $('#provinsi').next('.text-muted').remove();
                        $('#provinsi').find('option:not(:first)').remove();
                        
                        if (response.status === 'success') {
                            $.each(response.data, function(key, province) {
                                $('#provinsi').append('<option value="' + province.id + '">' + province.name + '</option>');
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#provinsi').next('.text-muted').remove();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data provinsi'
                        });
                    }
                });
            }

            function loadKota(provinceId) {
                $('#kota').prop('disabled', true);
                $('#kota').next('.text-muted').remove();
                $('#kota').after('<span class="text-muted">Memuat...</span>');
                
                $.ajax({
                    url: '{{ route("wilayah.kota", ["id" => "__id__"]) }}'.replace('__id__', provinceId),
                    type: 'GET',
                    success: function(response) {
                        $('#kota').next('.text-muted').remove();
                        $('#kota').prop('disabled', false);
                        $('#kota').find('option:not(:first)').remove();
                        resetDropdown('#kecamatan', 'Pilih Kecamatan', true);
                        resetDropdown('#kelurahan', 'Pilih Kelurahan', true);
                        
                        if (response.status === 'success') {
                            $.each(response.data, function(key, regency) {
                                $('#kota').append('<option value="' + regency.id + '">' + regency.name + '</option>');
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#kota').next('.text-muted').remove();
                        $('#kota').prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data kota'
                        });
                    }
                });
            }

            function loadKecamatan(regencyId) {
                $('#kecamatan').prop('disabled', true);
                $('#kecamatan').next('.text-muted').remove();
                $('#kecamatan').after('<span class="text-muted">Memuat...</span>');
                
                $.ajax({
                    url: '{{ route("wilayah.kecamatan", ["id" => "__id__"]) }}'.replace('__id__', regencyId),
                    type: 'GET',
                    success: function(response) {
                        $('#kecamatan').next('.text-muted').remove();
                        $('#kecamatan').prop('disabled', false);
                        $('#kecamatan').find('option:not(:first)').remove();
                        resetDropdown('#kelurahan', 'Pilih Kelurahan', true);
                        
                        if (response.status === 'success') {
                            $.each(response.data, function(key, district) {
                                $('#kecamatan').append('<option value="' + district.id + '">' + district.name + '</option>');
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#kecamatan').next('.text-muted').remove();
                        $('#kecamatan').prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data kecamatan'
                        });
                    }
                });
            }

            function loadKelurahan(districtId) {
                $('#kelurahan').prop('disabled', true);
                $('#kelurahan').next('.text-muted').remove();
                $('#kelurahan').after('<span class="text-muted">Memuat...</span>');
                
                $.ajax({
                    url: '{{ route("wilayah.kelurahan", ["id" => "__id__"]) }}'.replace('__id__', districtId),
                    type: 'GET',
                    success: function(response) {
                        $('#kelurahan').next('.text-muted').remove();
                        $('#kelurahan').prop('disabled', false);
                        $('#kelurahan').find('option:not(:first)').remove();
                        
                        if (response.status === 'success') {
                            $.each(response.data, function(key, village) {
                                $('#kelurahan').append('<option value="' + village.id + '">' + village.name + '</option>');
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#kelurahan').next('.text-muted').remove();
                        $('#kelurahan').prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data kelurahan'
                        });
                    }
                });
            }

            function resetDropdown(selector, defaultText, disabled) {
                $(selector).find('option:not(:first)').remove();
                $(selector).prepend('<option value="0">' + defaultText + '</option>');
                $(selector).val('0');
                $(selector).prop('disabled', disabled);
            }

            function updateSelectedInfo() {
                var province = $('#provinsi option:selected').text();
                var kota = $('#kota option:selected').text();
                var kecamatan = $('#kecamatan option:selected').text();
                var kelurahan = $('#kelurahan option:selected').text();

                var info = '<strong>Info:</strong> ';
                
                if ($('#provinsi').val() !== '0') {
                    info += 'Provinsi: ' + province;
                }
                if ($('#kota').val() !== '0') {
                    info += ' | Kota: ' + kota;
                }
                if ($('#kecamatan').val() !== '0') {
                    info += ' | Kecamatan: ' + kecamatan;
                }
                if ($('#kelurahan').val() !== '0') {
                    info += ' | Kelurahan: ' + kelurahan;
                }

                if ($('#provinsi').val() === '0') {
                    info += 'Silakan pilih wilayah di atas.';
                }

                $('#selected-info').html(info);
            }
        });
    </script>
@endpush
@endsection
