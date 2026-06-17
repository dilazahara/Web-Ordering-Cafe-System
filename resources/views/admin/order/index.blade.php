@extends('layouts.admin')

@section('title', 'Admin — Daftar Pesanan')

@push('styles')
<style>
/* ════ CSS PREMIUM MODERN — MANAJEMEN PESANAN ════ */

/* Dashboard Layout Wrapper */
.dashboard-wrap {
    display: flex;
    flex-direction: column;
    gap: 24px;
    margin-bottom: 32px;
}

/* 1. HEADER HALAMAN MODERN */
.premium-header {
    position: relative;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid #e2e8f0;
    padding: 28px 32px;
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.02);
}
.premium-header::after {
    content: '';
    position: absolute;
    top: -40px;
    right: -40px;
    width: 140px;
    height: 140px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.premium-header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}
.header-icon-box {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: var(--accent, #6366f1);
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.12);
}
.header-icon-box svg {
    width: 24px;
    height: 24px;
    stroke-width: 2.2;
}
.page-title h1 {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.5px;
    margin: 0;
}
.page-title p {
    font-size: 13.5px;
    color: #64748b;
    margin-top: 4px;
    font-weight: 500;
}

/* 2. TOOLBAR MODERN CARD & FILTER BARU */
.toolbar-wrap {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.01);
}
.toolbar-left {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    font-size: 13.5px;
    color: #475569;
    font-weight: 600;
}
.filter-group {
    display: flex;
    align-items: center;
    gap: 8px;
}
.filter-group label { white-space: nowrap; }
.toolbar-left select, 
.toolbar-left input[type="date"] {
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    background: #f8fafc;
    outline: none;
    cursor: pointer;
    transition: all 0.2s ease;
}
.toolbar-left select:focus, 
.toolbar-left input[type="date"]:focus {
    border-color: var(--accent, #6366f1);
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}
.toolbar-left input[type="date"] {
    text-transform: uppercase; /* Untuk tampilan datepicker yang rapi */
}

/* Tombol Refresh Baru */
#refreshBtn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.01);
}
#refreshBtn:hover {
    background: #f1f5f9;
    color: var(--accent, #6366f1);
    border-color: #cbd5e1;
}
#refreshBtn svg {
    width: 16px;
    height: 16px;
    stroke-width: 2.2;
}

.toolbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
}
.search-input-wrap {
    position: relative;
}
.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: #94a3b8;
    pointer-events: none;
    transition: color 0.2s;
}
.search-input-wrap input[type="text"] {
    width: 300px;
    padding: 10px 16px 10px 42px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 500;
    color: #0f172a;
    background: #ffffff;
    outline: none;
    transition: all 0.2s ease;
}
.search-input-wrap input[type="text"]:focus {
    border-color: var(--accent, #6366f1);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}
.search-input-wrap input[type="text"]:focus + .search-icon {
    color: var(--accent, #6366f1);
}
.search-input-wrap input[type="text"]::placeholder {
    color: #94a3b8;
}

#searchClearBtn {
    display: none;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
    gap: 6px;
}
#searchClearBtn:hover {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fca5a5;
}

/* 3. TABEL PREMIUM CONTAINER */
.box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.02);
    overflow: hidden;
}
.table-wrap {
    overflow-x: auto;
    width: 100%;
}
.rtable {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.rtable thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background: #f8fafc;
    padding: 16px 20px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #64748b;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}
.rtable tbody tr {
    transition: all 0.15s ease;
    background: #ffffff;
}
.rtable tbody tr:nth-child(even) {
    background: #fafbfe;
}
.rtable tbody tr:hover {
    background: #f1f5ff;
}
.rtable td {
    padding: 16px 20px;
    font-size: 13.5px;
    color: #334155;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}
.rtable tbody tr:last-child td {
    border-bottom: none;
}

/* 4. METODE BAYAR & BADGE STATUS MODERN */
.payment-pill {
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 11.5px;
    font-weight: 700;
    line-height: 1;
    border: 1px solid transparent;
}
.badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}

/* Theme Status Variant */
.badge.pending {
    background: #fffbeb;
    color: #b45309;
    border-color: #fde68a;
}
.badge.pending .badge-dot { background: #d97706; animation: status-pulse 2s infinite; }

.badge.selesai {
    background: #ecfdf5;
    color: #047857;
    border-color: #a7f3d0;
}
.badge.selesai .badge-dot { background: #10b981; }

.badge.proses {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #bfdbfe;
}
.badge.proses .badge-dot { background: #3b82f6; animation: status-pulse 1.4s infinite; }

.badge.diantar {
    background: #f5f3ff;
    color: #6d28d9;
    border-color: #ddd6fe;
}
.badge.diantar .badge-dot { background: #8b5cf6; }

@keyframes status-pulse {
    0% { transform: scale(0.85); opacity: 0.5; }
    50% { transform: scale(1.2); opacity: 1; }
    100% { transform: scale(0.85); opacity: 0.5; }
}

/* 5. EMPTY STATE MODERN */
.empty-state {
    padding: 54px 32px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
}
.empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 74px;
    height: 74px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #94a3b8;
    border-radius: 50%;
    margin-bottom: 4px;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
}
.empty-state-icon svg {
    width: 32px;
    height: 32px;
    stroke-width: 1.6;
}
.empty-state h3 {
    font-size: 17px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.empty-state p {
    font-size: 13.5px;
    color: #64748b;
    max-width: 340px;
    margin: 0 auto;
    line-height: 1.5;
}

/* 6. FOOTER TABEL & PAGINATION CUSTOM */
.table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding: 18px 24px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}
.table-info {
    font-size: 13.5px;
    color: #64748b;
    font-weight: 600;
}
.pagination-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
}
.pg-btn {
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
    border-radius: 10px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
}
.pg-btn:hover:not(.active):not(:disabled) {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #94a3b8;
    transform: translateY(-1px);
}
.pg-btn.active {
    background: var(--accent, #6366f1);
    color: #ffffff;
    border-color: var(--accent, #6366f1);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
}
.pg-btn:disabled {
    color: #cbd5e1;
    border-color: #e2e8f0;
    background: #f8fafc;
    cursor: not-allowed;
    transform: none !important;
}

@media (max-width: 1024px) {
    .toolbar-left { flex-direction: column; align-items: flex-start; }
}

@media (max-width: 768px) {
    .toolbar-wrap  { flex-direction: column; align-items: flex-start; }
    .toolbar-right { width: 100%; }
    .search-input-wrap { width: 100%; }
    .search-input-wrap input[type="text"] { width: 100%; }
    .table-footer  { flex-direction: column; align-items: flex-start; }
    .filter-group { width: 100%; justify-content: space-between; flex-wrap: wrap; }
    .filter-group select, .filter-group input[type="date"] { flex-grow: 1; }
    #refreshBtn { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="dashboard-wrap">

    {{-- ══ 1. HEADER HALAMAN MODERN ══ --}}
    <div class="premium-header">
        <div class="premium-header-left">
            <div class="header-icon-box">
                <i data-lucide="clipboard-list"></i>
            </div>
            <div class="page-title">
                <h1>Manajemen Pesanan</h1>
                <p>Pantau status dan proses seluruh pesanan restoran secara real-time</p>
            </div>
        </div>
    </div>

    {{-- ══ 2. TOOLBAR CARD MODERN ══ --}}
    <div class="toolbar-wrap">
        <div class="toolbar-left">
            {{-- Filter Data --}}
            <div class="filter-group">
                <label for="perPageSelect">Tampilkan</label>
                <select id="perPageSelect">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <label>data</label>
            </div>

            {{-- Filter Status --}}
            <div class="filter-group">
                <select id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu Bayar</option>
                    <option value="proses">Diproses</option>
                    <option value="diantar">Siap Diantar</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            {{-- Filter Tanggal --}}
            <div class="filter-group">
                <input type="date" id="dateFrom" title="Tanggal Dari">
                <span style="color:#94a3b8; font-weight:700;">-</span>
                <input type="date" id="dateTo" title="Tanggal Sampai">
            </div>

            {{-- Tombol Refresh --}}
            <button id="refreshBtn" title="Reset Semua Filter">
                <i data-lucide="refresh-cw"></i> Refresh
            </button>
        </div>

        <div class="toolbar-right">
            <div class="search-input-wrap">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" id="searchOrder" placeholder="Cari ID order, meja, status…" autocomplete="off">
            </div>
            <button id="searchClearBtn" onclick="clearSearch()">✕ Reset</button>
        </div>
    </div>

    {{-- ══ 3. TABEL PREMIUM ══ --}}
    <div class="box">
        <div class="table-wrap">
            <table class="rtable" id="ordersTable" style="min-width:800px;">
                <thead>
                    <tr>
                        <th style="padding-left:24px;">ID Order</th>
                        <th>Meja</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th style="padding-right:24px;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                {{-- Data Attribute untuk mempermudah logic JS filter tanpa mengubah struktur tabel/logic backend --}}
                <tr data-status="{{ $order->status }}" data-date="{{ $order->created_at->format('Y-m-d') }}">
                    <td style="padding-left:24px;">
                        <span style="font-weight:800; color:var(--accent, #6366f1); font-size:13.5px;">
                            {{ $order->queue_number ?: 'A-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        <span style="background:#f1f5f9; padding:6px 12px; border-radius:10px; font-size:12.5px; font-weight:700; color:#475569; border:1px solid #e2e8f0;">
                            {{ $order->table_number ? 'Meja '.$order->table_number : 'Take Away' }}
                        </span>
                    </td>
                    <td>
                        @if($order->items && $order->items->count() > 0)
                            @foreach($order->items->take(2) as $item)
                                <span style="font-size:13.5px; font-weight:500; color:#1e293b;">
                                    {{ $item->qty ?? $item->quantity ?? 1 }}x
                                    {{ $item->name ?? $item->menu->name ?? $item->menu->nama ?? '-' }}
                                    @if(!$loop->last), @endif
                                </span>
                            @endforeach
                            @if($order->items->count() > 2)
                                <span style="color:#94a3b8; font-size:11.5px; font-weight:700; background:#f1f5f9; padding:3px 7px; border-radius:6px; margin-left:4px;">+{{ $order->items->count()-2 }} lagi</span>
                            @endif
                        @else
                            <span style="font-size:13.5px; color:#94a3b8;">-</span>
                        @endif
                    </td>
                    <td style="font-weight:700; font-size:14px; color:#0f172a;">
                        Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}
                    </td>
                    <td>
                        @php
                            $pmLabels = [
                                'cash'        => ['icon'=>'💵','label'=>'Cash',         'bg'=>'#fffbeb','color'=>'#92400e','border'=>'#fde68a'],
                                'qris'        => ['icon'=>'📱','label'=>'QRIS',         'bg'=>'#f5f3ff','color'=>'#5b21b6','border'=>'#ddd6fe'],
                                'gopay'       => ['icon'=>'💚','label'=>'GoPay',        'bg'=>'#f0fdf4','color'=>'#065f46','border'=>'#a7f3d0'],
                                'ovo'         => ['icon'=>'💜','label'=>'OVO',          'bg'=>'#f5f3ff','color'=>'#4c1d95','border'=>'#ddd6fe'],
                                'dana'        => ['icon'=>'💙','label'=>'DANA',         'bg'=>'#eff6ff','color'=>'#1e40af','border'=>'#bfdbfe'],
                                'shopeepay'   => ['icon'=>'🧡','label'=>'ShopeePay',   'bg'=>'#fff7ed','color'=>'#9a3412','border'=>'#fed7aa'],
                                'bca'         => ['icon'=>'🏦','label'=>'VA BCA',      'bg'=>'#eff6ff','color'=>'#1e40af','border'=>'#bfdbfe'],
                                'bni'         => ['icon'=>'🏦','label'=>'VA BNI',      'bg'=>'#fff7ed','color'=>'#9a3412','border'=>'#fed7aa'],
                                'bri'         => ['icon'=>'🏦','label'=>'VA BRI',      'bg'=>'#f0fdf4','color'=>'#065f46','border'=>'#a7f3d0'],
                                'mandiri'     => ['icon'=>'🏦','label'=>'Mandiri',     'bg'=>'#eff6ff','color'=>'#1e40af','border'=>'#bfdbfe'],
                                'permata'     => ['icon'=>'🏦','label'=>'Permata',     'bg'=>'#fdf2f8','color'=>'#831843','border'=>'#fbcfe8'],
                                'credit_card' => ['icon'=>'💳','label'=>'Kartu Kredit','bg'=>'#eef2ff','color'=>'#3730a3','border'=>'#c7d2fe'],
                                'midtrans'    => ['icon'=>'💳','label'=>'Midtrans',    'bg'=>'#eef2ff','color'=>'#3730a3','border'=>'#c7d2fe'],
                            ];
                            $pm = $pmLabels[$order->payment_method] ?? [
                                'icon'=>'💳','label'=>strtoupper($order->payment_method ?? '-'),
                                'bg'=>'#f1f5f9','color'=>'#475569','border'=>'#e2e8f0'
                            ];
                        @endphp
                        <span class="payment-pill" style="display:inline-flex; align-items:center; gap:5px; padding:5px 10px; border-radius:8px; font-size:11.5px; font-weight:700; background:{{ $pm['bg'] }}; color:{{ $pm['color'] }}; border:1px solid {{ $pm['border'] }};">
                            {{ $pm['icon'] }} {{ $pm['label'] }}
                        </span>
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
                    <td style="font-size:13px; color:#64748b; white-space:nowrap; padding-right:24px; font-weight:600;">
                        {{ $order->created_at->format('H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i data-lucide="inbox"></i>
                            </div>
                            <h3>Belum ada pesanan</h3>
                            <p>Pesanan yang masuk akan tampil di sini secara real-time.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- ══ 6. FOOTER TABEL & PAGINATION ══ --}}
        <div class="table-footer">
            <div class="table-info" id="tableInfo"></div>
            <div class="pagination-wrap" id="paginationWrap"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // Definisi Elemen Form / Filter
    var input         = document.getElementById('searchOrder');
    var clearBtn      = document.getElementById('searchClearBtn');
    var perPageSelect = document.getElementById('perPageSelect');
    var statusFilter  = document.getElementById('statusFilter');
    var dateFrom      = document.getElementById('dateFrom');
    var dateTo        = document.getElementById('dateTo');
    var refreshBtn    = document.getElementById('refreshBtn');
    
    // Definisi Elemen Tabel
    var tableInfo     = document.getElementById('tableInfo');
    var paginWrap     = document.getElementById('paginationWrap');
    var tbody         = document.querySelector('#ordersTable tbody');

    if (!input || !tbody) return;

    // Semua baris data (kecuali baris "belum ada pesanan" / empty-state)
    var allRows = Array.from(tbody.querySelectorAll('tr')).filter(function (r) {
        return !r.querySelector('td[colspan]');
    });
    var emptyRow = tbody.querySelector('td[colspan]')
        ? tbody.querySelector('td[colspan]').closest('tr') : null;

    var filteredRows = allRows.slice();
    var currentPage  = 1;
    var perPage      = parseInt(perPageSelect.value);

    /* ── Render halaman aktif ── */
    function renderPage() {
        var total   = filteredRows.length;
        var totalPg = Math.max(1, Math.ceil(total / perPage));
        if (currentPage > totalPg) currentPage = totalPg;

        var start = (currentPage - 1) * perPage;
        var end   = start + perPage;

        allRows.forEach(function (r) { r.style.display = 'none'; });
        filteredRows.slice(start, end).forEach(function (r) { r.style.display = ''; });

        if (emptyRow) emptyRow.style.display = total === 0 ? '' : 'none';

        // Info teks
        if (total === 0) {
            tableInfo.textContent = 'Tidak ada data yang ditemukan';
        } else {
            tableInfo.textContent = 'Menampilkan ' + (start + 1) + '–' + Math.min(end, total) + ' dari ' + total + ' pesanan';
        }

        renderPagination(totalPg);
    }

    /* ── Render tombol pagination ── */
    function renderPagination(totalPg) {
        paginWrap.innerHTML = '';
        if (totalPg <= 1) return;

        paginWrap.appendChild(mkBtn('&laquo;', currentPage === 1, function () { currentPage = 1; renderPage(); }));
        paginWrap.appendChild(mkBtn('&lsaquo;', currentPage === 1, function () { currentPage--; renderPage(); }));

        pageRange(currentPage, totalPg).forEach(function (p) {
            if (p === '...') {
                var el = document.createElement('span');
                el.innerHTML = '&hellip;';
                el.style.cssText = 'padding:0 6px; color:#94a3b8; font-size:13px;';
                paginWrap.appendChild(el);
            } else {
                var btn = mkBtn(p, false, function () {
                    currentPage = parseInt(this.dataset.page);
                    renderPage();
                });
                btn.dataset.page = p;
                if (p === currentPage) btn.classList.add('active');
                paginWrap.appendChild(btn);
            }
        });

        paginWrap.appendChild(mkBtn('&rsaquo;', currentPage === totalPg, function () { currentPage++; renderPage(); }));
        paginWrap.appendChild(mkBtn('&raquo;', currentPage === totalPg, function () { currentPage = totalPg; renderPage(); }));
    }

    function mkBtn(label, disabled, onClick) {
        var btn = document.createElement('button');
        btn.classList.add('pg-btn');
        btn.innerHTML = label;
        btn.disabled  = disabled;
        if (!disabled) btn.addEventListener('click', onClick);
        return btn;
    }

    function pageRange(cur, total) {
        if (total <= 7) return Array.from({ length: total }, function (_, i) { return i + 1; });
        if (cur <= 4)         return [1, 2, 3, 4, 5, '...', total];
        if (cur >= total - 3) return [1, '...', total-4, total-3, total-2, total-1, total];
        return [1, '...', cur-1, cur, cur+1, '...', total];
    }

    /* ── Filter / Search Logic (Digabungkan) ── */
    function doSearch() {
        var q       = input.value.trim().toLowerCase();
        var sVal    = statusFilter.value;
        var dFrom   = dateFrom.value;
        var dTo     = dateTo.value;

        // Tampilkan/Sembunyikan tombol reset search input
        clearBtn.style.display = q ? 'inline-flex' : 'none';

        filteredRows = allRows.filter(function (r) {
            // 1. Text Search (ID, Meja, dll)
            var textMatch = q ? r.textContent.toLowerCase().includes(q) : true;

            // 2. Status Match
            var statusMatch = true;
            if (sVal) {
                var rowStatus = r.getAttribute('data-status') || '';
                if (sVal === 'pending' && rowStatus !== 'pending') statusMatch = false;
                if (sVal === 'proses'  && !['paid', 'process'].includes(rowStatus)) statusMatch = false;
                if (sVal === 'diantar' && !['done', 'delivered'].includes(rowStatus)) statusMatch = false;
                if (sVal === 'selesai' && !['delivered', 'done'].includes(rowStatus)) statusMatch = false;
            }

            // 3. Date Range Match
            var dateMatch = true;
            var rowDate = r.getAttribute('data-date'); // Y-m-d format dari Blade
            if (rowDate) {
                if (dFrom && rowDate < dFrom) dateMatch = false;
                if (dTo   && rowDate > dTo)   dateMatch = false;
            }

            // AND logic: Baris tampil jika semua kondisi terpenuhi
            return textMatch && statusMatch && dateMatch;
        });

        currentPage = 1; // Kembali ke halaman 1 setiap kali ada perubahan filter
        renderPage();
    }

    /* ── Fungsi Reset Bawaan ── */
    window.clearSearch = function () { 
        input.value = ''; 
        doSearch(); 
        input.focus(); 
    };

    /* ── Event Listener Filter ── */
    input.addEventListener('input', doSearch);
    statusFilter.addEventListener('change', doSearch);
    dateFrom.addEventListener('change', doSearch);
    dateTo.addEventListener('change', doSearch);

    perPageSelect.addEventListener('change', function () {
        perPage = parseInt(this.value);
        currentPage = 1;
        renderPage();
    });

    /* ── Fungsi Tombol Refresh Baru ── */
    refreshBtn.addEventListener('click', function() {
        // Kosongkan semua state filter
        input.value = '';
        statusFilter.value = '';
        dateFrom.value = '';
        dateTo.value = '';
        
        // Panggil re-render (otomatis ke halaman 1 dan show semua data)
        doSearch(); 
    });

    // Render awal saat halaman dimuat
    renderPage();
})();
</script>
@endpush