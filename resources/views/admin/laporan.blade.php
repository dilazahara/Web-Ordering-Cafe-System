<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan Analytics</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Inter',sans-serif;
    background:#f1f5f9;
    color:#0f172a;
}

/* TOPBAR */

.topbar{
    position:fixed;
    top:0;
    left:0;
    right:0;
    height:80px;
    background:rgba(255,255,255,.95);
    backdrop-filter:blur(18px);
    border-bottom:1px solid #e2e8f0;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 30px;
    z-index:1000;
}

.topbar-left{
    display:flex;
    align-items:center;
    gap:18px;
}

.topbar-left i{
    width:24px;
    height:24px;
    cursor:pointer;
    color:#475569;
}

/* SIDEBAR */

.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    top:0;
    left:0;
    background:linear-gradient(180deg,#0f172a,#1e1b4b);
    padding:30px;
    padding-top:100px;
    overflow-y:auto;
    transform:translateX(-100%);
    transition:.3s;
    z-index:999;
}

.sidebar.show{
    transform:translateX(0);
}

.menu-section{
    font-size:11px;
    letter-spacing:1px;
    color:#a78bfa;
    margin:18px 10px 8px;
}

.sidebar a{
    display:flex;
    align-items:center;
    gap:14px;
    padding:12px 14px;
    border-radius:12px;
    color:#94a3b8;
    text-decoration:none;
    transition:.2s;
    margin-bottom:6px;
}

.sidebar a:hover{
    background:rgba(255,255,255,.05);
    color:white;
}

.sidebar a.active{
    background:rgba(139,92,246,.20);
    color:#c4b5fd;
}

/* MAIN */

.main{
    padding:120px 30px 40px;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:28px;
    gap:20px;
    flex-wrap:wrap;
}

.page-header h1{
    font-size:34px;
    font-weight:800;
    margin-bottom:8px;
}

.page-header p{
    color:#64748b;
    font-size:15px;
}

/* ANALYTICS */

.analytics-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.analytics-card{
    background:white;
    border-radius:22px;
    padding:24px;
    box-shadow:0 4px 10px rgba(0,0,0,.04);
    position:relative;
    overflow:hidden;
    border:1px solid #e2e8f0;
}

.analytics-card::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:4px;
}

/* WARNA CARD */

.analytics-card.orange::before{
    background:#f97316;
}

.analytics-card.blue::before{
    background:#3b82f6;
}

.analytics-card.cyan::before{
    background:#06b6d4;
}

.analytics-card.green::before{
    background:#22c55e;
}

/* ICON BOX */

.icon-box{
    width:52px;
    height:52px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:18px;
}

.icon-box i{
    width:24px;
    height:24px;
}

/* ICON COLOR */

.orange .icon-box{
    background:#fff7ed;
    color:#f97316;
}

.blue .icon-box{
    background:#eff6ff;
    color:#3b82f6;
}

.cyan .icon-box{
    background:#ecfeff;
    color:#06b6d4;
}

.green .icon-box{
    background:#f0fdf4;
    color:#22c55e;
}

.analytics-label{
    font-size:14px;
    color:#64748b;
    margin-bottom:10px;
    font-weight:700;
}

.analytics-value{
    font-size:36px;
    font-weight:800;
    color:#0f172a;
}

.analytics-sub{
    margin-top:8px;
    font-size:13px;
    font-weight:600;
}

.orange .analytics-sub{
    color:#f97316;
}

.blue .analytics-sub{
    color:#3b82f6;
}

.cyan .analytics-sub{
    color:#06b6d4;
}

.green .analytics-sub{
    color:#22c55e;
}

/* FILTER */

.filter-box{
    background:white;
    padding:25px;
    border-radius:24px;
    margin-bottom:30px;
    box-shadow:0 4px 10px rgba(0,0,0,.04);
}

.filter-form{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
    align-items:center;
}

.filter-form select,
.filter-form input{
    padding:12px 16px;
    border:1px solid #dbe2ea;
    border-radius:14px;
    font-size:14px;
    min-width:160px;
    background:white;
}

.filter-form button,
.export-btn{
    padding:12px 20px;
    border:none;
    border-radius:14px;
    color:white;
    cursor:pointer;
    font-weight:600;
    display:inline-flex;
    align-items:center;
    gap:8px;
    text-decoration:none;
}

.filter-btn{
    background:#3b82f6;
}

.export-btn{
    background:#ef4444;
}

/* TABLE */

.table-box{
    background:white;
    border-radius:24px;
    padding:25px;
    box-shadow:0 4px 10px rgba(0,0,0,.04);
}

.table-title{
    font-size:22px;
    font-weight:700;
    margin-bottom:24px;
}

.table-wrapper{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:950px;
}

thead{
    background:#f8fafc;
}

th{
    padding:18px 16px;
    font-size:12px;
    text-transform:uppercase;
    color:#64748b;
    text-align:left;
    border-bottom:2px solid #e2e8f0;
}

td{
    padding:18px 16px;
    border-bottom:1px solid #f1f5f9;
    vertical-align:middle;
}

tbody tr:hover{
    background:#f8fafc;
}

/* STATUS */

.status{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.status.pending{
    background:#fef3c7;
    color:#92400e;
}

.status.process{
    background:#dbeafe;
    color:#1e40af;
}

.status.done{
    background:#dcfce7;
    color:#166534;
}

/* TOTAL */

.total-row td{
    border-top:2px solid #3b82f6;
    font-weight:800;
    font-size:15px;
}

/* RESPONSIVE */

@media(max-width:768px){

    .main{
        padding:110px 18px 30px;
    }

    .analytics-grid{
        grid-template-columns:1fr;
    }

    .filter-form{
        flex-direction:column;
        align-items:stretch;
    }

    .filter-form select,
    .filter-form input{
        width:100%;
    }

}

</style>
</head>

<body>

<div class="topbar">

<div class="topbar-left">

<i data-lucide="menu"
   onclick="toggleSidebar()"></i>

</div>

</div>

<div class="sidebar" id="sidebar">

<div class="menu-section">MAIN</div>

<a href="/admin/dashboard">
<i data-lucide="layout-dashboard"></i>
Dashboard
</a>

<a href="/admin/order">
<i data-lucide="receipt"></i>
Orders
</a>

<div class="menu-section">KATALOG</div>

<a href="/admin/menu">
<i data-lucide="utensils"></i>
Menu
</a>

<a href="/admin/kategori">
<i data-lucide="folder"></i>
Kategori
</a>

<a href="/admin/addons">
<i data-lucide="plus-circle"></i>
Add-ons
</a>

<div class="menu-section">OPERASIONAL</div>

<a href="/admin/meja">
<i data-lucide="armchair"></i>
Meja
</a>

<a href="/admin/pembayaran">
<i data-lucide="credit-card"></i>
Pembayaran
</a>

<div class="menu-section">ANALYTICS</div>

<a href="/admin/laporan" class="active">
<i data-lucide="bar-chart-3"></i>
Laporan
</a>

<div class="menu-section">SYSTEM</div>

<a href="/admin/user">
<i data-lucide="users"></i>
User
</a>

</div>

<div class="main">

@php

$totalPendapatan = $orders->sum('total');

$cash = $orders
->where('payment_method','cash')
->sum('total');

$qris = $orders
->where('payment_method','qris')
->sum('total');

$totalOrder = $orders->count();

@endphp

<div class="page-header">

<div>

<h1>Laporan Penjualan</h1>

<p>
Pantau performa bisnis dan transaksi penjualan secara realtime
</p>

</div>

</div>

<!-- ANALYTICS -->

<div class="analytics-grid">

<div class="analytics-card orange">

<div class="icon-box">
<i data-lucide="wallet"></i>
</div>

<div class="analytics-label">
Total Pendapatan
</div>

<div class="analytics-value">
Rp {{ number_format($totalPendapatan,0,',','.') }}
</div>

<div class="analytics-sub">
🔥 Semua transaksi berhasil
</div>

</div>

<div class="analytics-card blue">

<div class="icon-box">
<i data-lucide="shopping-bag"></i>
</div>

<div class="analytics-label">
Total Transaksi
</div>

<div class="analytics-value">
{{ $totalOrder }}
</div>

<div class="analytics-sub">
📦 Order masuk
</div>

</div>

<div class="analytics-card cyan">

<div class="icon-box">
<i data-lucide="badge-dollar-sign"></i>
</div>

<div class="analytics-label">
Pembayaran Cash
</div>

<div class="analytics-value">
Rp {{ number_format($cash,0,',','.') }}
</div>

<div class="analytics-sub">
💵 Pembayaran tunai
</div>

</div>

<div class="analytics-card green">

<div class="icon-box">
<i data-lucide="smartphone"></i>
</div>

<div class="analytics-label">
Pembayaran QRIS
</div>

<div class="analytics-value">
Rp {{ number_format($qris,0,',','.') }}
</div>

<div class="analytics-sub">
📱 Pembayaran digital
</div>

</div>

</div>

<!-- FILTER -->

<div class="filter-box">

<form method="GET"
action="/admin/laporan"
class="filter-form">

<select name="filter">

<option value="">
Semua Periode
</option>

<option value="hari"
{{ request('filter') == 'hari' ? 'selected' : '' }}>
Hari Ini
</option>

<option value="bulan"
{{ request('filter') == 'bulan' ? 'selected' : '' }}>
Bulan Ini
</option>

<option value="tahun"
{{ request('filter') == 'tahun' ? 'selected' : '' }}>
Tahun Ini
</option>

</select>

<input type="date"
name="tanggal"
value="{{ request('tanggal') }}">

<button type="submit"
class="filter-btn">

<i data-lucide="search"></i>

Filter

</button>

<a href="{{ route('admin.laporan.pdf') }}"
class="export-btn">

<i data-lucide="file-down"></i>

Export PDF

</a>

</form>

</div>

<!-- TABLE -->

<div class="table-box">

<div class="table-title">
Data Transaksi
</div>

<div class="table-wrapper">

<table>

<thead>

<tr>
<th>No</th>
<th>Waktu</th>
<th>ID Order</th>
<th>Meja</th>
<th>Detail Pesanan</th>
<th>Status</th>
<th>Pembayaran</th>
<th style="text-align:right;">
Total
</th>
</tr>

</thead>

<tbody>

@forelse($orders as $index => $order)

<tr>

<td>
{{ $index + 1 }}
</td>

<td>

<div style="font-weight:700;">
{{ $order->created_at->format('d M Y') }}
</div>

<div style="font-size:12px;color:#64748b;">
{{ $order->created_at->format('H:i') }} WIB
</div>

</td>

<td style="font-weight:800;color:#7c3aed;">

{{ $order->queue_number ?: 'A-' . str_pad($order->id,3,'0',STR_PAD_LEFT) }}

</td>

<td>

{{ $order->table_number ? 'Meja '.$order->table_number : 'Take Away' }}

</td>

<td style="min-width:220px;">

@foreach($order->items->take(2) as $item)

<div>

{{ $item->qty }}x
{{ $item->menu->name ?? '-' }}

</div>

@endforeach

</td>

<td>

@if($order->status == 'pending')

<span class="status pending">
Pending
</span>

@elseif($order->status == 'process')

<span class="status process">
Diproses
</span>

@else

<span class="status done">
Selesai
</span>

@endif

</td>

<td>

@if($order->payment_method == 'cash')

💵 Cash

@else

📱 QRIS

@endif

</td>

<td style="text-align:right;font-weight:800;">

Rp {{ number_format($order->total,0,',','.') }}

</td>

</tr>

@empty

<tr>

<td colspan="8"
style="text-align:center;padding:50px;color:#94a3b8;">

Tidak ada data transaksi

</td>

</tr>

@endforelse

@if(count($orders) > 0)

<tr class="total-row">

<td colspan="7"
style="text-align:right;">

TOTAL PENDAPATAN

</td>

<td style="text-align:right;color:#059669;">

Rp {{ number_format($totalPendapatan,0,',','.') }}

</td>

</tr>

@endif

</tbody>

</table>

</div>

</div>

</div>

<script>

lucide.createIcons();

function toggleSidebar(){

document
.getElementById('sidebar')
.classList
.toggle('show');

}

</script>

</body>
</html>

