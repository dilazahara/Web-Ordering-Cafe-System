<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pelayan')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --bg: #f4f6fb;
  --surface: #ffffff;
  --surface-2: #f8f9fd;
  --border: rgba(0,0,0,0.07);
  --border-md: rgba(0,0,0,0.12);
  --teal: #10b981;
  --teal-bg: #ecfdf5;
  --teal-border: #a7f3d0;
  --teal-text: #065f46;
  --red: #dc2626;
  --red-bg: #fef2f2;
  --red-border: #fecaca;
  --red-text: #991b1b;
  --amber-bg: #fffbeb;
  --amber-border: #fde68a;
  --amber-text: #92400e;
  --blue-bg: #eff6ff;
  --blue-border: #bfdbfe;
  --blue-text: #1e40af;
  --radius: 14px;
  --radius-sm: 10px;
}

html { scroll-behavior: smooth; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: #0f1623; -webkit-font-smoothing: antialiased; min-height: 100vh; line-height: 1.5; }

/* ── HEADER ── */
.header {
  background: var(--surface);
  border-bottom: 0.5px solid var(--border-md);
  padding: 0 28px;
  height: 68px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 50;
  box-shadow: 0 1px 12px rgba(0,0,0,.04);
}
.logo { display: flex; align-items: center; gap: 10px; }
.logo-mark { width: 38px; height: 38px; background: var(--teal); border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.logo-mark i { font-size: 18px; color: #fff; }
.logo-name { font-size: 17px; font-weight: 700; color: #0f1623; letter-spacing: -0.4px; }
.logo-name em { font-style: normal; color: var(--teal); }

.hdr-center { display: flex; align-items: center; }
.hdr-badge { display: flex; align-items: center; gap: 7px; padding: 7px 16px; border-radius: 30px; background: var(--teal-bg); border: 0.5px solid var(--teal-border); font-size: 13px; font-weight: 700; color: var(--teal-text); }
.dot-pulse { width: 7px; height: 7px; border-radius: 50%; background: var(--teal); flex-shrink: 0; animation: pulse 2s ease-in-out infinite; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.8)} }

.hdr-right { display: flex; align-items: center; gap: 10px; }
.clock-pill { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 10px; background: var(--surface-2); border: 0.5px solid var(--border-md); font-size: 13px; font-weight: 700; color: #0f1623; font-variant-numeric: tabular-nums; }
.clock-pill i { font-size: 15px; color: #5a6279; }

/* ── PROFILE DROPDOWN ── */
.profile-wrap { position: relative; }
.user-pill { display: flex; align-items: center; gap: 8px; padding: 6px 12px 6px 6px; border-radius: 10px; background: var(--surface-2); border: 0.5px solid var(--border-md); cursor: pointer; user-select: none; transition: all .15s; }
.user-pill:hover { background: #f0f1f5; border-color: rgba(0,0,0,.18); }
.user-pill.open { border-color: var(--teal); background: var(--teal-bg); }
.avatar { width: 30px; height: 30px; border-radius: 8px; background: var(--teal); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 700; flex-shrink: 0; overflow: hidden; }
.avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
.u-name { font-size: 13px; font-weight: 700; color: #0f1623; }
.chevron { font-size: 14px; color: #5a6279; transition: transform .2s; }
.user-pill.open .chevron { transform: rotate(180deg); }

.dropdown { position: absolute; top: calc(100% + 10px); right: 0; width: 240px; background: var(--surface); border: 0.5px solid var(--border-md); border-radius: 16px; box-shadow: 0 16px 40px rgba(0,0,0,.12); overflow: hidden; opacity: 0; transform: translateY(-8px) scale(.97); pointer-events: none; transition: opacity .18s, transform .18s; z-index: 200; }
.dropdown.show { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }
.dd-header { padding: 16px; background: linear-gradient(135deg, var(--teal-bg), #d1fae5); border-bottom: 0.5px solid var(--border-md); display: flex; align-items: center; gap: 12px; }
.dd-avatar { width: 40px; height: 40px; background: var(--teal); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; font-weight: 800; flex-shrink: 0; overflow: hidden; }
.dd-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
.dd-name { font-size: 13.5px; font-weight: 800; color: #0f1623; }
.dd-role { font-size: 11.5px; color: #5a6279; margin-top: 2px; }
.dd-body { padding: 8px; }
.dd-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; text-decoration: none; cursor: pointer; font-size: 13.5px; font-weight: 600; color: #5a6279; transition: all .15s; border: none; background: none; width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; }
.dd-item:hover { background: var(--surface-2); color: #0f1623; }
.dd-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: var(--surface-2); flex-shrink: 0; transition: all .15s; }
.dd-icon i { font-size: 15px; color: #5a6279; }
.dd-item:hover .dd-icon { background: #e8eaf0; }
.dd-divider { height: 0.5px; background: var(--border-md); margin: 6px 8px; }
.dd-item.danger { color: var(--red-text); }
.dd-item.danger:hover { background: var(--red-bg); color: var(--red); }
.dd-item.danger .dd-icon { background: var(--red-bg); }
.dd-item.danger .dd-icon i { color: var(--red); }
.dd-item.danger:hover .dd-icon { background: #fecaca; }

/* ── MAIN ── */
.main { padding: 32px 28px 64px; max-width: 1280px; margin: 0 auto; }
.page-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
.page-title { font-size: 22px; font-weight: 800; color: #0f1623; letter-spacing: -0.5px; }
.page-sub { font-size: 13px; color: #5a6279; margin-top: 4px; }

/* ── SWIPE HINT BANNER ── */
.swipe-hint {
  display: flex; align-items: center; gap: 10px;
  background: var(--teal-bg);
  border: 0.5px solid var(--teal-border);
  border-radius: 12px;
  padding: 11px 16px;
  margin-bottom: 20px;
  font-size: 13px;
  color: var(--teal-text);
  font-weight: 600;
}
.swipe-hint i { font-size: 20px; color: var(--teal); flex-shrink: 0; }

.section-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.section-label { font-size: 14px; font-weight: 700; color: #0f1623; display: flex; align-items: center; gap: 7px; }
.section-label i { font-size: 15px; color: var(--teal); }
.count-pill { padding: 4px 13px; border-radius: 20px; font-size: 12px; font-weight: 700; background: var(--teal-bg); color: var(--teal-text); border: 0.5px solid var(--teal-border); }

/* ── GRID ── */
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }

/* ── SWIPE WRAPPER ── */
.swipe-outer {
  position: relative;
  border-radius: var(--radius);
  overflow: hidden;
  /* for collapse animation */
  transition: max-height .4s ease, opacity .3s ease, margin .4s ease;
}

/* Green layer shown behind card as it slides */
.swipe-confirm {
  position: absolute;
  inset: 0;
  background: var(--teal);
  border-radius: var(--radius);
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding-right: 28px;
  gap: 10px;
  color: #fff;
  font-size: 15px;
  font-weight: 700;
  opacity: 0;
  pointer-events: none;
}
.swipe-confirm i { font-size: 28px; }

/* The actual card */
.card {
  position: relative;
  z-index: 1;
  background: var(--surface);
  border: 0.5px solid var(--border-md);
  border-radius: var(--radius);
  overflow: hidden;
  cursor: grab;
  user-select: none;
  touch-action: pan-y;
  will-change: transform;
}
.card:active { cursor: grabbing; }
.card.late-card { border-color: rgba(220,38,38,.25); }

/* Progress stripe at bottom of card */
.swipe-bar {
  position: absolute;
  bottom: 0; left: 0;
  height: 3px;
  width: 0%;
  background: #94a3b8;
  z-index: 10;
  border-radius: 0;
  pointer-events: none;
}

.card-head { padding: 16px 18px; background: var(--surface-2); border-bottom: 0.5px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.ch-left { display: flex; align-items: center; gap: 12px; }
.table-dot { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 19px; background: var(--teal-bg); border: 0.5px solid var(--teal-border); flex-shrink: 0; }
.table-dot.late-dot { background: var(--red-bg); border-color: var(--red-border); }
.ch-info h3 { font-size: 14px; font-weight: 800; color: #0f1623; margin-bottom: 2px; }
.ch-info p { font-size: 12px; color: #5a6279; }
.ch-right { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; }
.order-time { font-size: 12px; font-weight: 700; color: #5a6279; background: var(--surface); padding: 3px 9px; border-radius: 7px; border: 0.5px solid var(--border-md); }
.status-pill { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
.sp-green { background: var(--teal-bg); color: var(--teal-text); border: 0.5px solid var(--teal-border); }
.sp-red   { background: var(--red-bg);  color: var(--red-text);  border: 0.5px solid var(--red-border); }

.card-body { padding: 14px 18px; }
.wait-info { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #5a6279; margin-bottom: 12px; flex-wrap: wrap; }
.wait-info i { font-size: 13px; }
.wt-time { font-weight: 700; color: var(--teal); }
.wt-time.late { color: var(--red); }
.late-tag { margin-left: auto; font-size: 10px; font-weight: 700; color: var(--red-text); background: var(--red-bg); padding: 2px 8px; border-radius: 20px; border: 0.5px solid var(--red-border); }

.item-list { list-style: none; border-radius: var(--radius-sm); overflow: hidden; border: 0.5px solid var(--border-md); }
.item-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; font-size: 13px; border-bottom: 0.5px solid var(--border); }
.item-row:last-child { border-bottom: none; }
.item-row:nth-child(even) { background: var(--surface-2); }
.item-name { font-weight: 600; color: #0f1623; }
.item-qty { background: var(--blue-bg); color: var(--blue-text); font-size: 11px; font-weight: 700; padding: 2px 9px; border-radius: 20px; border: 0.5px solid var(--blue-border); }

.order-note { margin-top: 10px; background: var(--amber-bg); border: 0.5px solid var(--amber-border); border-radius: 9px; padding: 9px 12px; font-size: 12px; color: var(--amber-text); display: flex; align-items: flex-start; gap: 7px; }
.order-note i { font-size: 14px; flex-shrink: 0; margin-top: 1px; }

/* ── EMPTY ── */
.empty { grid-column: 1/-1; text-align: center; padding: 64px 20px; background: var(--surface); border: 0.5px solid var(--border-md); border-radius: var(--radius); }
.empty-icon { width: 60px; height: 60px; border-radius: 50%; background: var(--teal-bg); border: 0.5px solid var(--teal-border); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.empty-icon i { font-size: 26px; color: var(--teal); }
.empty h3 { font-size: 15px; font-weight: 700; color: #0f1623; margin-bottom: 6px; }
.empty p { font-size: 13px; color: #9198ae; max-width: 260px; margin: 0 auto; line-height: 1.6; }

/* ── TOAST ── */
.toast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%) translateY(80px); background: #0f1623; color: #fff; padding: 10px 20px; border-radius: 12px; font-size: 13px; font-weight: 600; transition: transform .3s; z-index: 999; white-space: nowrap; box-shadow: 0 8px 24px rgba(0,0,0,.15); display: flex; align-items: center; gap: 8px; }
.toast.show { transform: translateX(-50%) translateY(0); }
/* ── ALERT FLASH ── */
.alert-flash { padding: 12px 18px; border-radius: 12px; margin-bottom: 18px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
.alert-flash.success { background: #dcfce7; color: #166534; border: 1px solid #a7f3d0; }
.toast i { font-size: 16px; }

/* ── RESPONSIVE ── */
@media (max-width: 640px) {
  .header { padding: 0 16px; }
  .hdr-center { display: none; }
  .main { padding: 20px 16px 48px; }
  .u-name { display: none; }
  .grid { grid-template-columns: 1fr; }
}
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(0,0,0,.15); border-radius: 6px; }

    </style>
    @stack('styles')
</head>
<body>


<header class="header">

  <div class="logo">
    <div class="logo-mark"><i class="ti ti-arrow-right" aria-hidden="true"></i></div>
    <div class="logo-name">Pelayan<em></em></div>
  </div>

  <div class="hdr-center">
    <div class="hdr-badge">
      <span class="dot-pulse"></span>
      <span id="badgeCount">0 Siap Diantar</span>
    </div>
  </div>

  <div class="hdr-right">
    <div class="clock-pill">
      <i class="ti ti-clock" aria-hidden="true"></i>
      <span id="liveClock">00:00:00</span>
    </div>

    @php
      $user    = auth()->user();
      $initial = strtoupper(substr($user->name ?? 'P', 0, 1));
    @endphp

    <div class="profile-wrap">
      <div class="user-pill" id="profileBtn" onclick="toggleDropdown()">
        <div class="avatar">
          @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil">
          @else
            {{ $initial }}
          @endif
        </div>
        <span class="u-name">{{ $user->name }}</span>
        <i class="ti ti-chevron-down chevron" aria-hidden="true"></i>
      </div>

      <div class="dropdown" id="profileDropdown">
        <div class="dd-header">
          <div class="dd-avatar">
            @if($user->avatar)
              <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil">
            @else
              {{ $initial }}
            @endif
          </div>
          <div>
            <div class="dd-name">{{ $user->name }}</div>
            <div class="dd-role">{{ ucfirst($user->role ?? 'Pelayan') }} · Online</div>
          </div>
        </div>
        <div class="dd-body">
          <a href="/pelayan/account/profil" class="dd-item">
            <div class="dd-icon"><i class="ti ti-user"></i></div>Profil Saya
          </a>
          <a href="/pelayan/account/ganti-sandi" class="dd-item">
            <div class="dd-icon"><i class="ti ti-lock"></i></div>Ganti Password
          </a>
          <div class="dd-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dd-item danger">
              <div class="dd-icon"><i class="ti ti-logout"></i></div>Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>


<main class="main">
    @yield('content')
</main>

<script>
/* ── CLOCK ── */
function updateClock() {
  const n = new Date();
  document.getElementById('liveClock').textContent =
    [n.getHours(), n.getMinutes(), n.getSeconds()]
      .map(v => String(v).padStart(2, '0')).join(':');
}
setInterval(updateClock, 1000);
updateClock();

/* ── DROPDOWN ── */
function toggleDropdown() {
  const btn  = document.getElementById('profileBtn');
  const dd   = document.getElementById('profileDropdown');
  const open = dd.classList.toggle('show');
  btn.classList.toggle('open', open);
}
document.addEventListener('click', e => {
  const wrap = document.querySelector('.profile-wrap');
  if (wrap && !wrap.contains(e.target)) {
    document.getElementById('profileDropdown').classList.remove('show');
    document.getElementById('profileBtn').classList.remove('open');
  }
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.getElementById('profileDropdown').classList.remove('show');
    document.getElementById('profileBtn').classList.remove('open');
  }
});
</script>

@stack('scripts')
</body>
</html>