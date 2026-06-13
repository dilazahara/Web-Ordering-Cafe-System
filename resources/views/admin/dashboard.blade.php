@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
/* ══════════════════════════════════════
           CSS VARIABLES — sumber kebenaran tunggal
        ══════════════════════════════════════ */
        :root {
            /* Font */
            --font: 'Inter', sans-serif;
            --text-xs:   11px;
            --text-sm:   12px;
            --text-base: 13px;
            --text-md:   14px;
            --text-lg:   15px;
            --text-xl:   17px;
            --text-2xl:  22px;
            --text-3xl:  26px;
            --text-4xl:  32px;

            /* Warna Utama */
            --primary:        #6366f1;
            --primary-hover:  #4f46e5;
            --primary-light:  #eff0fe;
            --primary-dark:   #7c3aed;

            /* Teks */
            --text-dark:   #0f172a;
            --text-base-c: #1e293b;
            --text-mid:    #334155;
            --text-light:  #64748b;
            --text-muted:  #94a3b8;

            /* Background */
            --bg:          #f8fafc;
            --bg-white:    #ffffff;
            --border:      #e2e8f0;
            --border-light:#f1f5f9;

            /* Spacing konsisten */
            --space-xs:  4px;
            --space-sm:  8px;
            --space-md:  16px;
            --space-lg:  22px;
            --space-xl:  28px;
            --space-2xl: 36px;

            /* Border Radius konsisten */
            --radius-sm:   8px;
            --radius-md:   10px;
            --radius-lg:   12px;
            --radius-xl:   14px;
            --radius-2xl:  18px;
            --radius-3xl:  20px;
            --radius-full: 999px;

            /* Shadow */
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 2px 10px rgba(0,0,0,0.05);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.08);

            /* Topbar */
            --topbar-h: 68px;
        }

        /* ── RESET ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font); font-size: var(--text-md); background: var(--bg); color: var(--text-base-c); }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed; top: 0; left: 0; right: 0; height: var(--topbar-h);
            background: rgba(255,255,255,0.97); backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 var(--space-xl); z-index: 1000;
            box-shadow: var(--shadow-sm);
        }
        .topbar-left { display: flex; align-items: center; gap: var(--space-md); }
        .menu-btn {
            width: 38px; height: 38px; border-radius: var(--radius-lg);
            border: none; background: var(--border-light); color: var(--text-dark);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .menu-btn:hover { background: var(--border); }

        /* Clock */
        .live-clock {
            display: flex; align-items: center; gap: 7px;
            background: var(--bg); border: 1px solid var(--border);
            padding: 6px 12px; border-radius: var(--radius-lg);
            font-size: var(--text-base); font-weight: 700;
            color: var(--text-dark); letter-spacing: 0.5px;
        }

        /* Profile dropdown */
        .topbar-right { display: flex; align-items: center; gap: var(--space-sm); }
        .profile-wrap { position: relative; }
        .user-btn {
            display: flex; align-items: center; gap: 9px;
            padding: 5px 10px 5px 5px; border: 1px solid #c7d2e0;
            border-radius: var(--radius-lg); background: var(--bg);
            cursor: pointer; transition: all 0.18s; user-select: none;
        }
        .user-btn.open { border-color: var(--primary-dark); background: #f5f3ff; box-shadow: 0 0 0 3px rgba(124,58,237,.1); }
        .user-avatar {
            width: 30px; height: 30px; border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: var(--text-xs); font-weight: 800; flex-shrink: 0; overflow: hidden;
        }
        .user-avatar.has-photo { background: none; }
        .user-avatar:not(.has-photo) { background: linear-gradient(135deg, #818cf8, #4f46e5); }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md); }
        .user-name { font-size: var(--text-base); font-weight: 700; color: var(--text-dark); }
        .user-role { font-size: var(--text-xs); color: var(--text-muted); }
        .chevron { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2.5; transition: transform .2s; }
        .user-btn.open .chevron { transform: rotate(180deg); }

        .dropdown {
            position: absolute; top: calc(100% + 8px); right: 0; width: 230px;
            background: var(--bg-white); border: 1px solid var(--border);
            border-radius: var(--radius-2xl);
            box-shadow: 0 16px 48px rgba(0,0,0,.13); overflow: hidden;
            opacity: 0; transform: translateY(-8px) scale(.97);
            pointer-events: none; transition: opacity .18s, transform .18s; z-index: 200;
        }
        .dropdown.show { opacity: 1; transform: none; pointer-events: all; }
        .dp-head {
            padding: var(--space-md); background: linear-gradient(135deg,#eef2ff,#f5f3ff);
            border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 11px;
        }
        .dp-av {
            width: 38px; height: 38px; border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: var(--text-md); font-weight: 800;
            box-shadow: 0 2px 8px rgba(79,70,229,.28); overflow: hidden; flex-shrink: 0;
        }
        .dp-av.has-photo { background: none; }
        .dp-av:not(.has-photo) { background: linear-gradient(135deg,#818cf8,#4f46e5); }
        .dp-av img { width: 100%; height: 100%; object-fit: cover; border-radius: 11px; }
        .dp-nm { font-size: var(--text-base); font-weight: 800; color: var(--text-dark); }
        .dp-rl { font-size: var(--text-xs); color: var(--text-light); margin-top: 1px; }
        .dp-body { padding: 7px; }
        .dp-item {
            display: flex; align-items: center; gap: var(--space-sm);
            padding: 9px 11px; border-radius: var(--radius-md); text-decoration: none;
            font-size: var(--text-base); font-weight: 600; color: var(--text-dark);
            transition: all .15s; border: none; background: none; width: 100%;
            cursor: pointer; font-family: var(--font); text-align: left;
        }
        .dp-item:hover { background: var(--bg); }
        .dp-ico {
            width: 30px; height: 30px; border-radius: var(--radius-sm);
            background: var(--border-light);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .dp-ico svg { width: 15px; height: 15px; stroke: var(--text-light); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .dp-divider { height: 1px; background: var(--border-light); margin: 5px 7px; }
        .dp-item.danger { color: #dc2626; }
        .dp-item.danger:hover { background: #fef2f2; }
        .dp-item.danger .dp-ico { background: #fef2f2; }
        .dp-item.danger .dp-ico svg { stroke: #dc2626; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 240px; height: 100vh; position: fixed;
            background: linear-gradient(180deg, #0f172a, #1e1b4b);
            padding: 30px; padding-top: 100px; color: white;
            overflow-y: auto; transform: translateX(-100%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999; box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            display: flex; flex-direction: column; gap: var(--space-xs);
        }
        .sidebar.show { transform: translateX(0); }
        .menu-section {
            font-size: var(--text-xs); letter-spacing: 1px; color: #a78bfa;
            margin: var(--space-md) 10px var(--space-sm); opacity: 0.7; font-weight: 700;
        }
        .sidebar a {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: var(--radius-lg); text-decoration: none;
            color: #94a3b8; font-weight: 500; font-size: var(--text-md);
            transition: all 0.25s;
        }
        .sidebar a:hover { background: rgba(255,255,255,0.08); color: white; }
        .sidebar a.active {
            background: rgba(139, 92, 246, 0.25); color: #c4b5fd;
            box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4);
        }
        .sidebar i { width: 18px; height: 18px; stroke-width: 2.2; flex-shrink: 0; }

        /* ── OVERLAY ── */
        .overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.35); z-index: 998; backdrop-filter: blur(2px);
        }
        .overlay.show { display: block; }

        /* ── MAIN ── */
        .main { padding: 92px var(--space-xl) var(--space-2xl); }

        /* ── PAGE TITLE ── */
        .page-title h1 { font-size: var(--text-3xl); font-weight: 800; color: var(--text-dark); letter-spacing: -0.4px; }
        .page-title p { font-size: var(--text-md); color: var(--text-light); margin-top: 3px; }

        /* ── STATS GRID ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: var(--space-md); margin-top: var(--space-lg);
        }
        .stat-card {
            background: var(--bg-white); border-radius: var(--radius-2xl);
            padding: 20px var(--space-lg);
            border: 1px solid var(--border-light); box-shadow: var(--shadow-md);
            display: flex; align-items: center; gap: var(--space-md);
            position: relative; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .stat-card::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px; border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
        }
        .stat-card.orange::after { background: linear-gradient(90deg, #f97316, #fb923c); }
        .stat-card.blue::after   { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .stat-card.cyan::after   { background: linear-gradient(90deg, #06b6d4, #22d3ee); }
        .stat-card.green::after  { background: linear-gradient(90deg, #22c55e, #4ade80); }
        .stat-card.purple::after { background: linear-gradient(90deg, #7c3aed, #a855f7); }
        .stat-card.red::after    { background: linear-gradient(90deg, #ef4444, #f87171); }
        .stat-card.dark::after   { background: linear-gradient(90deg, #334155, #475569); }
        .stat-card.pink::after   { background: linear-gradient(90deg, #ec4899, #f472b6); }

        .stat-icon {
            width: 50px; height: 50px; border-radius: var(--radius-xl); flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon i { width: 22px; height: 22px; }
        .stat-icon.orange { background: #fff7ed; color: #f97316; }
        .stat-icon.blue   { background: #eff6ff; color: #3b82f6; }
        .stat-icon.cyan   { background: #ecfeff; color: #06b6d4; }
        .stat-icon.green  { background: #f0fdf4; color: #22c55e; }
        .stat-icon.purple { background: #f3e8ff; color: #7c3aed; }
        .stat-icon.red    { background: #fef2f2; color: #ef4444; }
        .stat-icon.dark   { background: #e2e8f0; color: #334155; }
        .stat-icon.pink   { background: #fdf2f8; color: #ec4899; }

        .stat-val  { font-size: var(--text-2xl); font-weight: 800; color: var(--text-dark); letter-spacing: -0.5px; }
        .stat-lbl  { font-size: var(--text-sm); color: var(--text-light); font-weight: 500; margin-top: 2px; }
        .stat-trend { font-size: var(--text-xs); font-weight: 700; margin-top: 4px; }
        .stat-trend.up      { color: #22c55e; }
        .stat-trend.neutral { color: var(--text-muted); }

        /* ── CONTENT GRID ── */
        .content-grid {
            display: grid; grid-template-columns: 2fr 1fr;
            gap: 20px; margin-top: 20px;
        }
        .box {
            background: var(--bg-white); border-radius: var(--radius-2xl);
            border: 1px solid var(--border-light); box-shadow: var(--shadow-md);
        }
        .box-header {
            padding: var(--space-md) var(--space-lg);
            border-bottom: 1px solid var(--border-light);
            display: flex; align-items: center; justify-content: space-between;
        }
        .box-header h3 { font-size: var(--text-lg); font-weight: 700; color: var(--text-dark); }
        .box-body { padding: var(--space-md) var(--space-lg); }
        .chart-wrap { position: relative; height: 220px; }

        /* ── TABLE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .rtable { width: 100%; min-width: 800px; border-collapse: collapse; white-space: nowrap; }
        .rtable thead th {
            background: var(--bg); padding: 11px var(--space-md);
            font-size: var(--text-xs); font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.7px;
            color: var(--text-light); text-align: left;
            border-bottom: 1px solid var(--border-light);
        }
        .rtable tbody tr { border-bottom: 1px solid var(--border-light); transition: background .15s; }
        .rtable tbody tr:last-child { border-bottom: none; }
        .rtable tbody tr:hover { background: #fafbff; }
        .rtable td { padding: 13px var(--space-md); font-size: var(--text-base); color: var(--text-mid); vertical-align: middle; }

        /* ── BADGE ── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: var(--radius-full);
            font-size: var(--text-xs); font-weight: 700;
        }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge.pending  { background: #fef3c7; color: #92400e; }
        .badge.pending .badge-dot  { background: #f59e0b; }
        .badge.proses   { background: #dbeafe; color: #1e40af; }
        .badge.proses .badge-dot   { background: #3b82f6; }
        .badge.selesai  { background: #dcfce7; color: #15803d; }
        .badge.selesai .badge-dot  { background: #22c55e; }
        .badge.diantar  { background: #f0fdf4; color: #15803d; }
        .badge.diantar .badge-dot  { background: #22c55e; }

        /* ── DATATABLES CUSTOM ── */
        .dataTables_wrapper { padding: 0 0 var(--space-md); }
        .dataTables_filter, .dataTables_length { display: none !important; }
        .dataTables_info {
            font-size: var(--text-base) !important; color: var(--text-light) !important;
            padding: var(--space-md) var(--space-lg) 0 !important; font-weight: 500;
        }
        .dataTables_paginate {
            padding: 12px var(--space-lg) 0 !important;
            display: flex !important; align-items: center; gap: var(--space-xs);
        }
        .paginate_button {
            border-radius: var(--radius-md) !important; border: 1px solid var(--border) !important;
            padding: 6px 13px !important; margin: 0 2px !important;
            background: var(--bg-white) !important; color: var(--text-light) !important;
            font-size: var(--text-base) !important; font-weight: 600 !important;
            cursor: pointer !important; transition: all .15s !important;
            font-family: var(--font) !important;
        }
        .paginate_button:hover:not(.current):not(.disabled) {
            background: #eff6ff !important; color: #2563eb !important; border-color: #bfdbfe !important;
        }
        .paginate_button.current, .paginate_button.current:hover {
            background: linear-gradient(135deg, #7c3aed, #6d28d9) !important;
            color: white !important; border-color: #7c3aed !important;
            box-shadow: 0 2px 8px rgba(124,58,237,0.3) !important;
        }
        .paginate_button.disabled, .paginate_button.disabled:hover {
            color: #cbd5e1 !important; cursor: default !important;
        }

        /* ── MENU TERLARIS ── */
        .menu-rank { display: flex; flex-direction: column; gap: 12px; }
        .menu-rank-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px; border-radius: var(--radius-xl);
            background: var(--bg); transition: all .2s; border: 1px solid var(--border-light);
        }
        .menu-rank-item:hover {
            background: var(--bg-white); transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0,0,0,.06);
        }
        .rank-num {
            width: 28px; height: 28px; border-radius: var(--radius-md); flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: var(--text-sm); font-weight: 800; color: white;
        }
        .rank-num.r1 { background: linear-gradient(135deg, #f59e0b, #f97316); }
        .rank-num.r2 { background: linear-gradient(135deg, #94a3b8, #64748b); }
        .rank-num.r3 { background: linear-gradient(135deg, #b45309, #92400e); }
        .rank-num.rn { background: var(--border); color: var(--text-light); }
        .rank-img { width: 50px; height: 50px; object-fit: cover; border-radius: var(--radius-lg); flex-shrink: 0; border: 1px solid var(--border-light); }
        .rank-info { flex: 1; min-width: 0; }
        .rank-name { font-size: var(--text-base); font-weight: 700; color: var(--text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .rank-sub  { font-size: var(--text-xs); color: var(--text-muted); margin-top: 3px; }
        .rank-qty  { font-size: var(--text-md); font-weight: 800; color: #7c3aed; flex-shrink: 0; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 780px) {
            .content-grid { grid-template-columns: 1fr; }
            .main { padding: 88px var(--space-md) 30px; }
        }
        @media (max-width: 540px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
</style>
@endpush

@section('content')
<div class="page-title">
    <h1>Selamat datang, {{ auth()->user()->name }}! 👋</h1>
    <p>Berikut ringkasan aktivitas cafe hari ini, {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card orange">
        <div class="stat-icon orange"><i data-lucide="banknote"></i></div>
        <div>
            <div class="stat-val">Rp {{ number_format($totalPenjualan,0,',','.') }}</div>
            <div class="stat-lbl">Total Penjualan</div>
            <div class="stat-trend neutral">💰 Kumulatif transaksi selesai</div>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue"><i data-lucide="shopping-bag"></i></div>
        <div>
            <div class="stat-val">{{ $totalOrder }}</div>
            <div class="stat-lbl">Total Order</div>
            <div class="stat-trend up">📦 Semua status</div>
        </div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-icon cyan"><i data-lucide="chef-hat"></i></div>
        <div>
            <div class="stat-val">{{ $totalDiproses }}</div>
            <div class="stat-lbl">Sedang Diproses</div>
            <div class="stat-trend neutral">🔥 Di dapur sekarang</div>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><i data-lucide="check-circle-2"></i></div>
        <div>
            <div class="stat-val">{{ $totalSelesai }}</div>
            <div class="stat-lbl">Order Selesai</div>
            <div class="stat-trend up">✅ Berhasil diantar</div>
        </div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon purple"><i data-lucide="armchair"></i></div>
        <div>
            <div class="stat-val">{{ $totalMeja }}</div>
            <div class="stat-lbl">Total Meja</div>
            <div class="stat-trend neutral">🪑 Meja tersedia</div>
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red"><i data-lucide="receipt"></i></div>
        <div>
            <div class="stat-val">{{ $totalTransaksi }}</div>
            <div class="stat-lbl">Total Transaksi</div>
            <div class="stat-trend up">💳 Semua transaksi</div>
        </div>
    </div>
    <div class="stat-card dark">
        <div class="stat-icon dark"><i data-lucide="users"></i></div>
        <div>
            <div class="stat-val">{{ $totalUser }}</div>
            <div class="stat-lbl">Total User</div>
            <div class="stat-trend neutral">👤 Pengguna sistem</div>
        </div>
    </div>
    <div class="stat-card pink">
        <div class="stat-icon pink"><i data-lucide="utensils"></i></div>
        <div>
            <div class="stat-val">{{ $totalMenu }}</div>
            <div class="stat-lbl">Total Menu</div>
            <div class="stat-trend up">🍔 Menu tersedia</div>
        </div>
    </div>
</div>

{{-- Content Grid --}}
<div class="content-grid">
    <div class="box">
        <div class="box-header">
            <div>
                <h3>Grafik Penjualan 7 Hari Terakhir</h3>
            </div>
            <div style="font-size:var(--text-sm);color:var(--text-muted);font-weight:600;">
                Hari ini: Rp {{ number_format($totalPenjualan,0,',','.') }}
            </div>
        </div>
        <div class="box-body">
            <div class="chart-wrap">
                <canvas id="chartPenjualan"></canvas>
            </div>
        </div>
    </div>

    <div class="box">
        {{-- ✅ HEADER MENU TERLARIS — DIUBAH DI SINI --}}
        <div class="box-header">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:34px; height:34px; border-radius:10px; background:linear-gradient(135deg,#f59e0b,#f97316); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i data-lucide="trophy" style="width:17px; height:17px; stroke:white; stroke-width:2.2;"></i>
                </div>
                <div>
                    <h3 style="font-size:var(--text-lg); font-weight:700; color:var(--text-dark); line-height:1.2;">Menu Terlaris</h3>
                    <p style="font-size:var(--text-xs); color:var(--text-muted); font-weight:500; margin-top:1px;">Minggu ini</p>
                </div>
            </div>
        </div>
        <div class="box-body">
            @if($menuTerlaris->isEmpty())
                <p style="color:var(--text-muted);font-size:var(--text-base);text-align:center;padding:20px 0;">Belum ada data minggu ini</p>
            @else
                <div class="menu-rank">
                    @foreach($menuTerlaris as $i => $item)
                    <div class="menu-rank-item">
                        <div class="rank-num {{ $i===0?'r1':($i===1?'r2':($i===2?'r3':'rn')) }}">{{ $i+1 }}</div>
                        <img src="{{ !empty($item->menu?->image) ? asset('storage/'.$item->menu->image) : 'https://via.placeholder.com/60' }}" alt="{{ $item->menu->name ?? 'Menu' }}" class="rank-img">
                        <div class="rank-info">
                            <div class="rank-name">{{ $item->menu->name ?? 'Menu Tidak Ditemukan' }}</div>
                            <div class="rank-sub">Terlaris minggu ini</div>
                        </div>
                        <div class="rank-qty">{{ $item->total_qty }}x</div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('chartPenjualan').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Penjualan',
            data: {!! json_encode($chartData) !!},
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124,58,237,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#7c3aed',
            pointRadius: 5,
            pointHoverRadius: 7,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: {
                    color: '#94a3b8', font: { size: 11, family: 'Inter' },
                    callback: v => 'Rp ' + (v/1000).toFixed(0) + 'rb'
                }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#94a3b8', font: { size: 11, family: 'Inter' } }
            }
        }
    }
});
</script>
<script>
$(document).ready(function () {
    $('#recentOrdersTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        searching: false,
        ordering: false,
        language: {
            info:     "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: { previous: "Previous", next: "Next" },
            emptyTable: "Belum ada pesanan hari ini"
        }
    });
});
</script>
@endpush