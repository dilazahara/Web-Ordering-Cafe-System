<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; margin-bottom: 20px; color: #111; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px 8px; text-align: left; vertical-align: top; }
        th { background: #f8fafc; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 11px; color: #555; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background: #f1f5f9; font-size: 13px; color: #111; }
        .item-list { margin: 0; padding-left: 15px; }
        .badge-text { font-weight: bold; font-size: 10px; padding: 3px 6px; border-radius: 4px; display: inline-block; }
        .b-cash { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .b-qris { background: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe; }
    </style>
</head>
<body>

    <h2>LAPORAN PENJUALAN</h2>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="15%">Waktu Transaksi</th>
                <th width="10%">ID Order</th>
                <th width="12%">Meja/Tipe</th>
                <th width="25%">Detail Pesanan</th>
                <th width="10%">Metode</th>
                <th width="10%">Status</th>
                <th width="15%">Total Harga</th>
            </tr>
        </thead>

        <tbody>
            @php $totalSemua = 0; @endphp

            @forelse($orders as $index => $order)
            @php 
                $isObj = is_object($order);
                $totalHarga = $isObj ? ($order->total ?? 0) : ($order['total'] ?? 0);
                $totalSemua += $totalHarga; 
                
                $rawDate = $isObj ? ($order->created_at ?? now()) : ($order['created_at'] ?? now());
                $createdAt = \Carbon\Carbon::parse($rawDate);
                
                $queueNumber = $isObj ? ($order->queue_number ?? null) : ($order['queue_number'] ?? null);
                $idOrder = $isObj ? ($order->id ?? 0) : ($order['id'] ?? 0);
                $tableNumber = $isObj ? ($order->table_number ?? null) : ($order['table_number'] ?? null);
                $statusOrder = $isObj ? ($order->status ?? 'pending') : ($order['status'] ?? 'pending');
                $paymentMethod = $isObj ? ($order->payment_method ?? 'cash') : ($order['payment_method'] ?? 'cash');

                $rawItems = $isObj ? ($order->items ?? []) : ($order['items'] ?? []);
                $items = is_string($rawItems) ? json_decode($rawItems, true) : $rawItems;
                
                $itemsList = [];
                if (!empty($items)) {
                    $iterableItems = is_array($items) ? $items : (is_iterable($items) ? $items : []);
                    foreach($iterableItems as $item) {
                        $isItemObj = is_object($item);
                        $qty = $isItemObj ? ($item->qty ?? $item->quantity ?? 1) : ($item['qty'] ?? $item['quantity'] ?? 1);
                        $name = $isItemObj ? ($item->name ?? $item->menu->name ?? $item->menu->name ?? '-') : ($item['name'] ?? $item['menu']['name'] ?? $item['menu']['name'] ?? '-');
                        $itemsList[] = $qty . 'x ' . $name;
                    }
                }
            @endphp

            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $createdAt->format('d M Y, H:i') }}</td>
                <td class="text-center" style="font-weight: bold;">{{ $queueNumber ?: 'A-' . str_pad($idOrder, 3, '0', STR_PAD_LEFT) }}</td>
                <td class="text-center">{{ $tableNumber ? 'Meja '.$tableNumber : 'Take Away' }}</td>
                <td>
                    @if(count($itemsList) > 0)
                        <ul class="item-list">
                        @foreach($itemsList as $str)
                            <li>{{ $str }}</li>
                        @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($paymentMethod === 'cash')
                        <span class="badge-text b-cash">CASH</span>
                    @elseif($paymentMethod === 'qris')
                        <span class="badge-text b-qris">QRIS</span>
                    @else
                        {{ strtoupper($paymentMethod ?? 'Tunai') }}
                    @endif
                </td>
                <td class="text-center">
                    @php
                        if ($statusOrder === 'pending') {
                            $textStatus = 'Menunggu Bayar';
                        } elseif ($statusOrder === 'paid') {
                            $textStatus = 'Lunas';
                        } elseif ($statusOrder === 'process') {
                            $textStatus = 'Diproses';
                        } elseif (in_array($statusOrder, ['done', 'delivered'])) {
                            $textStatus = 'Selesai';
                        } else {
                            $textStatus = ucfirst($statusOrder);
                        }
                    @endphp
                    {{ $textStatus }}
                </td>
                <td class="text-right">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data penjualan pada periode ini.</td>
            </tr>
            @endforelse

            @if(count($orders) > 0)
            <tr class="total-row">
                <td colspan="7" class="text-right" style="padding-right: 15px;">TOTAL PENDAPATAN :</td>
                <td class="text-right">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

</body>
</html>