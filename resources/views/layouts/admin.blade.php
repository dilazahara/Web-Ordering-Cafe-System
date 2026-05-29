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
    /* ═══════════════════════════════════════════════
       DESIGN SYSTEM — shared tokens
    ═══════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        /* Fonts */
        --font-ui:   'Plus Jakarta Sans', sans-serif;
        --font-data: 'Inter', sans-serif;

        /* Scale */
        --text-2xs:  10px; --text-xs: 11px; --text-sm: 12px;
        --text-base: 13px; --text-md: 14px; --text-lg: 15px;
        --text-xl: 17px; --text-2xl: 20px; --text-3xl: 24px; --text-4xl: 30px;

        /* Spacing */
        --sp-1: 4px;  --sp-2: 8px;  --sp-3: 12px; --sp-4: 16px;
        --sp-5: 20px; --sp-6: 24px; --sp-7: 28px; --sp-8: 32px;
        --sp-10: 40px; --sp-12: 48px;

        /* Radius */
        --r-sm: 6px; --r-md: 10px; --r-lg: 14px;
        --r-xl: 18px; --r-2xl: 22px; --r-full: 9999px;

        /* Shadows */
        --shadow-xs: 0 1px 2px rgba(0,0,0,.05);
        --shadow-sm: 0 1px 4px rgba(0,0,0,.06), 0 0 0 1px rgba(0,0,0,.04);
        --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 0 0 1px rgba(0,0,0,.04);
        --shadow-lg: 0 12px 40px rgba(0,0,0,.12), 0 0 0 1px rgba(0,0,0,.04);

        /* Neutrals */
        --n-50: #f8fafc; --n-100: #f1f5f9; --n-200: #e2e8f0;
        --n-300: #cbd5e1; --n-400: #94a3b8; --n-500: #64748b;
        --n-600: #475569; --n-700: #334155; --n-800: #1e293b; --n-900: #0f172a;

        /* Semantic */
        --text-primary:   #0f172a;
        --text-secondary: #475569;
        --text-muted:     #94a3b8;
        --bg-page:        #f0f2f8;
        --bg-surface:     #ffffff;
        --bg-subtle:      #f7f8fc;
        --border:         #e2e8f0;
        --border-md:      #cbd5e1;

        /* Status */
        --green:       #059669; --green-light: #ecfdf5; --green-dark: #065f46; --green-mid: #a7f3d0;
        --amber:       #d97706; --amber-light: #fffbeb; --amber-dark: #92400e; --amber-mid: #fde68a;
        --red:         #dc2626; --red-light:   #fef2f2; --red-dark:   #991b1b; --red-mid:   #fecaca;
        --blue:        #2563eb; --blue-light:  #eff6ff; --blue-dark:  #1e40af; --blue-mid:  #bfdbfe;
        --cyan:        #0891b2; --cyan-light:  #ecfeff;
        --orange:      #ea580c; --orange-light:#fff7ed;
        --pink:        #db2777; --pink-light:  #fdf2f8;

        /* ADMIN accent — purple */
        --accent:          #6366f1;
        --accent-hover:    #4f46e5;
        --accent-dark:     #4338ca;
        --accent-light:    #eef2ff;
        --accent-mid:      #c7d2fe;
        --accent-shadow:   rgba(99,102,241,.25);

        /* Sidebar */
        --sidebar-bg-from: #0f172a;
        --sidebar-bg-to:   #1e1b4b;
        --sidebar-accent:  #a78bfa;

        /* Layout */
        --header-h: 64px;
    }

    body {
        font-family: var(--font-ui);
        font-size: var(--text-md);
        background: var(--bg-page);
        color: var(--text-primary);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        line-height: 1.5;
    }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font-ui); cursor: pointer; }

    /* ── TOPBAR ─────────────────────────────────────────── */
    .topbar {
        position: fixed; top: 0; left: 0; right: 0;
        height: var(--header-h);
        background: rgba(255,255,255,.97);
        backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 var(--sp-7);
        z-index: 200;
        box-shadow: 0 1px 0 var(--border), 0 2px 12px rgba(0,0,0,.04);
    }
    .topbar-left  { display: flex; align-items: center; gap: var(--sp-3); }
    .topbar-right { display: flex; align-items: center; gap: var(--sp-2); }

    .menu-btn {
        width: 36px; height: 36px;
        border-radius: var(--r-md);
        border: 1px solid var(--border);
        background: var(--bg-subtle);
        color: var(--text-primary);
        display: flex; align-items: center; justify-content: center;
        transition: background .18s;
        cursor: pointer;
        flex-shrink: 0;
    }
    .menu-btn:hover { background: var(--border); }
    .menu-btn i { width: 18px; height: 18px; }

    /* ── CLOCK ─────────────────────────────────────────── */
    .clock-pill {
        display: flex; align-items: center; gap: var(--sp-2);
        padding: 7px var(--sp-3);
        border-radius: var(--r-md);
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-xs);
        font-family: var(--font-data);
        font-size: var(--text-base);
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: .4px;
        white-space: nowrap;
    }
    .clock-pill i { width: 14px; height: 14px; color: var(--accent); }

    /* ── USER BUTTON ────────────────────────────────────── */
    .user-btn {
        display: flex; align-items: center; gap: var(--sp-2);
        padding: 5px var(--sp-3) 5px 5px;
        border-radius: var(--r-md);
        border: 1px solid var(--border);
        background: var(--bg-surface);
        cursor: pointer;
        transition: all .18s;
        user-select: none;
    }
    .user-btn:hover { background: var(--bg-subtle); border-color: var(--border-md); box-shadow: var(--shadow-xs); }
    .user-btn.open  { border-color: var(--accent); background: var(--accent-light); box-shadow: 0 0 0 3px var(--accent-shadow); }

    .user-avatar {
        width: 30px; height: 30px;
        border-radius: var(--r-sm);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: var(--text-xs); font-weight: 800;
        flex-shrink: 0; overflow: hidden;
        background: linear-gradient(135deg, #818cf8, #4f46e5);
    }
    .user-avatar.has-photo { background: none; }
    .user-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: var(--r-sm); }

    .user-info .user-name { font-size: var(--text-base); font-weight: 700; color: var(--text-primary); line-height: 1.2; }
    .user-info .user-role { font-size: var(--text-xs); color: var(--text-muted); font-family: var(--font-data); }

    .chevron { width: 13px; height: 13px; stroke: var(--text-muted); fill: none; stroke-width: 2.5; transition: transform .2s; flex-shrink: 0; }
    .user-btn.open .chevron { transform: rotate(180deg); }

    /* ── DROPDOWN ───────────────────────────────────────── */
    .profile-wrap { position: relative; }
    .dropdown {
        position: absolute; top: calc(100% + 8px); right: 0;
        width: 236px; background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: var(--r-xl);
        box-shadow: var(--shadow-lg); overflow: hidden;
        opacity: 0; transform: translateY(-8px) scale(.97);
        pointer-events: none; transition: opacity .18s, transform .18s; z-index: 300;
    }
    .dropdown.show { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }

    .dp-head {
        padding: var(--sp-4);
        background: linear-gradient(135deg, var(--accent-light), #f5f3ff);
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: var(--sp-3);
    }
    .dp-av {
        width: 40px; height: 40px; border-radius: var(--r-md);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: var(--text-md); font-weight: 800;
        flex-shrink: 0; overflow: hidden;
        background: linear-gradient(135deg, #818cf8, #4f46e5);
        box-shadow: 0 2px 8px rgba(79,70,229,.28);
    }
    .dp-av.has-photo { background: none; }
    .dp-av img { width: 100%; height: 100%; object-fit: cover; border-radius: var(--r-md); }
    .dp-name { font-size: var(--text-base); font-weight: 800; color: var(--text-primary); line-height: 1.3; }
    .dp-role { font-size: var(--text-xs); color: var(--text-muted); font-family: var(--font-data); margin-top: 2px; }

    .dp-body { padding: var(--sp-2); }
    .dp-item {
        display: flex; align-items: center; gap: var(--sp-2);
        padding: 9px var(--sp-3); border-radius: var(--r-md);
        font-size: var(--text-base); font-weight: 600; color: var(--text-secondary);
        transition: background .15s, color .15s;
        border: none; background: none; width: 100%;
        font-family: var(--font-ui); text-align: left;
    }
    .dp-item:hover { background: var(--bg-subtle); color: var(--text-primary); }
    .dp-ico {
        width: 30px; height: 30px; border-radius: var(--r-sm);
        background: var(--bg-subtle); display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: background .15s;
    }
    .dp-ico svg { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .dp-item:hover .dp-ico { background: var(--border); }
    .dp-divider { height: 1px; background: var(--border); margin: var(--sp-1) var(--sp-2); }
    .dp-item.danger { color: var(--red-dark); }
    .dp-item.danger:hover { background: var(--red-light); color: var(--red); }
    .dp-item.danger .dp-ico { background: var(--red-light); }
    .dp-item.danger .dp-ico svg { stroke: var(--red); }
    .dp-item.danger:hover .dp-ico { background: var(--red-mid); }

    /* ── SIDEBAR ─────────────────────────────────────────── */
    .sidebar {
        width: 240px; height: 100vh;
        position: fixed; top: 0; left: 0;
        background: linear-gradient(180deg, var(--sidebar-bg-from) 0%, var(--sidebar-bg-to) 100%);
        padding: var(--sp-6) var(--sp-5);
        padding-top: calc(var(--header-h) + var(--sp-5));
        color: white; overflow-y: auto;
        transform: translateX(-100%);
        transition: transform .3s cubic-bezier(.4,0,.2,1);
        z-index: 199;
        box-shadow: 4px 0 24px rgba(0,0,0,.15);
        display: flex; flex-direction: column;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,.15) transparent;
    }
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 2px; }
    .sidebar.show { transform: translateX(0); }

    .menu-section {
        font-size: var(--text-2xs); font-weight: 700;
        letter-spacing: 1px; text-transform: uppercase;
        color: var(--sidebar-accent); opacity: .7;
        margin: var(--sp-5) var(--sp-2) var(--sp-2);
    }
    .menu-section:first-child { margin-top: 0; }

    .sidebar a {
        display: flex; align-items: center; gap: var(--sp-3);
        padding: 9px var(--sp-3); border-radius: var(--r-lg);
        color: rgba(255,255,255,.55); font-weight: 500; font-size: var(--text-md);
        transition: background .2s, color .2s, transform .2s;
        margin-bottom: 2px;
    }
    .sidebar a:hover { background: rgba(255,255,255,.09); color: white; transform: translateX(2px); }
    .sidebar a.active {
        background: rgba(139,92,246,.22); color: #c4b5fd;
        box-shadow: inset 0 0 0 1px rgba(139,92,246,.35);
    }
    .sidebar a i { width: 17px; height: 17px; stroke-width: 2; flex-shrink: 0; }

    /* ── OVERLAY ─────────────────────────────────────────── */
    .overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.4); z-index: 198;
        backdrop-filter: blur(3px);
    }
    .overlay.show { display: block; }

    /* ── MAIN ────────────────────────────────────────────── */
    .main {
        padding-top: calc(var(--header-h) + var(--sp-8));
        padding-left: var(--sp-7); padding-right: var(--sp-7);
        padding-bottom: var(--sp-12);
    }

    /* ── PAGE HEADER ─────────────────────────────────────── */
    .page-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: var(--sp-8); flex-wrap: wrap; gap: var(--sp-4);
    }
    .page-title   { font-size: var(--text-3xl); font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
    .page-sub     { font-size: var(--text-base); color: var(--text-secondary); margin-top: var(--sp-1); font-family: var(--font-data); }

    /* ── STATS GRID ──────────────────────────────────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: var(--sp-4); margin-bottom: var(--sp-8);
    }
    .stat-card {
        background: var(--bg-surface); border-radius: var(--r-xl);
        padding: var(--sp-5); border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        display: flex; align-items: center; gap: var(--sp-4);
        position: relative; overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .stat-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0;
        height: 3px; border-radius: var(--r-xl) var(--r-xl) 0 0;
    }
    .stat-card.orange::before { background: linear-gradient(90deg,#f97316,#fb923c); }
    .stat-card.blue::before   { background: linear-gradient(90deg,#3b82f6,#60a5fa); }
    .stat-card.cyan::before   { background: linear-gradient(90deg,#06b6d4,#22d3ee); }
    .stat-card.green::before  { background: linear-gradient(90deg,#22c55e,#4ade80); }
    .stat-card.purple::before { background: linear-gradient(90deg,#7c3aed,#a855f7); }
    .stat-card.red::before    { background: linear-gradient(90deg,#ef4444,#f87171); }
    .stat-card.dark::before   { background: linear-gradient(90deg,#334155,#475569); }
    .stat-card.pink::before   { background: linear-gradient(90deg,#ec4899,#f472b6); }

    .stat-icon {
        width: 48px; height: 48px; border-radius: var(--r-lg);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-icon i { width: 21px; height: 21px; }
    .stat-icon.orange { background:#fff7ed; color:#f97316; }
    .stat-icon.blue   { background:#eff6ff; color:#3b82f6; }
    .stat-icon.cyan   { background:#ecfeff; color:#06b6d4; }
    .stat-icon.green  { background:#f0fdf4; color:#22c55e; }
    .stat-icon.purple { background:#f3e8ff; color:#7c3aed; }
    .stat-icon.red    { background:#fef2f2; color:#ef4444; }
    .stat-icon.dark   { background:#e2e8f0; color:#334155; }
    .stat-icon.pink   { background:#fdf2f8; color:#ec4899; }

    .stat-value { font-size: 22px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; font-family: var(--font-data); }
    .stat-label { font-size: var(--text-xs); color: var(--text-muted); font-weight: 600; margin-top: 3px; text-transform: uppercase; letter-spacing: .5px; font-family: var(--font-data); }
    .stat-trend { font-size: var(--text-xs); font-weight: 700; margin-top: var(--sp-1); font-family: var(--font-data); }
    .stat-trend.up   { color: var(--green); }
    .stat-trend.flat { color: var(--text-muted); }

    /* ── CARD ────────────────────────────────────────────── */
    .card, .box {
        background: var(--bg-surface); border-radius: var(--r-xl);
        border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;
    }
    .card-header, .box-header {
        padding: var(--sp-4) var(--sp-5); border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; gap: var(--sp-4);
    }
    .card-header h3, .box-header h3 { font-size: var(--text-lg); font-weight: 700; color: var(--text-primary); }
    .card-body, .box-body { padding: var(--sp-4) var(--sp-5); }
    .chart-wrap { position: relative; height: 220px; }

    /* ── TOOLBAR ─────────────────────────────────────────── */
    .toolbar { display: flex; align-items: center; gap: var(--sp-3); flex-wrap: wrap; margin-bottom: var(--sp-5); }
    .search-wrap { position: relative; display: flex; align-items: center; }
    .search-wrap i { position: absolute; left: var(--sp-3); width: 14px; height: 14px; color: var(--text-muted); pointer-events: none; }
    .search-input {
        padding: 9px var(--sp-3) 9px calc(var(--sp-3) + 22px);
        border: 1.5px solid var(--border); border-radius: var(--r-lg);
        font-family: var(--font-ui); font-size: var(--text-base);
        color: var(--text-primary); outline: none; width: 220px;
        background: var(--bg-surface);
        transition: border-color .2s, box-shadow .2s;
    }
    .search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-shadow); }
    .search-input::placeholder { color: var(--text-muted); }

    /* ── BUTTONS ─────────────────────────────────────────── */
    .btn-primary, .btn-add {
        display: inline-flex; align-items: center; gap: var(--sp-2);
        padding: 9px var(--sp-4); border-radius: var(--r-lg);
        background: var(--accent); color: white;
        border: none; font-size: var(--text-base); font-weight: 600;
        cursor: pointer; font-family: var(--font-ui); white-space: nowrap;
        transition: background .2s, transform .15s, box-shadow .2s;
    }
    .btn-primary:hover, .btn-add:hover {
        background: var(--accent-hover); transform: translateY(-1px);
        box-shadow: 0 4px 12px var(--accent-shadow);
    }
    .btn-primary:active, .btn-add:active { transform: scale(.97); }
    .btn-primary i, .btn-add i { width: 14px; height: 14px; }

    .btn-secondary {
        display: inline-flex; align-items: center; gap: var(--sp-2);
        padding: 9px var(--sp-4); border-radius: var(--r-lg);
        background: var(--bg-surface); color: var(--text-secondary);
        border: 1.5px solid var(--border);
        font-size: var(--text-base); font-weight: 600;
        cursor: pointer; font-family: var(--font-ui); white-space: nowrap;
        transition: background .15s, border-color .15s;
    }
    .btn-secondary:hover { background: var(--bg-subtle); border-color: var(--border-md); }

    .btn-danger {
        display: inline-flex; align-items: center; gap: var(--sp-2);
        padding: 9px var(--sp-4); border-radius: var(--r-lg);
        background: var(--red); color: white;
        border: none; font-size: var(--text-base); font-weight: 600;
        cursor: pointer; font-family: var(--font-ui);
        transition: background .2s, transform .15s;
    }
    .btn-danger:hover { background: #c01c1c; transform: translateY(-1px); }

    /* ── ICON BUTTONS ────────────────────────────────────── */
    .act-btn {
        width: 34px; height: 34px; border-radius: var(--r-md);
        border: 1.5px solid transparent;
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .15s, border-color .15s, transform .1s;
        flex-shrink: 0; background: none;
    }
    .act-btn:active { transform: scale(.9); }
    .act-btn i { width: 14px; height: 14px; }
    .act-edit   { background: #eff6ff; color: #2563eb; border-color: #dbeafe; }
    .act-edit:hover { background: #dbeafe; border-color: #93c5fd; }
    .act-delete { background: var(--red-light); color: var(--red); border-color: var(--red-mid); }
    .act-delete:hover { background: var(--red-mid); border-color: #fca5a5; }
    .act-view   { background: var(--bg-subtle); color: var(--text-secondary); border-color: var(--border); }
    .act-view:hover { background: var(--border); }
    .action-wrap { display: flex; align-items: center; gap: var(--sp-2); }

    /* ── TABLE ───────────────────────────────────────────── */
    .table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: var(--bg-subtle); }
    thead th {
        padding: var(--sp-3) var(--sp-4);
        font-size: var(--text-xs); font-weight: 700;
        text-transform: uppercase; letter-spacing: .7px;
        color: var(--text-muted); text-align: left;
        border-bottom: 1px solid var(--border); white-space: nowrap;
        font-family: var(--font-data);
    }
    tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f9faff; }
    tbody td {
        padding: var(--sp-3) var(--sp-4);
        font-size: var(--text-base); color: var(--text-secondary);
        vertical-align: middle; font-family: var(--font-data);
    }

    /* ── BADGE ───────────────────────────────────────────── */
    .badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px var(--sp-2); border-radius: var(--r-full);
        font-size: var(--text-xs); font-weight: 700;
        font-family: var(--font-data); white-space: nowrap;
    }
    .badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
    .badge.pending  { background: var(--amber-light); color: var(--amber-dark); border: 1px solid var(--amber-mid); }
    .badge.pending  .badge-dot { background: var(--amber); }
    .badge.proses   { background: var(--blue-light); color: var(--blue-dark); border: 1px solid var(--blue-mid); }
    .badge.proses   .badge-dot { background: var(--blue); }
    .badge.selesai,
    .badge.diantar  { background: var(--green-light); color: var(--green-dark); border: 1px solid var(--green-mid); }
    .badge.selesai  .badge-dot,
    .badge.diantar  .badge-dot { background: var(--green); }
    .badge.dibatal  { background: var(--red-light);  color: var(--red-dark);  border: 1px solid var(--red-mid); }
    .badge.dibatal  .badge-dot { background: var(--red); }
    .badge.purple-badge { background: var(--accent-light); color: var(--accent-dark); border: 1px solid var(--accent-mid); }
    .badge.green-badge  { background: var(--green-light); color: var(--green-dark); border: 1px solid var(--green-mid); }
    .badge.red-badge    { background: var(--red-light);   color: var(--red-dark);   border: 1px solid var(--red-mid); }
    .badge.gray-badge   { background: var(--n-100);       color: var(--n-500);      border: 1px solid var(--border); }
    .badge.blue-badge   { background: var(--blue-light);  color: var(--blue-dark);  border: 1px solid var(--blue-mid); }

    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px var(--sp-3); border-radius: var(--r-full);
        font-size: var(--text-xs); font-weight: 700; font-family: var(--font-data);
    }
    .status-active   { background: var(--green-light); color: var(--green-dark); }
    .status-inactive { background: var(--n-100); color: var(--n-500); }
    .status-dot { width: 5px; height: 5px; border-radius: 50%; }
    .dot-active   { background: var(--green); }
    .dot-inactive { background: var(--text-muted); }

    /* ── FORM ────────────────────────────────────────────── */
    .form-group  { margin-bottom: var(--sp-5); }
    .form-label  { display: block; margin-bottom: var(--sp-2); font-size: var(--text-base); font-weight: 700; color: var(--text-primary); }
    .form-label .req { color: var(--red); margin-left: 2px; }
    .form-input, .form-select, .form-textarea {
        width: 100%; padding: 10px var(--sp-3);
        border: 1.5px solid var(--border); border-radius: var(--r-lg);
        background: var(--bg-subtle); font-size: var(--text-md);
        font-family: var(--font-ui); color: var(--text-primary); outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
        line-height: 1.5;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: var(--accent); background: var(--bg-surface);
        box-shadow: 0 0 0 3px var(--accent-shadow);
    }
    .form-input::placeholder, .form-textarea::placeholder { color: var(--text-muted); }
    .form-textarea { resize: vertical; min-height: 100px; }
    .form-input[readonly], .form-input:disabled { background: var(--n-100); color: var(--text-muted); cursor: not-allowed; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: var(--sp-4); }

    /* ── ALERT ───────────────────────────────────────────── */
    .alert {
        display: flex; align-items: center; gap: var(--sp-3);
        padding: var(--sp-3) var(--sp-4); border-radius: var(--r-lg);
        font-size: var(--text-base); font-weight: 600; margin-bottom: var(--sp-5);
    }
    .alert i { width: 16px; height: 16px; flex-shrink: 0; }
    .alert-success { background: var(--green-light); border: 1px solid var(--green-mid); color: var(--green-dark); }
    .alert-error   { background: var(--red-light);   border: 1px solid var(--red-mid);   color: var(--red-dark); }
    .alert-warning { background: var(--amber-light);  border: 1px solid var(--amber-mid);  color: var(--amber-dark); }
    .alert-info    { background: var(--blue-light);   border: 1px solid var(--blue-mid);   color: var(--blue-dark); }

    /* ── EMPTY STATE ─────────────────────────────────────── */
    .empty-state {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; padding: var(--sp-12) var(--sp-6); text-align: center;
    }
    .empty-state-icon {
        width: 72px; height: 72px; border-radius: 50%;
        background: var(--bg-subtle); border: 2px dashed var(--border);
        display: flex; align-items: center; justify-content: center; margin-bottom: var(--sp-5);
    }
    .empty-state-icon i { width: 28px; height: 28px; color: var(--text-muted); }
    .empty-state h3 { font-size: var(--text-lg); font-weight: 700; color: var(--text-primary); margin-bottom: var(--sp-2); }
    .empty-state p  { font-size: var(--text-base); color: var(--text-muted); max-width: 280px; font-family: var(--font-data); }

    /* ── FORM PAGE (create/edit) ─────────────────────────── */
    .form-page-wrap   { max-width: 600px; margin: 0 auto; }
    .back-link {
        display: inline-flex; align-items: center; gap: var(--sp-2);
        font-size: var(--text-base); font-weight: 600; color: var(--text-muted);
        margin-bottom: var(--sp-5); transition: color .15s;
    }
    .back-link:hover { color: var(--text-primary); }
    .back-link i { width: 14px; height: 14px; }
    .form-card {
        background: var(--bg-surface); border-radius: var(--r-xl);
        border: 1px solid var(--border); box-shadow: var(--shadow-sm);
        padding: var(--sp-7);
    }
    .button-group { display: flex; gap: var(--sp-3); margin-top: var(--sp-7); }
    .btn-save {
        flex: 1; padding: 12px; border-radius: var(--r-lg); border: none;
        font-size: var(--text-md); font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: var(--sp-2);
        font-family: var(--font-ui);
        background: linear-gradient(135deg, var(--accent), var(--accent-hover));
        color: white; box-shadow: 0 4px 14px var(--accent-shadow);
        transition: transform .2s, box-shadow .2s;
    }
    .btn-save:hover { transform: translateY(-1px); box-shadow: 0 8px 20px var(--accent-shadow); }
    .btn-save i { width: 15px; height: 15px; }
    .btn-back {
        flex: 1; padding: 12px; border-radius: var(--r-lg);
        border: 1.5px solid var(--border); font-size: var(--text-md); font-weight: 600;
        background: var(--bg-surface); color: var(--text-secondary);
        cursor: pointer; font-family: var(--font-ui);
        display: inline-flex; align-items: center; justify-content: center; gap: var(--sp-2);
        transition: background .15s;
    }
    .btn-back:hover { background: var(--bg-subtle); }

    /* ── MENU RANK ───────────────────────────────────────── */
    .menu-rank { display: flex; flex-direction: column; gap: var(--sp-3); }
    .menu-rank-item {
        display: flex; align-items: center; gap: var(--sp-3);
        padding: var(--sp-3); border-radius: var(--r-lg);
        background: var(--bg-subtle); border: 1px solid var(--border);
        transition: background .2s, transform .2s, box-shadow .2s;
    }
    .menu-rank-item:hover { background: var(--bg-surface); transform: translateY(-1px); box-shadow: var(--shadow-sm); }
    .rank-num {
        width: 28px; height: 28px; border-radius: var(--r-md);
        flex-shrink: 0; display: flex; align-items: center; justify-content: center;
        font-size: var(--text-sm); font-weight: 800; color: white;
        font-family: var(--font-data);
    }
    .rank-num.r1 { background: linear-gradient(135deg,#f59e0b,#f97316); }
    .rank-num.r2 { background: linear-gradient(135deg,#94a3b8,#64748b); }
    .rank-num.r3 { background: linear-gradient(135deg,#b45309,#92400e); }
    .rank-num.rn { background: var(--border); color: var(--text-muted); }
    .rank-img { width: 48px; height: 48px; object-fit: cover; border-radius: var(--r-lg); flex-shrink: 0; border: 1px solid var(--border); }
    .rank-name { font-size: var(--text-base); font-weight: 700; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .rank-sub  { font-size: var(--text-xs); color: var(--text-muted); margin-top: 2px; font-family: var(--font-data); }
    .rank-qty  { font-size: var(--text-md); font-weight: 800; color: var(--accent-dark); flex-shrink: 0; font-family: var(--font-data); }

    /* ── CONTENT GRID ────────────────────────────────────── */
    .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: var(--sp-5); margin-top: var(--sp-5); }

    /* ── DATATABLES OVERRIDE ─────────────────────────────── */
    .dataTables_wrapper { padding: 0; }
    .dataTables_filter,
    .dataTables_length  { display: none !important; }
    .dataTables_info {
        font-size: var(--text-base) !important; color: var(--text-muted) !important;
        padding: var(--sp-4) var(--sp-5) 0 !important;
        font-family: var(--font-data) !important;
    }
    .dataTables_paginate {
        padding: var(--sp-3) var(--sp-5) 0 !important;
        display: flex !important; align-items: center; gap: var(--sp-1);
    }
    .paginate_button {
        border-radius: var(--r-md) !important;
        border: 1px solid var(--border) !important;
        padding: 6px var(--sp-3) !important; margin: 0 2px !important;
        background: var(--bg-surface) !important; color: var(--text-muted) !important;
        font-size: var(--text-base) !important; font-weight: 600 !important;
        cursor: pointer !important; transition: all .15s !important;
        font-family: var(--font-data) !important;
    }
    .paginate_button:hover:not(.current):not(.disabled) {
        background: var(--blue-light) !important; color: var(--blue) !important; border-color: var(--blue-mid) !important;
    }
    .paginate_button.current,
    .paginate_button.current:hover {
        background: linear-gradient(135deg, var(--accent), var(--accent-hover)) !important;
        color: white !important; border-color: var(--accent) !important;
        box-shadow: 0 2px 8px var(--accent-shadow) !important;
    }
    .paginate_button.disabled,
    .paginate_button.disabled:hover { color: var(--n-300) !important; cursor: default !important; }

    /* ── RESPONSIVE ──────────────────────────────────────── */
    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .main { padding: calc(var(--header-h) + var(--sp-5)) var(--sp-4) var(--sp-12); }
        .form-grid-2 { grid-template-columns: 1fr; }
        .button-group { flex-direction: column; }
        .topbar { padding: 0 var(--sp-4); }
    }
    @media (max-width: 540px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .user-info { display: none; }
    }
    </style>

    @stack('styles')

{{-- ── LOGIN SUCCESS TOAST ── --}}
<style>
@keyframes toastIn {
    from { opacity: 0; transform: translateX(120px) scale(0.92); }
    to   { opacity: 1; transform: translateX(0)      scale(1); }
}
@keyframes toastOut {
    from { opacity: 1; transform: translateX(0)      scale(1); }
    to   { opacity: 0; transform: translateX(120px) scale(0.92); }
}
.login-toast-wrap {
    position: fixed;
    top: 80px; right: 24px;
    z-index: 99999;
    display: flex; flex-direction: column; gap: 10px;
    pointer-events: none;
}
.login-toast {
    pointer-events: auto;
    display: flex; align-items: flex-start; gap: 14px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #059669;
    border-radius: 14px;
    padding: 16px 18px;
    min-width: 300px; max-width: 360px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.12);
    animation: toastIn 0.4s cubic-bezier(0.34,1.56,0.64,1) both;
}
.login-toast.hide { animation: toastOut 0.35s ease forwards; }
.toast-icon {
    width: 38px; height: 38px; flex-shrink: 0;
    border-radius: 10px;
    background: #ecfdf5;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
}
.toast-body { flex: 1; }
.toast-title {
    font-size: 13px; font-weight: 700;
    color: #0f172a; margin-bottom: 3px;
}
.toast-msg {
    font-size: 12px; color: #64748b; line-height: 1.5;
}
.toast-close {
    background: none; border: none; cursor: pointer;
    color: #94a3b8; font-size: 16px; padding: 0;
    line-height: 1; flex-shrink: 0;
    transition: color .2s;
}
.toast-close:hover { color: #475569; }
.toast-progress {
    position: absolute; bottom: 0; left: 0;
    height: 3px; background: #059669;
    border-radius: 0 0 0 10px;
    animation: progress 4s linear forwards;
}
.login-toast { position: relative; overflow: hidden; }
@keyframes progress { from { width: 100%; } to { width: 0%; } }
</style>
</head>
<body>

@php
    $userAvatar  = auth()->user()->avatar ?? null;
    $avatarUrl   = $userAvatar ? asset('storage/' . $userAvatar) : null;
    $userInitial = strtoupper(substr(auth()->user()->name ?? 'A', 0, 1));
@endphp

{{-- ── TOPBAR ───────────────────────────────────────────────── --}}
<header class="topbar">
    <div class="topbar-left">
        <button class="menu-btn" onclick="toggleSidebar()" aria-label="Toggle menu">
            <i data-lucide="menu"></i>
        </button>
    </div>

    <div class="topbar-right">
        <div class="clock-pill" aria-live="polite">
            <i data-lucide="clock"></i>
            <span id="clock" style="font-variant-numeric:tabular-nums">--:--:--</span>
        </div>

        <div class="profile-wrap">
            <button class="user-btn" id="profileBtn" onclick="toggleDropdown()"
                    aria-expanded="false" aria-haspopup="true">
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
                        <div class="dp-name">{{ auth()->user()->name }}</div>
                        <div class="dp-role">{{ ucfirst(auth()->user()->role) }} · Online</div>
                    </div>
                </div>
                <div class="dp-body">
                    <a href="/admin/account/profil" class="dp-item" role="menuitem">
                        <div class="dp-ico"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                        Profil Saya
                    </a>
                    
                    <div class="dp-divider" role="separator"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dp-item danger" role="menuitem">
                            <div class="dp-ico"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></div>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ── SIDEBAR ──────────────────────────────────────────────── --}}
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

{{-- ── MAIN CONTENT ──────────────────────────────────────────── --}}
<main class="main">
    @yield('content')
</main>

{{-- ── SCRIPTS ───────────────────────────────────────────────── --}}
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

// Auto-dismiss alerts after 4 s
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 4000);
});

lucide.createIcons();
</script>

@stack('scripts')

{{-- ── LOGIN SUCCESS TOAST (muncul sekali setelah login) ── --}}
@if(session('login_success'))
<div class="login-toast-wrap" id="loginToastWrap">
    <div class="login-toast" id="loginToast">
        <div class="toast-icon">🎉</div>
        <div class="toast-body">
            <p class="toast-title">Berhasil Masuk!</p>
            <p class="toast-msg">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>.<br>Anda masuk sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>.</p>
        </div>
        <button class="toast-close" onclick="dismissToast()" title="Tutup">✕</button>
        <div class="toast-progress"></div>
    </div>
</div>
<script>
    function dismissToast() {
        const t = document.getElementById('loginToast');
        if (t) {
            t.classList.add('hide');
            setTimeout(() => {
                const w = document.getElementById('loginToastWrap');
                if (w) w.remove();
            }, 380);
        }
    }
    // Auto-dismiss setelah 4.5 detik
    setTimeout(dismissToast, 4500);
</script>
@endif

</body>
</html>