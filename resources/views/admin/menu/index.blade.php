<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Menu</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #F8F9FC; color: #1e293b; }

/* =======================
   TOPBAR — sama persis dashboard & laporan
======================= */
.topbar {
    position: fixed; top: 0; left: 0; right: 0; height: 80px;
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.95);
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 30px; z-index: 1000;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.topbar-left {
    display: flex; align-items: center; gap: 20px;
}
.topbar-left i {
    width: 24px; height: 24px; padding: 8px; border-radius: 12px;
    color: #475569; cursor: pointer; transition: all 0.3s ease;
}
.topbar-left i:hover {
    background: #f1f5f9; color: #1e293b; transform: scale(1.05);
}

/* =======================
   SIDEBAR — sama persis dashboard & laporan
======================= */
.sidebar {
    width: 240px; height: 100vh; position: fixed;
    background: linear-gradient(180deg, #0f172a, #1e1b4b);
    padding: 30px; padding-top: 100px;
    color: white; overflow-y: auto;
    transform: translateX(-100%);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 999; box-shadow: 4px 0 20px rgba(0,0,0,0.1);
    display: flex; flex-direction: column; gap: 8px;
}
.menu-section {
    font-size: 11px; letter-spacing: 1px;
    color: #a78bfa; margin: 18px 10px 8px; opacity: 0.7;
}
.sidebar.show { transform: translateX(0); }

/* =======================
   SIDEBAR MENU — sama persis dashboard & laporan
======================= */
.sidebar a,
.menu-parent {
    display: flex; align-items: center; gap: 15px;
    padding: 12px 14px; border-radius: 12px;
    text-decoration: none; color: #94a3b8;
    font-weight: 400; font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.sidebar i {
    width: 20px; height: 20px; stroke-width: 2.5; color: #c4b5fd;
}
.menu-parent { cursor: pointer; }
.menu-parent:hover,
.sidebar a:hover {
    background: rgba(255,255,255,0.06); color: white; transform: translateX(4px);
}
.sidebar a.active {
    background: rgba(139, 92, 246, 0.25); color: #c4b5fd;
    box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4);
}

/* =======================
   SUBMENU — sama persis dashboard & laporan
======================= */
.submenu {
    display: none; flex-direction: column;
    margin-left: 35px; gap: 5px;
    animation: slideDown 0.3s ease;
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.submenu-item {
    padding: 12px 16px; border-radius: 12px;
    font-size: 14px; color: #cbd5e1;
    text-decoration: none; transition: all 0.3s ease;
    display: block;
}
.submenu-item:hover { background: #334155; color: white; padding-left: 20px; }
.submenu-item.active { background: #3b82f6; color: white; }

/* ARROW — sama persis */
.arrow { margin-left: auto; transition: all 0.4s ease; }
.arrow.rotate { transform: rotate(180deg); }

/* =======================
   MAIN CONTENT
======================= */
.main { padding: 110px 30px 32px; max-width: 1100px; margin: 0 auto; }

/* =======================
   PAGE HEADER
======================= */
.page-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
}
.page-header h1 { font-size: 32px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
.page-header p  { font-size: 14px; color: #64748b; }

/* =======================
   TOOLBAR
======================= */
.toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.search-wrap { position: relative; display: flex; align-items: center; }
.search-wrap i { position: absolute; left: 11px; color: #9CA3AF; width: 15px; height: 15px; }
.search-input {
    padding: 10px 12px 10px 34px;
    border: 1.5px solid #e2e8f0; border-radius: 12px;
    font-family: 'Inter', sans-serif; font-size: 13px; color: #111827;
    outline: none; width: 210px; transition: border-color 0.2s;
    background: white;
}
.search-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.search-input::placeholder { color: #9CA3AF; }

.btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 12px;
    background: #6366f1; color: white; border: none;
    font-size: 13px; font-weight: 600; cursor: pointer;
    text-decoration: none; font-family: 'Inter', sans-serif;
    transition: all 0.2s; white-space: nowrap;
}
.btn-add:hover { background: #4f46e5; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.35); }
.btn-add:active { transform: scale(0.97); }

/* =======================
   ALERT
======================= */
.alert-success {
    background: #f0fdf4; color: #15803d;
    border: 1px solid #bbf7d0; border-radius: 12px;
    padding: 12px 16px; margin-bottom: 20px;
    font-size: 14px; font-weight: 500;
    display: flex; align-items: center; gap: 8px;
    transition: opacity 0.4s;
}

/* =======================
   CARD
======================= */
.card {
    background: white; border-radius: 20px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
    overflow: hidden;
}

/* =======================
   TABLE
======================= */
.tbl-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead { background: #f8fafc; }
th {
    padding: 16px 16px; text-align: left;
    font-size: 12px; font-weight: 600; color: #475569;
    text-transform: uppercase; letter-spacing: 0.6px;
    white-space: nowrap; border-bottom: 2px solid #e2e8f0;
}
td { padding: 16px 16px; font-size: 13.5px; color: #334155; border-bottom: 1px solid #f1f5f9; }
tbody tr { transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #f8fafc; }

.menu-img {
    width: 52px; height: 52px; object-fit: cover;
    border-radius: 10px; display: block; border: 1px solid #f0f0f0;
}
.menu-name { font-weight: 600; color: #111827; font-size: 14px; }
.menu-desc {
    font-size: 12px; color: #9ca3af; margin-top: 2px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px;
}

.cat-badge {
    display: inline-block; padding: 4px 10px; border-radius: 8px;
    background: #ede9fe; color: #5b21b6;
    font-size: 12px; font-weight: 600;
}

.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: 20px; font-size: 12px; font-weight: 600;
}
.status-active   { background: #f0fdf4; color: #15803d; }
.status-inactive { background: #f3f4f6; color: #6b7280; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; }
.dot-active   { background: #22c55e; }
.dot-inactive { background: #9ca3af; }

/* =======================
   ACTION BUTTONS
======================= */
.action-wrap { display: flex; align-items: center; gap: 6px; }
.act-btn {
    width: 34px; height: 34px; border-radius: 9px; border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s; text-decoration: none; flex-shrink: 0;
}
.act-btn:active { transform: scale(0.9); }
.act-edit { background: #eff6ff; color: #2563eb; border: 1.5px solid #dbeafe; }
.act-edit:hover { background: #dbeafe; border-color: #93c5fd; }
.act-delete { background: #fef2f2; color: #dc2626; border: 1.5px solid #fee2e2; }
.act-delete:hover { background: #fee2e2; border-color: #fca5a5; }

/* =======================
   MOBILE CARDS
======================= */
.mobile-list { display: none; padding: 14px; }

@media (max-width: 768px) {
    .tbl-wrap { display: none; }
    .mobile-list { display: flex; flex-direction: column; gap: 10px; }
    .search-input { width: 150px; }
    .main { padding: 100px 16px 24px; }
}

.m-card {
    background: white; border-radius: 14px;
    border: 1px solid #f0f0f0; padding: 12px;
    display: flex; gap: 12px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}
.m-card-img {
    width: 64px; height: 64px; object-fit: cover;
    border-radius: 10px; flex-shrink: 0; border: 1px solid #f0f0f0;
}
.m-card-body { flex: 1; min-width: 0; }
.m-card-name { font-size: 14px; font-weight: 700; color: #111827; }
.m-card-cat  { font-size: 11px; color: #6b7280; margin-top: 2px; }
.m-card-desc { font-size: 12px; color: #9ca3af; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.m-card-foot { display: flex; justify-content: space-between; align-items: center; margin-top: 8px; }
.m-price { font-size: 14px; font-weight: 700; color: #6366f1; }

/* =======================
   PAGINATION
======================= */
.pagination-wrap { padding: 16px 20px; border-top: 1px solid #f1f5f9; }

/* Empty state */
.empty-state { text-align: center; padding: 56px 20px; }
.empty-state p { color: #9ca3af; font-size: 14px; margin-top: 10px; }
</style>
</head>
<body>

<!-- ══ TOPBAR ══ -->
<div class="topbar">
    <div class="topbar-left">
        <i data-lucide="menu" onclick="toggleSidebar()"></i>
    </div>
</div>

<!-- ══ SIDEBAR ══ -->
<div class="sidebar" id="sidebar">

    <div class="menu-section">MAIN</div>
    <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i data-lucide="layout-dashboard"></i> Dashboard
    </a>

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

<!-- ══ MAIN ══ -->
<div class="main">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <h1>Manajemen Menu</h1>
            <p>Kelola semua menu yang tersedia di cafe</p>
        </div>
        <div class="toolbar">
            <form method="GET" action="/admin/menu">
                <div class="search-wrap">
                    <i data-lucide="search"></i>
                    <input type="text" name="search" class="search-input"
                        placeholder="Cari menu..."
                        value="{{ request('search') }}"
                        oninput="this.form.submit()">
                </div>
            </form>
            <a href="/admin/menu/create" class="btn-add">
                <i data-lucide="plus" style="width:15px;height:15px;"></i>
                Tambah Menu
            </a>
        </div>
    </div>

    <!-- ALERT -->
    @if(session('success'))
    <div class="alert-success" id="alertSuccess">
        <i data-lucide="check-circle" style="width:17px;height:17px;flex-shrink:0;"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- CARD -->
    <div class="card">

        <!-- DESKTOP TABLE -->
        <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th style="width:70px;">Foto</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($menus as $menu)
            <tr>
                <td style="color:#9CA3AF; font-size:13px;">
                    {{ $menus->firstItem() + $loop->index }}
                </td>
                <td>
                    <img class="menu-img"
                        src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://via.placeholder.com/60' }}"
                        alt="{{ $menu->name }}">
                </td>
                <td>
                    <p class="menu-name">{{ $menu->name }}</p>
                    <p class="menu-desc">{{ $menu->description ?? '-' }}</p>
                </td>
                <td>
                    <span class="cat-badge">{{ $menu->kategori->name ?? '-' }}</span>
                </td>
                <td style="font-weight:700; color:#6366f1;">
                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                </td>
                <td>
                    @if($menu->status)
                    <span class="status-badge status-active">
                        <span class="status-dot dot-active"></span> Aktif
                    </span>
                    @else
                    <span class="status-badge status-inactive">
                        <span class="status-dot dot-inactive"></span> Nonaktif
                    </span>
                    @endif
                </td>
                <td>
                    <div class="action-wrap" style="justify-content:center;">
                        <a href="/admin/menu/edit/{{ $menu->id }}" class="act-btn act-edit" title="Edit menu">
                            <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                        </a>
                        <form action="/admin/menu/delete/{{ $menu->id }}" method="POST">
                            @csrf
                            <button type="submit" class="act-btn act-delete" title="Hapus menu"
                                onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i data-lucide="utensils" style="width:40px;height:40px;color:#e5e7eb;"></i>
                        <p>Belum ada menu yang ditambahkan</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        </div>

        <!-- MOBILE CARDS -->
        <div class="mobile-list">
        @forelse($menus as $menu)
        <div class="m-card">
            <img class="m-card-img"
                src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://via.placeholder.com/64' }}"
                alt="{{ $menu->name }}">
            <div class="m-card-body">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:6px;">
                    <p class="m-card-name">{{ $menu->name }}</p>
                    @if($menu->status)
                    <span class="status-badge status-active" style="font-size:11px; padding:3px 8px; flex-shrink:0;">
                        <span class="status-dot dot-active"></span> Aktif
                    </span>
                    @else
                    <span class="status-badge status-inactive" style="font-size:11px; padding:3px 8px; flex-shrink:0;">
                        <span class="status-dot dot-inactive"></span> Nonaktif
                    </span>
                    @endif
                </div>
                <p class="m-card-cat">{{ $menu->kategori->name ?? '-' }}</p>
                <p class="m-card-desc">{{ $menu->description ?? '-' }}</p>
                <div class="m-card-foot">
                    <span class="m-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                    <div class="action-wrap">
                        <a href="/admin/menu/edit/{{ $menu->id }}" class="act-btn act-edit" title="Edit">
                            <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                        </a>
                        <form action="/admin/menu/delete/{{ $menu->id }}" method="POST">
                            @csrf
                            <button type="submit" class="act-btn act-delete" title="Hapus"
                                onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i data-lucide="utensils" style="width:40px;height:40px;color:#e5e7eb;"></i>
            <p>Belum ada menu yang ditambahkan</p>
        </div>
        @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="pagination-wrap">
            {{ $menus->links() }}
        </div>

    </div>
</div>

<script>
lucide.createIcons();

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
}


// Auto-dismiss alert
setTimeout(() => {
    const el = document.getElementById('alertSuccess');
    if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }
}, 3000);
</script>
</body>
</html>