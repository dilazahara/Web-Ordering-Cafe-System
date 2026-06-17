@extends('layouts.kasir')

@section('title', 'Kasir — Laporan')

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

/* ══ HEADER & NAVIGASI KASIR (TIDAK DIUBAH) ══ */
.header { position: fixed; top: 0; left: 0; right: 0; height: var(--header-h); background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; z-index: 100; box-shadow: var(--shadow-header); }
.logo { display: flex; align-items: center; gap: 10px; }
.logo-mark { width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgb(37 99 235/.35); }
.logo-mark svg { width: 18px; height: 18px; stroke: white; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.logo-text { font-size: 16px; font-weight: 800; letter-spacing: -0.5px; }
.logo-text span { color: var(--accent); }
.header-right { display: flex; align-items: center; gap: 8px; }
.header-clock{ display:flex; align-items:center; gap:8px; padding:8px 14px; border-radius:12px; background:var(--surface); border:1px solid var(--border); font-family:'Inter',sans-serif; box-shadow:var(--shadow-sm); }
.header-clock svg{ width:16px; height:16px; stroke:var(--accent); stroke-width:2.3; fill:none; }
#liveClock{ font-size:13px; font-weight:700; color:var(--text-primary); letter-spacing:.5px; }

/* Profile Dropdown */
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

/* ══ BAGIAN KONTEN ══ */
.main { margin-top: var(--total-top); padding: 36px 24px 72px; width: 100%; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 8px; }
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; }
.page-title { font-size: 23px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
.page-sub { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-family: 'Inter', sans-serif; }

/* Filter Bar */
.filter-bar { background: var(--surface); padding: 20px 24px; border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 24px; display: flex; gap: 15px; align-items: center; justify-content: space-between; box-shadow: var(--shadow); flex-wrap: wrap; }
.filter-bar input[type="date"] { padding: 10px 16px; border: 1px solid var(--border); border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 13.5px; color: var(--text-primary); transition: border-color 0.18s; min-width: 160px; }
.filter-bar input[type="date"]:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgb(37 99 235/.12); }
.filter-btn { display: flex; align-items: center; gap: 8px; padding: 10px 20px; border: none; border-radius: 12px; background: var(--accent); font-size: 13.5px; font-weight: 600; color: white; cursor: pointer; transition: all 0.18s; font-family: 'Inter', sans-serif; box-shadow: 0 3px 10px rgba(37,99,235,0.25); }
.filter-btn:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 5px 15px rgba(37,99,235,0.3); }
.download-btn { display: flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; background: var(--red); color: white; font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.18s; font-family: 'Inter', sans-serif; text-decoration: none; border: none; box-shadow: 0 3px 10px rgba(220,38,38,0.25); }
.download-btn:hover { background: #b91c1c; box-shadow: 0 5px 15px rgba(220,38,38,0.3); transform: translateY(-1px); }
@keyframes spin   { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes popIn  { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }

/* Table & Datatable Styles */
.table-wrap { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow-x: auto; box-shadow: var(--shadow); }
.rtable { width: 100%; border-collapse: collapse; font-family: 'Inter', sans-serif; min-width: 900px; }
.rtable thead th { background: var(--surface-2); padding: 14px 16px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: var(--text-muted); text-align: left; border-bottom: 1px solid var(--border); }
.rtable tbody tr { border-bottom: 1px solid var(--surface-2); transition: background .15s; background: var(--surface); }
.rtable tbody tr:hover { background: #fafbff; }
.rtable td { padding: 15px 16px; font-size: 13.5px; color: var(--text-secondary); vertical-align: middle; }

/* Badges */
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

/* ── EMPTY STATE ── */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 72px 24px 80px;
    text-align: center;
}
.empty-icon-ring {
    position: relative;
    width: 88px;
    height: 88px;
    margin-bottom: 24px;
}
.empty-icon-ring::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 2px dashed #cbd5e1;
    animation: spin-slow 12s linear infinite;
}
@keyframes spin-slow {
    to { transform: rotate(360deg); }
}
.empty-icon-inner {
    position: absolute;
    inset: 10px;
    border-radius: 50%;
    background: var(--surface-2);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
}
.empty-icon-inner svg {
    width: 30px;
    height: 30px;
    stroke: #94a3b8;
    stroke-width: 1.5;
    fill: none;
}
.empty-state h3 {
    font-size: 16px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
    letter-spacing: -0.2px;
}
.empty-state p {
    font-size: 13.5px;
    color: var(--text-muted);
    line-height: 1.7;
    max-width: 300px;
    font-family: 'Inter', sans-serif;
}
.empty-state .empty-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 20px;
    padding: 8px 16px;
    border-radius: 20px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-secondary);
    font-family: 'Inter', sans-serif;
}
.empty-state .empty-tag svg {
    width: 14px;
    height: 14px;
    stroke: var(--text-muted);
    stroke-width: 2;
    fill: none;
}

/* ── TOOLBAR ── */
.table-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 12px;
}
.toolbar-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.toolbar-label {
    font-size: 13px;
    color: var(--text-secondary);
    font-weight: 500;
    font-family: 'Inter', sans-serif;
}
.per-page-select {
    padding: 7px 30px 7px 12px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    outline: none;
    color: var(--text-primary);
    cursor: pointer;
    background: white;
    transition: border-color 0.2s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239198ae' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}
.per-page-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}
.toolbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
}
.toolbar-cari-label {
    font-size: 13px;
    color: var(--text-secondary);
    font-weight: 500;
    font-family: 'Inter', sans-serif;
}
.search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.search-wrap svg {
    position: absolute;
    left: 11px;
    width: 14px;
    height: 14px;
    stroke: var(--text-muted);
    stroke-width: 2;
    fill: none;
    pointer-events: none;
}
.search-input {
    padding: 8px 14px 8px 33px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    outline: none;
    width: 220px;
    color: var(--text-primary);
    transition: border-color 0.2s;
    background: white;
}
.search-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}

/* ── PAGINATION ── */
.pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 2px solid var(--border);
    background: var(--surface-2);
    flex-wrap: wrap;
    gap: 12px;
    border-bottom-left-radius: var(--radius-lg);
    border-bottom-right-radius: var(--radius-lg);
}
.pagination-info {
    font-size: 13px;
    color: var(--text-muted);
    font-family: 'Inter', sans-serif;
}
.pagination-info span {
    font-weight: 700;
    color: var(--text-primary);
}
.pagination-btns {
    display: flex;
    align-items: center;
    gap: 5px;
}
.page-btn {
    min-width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid var(--border);
    background: white;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.18s;
    padding: 0 12px;
    font-family: 'Inter', sans-serif;
}
.page-btn:hover:not(:disabled):not(.active) {
    background: var(--accent-bg);
    border-color: #bfdbfe;
    color: var(--accent);
}
.page-btn.active {
    background: linear-gradient(135deg, var(--accent), #1d4ed8);
    border-color: var(--accent);
    color: white;
    box-shadow: 0 2px 8px rgba(37,99,235,0.3);
}
.page-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* grand total row */
.grand-total-row {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 16px;
    padding: 18px 24px;
    border-top: 2px solid var(--border);
    background: var(--surface-2);
}

@media (max-width: 640px) {
  .main { padding: 24px 16px 48px; }
  .nav-link span { display: none; }
  .user-role, .user-info { display: none; }
  .filter-bar { flex-direction: column; align-items: stretch; }
  .page-header { flex-direction: column; }
  .table-wrap { padding: 15px; }
  .table-toolbar { flex-direction: column; align-items: flex-start; }
  .search-input { width: 100%; }
  .pagination-wrap { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush

@section('content')
<div class="page-header">
      <div>
        <div class="page-title">Laporan Penjualan Harian</div>
        <div class="page-sub">Pantau rekap transaksi kasir hari ini atau berdasarkan filter tanggal.</div>
      </div>
    </div>

    <div class="filter-bar">
      <form method="GET" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; flex:1;">
        <div style="display:flex; flex-direction:column; gap:4px;">
            <label style="font-size:12px; font-weight:600; color:var(--text-secondary);">Pilih Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') ?? date('Y-m-d') }}">
        </div>
        <button type="submit" class="filter-btn" style="margin-top:19px;">
          <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          Filter Data
        </button>
      </form>

      {{-- ✅ PERBAIKAN: Gunakan route() agar selalu mengarah ke KasirController --}}
      <a href="{{ route('kasir.laporan.pdf', ['tanggal' => request('tanggal')]) }}"
         class="download-btn"
         id="downloadPdfBtn"
         style="margin-top:19px;"
         onclick="startDownload(event, this)">
        <span id="downloadBtnContent" style="display:flex;align-items:center;gap:8px;">
          <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
          Download PDF
        </span>
        <span id="downloadBtnLoading" style="display:none;align-items:center;gap:8px;">
          <svg id="spinnerIcon" viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.9s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
          Menyiapkan PDF...
        </span>
      </a>

      {{-- Overlay loading --}}
      <div id="downloadOverlay" style="display:none;position:fixed;inset:0;background:rgba(15,22,35,.48);backdrop-filter:blur(4px);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:24px;padding:36px 44px;box-shadow:0 24px 64px rgba(0,0,0,.22);text-align:center;min-width:280px;max-width:340px;">

          {{-- State: Loading --}}
          <div id="dlStateLoading">
            <div style="width:60px;height:60px;margin:0 auto 18px;background:linear-gradient(135deg,#dc2626,#b91c1c);border-radius:18px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(220,38,38,.3);">
              <svg viewBox="0 0 24 24" width="28" height="28" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.85s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            </div>
            <div style="font-size:16px;font-weight:800;color:#0f172a;margin-bottom:6px;font-family:'Plus Jakarta Sans',sans-serif;">Menyiapkan Laporan...</div>
            <div style="font-size:12.5px;color:#64748b;font-family:'Inter',sans-serif;line-height:1.5;">PDF sedang diproses oleh server,<br>mohon tunggu sebentar</div>
            <div style="margin-top:18px;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
              <div id="downloadProgressBar" style="height:100%;width:0%;background:linear-gradient(90deg,#dc2626,#f97316);border-radius:99px;transition:width 0.35s ease;"></div>
            </div>
          </div>

          {{-- State: Sukses --}}
          <div id="dlStateSuccess" style="display:none;">
            <div style="width:60px;height:60px;margin:0 auto 18px;background:linear-gradient(135deg,#059669,#047857);border-radius:18px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(5,150,105,.3);animation:popIn .35s cubic-bezier(.34,1.56,.64,1) both;">
              <svg viewBox="0 0 24 24" width="28" height="28" stroke="white" stroke-width="2.8" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div style="font-size:16px;font-weight:800;color:#0f172a;margin-bottom:6px;font-family:'Plus Jakarta Sans',sans-serif;">Berhasil Diunduh!</div>
            <div style="font-size:12.5px;color:#64748b;font-family:'Inter',sans-serif;">File laporan PDF sudah tersimpan<br>di folder unduhan kamu</div>
          </div>

        </div>
      </div>
    </div>

    <div class="table-wrap">
        @php $grandTotal = 0; @endphp
        @if($orders->isEmpty())

            {{-- ── EMPTY STATE ── --}}
            <div class="empty-state">
                <div class="empty-icon-ring">
                    <div class="empty-icon-inner">
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>
                </div>

                @if(request('tanggal') && request('tanggal') !== date('Y-m-d'))
                    <h3>Tidak ada data untuk filter ini</h3>
                    <p>
                        Tidak ditemukan transaksi pada tanggal<br>
                        <strong style="color:#475569;">{{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}</strong>.<br>
                        Coba pilih tanggal yang berbeda.
                    </p>
                @else
                    <h3>Belum ada data laporan untuk hari ini</h3>
                    <p>
                        Transaksi yang berhasil diselesaikan hari ini<br>
                        akan muncul di sini secara otomatis.
                    </p>
                @endif

                <div class="empty-tag">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ request('tanggal') ? \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') : \Carbon\Carbon::today()->translatedFormat('d F Y') }}
                </div>
            </div>

        @else

            {{-- ── TOOLBAR ── --}}
            <div class="table-toolbar">
                <div class="toolbar-left">
                    <span class="toolbar-label">Tampilkan</span>
                    <select class="per-page-select" id="perPageSelect" onchange="ksOnPerPageChange()">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15" selected>15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="toolbar-label">data</span>
                </div>
                <div class="toolbar-right">
                    <span class="toolbar-cari-label">Cari:</span>
                    <div class="search-wrap">
                        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input
                            type="text"
                            class="search-input"
                            id="ksSearchInput"
                            placeholder="Cari ID, nama, meja, status..."
                            oninput="ksOnSearch()"
                        >
                    </div>
                </div>
            </div>

            <table class="rtable" id="laporanTable" style="width:100%;">
                <thead>
                    <tr>
                        <th style="padding-left:22px;">No</th>
                        <th>Waktu Transaksi</th>
                        <th>ID Order</th>
                        <th>Nama Pemesan</th>
                        <th>Tipe & Meja</th>
                        <th>Detail Pesanan</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th style="text-align:right;">Total Harga</th>
                    </tr>
                </thead>
                <tbody id="ksTableBody">
                @foreach($orders as $i => $order)
                @php $grandTotal += $order->total; @endphp
                <tr data-search="{{ strtolower(
                    ($order->queue_number ?: 'A-' . str_pad($order->id,3,'0',STR_PAD_LEFT))
                    . ' ' . ($order->customer_name ?? '')
                    . ' ' . (($order->order_type ?? 'dine_in') === 'take_away' ? 'take away takeaway' : ('meja ' . ($order->table_number ?? '')))
                    . ' ' . $order->status
                    . ' ' . $order->payment_method
                    . ' ' . $order->created_at->format('d M Y')
                ) }}">
                    <td class="row-no" style="padding-left:22px;">{{ $i + 1 }}</td>
                    <td>
                        <span style="font-weight: 600; color: var(--text-primary); display: block;">{{ $order->created_at->format('d M Y') }}</span>
                        <span style="font-size: 12px; color: var(--text-muted);">{{ $order->created_at->format('H:i') }} WIB</span>
                    </td>
                    <td>
                        <span style="font-weight:800; color:var(--accent); font-size:13px;">
                            {{ $order->queue_number ?: 'A-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        @if(!empty($order->customer_name))
                            <span style="font-weight:600; color:var(--text-primary); font-size:13px;">
                                {{ $order->customer_name }}
                            </span>
                        @else
                            <span style="font-size:12px; color:var(--text-muted); font-style:italic;">—</span>
                        @endif
                    </td>
                    <td>
                        @if(($order->order_type ?? 'dine_in') === 'take_away')
                            <span style="background:#eff6ff; padding:3px 8px; border-radius:8px; font-size:11px; font-weight:700; color:#2563eb; display:inline-block; margin-bottom:3px;">🛍️ Take Away</span>
                        @else
                            <span style="background:#fff7ed; padding:3px 8px; border-radius:8px; font-size:11px; font-weight:700; color:#ea580c; display:inline-block; margin-bottom:3px;">🪑 Dine In</span>
                            <br>
                            <span style="background:var(--surface-2); padding:3px 10px; border-radius:8px; font-size:12px; font-weight:700; color:var(--text-secondary); white-space:nowrap;">
                                Meja {{ $order->table_number ?? '—' }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($order->items && $order->items->count() > 0)
                            @foreach($order->items->take(2) as $item)
                                <span style="font-size:13px;">
                                    {{ $item->qty ?: ($item->quantity ?? 1) }}x {{ $item->name ?: ($item->menu->name ?? $item->menu->nama ?? '-') }}
                                    @if(!$loop->last)<br>@endif
                                </span>
                            @endforeach
                            @if($order->items->count() > 2)
                                <br><span style="color:var(--text-muted); font-size:12px;">+{{ $order->items->count()-2 }} lagi</span>
                            @endif
                        @else
                            <span style="font-size:13px; color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $pm = $order->payment_method ?? 'cash';
                            $midtransMethods = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans'];
                            $methodLabels = [
                                'gopay'       => ['emoji'=>'💚','label'=>'GoPay'],
                                'ovo'         => ['emoji'=>'💜','label'=>'OVO'],
                                'dana'        => ['emoji'=>'💙','label'=>'DANA'],
                                'shopeepay'   => ['emoji'=>'🧡','label'=>'ShopeePay'],
                                'bca'         => ['emoji'=>'🏦','label'=>'BCA VA'],
                                'bni'         => ['emoji'=>'🏦','label'=>'BNI VA'],
                                'bri'         => ['emoji'=>'🏦','label'=>'BRI VA'],
                                'mandiri'     => ['emoji'=>'🏦','label'=>'Mandiri VA'],
                                'permata'     => ['emoji'=>'🏦','label'=>'Permata VA'],
                                'credit_card' => ['emoji'=>'💳','label'=>'Kartu Kredit'],
                                'midtrans'    => ['emoji'=>'💳','label'=>'Online'],
                            ];
                        @endphp
                        @if($pm === 'cash')
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">💵 Cash</span>
                        @elseif(in_array($pm, $midtransMethods))
                            @php $ml = $methodLabels[$pm] ?? ['emoji'=>'💳','label'=>ucfirst($pm)]; @endphp
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0;">{{ $ml['emoji'] }} {{ $ml['label'] }}</span>
                        @else
                            <span style="font-size:12px; color:var(--text-secondary); text-transform:capitalize;">{{ $pm }}</span>
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
                    <td style="font-weight:700; color:var(--text-primary); text-align:right; white-space:nowrap;">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            {{-- Grand Total + Pagination --}}
            <div class="pagination-wrap" id="ksPaginationWrap">
                <div class="pagination-info" id="ksPaginationInfo"></div>
                <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span style="font-size:13px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.5px; font-family:'Inter',sans-serif;">Total Pendapatan:</span>
                        <span id="ksGrandTotalDisplay" style="font-size:18px; font-weight:800; color:var(--green); font-family:'Inter',sans-serif; white-space:nowrap;">
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="pagination-btns" id="ksPaginationBtns"></div>
                </div>
            </div>

        @endif
    </div>{{-- end table-wrap --}}
@endsection

@push('scripts')
<script>

function ksToast(msg,type,dur){type=type||'success';dur=dur||2400;var c=document.getElementById('ksToastContainer');if(!c)return;var colors={success:'background:linear-gradient(135deg,#059669,#047857);',info:'background:linear-gradient(135deg,#2563eb,#1d4ed8);',warning:'background:linear-gradient(135deg,#d97706,#b45309);',error:'background:linear-gradient(135deg,#dc2626,#b91c1c);'};var icons={success:'✅',info:'ℹ️',warning:'⚠️',error:'❌'};var t=document.createElement('div');t.style.cssText='pointer-events:auto;display:flex;align-items:center;gap:9px;padding:11px 18px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.18);font-size:13px;font-weight:600;font-family:"Plus Jakarta Sans",sans-serif;white-space:nowrap;color:white;opacity:0;transform:translateX(18px) scale(0.95);transition:all 0.25s cubic-bezier(.34,1.56,.64,1);max-width:340px;'+(colors[type]||colors.info);t.innerHTML='<span style="font-size:15px;">'+(icons[type]||'📢')+'</span><span>'+msg+'</span>';c.appendChild(t);requestAnimationFrame(function(){t.style.opacity='1';t.style.transform='translateX(0) scale(1)';});setTimeout(function(){t.style.opacity='0';t.style.transform='translateX(18px) scale(0.95)';setTimeout(function(){t.remove();},260);},dur);}

// ─────────────────────────────────────────────
//  TABLE: Search + Per-page + Pagination
// ─────────────────────────────────────────────
(function () {
    var perPage     = 15;
    var currentPage = 1;
    var keyword     = '';

    var tbody     = document.getElementById('ksTableBody');
    var infoEl    = document.getElementById('ksPaginationInfo');
    var btnsEl    = document.getElementById('ksPaginationBtns');
    var searchEl  = document.getElementById('ksSearchInput');
    var perPageEl = document.getElementById('perPageSelect');

    if (!tbody) return;

    var allRows = Array.from(tbody.querySelectorAll('tr[data-search]'));

    function getFiltered() {
        if (!keyword) return allRows;
        return allRows.filter(function (r) {
            return r.getAttribute('data-search').indexOf(keyword) !== -1;
        });
    }

    function render() {
        var filtered   = getFiltered();
        var total      = filtered.length;
        var totalPages = Math.max(1, Math.ceil(total / perPage));

        if (currentPage > totalPages) currentPage = totalPages;

        var start = (currentPage - 1) * perPage;
        var end   = start + perPage;

        // hide all
        allRows.forEach(function (r) { r.style.display = 'none'; });

        // show slice & renumber
        var visNo = 1;
        filtered.forEach(function (row, idx) {
            if (idx >= start && idx < end) {
                row.style.display = '';
                var noCell = row.querySelector('.row-no');
                if (noCell) noCell.textContent = start + visNo;
                visNo++;
            }
        });

        // no-results row
        var noRow = tbody.querySelector('.ks-no-results');
        if (total === 0) {
            if (!noRow) {
                var tr = document.createElement('tr');
                tr.className = 'ks-no-results';
                tr.innerHTML = '<td colspan="9" style="text-align:center;padding:48px 16px;color:var(--text-muted);font-size:13.5px;font-family:\'Inter\',sans-serif;">Tidak ada data yang sesuai pencarian "<strong style=\'color:var(--text-primary);\'>' + escHtml(keyword) + '</strong>"</td>';
                tbody.appendChild(tr);
            } else {
                noRow.querySelector('td').innerHTML = 'Tidak ada data yang sesuai pencarian "<strong style=\'color:var(--text-primary);\'>' + escHtml(keyword) + '</strong>"';
            }
        } else {
            if (noRow) noRow.remove();
        }

        // info
        if (infoEl) {
            if (total === 0) {
                infoEl.innerHTML = 'Menampilkan <span>0</span> dari <span>' + total + '</span> data';
            } else {
                var from = start + 1;
                var to   = Math.min(end, total);
                infoEl.innerHTML = 'Menampilkan <span>' + from + '–' + to + '</span> dari <span>' + total + '</span> data';
            }
        }

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (!btnsEl) return;
        btnsEl.innerHTML = '';

        var prev = makeBtn('‹', currentPage === 1, false, function () { currentPage--; render(); });
        btnsEl.appendChild(prev);

        buildPageRange(currentPage, totalPages).forEach(function (p) {
            if (p === '...') {
                var dots = document.createElement('span');
                dots.textContent = '…';
                dots.style.cssText = 'padding:0 5px;color:var(--text-muted);font-size:13px;align-self:center;';
                btnsEl.appendChild(dots);
            } else {
                btnsEl.appendChild(makeBtn(p, false, p === currentPage, (function (pg) {
                    return function () { currentPage = pg; render(); };
                })(p)));
            }
        });

        var next = makeBtn('›', currentPage === totalPages, false, function () { currentPage++; render(); });
        btnsEl.appendChild(next);
    }

    function buildPageRange(cur, total) {
        if (total <= 7) return Array.from({ length: total }, function (_, i) { return i + 1; });
        var pages = [1];
        if (cur > 3) pages.push('...');
        for (var i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) pages.push(i);
        if (cur < total - 2) pages.push('...');
        pages.push(total);
        return pages;
    }

    function makeBtn(label, disabled, active, onClick) {
        var btn = document.createElement('button');
        btn.className = 'page-btn' + (active ? ' active' : '');
        btn.textContent = label;
        btn.disabled = disabled;
        btn.addEventListener('click', onClick);
        return btn;
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    // expose to HTML onXxx attributes
    window.ksOnSearch = function () {
        keyword     = searchEl ? searchEl.value.trim().toLowerCase() : '';
        currentPage = 1;
        render();
    };

    window.ksOnPerPageChange = function () {
        perPage     = parseInt(perPageEl ? perPageEl.value : 15, 10);
        currentPage = 1;
        render();
        ksToast('Menampilkan ' + perPage + ' data per halaman', 'info', 1400);
    };

    // init
    render();

    // toast on load
    ksToast('📊 Laporan dimuat — ' + allRows.length + ' data', 'success', 2200);

})();

// feedback filter tanggal
document.querySelector('.filter-btn') && document.querySelector('.filter-btn').addEventListener('click', function() {
    var tgl = document.querySelector('input[name="tanggal"]');
    if (tgl && tgl.value) ksToast('🗓️ Filter laporan: ' + tgl.value, 'info', 2000);
});

/* ── DOWNLOAD PDF FEEDBACK ── */
function startDownload(e, btn) {
    e.preventDefault();
    var url = btn.getAttribute('href');

    var overlay      = document.getElementById('downloadOverlay');
    var btnContent   = document.getElementById('downloadBtnContent');
    var btnLoading   = document.getElementById('downloadBtnLoading');
    var progBar      = document.getElementById('downloadProgressBar');
    var stateLoading = document.getElementById('dlStateLoading');
    var stateSuccess = document.getElementById('dlStateSuccess');

    // Tampilkan overlay dulu
    overlay.style.display      = 'flex';
    stateLoading.style.display = '';
    stateSuccess.style.display = 'none';
    btnContent.style.display   = 'none';
    btnLoading.style.display   = 'flex';
    btn.style.pointerEvents    = 'none';
    btn.style.opacity          = '0.85';
    progBar.style.width        = '0%';

    // Progress bar animasi
    var progress = 0;
    var interval = setInterval(function() {
        progress += Math.random() * 12 + 4;
        if (progress > 85) progress = 85;
        progBar.style.width = progress + '%';
    }, 280);

    // Trigger download via iframe hidden — browser tidak navigate, overlay tetap tampil
    setTimeout(function() {
        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = url;
        document.body.appendChild(iframe);
        setTimeout(function() { document.body.removeChild(iframe); }, 10000);
    }, 600);

    // Selesai: progress 100% → state sukses → tutup overlay
    setTimeout(function() {
        clearInterval(interval);
        progBar.style.width = '100%';
        setTimeout(function() {
            stateLoading.style.display = 'none';
            stateSuccess.style.display = '';
            btnContent.style.display   = 'flex';
            btnLoading.style.display   = 'none';
            btn.style.pointerEvents    = '';
            btn.style.opacity          = '';
            setTimeout(function() {
                overlay.style.display = 'none';
                progBar.style.width   = '0%';
            }, 1600);
        }, 350);
    }, 3200);
}
</script>
@endpush