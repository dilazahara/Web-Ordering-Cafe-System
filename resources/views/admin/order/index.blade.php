<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Daftar Pesanan</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

{{-- DataTables CSS & Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
:root {
  --surface: #ffffff; --surface-2: #f8fafc;
  --border: #e2e8f0; --border-strong: #cbd5e1;
  --text-primary: #0f172a; --text-secondary: #64748b; --text-muted: #94a3b8;
  --accent: #7c3aed; --accent-bg: #f5f3ff; --accent-text: #5b21b6;
  --radius-lg: 18px;
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
  --shadow: 0 4px 20px rgba(0,0,0,0.03);
}

body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: var(--text-primary); }

/* ══ TOPBAR ══ */
.topbar {
    position: fixed; top: 0; left: 0; right: 0; height: 80px;
    backdrop-filter: blur(20px); background: rgba(255,255,255,0.95);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 30px; z-index: 1000; box-shadow: var(--shadow-sm);
}
.topbar-left { display: flex; align-items: center; gap: 20px; }
.topbar-left span { font-weight: 600; font-size: 18px; color: var(--text-primary); }
.menu-icon-btn {
    display: flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 12px;
    border: none; background: transparent; cursor: pointer;
    color: var(--text-secondary); transition: all 0.3s ease;
}
.menu-icon-btn:hover { background: var(--surface-2); color: var(--text-primary); transform: scale(1.05); }

/* ══ SIDEBAR ══ */
.sidebar {
    width: 240px; height: 100vh; position: fixed; top: 0; left: 0;
    background: linear-gradient(180deg, #0f172a, #1e1b4b);
    padding: 30px; padding-top: 100px;
    color: white; overflow-y: auto;
    transform: translateX(-100%);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 999; box-shadow: 4px 0 20px rgba(0,0,0,0.1);
    display: flex; flex-direction: column; gap: 8px;
}
.menu-section { font-size: 11px; letter-spacing: 1px; color: #a78bfa; margin: 18px 10px 8px; opacity: 0.7; font-weight: 600; }
.sidebar.show { transform: translateX(0); }
.sidebar a {
    display: flex; align-items: center; gap: 15px; padding: 12px 14px; border-radius: 12px;
    text-decoration: none; color: #94a3b8; font-weight: 500; font-size: 14px; transition: all 0.3s;
}
.sidebar i { width: 18px; height: 18px; stroke-width: 2.2; flex-shrink: 0; }
.sidebar a:hover { background: rgba(255,255,255,0.08); color: white; transform: translateX(4px); }
.sidebar a.active {
    background: rgba(139, 92, 246, 0.25); color: #c4b5fd;
    box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4);
}

.overlay { display: none; position: fixed; inset: 0; z-index: 998; background: rgba(0,0,0,0.35); backdrop-filter: blur(2px); }
.overlay.show { display: block; }

/* ══ MAIN ══ */
.main { padding: 110px 30px 40px; max-width: 1280px; margin: 0 auto; }
.page-header { margin-bottom: 28px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 15px; }
.page-title { font-size: 26px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
.page-subtitle { color: var(--text-secondary); font-size: 14px; margin-top: 4px; }

/* ══ BOX & TABLE STYLE (Sinkron dengan Dashboard) ══ */
.box {
    background: white; border-radius: var(--radius-lg); border: 1px solid #f1f5f9;
    box-shadow: 0 2px 10px rgba(0,0,0,.05); overflow: hidden;
}

.rtable { width: 100%; border-collapse: collapse; }
.rtable thead th {
    background: #f8fafc; padding: 14px 16px; font-size: 11.5px;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px;
    color: #64748b; text-align: left; border-bottom: 1px solid #f1f5f9;
}
.rtable tbody tr { border-bottom: 1px solid #f8fafc; transition: background .15s; background: white; }
.rtable tbody tr:hover { background: #fafbff; }
.rtable td { padding: 15px 16px; font-size: 13.5px; color: #334155; vertical-align: middle; }

/* Status Badges */
.badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; }
.badge-dot { width: 6px; height: 6px; border-radius: 50%; }
.badge.pending  { background: #fef3c7; color: #92400e; }
.badge.pending .badge-dot  { background: #f59e0b; }
.badge.proses   { background: #dbeafe; color: #1e40af; }
.badge.proses .badge-dot   { background: #3b82f6; }
.badge.selesai  { background: #dcfce7; color: #15803d; }
.badge.selesai .badge-dot  { background: #22c55e; }
.badge.diantar  { background: #f0fdf4; color: #15803d; }
.badge.diantar .badge-dot  { background: #22c55e; }

/* Datatable Custom Style */
.dataTables_wrapper { padding: 0 0 18px; }
.dataTables_filter { padding: 18px 22px 0; float: right; }
.dataTables_length { padding: 18px 22px 0; float: left; }
.dataTables_filter input, .dataTables_length select { border: 1px solid var(--border); border-radius: 8px; padding: 6px 10px; font-size: 13px; outline: none; }
.dataTables_filter input:focus, .dataTables_length select:focus { border-color: var(--accent); }

.dataTables_info { font-size: 13px !important; color: #64748b !important; padding: 14px 22px 0 !important; font-weight: 500; }
.dataTables_paginate { padding: 12px 22px 0 !important; display: flex !important; align-items: center; gap: 4px; }
.paginate_button {
    border-radius: 10px !important; border: 1px solid #e2e8f0 !important;
    padding: 6px 13px !important; margin: 0 2px !important; background: white !important;
    color: #475569 !important; font-size: 13px !important; font-weight: 600 !important;
    cursor: pointer !important; transition: all .15s !important;
}
.paginate_button:hover:not(.current):not(.disabled) { background: #eff6ff !important; color: #2563eb !important; border-color: #bfdbfe !important; }
.paginate_button.current, .paginate_button.current:hover {
    background: linear-gradient(135deg, #7c3aed, #6d28d9) !important;
    color: white !important; border-color: #7c3aed !important;
    box-shadow: 0 2px 8px rgba(124,58,237,0.3) !important;
}
.paginate_button.disabled, .paginate_button.disabled:hover { color: #cbd5e1 !important; cursor: default !important; }
.dataTables_scrollBody::-webkit-scrollbar { height: 5px; }
.dataTables_scrollBody::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 99px; }
.dataTables_scrollBody::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

@media (max-width: 768px) { .main { padding: 100px 16px 30px; } }
</style>
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <button class="menu-icon-btn" onclick="toggleSidebar()">
            <i data-lucide="menu"></i>
        </button>
    </div>
</div>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

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

    <div class="page-header">
        <div>
            <div class="page-title">Monitoring Pesanan</div>
            <div class="page-subtitle">Pantau aktivitas seluruh pesanan restoran secara real-time</div>
        </div>
    </div>

    {{-- Tabel Sinkron Dengan Dashboard --}}
    <div class="box">
        <div style="overflow-x:auto;">
            <table class="rtable" id="ordersTable" style="min-width:800px;">
                <thead>
                    <tr>
                        <th style="padding-left:22px;">ID Order</th>
                        <th>Meja</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                <tr>
<td style="padding-left:22px;">
                        <span style="font-weight:800; color:#7c3aed; font-size:13px;">
                            {{ $order->queue_number ?: 'A-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        <span style="background:#f1f5f9; padding:4px 10px; border-radius:8px; font-size:12px; font-weight:700; color:#475569;">
                            {{ $order->table_number ? 'Meja '.$order->table_number : 'Take Away' }}
                        </span>
                    </td>
                    <td>
                        @if($order->items && $order->items->count() > 0)
                            @foreach($order->items->take(2) as $item)
                                <span style="font-size:13px;">
                                    {{ $item->qty ?? $item->quantity ?? 1 }}x
                                    {{ $item->name ?? $item->menu->name ?? $item->menu->nama ?? '-' }}
                                    @if(!$loop->last), @endif
                                </span>
                            @endforeach
                            @if($order->items->count() > 2)
                                <span style="color:#94a3b8; font-size:12px;">+{{ $order->items->count()-2 }} lagi</span>
                            @endif
                        @else
                            <span style="font-size:13px; color:#94a3b8;">-</span>
                        @endif
                    </td>
                    <td style="font-weight:600; font-size:13px; color:#0f172a;">
                        Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($order->payment_method === 'cash')
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">💵 Cash</span>
                        @elseif($order->payment_method === 'qris')
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700; background:#ede9fe; color:#5b21b6; border:1px solid #ddd6fe;">📱 QRIS</span>
                        @else
                            <span style="font-size:12px; color:#64748b; text-transform:capitalize;">{{ $order->payment_method ?? 'Tunai' }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            if ($order->status === 'pending') {
                                $bg = 'pending'; $text = 'Menunggu Bayar';
                            } elseif ($order->status === 'paid') {
                                $bg = 'selesai'; $text = 'Lunas, Belum Diproses';
                            } elseif ($order->status === 'process') {
                                $bg = 'proses'; $text = 'Sedang Dimasak';
                            } elseif (in_array($order->status, ['done', 'delivered'])) {
                                $bg = 'diantar'; $text = 'Selesai Diantar';
                            } else {
                                $bg = 'pending'; $text = ucfirst($order->status);
                            }
                        @endphp
                        <span class="badge {{ $bg }}">
                            <span class="badge-dot"></span>
                            {{ $text }}
                        </span>
                    </td>
                    <td style="font-size:12px; color:#94a3b8; white-space:nowrap;">
                        {{ $order->created_at->format('H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:#94a3b8;">
                        <i data-lucide="inbox" style="margin:0 auto 10px; display:block; width:40px; height:40px; color:#cbd5e1;"></i>
                        <span style="font-weight:600; font-size:14px; color:#64748b;">Belum ada pesanan</span>
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

$(document).ready(function () {
    $('#ordersTable').DataTable({
        scrollX: true,
        pageLength: 15,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pesanan",
            paginate: { previous: "Sebelumnya", next: "Selanjutnya" },
            emptyTable: "Belum ada pesanan aktif hari ini"
        }
    });
});
</script>
</body>
</html>