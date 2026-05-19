<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Kasir — Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); line-height: 1.5; min-height: 100vh; -webkit-font-smoothing: antialiased; }

/* ── HEADER ── */
.header { position: fixed; top: 0; left: 0; right: 0; height: var(--header-h); background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; z-index: 100; box-shadow: var(--shadow-header); }
.logo { display: flex; align-items: center; gap: 10px; }
.logo-mark { width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgb(37 99 235/.35); }
.logo-mark svg { width: 18px; height: 18px; stroke: white; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.logo-text { font-size: 16px; font-weight: 800; letter-spacing: -0.5px; }
.logo-text span { color: var(--accent); }
.header-right { display: flex; align-items: center; gap: 8px; }
.header-clock {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 14px; border-radius: 12px;
  background: var(--surface); border: 1px solid var(--border);
  font-family: 'Inter', sans-serif; box-shadow: var(--shadow-sm);
}
.header-clock svg { width: 16px; height: 16px; stroke: var(--accent); stroke-width: 2.3; fill: none; }
#liveClock { font-size: 13px; font-weight: 700; color: var(--text-primary); letter-spacing: .5px; }
.divider-v { width: 1px; height: 28px; background: var(--border); margin: 0 4px; }

/* ── PROFILE DROPDOWN ── */
.profile-wrap { position: relative; }
.user-btn {
  display: flex; align-items: center; gap: 10px;
  padding: 5px 12px 5px 5px;
  border: 1px solid var(--border); border-radius: 12px;
  background: var(--surface); cursor: pointer;
  transition: all 0.18s; user-select: none;
}
.user-btn:hover { background: var(--surface-2); border-color: var(--border-strong); box-shadow: var(--shadow-sm); }
.user-btn.open { border-color: var(--accent); background: var(--accent-bg); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #818cf8, #4f46e5); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: 700; flex-shrink: 0; overflow: hidden; }
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-info { display: flex; flex-direction: column; }
.user-name { font-size: 13px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
.user-role { font-size: 11px; color: var(--text-muted); font-family: 'Inter', sans-serif; }
.chevron { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; transition: transform .2s; flex-shrink: 0; }
.user-btn.open .chevron { transform: rotate(180deg); }

.dropdown {
  position: absolute; top: calc(100% + 10px); right: 0;
  width: 240px; background: var(--surface);
  border: 1px solid var(--border); border-radius: 16px;
  box-shadow: 0 16px 48px rgb(0 0 0/.14), 0 0 0 1px rgb(0 0 0/.04);
  overflow: hidden;
  opacity: 0; transform: translateY(-8px) scale(.97);
  pointer-events: none; transition: opacity .18s, transform .18s; z-index: 200;
}
.dropdown.show { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }

.dropdown-header {
  padding: 16px;
  background: linear-gradient(135deg, var(--indigo-bg), var(--accent-bg));
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 12px;
}
.dropdown-avatar {
  width: 40px; height: 40px;
  background: linear-gradient(135deg, #818cf8, #4f46e5);
  border-radius: 12px; display: flex; align-items: center; justify-content: center;
  color: white; font-size: 14px; font-weight: 800; flex-shrink: 0;
  box-shadow: 0 2px 8px rgb(79 70 229/.3); overflow: hidden;
}
.dropdown-avatar img { width: 100%; height: 100%; object-fit: cover; }
.dropdown-name { font-size: 13.5px; font-weight: 800; color: var(--text-primary); }
.dropdown-role { font-size: 11.5px; color: var(--text-secondary); font-family: 'Inter', sans-serif; margin-top: 2px; }

.dropdown-body { padding: 8px; }
.dropdown-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px; border-radius: 10px;
  text-decoration: none; cursor: pointer;
  font-size: 13.5px; font-weight: 600; color: var(--text-secondary);
  transition: all .15s; border: none; background: none; width: 100%;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.dropdown-item:hover { background: var(--surface-2); color: var(--text-primary); }
.dropdown-item svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
.dropdown-item .item-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .15s; }
.dropdown-item:hover .item-icon { background: var(--border); }
.dropdown-divider { height: 1px; background: var(--border); margin: 6px 8px; }
.dropdown-item.danger { color: var(--red-text); }
.dropdown-item.danger:hover { background: var(--red-bg); color: var(--red); }
.dropdown-item.danger .item-icon { background: var(--red-bg); }
.dropdown-item.danger:hover .item-icon { background: #fecaca; }

/* ── TOP NAV ── */
.topnav { position: fixed; top: var(--header-h); left: 0; right: 0; height: var(--nav-h); background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); border-bottom: 1px solid var(--border); display: flex; justify-content: center; z-index: 99; }
.nav-container { max-width: 1280px; margin: 0 auto; display: flex; align-items: stretch; padding: 0 8px; }
.nav-link { display: flex; align-items: center; gap: 7px; padding: 0 18px; text-decoration: none; font-size: 13px; font-weight: 600; color: var(--text-secondary); transition: all 0.18s; white-space: nowrap; border-bottom: 2px solid transparent; margin-bottom: -1px; }
.nav-link svg { width: 15px; height: 15px; stroke: currentColor; stroke-width: 2.2; fill: none; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
.nav-link:hover { color: var(--text-primary); }
.nav-link.active { color: var(--accent); border-bottom-color: var(--accent); }

/* ── MAIN ── */
.main { margin-top: var(--total-top); padding: 36px 24px 72px; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 8px; }
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 32px; gap: 16px; }
.page-title { font-size: 23px; font-weight: 800; letter-spacing: -0.5px; }
.page-sub { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-family: 'Inter', sans-serif; }
.poll-live { font-size: 12px; color: var(--green); font-family: 'Inter', sans-serif; font-weight: 600; }

.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px; }
.stat { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 22px; box-shadow: var(--shadow); transition: box-shadow 0.22s, transform 0.22s; position: relative; overflow: hidden; }
.stat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 18px 18px 0 0; }
.stat-blue::before   { background: linear-gradient(90deg, #3b82f6, #6366f1); }
.stat-green::before  { background: linear-gradient(90deg, #10b981, #34d399); }
.stat-amber::before  { background: linear-gradient(90deg, #f59e0b, #f97316); }
.stat-red::before    { background: linear-gradient(90deg, #ef4444, #ec4899); }
.stat:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }
.stat-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px; font-family: 'Inter', sans-serif; }
.stat-value { font-size: 26px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.8px; line-height: 1; }
.stat-foot { margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); font-size: 11.5px; color: var(--text-muted); font-family: 'Inter', sans-serif; }

.section-title { font-size: 15px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 8px; letter-spacing: -0.3px; margin-bottom: 16px; }
.section-title svg { width: 16px; height: 16px; stroke: var(--accent); stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }

.quick-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px; }
.quick-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 24px; text-decoration: none; display: flex; align-items: center; gap: 16px; box-shadow: var(--shadow); transition: all 0.22s; }
.quick-card:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); border-color: #bfcfff; }
.quick-icon { width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.quick-icon svg { width: 22px; height: 22px; stroke-width: 2; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.qi-blue { background: var(--accent-bg); border: 1px solid #bfdbfe; }
.qi-blue svg { stroke: var(--accent); }
.qi-green { background: var(--green-bg); border: 1px solid #a7f3d0; }
.qi-green svg { stroke: var(--green); }
.qi-indigo { background: var(--indigo-bg); border: 1px solid #c7d2fe; }
.qi-indigo svg { stroke: var(--indigo); }
.quick-info h4 { font-size: 14px; font-weight: 800; margin-bottom: 3px; }
.quick-info p { font-size: 12px; color: var(--text-secondary); font-family: 'Inter', sans-serif; }

.table-wrap { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow); }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead { background: var(--surface-2); }
th { padding: 14px 16px; text-align: left; font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; font-family: 'Inter', sans-serif; }
td { padding: 14px 16px; border-top: 1px solid var(--border); color: var(--text-secondary); font-family: 'Inter', sans-serif; }
td.strong { font-weight: 600; color: var(--text-primary); }
tr:hover td { background: var(--surface-2); }
.pill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; font-family: 'Inter', sans-serif; display: inline-flex; align-items: center; }
.pill-green { background: var(--green-bg); color: var(--green-text); border: 1px solid #a7f3d0; }
.pill-amber { background: var(--amber-bg); color: var(--amber-text); border: 1px solid #fde68a; }
.pill-blue  { background: var(--accent-bg); color: var(--accent-text); border: 1px solid #bfdbfe; }
.pill-gray  { background: var(--surface-2); color: var(--text-muted); border: 1px solid var(--border); }

/* ── TOAST NOTIFICATION ── */
#toast-container {
  position: fixed;
  top: calc(var(--total-top) + 16px);
  right: 24px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 10px;
  pointer-events: none;
}
.toast {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  background: #ffffff;
  border: 1px solid var(--border);
  border-left: 4px solid var(--accent);
  border-radius: 14px;
  padding: 14px 16px;
  min-width: 300px;
  max-width: 380px;
  box-shadow: 0 8px 32px rgb(0 0 0/.14), 0 0 0 1px rgb(0 0 0/.04);
  pointer-events: all;
  cursor: pointer;
  opacity: 0;
  transform: translateX(32px);
  transition: opacity .3s ease, transform .3s ease;
}
.toast.show {
  opacity: 1;
  transform: translateX(0);
}
.toast.hide {
  opacity: 0;
  transform: translateX(32px);
}
.toast-icon {
  width: 36px; height: 36px; flex-shrink: 0;
  background: var(--accent-bg);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}
.toast-icon svg {
  width: 18px; height: 18px;
  stroke: var(--accent); stroke-width: 2.2;
  fill: none; stroke-linecap: round; stroke-linejoin: round;
}
.toast-icon.toast-icon-green { background: var(--green-bg); }
.toast-icon.toast-icon-green svg { stroke: var(--green); }
.toast-body { flex: 1; min-width: 0; }
.toast-title { font-size: 13px; font-weight: 800; color: var(--text-primary); margin-bottom: 2px; }
.toast-msg { font-size: 12px; color: var(--text-secondary); font-family: 'Inter', sans-serif; line-height: 1.45; }
.toast-time { font-size: 11px; color: var(--text-muted); font-family: 'Inter', sans-serif; margin-top: 4px; }
.toast-close {
  width: 20px; height: 20px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  border-radius: 6px; color: var(--text-muted);
  transition: background .15s, color .15s;
  cursor: pointer; background: none; border: none; padding: 0;
}
.toast-close:hover { background: var(--surface-2); color: var(--text-primary); }
.toast-close svg { width: 14px; height: 14px; stroke: currentColor; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }

/* ── TOAST PROGRESS BAR ── */
.toast-progress {
  position: absolute;
  bottom: 0; left: 0;
  height: 3px;
  background: var(--accent);
  border-radius: 0 0 0 10px;
  width: 100%;
  transition: width linear;
}
.toast { position: relative; overflow: hidden; }

@media (max-width: 1100px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) { .quick-grid { grid-template-columns: 1fr; } }
@media (max-width: 640px) {
  .main { padding: 24px 16px 48px; }
  .stats-row { grid-template-columns: 1fr 1fr; }
  .nav-link span { display: none; }
  .user-role, .user-info { display: none; }
  #toast-container { right: 12px; left: 12px; }
  .toast { min-width: unset; max-width: 100%; }
}
</style>
</head>
<body>

<!-- TOAST CONTAINER -->
<div id="toast-container"></div>

<header class="header">
  <div class="logo">
    <div class="logo-mark">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
    <div class="logo-text">Kasir<span></span></div>
  </div>
  <div class="header-right">

    {{-- CLOCK --}}
    <div class="header-clock">
      <svg viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
      </svg>
      <span id="liveClock">00:00:00</span>
    </div>

    <div class="divider-v"></div>

    {{-- PROFILE DROPDOWN --}}
    <div class="profile-wrap">

      <div class="user-btn" id="profileBtn" onclick="toggleDropdown()">
        <div class="avatar">
          @if(auth()->user()->avatar)
            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
          @else
            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
          @endif
        </div>
        <div class="user-info">
          <div class="user-name">{{ auth()->user()->name }}</div>
          <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
        </div>
        <svg class="chevron" viewBox="0 0 24 24">
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </div>

      <div class="dropdown" id="profileDropdown">

        <div class="dropdown-header">
          <div class="dropdown-avatar">
            @if(auth()->user()->avatar)
              <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
            @else
              {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            @endif
          </div>
          <div>
            <div class="dropdown-name">{{ auth()->user()->name }}</div>
            <div class="dropdown-role">{{ ucfirst(auth()->user()->role) }} · Online</div>
          </div>
        </div>

        <div class="dropdown-body">

          <a href="/kasir/account/profil" class="dropdown-item">
            <div class="item-icon">
              <svg viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </div>
            Profil Saya
          </a>

          <a href="/kasir/account/ganti-sandi" class="dropdown-item">
            <div class="item-icon">
              <svg viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
            </div>
            Ganti Password
          </a>

          <div class="dropdown-divider"></div>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item danger">
              <div class="item-icon">
                <svg viewBox="0 0 24 24">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                  <polyline points="16 17 21 12 16 7"/>
                  <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
              </div>
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
        <div class="page-title">Selamat datang, {{ auth()->user()->name }} 👋</div>
        <div class="page-sub">{{ now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp; Ringkasan hari ini</div>
      </div>
      <span class="poll-live">🟢 Live update tiap 5 detik</span>
    </div>

    <div class="stats-row">
      <div class="stat stat-blue">
        <div class="stat-label">Pesanan Hari Ini</div>
        <div class="stat-value" id="statTotal">{{ $orders->count() }}</div>
        <div class="stat-foot">Total pesanan masuk</div>
      </div>
      <div class="stat stat-amber">
        <div class="stat-label">Menunggu / Diproses</div>
        <div class="stat-value" id="statPending">{{ $orders->whereIn('status',['pending','process'])->count() }}</div>
        <div class="stat-foot">Belum selesai</div>
      </div>
      <div class="stat stat-green">
        <div class="stat-label">Selesai Hari Ini</div>
        <div class="stat-value" id="statDone">{{ $orders->whereIn('status',['done','delivered'])->count() }}</div>
        <div class="stat-foot">Done + Delivered</div>
      </div>
      <div class="stat stat-red">
        <div class="stat-label">Pendapatan</div>
        <div class="stat-value" id="statRevenue" style="font-size:20px;">
          Rp {{ number_format($orders->whereIn('status',['done','delivered'])->sum('total'),0,',','.') }}
        </div>
        <div class="stat-foot">Dari transaksi selesai</div>
      </div>
    </div>

    <div class="section-title">
      <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
      Akses Cepat
    </div>
    <div class="quick-grid">
      <a href="/kasir/pesanan" class="quick-card">
        <div class="quick-icon qi-blue">
          <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
        </div>
        <div class="quick-info"><h4>Kelola Pesanan</h4><p>Lihat & proses pesanan masuk</p></div>
      </a>
      <a href="/kasir/transaksi" class="quick-card">
        <div class="quick-icon qi-green">
          <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div class="quick-info"><h4>Riwayat Transaksi</h4><p>Lihat semua transaksi hari ini</p></div>
      </a>
      <a href="/kasir/laporan" class="quick-card">
        <div class="quick-icon qi-indigo">
          <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        </div>
        <div class="quick-info"><h4>Laporan</h4><p>Rekap penjualan & download PDF</p></div>
      </a>
    </div>

  </div>
</main>

<script>
/* ────────────────────────────────────────────
   DROPDOWN
──────────────────────────────────────────── */
function toggleDropdown() {
  const btn      = document.getElementById('profileBtn');
  const dropdown = document.getElementById('profileDropdown');
  const isOpen   = dropdown.classList.contains('show');
  dropdown.classList.toggle('show', !isOpen);
  btn.classList.toggle('open', !isOpen);
}

document.addEventListener('click', function(e) {
  const wrap = document.querySelector('.profile-wrap');
  if (wrap && !wrap.contains(e.target)) {
    document.getElementById('profileDropdown').classList.remove('show');
    document.getElementById('profileBtn').classList.remove('open');
  }
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.getElementById('profileDropdown').classList.remove('show');
    document.getElementById('profileBtn').classList.remove('open');
  }
});

/* ────────────────────────────────────────────
   LIVE CLOCK
──────────────────────────────────────────── */
function updateClock() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2, '0');
  const m = String(now.getMinutes()).padStart(2, '0');
  const s = String(now.getSeconds()).padStart(2, '0');
  document.getElementById('liveClock').textContent = `${h}:${m}:${s}`;
}
setInterval(updateClock, 1000);
updateClock();

/* ────────────────────────────────────────────
   TOAST SYSTEM
──────────────────────────────────────────── */
const TOAST_DURATION = 6000; // ms

function showToast({ title, message, type = 'new' }) {
  const container = document.getElementById('toast-container');

  const toast = document.createElement('div');
  toast.className = 'toast';

  const iconColor = type === 'new' ? '' : 'toast-icon-green';
  const iconSvg = type === 'new'
    ? `<svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>`
    : `<svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>`;

  const now = new Date();
  const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

  toast.innerHTML = `
    <div class="toast-icon ${iconColor}">${iconSvg}</div>
    <div class="toast-body">
      <div class="toast-title">${title}</div>
      <div class="toast-msg">${message}</div>
      <div class="toast-time">${timeStr}</div>
    </div>
    <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">
      <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="toast-progress" id="tp-${Date.now()}"></div>
  `;

  container.appendChild(toast);

  // Animate in
  requestAnimationFrame(() => {
    requestAnimationFrame(() => toast.classList.add('show'));
  });

  // Progress bar countdown
  const bar = toast.querySelector('.toast-progress');
  bar.style.transition = `width ${TOAST_DURATION}ms linear`;
  requestAnimationFrame(() => {
    requestAnimationFrame(() => { bar.style.width = '0%'; });
  });

  // Auto dismiss
  const timer = setTimeout(() => dismissToast(toast), TOAST_DURATION);
  toast._dismissTimer = timer;

  // Click toast body to go to pesanan
  toast.addEventListener('click', function(e) {
    if (!e.target.closest('.toast-close')) {
      window.location.href = '/kasir/pesanan';
    }
  });
}

function dismissToast(toast) {
  if (!toast || toast._dismissed) return;
  toast._dismissed = true;
  clearTimeout(toast._dismissTimer);
  toast.classList.remove('show');
  toast.classList.add('hide');
  setTimeout(() => toast.remove(), 350);
}

/* ────────────────────────────────────────────
   BEEP SOUND
──────────────────────────────────────────── */
let notifCtx = null;
function playBeep() {
  try {
    if (!notifCtx) notifCtx = new (window.AudioContext || window.webkitAudioContext)();
    const osc  = notifCtx.createOscillator();
    const gain = notifCtx.createGain();
    osc.connect(gain);
    gain.connect(notifCtx.destination);
    osc.frequency.value = 660;
    gain.gain.setValueAtTime(0.3, notifCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, notifCtx.currentTime + 0.5);
    osc.start();
    osc.stop(notifCtx.currentTime + 0.5);
  } catch(e) {}
}

/* ────────────────────────────────────────────
   HELPERS
──────────────────────────────────────────── */
function statusPill(status) {
  const map = {
    pending:   ['pill-amber', 'Pending'],
    process:   ['pill-blue',  'Diproses'],
    done:      ['pill-green', 'Selesai'],
    delivered: ['pill-gray',  'Diantar'],
  };
  const [cls, label] = map[status] ?? ['pill-gray', status];
  return `<span class="pill ${cls}">${label}</span>`;
}

function fmtRp(n)  { return 'Rp ' + Number(n).toLocaleString('id-ID'); }
function fmtTime(iso) { return new Date(iso).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }); }

/* ────────────────────────────────────────────
   UPDATE STATS & TABLE
──────────────────────────────────────────── */
function updateStats(orders) {
  const total   = orders.length;
  const pending = orders.filter(o => ['pending','process'].includes(o.status)).length;
  const done    = orders.filter(o => ['done','delivered'].includes(o.status)).length;
  const revenue = orders.filter(o => ['done','delivered'].includes(o.status))
                        .reduce((s, o) => s + Number(o.total), 0);

  document.getElementById('statTotal').textContent   = total;
  document.getElementById('statPending').textContent = pending;
  document.getElementById('statDone').textContent    = done;
  document.getElementById('statRevenue').textContent = fmtRp(revenue);
}

function renderTable(orders) {
  const tbody = document.getElementById('orderTableBody');
  if (!tbody) return;
  if (orders.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">Belum ada pesanan hari ini</td></tr>`;
    return;
  }
  tbody.innerHTML = orders.slice(0, 10).map(o => `
    <tr>
      <td class="strong">${o.queue_number}</td>
      <td class="strong">${o.table_number ? 'Meja ' + o.table_number : 'Take Away'}</td>
      <td>${fmtTime(o.created_at)}</td>
      <td class="strong">${fmtRp(o.total)}</td>
      <td>${o.payment_method ?? '-'}</td>
      <td>${statusPill(o.status)}</td>
    </tr>`).join('');
}

/* ────────────────────────────────────────────
   POLLING — LIVE UPDATE TIAP 5 DETIK
──────────────────────────────────────────── */
let lastIds       = null; // null = belum ada data awal (first load)
let lastStatuses  = {};   // { id: status } — deteksi perubahan status

function poll() {
  fetch('/kasir/poll')
    .then(r => r.json())
    .then(orders => {

      // ── Deteksi pesanan BARU ──
      const currentIds = orders.map(o => o.id);

      if (lastIds !== null) {
        const newOrders = orders.filter(o => !lastIds.includes(o.id));

        if (newOrders.length > 0) {
          playBeep();

          newOrders.forEach(o => {
            showToast({
              title  : `🛒 Pesanan Baru Masuk!`,
              message: `${o.table_number ? 'Meja ' + o.table_number : 'Take Away'} · ${fmtRp(o.total)}`,
              type   : 'new'
            });
          });
        }

        // ── Deteksi perubahan STATUS ──
        orders.forEach(o => {
          const prev = lastStatuses[o.id];
          if (prev && prev !== o.status) {
            const labelMap = { process: 'Diproses', done: 'Selesai', delivered: 'Diantar', pending: 'Pending' };
            showToast({
              title  : `📦 Status Diperbarui`,
              message: `Pesanan ${o.queue_number ?? '#'+o.id} → ${labelMap[o.status] ?? o.status}`,
              type   : 'status'
            });
          }
        });
      }

      // ── Simpan state terkini ──
      lastIds = currentIds;
      orders.forEach(o => { lastStatuses[o.id] = o.status; });

      // ── Update UI ──
      updateStats(orders);
      renderTable(orders);
    })
    .catch(() => {
      // Gagal fetch — diam saja, coba lagi 5 detik berikutnya
    });
}

// Jalankan segera + tiap 5 detik
poll();
setInterval(poll, 5000);
</script>
</body>
</html>