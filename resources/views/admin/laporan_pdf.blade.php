<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>

    <style>
        body {
            font-family: sans-serif;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        .total {
            font-weight: bold;
            background: #eee;
        }
    </style>
</head>

<body>

    <h2>Laporan Penjualan</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Order</th>
                <th>Meja</th>
                <th>Total</th>
                <th>Metode</th>
            </tr>
        </thead>

        <tbody>
            @php $totalSemua = 0; @endphp

            @foreach($data as $index => $item)
            @php $totalSemua += $item['total'] ?? 0; @endphp

            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['tanggal'] ?? '-' }}</td>
                <td>{{ $item['kode'] ?? '-' }}</td>
                <td>{{ $item['meja'] ?? '-' }}</td>
                <td>Rp {{ number_format($item['total'] ?? 0) }}</td>
                <td>{{ $item['metode'] ?? '-' }}</td>
            </tr>
            @endforeach

            @if(count($data) == 0)
            <tr>
                <td colspan="6">Tidak ada data</td>
            </tr>
            @endif

            <tr class="total">
                <td colspan="4">TOTAL</td>
                <td colspan="2">Rp {{ number_format($totalSemua) }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>