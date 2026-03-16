@extends('layouts.dashboard')

@section('title', 'Wilayah - Axios')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Wilayah (Axios) </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Wilayah (Axios)</li>
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
        // Set up axios defaults
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', function() {
            // Load provinces on page load
            loadProvinsi();

            // Province change event
            document.getElementById('provinsi').addEventListener('change', function() {
                var provinceId = this.value;
                
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
            document.getElementById('kota').addEventListener('change', function() {
                var regencyId = this.value;
                
                if (regencyId !== '0') {
                    loadKecamatan(regencyId);
                } else {
                    resetDropdown('#kecamatan', 'Pilih Kecamatan', true);
                    resetDropdown('#kelurahan', 'Pilih Kelurahan', true);
                }
                updateSelectedInfo();
            });

            // Kecamatan change event
            document.getElementById('kecamatan').addEventListener('change', function() {
                var districtId = this.value;
                
                if (districtId !== '0') {
                    loadKelurahan(districtId);
                } else {
                    resetDropdown('#kelurahan', 'Pilih Kelurahan', true);
                }
                updateSelectedInfo();
            });

            // Kelurahan change event
            document.getElementById('kelurahan').addEventListener('change', function() {
                updateSelectedInfo();
            });
        });

        function loadProvinsi() {
            var select = document.getElementById('provinsi');
            removeLoadingText(select);
            select.insertAdjacentHTML('afterend', '<span class="text-muted">Memuat...</span>');
            
            axios.get('{{ route("wilayah.provinsi") }}')
                .then(function(response) {
                    removeLoadingText(select);
                    clearOptions(select, true);
                    
                    if (response.data.status === 'success') {
                        response.data.data.forEach(function(province) {
                            var option = document.createElement('option');
                            option.value = province.id;
                            option.textContent = province.name;
                            select.appendChild(option);
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
                    removeLoadingText(select);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data provinsi'
                    });
                });
        }

        function loadKota(provinceId) {
            var select = document.getElementById('kota');
            select.disabled = true;
            removeLoadingText(select);
            select.insertAdjacentHTML('afterend', '<span class="text-muted">Memuat...</span>');
            
            axios.get('{{ route("wilayah.kota", ["id" => "__id__"]) }}'.replace('__id__', provinceId))
                .then(function(response) {
                    removeLoadingText(select);
                    select.disabled = false;
                    clearOptions(select, true);
                    resetDropdown('#kecamatan', 'Pilih Kecamatan', true);
                    resetDropdown('#kelurahan', 'Pilih Kelurahan', true);
                    
                    if (response.data.status === 'success') {
                        response.data.data.forEach(function(regency) {
                            var option = document.createElement('option');
                            option.value = regency.id;
                            option.textContent = regency.name;
                            select.appendChild(option);
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
                    removeLoadingText(select);
                    select.disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data kota'
                    });
                });
        }

        function loadKecamatan(regencyId) {
            var select = document.getElementById('kecamatan');
            select.disabled = true;
            removeLoadingText(select);
            select.insertAdjacentHTML('afterend', '<span class="text-muted">Memuat...</span>');
            
            axios.get('{{ route("wilayah.kecamatan", ["id" => "__id__"]) }}'.replace('__id__', regencyId))
                .then(function(response) {
                    removeLoadingText(select);
                    select.disabled = false;
                    clearOptions(select, true);
                    resetDropdown('#kelurahan', 'Pilih Kelurahan', true);
                    
                    if (response.data.status === 'success') {
                        response.data.data.forEach(function(district) {
                            var option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            select.appendChild(option);
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
                    removeLoadingText(select);
                    select.disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data kecamatan'
                    });
                });
        }

        function loadKelurahan(districtId) {
            var select = document.getElementById('kelurahan');
            select.disabled = true;
            removeLoadingText(select);
            select.insertAdjacentHTML('afterend', '<span class="text-muted">Memuat...</span>');
            
            axios.get('{{ route("wilayah.kelurahan", ["id" => "__id__"]) }}'.replace('__id__', districtId))
                .then(function(response) {
                    removeLoadingText(select);
                    select.disabled = false;
                    clearOptions(select, true);
                    
                    if (response.data.status === 'success') {
                        response.data.data.forEach(function(village) {
                            var option = document.createElement('option');
                            option.value = village.id;
                            option.textContent = village.name;
                            select.appendChild(option);
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
                    removeLoadingText(select);
                    select.disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data kelurahan'
                    });
                });
        }

        function removeLoadingText(select) {
            var nextSibling = select.nextElementSibling;
            if (nextSibling && nextSibling.classList.contains('text-muted')) {
                nextSibling.remove();
            }
        }

        function clearOptions(select, keepFirst) {
            while (select.options.length > 1) {
                select.remove(1);
            }
        }

        function resetDropdown(selector, defaultText, disabled) {
            var select = document.querySelector(selector);
            clearOptions(select, false);
            var option = document.createElement('option');
            option.value = '0';
            option.textContent = defaultText;
            select.appendChild(option);
            select.value = '0';
            select.disabled = disabled;
        }

        function updateSelectedInfo() {
            var province = document.getElementById('provinsi').options[document.getElementById('provinsi').selectedIndex].text;
            var kota = document.getElementById('kota').options[document.getElementById('kota').selectedIndex].text;
            var kecamatan = document.getElementById('kecamatan').options[document.getElementById('kecamatan').selectedIndex].text;
            var kelurahan = document.getElementById('kelurahan').options[document.getElementById('kelurahan').selectedIndex].text;

            var info = '<strong>Info:</strong> ';
            
            if (document.getElementById('provinsi').value !== '0') {
                info += 'Provinsi: ' + province;
            }
            if (document.getElementById('kota').value !== '0') {
                info += ' | Kota: ' + kota;
            }
            if (document.getElementById('kecamatan').value !== '0') {
                info += ' | Kecamatan: ' + kecamatan;
            }
            if (document.getElementById('kelurahan').value !== '0') {
                info += ' | Kelurahan: ' + kelurahan;
            }

            if (document.getElementById('provinsi').value === '0') {
                info += 'Silakan pilih wilayah di atas.';
            }

            document.getElementById('selected-info').innerHTML = info;
        }
    </script>
@endpush
@endsection
