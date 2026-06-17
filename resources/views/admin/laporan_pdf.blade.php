<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { font-size: 18px; font-weight: bold; color: #111; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #666; }
        .summary { display: flex; gap: 10px; margin-bottom: 18px; }
        .sum-box { border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 16px; flex: 1; }
        .sum-box .label { font-size: 10px; color: #666; margin-bottom: 4px; font-weight: bold; text-transform: uppercase; }
        .sum-box .val { font-size: 14px; font-weight: bold; color: #111; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 9px 8px; text-align: left; vertical-align: top; }
        th { background: #f8fafc; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 10px; color: #555; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background: #f1f5f9; font-size: 13px; color: #111; }
        .item-list { margin: 0; padding: 0; list-style: none; }
        .item-list li { margin-bottom: 2px; }
        .badge-text { font-weight: bold; font-size: 10px; padding: 3px 6px; border-radius: 4px; display: inline-block; }
        .b-cash     { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .b-qris     { background: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe; }
        .b-midtrans { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .s-process  { background: #dbeafe; color: #1e40af; font-weight: bold; font-size: 10px; padding: 2px 8px; border-radius: 20px; display:inline-block; }
        .s-paid     { background: #fef9c3; color: #854d0e; font-weight: bold; font-size: 10px; padding: 2px 8px; border-radius: 20px; display:inline-block; }
        .s-done     { background: #dcfce7; color: #166534; font-weight: bold; font-size: 10px; padding: 2px 8px; border-radius: 20px; display:inline-block; }
        .s-completed { background: #dcfce7; color: #166534; font-weight: bold; font-size: 10px; padding: 2px 8px; border-radius: 20px; display:inline-block; }
    </style>
</head>
<body>

@php
$midtransMethods = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans'];
$midtransNames   = [
    'gopay'=>'GoPay','ovo'=>'OVO','dana'=>'DANA','shopeepay'=>'ShopeePay',
    'bca'=>'BCA VA','bni'=>'BNI VA','bri'=>'BRI VA','mandiri'=>'Mandiri',
    'permata'=>'Permata VA','credit_card'=>'Kartu Kredit','midtrans'=>'Online',
];

$totalSemua    = $orders->sum('total');
@endphp

<div class="header">
    <h2>LAPORAN PENJUALAN</h2>
    <p>Tanggal: {{ $tanggalLabel ?? now()->translatedFormat('d F Y') }} &nbsp;|&nbsp; Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
</div>

<!-- Detail -->
<table>
    <thead>
        <tr>
            <th width="3%">No</th>
            <th width="11%">Waktu</th>
            <th width="8%">ID Order</th>
            <th width="12%">Nama Pemesan</th>
            <th width="10%">Tipe &amp; Meja</th>
            <th width="25%">Detail Pesanan</th>
            <th width="11%">Metode Bayar</th>
            <th width="9%">Status</th>
            <th width="13%">Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $index => $order)
        @php
            // Label metode bayar
            if ($order->payment_method === 'cash') {
                $payLabel = 'CASH';
                $payClass = 'b-cash';
            } elseif ($order->payment_method === 'qris') {
                $payLabel = 'QRIS';
                $payClass = 'b-qris';
            } elseif (in_array($order->payment_method, $midtransMethods)) {
                $payLabel = $midtransNames[$order->payment_method] ?? strtoupper($order->payment_method);
                $payClass = 'b-midtrans';
            } else {
                $payLabel = strtoupper($order->payment_method ?? '-');
                $payClass = 'b-midtrans';
            }

            // Label status
            if ($order->status === 'process') {
                $statusLabel = 'Diproses';
                $statusClass = 's-process';
            } elseif ($order->status === 'paid') {
                $statusLabel = 'Lunas';
                $statusClass = 's-paid';
            } elseif (in_array($order->status, ['done','delivered'])) {
                $statusLabel = 'Selesai';
                $statusClass = 's-done';
            } elseif ($order->status === 'completed') {
                $statusLabel = 'Selesai Diambil';
                $statusClass = 's-completed';
            } else {
                $statusLabel = ucfirst($order->status ?? '-');
                $statusClass = 's-done';
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
                @if($order->isTakeAway())
                    Take Away
                @else
                    Dine In{{ $order->table_number ? ' / Meja ' . $order->table_number : '' }}
                @endif
            </td>
            <td>
                @if($order->items && $order->items->count() > 0)
                    <ul class="item-list">
                        @foreach($order->items as $item)
                            <li>
                                {{ $item->qty ?? $item->quantity ?? 1 }}x {{ $item->menu->name ?? $item->name ?? '-' }}
                                {{-- ✅ FIX: tampilkan add-on di laporan PDF admin --}}
                                @if(!empty($item->addon_details))
                                <span style="color:#f97316;font-size:9px;"> ({{ collect($item->addon_details)->pluck('name')->join(', ') }})</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span style="color:#aaa;">-</span>
                @endif
            </td>
            <td class="text-center">
                <span class="badge-text {{ $payClass }}">{{ $payLabel }}</span>
            </td>
            <td class="text-center">
                <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
            </td>
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