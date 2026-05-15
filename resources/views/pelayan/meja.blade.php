<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pelayan — Status Meja</title>
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
  --teal: #10b77f; --teal-bg: #ecfdf5; --teal-text: #065f46;
  --header-h: 64px; --nav-h: 48px; --total-top: 112px;
  --radius-lg: 18px; --radius: 14px;
  --shadow-sm: 0 1px 4px rgb(0 0 0/.05), 0 0 0 1px rgb(0 0 0/.04);
  --shadow: 0 2px 8px rgb(0 0 0/.06), 0 0 0 1px rgb(0 0 0/.04);
  --shadow-md: 0 8px 24px rgb(0 0 0/.10), 0 0 0 1px rgb(0 0 0/.04);
  --shadow-header: 0 1px 0 var(--border), 0 2px 12px rgb(0 0 0/.04);
}
html { scroll-behavior: smooth; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); line-height: 1.5; min-height: 100vh; -webkit-font-smoothing: antialiased; }

/* ── HEADER ── */
.header { position: fixed; top: 0; left: 0; right: 0; height: var(--header-h); background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; z-index: 100; box-shadow: var(--shadow-header); }
.logo { display: flex; align-items: center; gap: 10px; }
.logo-mark { width: 36px; height: 36px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgb(16 185 129/.35); }
.logo-mark svg { width: 18px; height: 18px; stroke: white; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.logo-text { font-size: 16px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
.logo-text span { color: var(--teal); }
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
  stroke:var(--blue);
  stroke-width:2.3;
  fill:none;
}

#liveClock{
  font-size:13px;
  font-weight:700;
  color:var(--text-primary);
  letter-spacing:.5px;
}
.hdr-badge { display: flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 10px; background: var(--teal-bg); border: 1px solid #a7f3d0; font-size: 12.5px; font-weight: 700; color: var(--teal-text); font-family: 'Inter', sans-serif; }
.hdr-badge .dot-live { width: 7px; height: 7px; border-radius: 50%; background: var(--teal); animation: pulse 2s ease-in-out infinite; flex-shrink: 0; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.85)} }
.divider-v { width: 1px; height: 28px; background: var(--border); margin: 0 4px; }

/* ── PROFILE DROPDOWN ── */
.profile-wrap { position: relative; }
.user-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 5px 10px 5px 5px;
  border: 1px solid var(--border); border-radius: 12px;
  background: var(--surface); cursor: pointer;
  transition: all 0.18s; user-select: none;
}
.user-btn:hover { background: var(--surface-2); border-color: var(--border-strong); box-shadow: var(--shadow-sm); }
.user-btn.open { border-color: var(--teal); background: var(--teal-bg); box-shadow: 0 0 0 3px rgba(16,183,127,.12); }
.avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #34d399, #059669); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: 700; flex-shrink: 0; }
.user-info { display: flex; flex-direction: column; }
.user-name { font-size: 13px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
.user-role { font-size: 11px; color: var(--text-muted); font-family: 'Inter', sans-serif; }
.chevron { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; transition: transform .2s; flex-shrink: 0; }
.user-btn.open .chevron { transform: rotate(180deg); }

.dropdown {
  position: absolute; top: calc(100% + 10px); right: 0;
  width: 240px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 16px;
  box-shadow: 0 16px 48px rgb(0 0 0/.14), 0 0 0 1px rgb(0 0 0/.04);
  overflow: hidden;
  opacity: 0; transform: translateY(-8px) scale(.97);
  pointer-events: none;
  transition: opacity .18s, transform .18s;
  z-index: 200;
}
.dropdown.show { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }

.dropdown-header {
  padding: 16px;
  background: linear-gradient(135deg, var(--teal-bg), #d1fae5);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 12px;
}
.dropdown-avatar {
  width: 40px; height: 40px;
  background: linear-gradient(135deg, #34d399, #059669);
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  color: white; font-size: 14px; font-weight: 800;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgb(5 150 105/.3);
}
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
.dropdown-item .item-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: var(--surface-2); transition: all .15s; }
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
.nav-link.active { color: var(--teal); border-bottom-color: var(--teal); }

/* ── MAIN ── */
.main { margin-top: var(--total-top); padding: 36px 24px 72px; width: 100%; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 8px; }
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px; gap: 16px; flex-wrap: wrap; }
.page-title { font-size: 23px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
.page-title span { color: var(--teal); }
.page-sub { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-family: 'Inter', sans-serif; }
.refresh-badge { display: flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 10px; background: var(--surface); border: 1px solid var(--border); font-size: 12.5px; font-weight: 600; color: var(--text-secondary); font-family: 'Inter', sans-serif; }
.refresh-badge svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
#countdown { color: var(--teal); font-weight: 700; }

/* ── STATS ── */
.stats-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; margin-bottom: 28px; }
.stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 18px 16px; text-align: center; box-shadow: var(--shadow); transition: transform .2s, box-shadow .2s; }
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.stat-num { font-size: 30px; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; }
.stat-label { font-size: 12px; color: var(--text-muted); margin-top: 5px; font-family: 'Inter', sans-serif; }
.stat-num.kosong  { color: var(--text-muted); }
.stat-num.pending { color: var(--amber); }
.stat-num.process { color: var(--accent); }
.stat-num.done    { color: var(--teal); }
.stat-card.done-card { border-color: rgba(16,183,127,.3); background: var(--teal-bg); }

/* ── LEGEND ── */
.legend { display: flex; gap: 18px; flex-wrap: wrap; margin-bottom: 24px; padding: 14px 18px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
.legend-item { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: var(--text-secondary); font-family: 'Inter', sans-serif; font-weight: 500; }
.dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.dot-kosong  { background: var(--text-muted); }
.dot-pending { background: var(--amber); }
.dot-process { background: var(--accent); }
.dot-done    { background: var(--teal); }

/* ── MEJA GRID ── */
.section-title { font-size: 15px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 8px; letter-spacing: -0.3px; margin-bottom: 16px; }
.section-title svg { width: 16px; height: 16px; stroke: var(--teal); stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.meja-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; }

.meja-card { background: var(--surface); border: 2px solid var(--border); border-radius: var(--radius); padding: 18px 12px; text-align: center; position: relative; box-shadow: var(--shadow); transition: transform .2s, border-color .2s, box-shadow .2s; }
.meja-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.meja-card.kosong  { opacity: .55; }
.meja-card.pending { border-color: var(--amber); }
.meja-card.pending:hover { box-shadow: 0 6px 20px rgba(217,119,6,.15); }
.meja-card.process { border-color: var(--accent); }
.meja-card.process:hover { box-shadow: 0 6px 20px rgba(37,99,235,.15); }
.meja-card.done    { border-color: var(--teal); }
.meja-card.done:hover { box-shadow: 0 6px 20px rgba(16,183,127,.15); }

.meja-icon { font-size: 22px; margin-bottom: 8px; }
.meja-num { font-size: 22px; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-primary); margin-bottom: 6px; }
.meja-status-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; padding: 3px 9px; border-radius: 20px; display: inline-block; }
.meja-card.kosong  .meja-status-label { background: #e5e7eb; color: var(--text-muted); }
.meja-card.pending .meja-status-label { background: var(--amber-bg); color: var(--amber-text); border: 1px solid #fde68a; }
.meja-card.process .meja-status-label { background: var(--accent-bg); color: var(--accent-text); border: 1px solid #bfdbfe; }
.meja-card.done    .meja-status-label { background: var(--teal-bg); color: var(--teal-text); border: 1px solid #a7f3d0; }
.meja-order-count { font-size: 11px; color: var(--text-muted); margin-top: 6px; font-family: 'Inter', sans-serif; }

.badge-ready { position: absolute; top: -7px; right: -7px; width: 20px; height: 20px; background: var(--teal); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 800; color: #fff; box-shadow: 0 2px 6px rgba(16,183,127,.4); border: 2px solid white; font-family: 'Inter', sans-serif; }

/* ── RESPONSIVE ── */
@media (max-width: 640px) {
  .header { padding: 0 16px; }
  .main { padding: 24px 16px 48px; }
  .nav-link span { display: none; }
  .user-role { display: none; }
  .meja-grid { grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); }
}
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 6px; }
</style>
</head>
<body>

<!-- ══ HEADER ══ -->
<header class="header">
  <div class="logo">
    <div class="logo-mark">
      <svg viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="4" rx="1"/><line x1="7" y1="12" x2="7" y2="18"/><line x1="17" y1="12" x2="17" y2="18"/></svg>
    </div>
    <div class="logo-text">Pelayan<span></span></div>
  </div>
  <div class="header-right">
    <div class="header-clock">
  <svg viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="10"/>
    <polyline points="12 6 12 12 16 14"/>
  </svg>

  <span id="liveClock">00:00:00</span>
</div>

    <div class="hdr-badge">
      <span class="dot-live"></span>
      <span>Status Meja</span>
    </div>
    <div class="divider-v"></div>

    <!-- PROFILE DROPDOWN -->
    <!-- PROFILE DROPDOWN -->
<div class="profile-wrap">

  @php
      $user = auth()->user();

      $initial = strtoupper(substr($user->name ?? 'P', 0, 1));
  @endphp

  <div class="user-btn" id="profileBtn" onclick="toggleDropdown()">

      <div class="avatar">
          {{ $initial }}
      </div>

      <div class="user-info">
          <div class="user-name">
              {{ $user->name }}
          </div>

          <div class="user-role">
              {{ ucfirst($user->role ?? 'Pelayan') }}
          </div>
      </div>

      <svg class="chevron" viewBox="0 0 24 24">
          <polyline points="6 9 12 15 18 9"/>
      </svg>

  </div>

  <div class="dropdown" id="profileDropdown">

      <!-- HEADER -->
      <div class="dropdown-header">

          <div class="dropdown-avatar">
              {{ $initial }}
          </div>

          <div>
              <div class="dropdown-name">
                  {{ $user->name }}
              </div>

              <div class="dropdown-role">
                  {{ ucfirst($user->role ?? 'Pelayan') }} · Online
              </div>
          </div>

      </div>

      <!-- MENU -->
      <div class="dropdown-body">

          <a href="/pelayan/account/profil" class="dropdown-item">

              <div class="item-icon">
                  <svg viewBox="0 0 24 24">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                      <circle cx="12" cy="7" r="4"/>
                  </svg>
              </div>

              Profil Saya

          </a>

          <a href="/pelayan/account/ganti-sandi" class="dropdown-item">

              <div class="item-icon">
                  <svg viewBox="0 0 24 24">
                      <rect x="3" y="11" width="18" height="11" rx="2"/>
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
<!-- END PROFILE DROPDOWN -->
</header>

<!-- ══ TOP NAV ══ -->
<nav class="topnav">
  <div class="nav-container">
    <a href="{{ route('pelayan.antar') }}" class="nav-link {{ request()->routeIs('pelayan.antar') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><polyline points="12 8 12 12 15 14"/></svg>
      <span>Antar Makanan</span>
    </a>
    <a href="{{ route('pelayan.meja') }}" class="nav-link {{ request()->routeIs('pelayan.meja') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="4" rx="1"/><line x1="7" y1="12" x2="7" y2="18"/><line x1="17" y1="12" x2="17" y2="18"/></svg>
      <span>Status Meja</span>
    </a>
  </div>
</nav>

<!-- ══ MAIN ══ -->
<main class="main">
  <div class="container">

    <div class="page-header">
      <div>
        <h1 class="page-title">Status <span>Meja</span></h1>
        <p class="page-sub">Pantau kondisi semua meja hari ini</p>
      </div>
      <div class="refresh-badge">
        <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
        Refresh dalam <span id="countdown">30</span>d
      </div>
    </div>

    @php
      $mejaKosong  = 0;
      $mejaPending = 0;
      $mejaProcess = 0;
      $mejaDone    = 0;

      for ($i = 1; $i <= $totalMeja; $i++) {
        if (!isset($ordersAktif[$i]) || $ordersAktif[$i]->isEmpty()) {
          $mejaKosong++;
        } else {
          $statuses = $ordersAktif[$i]->pluck('status')->toArray();
          if (in_array('done', $statuses))         $mejaDone++;
          elseif (in_array('process', $statuses))  $mejaProcess++;
          else                                      $mejaPending++;
        }
      }
    @endphp

    <!-- STATS -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-num kosong">{{ $mejaKosong }}</div>
        <div class="stat-label">Meja Kosong</div>
      </div>
      <div class="stat-card">
        <div class="stat-num pending">{{ $mejaPending }}</div>
        <div class="stat-label">Menunggu Dapur</div>
      </div>
      <div class="stat-card">
        <div class="stat-num process">{{ $mejaProcess }}</div>
        <div class="stat-label">Sedang Dimasak</div>
      </div>
      <div class="stat-card done-card">
        <div class="stat-num done">{{ $mejaDone }}</div>
        <div class="stat-label">Siap Diantar</div>
      </div>
    </div>

    <!-- LEGEND -->
    <div class="legend">
      <div class="legend-item"><div class="dot dot-kosong"></div> Kosong</div>
      <div class="legend-item"><div class="dot dot-pending"></div> Menunggu dapur</div>
      <div class="legend-item"><div class="dot dot-process"></div> Sedang dimasak</div>
      <div class="legend-item"><div class="dot dot-done"></div> Siap diantar</div>
    </div>

    <!-- MEJA GRID -->
    <div class="section-title">
      <svg viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="4" rx="1"/><line x1="7" y1="12" x2="7" y2="18"/><line x1="17" y1="12" x2="17" y2="18"/></svg>
      Semua Meja
    </div>

    <div class="meja-grid">
      @for($i = 1; $i <= $totalMeja; $i++)
        @php
          $orders    = $ordersAktif[$i] ?? collect();
          $statuses  = $orders->pluck('status')->toArray();
          $doneCount = $orders->where('status', 'done')->count();

          if ($orders->isEmpty()) {
            $cardClass = 'kosong';
            $labelText = 'Kosong';
            $icon      = '🪑';
          } elseif (in_array('done', $statuses)) {
            $cardClass = 'done';
            $labelText = 'Siap Diantar';
            $icon      = '✅';
          } elseif (in_array('process', $statuses)) {
            $cardClass = 'process';
            $labelText = 'Dimasak';
            $icon      = '🍳';
          } else {
            $cardClass = 'pending';
            $labelText = 'Menunggu';
            $icon      = '⏳';
          }
        @endphp

        <div class="meja-card {{ $cardClass }}">
          @if($doneCount > 0)
            <div class="badge-ready">{{ $doneCount }}</div>
          @endif
          <div class="meja-icon">{{ $icon }}</div>
          <div class="meja-num">{{ $i }}</div>
          <div class="meja-status-label">{{ $labelText }}</div>
          @if(!$orders->isEmpty())
            <div class="meja-order-count">{{ $orders->count() }} order</div>
          @endif
        </div>
      @endfor
    </div>

  </div>
</main>

<script>
  function updateClock(){

    const now = new Date();

    const h = String(now.getHours()).padStart(2,'0');
    const m = String(now.getMinutes()).padStart(2,'0');
    const s = String(now.getSeconds()).padStart(2,'0');

    document.getElementById('liveClock')
        .textContent = `${h}:${m}:${s}`;

}

setInterval(updateClock,1000);

updateClock();

/* ── DROPDOWN ── */
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

/* ── COUNTDOWN ── */
let sisa = 30;
const el = document.getElementById('countdown');
const tick = setInterval(() => {
  sisa--;
  if (el) el.textContent = sisa;
  if (sisa <= 0) { clearInterval(tick); location.reload(); }
}, 1000);
</script>

</body>
</html>