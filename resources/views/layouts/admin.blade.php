<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- Google Fonts: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

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

    @stack('styles')
</head>
<body>


@php
    $userAvatar  = auth()->user()->avatar ?? null;
    $avatarUrl   = $userAvatar ? asset('storage/' . $userAvatar) : null;
    $userInitial = strtoupper(substr(auth()->user()->name, 0, 1));
@endphp

<div class="topbar">
    <div class="topbar-left">
        <button class="menu-btn" onclick="toggleSidebar()">
            <i data-lucide="menu" style="width:20px;height:20px;"></i>
        </button>
    </div>

    <div class="topbar-right">
        <div class="live-clock">
            <i data-lucide="clock" style="width:14px;height:14px;color:#7c3aed;"></i>
            <span id="clock">--:--:--</span>
        </div>

        <div class="profile-wrap">
            <div class="user-btn" id="profileBtn" onclick="toggleDropdown()">
                @if($avatarUrl)
                    <div class="user-avatar has-photo">
                        <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}">
                    </div>
                @else
                    <div class="user-avatar">{{ $userInitial }}</div>
                @endif
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>

            <div class="dropdown" id="dropdownMenu">
                <div class="dp-head">
                    @if($avatarUrl)
                        <div class="dp-av has-photo">
                            <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}">
                        </div>
                    @else
                        <div class="dp-av">{{ $userInitial }}</div>
                    @endif
                    <div>
                        <div class="dp-nm">{{ auth()->user()->name }}</div>
                        <div class="dp-rl">{{ ucfirst(auth()->user()->role) }} · Online</div>
                    </div>
                </div>
                <div class="dp-body">
                    <a href="/admin/account/profil" class="dp-item">
                        <div class="dp-ico">
                            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        Profil Saya
                    </a>
                    <a href="/admin/account/ganti-sandi" class="dp-item">
                        <div class="dp-ico">
                            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        Ganti Password
                    </a>
                    <div class="dp-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dp-item danger">
                            <div class="dp-ico">
                                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            </div>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sidebar" id="sidebar">
    <div class="menu-section">MAIN</div>
    <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i data-lucide="layout-dashboard"></i> Dashboard
    </a>

    <div class="menu-section">KATALOG</div>
    <a href="/admin/menu" class="{{ request()->is('admin/menu*') ? 'active' : '' }}">
        <i data-lucide="utensils"></i> Menu
    </a>
    <a href="/admin/kategori" class="{{ request()->is('admin/kategori*') ? 'active' : '' }}">
        <i data-lucide="folder"></i> Kategori
    </a>
    <a href="/admin/addons" class="{{ request()->is('admin/addons*') ? 'active' : '' }}">
        <i data-lucide="plus-circle"></i> Add-ons
    </a>
    <div class="menu-section">OPERASIONAL</div>
    <a href="/admin/meja" class="{{ request()->is('admin/meja*') ? 'active' : '' }}">
        <i data-lucide="armchair"></i> Meja
    </a>
    <a href="/admin/pembayaran" class="{{ request()->is('admin/pembayaran*') ? 'active' : '' }}">
        <i data-lucide="credit-card"></i> Pembayaran
    </a>
    <div class="menu-section">ANALITIK</div>
    <a href="/admin/laporan" class="{{ request()->is('admin/laporan*') ? 'active' : '' }}">
        <i data-lucide="bar-chart-3"></i> Laporan
    </a>
    <div class="menu-section">SYSTEM</div>
    <a href="/admin/user" class="{{ request()->is('admin/user*') ? 'active' : '' }}">
        <i data-lucide="users"></i> User
    </a>
</div>

<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>


<div class="main">
    @yield('content')
</div>

<script>
function updateClock() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2,'0');
    const m = String(now.getMinutes()).padStart(2,'0');
    const s = String(now.getSeconds()).padStart(2,'0');
    document.getElementById('clock').textContent = `${h}:${m}:${s}`;
}
updateClock();
setInterval(updateClock, 1000);

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('overlay').classList.toggle('show');
}
function toggleDropdown() {
    const btn  = document.getElementById('profileBtn');
    const menu = document.getElementById('dropdownMenu');
    btn.classList.toggle('open');
    menu.classList.toggle('show');
}
document.addEventListener('click', function(e) {
    const wrap = document.querySelector('.profile-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('profileBtn').classList.remove('open');
        document.getElementById('dropdownMenu').classList.remove('show');
    }
});
lucide.createIcons();
</script>

@stack('scripts')
</body>
</html>