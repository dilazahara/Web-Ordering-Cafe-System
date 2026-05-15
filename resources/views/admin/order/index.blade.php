<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orders</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; }

/* ══ TOPBAR ══ */
.topbar {
    position: fixed; top: 0; left: 0; right: 0; height: 80px;
    backdrop-filter: blur(20px);
    background: rgba(255,255,255,0.95);
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 30px; z-index: 1000;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.topbar-left { display: flex; align-items: center; gap: 20px; }
.topbar-left span { font-weight: 600; font-size: 18px; color: #1e293b; }
.menu-icon-btn {
    display: flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 12px;
    border: none; background: transparent; cursor: pointer;
    color: #475569; transition: all 0.3s ease;
}
.menu-icon-btn:hover { background: #f1f5f9; color: #1e293b; transform: scale(1.05); }
.topbar-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, #F97316, #FB923C);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 13px; font-weight: 700;
}

/* ══ SIDEBAR ══ */
.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    top: 0; left: 0;
    background: linear-gradient(180deg, #0f172a, #1e1b4b);
    padding: 30px 20px;
    padding-top: 100px;
    color: white;
    overflow-y: auto;           /* bisa di-scroll */
    overflow-x: hidden;
    transform: translateX(-100%);
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    z-index: 999;
    box-shadow: 4px 0 20px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    gap: 4px;

    /* scrollbar tipis */
    scrollbar-width: thin;
    scrollbar-color: rgba(139,92,246,0.3) transparent;
}
.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-track { background: transparent; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(139,92,246,0.35); border-radius: 99px; }

.sidebar.show { transform: translateX(0); }

/* overlay */
.sidebar-overlay {
    display: none; position: fixed; inset: 0; z-index: 998;
    background: rgba(0,0,0,0.35); backdrop-filter: blur(2px);
}
.sidebar-overlay.show { display: block; }

/* ══ SIDEBAR SECTIONS ══ */
.menu-section {
    font-size: 11px; letter-spacing: 1px; font-weight: 600;
    color: #a78bfa; margin: 18px 10px 6px; opacity: 0.8;
    text-transform: uppercase; flex-shrink: 0;
}

.sidebar a, .menu-parent {
    display: flex; align-items: center; gap: 14px;
    padding: 11px 14px; border-radius: 12px;
    text-decoration: none; color: #94a3b8;
    font-weight: 400; font-size: 14.5px;
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    flex-shrink: 0;
}
.sidebar a i, .menu-parent i {
    width: 18px; height: 18px; stroke-width: 2.2;
    color: #c4b5fd; flex-shrink: 0;
}
.menu-parent { cursor: pointer; }
.menu-parent:hover, .sidebar a:hover {
    background: rgba(255,255,255,0.06); color: white; transform: translateX(4px);
}
.sidebar a.active {
    background: rgba(139,92,246,0.25); color: #c4b5fd;
    box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4);
}

/* ══ SUBMENU ══ */
.submenu {
    display: none; flex-direction: column;
    margin-left: 32px; gap: 3px; margin-top: 3px;
    animation: slideDown 0.25s ease;
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.submenu-item {
    padding: 10px 14px; border-radius: 10px;
    font-size: 13.5px; color: #cbd5e1;
    text-decoration: none; transition: all 0.25s ease; display: block;
}
.submenu-item:hover { background: #334155; color: white; padding-left: 18px; }
.submenu-item.active { background: #3b82f6; color: white; }

.s-arrow {
    margin-left: auto; transition: all 0.35s ease;
    width: 16px !important; height: 16px !important;
}
.s-arrow.open { transform: rotate(180deg); }

/* ══ MAIN ══ */
.main { padding: 110px 30px 40px; }

/* ══ PAGE HEADER ══ */
.page-header { margin-bottom: 28px; }
.page-title { font-size: 32px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
.page-subtitle { color: #64748b; font-size: 15px; }

/* ══ CARD ══ */
.table-box {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
    border: 1px solid #f1f5f9;
    overflow: hidden;
}

/* ══ TABLE ══ */
.table-wrapper { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead { background: #f8fafc; }
th {
    padding: 16px 18px; text-align: left;
    font-size: 12px; font-weight: 700; color: #475569;
    text-transform: uppercase; letter-spacing: 0.6px;
    border-bottom: 2px solid #e2e8f0; white-space: nowrap;
}
td { padding: 16px 18px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
tbody tr { transition: background 0.15s; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: #f8fafc; }

/* ══ ORDER ID ══ */
.order-id { font-size: 15px; font-weight: 700; color: #6366f1; }

/* ══ MENU ITEM ══ */
.order-item {
    display: flex; justify-content: space-between; align-items: center;
    background: #f8fafc; padding: 10px 14px;
    border-radius: 12px; margin-bottom: 8px;
    border: 1px solid #f1f5f9;
}
.order-item:last-child { margin-bottom: 0; }
.item-name { font-size: 13.5px; font-weight: 600; color: #1e293b; }
.item-qty {
    width: 32px; height: 32px; border-radius: 9px;
    background: #ede9fe; color: #7c3aed;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; flex-shrink: 0; margin-left: 10px;
}

/* ══ STATUS ══ */
.status {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 13px; border-radius: 999px;
    font-size: 12px; font-weight: 700; white-space: nowrap;
}
.pending  { background: #fef3c7; color: #92400e; }
.process  { background: #dbeafe; color: #1e40af; }
.done     { background: #dcfce7; color: #166534; }

/* ══ TABLE TOTAL ══ */
.total-text { font-size: 14px; font-weight: 700; color: #6366f1; }

/* ══ EMPTY ══ */
.empty { text-align: center; padding: 60px 20px; color: #94a3b8; }
.empty i { width: 48px; height: 48px; color: #e2e8f0; }
.empty p { margin-top: 12px; font-size: 14px; }

/* ══ MOBILE ══ */
@media (max-width: 768px) {
    .main { padding: 100px 16px 30px; }
    .table-box { border-radius: 16px; }
    th, td { padding: 13px 12px; }
    .page-title { font-size: 26px; }
}
</style>
</head>
<body>

<!-- ══ TOPBAR ══ -->
<div class="topbar">
    <div class="topbar-left">
        <button class="menu-icon-btn" onclick="toggleSidebar()">
            <i data-lucide="menu" style="width:20px;height:20px;"></i>
        </button>
    </div>
</div>

<!-- ══ SIDEBAR OVERLAY ══ -->
<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- ══ SIDEBAR ══ -->
<div class="sidebar" id="sidebar">

    <div class="menu-section">MAIN</div>
    <a href="/admin/dashboard" class="{{ request()->is('admin') ? 'active' : '' }}">
        <i data-lucide="layout-dashboard"></i> Dashboard
    </a>
    <a href="/admin/order" class="{{ request()->is('admin/order') ? 'active' : '' }}">
        <i data-lucide="receipt"></i> Orders
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
    <div class="menu-parent" onclick="toggleMejaMenu()">
        <div style="display:flex;align-items:center;gap:14px;flex:1;">
            <i data-lucide="armchair"></i>
            <span>Meja</span>
        </div>
        <i data-lucide="chevron-down" class="s-arrow" id="arrowMeja"></i>
    </div>
    <div class="submenu" id="submenuMeja">
        <a href="/admin/meja" class="submenu-item {{ request()->is('admin/meja') ? 'active' : '' }}">Data Meja</a>
        <a href="/admin/meja/monitor" class="submenu-item {{ request()->is('admin/meja/monitor') ? 'active' : '' }}">Monitor Meja</a>
    </div>
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
    <a href="/admin/settings" class="{{ request()->is('admin/settings*') ? 'active' : '' }}">
        <i data-lucide="settings"></i> Settings
    </a>

</div>

<!-- ══ MAIN ══ -->
<div class="main">

    <div class="page-header">
        <div class="page-title">Daftar Pesanan</div>
        <div class="page-subtitle">Kelola semua pesanan pelanggan dengan mudah</div>
    </div>

    <div class="table-box">
        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Meja</th>
                    <th>Pesanan</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

            @forelse($orders as $order)
            <tr>
                <td>
                    <div class="order-id">{{ $order->queue_number }}</div>
                </td>
                <td>Meja {{ $order->table_number ?? '-' }}</td>
                <td>
                    @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="item-name">{{ $item->menu->name ?? 'Menu' }}</div>
                        <div class="item-qty">{{ $item->qty }}x</div>
                    </div>
                    @endforeach
                </td>
                <td>
                    <span class="total-text">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="status {{ $order->status }}">
                        @if($order->status == 'pending')   ⏳ Pending
                        @elseif($order->status == 'process') 🍳 Process
                        @elseif($order->status == 'done')   ✅ Done
                        @else {{ ucfirst($order->status) }}
                        @endif
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty">
                        <i data-lucide="inbox"></i>
                        <p>Belum ada pesanan</p>
                    </div>
                </td>
            </tr>
            @endforelse

            </tbody>
        </table>
        </div>
    </div>

</div>

<script>
lucide.createIcons();

function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('overlay').classList.toggle('show');
}
function closeSidebar(){
    document.getElementById('sidebar').classList.remove('show');
    document.getElementById('overlay').classList.remove('show');
}
function toggleMejaMenu(){
    const menu  = document.getElementById('submenuMeja');
    const arrow = document.getElementById('arrowMeja');
    menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    arrow.classList.toggle('open');
}
</script>
</body>
</html>