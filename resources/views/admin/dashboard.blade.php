{{-- ╔══════════════════════════════════════════╗
     ║   ADMIN DASHBOARD — FULL BLADE           ║
     ╚══════════════════════════════════════════╝ --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- ══════════════════════════════════════
         DataTables (jQuery + DataTables)
    ══════════════════════════════════════ --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; color: #334155; }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed; top: 0; left: 0; right: 0; height: 68px;
            background: rgba(255,255,255,0.97); backdrop-filter: blur(20px);
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px; z-index: 1000;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .menu-btn {
            width: 38px; height: 38px; border-radius: 10px; border: none;
            background: #f1f5f9; color: #1e293b; cursor: pointer;
            display: flex; align-items: center; justify-content: center; transition: all 0.2s;
        }
        .brand { font-size: 15px; font-weight: 800; color: #0f172a; letter-spacing: -0.3px; }
        .brand span { color: #7c3aed; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }

        /* Clock */
        .live-clock {
            display: flex; align-items: center; gap: 7px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            padding: 6px 12px; border-radius: 10px; font-size: 13px;
            font-weight: 700; color: #0f172a; letter-spacing: 0.5px;
        }

        /* Profile dropdown */
        .profile-wrap { position: relative; }
        .user-btn {
            display: flex; align-items: center; gap: 9px;
            padding: 5px 10px 5px 5px; border: 1px solid #c7d2e0;
            border-radius: 12px; background: #f8fafc; cursor: pointer;
            transition: all 0.18s; user-select: none;
        }
        .user-btn.open { border-color: #7c3aed; background: #f5f3ff; box-shadow: 0 0 0 3px rgba(124,58,237,.1); }
        .user-avatar {
            width: 30px; height: 30px; border-radius: 9px;
            background: linear-gradient(135deg, #818cf8, #4f46e5);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 12px; font-weight: 800; flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 700; color: #0f172a; }
        .user-role { font-size: 11px; color: #94a3b8; }
        .chevron { width: 14px; height: 14px; stroke: #94a3b8; fill: none; stroke-width: 2.5; transition: transform .2s; }
        .user-btn.open .chevron { transform: rotate(180deg); }

        .dropdown {
            position: absolute; top: calc(100% + 8px); right: 0; width: 230px;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
            box-shadow: 0 16px 48px rgba(0,0,0,.13); overflow: hidden;
            opacity: 0; transform: translateY(-8px) scale(.97);
            pointer-events: none; transition: opacity .18s, transform .18s; z-index: 200;
        }
        .dropdown.show { opacity: 1; transform: none; pointer-events: all; }
        .dp-head {
            padding: 14px 16px; background: linear-gradient(135deg,#eef2ff,#f5f3ff);
            border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 11px;
        }
        .dp-av {
            width: 38px; height: 38px; border-radius: 11px;
            background: linear-gradient(135deg,#818cf8,#4f46e5);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 14px; font-weight: 800;
            box-shadow: 0 2px 8px rgba(79,70,229,.28);
        }
        .dp-nm { font-size: 13px; font-weight: 800; color: #0f172a; }
        .dp-rl { font-size: 11px; color: #64748b; margin-top: 1px; }
        .dp-body { padding: 7px; }
        .dp-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 11px; border-radius: 9px; text-decoration: none;
            font-size: 13px; font-weight: 600; color: #0f172a;
            transition: all .15s; border: none; background: none; width: 100%;
            cursor: pointer; font-family: inherit; text-align: left;
        }
        .dp-item:hover { background: #f8fafc; }
        .dp-ico {
            width: 30px; height: 30px; border-radius: 8px; background: #f1f5f9;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .dp-ico svg { width: 15px; height: 15px; stroke: #64748b; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .dp-divider { height: 1px; background: #f1f5f9; margin: 5px 7px; }
        .dp-item.danger { color: #dc2626; }
        .dp-item.danger:hover { background: #fef2f2; }
        .dp-item.danger .dp-ico { background: #fef2f2; }
        .dp-item.danger .dp-ico svg { stroke: #dc2626; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 240px; height: 100vh; position: fixed;
            background: linear-gradient(180deg, #0f172a, #1e1b4b);
            padding: 30px; padding-top: 100px; color: white;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999; box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            display: flex; flex-direction: column; gap: 4px;
        }
        .sidebar.show { transform: translateX(0); }

        .menu-section {
            font-size: 11px; letter-spacing: 1px; color: #a78bfa;
            margin: 16px 10px 6px; opacity: 0.7; font-weight: 700;
        }

        .sidebar a {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 12px; text-decoration: none;
            color: #94a3b8; font-weight: 500; font-size: 14px;
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
        .main { padding: 92px 28px 36px; }

        /* ═══════════════════════════════════════════
           DASHBOARD SPECIFIC
        ═══════════════════════════════════════════ */
        .page-title h1 { font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.4px; }
        .page-title p { font-size: 14px; color: #64748b; margin-top: 3px; }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px; margin-top: 24px;
        }
        .stat-card {
            background: white; border-radius: 18px; padding: 20px 22px;
            border: 1px solid #f1f5f9; box-shadow: 0 2px 10px rgba(0,0,0,.05);
            display: flex; align-items: center; gap: 16px;
            position: relative; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .stat-card::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px; border-radius: 18px 18px 0 0;
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
            width: 50px; height: 50px; border-radius: 14px; flex-shrink: 0;
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

        .stat-val  { font-size: 22px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }
        .stat-lbl  { font-size: 12px; color: #64748b; font-weight: 500; margin-top: 2px; }
        .stat-trend { font-size: 11px; font-weight: 700; margin-top: 4px; }
        .stat-trend.up      { color: #22c55e; }
        .stat-trend.neutral { color: #94a3b8; }

        /* Content Grid */
        .content-grid {
            display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 20px;
        }
        .box {
            background: white; border-radius: 18px; border: 1px solid #f1f5f9;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }
        .box-header {
            padding: 18px 22px; border-bottom: 1px solid #f8fafc;
            display: flex; align-items: center; justify-content: space-between;
        }
        .box-header h3 { font-size: 15px; font-weight: 700; color: #0f172a; }
        .box-body { padding: 18px 22px; }

        /* Chart */
        .chart-wrap { position: relative; height: 220px; }

        /* ════════════════════════════
           TABLE PESANAN TERBARU
        ════════════════════════════ */
        .rtable { width: 100%; border-collapse: collapse; }
        .rtable thead th {
            background: #f8fafc; padding: 11px 16px; font-size: 11px;
            font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px;
            color: #64748b; text-align: left; border-bottom: 1px solid #f1f5f9;
        }
        .rtable tbody tr { border-bottom: 1px solid #f8fafc; transition: background .15s; }
        .rtable tbody tr:hover { background: #fafbff; }
        .rtable td { padding: 13px 16px; font-size: 13.5px; color: #334155; vertical-align: middle; }

        /* Status Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700;
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

        /* ════════════════════════════
           DATATABLES CUSTOM STYLE
        ════════════════════════════ */
        .dataTables_wrapper {
            padding: 0 0 18px;
        }
        /* Search & Length — hidden karena kita tidak pakai */
        .dataTables_filter,
        .dataTables_length { display: none !important; }

        .dataTables_info {
            font-size: 13px !important;
            color: #64748b !important;
            padding: 14px 22px 0 !important;
            font-weight: 500;
        }
        .dataTables_paginate {
            padding: 12px 22px 0 !important;
            display: flex !important;
            align-items: center;
            gap: 4px;
        }
        .paginate_button {
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
            padding: 6px 13px !important;
            margin: 0 2px !important;
            background: white !important;
            color: #475569 !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all .15s !important;
        }
        .paginate_button:hover:not(.current):not(.disabled) {
            background: #eff6ff !important;
            color: #2563eb !important;
            border-color: #bfdbfe !important;
        }
        .paginate_button.current,
        .paginate_button.current:hover {
            background: linear-gradient(135deg, #7c3aed, #6d28d9) !important;
            color: white !important;
            border-color: #7c3aed !important;
            box-shadow: 0 2px 8px rgba(124,58,237,0.3) !important;
        }
        .paginate_button.disabled,
        .paginate_button.disabled:hover {
            color: #cbd5e1 !important;
            cursor: default !important;
        }
        .dataTables_scrollBody {
            border-bottom: none !important;
        }
        /* Scrollbar halaman order */
        .dataTables_scrollBody::-webkit-scrollbar { height: 5px; }
        .dataTables_scrollBody::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 99px; }
        .dataTables_scrollBody::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        /* ════════════════════════════
           MENU TERLARIS
        ════════════════════════════ */
        .menu-rank { display: flex; flex-direction: column; gap: 12px; }
        .menu-rank-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px; border-radius: 14px; background: #f8fafc;
            transition: all .2s; border: 1px solid #f1f5f9;
        }
        .menu-rank-item:hover {
            background: #fff; transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0,0,0,.06);
        }
        .rank-num {
            width: 28px; height: 28px; border-radius: 9px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 800; color: white;
        }
        .rank-num.r1 { background: linear-gradient(135deg, #f59e0b, #f97316); }
        .rank-num.r2 { background: linear-gradient(135deg, #94a3b8, #64748b); }
        .rank-num.r3 { background: linear-gradient(135deg, #b45309, #92400e); }
        .rank-num.rn { background: #e2e8f0; color: #64748b; }
        .rank-img {
            width: 50px; height: 50px; object-fit: cover; border-radius: 12px;
            flex-shrink: 0; border: 1px solid #f1f5f9;
        }
        .rank-info { flex: 1; min-width: 0; }
        .rank-name { font-size: 13px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .rank-sub  { font-size: 11px; color: #94a3b8; margin-top: 3px; }
        .rank-qty  { font-size: 14px; font-weight: 800; color: #7c3aed; flex-shrink: 0; }

        /* Responsive */
        @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 780px) {
            .content-grid { grid-template-columns: 1fr; }
            .main { padding: 88px 16px 30px; }
        }
        @media (max-width: 540px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════
     TOPBAR
══════════════════════════════════════════ --}}
<div class="topbar">
    <div class="topbar-left">
        <button class="menu-btn" onclick="toggleSidebar()">
            <i data-lucide="menu" style="width:20px;height:20px;"></i>
        </button>
    </div>

    <div class="topbar-right">
        {{-- Clock --}}
        <div class="live-clock">
            <i data-lucide="clock" style="width:14px;height:14px;color:#7c3aed;"></i>
            <span id="clock">--:--:--</span>
        </div>

        {{-- Profile --}}
        <div class="profile-wrap">
            <div class="user-btn" id="profileBtn" onclick="toggleDropdown()">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="dropdown" id="dropdownMenu">
                <div class="dp-head">
                    <div class="dp-av">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
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

{{-- ══════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════ --}}
<div class="sidebar" id="sidebar">

    <div class="menu-section">MAIN</div>

    <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i data-lucide="layout-dashboard"></i> Dashboard
    </a>

    {{-- ★ MENU ORDER BARU — tepat di bawah Dashboard --}}
    <a href="/admin/order" class="{{ request()->is('admin/order*') ? 'active' : '' }}">
        <i data-lucide="clipboard-list"></i> Order
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

{{-- Overlay --}}
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

{{-- ══════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════ --}}
<div class="main">

    {{-- Page Title --}}
    <div class="page-title">
        <h1>Selamat datang, {{ auth()->user()->name }}! 👋</h1>
        <p>Berikut ringkasan aktivitas cafe hari ini, {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- ══════════════════
         STATS GRID
    ══════════════════ --}}
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

    {{-- ══════════════════
         CONTENT GRID
    ══════════════════ --}}
    <div class="content-grid">

        {{-- Grafik Penjualan --}}
        <div class="box">
            <div class="box-header">
                <div>
                    <h3>Grafik Penjualan 7 Hari Terakhir</h3>
                </div>
                <div style="font-size:12px;color:#94a3b8;font-weight:600;">
                    Hari ini: Rp {{ number_format($totalPenjualan,0,',','.') }}
                </div>
            </div>
            <div class="box-body">
                <div class="chart-wrap">
                    <canvas id="chartPenjualan"></canvas>
                </div>
            </div>
        </div>

        {{-- Menu Terlaris --}}
        <div class="box">
            <div class="box-header">
                <h3>🏆 Menu Terlaris</h3>
            </div>
            <div class="box-body">
                @if($menuTerlaris->isEmpty())
                    <p style="color:#94a3b8;font-size:13px;text-align:center;padding:20px 0;">
                        Belum ada data hari ini
                    </p>
                @else
                    <div class="menu-rank">
                        @foreach($menuTerlaris as $i => $item)
                        <div class="menu-rank-item">
                            <div class="rank-num {{ $i===0?'r1':($i===1?'r2':($i===2?'r3':'rn')) }}">
                                {{ $i+1 }}
                            </div>
                            <img
                                src="{{ !empty($item->menu?->image) ? asset('storage/'.$item->menu->image) : 'https://via.placeholder.com/60' }}"
                                alt="{{ $item->menu->name ?? 'Menu' }}"
                                class="rank-img"
                            >
                            <div class="rank-info">
                                <div class="rank-name">{{ $item->menu->name ?? 'Menu Tidak Ditemukan' }}</div>
                                <div class="rank-sub">Menu paling sering dipesan</div>
                            </div>
                            <div class="rank-qty">{{ $item->total_qty }}x</div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════
         PESANAN TERBARU — DataTables
    ════════════════════════════════════════ --}}
    <div class="box" style="margin-top:20px;">
        <div class="box-header">
            <h3>Pesanan Terbaru</h3>
            <a href="/admin/order"
               style="font-size:12px;color:#7c3aed;font-weight:700;text-decoration:none;">
                Lihat semua →
            </a>
        </div>

        {{-- Tabel pakai overflow-x:auto agar scroll horizontal berfungsi --}}
        <div style="overflow-x:auto;">
            <table class="rtable" id="recentOrdersTable" style="min-width:700px;">
                <thead>
                    <tr>
                        <th style="padding-left:22px;">ID Order</th>
                        <th>Meja</th>
                        <th>Item</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>

@foreach($pesananTerbaru as $order)

<tr>

    <td style="padding-left:22px;">
        <span style="
            font-weight:800;
            color:#7c3aed;
            font-size:13px;
        ">
            #{{ str_pad($order->id,3,'0',STR_PAD_LEFT) }}
        </span>
    </td>

    <td>
        <span style="
            background:#f1f5f9;
            padding:4px 10px;
            border-radius:8px;
            font-size:12px;
            font-weight:700;
            color:#475569;
        ">
            {{ $order->table_number
                ? 'Meja '.$order->table_number
                : 'Take Away'
            }}
        </span>
    </td>

    <td>

        @foreach($order->items->take(2) as $item)

            <span style="font-size:13px;">

                {{ $item->qty }}x
                {{ $item->menu->name ?? '-' }}

                @if(!$loop->last)
                    ,
                @endif

            </span>

        @endforeach

        @if($order->items->count() > 2)

            <span style="
                color:#94a3b8;
                font-size:12px;
            ">
                +{{ $order->items->count()-2 }} lagi
            </span>

        @endif

    </td>

    <td style="
        font-size:12px;
        color:#64748b;
        text-transform:capitalize;
    ">
        {{ $order->payment_method ?? 'Tunai' }}
    </td>

    <td>

        @php

            $sm = [

                'pending'   => ['Pending',  'pending'],

                'process'   => ['Diproses', 'proses'],

                'done'      => ['Selesai',  'selesai'],

                'delivered' => ['Diantar',  'diantar'],

            ];

            $s = $sm[$order->status]
                ?? [$order->status, 'pending'];

        @endphp

        <span class="badge {{ $s[1] }}">
            <span class="badge-dot"></span>
            {{ $s[0] }}
        </span>

    </td>

    <td style="
        font-size:12px;
        color:#94a3b8;
        white-space:nowrap;
    ">
        {{ $order->created_at->format('H:i') }}
    </td>

</tr>

@endforeach

</tbody>      

            </table>
        </div>
    </div>

</div>{{-- end .main --}}


{{-- ══════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════ --}}
<script>
// ── Clock ──
function updateClock() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2,'0');
    const m = String(now.getMinutes()).padStart(2,'0');
    const s = String(now.getSeconds()).padStart(2,'0');
    document.getElementById('clock').textContent = `${h}:${m}:${s}`;
}
updateClock();
setInterval(updateClock, 1000);

// ── Sidebar Toggle ──
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('overlay').classList.toggle('show');
}

// ── Profile Dropdown ──
function toggleDropdown() {
    const btn  = document.getElementById('profileBtn');
    const menu = document.getElementById('dropdownMenu');
    btn.classList.toggle('open');
    menu.classList.toggle('show');
}
document.addEventListener('click', function(e) {
    const wrap = document.querySelector('.profile-wrap');
    if (!wrap.contains(e.target)) {
        document.getElementById('profileBtn').classList.remove('open');
        document.getElementById('dropdownMenu').classList.remove('show');
    }
});

// ── Lucide Icons ──
lucide.createIcons();
</script>

{{-- ── Chart Penjualan ── --}}
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
                    color: '#94a3b8', font: { size: 11 },
                    callback: v => 'Rp ' + (v/1000).toFixed(0) + 'rb'
                }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#94a3b8', font: { size: 11 } }
            }
        }
    }
});
</script>

{{-- ════════════════════════════════════════════
     DataTables INIT — Pesanan Terbaru
════════════════════════════════════════════ --}}
<script>
$(document).ready(function () {
    $('#recentOrdersTable').DataTable({
        scrollX: true,          // scroll horizontal aktif
        pageLength: 10,         // 10 baris per halaman
        lengthChange: false,    // sembunyikan dropdown "show N entries"
        searching: false,       // sembunyikan kotak search (sudah ada di laporan)
        ordering: false,        // urutan sudah dari server (latest)
        language: {
            info:     "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                previous: "Previous",
                next:     "Next"
            },
            emptyTable: "Belum ada pesanan hari ini"
        }
    });
});
</script>

</body>
</html>