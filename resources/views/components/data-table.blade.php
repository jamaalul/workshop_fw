@props(['tableData' => []])

<div class="grid-margin col-lg-12 stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0 card-title">{{ $title }}</h4>
                @if ($createRoute)
                    <a href="{{ route($createRoute) }}" class="btn btn-primary btn-sm">
                        <i class="mr-1 mdi mdi-plus"></i>
                        Tambah Baru
                    </a>
                @endif
            </div>

            @if (empty($tableData) || count($tableData) === 0)
                <div class="py-5 text-center">
                    <i class="mdi-folder-open-outline text-muted mdi" style="font-size: 48px;"></i>
                    <h5 class="mt-3 text-muted">Tidak ada data</h5>
                    <p class="text-muted small">Belum ada data yang tersedia untuk ditampilkan saat ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @foreach (array_keys((array) $tableData[0]) as $header)
                                    @if ($header !== $idField)
                                        <th class="text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                                            {{ str_replace('_', ' ', $header) }}
                                        </th>
                                    @endif
                                @endforeach
                                <th class="text-right text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; width: 1%; white-space: nowrap;">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableData as $row)
                                <tr>
                                    @foreach ((array) $row as $key => $cell)
                                        @if ($key !== $idField)
                                            <td>
                                                {{ $cell }}
                                            </td>
                                        @endif
                                    @endforeach
                                    <td class="text-right" style="width: 1%; white-space: nowrap;">
                                        <div class="d-flex justify-content-end" style="gap: 0.5rem;">
                                            @if ($editRoute && isset($row[$idField]))
                                                <a href="{{ route($editRoute, $row[$idField]) }}"
                                                    class="d-flex align-items-center justify-content-center btn btn-inverse-primary btn-icon"
                                                    style="padding: 0.25rem; width: 28px; height: 28px;" title="Edit">
                                                    <i class="mdi mdi-pencil" style="font-size: 14px;"></i>
                                                </a>
                                            @else
                                                <button
                                                    class="d-flex align-items-center justify-content-center btn btn-inverse-secondary btn-icon"
                                                    style="padding: 0.25rem; width: 28px; height: 28px;" disabled
                                                    title="Edit route tidak disetel">
                                                    <i class="mdi mdi-pencil" style="font-size: 14px;"></i>
                                                </button>
                                            @endif

                                            @if ($deleteRoute && isset($row[$idField]))
                                                <form action="{{ route($deleteRoute, $row[$idField]) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="d-flex align-items-center justify-content-center btn btn-inverse-danger btn-icon"
                                                        style="padding: 0.25rem; width: 28px; height: 28px;"
                                                        title="Hapus">
                                                        <i class="mdi mdi-delete" style="font-size: 14px;"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button
                                                    class="d-flex align-items-center justify-content-center btn btn-inverse-secondary btn-icon"
                                                    style="padding: 0.25rem; width: 28px; height: 28px;" disabled
                                                    title="Delete tidak tersedia">
                                                    <i class="mdi mdi-delete" style="font-size: 14px;"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
