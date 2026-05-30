@extends('layouts.kasir')

@section('title', 'Kasir — Transaksi')

@push('styles')
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
  --indigo: #4f46e5; --indigo-bg: #eef2ff; --indigo-text: #3730a3;
  --header-h: 64px; --nav-h: 48px; --total-top: 112px;
  --radius-lg: 18px;
  --shadow-sm: 0 1px 4px rgb(0 0 0/.05), 0 0 0 1px rgb(0 0 0/.04);
  --shadow: 0 2px 8px rgb(0 0 0/.06), 0 0 0 1px rgb(0 0 0/.04);
  --shadow-md: 0 8px 24px rgb(0 0 0/.10), 0 0 0 1px rgb(0 0 0/.04);
  --shadow-header: 0 1px 0 var(--border), 0 2px 12px rgb(0 0 0/.04);
}
html { scroll-behavior: smooth; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); line-height: 1.5; min-height: 100vh; -webkit-font-smoothing: antialiased; }

.header { position: fixed; top: 0; left: 0; right: 0; height: var(--header-h); background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; z-index: 100; box-shadow: var(--shadow-header); }
.logo { display: flex; align-items: center; gap: 10px; }
.logo-mark { width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgb(37 99 235/.35); }
.logo-mark svg { width: 18px; height: 18px; stroke: white; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.logo-text { font-size: 16px; font-weight: 800; letter-spacing: -0.5px; }
.logo-text span { color: var(--accent); }
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
  stroke:var(--accent);
  stroke-width:2.3;
  fill:none;
}

#liveClock{
  font-size:13px;
  font-weight:700;
  color:var(--text-primary);
  letter-spacing:.5px;
}

/* ── PROFILE DROPDOWN ── */
.profile-wrap { position: relative; }
.user-btn { display: flex; align-items: center; gap: 10px; padding: 5px 12px 5px 5px; border: 1px solid var(--border); border-radius: 12px; background: var(--surface); cursor: pointer; transition: all 0.18s; user-select: none; }
.user-btn:hover { background: var(--surface-2); border-color: var(--border-strong); box-shadow: var(--shadow-sm); }
.user-btn.open { border-color: var(--accent); background: var(--accent-bg); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #818cf8, #4f46e5); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: 700; flex-shrink: 0; overflow: hidden; }
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-info { display: flex; flex-direction: column; }
.user-name { font-size: 13px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
.user-role { font-size: 11px; color: var(--text-muted); font-family: 'Inter', sans-serif; }
.chevron { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; transition: transform .2s; flex-shrink: 0; }
.user-btn.open .chevron { transform: rotate(180deg); }
.dropdown { position: absolute; top: calc(100% + 10px); right: 0; width: 240px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 16px 48px rgb(0 0 0/.14), 0 0 0 1px rgb(0 0 0/.04); overflow: hidden; opacity: 0; transform: translateY(-8px) scale(.97); pointer-events: none; transition: opacity .18s, transform .18s; z-index: 200; }
.dropdown.show { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }
.dropdown-header { padding: 16px; background: linear-gradient(135deg, var(--indigo-bg), var(--accent-bg)); border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
.dropdown-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, #818cf8, #4f46e5); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; font-weight: 800; flex-shrink: 0; box-shadow: 0 2px 8px rgb(79 70 229/.3); overflow: hidden; }
.dropdown-avatar img { width: 100%; height: 100%; object-fit: cover; }
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

.topnav { position: fixed; top: var(--header-h); left: 0; right: 0; height: var(--nav-h); background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); border-bottom: 1px solid var(--border); display: flex; justify-content: center; z-index: 99; }
.nav-container { max-width: 1280px; margin: 0 auto; display: flex; align-items: stretch; padding: 0 8px; }
.nav-link { display: flex; align-items: center; gap: 7px; padding: 0 18px; text-decoration: none; font-size: 13px; font-weight: 600; color: var(--text-secondary); transition: all 0.18s; white-space: nowrap; border-bottom: 2px solid transparent; margin-bottom: -1px; }
.nav-link svg { width: 15px; height: 15px; stroke: currentColor; stroke-width: 2.2; fill: none; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
.nav-link:hover { color: var(--text-primary); }
.nav-link.active { color: var(--accent); border-bottom-color: var(--accent); }

.main { margin-top: var(--total-top); padding: 36px 24px 72px; width: 100%; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 8px; }
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 32px; gap: 16px; }
.page-title { font-size: 23px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
.page-sub { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-family: 'Inter', sans-serif; }
.date-chip { display: flex; align-items: center; gap: 7px; padding: 9px 16px; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; font-size: 13px; font-weight: 600; color: var(--text-secondary); box-shadow: var(--shadow-sm); }
.date-chip svg { width: 15px; height: 15px; stroke: var(--accent); stroke-width: 2; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
.section-title { font-size: 15px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
.section-title svg { width: 16px; height: 16px; stroke: var(--accent); stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }

/* ══ CUSTOM SEARCH BAR ══ */
.search-toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 22px 16px;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
}
.search-input-wrap {
    position: relative;
    flex: 1;
    min-width: 220px;
    max-width: 380px;
}
.search-input-wrap svg {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    stroke: #94a3b8;
    stroke-width: 2.2;
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
    pointer-events: none;
    transition: stroke .2s;
}
.search-input-wrap:focus-within svg {
    stroke: var(--accent);
}
#customSearch {
    width: 100%;
    padding: 9px 12px 9px 38px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 13px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 500;
    color: var(--text-primary);
    background: var(--surface-2);
    outline: none;
    transition: all .2s;
}
#customSearch:focus {
    border-color: var(--accent);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,.08);
}
#customSearch::placeholder {
    color: var(--text-muted);
}
#clearSearchBtn {
    display: none;
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    background: #cbd5e1;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 11px;
    color: #fff;
    align-items: center;
    justify-content: center;
    line-height: 1;
    transition: background .15s;
    padding: 0;
}
#clearSearchBtn:hover { background: #94a3b8; }

.search-length-wrap {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    color: var(--text-secondary);
    font-family: 'Inter', sans-serif;
    white-space: nowrap;
}
.search-length-wrap select {
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 7px 10px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: var(--text-primary);
    background: var(--surface-2);
    outline: none;
    cursor: pointer;
    transition: border-color .2s;
}
.search-length-wrap select:focus { border-color: var(--accent); }

.search-filter-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 12px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    background: var(--surface-2);
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all .18s;
    font-family: 'Plus Jakarta Sans', sans-serif;
    white-space: nowrap;
}
.filter-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-bg); }
.filter-btn.active { border-color: var(--accent); color: var(--accent); background: var(--accent-bg); }
.filter-btn .dot { width: 7px; height: 7px; border-radius: 50%; }

#searchResultInfo {
    font-size: 12px;
    color: var(--text-muted);
    font-family: 'Inter', sans-serif;
    padding: 0 22px 10px;
    min-height: 22px;
    transition: all .2s;
}
#searchResultInfo.has-result {
    color: var(--accent-text);
    font-weight: 600;
}

/* ══ BOX & TABLE STYLE (Sinkron Admin Index) ══ */
.table-wrap {
    background: white; border-radius: var(--radius-lg); border: 1px solid var(--border);
    box-shadow: 0 2px 10px rgba(0,0,0,.05); overflow-x: auto; padding-bottom: 5px;
}
.rtable { width: 100%; border-collapse: collapse; font-family: 'Inter', sans-serif; min-width: 700px; }
.rtable thead th {
    background: #f8fafc; padding: 14px 16px; font-size: 11.5px;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px;
    color: #64748b; text-align: left; border-bottom: 1px solid #f1f5f9;
}
.rtable tbody tr { border-bottom: 1px solid #f8fafc; transition: background .15s; background: white; }
.rtable tbody tr:hover { background: #fafbff; }
.rtable td { padding: 15px 16px; font-size: 13.5px; color: #334155; vertical-align: middle; }
/* Hide rows via search */
.rtable tbody tr.ks-hidden { display: none; }

/* Status Badges Sinkron Admin */
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
.paginate_button {
    border-radius: 10px !important; border: 1px solid #e2e8f0 !important;
    padding: 6px 13px !important; margin: 0 2px !important; background: white !important;
    color: #475569 !important; font-size: 13px !important; font-weight: 600 !important;
    cursor: pointer !important; transition: all .15s !important;
}
.paginate_button:hover:not(.current):not(.disabled) { background: #eff6ff !important; color: #2563eb !important; border-color: #bfdbfe !important; }
.paginate_button.current, .paginate_button.current:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
    color: white !important; border-color: #2563eb !important;
    box-shadow: 0 2px 8px rgba(37,99,235,0.3) !important;
}
.paginate_button.disabled, .paginate_button.disabled:hover { color: #cbd5e1 !important; cursor: default !important; }
.dataTables_scrollBody::-webkit-scrollbar { height: 5px; }
.dataTables_scrollBody::-webkit-scrollbar-track { background: transparent; border-radius: 99px; }
.dataTables_scrollBody::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 99px; }

/* ══ MODAL STRUK ══ */
.struk-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(15,22,35,.55); backdrop-filter: blur(4px);
  z-index: 1000; align-items: center; justify-content: center; padding: 20px;
}
.struk-overlay.show { display: flex; }
@keyframes fadeInUp {
  from { opacity:0; transform:translateY(20px) scale(.96); }
  to   { opacity:1; transform:translateY(0)    scale(1);   }
}
.struk-box {
  background: #fff; border-radius: 18px; max-width: 400px; width: 100%;
  overflow: hidden; box-shadow: 0 24px 64px rgba(0,0,0,.2);
  animation: fadeInUp .25s cubic-bezier(.34,1.56,.64,1) both;
}

/* ══ EMPTY SEARCH STATE ══ */
#noSearchResult {
    display: none;
    text-align: center;
    padding: 48px 20px;
}
#noSearchResult svg {
    width: 38px; height: 38px; stroke: #cbd5e1; stroke-width: 1.5; fill: none;
    margin: 0 auto 12px; display: block;
}

@media (max-width: 640px) {
  .main { padding: 24px 16px 48px; }
  .nav-link span { display: none; }
  .user-role, .user-info { display: none; }
  .page-header { flex-direction: column; }
  .search-toolbar { flex-direction: column; align-items: stretch; }
  .search-input-wrap { max-width: 100%; }
}
</style>
@endpush

@section('content')
<div class="page-header">
      <div>
        <div class="page-title">Transaksi</div>
        <div class="page-sub">{{ now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp; Riwayat semua transaksi</div>
      </div>
      <div class="date-chip">
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        {{ now()->translatedFormat('d F Y') }}
      </div>
    </div>

    <div class="section-header">
      <div class="section-title">
        <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        Daftar Transaksi
      </div>
    </div>

    <div class="table-wrap">
        @if($orders->isEmpty())
            <div style="text-align:center; padding:56px 20px;">
                <svg viewBox="0 0 24 24" style="width:40px;height:40px;stroke:#cbd5e1;stroke-width:1.5;fill:none;margin:0 auto 12px;display:block;"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span style="font-weight:600; font-size:14px; color:#64748b; display:block;">Belum ada transaksi hari ini</span>
            </div>
        @else
            {{-- ══ CUSTOM SEARCH TOOLBAR ══ --}}
            <div class="search-toolbar">
                {{-- Search Input --}}
                <div class="search-input-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input
                        type="text"
                        id="customSearch"
                        placeholder="Cari ID order, meja, item, metode bayar..."
                        autocomplete="off"
                    >
                    <button id="clearSearchBtn" title="Hapus pencarian">✕</button>
                </div>

                {{-- Show per page --}}
                <div class="search-length-wrap">
                    <span>Tampilkan</span>
                    <select id="lengthSelect">
                        <option value="10">10</option>
                        <option value="15" selected>15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="-1">Semua</option>
                    </select>
                </div>

            {{-- Search result info --}}
            <div id="searchResultInfo"></div>

            <table class="rtable" id="transaksiTable" style="width:100%;">
                <thead>
                    <tr>
                        <th style="padding-left:22px; cursor:pointer;" data-col="0">
                            ID Order <span class="sort-icon" data-col="0">↕</span>
                        </th>
                        <th>Meja</th>
                        <th>Item</th>
                        <th style="cursor:pointer;" data-col="3">
                            Total <span class="sort-icon" data-col="3">↕</span>
                        </th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th style="cursor:pointer;" data-col="6">
                            Waktu <span class="sort-icon" data-col="6">↕</span>
                        </th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="transaksiBody">
                @foreach($orders as $order)
                @php
                    if ($order->status === 'pending') {
                        $badgeClass = 'pending'; $statusText = 'Menunggu Bayar';
                    } elseif ($order->status === 'paid') {
                        $badgeClass = 'selesai'; $statusText = 'Lunas, Belum Diproses';
                    } elseif ($order->status === 'process') {
                        $badgeClass = 'proses'; $statusText = 'Sedang Dimasak';
                    } elseif (in_array($order->status, ['done', 'delivered'])) {
                        $badgeClass = 'diantar'; $statusText = 'Selesai Diantar';
                    } else {
                        $badgeClass = 'pending'; $statusText = ucfirst($order->status);
                    }
                    $itemsForStruk = $order->items ? $order->items->map(function($i) {
                        return [
                            'name'     => !empty($i->name) ? $i->name : ($i->menu->name ?? '-'),
                            'qty'      => $i->qty ?? 1,
                            'subtotal' => $i->subtotal > 0 ? $i->subtotal : (($i->price ?? 0) * ($i->qty ?? 1)),
                        ];
                    })->values()->toArray() : [];
                @endphp
                <tr data-status="{{ $badgeClass }}">
                    <td style="padding-left:22px;">
                        <span style="font-weight:800; color:#2563eb; font-size:13px;">
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
                                    {{ $item->qty ?: ($item->quantity ?? 1) }}x
                                    {{ $item->name ?: ($item->menu->name ?? $item->menu->nama ?? '-') }}
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
                    <td style="font-weight:600; font-size:13px; color:#0f172a;" data-total="{{ $order->total ?? 0 }}">
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
                        <span class="badge {{ $badgeClass }}">
                            <span class="badge-dot"></span>
                            {{ $statusText }}
                        </span>
                    </td>
                    <td style="font-size:12px; color:#94a3b8; white-space:nowrap;" data-waktu="{{ $order->created_at->format('H:i') }}">
                        {{ $order->created_at->format('H:i') }}
                    </td>
                    {{-- ══ KOLOM AKSI: CETAK STRUK ══ --}}
                    <td>
                        <button
                          type="button"
                          onclick="openStrukModal(
                            {{ $order->id }},
                            '{{ $order->queue_number ?: 'A-'.str_pad($order->id,3,'0',STR_PAD_LEFT) }}',
                            '{{ $order->table_number ?? '' }}',
                            {{ $order->total ?? 0 }},
                            '{{ $order->payment_method ?? 'cash' }}',
                            '{{ $order->created_at->format('d/m/Y H:i') }}',
                            {{ json_encode($itemsForStruk) }},
                            {{ $order->uang_diterima ?? 0 }}
                          )"
                          style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#4f46e5;color:#fff;border:none;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;white-space:nowrap;box-shadow:0 2px 6px rgb(79 70 229/.25);transition:all .18s;"
                          onmouseover="this.style.background='#4338ca';this.style.transform='translateY(-1px)'"
                          onmouseout="this.style.background='#4f46e5';this.style.transform=''"
                        >
                          🧾 Struk
                        </button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            {{-- No result state --}}
            <div id="noSearchResult">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span style="font-weight:600; font-size:14px; color:#64748b; display:block;">Tidak ada hasil untuk pencarian ini</span>
                <span style="font-size:12px; color:#94a3b8; margin-top:4px; display:block;">Coba kata kunci lain atau reset filter</span>
                <button onclick="resetSearch()" style="margin-top:14px;padding:8px 18px;border:1.5px solid var(--border);border-radius:8px;background:#fff;font-size:13px;font-weight:600;color:var(--text-secondary);cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:background .15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">Reset Pencarian</button>
            </div>

            {{-- Custom Pagination --}}
            <div id="customPagination" style="display:flex;align-items:center;justify-content:space-between;padding:14px 22px 6px;flex-wrap:wrap;gap:10px;">
                <div id="paginationInfo" style="font-size:13px;color:#64748b;font-family:'Inter',sans-serif;font-weight:500;"></div>
                <div id="paginationButtons" style="display:flex;gap:4px;align-items:center;"></div>
            </div>
        @endif
    </div>{{-- end table-wrap --}}

{{-- ══════════════════════════════════════
     MODAL STRUK — Font Poppins
══════════════════════════════════════ --}}
<div id="strukModal" class="struk-overlay" onclick="if(event.target===this)closeStrukModal()">
  <div class="struk-box">
    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #e4e8f0;background:linear-gradient(135deg,#eef2ff,#eff4ff);">
      <div style="display:flex;align-items:center;gap:10px;">
        <div style="width:36px;height:36px;background:#4f46e5;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 2px 8px rgb(79 70 229/.3);">🧾</div>
        <div>
          <div style="font-size:14px;font-weight:800;color:#0f1623;font-family:'Poppins',sans-serif;">Struk Pembayaran</div>
          <div style="font-size:11px;color:#5a6279;font-family:'Poppins',sans-serif;" id="strukSubtitle">Order #—</div>
        </div>
      </div>
      <button onclick="closeStrukModal()" style="width:32px;height:32px;border:none;background:#e4e8f0;border-radius:8px;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;color:#5a6279;transition:background .15s;" onmouseover="this.style.background='#ccd2e0'" onmouseout="this.style.background='#e4e8f0'">✕</button>
    </div>

    {{-- Struk Content — Font Poppins --}}
    <div style="padding:0 20px;max-height:60vh;overflow-y:auto;">
      <div id="strukPrint" style="font-family:'Poppins',sans-serif;font-size:12px;color:#111;padding:20px 4px;">
        <div style="text-align:center;margin-bottom:10px;">
          <div style="font-size:16px;font-weight:700;letter-spacing:2px;font-family:'Poppins',sans-serif;">CAFE MOMOO</div>
          <div style="font-size:11px;color:#555;margin-top:2px;font-family:'Poppins',sans-serif;">Terima kasih atas kunjungan Anda</div>
          <div style="border-top:2px dashed #bbb;margin:10px 0 0;"></div>
        </div>
        <table style="width:100%;font-size:12px;border-collapse:collapse;margin-bottom:4px;font-family:'Poppins',sans-serif;">
          <tr><td style="padding:3px 0;">No. Order</td><td style="text-align:right;font-weight:700;" id="sQueue">—</td></tr>
          <tr><td style="padding:3px 0;">Meja</td><td style="text-align:right;" id="sMeja">—</td></tr>
          <tr><td style="padding:3px 0;">Waktu</td><td style="text-align:right;font-size:11px;" id="sWaktu">—</td></tr>
          <tr><td style="padding:3px 0;">Metode</td><td style="text-align:right;" id="sMetode">—</td></tr>
        </table>
        <div style="border-top:1px dashed #bbb;margin:8px 0;"></div>
        <div style="font-weight:700;margin-bottom:6px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:#555;font-family:'Poppins',sans-serif;">Item Pesanan</div>
        <div id="sItems" style="margin-bottom:4px;font-family:'Poppins',sans-serif;"></div>
        <div style="border-top:1px dashed #bbb;margin:8px 0;"></div>
        <table style="width:100%;font-size:13px;border-collapse:collapse;font-family:'Poppins',sans-serif;">
          <tr style="font-weight:700;">
            <td style="padding:3px 0;">TOTAL</td>
            <td style="text-align:right;" id="sTotal">—</td>
          </tr>
        </table>
        <div id="sUangBlock" style="display:none;">
          <table style="width:100%;font-size:12px;border-collapse:collapse;margin-top:4px;font-family:'Poppins',sans-serif;">
            <tr><td style="padding:3px 0;">Uang Diterima</td><td style="text-align:right;" id="sUang">—</td></tr>
            <tr style="font-weight:700;"><td style="padding:3px 0;">Kembalian</td><td style="text-align:right;" id="sKembali">—</td></tr>
          </table>
        </div>
        <div style="border-top:2px dashed #bbb;margin:10px 0 8px;"></div>
        <div style="text-align:center;font-size:11px;color:#666;line-height:1.8;font-family:'Poppins',sans-serif;">
          Terima kasih telah memesan!<br>
          Semoga makanan Anda lezat 😊
          <div style="font-size:10px;margin-top:6px;color:#999;font-family:'Poppins',sans-serif;">Dicetak: <span id="sPrintTime">—</span></div>
        </div>
      </div>
    </div>

    {{-- Footer --}}
    <div style="padding:14px 20px;border-top:1px solid #e4e8f0;display:flex;gap:10px;background:#fff;">
      <button onclick="closeStrukModal()" style="flex:1;padding:10px;border:1.5px solid #ccd2e0;background:#f7f8fc;border-radius:10px;font-size:13px;font-weight:700;color:#5a6279;cursor:pointer;font-family:'Poppins',sans-serif;transition:background .15s;" onmouseover="this.style.background='#e4e8f0'" onmouseout="this.style.background='#f7f8fc'">Tutup</button>
      <button onclick="cetakStruk()" style="flex:2;padding:10px;border:none;background:#4f46e5;color:white;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;font-family:'Poppins',sans-serif;display:flex;align-items:center;justify-content:center;gap:6px;box-shadow:0 3px 12px rgb(79 70 229/.3);transition:background .18s;" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
        🖨️ Cetak Struk
      </button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
{{-- Load Poppins dari Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<script>

/* ── TOAST ── */
function ksToast(msg,type,dur){type=type||'success';dur=dur||2400;var c=document.getElementById('ksToastContainer');if(!c)return;var colors={success:'background:linear-gradient(135deg,#059669,#047857);',info:'background:linear-gradient(135deg,#2563eb,#1d4ed8);',warning:'background:linear-gradient(135deg,#d97706,#b45309);',error:'background:linear-gradient(135deg,#dc2626,#b91c1c);'};var icons={success:'✅',info:'ℹ️',warning:'⚠️',error:'❌'};var t=document.createElement('div');t.style.cssText='pointer-events:auto;display:flex;align-items:center;gap:9px;padding:11px 18px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.18);font-size:13px;font-weight:600;font-family:"Plus Jakarta Sans",sans-serif;white-space:nowrap;color:white;opacity:0;transform:translateX(18px) scale(0.95);transition:all 0.25s cubic-bezier(.34,1.56,.64,1);max-width:340px;'+(colors[type]||colors.info);t.innerHTML='<span style="font-size:15px;">'+(icons[type]||'📢')+'</span><span>'+msg+'</span>';c.appendChild(t);requestAnimationFrame(function(){t.style.opacity='1';t.style.transform='translateX(0) scale(1)';});setTimeout(function(){t.style.opacity='0';t.style.transform='translateX(18px) scale(0.95)';setTimeout(function(){t.remove();},260);},dur);}

/* ══════════════════════════════════════
   CUSTOM SEARCH & FILTER ENGINE
══════════════════════════════════════ */
(function() {
    // State
    var state = {
        query: '',
        status: 'all',
        pageSize: 15,
        currentPage: 1,
        sortCol: -1,
        sortDir: 'asc'
    };

    var allRows = [];
    var filteredRows = [];

    // DOM refs (populated on DOMContentLoaded)
    var searchInput, clearBtn, lengthSelect, filterBtns,
        resultInfo, tbody, noResult, paginationInfo, paginationButtons;

    function init() {
        // Only init if table exists
        if (!document.getElementById('transaksiTable')) return;

        searchInput      = document.getElementById('customSearch');
        clearBtn         = document.getElementById('clearSearchBtn');
        lengthSelect     = document.getElementById('lengthSelect');
        filterBtns       = document.querySelectorAll('.filter-btn');
        resultInfo       = document.getElementById('searchResultInfo');
        tbody            = document.getElementById('transaksiBody');
        noResult         = document.getElementById('noSearchResult');
        paginationInfo   = document.getElementById('paginationInfo');
        paginationButtons= document.getElementById('paginationButtons');

        // Cache all rows
        allRows = Array.from(tbody.querySelectorAll('tr'));

        // Initial render
        applyFilters();

        ksToast('📋 Data transaksi dimuat — ' + allRows.length + ' transaksi', 'success', 2200);

        // Search input
        var searchTimer;
        searchInput.addEventListener('input', function() {
            state.query = this.value.trim().toLowerCase();
            state.currentPage = 1;
            clearBtn.style.display = state.query ? 'flex' : 'none';
            clearTimeout(searchTimer);
            if (state.query) {
                searchTimer = setTimeout(function() {
                    ksToast('🔍 Mencari: "' + state.query + '"', 'info', 1400);
                }, 600);
            }
            applyFilters();
        });

        // Clear button
        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            state.query = '';
            clearBtn.style.display = 'none';
            state.currentPage = 1;
            applyFilters();
            searchInput.focus();
        });

        // Length select
        lengthSelect.addEventListener('change', function() {
            state.pageSize = this.value === '-1' ? -1 : parseInt(this.value);
            state.currentPage = 1;
            ksToast('Menampilkan ' + (state.pageSize === -1 ? 'semua' : state.pageSize) + ' data per halaman', 'info', 1400);
            applyFilters();
        });

        // Filter buttons
        filterBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterBtns.forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');
                state.status = this.dataset.status;
                state.currentPage = 1;
                ksToast('Filter: ' + (this.textContent.trim()), 'info', 1200);
                applyFilters();
            });
        });

        // Sort on column header click
        document.querySelectorAll('.rtable thead th[data-col]').forEach(function(th) {
            th.addEventListener('click', function() {
                var col = parseInt(this.dataset.col);
                if (state.sortCol === col) {
                    state.sortDir = state.sortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    state.sortCol = col;
                    state.sortDir = 'asc';
                }
                // Update sort icons
                document.querySelectorAll('.sort-icon').forEach(function(ic) {
                    ic.textContent = '↕';
                    ic.style.color = '#cbd5e1';
                });
                var icon = th.querySelector('.sort-icon');
                if (icon) {
                    icon.textContent = state.sortDir === 'asc' ? '↑' : '↓';
                    icon.style.color = '#2563eb';
                }
                ksToast('Diurutkan berdasarkan ' + th.textContent.replace(/[↕↑↓]/g,'').trim() + ' (' + (state.sortDir === 'asc' ? 'A→Z' : 'Z→A') + ')', 'info', 1400);
                state.currentPage = 1;
                applyFilters();
            });
        });
    }

    function getRowText(row) {
        return row.textContent.toLowerCase();
    }

    function applyFilters() {
        // 1. Filter by status
        var statusFiltered = allRows.filter(function(row) {
            if (state.status === 'all') return true;
            return row.dataset.status === state.status;
        });

        // 2. Filter by search query
        var searched = statusFiltered.filter(function(row) {
            if (!state.query) return true;
            return getRowText(row).indexOf(state.query) !== -1;
        });

        // 3. Sort
        if (state.sortCol >= 0) {
            searched.sort(function(a, b) {
                var cellA = a.cells[state.sortCol] ? a.cells[state.sortCol].textContent.trim() : '';
                var cellB = b.cells[state.sortCol] ? b.cells[state.sortCol].textContent.trim() : '';
                // Try numeric sort for Total column
                if (state.sortCol === 3) {
                    var numA = parseFloat(a.cells[state.sortCol].dataset.total || 0);
                    var numB = parseFloat(b.cells[state.sortCol].dataset.total || 0);
                    return state.sortDir === 'asc' ? numA - numB : numB - numA;
                }
                // Time sort
                if (state.sortCol === 6) {
                    return state.sortDir === 'asc'
                        ? cellA.localeCompare(cellB)
                        : cellB.localeCompare(cellA);
                }
                return state.sortDir === 'asc'
                    ? cellA.localeCompare(cellB, 'id')
                    : cellB.localeCompare(cellA, 'id');
            });
        }

        filteredRows = searched;

        // 4. Paginate
        renderTable();
        renderPagination();
        renderResultInfo();
    }

    function renderTable() {
        var total = filteredRows.length;

        // Hide all rows first
        allRows.forEach(function(r) { r.style.display = 'none'; });

        if (total === 0) {
            noResult.style.display = 'block';
            tbody.parentElement.style.display = 'none';
            document.getElementById('customPagination').style.display = 'none';
            return;
        }

        noResult.style.display = 'none';
        tbody.parentElement.style.display = '';
        document.getElementById('customPagination').style.display = 'flex';

        var start, end;
        if (state.pageSize === -1) {
            start = 0;
            end = total;
        } else {
            start = (state.currentPage - 1) * state.pageSize;
            end = Math.min(start + state.pageSize, total);
        }

        // Re-append in sorted order and show paged rows
        filteredRows.forEach(function(row, idx) {
            tbody.appendChild(row);
            row.style.display = (idx >= start && idx < end) ? '' : 'none';
        });
    }

    function renderPagination() {
        var total = filteredRows.length;
        if (total === 0 || state.pageSize === -1) {
            paginationInfo.textContent = total > 0 ? 'Menampilkan semua ' + total + ' transaksi' : '';
            paginationButtons.innerHTML = '';
            return;
        }

        var totalPages = Math.ceil(total / state.pageSize);
        var start = (state.currentPage - 1) * state.pageSize + 1;
        var end = Math.min(state.currentPage * state.pageSize, total);

        paginationInfo.textContent = 'Menampilkan ' + start + '–' + end + ' dari ' + total + ' transaksi';

        // Build page buttons
        var html = '';
        // Prev
        html += '<button onclick="ksPage(' + (state.currentPage - 1) + ')" ' +
            (state.currentPage === 1 ? 'disabled' : '') +
            ' style="border-radius:10px;border:1px solid #e2e8f0;padding:6px 13px;background:white;color:' +
            (state.currentPage === 1 ? '#cbd5e1' : '#475569') +
            ';font-size:13px;font-weight:600;cursor:' + (state.currentPage === 1 ? 'default' : 'pointer') + ';font-family:\'Inter\',sans-serif;transition:all .15s;">←</button>';

        // Page numbers (show max 5 around current)
        var from = Math.max(1, state.currentPage - 2);
        var to   = Math.min(totalPages, from + 4);
        from = Math.max(1, to - 4);

        if (from > 1) {
            html += '<button onclick="ksPage(1)" style="border-radius:10px;border:1px solid #e2e8f0;padding:6px 13px;background:white;color:#475569;font-size:13px;font-weight:600;cursor:pointer;font-family:\'Inter\',sans-serif;">1</button>';
            if (from > 2) html += '<span style="padding:0 4px;color:#94a3b8;font-size:13px;">…</span>';
        }

        for (var p = from; p <= to; p++) {
            var active = p === state.currentPage;
            html += '<button onclick="ksPage(' + p + ')" style="border-radius:10px;border:1px solid ' +
                (active ? '#2563eb' : '#e2e8f0') + ';padding:6px 13px;background:' +
                (active ? 'linear-gradient(135deg,#2563eb,#1d4ed8)' : 'white') +
                ';color:' + (active ? 'white' : '#475569') +
                ';font-size:13px;font-weight:600;cursor:pointer;font-family:\'Inter\',sans-serif;' +
                (active ? 'box-shadow:0 2px 8px rgba(37,99,235,0.3);' : '') +
                '">' + p + '</button>';
        }

        if (to < totalPages) {
            if (to < totalPages - 1) html += '<span style="padding:0 4px;color:#94a3b8;font-size:13px;">…</span>';
            html += '<button onclick="ksPage(' + totalPages + ')" style="border-radius:10px;border:1px solid #e2e8f0;padding:6px 13px;background:white;color:#475569;font-size:13px;font-weight:600;cursor:pointer;font-family:\'Inter\',sans-serif;">' + totalPages + '</button>';
        }

        // Next
        html += '<button onclick="ksPage(' + (state.currentPage + 1) + ')" ' +
            (state.currentPage === totalPages ? 'disabled' : '') +
            ' style="border-radius:10px;border:1px solid #e2e8f0;padding:6px 13px;background:white;color:' +
            (state.currentPage === totalPages ? '#cbd5e1' : '#475569') +
            ';font-size:13px;font-weight:600;cursor:' + (state.currentPage === totalPages ? 'default' : 'pointer') + ';font-family:\'Inter\',sans-serif;transition:all .15s;">→</button>';

        paginationButtons.innerHTML = html;
    }

    function renderResultInfo() {
        if (!state.query && state.status === 'all') {
            resultInfo.textContent = '';
            resultInfo.classList.remove('has-result');
            return;
        }
        var total = filteredRows.length;
        var parts = [];
        if (state.query) parts.push('kata kunci "' + state.query + '"');
        if (state.status !== 'all') {
            var labels = { pending: 'Menunggu', proses: 'Dimasak', selesai: 'Selesai', diantar: 'Diantar' };
            parts.push('status ' + (labels[state.status] || state.status));
        }
        resultInfo.textContent =  total + ' transaksi ditemukan untuk ' + parts.join(' + ');
        resultInfo.classList.toggle('has-result', total > 0);
    }

    // Expose page changer globally (used in inline onclick)
    window.ksPage = function(page) {
        var totalPages = state.pageSize === -1 ? 1 : Math.ceil(filteredRows.length / state.pageSize);
        if (page < 1 || page > totalPages) return;
        state.currentPage = page;
        ksToast('Halaman ' + page + ' dari ' + totalPages, 'info', 1200);
        renderTable();
        renderPagination();
    };

    // Expose reset search globally
    window.resetSearch = function() {
        searchInput.value = '';
        state.query = '';
        state.status = 'all';
        state.currentPage = 1;
        clearBtn.style.display = 'none';
        filterBtns.forEach(function(b) { b.classList.remove('active'); });
        var allBtn = document.querySelector('.filter-btn[data-status="all"]');
        if (allBtn) allBtn.classList.add('active');
        applyFilters();
    };

    // Init on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

/* ══════════════════════════════════════
   STRUK MODAL FUNCTIONS — Font Poppins
══════════════════════════════════════ */
function openStrukModal(id, queueNum, meja, total, metode, waktu, items, uangDiterima) {
    document.getElementById('strukSubtitle').textContent = 'Order ' + queueNum;
    document.getElementById('sQueue').textContent   = queueNum;
    document.getElementById('sMeja').textContent    = meja ? 'Meja ' + meja : 'Take Away';
    document.getElementById('sWaktu').textContent   = waktu;
    document.getElementById('sMetode').textContent  = metode === 'cash' ? '💵 Cash' : '📱 QRIS';
    document.getElementById('sTotal').textContent   = 'Rp ' + Number(total).toLocaleString('id-ID');
    document.getElementById('sPrintTime').textContent = new Date().toLocaleString('id-ID');

    // Render item list
    var itemsHtml = '';
    if (items && items.length > 0) {
        items.forEach(function(item) {
            var sub = Number(item.subtotal || 0);
            itemsHtml +=
                '<div style="display:flex;justify-content:space-between;margin-bottom:5px;font-family:\'Poppins\',sans-serif;">' +
                    '<span style="flex:1;padding-right:8px;">' + (item.qty || 1) + 'x ' + (item.name || '-') + '</span>' +
                    '<span style="white-space:nowrap;font-weight:600;">Rp ' + sub.toLocaleString('id-ID') + '</span>' +
                '</div>';
        });
    } else {
        itemsHtml = '<div style="color:#999;font-size:11px;font-style:italic;font-family:\'Poppins\',sans-serif;">—</div>';
    }
    document.getElementById('sItems').innerHTML = itemsHtml;

    // Uang diterima & kembalian (hanya cash)
    var uangBlock = document.getElementById('sUangBlock');
    if (metode === 'cash' && Number(uangDiterima) > 0) {
        document.getElementById('sUang').textContent    = 'Rp ' + Number(uangDiterima).toLocaleString('id-ID');
        var kembalian = Math.max(0, Number(uangDiterima) - Number(total));
        document.getElementById('sKembali').textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
        uangBlock.style.display = '';
    } else {
        uangBlock.style.display = 'none';
    }

    var modal = document.getElementById('strukModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeStrukModal() {
    document.getElementById('strukModal').classList.remove('show');
    document.body.style.overflow = '';
}

function cetakStruk() {
    var isi = document.getElementById('strukPrint').innerHTML;
    var win = window.open('', '_blank', 'width=340,height=640,toolbar=0,menubar=0,scrollbars=1');
    win.document.write(
        '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Struk</title>' +
        '<link rel="preconnect" href="https://fonts.googleapis.com">' +
        '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' +
        '<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">' +
        '<style>' +
        'body{margin:0;padding:16px;font-family:"Poppins",sans-serif;font-size:12px;color:#111;background:#fff;max-width:300px;}' +
        'table{width:100%;border-collapse:collapse;}' +
        '@media print{body{padding:4px;max-width:none;}#printBtn{display:none!important;}}' +
        '#printBtn{display:block;width:100%;margin-top:14px;padding:9px;background:#4f46e5;color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;font-family:"Poppins",sans-serif;}' +
        '</style>' +
        '</head><body>' +
        isi +
        '<button id="printBtn" onclick="window.print();setTimeout(function(){window.close();},500);">🖨️ Cetak / Print</button>' +
        '</body></html>'
    );
    win.document.close();
    win.focus();
    setTimeout(function() { try { win.print(); } catch(e) {} }, 800);
}

</script>
@endpush