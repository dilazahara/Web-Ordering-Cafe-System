<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
/* ═══════════════════════════════════════════
   SHARED BASE — identik di semua halaman admin
═══════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: #1e293b; }

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
    background: none; cursor: pointer; display: flex; align-items: center;
    justify-content: center; color: #475569; transition: all 0.2s;
}
.menu-btn:hover { background: #f1f5f9; color: #1e293b; }
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
.live-clock i { width: 14px; height: 14px; color: #7c3aed; }

/* Profile dropdown */
.profile-wrap { position: relative; }
.user-btn {
    display: flex; align-items: center; gap: 9px;
    padding: 5px 10px 5px 5px; border: 1px solid #e2e8f0;
    border-radius: 12px; background: #fff; cursor: pointer;
    transition: all 0.18s; user-select: none;
}
.user-btn:hover { background: #f8fafc; border-color: #c7d2e0; }
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
    box-shadow: 0 16px 48px rgba(0,0,0,.13), 0 0 0 1px rgba(0,0,0,.03);
    overflow: hidden; opacity: 0; transform: translateY(-8px) scale(.97);
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
    font-size: 13px; font-weight: 600; color: #475569;
    transition: all .15s; border: none; background: none; width: 100%;
    cursor: pointer; font-family: inherit; text-align: left;
}
.dp-item:hover { background: #f8fafc; color: #0f172a; }
.dp-ico {
    width: 30px; height: 30px; border-radius: 8px; background: #f1f5f9;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.dp-ico svg { width: 15px; height: 15px; stroke: #64748b; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.dp-divider { height: 1px; background: #f1f5f9; margin: 5px 7px; }
.dp-item.danger { color: #991b1b; }
.dp-item.danger:hover { background: #fef2f2; color: #dc2626; }
.dp-item.danger .dp-ico { background: #fef2f2; }
.dp-item.danger .dp-ico svg { stroke: #dc2626; }

/* ── SIDEBAR ── */
.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    background: linear-gradient(180deg, #0f172a, #1e1b4b);
    padding: 30px;
    padding-top: 100px;
    color: white;
    overflow-y: auto;
    transform: translateX(-100%);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 999;
    box-shadow: 4px 0 20px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sidebar.show {
    transform: translateX(0);
}

/* MENU SECTION */
.menu-section {
    font-size: 11px;
    letter-spacing: 1px;
    color: #a78bfa;
    margin: 18px 10px 8px;
    opacity: 0.7;
}

/* SIDEBAR MENU */
.sidebar a,
.menu-parent {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 14px;
    border-radius: 12px;
    text-decoration: none;
    color: #94a3b8;
    font-weight: 400;
    font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar i {
    width: 20px;
    height: 20px;
    stroke-width: 2.5;
    color: #c4b5fd;
}

.menu-parent {
    cursor: pointer;
}

.menu-parent:hover,
.sidebar a:hover {
    background: rgba(255,255,255,0.06);
    color: white;
    transform: translateX(4px);
}

.sidebar a.active {
    background: rgba(139, 92, 246, 0.25);
    color: #c4b5fd;
    box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4);
}


/* ── OVERLAY ── */
.overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.35); z-index: 998; backdrop-filter: blur(2px);
}
.overlay.show { display: block; }

/* ── MAIN ── */
.main { padding: 92px 28px 36px; }

/* ═══════════════════════════════════════════
   DASHBOARD-SPECIFIC
═══════════════════════════════════════════ */
.page-title h1 { font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.4px; }
.page-title p { font-size: 14px; color: #64748b; margin-top: 3px; }

/* Stats Row */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
    margin-top: 24px;
}

.stat-card {
    background: white;
    border-radius: 18px;
    padding: 20px 22px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
}

.stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: 18px 18px 0 0;
}

/* Top Border Colors */
.stat-card.orange::after {
    background: linear-gradient(90deg, #f97316, #fb923c);
}

.stat-card.blue::after {
    background: linear-gradient(90deg, #3b82f6, #60a5fa);
}

.stat-card.cyan::after {
    background: linear-gradient(90deg, #06b6d4, #22d3ee);
}

.stat-card.green::after {
    background: linear-gradient(90deg, #22c55e, #4ade80);
}

.stat-card.purple::after {
    background: linear-gradient(90deg, #7c3aed, #a855f7);
}

.stat-card.red::after {
    background: linear-gradient(90deg, #ef4444, #f87171);
}

.stat-card.dark::after {
    background: linear-gradient(90deg, #334155, #475569);
}

.stat-card.pink::after {
    background: linear-gradient(90deg, #ec4899, #f472b6);
}

/* Icons */
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    width: 22px;
    height: 22px;
}

.stat-icon.orange {
    background: #fff7ed;
    color: #f97316;
}

.stat-icon.blue {
    background: #eff6ff;
    color: #3b82f6;
}

.stat-icon.cyan {
    background: #ecfeff;
    color: #06b6d4;
}

.stat-icon.green {
    background: #f0fdf4;
    color: #22c55e;
}

.stat-icon.purple {
    background: #f3e8ff;
    color: #7c3aed;
}

.stat-icon.red {
    background: #fef2f2;
    color: #ef4444;
}

.stat-icon.dark {
    background: #e2e8f0;
    color: #334155;
}

.stat-icon.pink {
    background: #fdf2f8;
    color: #ec4899;
}

/* Text */
.stat-val {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.5px;
}

.stat-lbl {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
    margin-top: 2px;
}

.stat-trend {
    font-size: 11px;
    font-weight: 700;
    margin-top: 4px;
}

.stat-trend.up {
    color: #22c55e;
}

.stat-trend.neutral {
    color: #94a3b8;
}

/* Content grid */
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

/* Table recent orders */
.rtable { width: 100%; border-collapse: collapse; }
.rtable thead th {
    background: #f8fafc; padding: 11px 16px; font-size: 11px;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px;
    color: #64748b; text-align: left; border-bottom: 1px solid #f1f5f9;
}
.rtable tbody tr { border-bottom: 1px solid #f8fafc; transition: background .15s; }
.rtable tbody tr:last-child { border-bottom: none; }
.rtable tbody tr:hover { background: #fafbff; }
.rtable td { padding: 13px 16px; font-size: 13.5px; color: #334155; vertical-align: middle; }

/* Status badges */
.badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700;
}
.badge-dot { width: 6px; height: 6px; border-radius: 50%; }
.badge.pending { background: #fef3c7; color: #92400e; }
.badge.pending .badge-dot { background: #f59e0b; }
.badge.proses  { background: #dbeafe; color: #1e40af; }
.badge.proses  .badge-dot { background: #3b82f6; }
.badge.selesai { background: #dcfce7; color: #15803d; }
.badge.selesai .badge-dot { background: #22c55e; }
.badge.diantar { background: #f0fdf4; color: #15803d; }
.badge.diantar .badge-dot { background: #22c55e; }

/* =======================
   TOP MENU LIST
======================= */

.menu-rank{
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.menu-rank-item{
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px;
    border-radius: 16px;
    background: #f8fafc;
    transition: all .2s ease;
    border: 1px solid #f1f5f9;
}

.menu-rank-item:hover{
    background: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,.06);
}

/* Ranking Number */
.rank-num{
    width: 30px;
    height: 30px;
    border-radius: 10px;
    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 12px;
    font-weight: 800;
    color: white;
}

.rank-num.r1{
    background: linear-gradient(135deg, #f59e0b, #f97316);
}

.rank-num.r2{
    background: linear-gradient(135deg, #94a3b8, #64748b);
}

.rank-num.r3{
    background: linear-gradient(135deg, #b45309, #92400e);
}

.rank-num.rn{
    background: #e2e8f0;
    color: #64748b;
}

/* Menu Image */
.rank-img{
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 14px;
    flex-shrink: 0;
    border: 1px solid #f1f5f9;
    background: white;
}

/* Info */
.rank-info{
    flex: 1;
    min-width: 0;
}

/* Menu Name */
.rank-name{
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Subtitle */
.rank-sub{
    font-size: 11px;
    color: #94a3b8;
    margin-top: 4px;
}

/* Qty */
.rank-qty{
    font-size: 14px;
    font-weight: 800;
    color: #7c3aed;
    flex-shrink: 0;
}

@media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 780px) {
    .content-grid { grid-template-columns: 1fr; }
    .main { padding: 88px 16px 30px; }
}
@media (max-width: 540px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
</style>
</head>
<body>

<!-- ══ TOPBAR ══ -->
<div class="topbar">
    <div class="topbar-left">
        <button class="menu-btn" onclick="toggleSidebar()" aria-label="Menu">
            <i data-lucide="menu" style="width:20px;height:20px;"></i>
        </button>
    </div>
    <div class="topbar-right">
        <div class="live-clock">
            <i data-lucide="clock-3"></i>
            <span id="liveClock">00:00:00</span>
        </div>

        <!-- PROFILE DROPDOWN -->
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
                        <div class="dp-ico"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                        Profil Saya
                    </a>
                    <a href="/admin/account/ganti-sandi" class="dp-item">
                        <div class="dp-ico"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
                        Ganti Password
                    </a>
                    <div class="dp-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dp-item danger">
                            <div class="dp-ico"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></div>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══ OVERLAY ══ -->
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<!-- ══ SIDEBAR ══ -->
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

<!-- ══ MAIN ══ -->
<div class="main">

    <div class="page-title">
        <h1>Selamat datang, {{ auth()->user()->name }}! 👋</h1>
        <p>Berikut ringkasan aktivitas cafe hari ini, {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

<!-- Stats -->
<div class="stats-grid">

    <!-- Total Penjualan -->
    <div class="stat-card orange">
        <div class="stat-icon orange">
            <i data-lucide="banknote"></i>
        </div>
        <div>
            <div class="stat-val">
                Rp {{ number_format($totalPenjualan,0,',','.') }}
            </div>
            <div class="stat-lbl">Total Penjualan</div>
            <div class="stat-trend neutral">
                💰 Kumulatif transaksi selesai
            </div>
        </div>
    </div>

    <!-- Total Order -->
    <div class="stat-card blue">
        <div class="stat-icon blue">
            <i data-lucide="shopping-bag"></i>
        </div>
        <div>
            <div class="stat-val">{{ $totalOrder }}</div>
            <div class="stat-lbl">Total Order</div>
            <div class="stat-trend up">
                📦 Semua status
            </div>
        </div>
    </div>

    <!-- Diproses -->
    <div class="stat-card cyan">
        <div class="stat-icon cyan">
            <i data-lucide="chef-hat"></i>
        </div>
        <div>
            <div class="stat-val">{{ $totalDiproses }}</div>
            <div class="stat-lbl">Sedang Diproses</div>
            <div class="stat-trend neutral">
                🔥 Di dapur sekarang
            </div>
        </div>
    </div>

    <!-- Selesai -->
    <div class="stat-card green">
        <div class="stat-icon green">
            <i data-lucide="check-circle-2"></i>
        </div>
        <div>
            <div class="stat-val">{{ $totalSelesai }}</div>
            <div class="stat-lbl">Order Selesai</div>
            <div class="stat-trend up">
                ✅ Berhasil diantar
            </div>
        </div>
    </div>

    <!-- Total Meja -->
    <div class="stat-card purple">
        <div class="stat-icon purple">
            <i data-lucide="armchair"></i>
        </div>
        <div>
            <div class="stat-val">{{ $totalMeja }}</div>
            <div class="stat-lbl">Total Meja</div>
            <div class="stat-trend neutral">
                🪑 Meja tersedia
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="stat-card red">
        <div class="stat-icon red">
            <i data-lucide="receipt"></i>
        </div>
        <div>
            <div class="stat-val">{{ $totalTransaksi }}</div>
            <div class="stat-lbl">Total Transaksi</div>
            <div class="stat-trend up">
                💳 Semua transaksi
            </div>
        </div>
    </div>

    <!-- Total User -->
    <div class="stat-card dark">
        <div class="stat-icon dark">
            <i data-lucide="users"></i>
        </div>
        <div>
            <div class="stat-val">{{ $totalUser }}</div>
            <div class="stat-lbl">Total User</div>
            <div class="stat-trend neutral">
                👤 Pengguna sistem
            </div>
        </div>
    </div>

    <!-- Total Menu -->
    <div class="stat-card pink">
        <div class="stat-icon pink">
            <i data-lucide="utensils"></i>
        </div>
        <div>
            <div class="stat-val">{{ $totalMenu }}</div>
            <div class="stat-lbl">Total Menu</div>
            <div class="stat-trend up">
                🍔 Menu tersedia
            </div>
        </div>
    </div>

</div>

    <!-- Content Grid -->
    <div class="content-grid">

        <!-- Chart Penjualan -->
        <div class="box">
            <div class="box-header">
                <div>
                    <h3>Grafik Penjualan 7 Hari Terakhir</h3>
                </div>
                <div style="font-size:12px;color:#94a3b8;font-weight:600;">Hari ini: Rp {{ number_format($totalPenjualan,0,',','.') }}</div>
            </div>
            <div class="box-body">
                <div class="chart-wrap">
                    <canvas id="chartPenjualan"></canvas>
                </div>
            </div>
        </div>

        <!-- Menu Terlaris -->
        <div class="box">
            <div class="box-header">
                <h3>🏆 Menu Terlaris</h3>
            </div>
            <div class="box-body">
                @if($menuTerlaris->isEmpty())
                <p style="color:#94a3b8;font-size:13px;text-align:center;padding:20px 0;">Belum ada data hari ini</p>
                @else
                <div class="menu-rank">

               @foreach($menuTerlaris as $i => $item)
<div class="menu-rank-item">

    <!-- Ranking -->
    <div class="rank-num {{ $i===0?'r1':($i===1?'r2':($i===2?'r3':'rn')) }}">
        {{ $i+1 }}
    </div>

    <!-- Foto -->
    <img
        src="{{ !empty($item->menu?->image)
            ? asset('storage/'.$item->menu->image)
            : 'https://via.placeholder.com/60' }}"
        alt="{{ $item->menu->name ?? 'Menu' }}"
        class="rank-img"
    >

    <!-- Info -->
    <div class="rank-info">

        <div class="rank-name">
            {{ $item->menu->name ?? 'Menu Tidak Ditemukan' }}
        </div>

        <div class="rank-sub">
            Menu paling sering dipesan
        </div>

    </div>

    <!-- Qty -->
    <div class="rank-qty">
        {{ $item->total_qty }}x
    </div>

</div>
@endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="box" style="margin-top:20px;">
        <div class="box-header">
            <h3>Pesanan Terbaru</h3>
            <a href="/admin/laporan" style="font-size:12px;color:#7c3aed;font-weight:700;text-decoration:none;">Lihat semua →</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="rtable">
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
                    @forelse($pesananTerbaru as $order)
                    <tr>
                        <td style="padding-left:22px;">
                            <span style="font-weight:800;color:#7c3aed;font-size:13px;">#{{ str_pad($order->id,3,'0',STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <span style="background:#f1f5f9;padding:4px 10px;border-radius:8px;font-size:12px;font-weight:700;color:#475569;">
                                Meja {{ $order->table_number }}
                            </span>
                        </td>
                        <td>
                            @foreach($order->items->take(2) as $item)
                                <span style="font-size:13px;">{{ $item->qty }}x {{ $item->menu->name ?? '-' }}{{ !$loop->last ? ', ' : '' }}</span>
                            @endforeach
                            @if($order->items->count() > 2)
                                <span style="color:#94a3b8;font-size:12px;">+{{ $order->items->count()-2 }} lagi</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:#64748b;">{{ $order->payment_method ?? 'Tunai' }}</td>
                        <td>
                            @php
                                $sm = ['pending'=>['Pending','pending'],'process'=>['Diproses','proses'],'done'=>['Selesai','selesai'],'delivered'=>['Diantar','diantar']];
                                $s = $sm[$order->status] ?? [$order->status,'pending'];
                            @endphp
                            <span class="badge {{ $s[1] }}"><span class="badge-dot"></span>{{ $s[0] }}</span>
                        </td>
                        <td style="font-size:12px;color:#94a3b8;">{{ $order->created_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;">
                            <i data-lucide="inbox" style="width:36px;height:36px;display:block;margin:0 auto 8px;color:#e2e8f0;"></i>
                            Belum ada pesanan hari ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /main -->

<script>
lucide.createIcons();

/* Clock */
function updateClock() {
    const n = new Date();
    document.getElementById('liveClock').textContent =
        [n.getHours(),n.getMinutes(),n.getSeconds()].map(v=>String(v).padStart(2,'0')).join(':');
}
setInterval(updateClock, 1000); updateClock();

/* Dropdown */
function toggleDropdown() {
    const btn = document.getElementById('profileBtn');
    const panel = document.getElementById('dropdownMenu');
    const open = panel.classList.contains('show');
    panel.classList.toggle('show', !open);
    btn.classList.toggle('open', !open);
}
document.addEventListener('click', e => {
    const w = document.querySelector('.profile-wrap');
    if (w && !w.contains(e.target)) {
        document.getElementById('dropdownMenu').classList.remove('show');
        document.getElementById('profileBtn').classList.remove('open');
    }
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.getElementById('dropdownMenu').classList.remove('show');
        document.getElementById('profileBtn').classList.remove('open');
    }
});

/* Sidebar */
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('overlay').classList.toggle('show');
}

/* Chart */
const labels = @json($grafikLabels ?? []);
const data = @json($grafikData ?? []);
new Chart(document.getElementById('chartPenjualan'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Penjualan (Rp)',
            data,
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124,58,237,.10)',
            fill: true,
            tension: 0.45,
            borderWidth: 2.5,
            pointBackgroundColor: '#7c3aed',
            pointRadius: 4,
            pointHoverRadius: 7,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: {
            callbacks: { label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id') }
        }},
        scales: {
            x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94a3b8' }},
            y: { grid: { color: '#f1f5f9' }, ticks: {
                font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94a3b8',
                callback: v => 'Rp ' + (v >= 1000 ? (v/1000) + 'k' : v)
            }}
        }
    }
});
</script>
</body>
</html>