<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dapur — Sedang Diproses</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --bg: #f0f2f8; --surface: #ffffff; --surface-2: #f7f8fc;
  --border: #e4e8f0; --border-strong: #ccd2e0;
  --text-primary: #0f1623; --text-secondary: #5a6279; --text-muted: #9198ae;
  --green: #059669; --green-bg: #ecfdf5; --green-text: #065f46;
  --amber: #d97706; --amber-bg: #fffbeb; --amber-text: #92400e;
  --red: #dc2626; --red-bg: #fef2f2; --red-text: #991b1b;
  --orange: #ea580c; --orange-bg: #fff7ed; --orange-text: #9a3412;
  --blue: #2563eb; --blue-bg: #eff4ff; --blue-text: #1e40af;
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
.logo-mark { width: 36px; height: 36px; background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgb(234 88 12/.35); }
.logo-mark svg { width: 18px; height: 18px; stroke: white; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.logo-text { font-size: 16px; font-weight: 800; letter-spacing: -0.5px; }
.logo-text span { color: var(--orange); }
.header-right { display: flex; align-items: center; gap: 8px; }
.header-clock { display:flex; align-items:center; gap:8px; padding:8px 14px; border-radius:12px; background:var(--surface); border:1px solid var(--border); font-family:'Inter',sans-serif; box-shadow:var(--shadow-sm); }
.header-clock svg { width:16px; height:16px; stroke:var(--blue); stroke-width:2.3; fill:none; }
#liveClock { font-size:13px; font-weight:700; color:var(--text-primary); letter-spacing:.5px; }

/* ── PROFILE DROPDOWN ── */
.profile-wrap { position: relative; }
.user-btn { display: flex; align-items: center; gap: 10px; padding: 5px 12px 5px 5px; border: 1px solid var(--border); border-radius: 12px; background: var(--surface); cursor: pointer; transition: all 0.18s; user-select: none; }
.user-btn:hover { background: var(--surface-2); border-color: var(--border-strong); box-shadow: var(--shadow-sm); }
.user-btn.open { border-color: var(--orange); background: var(--orange-bg); box-shadow: 0 0 0 3px rgba(234,88,12,.1); }
.avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #fb923c, #ea580c); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: 700; flex-shrink: 0; overflow: hidden; }
.avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
.dropdown-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, #fb923c, #ea580c); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; font-weight: 800; flex-shrink: 0; box-shadow: 0 2px 8px rgb(234 88 12/.3); overflow: hidden; }
.dropdown-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
.user-info { display: flex; flex-direction: column; }
.user-name { font-size: 13px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
.user-role { font-size: 11px; color: var(--text-muted); font-family: 'Inter', sans-serif; }
.chevron { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; transition: transform .2s; flex-shrink: 0; }
.user-btn.open .chevron { transform: rotate(180deg); }
.dropdown { position: absolute; top: calc(100% + 10px); right: 0; width: 240px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 16px 48px rgb(0 0 0/.14), 0 0 0 1px rgb(0 0 0/.04); overflow: hidden; opacity: 0; transform: translateY(-8px) scale(.97); pointer-events: none; transition: opacity .18s, transform .18s; z-index: 200; }
.dropdown.show { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }
.dropdown-header { padding: 16px; background: linear-gradient(135deg, var(--orange-bg), #fef3c7); border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
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
.dropdown-item.danger:hover .item-icon { background: #fecaca; }

/* ── TOPNAV ── */
.topnav { position: fixed; top: var(--header-h); left: 0; right: 0; height: var(--nav-h); background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); border-bottom: 1px solid var(--border); display: flex; justify-content: center; z-index: 99; }
.nav-container { max-width: 1280px; margin: 0 auto; display: flex; align-items: stretch; padding: 0 8px; }
.nav-link { display: flex; align-items: center; gap: 7px; padding: 0 18px; text-decoration: none; font-size: 13px; font-weight: 600; color: var(--text-secondary); transition: all 0.18s; white-space: nowrap; border-bottom: 2px solid transparent; margin-bottom: -1px; }
.nav-link svg { width: 15px; height: 15px; stroke: currentColor; stroke-width: 2.2; fill: none; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
.nav-link:hover { color: var(--text-primary); }
.nav-link.active { color: var(--orange); border-bottom-color: var(--orange); }
.nav-badge { background: var(--red); color: white; font-size: 10px; font-weight: 700; border-radius: 8px; padding: 1px 6px; font-family: 'Inter', sans-serif; }

/* ── MAIN ── */
.main { margin-top: var(--total-top); padding: 36px 24px 72px; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 8px; }
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 32px; gap: 16px; }
.page-title { font-size: 23px; font-weight: 800; letter-spacing: -0.5px; }
.page-sub { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-family: 'Inter', sans-serif; }
.alert { padding: 14px 18px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
.alert-success { background: var(--green-bg); color: var(--green-text); border: 1px solid #a7f3d0; }

/* ── STATS BANNER ── */
.stats-banner { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
.stat-card { flex: 1; min-width: 130px; background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 14px 16px; box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 12px; transition: box-shadow .2s; }
.stat-card:hover { box-shadow: var(--shadow); }
.stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.stat-icon.blue { background: var(--blue-bg); }
.stat-icon.red { background: var(--red-bg); }
.stat-icon.green { background: var(--green-bg); }
.stat-icon.amber { background: var(--amber-bg); }
.stat-val { font-size: 22px; font-weight: 800; line-height: 1.1; color: var(--text-primary); font-variant-numeric: tabular-nums; font-family: 'Inter', sans-serif; }
.stat-label { font-size: 11.5px; color: var(--text-muted); font-family: 'Inter', sans-serif; margin-top: 3px; }
.stat-card.urgent-card { border-color: #fecaca; background: var(--red-bg); animation: urgent-pulse 2s ease-in-out infinite; }
.stat-card.urgent-card .stat-val { color: var(--red); }
.stat-card.urgent-card .stat-label { color: var(--red-text); }
@keyframes urgent-pulse { 0%,100%{ box-shadow: 0 0 0 0 rgba(220,38,38,0); } 50%{ box-shadow: 0 0 0 4px rgba(220,38,38,.15); } }

/* ── URGENT ALERT BANNER ── */
.urgent-alert { display: none; margin-bottom: 20px; padding: 14px 18px; border-radius: 12px; background: var(--red-bg); border: 1px solid #fecaca; font-size: 13px; font-weight: 700; color: var(--red-text); align-items: center; gap: 10px; animation: urgent-pulse 2s ease-in-out infinite; }
.urgent-alert.visible { display: flex; }
.urgent-alert-icon { font-size: 20px; flex-shrink: 0; }
.urgent-alert-text { flex: 1; }
.urgent-alert-count { background: var(--red); color: white; font-size: 11px; font-weight: 800; border-radius: 20px; padding: 2px 10px; font-family: 'Inter', sans-serif; flex-shrink: 0; }

/* ── ORDER GRID ── */
.order-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 18px; }
.order-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow); transition: box-shadow 0.22s, transform 0.22s; position: relative; }
.order-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #3b82f6, #6366f1); }
.order-card:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }
.order-card-top { padding: 18px 20px; border-bottom: 1px solid var(--border); background: var(--surface-2); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.oc-left { display: flex; align-items: center; gap: 14px; }
.table-badge { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; background: var(--blue-bg); border: 1px solid #bfdbfe; }
.oc-info h3 { font-size: 15px; font-weight: 800; margin-bottom: 3px; }
.oc-info p { font-size: 12px; color: var(--text-secondary); font-family: 'Inter', sans-serif; }
.oc-time { font-size: 13px; font-weight: 700; color: var(--text-secondary); font-family: 'Inter', sans-serif; background: var(--surface); padding: 4px 10px; border-radius: 8px; border: 1px solid var(--border); }
.progress-wrap { padding: 12px 20px; background: var(--blue-bg); border-bottom: 1px solid #bfdbfe; }
.progress-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.progress-label { font-size: 11.5px; font-weight: 700; color: var(--blue-text); font-family: 'Inter', sans-serif; text-transform: uppercase; letter-spacing: 0.5px; }
.progress-time { font-size: 13px; font-weight: 800; color: var(--blue); font-family: 'Inter', sans-serif; font-variant-numeric: tabular-nums; }
.progress-time.warning { color: var(--red); }
.progress-bar { height: 6px; background: #dbeafe; border-radius: 6px; overflow: hidden; }
.progress-fill { height: 100%; background: linear-gradient(90deg, #3b82f6, #6366f1); border-radius: 6px; transition: width 1s linear; }
.order-items { padding: 16px 20px; }
.item-row { display:flex; justify-content:space-between; align-items:center; padding:14px 0; border-bottom:1px solid #eee; }
.item-row:last-child { border-bottom: none; }
.item-left { display: flex; align-items: flex-start; gap: 10px; flex: 1; }
.item-qty { min-width:40px; height:40px; border-radius:12px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-weight:700; }
.item-name { font-size:16px; font-weight:700; color:#111827; }
.item-price { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }
.item-check { width: 22px; height: 22px; border-radius: 6px; border: 2px solid #bfdbfe; background: white; cursor: pointer; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all 0.18s; margin-top: 2px; }
.item-check.checked { background: var(--green); border-color: var(--green); }
.item-check.checked::after { content: '✓'; color: white; font-size: 12px; font-weight: 800; }
.order-footer { padding: 14px 20px; border-top: 1px solid var(--border); }
.act-btn { width: 100%; padding: 11px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; border: none; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; justify-content: center; gap: 7px; transition: all 0.18s; }
.act-btn svg { width: 15px; height: 15px; stroke: currentColor; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.ab-success { background: linear-gradient(135deg, #10b981, #059669); color: white; box-shadow: 0 3px 10px rgb(5 150 105/.25); }
.ab-success:hover { box-shadow: 0 6px 16px rgb(5 150 105/.35); filter: brightness(1.07); transform: translateY(-1px); }
.pill { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; font-family: 'Inter', sans-serif; }
.pill-blue { background: var(--blue-bg); color: var(--blue-text); border: 1px solid #bfdbfe; }

/* ── EMPTY STATE ── */
.empty-state { text-align: center; padding: 60px 20px 80px; }
.empty-icon-wrap { position: relative; display: inline-block; margin-bottom: 24px; }
.empty-icon { font-size: 80px; display: block; animation: float 3.5s ease-in-out infinite; line-height: 1; }
.empty-glow { width: 140px; height: 140px; background: radial-gradient(circle, rgba(16,185,129,.15) 0%, transparent 70%); border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); animation: pulse-glow 3.5s ease-in-out infinite; pointer-events: none; }
@keyframes float { 0%,100%{ transform:translateY(0) rotate(-2deg); } 50%{ transform:translateY(-14px) rotate(2deg); } }
@keyframes pulse-glow { 0%,100%{ opacity:.4; transform:translate(-50%,-50%) scale(1); } 50%{ opacity:1; transform:translate(-50%,-50%) scale(1.25); } }

.empty-title { font-size: 22px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; margin-bottom: 8px; }
.empty-sub { font-size: 14px; color: var(--text-muted); font-family: 'Inter', sans-serif; margin-bottom: 36px; }

.empty-tips { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; max-width: 720px; margin: 0 auto 32px; text-align: left; }
.empty-tip { display: flex; align-items: flex-start; gap: 14px; padding: 16px 18px; background: var(--surface); border: 1px solid var(--border); border-radius: 14px; box-shadow: var(--shadow-sm); transition: box-shadow .2s, transform .2s; }
.empty-tip:hover { box-shadow: var(--shadow); transform: translateY(-2px); }
.tip-icon { font-size: 24px; flex-shrink: 0; margin-top: 1px; }
.tip-body { flex: 1; }
.tip-title { font-size: 13px; font-weight: 800; color: var(--text-primary); margin-bottom: 3px; }
.tip-desc { font-size: 12px; color: var(--text-secondary); font-family: 'Inter', sans-serif; line-height: 1.5; }

.empty-status { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--green-bg); border: 1px solid #a7f3d0; border-radius: 50px; font-size: 13px; font-weight: 700; color: var(--green-text); }
.empty-status-dot { width: 8px; height: 8px; background: var(--green); border-radius: 50%; animation: blink 1.5s ease-in-out infinite; }
@keyframes blink { 0%,100%{ opacity:1; transform:scale(1); } 50%{ opacity:.5; transform:scale(.8); } }

/* ── RESPONSIVE ── */
@media (max-width: 640px) {
  .main { padding: 24px 16px 48px; }
  .order-grid { grid-template-columns: 1fr; }
  .nav-link span { display: none; }
  .user-role, .user-info { display: none; }
  .stats-banner { gap: 8px; }
  .stat-card { min-width: 140px; }
  .empty-tips { grid-template-columns: 1fr; }
}

::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 6px; }
</style>
</head>
<body>

<!-- ═══════════════════════════════ HEADER ═══════════════════════════════ -->
<header class="header">
  <div class="logo">
    <div class="logo-mark">
      <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2z"/><path d="M8 12h8M12 8v8"/></svg>
    </div>
    <div class="logo-text">Dapur<span></span></div>
  </div>

  <div class="header-right">
    <x-notification-bell />

    <div class="header-clock">
      <svg viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
      </svg>
      <span id="liveClock">00:00:00</span>
    </div>

    <!-- PROFILE DROPDOWN -->
    <div class="profile-wrap">
      <div class="user-btn" id="profileBtn" onclick="toggleDropdown()">
        <div class="avatar">
          @if(Auth::user()->avatar)
            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
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
              <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
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
          <a href="/dapur/account/profil" class="dropdown-item">
            <div class="item-icon"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Profil Saya
          </a>
          <a href="/dapur/account/ganti-sandi" class="dropdown-item">
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
    <!-- END PROFILE DROPDOWN -->
  </div>
</header>

<!-- ═══════════════════════════════ TOPNAV ═══════════════════════════════ -->
<nav class="topnav">
  <div class="nav-container">
    <a href="/dapur/proses" class="nav-link {{ request()->is('dapur/proses') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      <span>Sedang Diproses</span>
      @if($orders->count() > 0)
        <span class="nav-badge">{{ $orders->count() }}</span>
      @endif
    </a>
    <a href="/dapur/selesai" class="nav-link {{ request()->is('dapur/selesai') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      <span>Selesai</span>
    </a>
  </div>
</nav>

<!-- ═══════════════════════════════ MAIN ═══════════════════════════════ -->
<main class="main">
  <div class="container">

    <div class="page-header">
      <div>
        <div class="page-title">Sedang Diproses</div>
        <div class="page-sub">
          @if($orders->count() > 0)
            {{ $orders->count() }} pesanan sedang dikerjakan
          @else
            Tidak ada pesanan aktif saat ini
          @endif
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    @if($orders->count() > 0)

      {{-- ── STATS BANNER ── --}}
      <div class="stats-banner">

        <div class="stat-card">
          <div class="stat-icon blue">🍽️</div>
          <div>
            <div class="stat-val">{{ $orders->count() }}</div>
            <div class="stat-label">Pesanan aktif</div>
          </div>
        </div>

        {{-- Card ini ditampilkan/disembunyikan oleh JS --}}
        <div class="stat-card" id="statLateCard" style="display:none;">
          <div class="stat-icon red">⏰</div>
          <div>
            <div class="stat-val" id="statLateNum">0</div>
            <div class="stat-label">Lewat 10 menit</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green">⚡</div>
          <div>
            <div class="stat-val" id="statFastest">--:--</div>
            <div class="stat-label">Order terbaru</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon amber">🧾</div>
          <div>
            <div class="stat-val">{{ $orders->sum(fn($o) => $o->items->sum('qty')) }}</div>
            <div class="stat-label">Total item</div>
          </div>
        </div>

      </div>

      {{-- ── URGENT ALERT ── --}}
      <div class="urgent-alert" id="urgentAlert">
        <span class="urgent-alert-icon">🚨</span>
        <span class="urgent-alert-text" id="urgentAlertText">Ada pesanan yang melewati batas waktu!</span>
        <span class="urgent-alert-count" id="urgentAlertCount">0</span>
      </div>

      {{-- ── ORDER GRID ── --}}
      <div class="order-grid">

        @foreach($orders as $order)
        <div class="order-card">

          {{-- HEADER CARD --}}
          <div class="order-card-top">
            <div class="oc-left">
              <div class="table-badge">👨‍🍳</div>
              <div class="oc-info">
                <h3>🍽️ Meja {{ $order->table_number ?? '-' }}</h3>
                <p>
                  {{ $order->queue_number }}
                  &nbsp;·&nbsp;
                  @if($order->payment_method === 'qris')
                    <span class="pill pill-green">📱 QRIS (Auto)</span>
                  @else
                    <span class="pill pill-amber">💵 Cash</span>
                  @endif
                  &nbsp;·&nbsp;
                  <span class="pill pill-blue">Diproses</span>
                </p>
              </div>
            </div>
            <div class="oc-time time" data-time="{{ $order->process_at ?? $order->created_at }}">00:00</div>
          </div>

          {{-- PROGRESS --}}
          <div class="progress-wrap">
            <div class="progress-top">
              <span class="progress-label">⏱ Waktu proses</span>
              <span class="progress-time time" data-time="{{ $order->process_at ?? $order->created_at }}">00:00</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-time="{{ $order->process_at ?? $order->created_at }}" style="width:0%"></div>
            </div>
          </div>

          {{-- ITEMS --}}
          <div class="order-items">
            @foreach($order->items as $item)
            <div class="item-row">
              <div class="item-name">{{ $item->menu->nama ?? 'Menu' }}</div>
              <div class="item-qty">{{ $item->qty }}x</div>
            </div>
            @endforeach
          </div>

          {{-- NOTE --}}
          @if($order->note)
          <div style="margin: 0 20px 14px; padding: 10px 14px; background:var(--amber-bg); border:1px solid #fde68a; border-radius:10px; font-size:12.5px; color:var(--amber-text);">
            📝 <strong>Catatan:</strong> {{ $order->note }}
          </div>
          @endif

          {{-- ACTION --}}
          <div class="order-footer">
            <form action="{{ route('dapur.tandaiSelesai', $order->id) }}" method="POST">
              @csrf
              <button type="submit" class="act-btn ab-success">
                ✅ Tandai Selesai
              </button>
            </form>
          </div>

        </div>
        @endforeach

      </div>

    @else

      {{-- ════════════════════════════════════
           EMPTY STATE — DAPUR KOSONG
      ════════════════════════════════════ --}}
      <div class="empty-state">

        <div class="empty-icon-wrap">
          <div class="empty-glow"></div>
          <span class="empty-icon">🧑‍🍳</span>
        </div>

        <div class="empty-title">Dapur Bersih, Semua Kelar!</div>
        <div class="empty-sub">Tidak ada pesanan yang sedang diproses saat ini 🎉</div>

        <div class="empty-tips">

          <div class="empty-tip">
            <span class="tip-icon">📋</span>
            <div class="tip-body">
              <div class="tip-title">Pesanan baru muncul otomatis</div>
              <div class="tip-desc">Halaman ini memperbarui diri ketika order baru masuk dari kasir</div>
            </div>
          </div>

          <div class="empty-tip">
            <span class="tip-icon">⏱️</span>
            <div class="tip-body">
              <div class="tip-title">Target waktu: 10 menit</div>
              <div class="tip-desc">Timer berubah merah & alert muncul jika pesanan melewati batas waktu</div>
            </div>
          </div>

          <div class="empty-tip">
            <span class="tip-icon">🚨</span>
            <div class="tip-body">
              <div class="tip-title">Notifikasi pesanan menumpuk</div>
              <div class="tip-desc">Banner merah muncul otomatis jika ada pesanan yang terlambat dikerjakan</div>
            </div>
          </div>

          <div class="empty-tip">
            <span class="tip-icon">✅</span>
            <div class="tip-body">
              <div class="tip-title">Tandai selesai saat hidangan siap</div>
              <div class="tip-desc">Pesanan akan pindah ke tab Selesai dan kasir mendapat notifikasi</div>
            </div>
          </div>

        </div>

        <div class="empty-status">
          <span class="empty-status-dot"></span>
          Dapur siap menerima pesanan
        </div>

      </div>

    @endif

  </div>
</main>

<script>

// ═══════════════════════════════
// TIMER ORDER
// ═══════════════════════════════

const TARGET = 600; // 10 menit dalam detik

function updateTimers() {
  document.querySelectorAll('[data-time]').forEach(el => {
    const diff = Math.floor((Date.now() - new Date(el.dataset.time)) / 1000);
    const m = String(Math.floor(diff / 60)).padStart(2, '0');
    const s = String(diff % 60).padStart(2, '0');

    if (el.classList.contains('time')) {
      el.textContent = m + ':' + s;
      if (diff >= TARGET) {
        el.classList.add('warning');
      }
    }

    if (el.classList.contains('progress-fill')) {
      const pct = Math.min((diff / TARGET) * 100, 100);
      el.style.width = pct + '%';
      if (pct >= 100) {
        el.style.background = 'linear-gradient(90deg,#ef4444,#dc2626)';
      }
    }
  });
}

setInterval(updateTimers, 1000);
updateTimers();

// ═══════════════════════════════
// STATS BANNER REALTIME
// ═══════════════════════════════

function updateStats() {
  const fills = document.querySelectorAll('.progress-fill[data-time]');
  if (!fills.length) return;

  let lateCount = 0;
  let minDiff = Infinity;

  fills.forEach(el => {
    const diff = Math.floor((Date.now() - new Date(el.dataset.time)) / 1000);
    if (diff >= TARGET) lateCount++;
    if (diff < minDiff) minDiff = diff;
  });

  // Stat: order terbaru (paling kecil diff-nya)
  const statFastest = document.getElementById('statFastest');
  if (statFastest && minDiff !== Infinity) {
    const m = String(Math.floor(minDiff / 60)).padStart(2, '0');
    const s = String(minDiff % 60).padStart(2, '0');
    statFastest.textContent = m + ':' + s;
  }

  // Stat card: lewat 10 menit
  const statLateCard = document.getElementById('statLateCard');
  const statLateNum  = document.getElementById('statLateNum');
  if (statLateCard && statLateNum) {
    statLateNum.textContent = lateCount;
    if (lateCount > 0) {
      statLateCard.style.display = 'flex';
      statLateCard.classList.add('urgent-card');
    } else {
      statLateCard.style.display = 'none';
      statLateCard.classList.remove('urgent-card');
    }
  }

  // Urgent alert banner
  const urgentAlert     = document.getElementById('urgentAlert');
  const urgentAlertText = document.getElementById('urgentAlertText');
  const urgentAlertCount= document.getElementById('urgentAlertCount');
  if (urgentAlert) {
    if (lateCount > 0) {
      urgentAlert.classList.add('visible');
      urgentAlertCount.textContent = lateCount;
      urgentAlertText.textContent = lateCount === 1
        ? '1 pesanan sudah melewati 10 menit — segera selesaikan!'
        : `${lateCount} pesanan sudah melewati batas waktu — prioritaskan sekarang!`;
    } else {
      urgentAlert.classList.remove('visible');
    }
  }
}

setInterval(updateStats, 1000);
updateStats();

// ═══════════════════════════════
// LIVE CLOCK
// ═══════════════════════════════

function updateClock() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2, '0');
  const m = String(now.getMinutes()).padStart(2, '0');
  const s = String(now.getSeconds()).padStart(2, '0');
  document.getElementById('liveClock').textContent = `${h}:${m}:${s}`;
}

setInterval(updateClock, 1000);
updateClock();

// ═══════════════════════════════
// DROPDOWN PROFILE
// ═══════════════════════════════

const profileBtn      = document.getElementById('profileBtn');
const profileDropdown = document.getElementById('profileDropdown');

function toggleDropdown() {
  profileDropdown.classList.toggle('show');
  profileBtn.classList.toggle('open');
}

document.addEventListener('click', function(e) {
  if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
    profileDropdown.classList.remove('show');
    profileBtn.classList.remove('open');
  }
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    profileDropdown.classList.remove('show');
    profileBtn.classList.remove('open');
  }
});

</script>
</body>
</html>