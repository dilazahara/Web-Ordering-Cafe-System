@extends('layouts.admin')

@section('title', 'Laporan Analytics')

@push('styles')
<style>
*{ margin:0; padding:0; box-sizing:border-box; }
body{ font-family:'Inter',sans-serif; background:#f1f5f9; color:#0f172a; }

/* TOPBAR */
.topbar{ position:fixed; top:0; left:0; right:0; height:80px; background:rgba(255,255,255,.95); backdrop-filter:blur(18px); border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; padding:0 30px; z-index:1000; }
.topbar-left{ display:flex; align-items:center; gap:18px; }
.topbar-left i{ width:24px; height:24px; cursor:pointer; color:#475569; }

/* SIDEBAR */
.sidebar{ width:240px; height:100vh; position:fixed; top:0; left:0; background:linear-gradient(180deg,#0f172a,#1e1b4b); padding:30px; padding-top:100px; overflow-y:auto; transform:translateX(-100%); transition:.3s; z-index:999; }
.sidebar.show{ transform:translateX(0); }
.menu-section{ font-size:11px; letter-spacing:1px; color:#a78bfa; margin:18px 10px 8px; }
.sidebar a{ display:flex; align-items:center; gap:14px; padding:12px 14px; border-radius:12px; color:#94a3b8; text-decoration:none; transition:.2s; margin-bottom:6px; }
.sidebar a:hover{ background:rgba(255,255,255,.05); color:white; }
.sidebar a.active{ background:rgba(139,92,246,.20); color:#c4b5fd; }

/* MAIN */
.main{ padding:120px 30px 40px; }
.page-header{ display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px; gap:20px; flex-wrap:wrap; }
.page-header h1{ font-size:34px; font-weight:800; margin-bottom:8px; }
.page-header p{ color:#64748b; font-size:15px; }

/* ANALYTICS */
.analytics-grid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom:30px; }
.analytics-card{ background:white; border-radius:22px; padding:24px; box-shadow:0 4px 10px rgba(0,0,0,.04); position:relative; overflow:hidden; border:1px solid #e2e8f0; }
.analytics-card::before{ content:''; position:absolute; top:0; left:0; width:100%; height:4px; }
.analytics-card.orange::before{ background:#f97316; }
.analytics-card.blue::before{ background:#3b82f6; }
.analytics-card.cyan::before{ background:#06b6d4; }
.analytics-card.green::before{ background:#22c55e; }
.icon-box{ width:52px; height:52px; border-radius:16px; display:flex; align-items:center; justify-content:center; margin-bottom:18px; }
.icon-box i{ width:24px; height:24px; }
.orange .icon-box{ background:#fff7ed; color:#f97316; }
.blue .icon-box{ background:#eff6ff; color:#3b82f6; }
.cyan .icon-box{ background:#ecfeff; color:#06b6d4; }
.green .icon-box{ background:#f0fdf4; color:#22c55e; }
.analytics-label{ font-size:14px; color:#64748b; margin-bottom:10px; font-weight:700; }
.analytics-value{ font-size:36px; font-weight:800; color:#0f172a; }
.analytics-sub{ margin-top:8px; font-size:13px; font-weight:600; }
.orange .analytics-sub{ color:#f97316; }
.blue .analytics-sub{ color:#3b82f6; }
.cyan .analytics-sub{ color:#06b6d4; }
.green .analytics-sub{ color:#22c55e; }

/* FILTER */
.filter-box{ background:white; padding:25px; border-radius:24px; margin-bottom:30px; box-shadow:0 4px 10px rgba(0,0,0,.04); }
.filter-form{ display:flex; gap:15px; flex-wrap:wrap; align-items:center; }
.filter-form select, .filter-form input{ padding:12px 16px; border:1px solid #dbe2ea; border-radius:14px; font-size:14px; min-width:160px; background:white; }
.filter-form button, .export-btn{ padding:12px 20px; border:none; border-radius:14px; color:white; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
.filter-btn{ background:#3b82f6; }
.export-btn{ background:#ef4444; }

/* TABLE */
.table-box{ background:white; border-radius:24px; padding:25px; box-shadow:0 4px 10px rgba(0,0,0,.04); }

/* TABLE TITLE */
.table-title-wrap{ display:flex; align-items:center; gap:14px; margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #f1f5f9; }
.table-title-icon{ width:44px; height:44px; border-radius:14px; background:#eff6ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.table-title-icon i{ width:22px; height:22px; color:#3b82f6; }
.table-title-text h2{ font-size:20px; font-weight:700; color:#0f172a; line-height:1.2; }
.table-title-text p{ font-size:13px; color:#64748b; margin-top:3px; }

/* ── TOOLBAR ── */
.table-toolbar{
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:18px; flex-wrap:wrap; gap:12px;
    padding:14px 18px; background:#f8fafc;
    border-radius:14px; border:1px solid #f1f5f9;
}
.toolbar-left{ display:flex; align-items:center; gap:10px; }
.toolbar-label{ font-size:13px; color:#64748b; font-weight:500; }
.per-page-select{
    padding:7px 32px 7px 12px; border:1.5px solid #e2e8f0;
    border-radius:9px; font-size:13px; font-family:'Inter',sans-serif;
    outline:none; color:#1e293b; cursor:pointer; background:white;
    transition:border-color 0.2s; appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 10px center;
}
.per-page-select:focus{ border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
.toolbar-right{ display:flex; align-items:center; gap:8px; }
.toolbar-cari-label{ font-size:13px; color:#64748b; font-weight:500; }
.search-wrap{ position:relative; display:flex; align-items:center; }
.search-wrap i{ position:absolute; left:12px; width:15px; height:15px; color:#94a3b8; }
.search-input{
    padding:9px 14px 9px 36px; border:1.5px solid #e2e8f0;
    border-radius:10px; font-size:13px; font-family:'Inter',sans-serif;
    outline:none; width:230px; color:#1e293b; transition:border-color 0.2s;
}
.search-input:focus{ border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }

/* EMPTY STATE */
.empty-state{ display:flex; flex-direction:column; align-items:center; justify-content:center; padding:70px 20px; text-align:center; }
.empty-icon-wrap{ width:80px; height:80px; border-radius:50%; background:#f8fafc; border:2px dashed #cbd5e1; display:flex; align-items:center; justify-content:center; margin-bottom:20px; }
.empty-icon-wrap i{ width:36px; height:36px; color:#94a3b8; }
.empty-state h3{ font-size:17px; font-weight:700; color:#334155; margin-bottom:8px; }
.empty-state p{ font-size:14px; color:#94a3b8; max-width:320px; line-height:1.6; }

/* TABLE WRAPPER */
.table-wrapper{ overflow-x:auto; }
table{ width:100%; border-collapse:collapse; min-width:950px; }
thead{ background:#f8fafc; }
th{ padding:18px 16px; font-size:12px; text-transform:uppercase; color:#64748b; text-align:left; border-bottom:2px solid #e2e8f0; }
td{ padding:18px 16px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
tbody tr:hover{ background:#f8fafc; }
.hidden-row{ display:none !important; }

/* STATUS */
.status{ display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:999px; font-size:12px; font-weight:700; }
.status.pending{ background:#fef3c7; color:#92400e; }
.status.process{ background:#dbeafe; color:#1e40af; }
.status.done{ background:#dcfce7; color:#166534; }

/* TOTAL */
.total-row td{ border-top:2px solid #3b82f6; font-weight:800; font-size:15px; }

/* ── PAGINATION ── */
.pagination-wrap{
    display:flex; align-items:center; justify-content:space-between;
    margin-top:20px; padding-top:16px; border-top:1px solid #f1f5f9;
    flex-wrap:wrap; gap:12px;
}
.pagination-info{ font-size:13px; color:#64748b; }
.pagination-info span{ font-weight:700; color:#0f172a; }
.pagination-btns{ display:flex; align-items:center; gap:6px; }
.page-btn{
    min-width:38px; height:38px; border-radius:10px;
    display:inline-flex; align-items:center; justify-content:center;
    border:1.5px solid #e2e8f0; background:white;
    font-size:13px; font-weight:600; color:#475569;
    cursor:pointer; transition:all 0.2s; padding:0 12px;
    font-family:'Inter',sans-serif;
}
.page-btn:hover:not(:disabled):not(.active){ background:#f1f5f9; border-color:#cbd5e1; color:#1e293b; }
.page-btn.active{ background:#3b82f6; border-color:#3b82f6; color:white; box-shadow:0 3px 8px rgba(59,130,246,0.3); }
.page-btn:disabled{ opacity:0.4; cursor:not-allowed; }

/* NO RESULTS */
.no-results-row td{ text-align:center; padding:50px; color:#94a3b8; font-size:14px; }

@media(max-width:768px){
    .main{ padding:110px 18px 30px; }
    .analytics-grid{ grid-template-columns:1fr; }
    .filter-form{ flex-direction:column; align-items:stretch; }
    .filter-form select, .filter-form input{ width:100%; }
    .table-toolbar{ flex-direction:column; align-items:flex-start; }
    .pagination-wrap{ flex-direction:column; align-items:flex-start; }
}
</style>
@endpush

@section('content')
@php
$totalPendapatan = $orders->sum('total');
$cash = $orders->where('payment_method','cash')->sum('total');
$qris = $orders->where('payment_method','qris')->sum('total');
$totalOrder = $orders->count();
@endphp

<div class="page-header">
    <div>
        <h1>Laporan Penjualan</h1>
        <p>Pantau performa bisnis dan transaksi penjualan secara realtime</p>
    </div>
</div>

<!-- ANALYTICS -->
<div class="analytics-grid">
    <div class="analytics-card orange">
        <div class="icon-box"><i data-lucide="wallet"></i></div>
        <div class="analytics-label">Total Pendapatan</div>
        <div class="analytics-value">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
        <div class="analytics-sub">🔥 Semua transaksi berhasil</div>
    </div>
    <div class="analytics-card blue">
        <div class="icon-box"><i data-lucide="shopping-bag"></i></div>
        <div class="analytics-label">Total Transaksi</div>
        <div class="analytics-value">{{ $totalOrder }}</div>
        <div class="analytics-sub">📦 Order masuk</div>
    </div>
    <div class="analytics-card cyan">
        <div class="icon-box"><i data-lucide="badge-dollar-sign"></i></div>
        <div class="analytics-label">Pembayaran Cash</div>
        <div class="analytics-value">Rp {{ number_format($cash,0,',','.') }}</div>
        <div class="analytics-sub">💵 Pembayaran tunai</div>
    </div>
    <div class="analytics-card green">
        <div class="icon-box"><i data-lucide="smartphone"></i></div>
        <div class="analytics-label">Pembayaran QRIS</div>
        <div class="analytics-value">Rp {{ number_format($qris,0,',','.') }}</div>
        <div class="analytics-sub">📱 Pembayaran digital</div>
    </div>
</div>

<!-- FILTER -->
<div class="filter-box">
    <form method="GET" action="/admin/laporan" class="filter-form" id="filterForm">

        {{-- Dropdown periode --}}
        <select name="filter" id="filterPeriode" onchange="onFilterChange()">
            <option value="">-- Pilih Periode --</option>
            <option value="hari"  {{ request('filter') == 'hari'  ? 'selected' : '' }}>Hari Ini</option>
            <option value="bulan" {{ request('filter') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="tahun" {{ request('filter') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
        </select>

        {{-- Pemisah visual --}}
        <span style="color:#cbd5e1;font-size:13px;font-weight:600;white-space:nowrap;">atau pilih tanggal</span>

        {{-- Input tanggal spesifik --}}
        <input type="date" name="tanggal" id="filterTanggal"
               value="{{ request('tanggal') }}"
               onchange="onTanggalChange()">

        <button type="submit" class="filter-btn">
            <i data-lucide="search"></i>Filter
        </button>

        {{-- Export PDF — bawa semua parameter filter yang aktif --}}
        <a id="exportPdfBtn"
           href="{{ route('admin.laporan.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
           class="export-btn">
            <i data-lucide="file-down"></i>Export PDF
        </a>

    </form>

    {{-- Label filter aktif --}}
    @if(request('filter') || request('tanggal'))
    <div style="margin-top:14px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
        <span style="font-size:12px;color:#64748b;font-weight:600;">Filter aktif:</span>
        @if(request('filter') == 'hari')
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 Hari Ini</span>
        @elseif(request('filter') == 'bulan')
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 Bulan Ini — {{ now()->translatedFormat('F Y') }}</span>
        @elseif(request('filter') == 'tahun')
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 Tahun {{ now()->year }}</span>
        @elseif(request('tanggal'))
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 {{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}</span>
        @endif
        <span style="font-size:12px;color:#64748b;">— menampilkan <strong>{{ $orders->count() }}</strong> transaksi</span>
    </div>
    @endif
</div>

<!-- TABLE -->
<div class="table-box">

    <div class="table-title-wrap">
        <div class="table-title-icon"><i data-lucide="file-bar-chart-2"></i></div>
        <div class="table-title-text">
            <h2>Data Laporan</h2>
            <p>Rincian seluruh transaksi penjualan</p>
        </div>
    </div>

    @if($orders->isNotEmpty())
    <!-- ── TOOLBAR ── -->
    <div class="table-toolbar">
        <div class="toolbar-left">
            <span class="toolbar-label">Tampilkan</span>
            <select class="per-page-select" id="perPageSelect" onchange="onPerPageChange()">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15" selected>15</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="toolbar-label">data</span>
        </div>
        <div class="toolbar-right">
            <span class="toolbar-cari-label">Cari:</span>
            <div class="search-wrap">
                <input type="text" class="search-input" id="searchInput"
                    placeholder="Cari ID, meja, status..." oninput="onSearch()">
            </div>
        </div>
    </div>
    @endif

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
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody id="laporanTableBody">

                @forelse($orders as $index => $order)
                <tr data-search="{{ strtolower(
                    ($order->queue_number ?: 'A-' . str_pad($order->id,3,'0',STR_PAD_LEFT))
                    . ' ' . ($order->table_number ? 'meja '.$order->table_number : 'take away')
                    . ' ' . $order->status
                    . ' ' . $order->payment_method
                    . ' ' . $order->created_at->format('d M Y')
                ) }}">
                    <td class="row-no">{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight:700;">{{ $order->created_at->format('d M Y') }}</div>
                        <div style="font-size:12px;color:#64748b;">{{ $order->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td style="font-weight:800;color:#7c3aed;">
                        {{ $order->queue_number ?: 'A-' . str_pad($order->id,3,'0',STR_PAD_LEFT) }}
                    </td>
                    <td>{{ $order->table_number ? 'Meja '.$order->table_number : 'Take Away' }}</td>
                    <td style="min-width:220px;">
                        @foreach($order->items->take(2) as $item)
                        <div>{{ $item->qty }}x {{ $item->menu->name ?? '-' }}</div>
                        @endforeach
                    </td>
                    <td>
                        @if($order->status == 'pending')
                            <span class="status pending">Pending</span>
                        @elseif($order->status == 'process')
                            <span class="status process">Diproses</span>
                        @else
                            <span class="status done">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($order->payment_method == 'cash') 💵 Cash
                        @else 📱 QRIS
                        @endif
                    </td>
                    <td style="text-align:right;font-weight:800;">
                        Rp {{ number_format($order->total,0,',','.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:0;border:none;">
                        <div class="empty-state">
                            <div class="empty-icon-wrap"><i data-lucide="inbox"></i></div>
                            <h3>Tidak ada data laporan saat ini</h3>
                            <p>Belum ada transaksi yang tercatat.<br>Coba ubah filter periode atau pilih tanggal yang berbeda.</p>
                        </div>
                    </td>
                </tr>
                @endforelse

                @if(count($orders) > 0)
                <tr class="total-row" id="totalRow">
                    <td colspan="7" style="text-align:right;">TOTAL PENDAPATAN</td>
                    <td style="text-align:right;color:#059669;">Rp {{ number_format($totalPendapatan,0,',','.') }}</td>
                </tr>
                @endif

            </tbody>
        </table>
    </div>

    @if($orders->isNotEmpty())
    <!-- ── PAGINATION ── -->
    <div class="pagination-wrap" id="paginationWrap">
        <div class="pagination-info" id="paginationInfo"></div>
        <div class="pagination-btns" id="paginationBtns"></div>
    </div>
    @endif

</div>
@endsection