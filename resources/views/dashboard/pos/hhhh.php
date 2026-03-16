@extends('layouts.dashboard')

@section('title', 'Point of Sales')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Point of Sales Case Studies </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">POS</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-jquery text-primary icon-md"></i>
                        <div class="ms-3">
                            <h4 class="card-title">POS (jQuery AJAX)</h4>
                            <p class="card-description">Implementation using jQuery and traditional AJAX requests.</p>
                            <a href="{{ route('pos.jquery') }}" class="btn btn-gradient-primary">Open Version</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row align-items-top">
                        <i class="mdi mdi-axis-arrow text-success icon-md"></i>
                        <div class="ms-3">
                            <h4 class="card-title">POS (Axios)</h4>
                            <p class="card-description">Modern implementation using Axios library.</p>
                            <a href="{{ route('pos.axios') }}" class="btn btn-gradient-success">Open Version</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
