@extends('layouts.admin')

@section('title', 'Laporan Analytics')

@push('styles')
<style>
/* ANALYTICS CARDS */
.analytics-grid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:20px; margin-bottom:30px; }
.analytics-card{ background:white; border-radius:22px; padding:24px; box-shadow:0 4px 10px rgba(0,0,0,.04); position:relative; overflow:hidden; border:1px solid #e2e8f0; }
.analytics-card::before{ content:''; position:absolute; top:0; left:0; width:100%; height:4px; }
.analytics-card.orange::before{ background:#f97316; }
.analytics-card.blue::before{ background:#3b82f6; }
.analytics-card.cyan::before{ background:#06b6d4; }
.analytics-card.green::before{ background:#22c55e; }
.analytics-card.purple::before{ background:#8b5cf6; }
.icon-box{ width:52px; height:52px; border-radius:16px; display:flex; align-items:center; justify-content:center; margin-bottom:18px; }
.icon-box i{ width:24px; height:24px; }
.orange .icon-box{ background:#fff7ed; color:#f97316; }
.blue .icon-box{ background:#eff6ff; color:#3b82f6; }
.cyan .icon-box{ background:#ecfeff; color:#06b6d4; }
.green .icon-box{ background:#f0fdf4; color:#22c55e; }
.purple .icon-box{ background:#f5f3ff; color:#8b5cf6; }
.analytics-label{ font-size:13px; color:#64748b; margin-bottom:10px; font-weight:700; }
.analytics-value{ font-size:30px; font-weight:800; color:#0f172a; }
.analytics-sub{ margin-top:8px; font-size:13px; font-weight:600; }
.orange .analytics-sub{ color:#f97316; }
.blue .analytics-sub{ color:#3b82f6; }
.cyan .analytics-sub{ color:#06b6d4; }
.green .analytics-sub{ color:#22c55e; }
.purple .analytics-sub{ color:#8b5cf6; }

/* FILTER BOX */
.filter-box{ background:white; padding:25px; border-radius:24px; margin-bottom:30px; box-shadow:0 4px 10px rgba(0,0,0,.04); }
.filter-form{ display:flex; gap:15px; flex-wrap:wrap; align-items:center; }
.filter-form select, .filter-form input{ padding:12px 16px; border:1px solid #dbe2ea; border-radius:14px; font-size:14px; min-width:160px; background:white; }
.filter-form button, .export-btn{ padding:12px 20px; border:none; border-radius:14px; color:white; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
.filter-btn{ background:#3b82f6; }
.export-btn{ background:#ef4444; }

/* TABLE MANAGEMENT */
.table-box{ background:white; border-radius:24px; padding:25px; box-shadow:0 4px 10px rgba(0,0,0,.04); }
.table-title-wrap{ display:flex; align-items:center; gap:14px; margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #f1f5f9; }
.table-title-icon{ width:44px; height:44px; border-radius:14px; background:#eff6ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.table-title-icon i{ width:22px; height:22px; color:#3b82f6; }
.table-title-text h2{ font-size:20px; font-weight:700; color:#0f172a; line-height:1.2; }
.table-title-text p{ font-size:13px; color:#64748b; margin-top:3px; }

/* TOOLBAR & SEARCH */
.table-toolbar{ display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; flex-wrap:wrap; gap:12px; padding:14px 18px; background:#f8fafc; border-radius:14px; border:1px solid #f1f5f9; }
.toolbar-left{ display:flex; align-items:center; gap:10px; }
.toolbar-label{ font-size:13px; color:#64748b; font-weight:500; }
.per-page-select{ padding:7px 32px 7px 12px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; font-family:'Inter',sans-serif; outline:none; color:#1e293b; cursor:pointer; background:white; transition:border-color 0.2s; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; }
.per-page-select:focus{ border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
.toolbar-right{ display:flex; align-items:center; gap:8px; }
.toolbar-cari-label{ font-size:13px; color:#64748b; font-weight:500; }
.search-wrap{ position:relative; display:flex; align-items:center; }
.search-wrap i{ position:absolute; left:12px; width:15px; height:15px; color:#94a3b8; }
.search-input{ padding:9px 14px 9px 36px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; font-family:'Inter',sans-serif; outline:none; width:230px; color:#1e293b; transition:border-color 0.2s; }
.search-input:focus{ border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }

/* EMPTY STATE */
.empty-state{ display:flex; flex-direction:column; align-items:center; justify-content:center; padding:70px 20px; text-align:center; }
.empty-icon-wrap{ width:80px; height:80px; border-radius:50%; background:#f8fafc; border:2px dashed #cbd5e1; display:flex; align-items:center; justify-content:center; margin-bottom:20px; }
.empty-icon-wrap i{ width:36px; height:36px; color:#94a3b8; }
.empty-state h3{ font-size:17px; font-weight:700; color:#334155; margin-bottom:8px; }
.empty-state p{ font-size:14px; color:#94a3b8; max-width:320px; line-height:1.6; }

/* TABLE CONTEXT */
.table-wrapper{ overflow-x:auto; }
table{ width:100%; border-collapse:collapse; min-width:950px; }
thead{ background:#f8fafc; }
th{ padding:18px 16px; font-size:12px; text-transform:uppercase; color:#64748b; text-align:left; border-bottom:2px solid #e2e8f0; }
td{ padding:18px 16px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
tbody tr:hover{ background:#f8fafc; }

/* STATUS BADGES */
.status{ display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; font-size:12px; font-weight:700; }
.status.process{ background:#dbeafe; color:#1e40af; }
.status.done{ background:#dcfce7; color:#166534; }
.status.delivered{ background:#f0fdf4; color:#14532d; }
.status.paid{ background:#fef9c3; color:#854d0e; }

/* PAYMENT BADGES */
.pay-badge{ display:inline-flex; align-items:center; gap:5px; padding:5px 10px; border-radius:8px; font-size:12px; font-weight:700; }
.pay-cash{ background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
.pay-midtrans{ background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }

/* TOTAL ROW */
.total-row td{ border-top:2px solid #3b82f6; font-weight:800; font-size:15px; }

/* PAGINATION SYSTEM */
.pagination-wrap{ display:flex; align-items:center; justify-content:space-between; margin-top:20px; padding-top:16px; border-top:1px solid #f1f5f9; flex-wrap:wrap; gap:12px; }
.pagination-info{ font-size:13px; color:#64748b; }
.pagination-info span{ font-weight:700; color:#0f172a; }
.pagination-btns{ display:flex; align-items:center; gap:6px; }
.page-btn{ min-width:38px; height:38px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; border:1.5px solid #e2e8f0; background:white; font-size:13px; font-weight:600; color:#475569; cursor:pointer; transition:all 0.2s; padding:0 12px; font-family:'Inter',sans-serif; }
.page-btn:hover:not(:disabled):not(.active){ background:#f1f5f9; border-color:#cbd5e1; color:#1e293b; }
.page-btn.active{ background:#3b82f6; border-color:#3b82f6; color:white; box-shadow:0 3px 8px rgba(59,130,246,0.3); }
.page-btn:disabled{ opacity:0.4; cursor:not-allowed; }
.no-results-row td{ text-align:center; padding:50px; color:#94a3b8; font-size:14px; }

/* BREAKDOWN ONLINE */
.breakdown-box{ background:white; border-radius:22px; padding:22px 26px; margin-bottom:30px; box-shadow:0 4px 10px rgba(0,0,0,.04); border:1px solid #e2e8f0; }
.breakdown-title{ display:flex; align-items:center; gap:10px; font-size:14px; font-weight:700; color:#0f172a; margin-bottom:18px; padding-bottom:14px; border-bottom:1px solid #f1f5f9; }
.breakdown-title i{ width:18px; height:18px; color:#8b5cf6; }
.breakdown-grid{ display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:14px; }
.breakdown-item{ display:flex; align-items:center; gap:12px; background:#f8fafc; border-radius:14px; padding:14px 16px; border:1px solid #f1f5f9; }
.breakdown-icon{ font-size:22px; flex-shrink:0; }
.breakdown-body{ min-width:0; }
.breakdown-name{ font-size:12px; font-weight:700; color:#64748b; margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.breakdown-val{ font-size:14px; font-weight:800; color:#0f172a; }
.breakdown-count{ font-size:11px; color:#94a3b8; margin-top:2px; }

/* KEYFRAMES */
@keyframes spin  { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes popIn { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }

@media(max-width:768px){
    .analytics-grid{ grid-template-columns:1fr 1fr; }
    .filter-form{ flex-direction:column; align-items:stretch; }
    .filter-form select, .filter-form input{ width:100%; }
    .table-toolbar{ flex-direction:column; align-items:flex-start; }
    .pagination-wrap{ flex-direction:column; align-items:flex-start; }
    .breakdown-grid{ grid-template-columns:1fr 1fr; }
}
</style>
@endpush

@section('content')
@php
$midtransMethods = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans'];

$totalPendapatan = $orders->sum('total');
$totalOrder      = $orders->count();
$cash            = $orders->where('payment_method', 'cash')->sum('total');
$midtransTotal   = $orders->whereIn('payment_method', $midtransMethods)->sum('total');

// Definisi label & icon per metode Midtrans
$midtransDetail = [
    'gopay'       => ['label' => 'GoPay',       'icon' => '🟢'],
    'ovo'         => ['label' => 'OVO',          'icon' => '🟣'],
    'dana'        => ['label' => 'DANA',         'icon' => '🔵'],
    'shopeepay'   => ['label' => 'ShopeePay',    'icon' => '🟠'],
    'bca'         => ['label' => 'BCA Virtual',  'icon' => '🏦'],
    'bni'         => ['label' => 'BNI Virtual',  'icon' => '🏦'],
    'bri'         => ['label' => 'BRI Virtual',  'icon' => '🏦'],
    'mandiri'     => ['label' => 'Mandiri Bill', 'icon' => '🏦'],
    'permata'     => ['label' => 'Permata VA',   'icon' => '🏦'],
    'credit_card' => ['label' => 'Kartu Kredit', 'icon' => '💳'],
    'midtrans'    => ['label' => 'Midtrans',     'icon' => '🌐'],
];

// Hanya metode yang punya transaksi
$midtransUsed = collect($midtransDetail)->filter(
    fn($v, $k) => $orders->where('payment_method', $k)->count() > 0
);
@endphp

<div class="page-header">
    <div>
        <h1>Laporan Penjualan</h1>
        <p>Pantau performa bisnis dan transaksi penjualan secara realtime</p>
    </div>
</div>

<div class="analytics-grid">
    <div class="analytics-card orange">
        <div class="icon-box"><i data-lucide="wallet"></i></div>
        <div class="analytics-label">Total Pendapatan</div>
        <div class="analytics-value">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
        <div class="analytics-sub">{{ $totalOrder }} transaksi</div>
    </div>
    <div class="analytics-card blue">
        <div class="icon-box"><i data-lucide="shopping-bag"></i></div>
        <div class="analytics-label">Total Transaksi</div>
        <div class="analytics-value">{{ $totalOrder }}</div>
        <div class="analytics-sub">order selesai dibayar</div>
    </div>
    <div class="analytics-card cyan">
        <div class="icon-box"><i data-lucide="banknote"></i></div>
        <div class="analytics-label">Pembayaran Cash</div>
        <div class="analytics-value">Rp {{ number_format($cash,0,',','.') }}</div>
        <div class="analytics-sub">{{ $orders->where('payment_method','cash')->count() }} transaksi</div>
    </div>
    <div class="analytics-card purple">
        <div class="icon-box"><i data-lucide="smartphone"></i></div>
        <div class="analytics-label">Bayar Online (Midtrans)</div>
        <div class="analytics-value">Rp {{ number_format($midtransTotal,0,',','.') }}</div>
        <div class="analytics-sub">{{ $orders->whereIn('payment_method',$midtransMethods)->count() }} transaksi</div>
    </div>
</div>

{{-- Breakdown per metode Midtrans — hanya muncul jika ada transaksi online --}}
@if($midtransUsed->isNotEmpty())
<div class="breakdown-box">
    <div class="breakdown-title">
        <i data-lucide="layers"></i>
        <span>Rincian Pembayaran Online</span>
    </div>
    <div class="breakdown-grid">
        @foreach($midtransUsed as $kode => $info)
        @php
            $jml   = $orders->where('payment_method', $kode)->count();
            $total = $orders->where('payment_method', $kode)->sum('total');
        @endphp
        <div class="breakdown-item">
            <div class="breakdown-icon">{{ $info['icon'] }}</div>
            <div class="breakdown-body">
                <div class="breakdown-name">{{ $info['label'] }}</div>
                <div class="breakdown-val">Rp {{ number_format($total,0,',','.') }}</div>
                <div class="breakdown-count">{{ $jml }} transaksi</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="filter-box">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="filter-form" id="filterForm">

        <select name="filter" id="filterPeriode" onchange="onFilterChange()">
            <option value="">-- Pilih Periode --</option>
            <option value="hari"         {{ request('filter') == 'hari' ? 'selected' : '' }}>Hari Ini</option>
            <option value="last_7_days"  {{ request('filter') == 'last_7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
            <option value="last_30_days" {{ request('filter') == 'last_30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
            <option value="bulan"        {{ request('filter') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="tahun"        {{ request('filter') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
            <option value="all"          {{ request('filter') == 'all' ? 'selected' : '' }}>Semua Data</option>
        </select>

        <span style="color:#cbd5e1;font-size:13px;font-weight:600;white-space:nowrap;">atau pilih tanggal</span>

        <input type="date" name="tanggal" id="filterTanggal"
               value="{{ request('tanggal') }}"
               onchange="onTanggalChange()">

        <button type="submit" class="filter-btn">
            <i data-lucide="search"></i>Filter
        </button>

        <a id="exportPdfBtn"
           href="{{ route('admin.laporan.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
           class="export-btn"
           onclick="startDownload(event, this)">
            <span id="exportBtnContent" style="display:inline-flex;align-items:center;gap:8px;">
                <i data-lucide="file-down"></i>Export PDF
            </span>
            <span id="exportBtnLoading" style="display:none;align-items:center;gap:8px;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.85s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                Menyiapkan...
            </span>
        </a>

        {{-- Overlay Loading Download PDF --}}
        <div id="downloadOverlay" style="display:none;position:fixed;inset:0;background:rgba(15,22,35,.48);backdrop-filter:blur(4px);z-index:9999;align-items:center;justify-content:center;">
            <div style="background:#fff;border-radius:24px;padding:36px 44px;box-shadow:0 24px 64px rgba(0,0,0,.22);text-align:center;min-width:280px;max-width:340px;">

                {{-- State: Loading --}}
                <div id="dlStateLoading">
                    <div style="width:60px;height:60px;margin:0 auto 18px;background:linear-gradient(135deg,#ef4444,#dc2626);border-radius:18px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(239,68,68,.3);">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.85s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                    </div>
                    <div style="font-size:16px;font-weight:800;color:#0f172a;margin-bottom:6px;font-family:'Inter',sans-serif;">Menyiapkan Laporan...</div>
                    <div style="font-size:12.5px;color:#64748b;font-family:'Inter',sans-serif;line-height:1.5;">PDF sedang diproses oleh server,<br>mohon tunggu sebentar</div>
                    <div style="margin-top:18px;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                        <div id="downloadProgressBar" style="height:100%;width:0%;background:linear-gradient(90deg,#ef4444,#f97316);border-radius:99px;transition:width 0.35s ease;"></div>
                    </div>
                </div>

                {{-- State: Sukses --}}
                <div id="dlStateSuccess" style="display:none;">
                    <div style="width:60px;height:60px;margin:0 auto 18px;background:linear-gradient(135deg,#059669,#047857);border-radius:18px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(5,150,105,.3);animation:popIn .35s cubic-bezier(.34,1.56,.64,1) both;">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="white" stroke-width="2.8" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div style="font-size:16px;font-weight:800;color:#0f172a;margin-bottom:6px;font-family:'Inter',sans-serif;">Berhasil Diunduh!</div>
                    <div style="font-size:12.5px;color:#64748b;font-family:'Inter',sans-serif;">File laporan PDF sudah tersimpan<br>di folder unduhan kamu</div>
                </div>

            </div>
        </div>

    </form>

    @if(request('filter') || request('tanggal'))
    <div style="margin-top:14px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
        <span style="font-size:12px;color:#64748b;font-weight:600;">Filter aktif:</span>
        @if(request('filter') == 'hari')
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 Hari Ini</span>
        @elseif(request('filter') == 'last_7_days')
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 7 Hari Terakhir</span>
        @elseif(request('filter') == 'last_30_days')
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 30 Hari Terakhir</span>
        @elseif(request('filter') == 'bulan')
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 Bulan Ini — {{ now()->translatedFormat('F Y') }}</span>
        @elseif(request('filter') == 'tahun')
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 Tahun {{ now()->year }}</span>
        @elseif(request('filter') == 'all')
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 Semua Data</span>
        @elseif(request('tanggal'))
            <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">📅 {{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}</span>
        @endif
        <span style="font-size:12px;color:#64748b;">— menampilkan <strong>{{ $orders->count() }}</strong> transaksi</span>
    </div>
    @endif
</div>

<div class="table-box">

    <div class="table-title-wrap">
        <div class="table-title-icon"><i data-lucide="file-bar-chart-2"></i></div>
        <div class="table-title-text">
            <h2>Data Laporan</h2>
            <p>Rincian transaksi yang sudah dibayar (Cash dan Online/Midtrans)</p>
        </div>
    </div>

    @if($orders->isNotEmpty())
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
                <i data-lucide="search"></i>
                <input type="text" class="search-input" id="searchInput"
                    placeholder="Cari ID, meja, metode, status..." oninput="onSearch()">
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
                    <th>Nama Pemesan</th>
                    <th>Tipe & Meja</th>
                    <th>Detail Pesanan</th>
                    <th>Status</th>
                    <th>Metode Bayar</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody id="laporanTableBody">

                @forelse($orders as $index => $order)
                @php
                    $pm = $order->payment_method ?? '';
                    $payNames = [
                        'cash'        => ['💵 Cash',        'pay-cash'],
                        'gopay'       => ['🟢 GoPay',       'pay-midtrans'],
                        'ovo'         => ['🟣 OVO',         'pay-midtrans'],
                        'dana'        => ['🔵 DANA',        'pay-midtrans'],
                        'shopeepay'   => ['🟠 ShopeePay',   'pay-midtrans'],
                        'bca'         => ['🏦 BCA Virtual', 'pay-midtrans'],
                        'bni'         => ['🏦 BNI Virtual', 'pay-midtrans'],
                        'bri'         => ['🏦 BRI Virtual', 'pay-midtrans'],
                        'mandiri'     => ['🏦 Mandiri Bill','pay-midtrans'],
                        'permata'     => ['🏦 Permata VA',  'pay-midtrans'],
                        'credit_card' => ['💳 Kartu Kredit','pay-midtrans'],
                        'midtrans'    => ['🌐 Midtrans',    'pay-midtrans'],
                    ];
                    [$payText, $payClass] = $payNames[$pm] ?? ['🌐 ' . strtoupper($pm), 'pay-midtrans'];
                @endphp
                <tr data-search="{{ strtolower(
                    ($order->queue_number ?: 'A-' . str_pad($order->id,3,'0',STR_PAD_LEFT))
                    . ' ' . ($order->customer_name ?? '')
                    . ' ' . ($order->isTakeAway() ? 'take away' : 'dine in meja '.$order->table_number)
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
                    <td style="font-weight:600;color:#1e293b;">{{ $order->customer_name ?: '—' }}</td>
                    <td>
                        @if($order->isTakeAway())
                            🛍️ Take Away
                        @else
                            🪑 Dine In{{ $order->table_number ? ' — Meja '.$order->table_number : '' }}
                        @endif
                    </td>
                    <td style="min-width:220px;">
                        @foreach($order->items->take(2) as $item)
                        <div>{{ $item->qty }}x {{ $item->menu->name ?? '-' }}</div>
                        @endforeach
                        @if($order->items->count() > 2)
                        <div style="font-size:11px;color:#94a3b8;">+{{ $order->items->count() - 2 }} item lagi</div>
                        @endif
                    </td>
                    <td>
                        @if($order->status === 'process')
                            <span class="status process">Diproses</span>
                        @elseif($order->status === 'paid')
                            <span class="status paid">Lunas</span>
                        @elseif($order->status === 'done')
                            <span class="status done">Selesai</span>
                        @elseif($order->status === 'delivered')
                            <span class="status delivered">✅ Diantar</span>
                        @else
                            <span class="status done">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="pay-badge {{ $payClass }}">{{ $payText }}</span>
                    </td>
                    <td style="text-align:right;font-weight:800;">
                        Rp {{ number_format($order->total,0,',','.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="padding:0;border:none;">
                        <div class="empty-state">
                            <div class="empty-icon-wrap"><i data-lucide="inbox"></i></div>
                            <h3>Tidak ada data laporan saat ini</h3>
                            <p>Belum ada transaksi yang tercatat.<br>Coba ubah filter periode atau pilih tanggal yang berbeda.</p>
                        </div>
                    </td>
                </tr>
                @endforelse

                @if($orders->count() > 0)
                <tr class="total-row" id="totalRow">
                    <td colspan="8" style="text-align:right;">TOTAL PENDAPATAN</td>
                    <td style="text-align:right;color:#059669;">Rp {{ number_format($totalPendapatan,0,',','.') }}</td>
                </tr>
                @endif

            </tbody>
        </table>
    </div>

    @if($orders->isNotEmpty())
    <div class="pagination-wrap" id="paginationWrap">
        <div class="pagination-info" id="paginationInfo"></div>
        <div class="pagination-btns" id="paginationBtns"></div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function onFilterChange() {
    var val = document.getElementById('filterPeriode').value;
    if (val) document.getElementById('filterTanggal').value = '';
}
function onTanggalChange() {
    var val = document.getElementById('filterTanggal').value;
    if (val) document.getElementById('filterPeriode').value = '';
}

(function () {
    var perPage     = 15;
    var currentPage = 1;
    var keyword     = '';

    var tbody     = document.getElementById('laporanTableBody');
    var totalRow  = document.getElementById('totalRow');
    var infoEl    = document.getElementById('paginationInfo');
    var btnsEl    = document.getElementById('paginationBtns');
    var searchEl  = document.getElementById('searchInput');
    var perPageEl = document.getElementById('perPageSelect');

    if (!tbody) return;

    var allRows = Array.from(tbody.querySelectorAll('tr[data-search]'));

    function getFiltered() {
        if (!keyword) return allRows;
        return allRows.filter(function (r) {
            return r.getAttribute('data-search').indexOf(keyword) !== -1;
        });
    }

    function render() {
        var filtered   = getFiltered();
        var total      = filtered.length;
        var totalPages = Math.max(1, Math.ceil(total / perPage));

        if (currentPage > totalPages) currentPage = totalPages;

        var start = (currentPage - 1) * perPage;
        var end   = start + perPage;

        var visibleNo = 1;
        allRows.forEach(function (row) { row.style.display = 'none'; });
        filtered.forEach(function (row, idx) {
            if (idx >= start && idx < end) {
                row.style.display = '';
                var noCell = row.querySelector('.row-no');
                if (noCell) noCell.textContent = start + visibleNo;
                visibleNo++;
            }
        });

        if (totalRow) totalRow.style.display = total > 0 ? '' : 'none';

        var noResultsRow = tbody.querySelector('.no-results-row');
        if (total === 0) {
            if (!noResultsRow) {
                var tr = document.createElement('tr');
                tr.className = 'no-results-row';
                tr.innerHTML = '<td colspan="9" style="text-align:center;padding:50px;color:#94a3b8;font-size:14px;">Tidak ada data yang sesuai pencarian "<strong>' + escapeHtml(keyword) + '</strong>"</td>';
                tbody.insertBefore(tr, totalRow || null);
            } else {
                noResultsRow.querySelector('td').innerHTML = 'Tidak ada data yang sesuai pencarian "<strong>' + escapeHtml(keyword) + '</strong>"';
            }
        } else {
            if (noResultsRow) noResultsRow.remove();
        }

        if (infoEl) {
            if (total === 0) {
                infoEl.innerHTML = 'Menampilkan <span>0</span> dari <span>' + total + '</span> data';
            } else {
                var from = start + 1;
                var to   = Math.min(end, total);
                infoEl.innerHTML = 'Menampilkan <span>' + from + '–' + to + '</span> dari <span>' + total + '</span> data';
            }
        }

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (!btnsEl) return;
        btnsEl.innerHTML = '';

        btnsEl.appendChild(makeBtn('‹', currentPage === 1, false, function () { currentPage--; render(); }));

        buildPageRange(currentPage, totalPages).forEach(function (p) {
            if (p === '...') {
                var dots = document.createElement('span');
                dots.textContent = '…';
                dots.style.cssText = 'padding:0 6px;color:#94a3b8;font-size:13px;align-self:center;';
                btnsEl.appendChild(dots);
            } else {
                btnsEl.appendChild(makeBtn(p, false, p === currentPage, (function (pg) {
                    return function () { currentPage = pg; render(); };
                }(p))));
            }
        });

        btnsEl.appendChild(makeBtn('›', currentPage === totalPages, false, function () { currentPage++; render(); }));
    }

    function buildPageRange(cur, total) {
        if (total <= 7) return Array.from({ length: total }, function (_, i) { return i + 1; });
        var pages = [1];
        if (cur > 3) pages.push('...');
        for (var i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) pages.push(i);
        if (cur < total - 2) pages.push('...');
        pages.push(total);
        return pages;
    }

    function makeBtn(label, disabled, active, onClick) {
        var btn = document.createElement('button');
        btn.className = 'page-btn' + (active ? ' active' : '');
        btn.textContent = label;
        btn.disabled = disabled;
        btn.addEventListener('click', onClick);
        return btn;
    }

    function escapeHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    window.onSearch = function () {
        keyword     = (searchEl ? searchEl.value.trim().toLowerCase() : '');
        currentPage = 1;
        render();
    };

    window.onPerPageChange = function () {
        perPage     = parseInt(perPageEl ? perPageEl.value : 15, 10);
        currentPage = 1;
        render();
    };

    render();
})();

/* ── DOWNLOAD PDF FEEDBACK ── */
function startDownload(e, btn) {
    e.preventDefault();
    var url = btn.getAttribute('href');

    var overlay      = document.getElementById('downloadOverlay');
    var btnContent   = document.getElementById('exportBtnContent');
    var btnLoading   = document.getElementById('exportBtnLoading');
    var progBar      = document.getElementById('downloadProgressBar');
    var stateLoading = document.getElementById('dlStateLoading');
    var stateSuccess = document.getElementById('dlStateSuccess');

    overlay.style.display      = 'flex';
    stateLoading.style.display = '';
    stateSuccess.style.display = 'none';
    btnContent.style.display   = 'none';
    btnLoading.style.display   = 'inline-flex';
    btn.style.pointerEvents    = 'none';
    btn.style.opacity          = '0.85';
    progBar.style.width        = '0%';

    var progress = 0;
    var interval = setInterval(function() {
        progress += Math.random() * 12 + 4;
        if (progress > 85) progress = 85;
        progBar.style.width = progress + '%';
    }, 280);

    setTimeout(function() {
        window.location.href = url;
    }, 800);

    setTimeout(function() {
        clearInterval(interval);
        progBar.style.width = '100%';

        setTimeout(function() {
            stateLoading.style.display = 'none';
            stateSuccess.style.display = '';
            btnContent.style.display   = 'inline-flex';
            btnLoading.style.display   = 'none';
            btn.style.pointerEvents    = '';
            btn.style.opacity          = '';

            setTimeout(function() {
                overlay.style.display = 'none';
                progBar.style.width   = '0%';
            }, 1600);
        }, 350);
    }, 3200);
}
</script>
@endpush