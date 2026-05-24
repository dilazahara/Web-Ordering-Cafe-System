<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dapur')</title>
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
  --header-h: 64px;
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

/* ── MAIN (no topnav, langsung di bawah header) ── */
.main { margin-top: var(--header-h); padding: 36px 24px 72px; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 8px; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; gap: 16px; flex-wrap: wrap; }
.page-title { font-size: 23px; font-weight: 800; letter-spacing: -0.5px; }
.page-sub { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-family: 'Inter', sans-serif; }
.alert { padding: 14px 18px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
.alert-success { background: var(--green-bg); color: var(--green-text); border: 1px solid #a7f3d0; }

/* ── LIVE UPDATE BADGE ── */
.live-update-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13.5px;
  font-weight: 700;
  color: var(--green);
}
.live-dot {
  width: 10px;
  height: 10px;
  background: #10b981;
  border-radius: 50%;
  box-shadow: 0 0 6px rgba(16, 185, 129, 0.5);
  animation: blink 1.5s ease-in-out infinite;
}

/* ── SWIPE HINT BANNER ── */
.swipe-hint {
  display: flex; align-items: center; gap: 10px;
  background: var(--green-bg);
  border: 1px solid #a7f3d0;
  border-radius: 12px;
  padding: 12px 18px;
  margin-bottom: 20px;
  font-size: 13px;
  color: var(--green-text);
  font-weight: 600;
}
.swipe-hint-icon { font-size: 22px; flex-shrink: 0; }

/* ── STATS BANNER ── */
.stats-banner { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
.stat-card { flex: 1; min-width: 130px; background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 14px 16px; box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 12px; transition: box-shadow .2s; }
.stat-card:hover { box-shadow: var(--shadow); }
.stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.stat-icon.blue { background: var(--blue-bg); }
.stat-icon.red { background: var(--red-bg); }
.stat-icon.green { background: var(--green-bg); }
.stat-icon.amber { background: var(--amber-bg); }
.stat-icon.purple { background: #f3f0ff; }
.stat-val { font-size: 22px; font-weight: 800; line-height: 1.1; color: var(--text-primary); font-variant-numeric: tabular-nums; font-family: 'Inter', sans-serif; }
.stat-label { font-size: 11.5px; color: var(--text-muted); font-family: 'Inter', sans-serif; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.5px; }
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

/* ── SWIPE WRAPPER ── */
.swipe-outer {
  position: relative;
  border-radius: var(--radius-lg);
  overflow: hidden;
  transition: max-height .4s ease, opacity .3s ease, margin .4s ease;
}

/* Green confirmation layer */
.swipe-confirm {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, #10b981, #059669);
  border-radius: var(--radius-lg);
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding-right: 32px;
  gap: 12px;
  color: #fff;
  font-size: 16px;
  font-weight: 800;
  opacity: 0;
  pointer-events: none;
  z-index: 0;
}
.swipe-confirm-icon { font-size: 32px; }

/* Progress bar at bottom */
.swipe-bar {
  position: absolute;
  bottom: 0; left: 0;
  height: 4px;
  width: 0%;
  background: #94a3b8;
  z-index: 10;
  border-radius: 0;
  pointer-events: none;
  transition: background .2s;
}

.order-card { 
  position: relative;
  z-index: 1;
  background: var(--surface); 
  border: 1px solid var(--border); 
  border-radius: var(--radius-lg); 
  overflow: hidden; 
  box-shadow: var(--shadow); 
  cursor: grab;
  user-select: none;
  touch-action: pan-y;
  will-change: transform;
}
.order-card:active { cursor: grabbing; }
.order-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #3b82f6, #6366f1); z-index: 5; }
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
.pill { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; font-family: 'Inter', sans-serif; }
.pill-blue { background: var(--blue-bg); color: var(--blue-text); border: 1px solid #bfdbfe; }
.pill-green { background: var(--green-bg); color: var(--green-text); border: 1px solid #a7f3d0; }
.pill-amber { background: var(--amber-bg); color: var(--amber-text); border: 1px solid #fde68a; }

/* ── TOAST ── */
.toast {
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%) translateY(100px);
  background: #0f1623;
  color: #fff;
  padding: 12px 24px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 700;
  transition: transform .3s;
  z-index: 999;
  white-space: nowrap;
  box-shadow: 0 8px 24px rgba(0,0,0,.2);
  display: flex;
  align-items: center;
  gap: 10px;
}
.toast.show { transform: translateX(-50%) translateY(0); }
.toast-icon { font-size: 20px; }

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
  .user-role, .user-info { display: none; }
  .stats-banner { gap: 8px; }
  .stat-card { min-width: 140px; }
  .empty-tips { grid-template-columns: 1fr; }
  .page-header { flex-direction: column; align-items: flex-start; }
}

::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 6px; }

    </style>
    @stack('styles')
</head>
<body>


<header class="header">
  <div class="logo">
    <div class="logo-mark">
      <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2z"/><path d="M8 12h8M12 8v8"/></svg>
    </div>
    <div class="logo-text">Dapur<span></span></div>
  </div>

  <div class="header-right">
    <div class="header-clock">
      <svg viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
      </svg>
      <span id="liveClock">00:00:00</span>
    </div>

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
    </div>
</header>

{{-- ── TOPNAV DIHAPUS — hanya ada satu halaman (Sedang Diproses) ── --}}


@yield('topnav')

<main class="main">
  <div class="container">
    @yield('content')
  </div>
</main>

<script>
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

const profileBtn = document.getElementById('profileBtn');
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

// ═══════════════════════════════
</script>

@stack('scripts')
</body>
</html>