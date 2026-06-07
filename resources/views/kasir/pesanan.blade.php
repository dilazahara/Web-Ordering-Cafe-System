@extends('layouts.kasir')

@section('title', 'Kasir — Pesanan')

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
  --orange: #ea580c; --orange-bg: #fff7ed; --orange-text: #9a3412;
  --header-h: 64px; --nav-h: 48px; --total-top: 112px;
  --radius-lg: 18px;
  --shadow-sm: 0 1px 4px rgb(0 0 0/.05), 0 0 0 1px rgb(0 0 0/.04);
  --shadow: 0 2px 8px rgb(0 0 0/.06), 0 0 0 1px rgb(0 0 0/.04);
  --shadow-md: 0 8px 24px rgb(0 0 0/.10), 0 0 0 1px rgb(0 0 0/.04);
  --shadow-header: 0 1px 0 var(--border), 0 2px 12px rgb(0 0 0/.04);
}
html { scroll-behavior: smooth; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); line-height: 1.5; min-height: 100vh; -webkit-font-smoothing: antialiased; }

/* ═══════════════════════════════════
   HEADER
═══════════════════════════════════ */
.header { position: fixed; top: 0; left: 0; right: 0; height: var(--header-h); background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; z-index: 100; box-shadow: var(--shadow-header); }
.logo { display: flex; align-items: center; gap: 10px; }
.logo-mark { width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgb(37 99 235/.35); }
.logo-mark svg { width: 18px; height: 18px; stroke: white; stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.logo-text { font-size: 16px; font-weight: 800; letter-spacing: -0.5px; }
.logo-text span { color: var(--accent); }
.header-right { display: flex; align-items: center; gap: 8px; }
.header-clock { display:flex; align-items:center; gap:8px; padding:8px 14px; border-radius:12px; background:var(--surface); border:1px solid var(--border); font-family:'Inter',sans-serif; box-shadow:var(--shadow-sm); }
.header-clock svg { width:16px; height:16px; stroke:var(--accent); stroke-width:2.3; fill:none; }
#liveClock { font-size:13px; font-weight:700; color:var(--text-primary); letter-spacing:.5px; }
.hdr-btn { position: relative; width: 38px; height: 38px; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-secondary); transition: all .18s; }
.hdr-btn:hover { background: var(--accent-bg); border-color: #bfcfff; color: var(--accent); }
.hdr-btn svg { width: 18px; height: 18px; stroke: currentColor; stroke-width: 2; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.divider-v { width: 1px; height: 28px; background: var(--border); margin: 0 4px; }
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
.dropdown-item.danger:hover .item-icon { background: #fecaca; }

/* ═══════════════════════════════════
   TOP NAV
═══════════════════════════════════ */
.topnav { position: fixed; top: var(--header-h); left: 0; right: 0; height: var(--nav-h); background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); border-bottom: 1px solid var(--border); display: flex; justify-content: center; z-index: 99; }
.nav-container { max-width: 1280px; margin: 0 auto; display: flex; align-items: stretch; padding: 0 8px; }
.nav-link { display: flex; align-items: center; gap: 7px; padding: 0 18px; text-decoration: none; font-size: 13px; font-weight: 600; color: var(--text-secondary); transition: all 0.18s; white-space: nowrap; border-bottom: 2px solid transparent; margin-bottom: -1px; }
.nav-link svg { width: 15px; height: 15px; stroke: currentColor; stroke-width: 2.2; fill: none; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
.nav-link:hover { color: var(--text-primary); }
.nav-link.active { color: var(--accent); border-bottom-color: var(--accent); }

/* ═══════════════════════════════════
   MAIN LAYOUT
═══════════════════════════════════ */
.main { margin-top: var(--total-top); padding: 28px 24px 72px; width: 100%; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 8px; }

/* ═══════════════════════════════════
   PAGE HEADER
═══════════════════════════════════ */
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
.page-header-left {}
.page-title { font-size: 23px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; display: flex; align-items: center; gap: 10px; }
.page-title-dot { width: 10px; height: 10px; background: var(--accent); border-radius: 50%; display: inline-block; box-shadow: 0 0 0 3px rgba(37,99,235,.2); animation: pulse-dot 2s infinite; }
@keyframes pulse-dot { 0%,100%{box-shadow:0 0 0 3px rgba(37,99,235,.2)} 50%{box-shadow:0 0 0 6px rgba(37,99,235,.08)} }
.page-sub { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-family: 'Inter', sans-serif; }

/* ═══════════════════════════════════
   STATUS OVERVIEW BAR
═══════════════════════════════════ */
.status-overview { display: flex; gap: 10px; margin-bottom: 22px; flex-wrap: wrap; }
.stat-chip { display: flex; align-items: center; gap: 8px; padding: 9px 14px; border-radius: 12px; border: 1.5px solid; cursor: pointer; transition: all .18s; font-family: 'Inter', sans-serif; user-select: none; }
.stat-chip:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.stat-chip.active { box-shadow: var(--shadow-md); }
.stat-chip-icon { font-size: 16px; line-height: 1; }
.stat-chip-body {}
.stat-chip-count { font-size: 18px; font-weight: 800; line-height: 1; letter-spacing: -0.5px; }
.stat-chip-label { font-size: 10.5px; font-weight: 600; opacity: .8; margin-top: 1px; text-transform: uppercase; letter-spacing: .4px; }

.chip-all   { background: #f8faff; border-color: var(--border-strong); color: var(--text-primary); }
.chip-all.active   { background: var(--accent-bg); border-color: #93c5fd; color: var(--accent); }
.chip-new   { background: #fff7ed; border-color: #fed7aa; color: var(--orange); }
.chip-new.active   { background: #ffedd5; border-color: var(--orange); box-shadow: 0 4px 16px rgba(234,88,12,.18); }
.chip-pending { background: var(--amber-bg); border-color: #fde68a; color: var(--amber); }
.chip-pending.active { background: #fef3c7; border-color: var(--amber); box-shadow: 0 4px 16px rgba(217,119,6,.18); }
.chip-process { background: var(--accent-bg); border-color: #bfdbfe; color: var(--accent); }
.chip-process.active { background: #dbeafe; border-color: var(--accent); box-shadow: 0 4px 16px rgba(37,99,235,.18); }
.chip-done  { background: var(--green-bg); border-color: #a7f3d0; color: var(--green); }
.chip-done.active  { background: #d1fae5; border-color: var(--green); box-shadow: 0 4px 16px rgba(5,150,105,.18); }

/* ═══════════════════════════════════
   TOOLBAR: SEARCH + SORT
═══════════════════════════════════ */
.toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; flex-wrap: wrap; }
.search-wrap { flex: 1; min-width: 200px; max-width: 400px; position: relative; }
.search-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; stroke: var(--text-muted); fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; pointer-events: none; }
.search-input { width: 100%; padding: 9px 14px 9px 38px; border: 1.5px solid var(--border-strong); border-radius: 11px; font-size: 13.5px; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 500; color: var(--text-primary); background: var(--surface); outline: none; transition: border-color .18s, box-shadow .18s; }
.search-input::placeholder { color: var(--text-muted); }
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.search-clear { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; border: none; background: var(--border-strong); border-radius: 50%; cursor: pointer; display: none; align-items: center; justify-content: center; color: var(--text-secondary); font-size: 11px; font-weight: 700; transition: background .15s; }
.search-clear:hover { background: var(--border); color: var(--text-primary); }
.search-clear.visible { display: flex; }

.sort-select { padding: 9px 34px 9px 12px; border: 1.5px solid var(--border-strong); border-radius: 11px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; color: var(--text-secondary); background: var(--surface); outline: none; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239198ae' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; transition: border-color .18s; }
.sort-select:focus { border-color: var(--accent); }

.section-count { display: flex; align-items: center; gap: 8px; margin-left: auto; }

/* ═══════════════════════════════════
   ORDER GRID & CARDS
═══════════════════════════════════ */
.order-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 16px; }

.order-card {
  background: var(--surface);
  border: 1.5px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: box-shadow 0.22s, transform 0.22s, opacity .22s;
  position: relative;
  animation: card-in .3s ease both;
}
.order-card.hidden { display: none; }

@keyframes card-in {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}

.order-card:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }

.order-card.is-new-urgent {
  border-color: var(--border-strong);
  animation: card-in .3s ease both;
}

/* ── STATUS STRIPE ── */
.status-stripe { height: 3px; width: 100%; background: var(--border); }

.order-card.is-pending-cash { border-color: var(--border); }
.order-card.is-pending-cash .order-card-top { background: var(--surface); }
.order-card.is-cash-paid { border-color: var(--border); }
.order-card.is-cash-paid .order-card-top { background: var(--surface); }
.order-card.is-qris { border-color: var(--border); }
.order-card.is-qris .order-card-top { background: var(--surface); }

/* ── QUEUE NUMBER BADGE ── */
.queue-badge {
  position: absolute; top: 10px; right: 12px;
  background: var(--surface-2);
  color: var(--text-secondary);
  border: 1px solid var(--border-strong);
  font-size: 10px; font-weight: 700; font-family: 'Inter', sans-serif;
  padding: 2px 8px; border-radius: 20px; letter-spacing: .3px;
  z-index: 2;
}

/* ── CARD TOP ── */
.order-card-top {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between; gap: 10px;
  background: var(--surface);
}
.oc-left { display: flex; align-items: center; gap: 10px; }

.table-badge {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; flex-shrink: 0;
  background: var(--surface-2);
  border: 1.5px solid var(--border);
}
.table-badge-urgent,
.table-badge-pending,
.table-badge-process,
.table-badge-done,
.table-badge-qris { background: var(--surface-2); border: 1.5px solid var(--border); }

.oc-info h3 { font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 0; letter-spacing: -.1px; }
.oc-meta { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; margin-top: 3px; }
.oc-time-inline { font-size: 11px; font-family: 'Inter', sans-serif; font-weight: 600; color: var(--text-muted); }
.elapsed-badge { font-size: 10.5px; font-weight: 700; font-family: 'Inter', sans-serif; padding: 1px 6px; border-radius: 20px; }
.elapsed-urgent { background: #fee2e2; color: var(--red-text); }
.elapsed-warn   { background: #fef3c7; color: var(--amber-text); }
.elapsed-normal { background: var(--surface-2); color: var(--text-muted); border: 1px solid var(--border); }

.oc-right { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
.pay-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; font-family: 'Inter', sans-serif; }
.pay-cash { background: var(--surface-2); color: var(--text-secondary); border: 1px solid var(--border); }
.pay-qris { background: var(--surface-2); color: var(--text-secondary); border: 1px solid var(--border); }

/* ── ITEM LIST ── */
.order-items { padding: 12px 16px; }
.item-row { display: flex; justify-content: space-between; align-items: flex-start; font-size: 12.5px; padding: 6px 0; color: var(--text-secondary); border-bottom: 1px solid var(--border); font-family: 'Inter', sans-serif; gap: 8px; }
.item-row:last-child { border-bottom: none; }
.item-row-left { flex: 1; }
.item-name { font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 5px; }
.item-qty { font-size: 10.5px; font-weight: 700; background: var(--border); color: var(--text-secondary); padding: 1px 5px; border-radius: 4px; font-family: 'Inter', sans-serif; }
.item-notes-small { font-size: 11px; color: var(--amber); margin-top: 2px; }
.item-price { font-weight: 700; color: var(--text-primary); font-variant-numeric: tabular-nums; white-space: nowrap; font-size: 12.5px; }

/* ── TOTAL ROW ── */
.total-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 16px; background: var(--surface-2); border-top: 1px solid var(--border); }
.total-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; font-family: 'Inter', sans-serif; }
.total-value { font-size: 16px; font-weight: 800; color: var(--text-primary); font-variant-numeric: tabular-nums; }

/* ── STATUS BANNERS ── */
.lunas-banner { margin: 10px 16px 4px; padding: 10px 14px; border-radius: 10px; display: flex; align-items: center; gap: 10px; }
.lunas-banner-cash { background: var(--green-bg); border: 1px solid #a7f3d0; }
.lunas-banner-qris { background: var(--surface-2); border: 1px solid var(--border); }
.lunas-banner-icon { font-size: 18px; flex-shrink: 0; }
.lunas-banner-text { flex: 1; }
.lunas-banner-title { font-size: 12px; font-weight: 700; letter-spacing: -0.1px; }
.lunas-banner-cash .lunas-banner-title { color: var(--green-text); }
.lunas-banner-qris .lunas-banner-title { color: var(--text-secondary); }
.lunas-banner-sub { font-size: 11px; font-family: 'Inter', sans-serif; margin-top: 1px; color: var(--text-muted); }
.lunas-banner-cash .lunas-banner-sub { color: #047857; }

.cash-alert { margin: 10px 16px 4px; padding: 9px 12px; background: var(--amber-bg); border: 1px solid #fde68a; border-radius: 8px; font-size: 12px; color: var(--amber-text); font-weight: 600; display: flex; align-items: center; gap: 8px; }
.cash-alert svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }

.status-info-box { margin: 10px 16px 4px; padding: 9px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.status-info-box svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
.box-cash-pending { background: var(--amber-bg); border: 1px solid #fde68a; color: var(--amber-text); }

/* ── CARD FOOTER ── */
.order-footer { padding: 10px 16px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 8px; background: var(--surface); }
.status-pills { display: flex; gap: 5px; flex-wrap: wrap; }
.action-btns { display: flex; gap: 6px; flex-shrink: 0; }

.pill { padding: 3px 8px; border-radius: 20px; font-size: 10.5px; font-weight: 700; font-family: 'Inter', sans-serif; display: inline-flex; align-items: center; gap: 3px; }
.pill-blue   { background: var(--accent-bg); color: var(--accent-text); border: 1px solid #bfdbfe; }
.pill-green  { background: var(--green-bg);  color: var(--green-text);  border: 1px solid #a7f3d0; }
.pill-amber  { background: var(--amber-bg);  color: var(--amber-text);  border: 1px solid #fde68a; }
.pill-red    { background: var(--red-bg);    color: var(--red-text);    border: 1px solid #fecaca; }
.pill-orange { background: var(--orange-bg); color: var(--orange-text); border: 1px solid #fed7aa; }
.pill-indigo { background: var(--indigo-bg); color: var(--indigo-text); border: 1px solid #c7d2fe; }

.act-btn { padding: 7px 13px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.18s; border: none; font-family: 'Plus Jakarta Sans', sans-serif; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
.act-btn:hover { transform: translateY(-1px); }
.act-btn:active { transform: scale(0.96); }
.ab-primary { background: var(--accent); color: white; box-shadow: 0 2px 8px rgb(37 99 235/.2); }
.ab-primary:hover { background: #1d55d8; }
.ab-success { background: var(--green); color: white; box-shadow: 0 2px 8px rgb(5 150 105/.2); }
.ab-success:hover { background: #047857; }
.ab-outline { background: var(--surface); color: var(--text-secondary); border: 1.5px solid var(--border-strong); }
.ab-outline:hover { background: var(--surface-2); color: var(--text-primary); }
.ab-orange { background: var(--orange); color: white; box-shadow: 0 2px 8px rgb(234 88 12/.2); }
.ab-orange:hover { background: #c2410c; }

/* ── EMPTY & NO RESULTS ── */
.empty-state { grid-column: 1 / -1; text-align: center; padding: 56px 20px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow); }
.empty-state svg { opacity: .4; }
.empty-state p { font-size: 14px; color: var(--text-muted); margin-top: 12px; }
.empty-state small { font-size: 12px; color: var(--text-muted); opacity: .7; margin-top: 4px; display: block; font-family: 'Inter', sans-serif; }

.no-results-msg { display: none; grid-column: 1/-1; text-align: center; padding: 48px; color: var(--text-muted); }
.no-results-msg.visible { display: block; }
.no-results-msg svg { opacity: .3; margin-bottom: 12px; }
.no-results-msg p { font-size: 14px; font-weight: 600; }
.no-results-msg small { font-size: 12px; font-family: 'Inter', sans-serif; margin-top: 4px; display: block; }

/* ═══════════════════════════════════
   MODAL CASH
═══════════════════════════════════ */
.modal-overlay { position: fixed; inset: 0; background: rgba(15, 22, 35, 0.5); backdrop-filter: blur(4px); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; transition: opacity 0.25s; }
.modal-overlay.show { opacity: 1; pointer-events: all; }
.modal-box { background: var(--surface); border-radius: 20px; width: 100%; max-width: 420px; box-shadow: 0 24px 60px rgb(0 0 0/.18), 0 0 0 1px rgb(0 0 0/.05); overflow: hidden; transform: translateY(16px) scale(.97); transition: transform 0.28s cubic-bezier(.34,1.56,.64,1); }
.modal-overlay.show .modal-box { transform: translateY(0) scale(1); }
.modal-head { padding: 20px 24px 16px; background: var(--surface-2); border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 14px; }
.modal-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--orange); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.modal-head-info h2 { font-size: 15px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.3px; }
.modal-head-info p { font-size: 12px; color: var(--text-secondary); margin-top: 2px; font-family: 'Inter', sans-serif; }
.modal-body { padding: 20px 24px; }
.modal-total-block { background: var(--surface-2); border: 1.5px solid var(--border); border-radius: 12px; padding: 14px 18px; text-align: center; margin-bottom: 18px; }
.modal-total-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; font-family: 'Inter', sans-serif; margin-bottom: 4px; }
.modal-total-amount { font-size: 28px; font-weight: 800; color: var(--text-primary); font-variant-numeric: tabular-nums; letter-spacing: -1px; }
.modal-field { margin-bottom: 16px; }
.modal-field label { display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px; font-family: 'Inter', sans-serif; }
.input-money-wrap { position: relative; }
.input-money-prefix { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; font-weight: 700; color: var(--text-secondary); font-family: 'Inter', sans-serif; pointer-events: none; user-select: none; }
.input-money { width: 100%; padding: 12px 14px 12px 42px; border: 1.5px solid var(--border-strong); border-radius: 10px; font-size: 18px; font-weight: 700; color: var(--text-primary); font-family: 'Inter', sans-serif; font-variant-numeric: tabular-nums; background: var(--surface); transition: border-color 0.18s, box-shadow 0.18s; outline: none; -moz-appearance: textfield; }
.input-money::-webkit-inner-spin-button, .input-money::-webkit-outer-spin-button { -webkit-appearance: none; }
.input-money:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.input-money.error { border-color: var(--red); box-shadow: 0 0 0 3px rgba(220,38,38,.08); }
.quick-cash { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 10px; }
.qc-btn { padding: 5px 11px; border-radius: 7px; background: var(--surface-2); border: 1.5px solid var(--border-strong); font-size: 11.5px; font-weight: 700; color: var(--text-secondary); cursor: pointer; font-family: 'Inter', sans-serif; transition: all .15s; }
.qc-btn:hover { background: var(--accent-bg); border-color: #bfcfff; color: var(--accent); }
.modal-change-block { border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s; background: var(--green-bg); border: 1px solid #a7f3d0; }
.modal-change-block.insuf { background: var(--red-bg); border-color: #fecaca; }
.modal-change-block.exact { background: var(--surface-2); border-color: var(--border); }
.modal-change-label { font-size: 12px; font-weight: 700; color: var(--green-text); font-family: 'Inter', sans-serif; display: flex; align-items: center; gap: 6px; }
.modal-change-block.insuf .modal-change-label { color: var(--red-text); }
.modal-change-block.exact .modal-change-label { color: var(--text-secondary); }
.modal-change-value { font-size: 18px; font-weight: 800; color: var(--green); font-variant-numeric: tabular-nums; }
.modal-change-block.insuf .modal-change-value { color: var(--red); }
.modal-change-block.exact .modal-change-value { color: var(--text-secondary); font-size: 14px; font-weight: 700; }
.modal-foot { padding: 0 24px 20px; display: flex; gap: 10px; }
.modal-btn { flex: 1; padding: 12px 18px; border-radius: 10px; font-size: 13.5px; font-weight: 700; cursor: pointer; border: none; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.18s; display: flex; align-items: center; justify-content: center; gap: 7px; }
.modal-btn:active { transform: scale(0.97); }
.modal-btn-cancel { background: var(--surface-2); color: var(--text-secondary); border: 1.5px solid var(--border-strong); }
.modal-btn-cancel:hover { background: var(--bg); color: var(--text-primary); }
.modal-btn-confirm { background: var(--green); color: white; box-shadow: 0 3px 12px rgb(5 150 105/.25); }
.modal-btn-confirm:hover { background: #047857; box-shadow: 0 5px 16px rgb(5 150 105/.35); }
.modal-btn-confirm:disabled { background: var(--border); color: var(--text-muted); box-shadow: none; cursor: not-allowed; }

/* ═══════════════════════════════════
   KANBAN BOARD
═══════════════════════════════════ */
.kanban-board {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  align-items: start;
}
.kanban-col {
  background: var(--surface-2);
  border: 1px solid var(--border);
  border-radius: 14px;
  overflow: hidden;
  min-height: 200px;
}
.kanban-col-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 14px;
  font-size: 12.5px;
  font-weight: 700;
  border-bottom: 1px solid var(--border);
  background: var(--surface);
  color: var(--text-primary);
}
.kanban-col-title {
  display: flex;
  align-items: center;
  gap: 7px;
}
.kanban-col-icon { font-size: 15px; }
.kanban-col-count {
  min-width: 22px;
  height: 22px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 800;
  font-family: 'Inter', sans-serif;
  padding: 0 7px;
  background: var(--border);
  color: var(--text-secondary);
}
.kanban-header-amber .kanban-col-title,
.kanban-header-blue  .kanban-col-title,
.kanban-header-green .kanban-col-title { color: var(--text-primary); }

.kanban-col-body {
  padding: 10px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  min-height: 120px;
}
.kanban-empty {
  text-align: center;
  padding: 32px 12px;
  color: var(--text-muted);
  font-size: 12px;
  font-family: 'Inter', sans-serif;
}
.kanban-empty span { display: block; font-size: 22px; margin-bottom: 6px; opacity: .4; }
.kanban-col-body .order-card { margin: 0; box-shadow: var(--shadow-sm); }
.kanban-col-body .order-card:hover { box-shadow: var(--shadow); transform: translateY(-2px); }

@media (max-width: 1024px) { .kanban-board { grid-template-columns: 1fr 1fr; } }
@media (max-width: 640px)  { .kanban-board { grid-template-columns: 1fr; } }

/* ═══════════════════════════════════
   RESPONSIVE
═══════════════════════════════════ */
@media (max-width: 960px) { .order-grid { grid-template-columns: 1fr; } }
@media (max-width: 720px) {
  .status-overview { gap: 8px; }
  .stat-chip { padding: 7px 11px; }
  .stat-chip-count { font-size: 15px; }
  .search-wrap { max-width: 100%; }
  .toolbar { gap: 8px; }
}
@media (max-width: 640px) {
  .main { padding: 20px 14px 48px; }
  .nav-link span { display: none; }
  .user-role, .user-info { display: none; }
  .page-header { flex-direction: column; }
  .order-footer { flex-direction: column; align-items: flex-start; }
  .action-btns { width: 100%; }
  .act-btn { flex: 1; text-align: center; justify-content: center; }
  .modal-box { border-radius: 20px; }
  .modal-head, .modal-body, .modal-foot { padding-left: 20px; padding-right: 20px; }
  .modal-total-amount { font-size: 26px; }
}
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 6px; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
  <div class="page-header-left">
    <div class="page-title">
      <span class="page-title-dot"></span>
      Pesanan Aktif
    </div>
    <div class="page-sub">{{ now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp; Kelola pesanan masuk secara real-time</div>
  </div>
</div>

{{-- STATUS OVERVIEW CHIPS --}}
@php
  $cntAll     = $orders->count();
  $cntUrgent  = $orders->where('payment_method','cash')->where('status','pending')->count();
  $cntPending = $orders->whereIn('status',['pending','waiting_payment'])->count();
  $cntProcess = $orders->where('status','process')->count();
  $cntDone    = $orders->whereIn('status',['done','delivered'])->count();
@endphp

<div class="status-overview" id="statusOverview">
  <div class="stat-chip chip-all active" data-filter="all" onclick="filterByChip(this,'all')">
    <div class="stat-chip-icon">📋</div>
    <div class="stat-chip-body">
      <div class="stat-chip-count">{{ $cntAll }}</div>
      <div class="stat-chip-label">Semua</div>
    </div>
  </div>
  @if($cntUrgent > 0)
  <div class="stat-chip chip-new" data-filter="urgent" onclick="filterByChip(this,'urgent')">
    <div class="stat-chip-icon">🔥</div>
    <div class="stat-chip-body">
      <div class="stat-chip-count">{{ $cntUrgent }}</div>
      <div class="stat-chip-label">Perlu Konfirmasi</div>
    </div>
  </div>
  @endif
  <div class="stat-chip chip-pending" data-filter="pending" onclick="filterByChip(this,'pending')">
    <div class="stat-chip-icon">⏳</div>
    <div class="stat-chip-body">
      <div class="stat-chip-count">{{ $cntPending }}</div>
      <div class="stat-chip-label">Menunggu</div>
    </div>
  </div>
  <div class="stat-chip chip-process" data-filter="process" onclick="filterByChip(this,'process')">
    <div class="stat-chip-icon">🍳</div>
    <div class="stat-chip-body">
      <div class="stat-chip-count">{{ $cntProcess }}</div>
      <div class="stat-chip-label">Di Dapur</div>
    </div>
  </div>
  <div class="stat-chip chip-done" data-filter="done" onclick="filterByChip(this,'done')">
    <div class="stat-chip-icon">✅</div>
    <div class="stat-chip-body">
      <div class="stat-chip-count">{{ $cntDone }}</div>
      <div class="stat-chip-label">Lunas</div>
    </div>
  </div>
</div>

{{-- TOOLBAR: SEARCH + SORT --}}
<div class="toolbar">
  <div class="search-wrap">
    <svg class="search-icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input
      type="text"
      id="searchInput"
      class="search-input"
      placeholder="Cari nomor meja, antrian, atau nama menu…"
      oninput="handleSearch(this.value)"
      autocomplete="off"
    >
    <button class="search-clear" id="searchClear" onclick="clearSearch()" title="Hapus pencarian">✕</button>
  </div>
  <select class="sort-select" id="sortSelect" onchange="handleSort(this.value)">
    <option value="newest">Terbaru</option>
    <option value="oldest">Terlama</option>
    <option value="highest">Total Terbesar</option>
    <option value="lowest">Total Terkecil</option>
    <option value="table">Nomor Meja</option>
  </select>
  <div class="section-count">
    <span class="pill pill-blue" id="countPill">{{ $orders->count() }} pesanan</span>
    @if($cntUrgent > 0)
      <span class="pill pill-orange">🔥 {{ $cntUrgent }} perlu aksi</span>
    @endif
  </div>
</div>

{{-- KANBAN BOARD --}}
<div class="kanban-board" id="orderGrid">

  {{-- KOLOM MENUNGGU --}}
  <div class="kanban-col">
    <div class="kanban-col-header kanban-header-amber">
      <div class="kanban-col-title">
        <span class="kanban-col-icon">⏳</span>
        <span>Menunggu</span>
      </div>
      <span class="kanban-col-count" id="count-pending">0</span>
    </div>
    <div class="kanban-col-body" id="lane-pending"></div>
  </div>

  {{-- KOLOM DI DAPUR --}}
  <div class="kanban-col">
    <div class="kanban-col-header kanban-header-blue">
      <div class="kanban-col-title">
        <span class="kanban-col-icon">🍳</span>
        <span>Di Dapur</span>
      </div>
      <span class="kanban-col-count" id="count-process">0</span>
    </div>
    <div class="kanban-col-body" id="lane-process"></div>
  </div>

  {{-- KOLOM SIAP DIANTAR / SELESAI --}}
  <div class="kanban-col">
    <div class="kanban-col-header kanban-header-green">
      <div class="kanban-col-title">
        <span class="kanban-col-icon">🍽️</span>
        <span>Siap Diantar / Selesai</span>
      </div>
      <span class="kanban-col-count" id="count-done">0</span>
    </div>
    <div class="kanban-col-body" id="lane-done"></div>
  </div>

</div>

{{-- HIDDEN CARD POOL (rendered by blade, distributed by JS) --}}
<div id="cardPool" style="display:none;">

  @forelse($orders as $idx => $order)
  @php
    $midtransMethods = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans'];
    $isMidtrans = in_array($order->payment_method, $midtransMethods);

    if ($order->payment_method === 'cash' && $order->status === 'pending') {
      $cardClass   = 'is-pending-cash';
      $stripeClass = 'stripe-urgent';
      $badgeClass  = 'table-badge-urgent';
      $filterState = 'urgent pending';
    } elseif ($order->payment_method === 'cash' && $order->status === 'process') {
      $cardClass   = 'is-cash-paid';
      $stripeClass = 'stripe-process';
      $badgeClass  = 'table-badge-process';
      $filterState = 'process';
    } elseif ($order->payment_method === 'cash' && in_array($order->status, ['done','delivered'])) {
      $cardClass   = 'is-cash-paid';
      $stripeClass = 'stripe-done';
      $badgeClass  = 'table-badge-done';
      $filterState = 'done';
    } elseif ($order->payment_method === 'qris' && $order->status === 'pending') {
      $cardClass   = 'is-qris';
      $stripeClass = 'stripe-qris';
      $badgeClass  = 'table-badge-qris';
      $filterState = 'pending';
    } elseif ($order->payment_method === 'qris' && $order->status === 'process') {
      $cardClass   = 'is-qris';
      $stripeClass = 'stripe-process';
      $badgeClass  = 'table-badge-process';
      $filterState = 'process';
    } elseif ($order->payment_method === 'qris' && in_array($order->status, ['done','delivered'])) {
      $cardClass   = 'is-qris';
      $stripeClass = 'stripe-done';
      $badgeClass  = 'table-badge-done';
      $filterState = 'done';
    } elseif ($isMidtrans && $order->status === 'waiting_payment') {
      $cardClass   = 'is-midtrans-waiting';
      $stripeClass = 'stripe-pending';
      $badgeClass  = 'table-badge-pending';
      $filterState = 'pending';
    } elseif ($isMidtrans && $order->status === 'process') {
      $cardClass   = 'is-midtrans-paid';
      $stripeClass = 'stripe-process';
      $badgeClass  = 'table-badge-process';
      $filterState = 'process';
    } elseif ($isMidtrans && in_array($order->status, ['done','delivered'])) {
      $cardClass   = 'is-midtrans-paid';
      $stripeClass = 'stripe-done';
      $badgeClass  = 'table-badge-done';
      $filterState = 'done';
    } else {
      $cardClass   = '';
      $stripeClass = 'stripe-pending';
      $badgeClass  = 'table-badge-pending';
      $filterState = 'pending';
    }

    // Label metode pembayaran Midtrans
    $midtransLabel = match($order->payment_method) {
      'gopay'       => '💚 GoPay',
      'ovo'         => '🟣 OVO',
      'dana'        => '🔵 DANA',
      'shopeepay'   => '🟠 ShopeePay',
      'bca'         => '🏦 VA BCA',
      'bni'         => '🏦 VA BNI',
      'bri'         => '🏦 VA BRI',
      'mandiri'     => '🏦 Mandiri',
      'permata'     => '🏦 Permata',
      'credit_card' => '💳 Kartu Kredit',
      default       => '💳 Midtrans',
    };

    $urgentClass = ($order->payment_method === 'cash' && $order->status === 'pending') ? ' is-new-urgent' : '';

    $minutesAgo = $order->created_at->diffInMinutes(now());
    if ($minutesAgo < 5)       { $elapsedClass = 'elapsed-urgent'; $elapsedText = 'Baru '.$minutesAgo.'m lalu'; }
    elseif ($minutesAgo < 20)  { $elapsedClass = 'elapsed-warn';   $elapsedText = $minutesAgo.'m lalu'; }
    else                       { $elapsedClass = 'elapsed-normal';  $elapsedText = $order->created_at->diffForHumans(); }

    $queueNum = str_pad($order->id % 100, 3, '0', STR_PAD_LEFT);
  @endphp

  <div
    class="order-card {{ $cardClass }}{{ $urgentClass }}"
    data-state="{{ $filterState }}"
    data-table="{{ $order->table_number ?? '' }}"
    data-queue="{{ $queueNum }}"
    data-total="{{ $order->total }}"
    data-created="{{ $order->created_at->timestamp }}"
    data-search="{{ strtolower(($order->table_number ?? '') . ' ' . $queueNum . ' ' . ($order->customer_name ?? '') . ' ' . ($order->note ?? '') . ' ' . implode(' ', $order->items ? $order->items->pluck('name')->toArray() : [])) }}"
    style="animation-delay: {{ $idx * 0.05 }}s"
  >
    {{-- COLORED STATUS STRIPE --}}
    <div class="status-stripe {{ $stripeClass }}"></div>

    {{-- QUEUE NUMBER BADGE --}}
    <div class="queue-badge">{{ $order->queue_number ?: 'A-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>

    {{-- CARD TOP --}}
    <div class="order-card-top">
      <div class="oc-left">
        <div class="table-badge {{ $badgeClass }}">🍽️</div>
        <div class="oc-info">
          <h3>Meja {{ $order->table_number ?? '—' }}</h3>
          <div class="oc-meta">
            <span class="oc-time-inline">{{ $order->created_at->translatedFormat('H:i') }}</span>
            <span class="elapsed-badge {{ $elapsedClass }}">{{ $elapsedText }}</span>
          </div>
          @if($order->customer_name)
            <div style="font-size:11.5px;color:var(--text-muted);margin-top:3px;font-family:'Inter',sans-serif;">👤 {{ $order->customer_name }}</div>
          @endif
          @if($order->note)
            <div style="font-size:11.5px;color:var(--text-muted);margin-top:4px;font-family:'Inter',sans-serif;">📝 {{ $order->note }}</div>
          @endif
        </div>
      </div>
      <div class="oc-right">
        @if($order->payment_method === 'cash')
          <span class="pay-badge pay-cash">💵 Cash</span>
        @elseif($order->payment_method === 'qris')
          <span class="pay-badge pay-qris">📱 QRIS</span>
        @elseif($isMidtrans)
          <span class="pay-badge" style="background:#f0fdf4;color:#065f46;border:1px solid #a7f3d0;">{{ $midtransLabel }}</span>
        @endif
      </div>
    </div>

    {{-- ITEM LIST --}}
    <div class="order-items">
      @if($order->items && $order->items->count() > 0)
     @foreach($order->items as $item)
<div class="item-row">
    <div class="item-row-left">

        <div class="item-name">
            {{ !empty($item->name) ? $item->name : ($item->menu->name ?? '-') }}
            <span class="item-qty">
                ×{{ $item->qty ?? 1 }}
            </span>
        </div>

        @if(!empty($item->notes))
            <div class="item-notes-small">
                📝 {{ $item->notes }}
            </div>
        @endif

    </div>

    <span class="item-price">
        Rp {{ number_format(($item->subtotal > 0 ? $item->subtotal : (($item->price ?? 0) * ($item->qty ?? 1))), 0, ',', '.') }}
    </span>
</div>
@endforeach
      @else
        <div class="item-row">
          <div class="item-row-left"><div class="item-name">Total Pesanan</div></div>
          <span class="item-price">Rp {{ number_format($order->total) }}</span>
        </div>
      @endif
    </div>

    {{-- TOTAL --}}
    <div class="total-row">
      <span class="total-label">Total</span>
      <span class="total-value">Rp {{ number_format($order->total) }}</span>
    </div>

    {{-- STATUS BANNERS --}}
    @if($order->payment_method === 'cash' && $order->status === 'pending')
      <div class="cash-alert">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Customer menunjukkan bill — verifikasi nomor antrian &amp; terima uang cash sebelum konfirmasi!
      </div>
    @elseif($order->payment_method === 'cash' && $order->status === 'process')
      <div class="lunas-banner lunas-banner-cash">
        <div class="lunas-banner-icon">🍳</div>
        <div class="lunas-banner-text">
          <div class="lunas-banner-title">LUNAS — Sedang Dimasak Dapur</div>
          <div class="lunas-banner-sub">Pembayaran cash diterima, pesanan sedang diproses dapur</div>
        </div>
      </div>
    @elseif($order->payment_method === 'cash' && $order->status === 'done')
      <div class="lunas-banner lunas-banner-cash">
        <div class="lunas-banner-icon">🍽️</div>
        <div class="lunas-banner-text">
          <div class="lunas-banner-title">SIAP DIANTAR — Menunggu Pelayan</div>
          <div class="lunas-banner-sub">Makanan sudah selesai dimasak, pelayan sedang mengantar</div>
        </div>
      </div>
    @elseif($order->payment_method === 'cash' && $order->status === 'delivered')
      <div class="lunas-banner lunas-banner-cash">
        <div class="lunas-banner-icon">✅</div>
        <div class="lunas-banner-text">
          <div class="lunas-banner-title">SELESAI — Pesanan Sudah Diantar</div>
          <div class="lunas-banner-sub">Transaksi cash selesai sempurna</div>
        </div>
      </div>
    @elseif($order->payment_method === 'qris' && in_array($order->status, ['process','done','delivered']))
      <div class="lunas-banner lunas-banner-qris">
        <div class="lunas-banner-icon">
          @if($order->status === 'process') 🍳
          @elseif($order->status === 'done') 🍽️
          @else ✅
          @endif
        </div>
        <div class="lunas-banner-text">
          <div class="lunas-banner-title">
            @if($order->status === 'process') LUNAS — Sedang Dimasak Dapur
            @elseif($order->status === 'done') SIAP DIANTAR — Menunggu Pelayan
            @else SELESAI — Pesanan Sudah Diantar
            @endif
          </div>
          <div class="lunas-banner-sub">Pembayaran QRIS terverifikasi otomatis</div>
        </div>
      </div>
    @elseif($order->payment_method === 'qris' && $order->status === 'pending')
      <div class="status-info-box box-cash-pending">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Menunggu konfirmasi pembayaran QRIS dari sistem...
      </div>
    @elseif($isMidtrans && $order->status === 'waiting_payment')
      <div class="status-info-box" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Menunggu pembayaran {{ $midtransLabel }} dari customer...
      </div>
    @elseif($isMidtrans && $order->status === 'process')
      <div class="lunas-banner" style="background:#f0fdf4;border:1px solid #a7f3d0;">
        <div class="lunas-banner-icon">🍳</div>
        <div class="lunas-banner-text">
          <div class="lunas-banner-title" style="color:#065f46;">LUNAS — Sedang Dimasak Dapur</div>
          <div class="lunas-banner-sub">{{ $midtransLabel }} terverifikasi, pesanan masuk dapur</div>
        </div>
      </div>
    @elseif($isMidtrans && $order->status === 'done')
      <div class="lunas-banner" style="background:#f0fdf4;border:1px solid #a7f3d0;">
        <div class="lunas-banner-icon">🍽️</div>
        <div class="lunas-banner-text">
          <div class="lunas-banner-title" style="color:#065f46;">SIAP DIANTAR — Menunggu Pelayan</div>
          <div class="lunas-banner-sub">{{ $midtransLabel }} — makanan siap, pelayan sedang mengantar</div>
        </div>
      </div>
    @elseif($isMidtrans && $order->status === 'delivered')
      <div class="lunas-banner" style="background:#f0fdf4;border:1px solid #a7f3d0;">
        <div class="lunas-banner-icon">✅</div>
        <div class="lunas-banner-text">
          <div class="lunas-banner-title" style="color:#065f46;">SELESAI — {{ $midtransLabel }}</div>
          <div class="lunas-banner-sub">Pesanan sudah diantar ke pelanggan</div>
        </div>
      </div>
    @endif

    {{-- CARD FOOTER --}}
    <div class="order-footer">
      <div class="status-pills">
        @if($order->payment_method === 'cash' && $order->status === 'pending')
          <span class="pill pill-amber">⏳ Menunggu Bayar</span>
          <span class="pill pill-orange">💵 Cash</span>
        @elseif($order->payment_method === 'cash' && $order->status === 'process')
          <span class="pill pill-green">✅ Lunas</span>
          <span class="pill pill-blue">🍳 Diproses Dapur</span>
        @elseif($order->payment_method === 'cash' && $order->status === 'done')
          <span class="pill pill-green">✅ Lunas</span>
          <span class="pill pill-indigo">🍽️ Siap Diantar</span>
        @elseif($order->payment_method === 'cash' && $order->status === 'delivered')
          <span class="pill pill-green">✅ Lunas &amp; Selesai</span>
          <span class="pill pill-orange">💵 Cash</span>
        @elseif($order->payment_method === 'qris' && $order->status === 'pending')
          <span class="pill pill-amber">⏳ Menunggu QRIS</span>
          <span class="pill pill-indigo">📱 QRIS</span>
        @elseif($order->payment_method === 'qris' && $order->status === 'process')
          <span class="pill pill-green">✅ Lunas</span>
          <span class="pill pill-blue">🔥 Diproses Dapur</span>
        @elseif($order->payment_method === 'qris' && $order->status === 'done')
          <span class="pill pill-green">✅ Lunas</span>
          <span class="pill pill-indigo">🍽️ Siap Diantar</span>
        @elseif($order->payment_method === 'qris' && $order->status === 'delivered')
          <span class="pill pill-green">✅ Lunas &amp; Selesai</span>
          <span class="pill pill-indigo">📱 QRIS</span>
        @elseif($isMidtrans && $order->status === 'waiting_payment')
          <span class="pill pill-amber">⏳ Menunggu Bayar</span>
          <span class="pill" style="background:#f0fdf4;color:#065f46;border:1px solid #a7f3d0;">{{ $midtransLabel }}</span>
        @elseif($isMidtrans && $order->status === 'process')
          <span class="pill pill-green">✅ Lunas</span>
          <span class="pill pill-blue">🍳 Diproses Dapur</span>
          <span class="pill" style="background:#f0fdf4;color:#065f46;border:1px solid #a7f3d0;">{{ $midtransLabel }}</span>
        @elseif($isMidtrans && $order->status === 'done')
          <span class="pill pill-green">✅ Lunas</span>
          <span class="pill pill-indigo">🍽️ Siap Diantar</span>
          <span class="pill" style="background:#f0fdf4;color:#065f46;border:1px solid #a7f3d0;">{{ $midtransLabel }}</span>
        @elseif($isMidtrans && $order->status === 'delivered')
          <span class="pill pill-green">✅ Lunas &amp; Selesai</span>
          <span class="pill" style="background:#f0fdf4;color:#065f46;border:1px solid #a7f3d0;">{{ $midtransLabel }}</span>
        @else
          <span class="pill pill-amber">{{ ucfirst($order->status) }}</span>
        @endif
      </div>
      <div class="action-btns">
        @if($order->payment_method === 'cash' && $order->status === 'pending')
          {{-- Tombol buka modal cash --}}
          <button
            type="button"
            class="act-btn ab-orange"
            onclick="openCashModal({{ $order->id }}, {{ $order->total }}, '{{ addslashes(($order->order_type ?? 'dine_in') === 'takeaway' ? 'Takeaway' : ($order->table_number ?? '-')) }}')"
          >
            💵 Bayar Sekarang
          </button>
        @elseif($order->status === 'done')
          {{-- Pesanan sudah selesai dimasak, kasir bisa tandai selesai diantar --}}
          <form action="{{ url('/kasir/pesanan/' . $order->id . '/selesai') }}" method="POST" style="display:inline;" class="form-selesai">
            @csrf
            @method('PATCH')
            <button type="submit" class="act-btn ab-success">✅ Selesai Diantar</button>
          </form>
        @elseif($order->status === 'process')
          {{-- Sedang dimasak dapur, kasir tidak bisa lakukan apa-apa --}}
          <span style="font-size:11px;color:var(--text-muted);font-family:'Inter',sans-serif;">🍳 Sedang dimasak...</span>
        @elseif($isMidtrans && $order->status === 'waiting_payment')
          <span style="font-size:11px;color:var(--text-muted);font-family:'Inter',sans-serif;">⏳ Menunggu pembayaran online</span>
        @elseif($order->status === 'delivered')
          <span style="font-size:11px;color:var(--green);font-family:'Inter',sans-serif;">✅ Selesai</span>
        @endif
      </div>
    </div>

  </div>{{-- .order-card --}}
  @empty
  @endforelse

</div>{{-- #cardPool --}}

{{-- EMPTY STATE (shown by JS if no cards in a lane) --}}

{{-- ══════════════════════════════════════
     MODAL CASH PAYMENT
══════════════════════════════════════ --}}
<div class="modal-overlay" id="cashModal" onclick="handleOverlayClick(event)">
  <div class="modal-box">

    <div class="modal-head">
      <div class="modal-icon">💵</div>
      <div class="modal-head-info">
        <h2>Konfirmasi Pembayaran Cash</h2>
        <p id="modalTableLabel">Meja —</p>
      </div>
    </div>

    <div class="modal-body">
      <div class="modal-total-block">
        <div class="modal-total-label">Total yang Harus Dibayar</div>
        <div class="modal-total-amount" id="modalTotalDisplay">Rp 0</div>
      </div>

      <div class="modal-field">
        <label for="cashInput">Uang Diterima dari Customer</label>
        <div class="input-money-wrap">
          <span class="input-money-prefix">Rp</span>
          <input
            type="number"
            id="cashInput"
            class="input-money"
            placeholder="0"
            oninput="calcChange()"
            min="0"
          >
        </div>
        <div class="quick-cash" id="quickCashBtns"></div>
      </div>

      <div class="modal-change-block" id="changeBlock">
        <span class="modal-change-label" id="changeLabel">Kembalian</span>
        <span class="modal-change-value" id="changeValue">Rp 0</span>
      </div>
    </div>

    <form id="cashConfirmForm" method="POST" action="">
      @csrf
      @method('PATCH')
      <input type="hidden" name="uang_diterima" id="hiddenUangDiterima" value="0">
      <div class="modal-foot">
        <button type="button" class="modal-btn modal-btn-cancel" onclick="closeCashModal()">
          Batal
        </button>
        <button type="submit" class="modal-btn modal-btn-confirm" id="confirmBtn" disabled>
          ✅ Konfirmasi Lunas
        </button>
      </div>
    </form>

  </div>
</div>

{{-- TOAST CONTAINER --}}
<div
  id="ksToastContainer"
  style="position:fixed;bottom:24px;right:24px;display:flex;flex-direction:column;gap:8px;z-index:9999;pointer-events:none;"
></div>

@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════
   TOAST SYSTEM — didefinisikan PERTAMA
═══════════════════════════════════════════════ */
function ksToast(msg, type, dur) {
  type = type || 'success';
  dur  = dur  || 2400;
  var c = document.getElementById('ksToastContainer');
  if (!c) return;

  var colors = {
    success : 'background:linear-gradient(135deg,#059669,#047857);',
    info    : 'background:linear-gradient(135deg,#2563eb,#1d4ed8);',
    warning : 'background:linear-gradient(135deg,#d97706,#b45309);',
    error   : 'background:linear-gradient(135deg,#dc2626,#b91c1c);'
  };
  var icons = { success:'✅', info:'ℹ️', warning:'⚠️', error:'❌' };

  var t = document.createElement('div');
  t.style.cssText = [
    'pointer-events:auto;display:flex;align-items:center;gap:9px;',
    'padding:11px 18px;border-radius:12px;',
    'box-shadow:0 8px 24px rgba(0,0,0,0.18);',
    'font-size:13px;font-weight:600;',
    'font-family:"Plus Jakarta Sans",sans-serif;',
    'white-space:nowrap;color:white;',
    'opacity:0;transform:translateX(18px) scale(0.95);',
    'transition:all 0.25s cubic-bezier(.34,1.56,.64,1);',
    'max-width:340px;',
    (colors[type] || colors.info)
  ].join('');

  t.innerHTML = '<span style="font-size:15px;">' + (icons[type] || '📢') + '</span>'
              + '<span>' + msg + '</span>';
  c.appendChild(t);

  requestAnimationFrame(function() {
    t.style.opacity   = '1';
    t.style.transform = 'translateX(0) scale(1)';
  });

  setTimeout(function() {
    t.style.opacity   = '0';
    t.style.transform = 'translateX(18px) scale(0.95)';
    setTimeout(function() { t.remove(); }, 260);
  }, dur);
}

/* ═══════════════════════════════════════════════
   LIVE CLOCK
═══════════════════════════════════════════════ */
(function startClock() {
  var el = document.getElementById('liveClock');
  if (!el) return;
  function tick() {
    var now = new Date();
    el.textContent = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
  }
  tick();
  setInterval(tick, 1000);
})();

/* ═══════════════════════════════════════════════
   DROPDOWN PROFILE
═══════════════════════════════════════════════ */
function toggleProfile() {
  var btn  = document.getElementById('profileBtn');
  var drop = document.getElementById('profileDropdown');
  if (!btn || !drop) return;
  var isOpen = drop.classList.toggle('show');
  btn.classList.toggle('open', isOpen);
}

document.addEventListener('click', function(e) {
  var wrap = document.querySelector('.profile-wrap');
  if (wrap && !wrap.contains(e.target)) {
    var drop = document.getElementById('profileDropdown');
    var btn  = document.getElementById('profileBtn');
    if (drop) drop.classList.remove('show');
    if (btn)  btn.classList.remove('open');
  }
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    var drop = document.getElementById('profileDropdown');
    var btn  = document.getElementById('profileBtn');
    if (drop) drop.classList.remove('show');
    if (btn)  btn.classList.remove('open');
    closeCashModal();
  }
});

/* ═══════════════════════════════════════════════
   SESSION SUCCESS TOAST — dijalankan setelah DOM ready
═══════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function() {
  @if(session('success'))
    ksToast('✅ {{ addslashes(session('success')) }}', 'success', 3500);
  @endif

  /* ── DISTRIBUTE CARDS TO KANBAN LANES ── */
  var pool  = document.getElementById('cardPool');
  var cards = pool ? Array.from(pool.querySelectorAll('.order-card')) : [];

  var lanes = {
    pending : document.getElementById('lane-pending'),
    process : document.getElementById('lane-process'),
    done    : document.getElementById('lane-done'),
  };

  var counts = { pending: 0, process: 0, done: 0 };

  cards.forEach(function(card) {
    var state = (card.dataset.state || '').trim();
    var lane  = null;

    if (state.includes('process')) {
      lane = lanes.process;
      counts.process++;
    } else if (state.includes('done')) {
      lane = lanes.done;
      counts.done++;
    } else {
      // pending / urgent / default → menunggu
      lane = lanes.pending;
      counts.pending++;
    }

    if (lane) lane.appendChild(card);
  });

  /* Update count badges */
  ['pending','process','done'].forEach(function(k) {
    var el = document.getElementById('count-' + k);
    if (el) el.textContent = counts[k];

    /* Show empty state if lane is empty */
    var laneEl = lanes[k];
    if (laneEl && counts[k] === 0) {
      var emptyLabels = { pending:'Tidak ada pesanan menunggu', process:'Tidak ada yang di dapur', done:'Belum ada yang siap diantar' };
      var emptyIcons  = { pending:'⏳', process:'🍳', done:'🍽️' };
      var div = document.createElement('div');
      div.className = 'kanban-empty';
      div.innerHTML = '<span>' + emptyIcons[k] + '</span>' + emptyLabels[k];
      laneEl.appendChild(div);
    }
  });

  /* Update stat chips counts */
  document.getElementById('count-pending') && (document.getElementById('count-pending').textContent = counts.pending);

  /* Feedback form "Selesai" */
  document.querySelectorAll('.form-selesai').forEach(function(form) {
    form.addEventListener('submit', function() {
      ksToast('⏳ Memproses pesanan...', 'info', 2000);
    });
  });

  /* Feedback form cash konfirmasi */
  var cashForm = document.getElementById('cashConfirmForm');
  if (cashForm) {
    cashForm.addEventListener('submit', function() {
      ksToast('💵 Konfirmasi pembayaran cash diproses...', 'success', 3000);
    });
  }
});

/* ═══════════════════════════════════════════════
   FILTER BY STATUS CHIP
═══════════════════════════════════════════════ */
var activeFilter = 'all';
var activeSearch = '';

function filterByChip(el, state) {
  document.querySelectorAll('.stat-chip').forEach(function(c) { c.classList.remove('active'); });
  el.classList.add('active');
  activeFilter = state;
  var labels = { all:'Semua pesanan', urgent:'Perlu konfirmasi', pending:'Menunggu', process:'Di dapur', done:'Lunas' };
  ksToast('Filter: ' + (labels[state] || state), 'info', 1600);
  applyFilters();
}

/* ═══════════════════════════════════════════════
   SEARCH
═══════════════════════════════════════════════ */
function handleSearch(val) {
  activeSearch = val.trim().toLowerCase();
  var clearBtn = document.getElementById('searchClear');
  if (clearBtn) clearBtn.classList.toggle('visible', activeSearch.length > 0);
  applyFilters();
}

function clearSearch() {
  var inp = document.getElementById('searchInput');
  if (inp) inp.value = '';
  activeSearch = '';
  var clearBtn = document.getElementById('searchClear');
  if (clearBtn) clearBtn.classList.remove('visible');
  ksToast('Pencarian dihapus', 'info', 1400);
  applyFilters();
}

/* ═══════════════════════════════════════════════
   SORT
═══════════════════════════════════════════════ */
function handleSort(val) {
  var grid  = document.getElementById('orderGrid');
  var cards = Array.from(grid.querySelectorAll('.order-card'));

  cards.sort(function(a, b) {
    if (val === 'newest')  return Number(b.dataset.created) - Number(a.dataset.created);
    if (val === 'oldest')  return Number(a.dataset.created) - Number(b.dataset.created);
    if (val === 'highest') return Number(b.dataset.total)   - Number(a.dataset.total);
    if (val === 'lowest')  return Number(a.dataset.total)   - Number(b.dataset.total);
    if (val === 'table') {
      var ta = a.dataset.table || '999', tb = b.dataset.table || '999';
      return ta.localeCompare(tb, undefined, { numeric: true });
    }
    return 0;
  });

  cards.forEach(function(c) { grid.appendChild(c); });

  var sortLabels = { newest:'Terbaru', oldest:'Terlama', highest:'Total Terbesar', lowest:'Total Terkecil', table:'Nomor Meja' };
  ksToast('Urutan: ' + (sortLabels[val] || val), 'info', 1500);
}

/* ═══════════════════════════════════════════════
   APPLY FILTERS (state + search digabung)
═══════════════════════════════════════════════ */
function applyFilters() {
  var cards   = document.querySelectorAll('#lane-pending .order-card, #lane-process .order-card, #lane-done .order-card');
  var visible = 0;

  cards.forEach(function(card) {
    var search = card.dataset.search || '';
    var table  = (card.dataset.table || '').toLowerCase();
    var queue  = (card.dataset.queue || '').toLowerCase();

    var searchOk = true;
    if (activeSearch) {
      searchOk = search.includes(activeSearch)
              || table.includes(activeSearch)
              || queue.includes(activeSearch);
    }

    card.style.display = searchOk ? '' : 'none';
    if (searchOk) visible++;
  });

  /* Update count pill */
  var pill = document.getElementById('countPill');
  if (pill) pill.textContent = visible + ' pesanan';

  /* Update per-lane count badges & show empty msg per lane */
  ['pending','process','done'].forEach(function(lane) {
    var laneEl    = document.getElementById('lane-' + lane);
    var countEl   = document.getElementById('count-' + lane);
    var emptyEl   = laneEl ? laneEl.querySelector('.kanban-empty') : null;
    if (!laneEl) return;

    var laneCards   = laneEl.querySelectorAll('.order-card');
    var laneVisible = 0;
    laneCards.forEach(function(c) {
      if (c.style.display !== 'none') laneVisible++;
    });

    if (countEl) countEl.textContent = laneVisible;

    /* Show/hide empty placeholder per lane */
    if (emptyEl) {
      emptyEl.style.display = (laneVisible === 0 && laneCards.length > 0) ? 'block' : '';
    }
  });
}


/* ═══════════════════════════════════════════════
   MODAL CASH
═══════════════════════════════════════════════ */
var _modalTotal = 0;

function formatRp(n) {
  return 'Rp ' + Math.abs(n).toLocaleString('id-ID');
}

function openCashModal(orderId, total, tableLabel) {
  _modalTotal = total;

  var labelEl = document.getElementById('modalTableLabel');
  if (labelEl) labelEl.textContent = 'Meja ' + tableLabel;

  var totalEl = document.getElementById('modalTotalDisplay');
  if (totalEl) totalEl.textContent = formatRp(total);

  var form = document.getElementById('cashConfirmForm');
  if (form) form.action = '/kasir/pesanan/' + orderId + '/konfirmasi';

  var input = document.getElementById('cashInput');
  if (input) {
    input.value = '';
    input.classList.remove('error');
  }

  buildQuickCash(total);
  calcChange();

  var modal = document.getElementById('cashModal');
  if (modal) modal.classList.add('show');

  ksToast('💵 Konfirmasi pembayaran — Meja ' + tableLabel, 'info', 2000);
  setTimeout(function() { if (input) input.focus(); }, 280);
}

function closeCashModal() {
  var modal = document.getElementById('cashModal');
  if (modal) modal.classList.remove('show');
}

function handleOverlayClick(e) {
  if (e.target === document.getElementById('cashModal')) {
    closeCashModal();
  }
}

function buildQuickCash(total) {
  var container = document.getElementById('quickCashBtns');
  if (!container) return;
  container.innerHTML = '';

  var nominals = [total];
  var rounds   = [5000, 10000, 20000, 50000, 100000];

  for (var i = 0; i < rounds.length; i++) {
    var r   = rounds[i];
    var val = Math.ceil(total / r) * r;
    if (val > total && nominals.indexOf(val) === -1) nominals.push(val);
    if (nominals.length >= 5) break;
  }
  nominals.sort(function(a, b) { return a - b; });

  nominals.forEach(function(n) {
    var btn = document.createElement('button');
    btn.type      = 'button';
    btn.className = 'qc-btn';
    btn.textContent = (n === total) ? '💯 Pas ' + formatRp(n) : formatRp(n);
    btn.onclick = (function(amount) {
      return function() {
        var inp = document.getElementById('cashInput');
        if (inp) inp.value = amount;
        calcChange();
      };
    })(n);
    container.appendChild(btn);
  });
}

function calcChange() {
  var input       = document.getElementById('cashInput');
  var changeBlock = document.getElementById('changeBlock');
  var changeLabel = document.getElementById('changeLabel');
  var changeValue = document.getElementById('changeValue');
  var confirmBtn  = document.getElementById('confirmBtn');
  var hidden      = document.getElementById('hiddenUangDiterima');

  if (!input || !changeBlock || !changeLabel || !changeValue || !confirmBtn) return;

  var received = parseInt(input.value) || 0;
  var change   = received - _modalTotal;

  if (hidden) hidden.value = received;

  input.classList.remove('error');
  changeBlock.classList.remove('insuf', 'exact');

  if (received === 0) {
    changeLabel.textContent = 'Kembalian';
    changeValue.textContent = 'Rp 0';
    confirmBtn.disabled     = true;
    return;
  }

  if (change < 0) {
    changeBlock.classList.add('insuf');
    changeLabel.textContent = '⚠️ Kurang';
    changeValue.textContent = '−' + formatRp(change);
    confirmBtn.disabled     = true;
    input.classList.add('error');
    ksToast('Uang kurang ' + formatRp(Math.abs(change)), 'error', 1800);
  } else if (change === 0) {
    changeBlock.classList.add('exact');
    changeLabel.textContent = '✨ Pas, tidak ada kembalian';
    changeValue.textContent = '';
    confirmBtn.disabled     = false;
    ksToast('✨ Uang pas! Bisa langsung konfirmasi', 'success', 1800);
  } else {
    changeLabel.textContent = '💰 Kembalian';
    changeValue.textContent = formatRp(change);
    confirmBtn.disabled     = false;
    ksToast('Kembalian: ' + formatRp(change), 'success', 1800);
  }
}
</script>
@endpush