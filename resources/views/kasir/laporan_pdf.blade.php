<h2>Laporan Penjualan</h2>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Waktu</th>
        <th>Order</th>
        <th>Total</th>
    </tr>

    @php $total = 0; @endphp

    @foreach($orders as $i => $o)
    @php $total += $o->total; @endphp
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $o->created_at }}</td>
        <td>{{ $order->queue_number }}</td>
        <td>Rp {{ number_format($o->total) }}</td>
    </tr>
    @endforeach

    <tr>
        <td colspan="3"><b>Total</b></td>
        <td><b>Rp {{ number_format($total) }}</b></td>
    </tr>
</table>