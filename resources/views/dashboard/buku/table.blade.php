@extends('layouts.dashboard')

@section('title', 'Buku')

@section('content')
    <div class="row">
        <x-data-table :tableData="$tableData" :model="$model" :idField="$idField" :title="$title" :createRoute="$createRoute"
            :editRoute="$editRoute" :deleteRoute="$deleteRoute" :printAllRoute="'laporan.buku'" />
    </div>
@endsection

@push('scripts')
    {{-- javascript --}}
@endpush
