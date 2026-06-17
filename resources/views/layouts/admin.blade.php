<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <style>
        /* ══════════════════════════════════════
           CSS GLOBAL & LAYOUT
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
        a { text-decoration: none; color: inherit; }

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
            width: 240px; height: 100vh; position: fixed; top: 0; left: 0;
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

        /* ── PAGE HEADER (Global) ── */
        .page-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: var(--space-xl); flex-wrap: wrap; gap: var(--space-md);
        }
        .page-title h1, .page-title { font-size: var(--text-3xl); font-weight: 800; color: var(--text-dark); letter-spacing: -0.4px; }
        .page-title p, .page-sub { font-size: var(--text-md); color: var(--text-light); margin-top: 3px; }

        /* ── RESPONSIVE GLOBAL ── */
        @media (max-width: 780px) {
            .main { padding: 88px var(--space-md) 30px; }
            .topbar { padding: 0 var(--space-md); }
        }
    </style>
    @stack('styles')
</head>
<body>

@php
    $userAvatar  = auth()->user()->avatar ?? null;
    $avatarUrl   = $userAvatar ? asset('storage/' . $userAvatar) : null;
    $userInitial = strtoupper(substr(auth()->user()->name ?? 'A', 0, 1));
@endphp

{{-- ── TOPBAR ── --}}
<header class="topbar">
    <div class="topbar-left">
        <button class="menu-btn" onclick="toggleSidebar()" aria-label="Toggle menu">
            <i data-lucide="menu"></i>
        </button>
    </div>

    <div class="topbar-right">
        <div class="live-clock" aria-live="polite">
            <i data-lucide="clock" style="width: 14px; height: 14px; color: var(--primary);"></i>
            <span id="clock" style="font-variant-numeric:tabular-nums">--:--:--</span>
        </div>

        <div class="profile-wrap">
            <button class="user-btn" id="profileBtn" onclick="toggleDropdown()" aria-expanded="false" aria-haspopup="true">
                <div class="user-avatar {{ $avatarUrl ? 'has-photo' : '' }}">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}">
                    @else
                        {{ $userInitial }}
                    @endif
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </button>

            <div class="dropdown" id="dropdownMenu" role="menu">
                <div class="dp-head">
                    <div class="dp-av {{ $avatarUrl ? 'has-photo' : '' }}">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}">
                        @else
                            {{ $userInitial }}
                        @endif
                    </div>
                    <div>
                        <div class="dp-nm">{{ auth()->user()->name }}</div>
                        <div class="dp-rl">{{ ucfirst(auth()->user()->role) }} · Online</div>
                    </div>
                </div>
                <div class="dp-body">
                    <a href="/admin/account/profil" class="dp-item" role="menuitem">
                        <div class="dp-ico"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                        Profil Saya
                    </a>
                    <div class="dp-divider" role="separator"></div>
                    <button type="button" class="dp-item danger" role="menuitem" id="logoutBtn" onclick="openLogoutModal()">
                        <div class="dp-ico" id="logoutIcon">
                            <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        </div>
                        <span id="logoutText">Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ── SIDEBAR ── --}}
<nav class="sidebar" id="sidebar" aria-label="Navigasi admin">
    <div class="menu-section">Main</div>
    <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i data-lucide="layout-dashboard"></i> Dashboard
    </a>

    <div class="menu-section">Katalog</div>
    <a href="/admin/menu" class="{{ request()->is('admin/menu*') ? 'active' : '' }}">
        <i data-lucide="utensils"></i> Menu
    </a>
    <a href="/admin/kategori" class="{{ request()->is('admin/kategori*') ? 'active' : '' }}">
        <i data-lucide="folder"></i> Kategori
    </a>
    <a href="/admin/addons" class="{{ request()->is('admin/addons*') ? 'active' : '' }}">
        <i data-lucide="plus-circle"></i> Add-ons
    </a>

    <div class="menu-section">Operasional</div>
    <a href="/admin/meja" class="{{ request()->is('admin/meja*') ? 'active' : '' }}">
        <i data-lucide="armchair"></i> Meja
    </a>
    <a href="/admin/order" class="{{ request()->is('admin/order*') ? 'active' : '' }}">
        <i data-lucide="clipboard-list"></i> Order
    </a>
    <a href="/admin/pembayaran" class="{{ request()->is('admin/pembayaran*') ? 'active' : '' }}">
        <i data-lucide="credit-card"></i> Pembayaran
    </a>

    <div class="menu-section">Analitik</div>
    <a href="/admin/laporan" class="{{ request()->is('admin/laporan*') ? 'active' : '' }}">
        <i data-lucide="bar-chart-3"></i> Laporan
    </a>

    <div class="menu-section">Sistem</div>
    <a href="/admin/user" class="{{ request()->is('admin/user*') ? 'active' : '' }}">
        <i data-lucide="users"></i> User
    </a>
</nav>

<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

{{-- ── MAIN CONTENT ── --}}
<main class="main">
    @yield('content')
</main>

{{-- ── SCRIPTS ── --}}
<script>
// Live clock
(function tick() {
    const d = new Date();
    const p = n => String(n).padStart(2,'0');
    document.getElementById('clock').textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
    setTimeout(tick, 1000);
})();

// Sidebar toggle
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('overlay').classList.toggle('show');
}

// Profile dropdown
function toggleDropdown() {
    const btn  = document.getElementById('profileBtn');
    const menu = document.getElementById('dropdownMenu');
    const open = btn.classList.toggle('open');
    menu.classList.toggle('show', open);
    btn.setAttribute('aria-expanded', open);
}
document.addEventListener('click', function(e) {
    const wrap = document.querySelector('.profile-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('profileBtn').classList.remove('open');
        document.getElementById('dropdownMenu').classList.remove('show');
        document.getElementById('profileBtn').setAttribute('aria-expanded','false');
    }
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.getElementById('profileBtn').classList.remove('open');
        document.getElementById('dropdownMenu').classList.remove('show');
    }
});

lucide.createIcons();
</script>

@stack('scripts')

{{-- ══════════════════════════════════════
     MODAL KONFIRMASI LOGOUT
══════════════════════════════════════ --}}
<div id="logoutModal" onclick="if(event.target===this)closeLogoutModal()"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);
            align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:20px;width:100%;max-width:360px;
                box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden;">

        {{-- Header --}}
        <div style="padding:24px 24px 0;text-align:center;">
            <div style="width:60px;height:60px;background:#fef2f2;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"
                     style="width:28px;height:28px;">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </div>
            <p style="font-weight:800;font-size:17px;color:#0f172a;margin:0 0 6px;">Keluar dari Akun?</p>
            <p style="font-size:13px;color:#64748b;margin:0 0 24px;line-height:1.5;">
                Sesi Anda akan diakhiri dan Anda akan diarahkan ke halaman login.
            </p>
        </div>

        {{-- Form POST ke route logout (wajib POST + CSRF) --}}
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
        </form>

        {{-- Tombol --}}
        <div style="display:flex;gap:10px;padding:0 24px 24px;">
            <button type="button" onclick="closeLogoutModal()"
                    style="flex:1;padding:12px;border-radius:12px;border:1.5px solid #e2e8f0;
                           background:#f8fafc;color:#475569;font-weight:700;font-size:13px;
                           cursor:pointer;transition:background .15s;font-family:inherit;"
                    onmouseover="this.style.background='#e2e8f0'"
                    onmouseout="this.style.background='#f8fafc'">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('logoutForm').submit()"
                    style="flex:1;padding:12px;border-radius:12px;border:none;
                           background:linear-gradient(135deg,#ef4444,#dc2626);
                           color:#fff;font-weight:700;font-size:13px;
                           cursor:pointer;box-shadow:0 4px 14px rgba(239,68,68,.35);
                           transition:opacity .15s;font-family:inherit;"
                    onmouseover="this.style.opacity='.88'"
                    onmouseout="this.style.opacity='1'">
                Ya, Logout
            </button>
        </div>
    </div>
</div>

<script>
function openLogoutModal() {
    const m = document.getElementById('logoutModal');
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLogoutModal() {
    const m = document.getElementById('logoutModal');
    m.style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLogoutModal();
});
</script>

</body>
</html>