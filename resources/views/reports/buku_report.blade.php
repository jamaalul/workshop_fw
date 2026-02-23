<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Data Buku</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18pt;
        }

        .header p {
            margin: 5px 0;
            font-size: 10pt;
        }

        .report-title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10pt;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>WORKSHOP FRAMEWORK LARAVEL</h1>
        <p>Jl. Dharmawangsa Dalam Selatan No.28 – 30, Airlangga, Kec. Gubeng, Surabaya, Jawa Timur 60286</p>
        <p>Email: jamaalul@gmail.com | Telp: (021) 1234567</p>
    </div>

    <div class="report-title">Laporan Data Buku</div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Buku</th>
                <th>Judul Buku</th>
                <th width="20%">Pengarang</th>
                <th width="15%">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($buku as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->pengarang }}</td>
                    <td>{{ $item->kategori->nama_kategori }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>

</html>
