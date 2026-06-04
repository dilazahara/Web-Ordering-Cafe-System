<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Harian</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header-wrap { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #e2e8f0; padding-bottom: 16px; }
        h2 { margin: 0 0 6px 0; color: #111; font-weight: bold; font-size: 18px; letter-spacing: 0.5px; }
        .sub-title { font-size: 12px; color: #555; margin: 0; }
        .sub-title strong { color: #1e40af; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px 8px; text-align: left; vertical-align: top; }
        th { background: #f8fafc; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 11px; color: #555; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background: #f1f5f9; font-size: 13px; color: #111; }
        .item-list { margin: 0; padding: 0; list-style: none; }
        .item-list li { margin-bottom: 2px; }
        .badge-text { font-weight: bold; font-size: 10px; padding: 3px 6px; border-radius: 4px; display: inline-block; }
        .b-cash { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .b-qris { background: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe; }
    </style>
</head>
<body>

    <div class="header-wrap">
        <h2>LAPORAN PENJUALAN</h2>
        <p class="sub-title">Tanggal: <strong>{{ $tanggalLabel }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Waktu Transaksi</th>
                <th width="8%">ID Order</th>
                <th width="11%">Nama Pemesan</th>
                <th width="9%">Meja</th>
                <th width="22%">Detail Pesanan</th>
                <th width="9%">Metode</th>
                <th width="9%">Status</th>
                <th width="12%">Total Harga</th>
            </tr>
        </thead>

        <tbody>
            @php $totalSemua = 0; @endphp

            @forelse($orders as $index => $order)
            @php
                $totalSemua += $order->total ?? 0;

                if ($order->status === 'pending') {
                    $textStatus = 'Menunggu Bayar';
                } elseif ($order->status === 'paid') {
                    $textStatus = 'Lunas';
                } elseif ($order->status === 'process') {
                    $textStatus = 'Diproses';
                } elseif (in_array($order->status, ['done', 'delivered'])) {
                    $textStatus = 'Selesai';
                } else {
                    $textStatus = ucfirst($order->status ?? '-');
                }
            @endphp

            <tr>
                <td class="text-center">{{ $index + 1 }}</td>

                <td class="text-center">
                    {{ $order->created_at->format('d M Y') }}<br>
                    <span style="color:#888;">{{ $order->created_at->format('H:i') }} WIB</span>
                </td>

                <td class="text-center" style="font-weight:bold;">
                    {{ $order->queue_number ?: 'A-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                </td>

                <td>{{ $order->customer_name ?: '—' }}</td>

                <td class="text-center">
                    {{ $order->table_number ? 'Meja ' . $order->table_number : 'Take Away' }}
                </td>

                <td>
                    @if($order->items && $order->items->count() > 0)
                        <ul class="item-list">
                            @foreach($order->items as $item)
                                <li>{{ $item->qty ?? $item->quantity ?? 1 }}x {{ $item->menu->name ?? $item->name ?? $item->menu_name ?? '-' }}</li>
                            @endforeach
                        </ul>
                    @else
                        <span style="color:#aaa;">-</span>
                    @endif
                </td>

                <td class="text-center">
                    @if($order->payment_method === 'cash')
                        <span class="badge-text b-cash">CASH</span>
                    @elseif($order->payment_method === 'qris')
                        <span class="badge-text b-qris">QRIS</span>
                    @else
                        {{ strtoupper($order->payment_method ?? '-') }}
                    @endif
                </td>

                <td class="text-center">{{ $textStatus }}</td>

                <td class="text-right">Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</td>
            </tr>

            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding:20px;">
                    Tidak ada data penjualan pada periode ini.
                </td>
            </tr>
            @endforelse

            @if($orders->count() > 0)
            <tr class="total-row">
                <td colspan="8" class="text-right" style="padding-right:15px;">TOTAL PENDAPATAN :</td>
                <td class="text-right">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

</body>
</html>