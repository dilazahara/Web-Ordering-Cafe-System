@extends('layouts.kasir')

@section('title', 'Kasir — Laporan')

@push('styles')
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --bg: #f0f2f8; --surface: #ffffff; --surface-2: #f7f8fc;
  --border: #e4e8f0; --border-strong: #ccd2e0;
  --text-primary: #0f1623; --text-secondary: #5a6279; --text-muted: #9198ae;
  --accent: #2563eb; --accent-bg: #eff4ff; --accent-text: #1e40af;
  --green: #059669; --green-bg: #ecfdf5; --green-text: #065f46;
  --amber: #d97706; --amber-bg: #fffbeb; --amber-text: #92400e;
  --red: #dc2626; --red-bg: #fef2f2; --red-text: #991b1b;
  --indigo: #4f46e5; --indigo-bg: #eef2ff; --indigo-text: #3730a3;
  --header-h: 64px; --nav-h: 48px; --total-top: 112px;
  --radius-lg: 18px;
  --shadow-sm: 0 1px 4px rgb(0 0 0/.05), 0 0 0 1px rgb(0 0 0/.04);
  --shadow: 0 2px 8px rgb(0 0 0/.06), 0 0 0 1px rgb(0 0 0/.04);
  --shadow-md: 0 8px 24px rgb(0 0 0/.10), 0 0 0 1px rgb(0 0 0/.04);
  --shadow-header: 0 1px 0 var(--border), 0 2px 12px rgb(0 0 0/.04);
}
html { scroll-behavior: smooth; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); line-height: 1.5; min-height: 100vh; -webkit-font-smoothing: antialiased; }

/* ══ HEADER & NAVIGASI KASIR (TIDAK DIUBAH) ══ */
.header { position: fixed; top: 0; left: 0; right: 0; height: var(--header-h); background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; z-index: 100; box-shadow: var(--shadow-header); }
.logo { display: flex; align-items: center; gap: 10px; }
.logo-mark { width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgb(37 99 235/.35); }
.logo-mark svg { width: 18px; height: 18px; stroke: white; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.logo-text { font-size: 16px; font-weight: 800; letter-spacing: -0.5px; }
.logo-text span { color: var(--accent); }
.header-right { display: flex; align-items: center; gap: 8px; }
.header-clock{ display:flex; align-items:center; gap:8px; padding:8px 14px; border-radius:12px; background:var(--surface); border:1px solid var(--border); font-family:'Inter',sans-serif; box-shadow:var(--shadow-sm); }
.header-clock svg{ width:16px; height:16px; stroke:var(--accent); stroke-width:2.3; fill:none; }
#liveClock{ font-size:13px; font-weight:700; color:var(--text-primary); letter-spacing:.5px; }

/* Profile Dropdown */
.profile-wrap { position: relative; }
.user-btn { display: flex; align-items: center; gap: 10px; padding: 5px 12px 5px 5px; border: 1px solid var(--border); border-radius: 12px; background: var(--surface); cursor: pointer; transition: all 0.18s; user-select: none; }
.user-btn:hover { background: var(--surface-2); border-color: var(--border-strong); box-shadow: var(--shadow-sm); }
.user-btn.open { border-color: var(--accent); background: var(--accent-bg); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #818cf8, #4f46e5); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: 700; flex-shrink: 0; overflow: hidden; }
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-info { display: flex; flex-direction: column; }
.user-name { font-size: 13px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
.user-role { font-size: 11px; color: var(--text-muted); font-family: 'Inter', sans-serif; }
.chevron { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; transition: transform .2s; flex-shrink: 0; }
.user-btn.open .chevron { transform: rotate(180deg); }
.dropdown { position: absolute; top: calc(100% + 10px); right: 0; width: 240px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 16px 48px rgb(0 0 0/.14), 0 0 0 1px rgb(0 0 0/.04); overflow: hidden; opacity: 0; transform: translateY(-8px) scale(.97); pointer-events: none; transition: opacity .18s, transform .18s; z-index: 200; }
.dropdown.show { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }
.dropdown-header { padding: 16px; background: linear-gradient(135deg, var(--indigo-bg), var(--accent-bg)); border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
.dropdown-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, #818cf8, #4f46e5); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; font-weight: 800; flex-shrink: 0; box-shadow: 0 2px 8px rgb(79 70 229/.3); overflow: hidden; }
.dropdown-avatar img { width: 100%; height: 100%; object-fit: cover; }
.dropdown-name { font-size: 13.5px; font-weight: 800; color: var(--text-primary); }
.dropdown-role { font-size: 11.5px; color: var(--text-secondary); font-family: 'Inter', sans-serif; margin-top: 2px; }
.dropdown-body { padding: 8px; }
.dropdown-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; text-decoration: none; cursor: pointer; font-size: 13.5px; font-weight: 600; color: var(--text-secondary); transition: all .15s; border: none; background: none; width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; }
.dropdown-item:hover { background: var(--surface-2); color: var(--text-primary); }
.dropdown-item svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
.dropdown-item .item-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .15s; }
.dropdown-item:hover .item-icon { background: var(--border); }
.dropdown-divider { height: 1px; background: var(--border); margin: 6px 8px; }
.dropdown-item.danger { color: var(--red-text); }
.dropdown-item.danger:hover { background: var(--red-bg); color: var(--red); }
.dropdown-item.danger .item-icon { background: var(--red-bg); }

.topnav { position: fixed; top: var(--header-h); left: 0; right: 0; height: var(--nav-h); background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); border-bottom: 1px solid var(--border); display: flex; justify-content: center; z-index: 99; }
.nav-container { max-width: 1280px; margin: 0 auto; display: flex; align-items: stretch; padding: 0 8px; }
.nav-link { display: flex; align-items: center; gap: 7px; padding: 0 18px; text-decoration: none; font-size: 13px; font-weight: 600; color: var(--text-secondary); transition: all 0.18s; white-space: nowrap; border-bottom: 2px solid transparent; margin-bottom: -1px; }
.nav-link svg { width: 15px; height: 15px; stroke: currentColor; stroke-width: 2.2; fill: none; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
.nav-link:hover { color: var(--text-primary); }
.nav-link.active { color: var(--accent); border-bottom-color: var(--accent); }

/* ══ BAGIAN KONTEN ══ */
.main { margin-top: var(--total-top); padding: 36px 24px 72px; width: 100%; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 8px; }
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; }
.page-title { font-size: 23px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
.page-sub { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-family: 'Inter', sans-serif; }

/* Filter Bar */
.filter-bar { background: var(--surface); padding: 20px 24px; border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 24px; display: flex; gap: 15px; align-items: center; justify-content: space-between; box-shadow: var(--shadow); flex-wrap: wrap; }
.filter-bar input[type="date"] { padding: 10px 16px; border: 1px solid var(--border); border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 13.5px; color: var(--text-primary); transition: border-color 0.18s; min-width: 160px; }
.filter-bar input[type="date"]:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgb(37 99 235/.12); }
.filter-btn { display: flex; align-items: center; gap: 8px; padding: 10px 20px; border: none; border-radius: 12px; background: var(--accent); font-size: 13.5px; font-weight: 600; color: white; cursor: pointer; transition: all 0.18s; font-family: 'Inter', sans-serif; box-shadow: 0 3px 10px rgba(37,99,235,0.25); }
.filter-btn:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 5px 15px rgba(37,99,235,0.3); }
.download-btn { display: flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; background: var(--red); color: white; font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.18s; font-family: 'Inter', sans-serif; text-decoration: none; border: none; box-shadow: 0 3px 10px rgba(220,38,38,0.25); }
.download-btn:hover { background: #b91c1c; box-shadow: 0 5px 15px rgba(220,38,38,0.3); transform: translateY(-1px); }

/* Table & Datatable Styles */
.table-wrap { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow-x: auto; box-shadow: var(--shadow); }
.rtable { width: 100%; border-collapse: collapse; font-family: 'Inter', sans-serif; min-width: 800px; }
.rtable thead th { background: var(--surface-2); padding: 14px 16px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: var(--text-muted); text-align: left; border-bottom: 1px solid var(--border); }
.rtable tbody tr { border-bottom: 1px solid var(--surface-2); transition: background .15s; background: var(--surface); }
.rtable tbody tr:hover { background: #fafbff; }
.rtable td { padding: 15px 16px; font-size: 13.5px; color: var(--text-secondary); vertical-align: middle; }

/* Badges */
.badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; }
.badge-dot { width: 6px; height: 6px; border-radius: 50%; }
.badge.pending  { background: #fef3c7; color: #92400e; }
.badge.pending .badge-dot  { background: #f59e0b; }
.badge.proses   { background: #dbeafe; color: #1e40af; }
.badge.proses .badge-dot   { background: #3b82f6; }
.badge.selesai  { background: #dcfce7; color: #15803d; }
.badge.selesai .badge-dot  { background: #22c55e; }
.badge.diantar  { background: #f0fdf4; color: #15803d; }
.badge.diantar .badge-dot  { background: #22c55e; }

/* DataTables */
.dataTables_wrapper { padding: 0 0 18px; }
.dataTables_filter { padding: 18px 22px 0; float: right; }
.dataTables_length { padding: 18px 22px 0; float: left; }
.dataTables_filter input, .dataTables_length select { border: 1px solid var(--border); border-radius: 8px; padding: 6px 10px; font-size: 13px; outline: none; }
.dataTables_filter input:focus, .dataTables_length select:focus { border-color: var(--accent); }
.dataTables_info { font-size: 13px !important; color: var(--text-muted) !important; padding: 14px 22px 0 !important; font-weight: 500; font-family: 'Inter', sans-serif; }
.dataTables_paginate { padding: 12px 22px 0 !important; display: flex !important; align-items: center; gap: 4px; font-family: 'Inter', sans-serif; }
.paginate_button { border-radius: 10px !important; border: 1px solid var(--border) !important; padding: 6px 13px !important; margin: 0 2px !important; background: var(--surface) !important; color: var(--text-secondary) !important; font-size: 13px !important; font-weight: 600 !important; cursor: pointer !important; transition: all .15s !important; }
.paginate_button:hover:not(.current):not(.disabled) { background: var(--accent-bg) !important; color: var(--accent) !important; border-color: #bfdbfe !important; }
.paginate_button.current, .paginate_button.current:hover { background: linear-gradient(135deg, var(--accent), #1d4ed8) !important; color: white !important; border-color: var(--accent) !important; box-shadow: 0 2px 8px rgba(37,99,235,0.3) !important; }
.paginate_button.disabled, .paginate_button.disabled:hover { color: var(--border-strong) !important; cursor: default !important; }
.dataTables_scrollBody::-webkit-scrollbar { height: 5px; }
.dataTables_scrollBody::-webkit-scrollbar-track { background: transparent; border-radius: 99px; }
.dataTables_scrollBody::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 99px; }

/* ── EMPTY STATE (DIUPGRADE) ── */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 72px 24px 80px;
    text-align: center;
}
.empty-icon-ring {
    position: relative;
    width: 88px;
    height: 88px;
    margin-bottom: 24px;
}
.empty-icon-ring::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 2px dashed #cbd5e1;
    animation: spin-slow 12s linear infinite;
}
@keyframes spin-slow {
    to { transform: rotate(360deg); }
}
.empty-icon-inner {
    position: absolute;
    inset: 10px;
    border-radius: 50%;
    background: var(--surface-2);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
}
.empty-icon-inner svg {
    width: 30px;
    height: 30px;
    stroke: #94a3b8;
    stroke-width: 1.5;
    fill: none;
}
.empty-state h3 {
    font-size: 16px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
    letter-spacing: -0.2px;
}
.empty-state p {
    font-size: 13.5px;
    color: var(--text-muted);
    line-height: 1.7;
    max-width: 300px;
    font-family: 'Inter', sans-serif;
}
.empty-state .empty-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 20px;
    padding: 8px 16px;
    border-radius: 20px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-secondary);
    font-family: 'Inter', sans-serif;
}
.empty-state .empty-tag svg {
    width: 14px;
    height: 14px;
    stroke: var(--text-muted);
    stroke-width: 2;
    fill: none;
}

@media (max-width: 640px) {
  .main { padding: 24px 16px 48px; }
  .nav-link span { display: none; }
  .user-role, .user-info { display: none; }
  .filter-bar { flex-direction: column; align-items: stretch; }
  .page-header { flex-direction: column; }
  .table-wrap { padding: 15px; }
}
</style>
@endpush

@section('content')
<div class="page-header">
      <div>
        <div class="page-title">Laporan Penjualan Harian</div>
        <div class="page-sub">Pantau rekap transaksi kasir hari ini atau berdasarkan filter tanggal.</div>
      </div>
    </div>

    <div class="filter-bar">
      <form method="GET" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; flex:1;">
        <div style="display:flex; flex-direction:column; gap:4px;">
            <label style="font-size:12px; font-weight:600; color:var(--text-secondary);">Pilih Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') ?? date('Y-m-d') }}">
        </div>
        <button type="submit" class="filter-btn" style="margin-top:19px;">
          <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          Filter Data
        </button>
      </form>
      <a href="/kasir/laporan/pdf?tanggal={{ request('tanggal') }}" class="download-btn" style="margin-top:19px;">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        Download PDF
      </a>
    </div>

    <div class="table-wrap">
        @php $grandTotal = 0; @endphp
        @if($orders->isEmpty())

            {{-- ── EMPTY STATE (DIUPGRADE) ── --}}
            <div class="empty-state">

                <div class="empty-icon-ring">
                    <div class="empty-icon-inner">
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>
                </div>

                @if(request('tanggal') && request('tanggal') !== date('Y-m-d'))
                    <h3>Tidak ada data untuk filter ini</h3>
                    <p>
                        Tidak ditemukan transaksi pada tanggal<br>
                        <strong style="color:#475569;">{{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}</strong>.<br>
                        Coba pilih tanggal yang berbeda.
                    </p>
                @else
                    <h3>Belum ada data laporan untuk hari ini</h3>
                    <p>
                        Transaksi yang berhasil diselesaikan hari ini<br>
                        akan muncul di sini secara otomatis.
                    </p>
                @endif

                <div class="empty-tag">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ request('tanggal') ? \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') : \Carbon\Carbon::today()->translatedFormat('d F Y') }}
                </div>

            </div>

        @else
            <table class="rtable" id="laporanTable" style="width:100%;">
                <thead>
                    <tr>
                        <th style="padding-left:22px;">No</th>
                        <th>Waktu Transaksi</th>
                        <th>ID Order</th>
                        <th>Meja</th>
                        <th>Detail Pesanan</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th style="text-align:right;">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders as $i => $order)
                @php $grandTotal += $order->total; @endphp
                <tr>
                    <td style="padding-left:22px;">{{ $i + 1 }}</td>
                    <td>
                        <span style="font-weight: 600; color: var(--text-primary); display: block;">{{ $order->created_at->format('d M Y') }}</span>
                        <span style="font-size: 12px; color: var(--text-muted);">{{ $order->created_at->format('H:i') }} WIB</span>
                    </td>
                    <td>
                        <span style="font-weight:800; color:var(--accent); font-size:13px;">
                            {{ $order->queue_number ?: 'A-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        <span style="background:var(--surface-2); padding:4px 10px; border-radius:8px; font-size:12px; font-weight:700; color:var(--text-secondary); white-space:nowrap;">
                            {{ $order->table_number ? 'Meja '.$order->table_number : 'Take Away' }}
                        </span>
                    </td>
                    <td>
                        @if($order->items && $order->items->count() > 0)
                            @foreach($order->items->take(2) as $item)
                                <span style="font-size:13px;">
                                    {{ $item->qty ?? $item->quantity ?? 1 }}x
                                    {{ $item->name ?? $item->menu->name ?? $item->menu->nama ?? '-' }}
                                    @if(!$loop->last)<br>@endif
                                </span>
                            @endforeach
                            @if($order->items->count() > 2)
                                <br><span style="color:var(--text-muted); font-size:12px;">+{{ $order->items->count()-2 }} lagi</span>
                            @endif
                        @else
                            <span style="font-size:13px; color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($order->payment_method === 'cash')
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">💵 Cash</span>
                        @elseif($order->payment_method === 'qris')
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700; background:#ede9fe; color:#5b21b6; border:1px solid #ddd6fe;">📱 QRIS</span>
                        @else
                            <span style="font-size:12px; color:var(--text-secondary); text-transform:capitalize;">{{ $order->payment_method ?? 'Tunai' }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            if ($order->status === 'pending') {
                                $bg = 'pending'; $text = 'Menunggu Bayar';
                            } elseif ($order->status === 'paid') {
                                $bg = 'selesai'; $text = 'Lunas, Belum Diproses';
                            } elseif ($order->status === 'process') {
                                $bg = 'proses'; $text = 'Sedang Dimasak';
                            } elseif (in_array($order->status, ['done', 'delivered'])) {
                                $bg = 'diantar'; $text = 'Selesai Diantar';
                            } else {
                                $bg = 'pending'; $text = ucfirst($order->status);
                            }
                        @endphp
                        <span class="badge {{ $bg }}">
                            <span class="badge-dot"></span>
                            {{ $text }}
                        </span>
                    </td>
                    <td style="font-weight:700; color:var(--text-primary); text-align:right; white-space:nowrap;">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div style="display:flex; justify-content:flex-end; align-items:center; gap:16px; padding:18px 24px; border-top:2px solid var(--border); background:var(--surface-2);">
                <span style="font-size:14px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.5px; font-family:'Inter',sans-serif;">Total Pendapatan :</span>
                <span style="font-size:20px; font-weight:800; color:var(--green); font-family:'Inter',sans-serif; white-space:nowrap;">
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </span>
            </div>
        @endif
    </div>{{-- end table-wrap --}}
@endsection

@push('scripts')
<script>

function ksToast(msg,type,dur){type=type||'success';dur=dur||2400;var c=document.getElementById('ksToastContainer');if(!c)return;var colors={success:'background:linear-gradient(135deg,#059669,#047857);',info:'background:linear-gradient(135deg,#2563eb,#1d4ed8);',warning:'background:linear-gradient(135deg,#d97706,#b45309);',error:'background:linear-gradient(135deg,#dc2626,#b91c1c);'};var icons={success:'✅',info:'ℹ️',warning:'⚠️',error:'❌'};var t=document.createElement('div');t.style.cssText='pointer-events:auto;display:flex;align-items:center;gap:9px;padding:11px 18px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.18);font-size:13px;font-weight:600;font-family:"Plus Jakarta Sans",sans-serif;white-space:nowrap;color:white;opacity:0;transform:translateX(18px) scale(0.95);transition:all 0.25s cubic-bezier(.34,1.56,.64,1);max-width:340px;'+(colors[type]||colors.info);t.innerHTML='<span style="font-size:15px;">'+(icons[type]||'📢')+'</span><span>'+msg+'</span>';c.appendChild(t);requestAnimationFrame(function(){t.style.opacity='1';t.style.transform='translateX(0) scale(1)';});setTimeout(function(){t.style.opacity='0';t.style.transform='translateX(18px) scale(0.95)';setTimeout(function(){t.remove();},260);},dur);}


// Setup DataTables
$(document).ready(function () {
    if (!$('#laporanTable').length) return;

    if ($.fn.DataTable.isDataTable('#laporanTable')) {
        $('#laporanTable').DataTable().destroy();
    }

    var dt = $('#laporanTable').DataTable({
        destroy    : true,
        autoWidth  : false,
        pageLength : 15,
        ordering   : false,
        searching  : true,
        paging     : true,
        info       : true,
        language: {
            search      : "Cari Laporan:",
            lengthMenu  : "Tampilkan _MENU_ data",
            info        : "Menampilkan _START_ sampai _END_ dari _TOTAL_ laporan",
            emptyTable  : "Belum ada laporan aktif pada periode ini",
            zeroRecords : "Data tidak ditemukan",
            paginate    : { previous: "Sebelumnya", next: "Selanjutnya" }
        }
    });

    /* feedback cari */
    var st;
    $('#laporanTable_filter input').on('input', function() {
        clearTimeout(st);
        var val = $(this).val().trim();
        st = setTimeout(function() { if(val) ksToast('🔍 Mencari: "' + val + '"', 'info', 1400); }, 600);
    });
    /* feedback halaman */
    dt.on('page.dt', function() {
        var info = dt.page.info();
        ksToast('Halaman ' + (info.page+1) + ' dari ' + info.pages, 'info', 1200);
    });
    /* feedback jumlah per halaman */
    dt.on('length.dt', function(e, s, len) {
        ksToast('Menampilkan ' + len + ' data per halaman', 'info', 1400);
    });

    ksToast('📊 Laporan dimuat — ' + dt.data().length + ' data', 'success', 2200);

    /* feedback filter tanggal */
    document.querySelector('.filter-btn') && document.querySelector('.filter-btn').addEventListener('click', function() {
        var tgl = document.querySelector('input[name="tanggal"]');
        if (tgl && tgl.value) ksToast('🗓️ Filter laporan: ' + tgl.value, 'info', 2000);
    });

    /* feedback download PDF */
    document.querySelector('.download-btn') && document.querySelector('.download-btn').addEventListener('click', function() {
        ksToast('📄 Mengunduh laporan PDF...', 'success', 3000);
    });
});

</script>
@endpush