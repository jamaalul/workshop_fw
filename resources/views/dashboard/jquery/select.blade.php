@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single {
            height: auto;
            padding: 0.6rem 1.375rem;
            border: 1px solid #ebedf2;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            line-height: inherit;
            color: #495057;
        }
    </style>
@endpush

@section('title', 'JQuery Select')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> JQuery Select </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">JQuery Select</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        {{-- Card 1: Native Select --}}
        <div class="grid-margin col-md-6 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Select</h4>
                    <p class="card-description"> Native HTML Select </p>
                    
                    <form class="forms-sample mb-4" id="formCity1">
                        <div class="form-group">
                            <label for="city1">Kota</label>
                            <input type="text" class="form-control" id="city1" placeholder="Masukkan nama kota" required>
                        </div>
                        <button type="button" class="btn btn-gradient-primary btn-add-city-1">Tambahkan</button>
                    </form>

                    <div class="form-group border-top pt-4">
                        <label for="selectCity1">Pilih Kota</label>
                        <select class="form-control" id="selectCity1">
                            <option value="" disabled selected>-- Pilih Kota --</option>
                        </select>
                    </div>

                    <div class="alert alert-info mt-3" role="alert">
                        <strong>Kota Terpilih:</strong> <span id="selectedCity1">-</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Select2 --}}
        <div class="grid-margin col-md-6 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Select 2</h4>
                    <p class="card-description"> JQuery Select2 Plugin </p>
                    
                    <form class="forms-sample mb-4" id="formCity2">
                        <div class="form-group">
                            <label for="city2">Kota</label>
                            <input type="text" class="form-control" id="city2" placeholder="Masukkan nama kota" required>
                        </div>
                        <button type="button" class="btn btn-gradient-primary btn-add-city-2">Tambahkan</button>
                    </form>

                    <div class="form-group border-top pt-4">
                        <label for="selectCity2">Pilih Kota</label>
                        <select class="form-control mb-3" id="selectCity2" style="width: 100%;">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>

                    <div class="alert alert-info mt-3" role="alert">
                        <strong>Kota Terpilih:</strong> <span id="selectedCity2">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // ==========================================
            // Card 1: Native Select
            // ==========================================
            $('.btn-add-city-1').on('click', function() {
                var $form = $('#formCity1');
                if (!$form[0].checkValidity()) {
                    $form[0].reportValidity();
                    return;
                }

                var city = $('#city1').val().trim();
                
                // Add new option
                var escapedCity = $('<span>').text(city).html();
                var newOption = $('<option>', {
                    value: escapedCity,
                    text: escapedCity
                });
                
                // Remove placeholder if it is the only option and currently selected
                if ($('#selectCity1 option').length === 1 && $('#selectCity1').val() === null) {
                    $('#selectCity1').empty();
                    $('#selectCity1').append(newOption);
                    $('#selectCity1').val(escapedCity).trigger('change');
                } else {
                    $('#selectCity1').append(newOption);
                    $('#selectCity1').val(escapedCity).trigger('change');
                }
                
                // Clear input
                $('#city1').val('').focus();
            });

            $('#selectCity1').on('change', function() {
                var selected = $(this).val();
                if (selected) {
                    $('#selectedCity1').text(selected);
                } else {
                    $('#selectedCity1').text('-');
                }
            });

            // ==========================================
            // Card 2: Select2
            // ==========================================
            // Initialize Select2
            $('#selectCity2').select2({
                placeholder: "-- Pilih Kota --",
                allowClear: true,
                theme: "bootstrap"
            });

            $('.btn-add-city-2').on('click', function() {
                var $form = $('#formCity2');
                if (!$form[0].checkValidity()) {
                    $form[0].reportValidity();
                    return;
                }

                var city = $('#city2').val().trim();
                var escapedCity = $('<span>').text(city).html();

                // Check if option already exists to prevent duplicate value issues in Select2
                if ($('#selectCity2').find("option[value='" + escapedCity + "']").length) {
                    $('#selectCity2').val(escapedCity).trigger('change');
                } else {
                    // Create the DOM option that is pre-selected by default
                    var newOption = new Option(escapedCity, escapedCity, true, true);
                    // Append it to the select
                    $('#selectCity2').append(newOption).trigger('change');
                }
                
                // Clear input
                $('#city2').val('').focus();
            });

            $('#selectCity2').on('select2:select change', function(e) {
                var selected = $(this).val();
                if (selected) {
                    $('#selectedCity2').text(selected);
                } else {
                    $('#selectedCity2').text('-');
                }
            });
            
            // Allow clearing input logic from enter key
            $('#formCity1, #formCity2').on('submit', function(e) {
                e.preventDefault();
            });
            $('#city1').keypress(function(e) {
                if(e.which == 13) {
                    $('.btn-add-city-1').click();
                }
            });
            $('#city2').keypress(function(e) {
                if(e.which == 13) {
                    $('.btn-add-city-2').click();
                }
            });
        });
    </script>
@endpush
