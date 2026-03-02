@extends('layouts.dashboard')

@section('title', 'Barang')

@section('content')
    <div class="row">
        <x-data-table :tableData="$tableData" :model="$model" :idField="$idField" :title="$title" :createRoute="$createRoute"
            :editRoute="$editRoute" :deleteRoute="$deleteRoute" :labelRoute="$labelRoute" />
    </div>

    {{-- Modal Cetak Label --}}
    <div class="modal fade" id="labelModal" tabindex="-1" aria-labelledby="labelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="labelForm" action="{{ route('barang.printLabel') }}" method="POST" target="_blank">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="labelModalLabel">
                            <i class="mdi mdi-label-outline me-1"></i>
                            Cetak Label Harga
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            Kertas Label TnJ No. 108 — 5 kolom × 8 baris (40 label per lembar).
                            Tentukan posisi kolom dan baris pertama tempat label mulai dicetak.
                        </p>

                        <div class="row g-3">
                            <div class="col-6">
                                <label for="start_x" class="form-label fw-bold">Posisi Kolom (X)</label>
                                <input type="number" class="form-control" id="start_x" name="start_x"
                                    min="1" max="5" value="1" required>
                                <div class="form-text">1 – 5 (kiri ke kanan)</div>
                            </div>
                            <div class="col-6">
                                <label for="start_y" class="form-label fw-bold">Posisi Baris (Y)</label>
                                <input type="number" class="form-control" id="start_y" name="start_y"
                                    min="1" max="8" value="1" required>
                                <div class="form-text">1 – 8 (atas ke bawah)</div>
                            </div>
                        </div>

                        {{-- Grid visualization --}}
                        <div class="mt-3">
                            <label class="form-label fw-bold">Preview Posisi Awal</label>
                            <div class="border rounded p-2" style="background: #f8f9fa;">
                                <table class="table table-bordered table-sm mb-0 text-center" style="table-layout: fixed;" id="previewGrid">
                                    <tbody>
                                        @for ($y = 1; $y <= 8; $y++)
                                            <tr>
                                                @for ($x = 1; $x <= 5; $x++)
                                                    <td class="preview-cell"
                                                        data-x="{{ $x }}" data-y="{{ $y }}"
                                                        style="height: 24px; font-size: 9px; padding: 2px; color: #aaa;">
                                                        {{ $x }},{{ $y }}
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Hidden inputs for selected IDs --}}
                        <div id="selectedIdsContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-printer me-1"></i>
                            Cetak Label
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labelModal = document.getElementById('labelModal');
        const startX = document.getElementById('start_x');
        const startY = document.getElementById('start_y');
        const container = document.getElementById('selectedIdsContainer');

        function updatePreview() {
            const x = parseInt(startX.value) || 1;
            const y = parseInt(startY.value) || 1;
            document.querySelectorAll('.preview-cell').forEach(cell => {
                const cx = parseInt(cell.dataset.x);
                const cy = parseInt(cell.dataset.y);
                if (cy < y || (cy === y && cx < x)) {
                    cell.style.background = '#e9ecef';
                    cell.style.color = '#aaa';
                    cell.textContent = '';
                } else {
                    cell.style.background = '#fff3cd';
                    cell.style.color = '#856404';
                    cell.textContent = '✕';
                }
            });
        }

        startX.addEventListener('input', updatePreview);
        startY.addEventListener('input', updatePreview);

        labelModal.addEventListener('show.bs.modal', function () {
            // Populate hidden inputs with selected IDs
            container.innerHTML = '';
            document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
            updatePreview();
        });
    });
</script>
@endpush
