<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Kasir — Transaksi</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

{{-- DataTables CSS & Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

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

.header { position: fixed; top: 0; left: 0; right: 0; height: var(--header-h); background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; z-index: 100; box-shadow: var(--shadow-header); }
.logo { display: flex; align-items: center; gap: 10px; }
.logo-mark { width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgb(37 99 235/.35); }
.logo-mark svg { width: 18px; height: 18px; stroke: white; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.logo-text { font-size: 16px; font-weight: 800; letter-spacing: -0.5px; }
.logo-text span { color: var(--accent); }
.header-right { display: flex; align-items: center; gap: 8px; }
.header-clock{
  display:flex;
  align-items:center;
  gap:8px;
  padding:8px 14px;
  border-radius:12px;
  background:var(--surface);
  border:1px solid var(--border);
  font-family:'Inter',sans-serif;
  box-shadow:var(--shadow-sm);
}

.header-clock svg{
  width:16px;
  height:16px;
  stroke:var(--accent);
  stroke-width:2.3;
  fill:none;
}

#liveClock{
  font-size:13px;
  font-weight:700;
  color:var(--text-primary);
  letter-spacing:.5px;
}

/* ── PROFILE DROPDOWN ── */
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

.main { margin-top: var(--total-top); padding: 36px 24px 72px; width: 100%; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 8px; }
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 32px; gap: 16px; }
.page-title { font-size: 23px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
.page-sub { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-family: 'Inter', sans-serif; }
.date-chip { display: flex; align-items: center; gap: 7px; padding: 9px 16px; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; font-size: 13px; font-weight: 600; color: var(--text-secondary); box-shadow: var(--shadow-sm); }
.date-chip svg { width: 15px; height: 15px; stroke: var(--accent); stroke-width: 2; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
.section-title { font-size: 15px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
.section-title svg { width: 16px; height: 16px; stroke: var(--accent); stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }

/* ══ BOX & TABLE STYLE (Sinkron Admin Index) ══ */
.table-wrap {
    background: white; border-radius: var(--radius-lg); border: 1px solid var(--border);
    box-shadow: 0 2px 10px rgba(0,0,0,.05); overflow-x: auto; padding-bottom: 5px;
}
.rtable { width: 100%; border-collapse: collapse; font-family: 'Inter', sans-serif; min-width: 700px; }
.rtable thead th {
    background: #f8fafc; padding: 14px 16px; font-size: 11.5px;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px;
    color: #64748b; text-align: left; border-bottom: 1px solid #f1f5f9;
}
.rtable tbody tr { border-bottom: 1px solid #f8fafc; transition: background .15s; background: white; }
.rtable tbody tr:hover { background: #fafbff; }
.rtable td { padding: 15px 16px; font-size: 13.5px; color: #334155; vertical-align: middle; }

/* Status Badges Sinkron Admin */
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

/* Datatable Custom Style */
.dataTables_wrapper { padding: 0 0 18px; }
.dataTables_filter { padding: 18px 22px 0; float: right; }
.dataTables_length { padding: 18px 22px 0; float: left; }
.dataTables_filter input, .dataTables_length select { border: 1px solid var(--border); border-radius: 8px; padding: 6px 10px; font-size: 13px; outline: none; }
.dataTables_filter input:focus, .dataTables_length select:focus { border-color: var(--accent); }
.dataTables_info { font-size: 13px !important; color: #64748b !important; padding: 14px 22px 0 !important; font-weight: 500; font-family: 'Inter', sans-serif; }
.dataTables_paginate { padding: 12px 22px 0 !important; display: flex !important; align-items: center; gap: 4px; font-family: 'Inter', sans-serif; }
.paginate_button {
    border-radius: 10px !important; border: 1px solid #e2e8f0 !important;
    padding: 6px 13px !important; margin: 0 2px !important; background: white !important;
    color: #475569 !important; font-size: 13px !important; font-weight: 600 !important;
    cursor: pointer !important; transition: all .15s !important;
}
.paginate_button:hover:not(.current):not(.disabled) { background: #eff6ff !important; color: #2563eb !important; border-color: #bfdbfe !important; }
.paginate_button.current, .paginate_button.current:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
    color: white !important; border-color: #2563eb !important;
    box-shadow: 0 2px 8px rgba(37,99,235,0.3) !important;
}
.paginate_button.disabled, .paginate_button.disabled:hover { color: #cbd5e1 !important; cursor: default !important; }
.dataTables_scrollBody::-webkit-scrollbar { height: 5px; }
.dataTables_scrollBody::-webkit-scrollbar-track { background: transparent; border-radius: 99px; }
.dataTables_scrollBody::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 99px; }

@media (max-width: 640px) {
  .main { padding: 24px 16px 48px; }
  .nav-link span { display: none; }
  .user-role, .user-info { display: none; }
  .page-header { flex-direction: column; }
}
</style>
</head>
<body>

<header class="header">
  <div class="logo">
    <div class="logo-mark">
      <svg viewBox="0 0 24 24">
        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </div>
    <div class="logo-text">Kasir<span></span></div>
  </div>

  <div class="header-right">
    <div class="header-clock">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      <span id="liveClock">00:00:00</span>
    </div>

    <div class="profile-wrap">
      <div class="user-btn" id="profileBtn" onclick="toggleDropdown()">
        <div class="avatar">
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
            @else
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            @endif
        </div>
        <div class="user-info">
          <div class="user-name">{{ Auth::user()->name }}</div>
          <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
        </div>
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
      </div>

      <div class="dropdown" id="profileDropdown">
        <div class="dropdown-header">
          <div class="dropdown-avatar">
              @if(Auth::user()->avatar)
                  <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
              @else
                  {{ strtoupper(substr(Auth::user()->name,0,1)) }}
              @endif
          </div>
          <div>
            <div class="dropdown-name">{{ Auth::user()->name }}</div>
            <div class="dropdown-role">{{ ucfirst(Auth::user()->role) }} · Online</div>
          </div>
        </div>

        <div class="dropdown-body">
          <a href="/kasir/account/profil" class="dropdown-item">
            <div class="item-icon"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Profil Saya
          </a>
          <a href="/kasir/account/ganti-sandi" class="dropdown-item">
            <div class="item-icon"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
            Ganti Password
          </a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item danger">
              <div class="item-icon"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></div>
              Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>

<nav class="topnav">
  <div class="nav-container">
    <a href="/kasir/dashboard" class="nav-link {{ request()->is('kasir/dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      <span>Dashboard</span>
    </a>
    <a href="/kasir/pesanan" class="nav-link {{ request()->is('kasir/pesanan') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      <span>Pesanan</span>
    </a>
    <a href="/kasir/transaksi" class="nav-link {{ request()->is('kasir/transaksi') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      <span>Transaksi</span>
    </a>
    <a href="/kasir/laporan" class="nav-link {{ request()->is('kasir/laporan') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      <span>Laporan</span>
    </a>
  </div>
</nav>

<main class="main">
  <div class="container">
    <div class="page-header">
      <div>
        <div class="page-title">Transaksi</div>
        <div class="page-sub">{{ now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp; Riwayat semua transaksi</div>
      </div>
      <div class="date-chip">
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        {{ now()->translatedFormat('d F Y') }}
      </div>
    </div>

    <div class="section-header">
      <div class="section-title">
        <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        Daftar Transaksi
      </div>
    </div>

    <div class="table-wrap">
        @if($orders->isEmpty())
            <div style="text-align:center; padding:56px 20px;">
                <svg viewBox="0 0 24 24" style="width:40px;height:40px;stroke:#cbd5e1;stroke-width:1.5;fill:none;margin:0 auto 12px;display:block;"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span style="font-weight:600; font-size:14px; color:#64748b; display:block;">Belum ada transaksi hari ini</span>
            </div>
        @else
            <table class="rtable" id="transaksiTable" style="width:100%;">
                <thead>
                    <tr>
                        <th style="padding-left:22px;">ID Order</th>
                        <th>Meja</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                <tr>
                    <td style="padding-left:22px;">
                        <span style="font-weight:800; color:#2563eb; font-size:13px;">
                            {{ $order->queue_number ?: 'A-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        <span style="background:#f1f5f9; padding:4px 10px; border-radius:8px; font-size:12px; font-weight:700; color:#475569;">
                            {{ $order->table_number ? 'Meja '.$order->table_number : 'Take Away' }}
                        </span>
                    </td>
                    <td>
                        @if($order->items && $order->items->count() > 0)
                            @foreach($order->items->take(2) as $item)
                                <span style="font-size:13px;">
                                    {{ $item->qty ?? $item->quantity ?? 1 }}x
                                    {{ $item->name ?? $item->menu->name ?? $item->menu->nama ?? '-' }}
                                    @if(!$loop->last), @endif
                                </span>
                            @endforeach
                            @if($order->items->count() > 2)
                                <span style="color:#94a3b8; font-size:12px;">+{{ $order->items->count()-2 }} lagi</span>
                            @endif
                        @else
                            <span style="font-size:13px; color:#94a3b8;">-</span>
                        @endif
                    </td>
                    <td style="font-weight:600; font-size:13px; color:#0f172a;">
                        Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($order->payment_method === 'cash')
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">💵 Cash</span>
                        @elseif($order->payment_method === 'qris')
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700; background:#ede9fe; color:#5b21b6; border:1px solid #ddd6fe;">📱 QRIS</span>
                        @else
                            <span style="font-size:12px; color:#64748b; text-transform:capitalize;">{{ $order->payment_method ?? 'Tunai' }}</span>
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
                    <td style="font-size:12px; color:#94a3b8; white-space:nowrap;">
                        {{ $order->created_at->format('H:i') }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>{{-- end table-wrap --}}
  </div>{{-- end container --}}
</main>

<!-- ── TOAST KASIR ── -->
<div id="ksToastContainer" style="position:fixed;top:80px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:8px;align-items:flex-end;pointer-events:none;"></div>

<script>
function updateClock() {
    const now = new Date();

    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');

    const clock = document.getElementById('liveClock');

    if (clock) {
        clock.textContent = `${h}:${m}:${s}`;
    }
}

setInterval(updateClock, 1000);
updateClock();



/* =========================
   DROPDOWN PROFILE
========================= */

function toggleDropdown() {

    const btn = document.getElementById('profileBtn');
    const dropdown = document.getElementById('profileDropdown');

    if (!btn || !dropdown) return;

    const isOpen = dropdown.classList.contains('show');

    dropdown.classList.toggle('show', !isOpen);
    btn.classList.toggle('open', !isOpen);
}

document.addEventListener('click', function (e) {

    const wrap = document.querySelector('.profile-wrap');

    if (wrap && !wrap.contains(e.target)) {

        const dropdown = document.getElementById('profileDropdown');
        const btn = document.getElementById('profileBtn');

        if (dropdown) dropdown.classList.remove('show');
        if (btn) btn.classList.remove('open');
    }
});

document.addEventListener('keydown', function (e) {

    if (e.key === 'Escape') {

        const dropdown = document.getElementById('profileDropdown');
        const btn = document.getElementById('profileBtn');

        if (dropdown) dropdown.classList.remove('show');
        if (btn) btn.classList.remove('open');
    }
});



/* =========================
   DATATABLE
========================= */

/* ── TOAST ── */
function ksToast(msg,type,dur){type=type||'success';dur=dur||2400;var c=document.getElementById('ksToastContainer');if(!c)return;var colors={success:'background:linear-gradient(135deg,#059669,#047857);',info:'background:linear-gradient(135deg,#2563eb,#1d4ed8);',warning:'background:linear-gradient(135deg,#d97706,#b45309);',error:'background:linear-gradient(135deg,#dc2626,#b91c1c);'};var icons={success:'✅',info:'ℹ️',warning:'⚠️',error:'❌'};var t=document.createElement('div');t.style.cssText='pointer-events:auto;display:flex;align-items:center;gap:9px;padding:11px 18px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.18);font-size:13px;font-weight:600;font-family:"Plus Jakarta Sans",sans-serif;white-space:nowrap;color:white;opacity:0;transform:translateX(18px) scale(0.95);transition:all 0.25s cubic-bezier(.34,1.56,.64,1);max-width:340px;'+(colors[type]||colors.info);t.innerHTML='<span style="font-size:15px;">'+(icons[type]||'📢')+'</span><span>'+msg+'</span>';c.appendChild(t);requestAnimationFrame(function(){t.style.opacity='1';t.style.transform='translateX(0) scale(1)';});setTimeout(function(){t.style.opacity='0';t.style.transform='translateX(18px) scale(0.95)';setTimeout(function(){t.remove();},260);},dur);}

$(document).ready(function () {
    // Hanya init jika tabel ada (ada data)
    if (!$('#transaksiTable').length) return;

    if ($.fn.DataTable.isDataTable('#transaksiTable')) {
        $('#transaksiTable').DataTable().destroy();
    }

    var dt = $('#transaksiTable').DataTable({
        destroy      : true,
        responsive   : false,
        autoWidth    : false,
        pageLength   : 15,
        ordering     : true,
        searching    : true,
        paging       : true,
        info         : true,
        language: {
            search      : "Cari:",
            lengthMenu  : "Tampilkan _MENU_ data",
            info        : "Menampilkan _START_ sampai _END_ dari _TOTAL_ transaksi",
            emptyTable  : "Belum ada transaksi hari ini",
            zeroRecords : "Data tidak ditemukan",
            paginate    : { previous: "Sebelumnya", next: "Selanjutnya" }
        }
    });

    /* feedback pencarian */
    var searchTimer;
    $('#transaksiTable_filter input').on('input', function() {
        clearTimeout(searchTimer);
        var val = $(this).val().trim();
        searchTimer = setTimeout(function() {
            if (val) ksToast('🔍 Mencari: "' + val + '"', 'info', 1400);
        }, 600);
    });

    /* feedback sort kolom */
    $('#transaksiTable thead th').on('click', function() {
        var colName = $(this).text().trim();
        ksToast('Diurutkan berdasarkan ' + colName, 'info', 1400);
    });

    /* feedback ganti halaman */
    dt.on('page.dt', function() {
        var info = dt.page.info();
        ksToast('Halaman ' + (info.page + 1) + ' dari ' + info.pages, 'info', 1200);
    });

    /* feedback ganti jumlah tampil */
    dt.on('length.dt', function(e, s, len) {
        ksToast('Menampilkan ' + len + ' data per halaman', 'info', 1400);
    });

    ksToast('📋 Data transaksi dimuat — ' + dt.data().length + ' transaksi', 'success', 2200);
});
</script>
</body>
</html>