<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Label Grid</title>
    <style>
        /* F4 paper size for printing */
        @page {
            size: 215.9mm 330mm;
            margin: 0;
            padding: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
        }

        .page {
            page-break-after: always;
            width: 215.9mm;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            width: 38mm;
            height: 18mm;
            text-align: center;
            vertical-align: middle;
            padding: 1mm;
        }

        .label-nama {
            font-size: 7pt;
            font-weight: bold;
            line-height: 1.2;
            margin-bottom: 1mm;
            word-wrap: break-word;
            overflow: hidden;
        }

        .label-harga {
            font-size: 9pt;
            font-weight: bold;
            color: #000;
        }

        /* Optional: Make browser preview closer to print size */
        @media screen {
            body {
                display: flex;
                justify-content: center;
            }

            .page {
                background: white;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                margin: 20px 0;
            }
        }
    </style>
</head>

<body>
    @php
        $cols = 5;
        $rows = 8;
        $totalPerPage = $cols * $rows;

        // Calculate how many cells to skip on first page
        $skipCells = ($startY - 1) * $cols + ($startX - 1);

        // Build array of all cells needed
        $allLabels = [];

        // First page: add empty cells for skipped positions
        for ($i = 0; $i < $skipCells; $i++) {
            $allLabels[] = null;
        }

        // Add actual barang data
        foreach ($barangs as $item) {
            $allLabels[] = $item;
        }

        // Calculate total pages
        $totalLabels = count($allLabels);
        $totalPages = ceil($totalLabels / $totalPerPage);
        if ($totalPages < 1) $totalPages = 1;
    @endphp

    @for ($page = 0; $page < $totalPages; $page++)
        <div class="page">
            <table>
                <tbody>
                    @for ($row = 0; $row < $rows; $row++)
                        <tr>
                            @for ($col = 0; $col < $cols; $col++)
                                @php
                                    $index = $page * $totalPerPage + $row * $cols + $col;
                                    $item = $index < $totalLabels ? $allLabels[$index] : null;
                                @endphp
                                <td>
                                    @if ($item)
                                        <div class="label-nama">{{ $item->nama }}</div>
                                        <div class="label-harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    @endfor
</body>

</html>