<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan - Clean Modern</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script src="https://unpkg.com/lucide@latest"></script>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
    color: #1e293b;
}

/* =======================
   HEADER - CLEAN GLASS
======================= */
.topbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 80px;
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.95);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 1000;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.topbar-left i {
    width: 24px;
    height: 24px;
    padding: 8px;
    border-radius: 12px;
    color: #475569;
    cursor: pointer;
    transition: all 0.3s ease;
}

.topbar-left i:hover {
    background: #f1f5f9;
    color: #1e293b;
    transform: scale(1.05);
}

/* =======================
   SIDEBAR — sama persis menu
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
   SIDEBAR MENU — sama persis menu
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
   SUBMENU — sama persis menu
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
.main {
    margin-left: 0;
    padding: 120px 30px 30px;
    min-height: 100vh;
}

/* =======================
   PAGE HEADER
======================= */
.header {
    margin-bottom: 30px;
}

.header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

/* =======================
   FILTER BAR - MODERN
======================= */
.filter-box {
    background: white;
    padding: 25px 30px;
    border-radius: 20px;
    box-shadow: 0 4px 6px -1px rgba(0, 0,0, 0.1);
    border: 1px solid #f1f5f9;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

select, input[type="date"] {
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    background: white;
    transition: all 0.3s ease;
    min-width: 160px;
}

select:focus, input[type="date"]:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn {
    padding: 12px 20px;
    border: none;
    border-radius: 12px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-filter {
    background: #3b82f6;
    color: white;
}

.btn-filter:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-pdf {
    background: #ef4444;
    color: white;
}

.btn-pdf:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* =======================
   TABLE CONTAINER
======================= */
.table-box {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 4px 6px -1px rgba(0, 0,0, 0.1);
    border: 1px solid #f1f5f9;
    overflow: hidden;
}

.table-title {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* =======================
   TABLE - ENHANCED
======================= */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

thead {
    background: #f8fafc;
}

th {
    text-align: left;
    font-weight: 600;
    color: #475569;
    padding: 18px 16px;
    font-size: 14px;
    border-bottom: 2px solid #e2e8f0;
}

td {
    padding: 18px 16px;
    font-size: 14px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
}

tbody tr {
    transition: all 0.2s ease;
}

tbody tr:hover {
    background: #f8fafc;
    transform: scale(1.01);
}

.total {
    font-weight: 700;
    background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 100%);
    font-size: 16px;
}

.total td {
    padding: 20px 16px;
    border-top: 3px solid #3b82f6;
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

.empty-state i {
    width: 64px;
    height: 64px;
    stroke-width: 1;
    opacity: 0.5;
    margin-bottom: 20px;
}

/* =======================
   RESPONSIVE
======================= */
@media (max-width: 768px) {
    .sidebar { width: 260px; }
    .main { padding: 110px 20px 20px; }
    .filter-box { flex-direction: column; align-items: stretch; }
    .filter-group { width: 100%; justify-content: space-between; }
    select, input[type="date"] { min-width: auto; flex: 1; }
    .table-box { padding: 20px; }
    
    table {
        font-size: 13px;
    }
    
    th, td {
        padding: 12px 8px;
    }
}

@media (max-width: 480px) {
    .sidebar { width: 100%; }
    .topbar { padding: 0 20px; }
}
</style>
</head>

<body>

<div class="topbar">
    <div class="topbar-left">
        <i data-lucide="menu" onclick="toggleSidebar()"></i>
        <span style="font-weight: 600; font-size: 20px;">Laporan</span>
    </div>
</div>

<div class="sidebar" id="sidebar">

    <div class="menu-section">MAIN</div>
    <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i data-lucide="layout-dashboard"></i> Dashboard
    </a>
    
    <a href="/admin/order" class="{{ request()->is('admin/order*') ? 'active' : '' }}">
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

<div class="main">
    <div class="header">
        <h1>Laporan Penjualan</h1>
        <p style="color: #64748b; font-size: 16px;">Filter dan export data penjualan dengan mudah</p>
    </div>

    <div class="filter-box">
        <form method="GET" action="/admin/laporan" style="display: flex; gap: 15px; flex: 1; flex-wrap: wrap; align-items: center;">
            <div class="filter-group">
                <label style="font-weight: 500; color: #475569; font-size: 14px; white-space: nowrap;">Filter:</label>
                <select name="filter">
                    <option value="">Semua Periode</option>
                    <option value="hari" {{ request('filter')=='hari' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="bulan" {{ request('filter')=='bulan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun" {{ request('filter')=='tahun' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>

            <div class="filter-group">
                <label style="font-weight: 500; color: #475569; font-size: 14px; white-space: nowrap;">Tanggal:</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}">
            </div>

            <button type="submit" class="btn btn-filter">
                <i data-lucide="search"></i>
                Filter Data
            </button>
        </form>

        <a href="{{ route('admin.laporan.pdf') }}" class="btn btn-pdf">
            <i data-lucide="file-down"></i>
            Export PDF
        </a>
    </div>

    <div class="table-box">
        <div class="table-title">
            <i data-lucide="table"></i>
            Data Laporan
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Order ID</th>
                    <th>Meja</th>
                    <th>Total</th>
                    <th>Metode Bayar</th>
                </tr>
            </thead>

            <tbody>
            @php $totalSemua = 0; @endphp

            @foreach($data as $index => $item)
            @php $totalSemua += $item['total'] ?? 0; @endphp

            <tr>
                <td style="font-weight: 600;">{{ $index + 1 }}</td>
                <td>{{ $item['tanggal'] ?? '-' }}</td>
                <td style="font-weight: 600; color: #3b82f6;">{{ $item['kode'] ?? '-' }}</td>
                <td>{{ $item['meja'] ?? '-' }}</td>
                <td style="font-weight: 600; color: #059669;">Rp {{ number_format($item['total'] ?? 0, 0, ',', '.') }}</td>
                <td>
                    <span style="padding: 4px 12px; background: #f1f5f9; border-radius: 20px; font-size: 12px; font-weight: 500;">
                        {{ $item['metode'] ?? '-' }}
                    </span>
                </td>
            </tr>
            @endforeach

            @if(count($data) == 0)
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i data-lucide="inbox"></i>
                        <h3 style="margin-bottom: 8px; color: #475569;">Belum ada data</h3>
                        <p>Gunakan filter di atas untuk melihat laporan penjualan</p>
                    </div>
                </td>
            </tr>
            @endif

            <tr class="total">
                <td colspan="4" style="text-align: right; font-size: 16px;">TOTAL PENJUALAN</td>
                <td colspan="2" style="color: #059669; font-size: 20px;">
                    Rp {{ number_format($totalSemua, 0, ',', '.') }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
lucide.createIcons();

function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("show");
}

function toggleMejaMenu(){
    const menu = document.getElementById('submenuMeja');
    const arrow = document.getElementById('arrowMeja');
    if(menu && arrow) {
        menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        arrow.classList.toggle('rotate');
    }
}
</script>

</body>
</html>