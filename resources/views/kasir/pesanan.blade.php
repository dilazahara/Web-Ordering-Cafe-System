<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Kasir — Pesanan</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
.hdr-btn { position: relative; width: 38px; height: 38px; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-secondary); transition: all .18s; }
.hdr-btn:hover { background: var(--accent-bg); border-color: #bfcfff; color: var(--accent); }
.hdr-btn svg { width: 18px; height: 18px; stroke: currentColor; stroke-width: 2; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.notif-badge { position: absolute; top: -4px; right: -4px; min-width: 17px; height: 17px; background: var(--red); color: white; font-size: 10px; font-weight: 700; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 0 4px; border: 2px solid white; font-family: 'Inter', sans-serif; }
.divider-v { width: 1px; height: 28px; background: var(--border); margin: 0 4px; }

.profile-wrap { position: relative; }
.user-btn { display: flex; align-items: center; gap: 10px; padding: 5px 12px 5px 5px; border: 1px solid var(--border); border-radius: 12px; background: var(--surface); cursor: pointer; transition: all 0.18s; user-select: none; }
.user-btn:hover { background: var(--surface-2); border-color: var(--border-strong); box-shadow: var(--shadow-sm); }
.user-btn.open { border-color: var(--accent); background: var(--accent-bg); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }

/* PERBAIKAN: Penambahan overflow: hidden dan styling img pada .avatar */
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

/* PERBAIKAN: Penambahan overflow: hidden dan styling img pada .dropdown-avatar */
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
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
.section-title { font-size: 15px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 8px; letter-spacing: -0.3px; }
.section-title svg { width: 16px; height: 16px; stroke: var(--accent); stroke-width: 2.5; fill: none; stroke-linecap: round; stroke-linejoin: round; }
.section-actions { display: flex; align-items: center; gap: 8px; }

.pill { padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; font-family: 'Inter', sans-serif; display: inline-flex; align-items: center; gap: 4px; }
.pill-blue   { background: var(--accent-bg); color: var(--accent-text); border: 1px solid #bfdbfe; }
.pill-green  { background: var(--green-bg);  color: var(--green-text);  border: 1px solid #a7f3d0; }
.pill-amber  { background: var(--amber-bg);  color: var(--amber-text);  border: 1px solid #fde68a; }
.pill-red    { background: var(--red-bg);    color: var(--red-text);    border: 1px solid #fecaca; }
.pill-orange { background: var(--orange-bg); color: var(--orange-text); border: 1px solid #fed7aa; }
.pill-indigo { background: var(--indigo-bg); color: var(--indigo-text); border: 1px solid #c7d2fe; }

.filter-btn { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); font-size: 12.5px; font-weight: 600; color: var(--text-secondary); cursor: pointer; transition: all 0.18s; font-family: 'Plus Jakarta Sans', sans-serif; }
.filter-btn:hover { border-color: #bfcfff; color: var(--accent); background: var(--accent-bg); }
.filter-btn svg { width: 13px; height: 13px; stroke: currentColor; stroke-width: 2; fill: none; stroke-linecap: round; stroke-linejoin: round; }

.order-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 18px; }
.order-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow); transition: box-shadow 0.22s, transform 0.22s; }
.order-card:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }

/* Cash pending: border amber */
.order-card.is-pending-cash { border-color: #fde68a; box-shadow: 0 2px 8px rgb(217 119 6/.15), 0 0 0 1px #fde68a; }
.order-card.is-pending-cash .order-card-top { background: var(--amber-bg); }

/* Cash lunas: border hijau */
.order-card.is-cash-paid { border-color: #a7f3d0; box-shadow: 0 2px 8px rgb(5 150 105/.12), 0 0 0 1px #a7f3d0; }
.order-card.is-cash-paid .order-card-top { background: var(--green-bg); }

/* QRIS: border indigo/ungu */
.order-card.is-qris { border-color: #c7d2fe; box-shadow: 0 2px 8px rgb(79 70 229/.12), 0 0 0 1px #c7d2fe; }
.order-card.is-qris .order-card-top { background: var(--indigo-bg); }

.order-card-top { padding: 20px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 12px; background: var(--surface-2); }
.oc-left { display: flex; align-items: center; gap: 14px; }
.table-badge { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; background: var(--accent-bg); border: 1px solid #bfdbfe; }
.oc-info h3 { font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 3px; }
.oc-info p { font-size: 12px; color: var(--text-secondary); font-family: 'Inter', sans-serif; }
.oc-right { display: flex; flex-direction: column; align-items: flex-end; gap: 7px; }
.oc-time { font-size: 14px; font-weight: 700; color: var(--text-secondary); font-family: 'Inter', sans-serif; background: var(--surface); padding: 3px 8px; border-radius: 7px; border: 1px solid var(--border); }
.pay-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 8px; font-size: 11px; font-weight: 700; font-family: 'Inter', sans-serif; }
.pay-cash  { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.pay-qris  { background: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe; }

.order-items { padding: 18px 22px; }
.item-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding: 8px 0; color: var(--text-secondary); border-bottom: 1px dashed var(--border); font-family: 'Inter', sans-serif; }
.item-row:last-child { border-bottom: none; }
.item-name { font-weight: 500; color: var(--text-primary); }
.item-notes-small { font-size: 11px; color: var(--amber); margin-top: 2px; }
.item-price { font-weight: 700; color: var(--text-primary); font-variant-numeric: tabular-nums; }
.total-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 22px; background: linear-gradient(to right, var(--accent-bg), #f0f4ff); border-top: 1px solid #dbe4ff; }
.total-label { font-size: 12.5px; font-weight: 700; color: var(--accent-text); text-transform: uppercase; letter-spacing: 0.6px; font-family: 'Inter', sans-serif; }
.total-value { font-size: 18px; font-weight: 800; color: var(--accent); font-variant-numeric: tabular-nums; }

/* ── Status info boxes ── */
.status-info-box { margin: 0 22px 14px; padding: 10px 14px; border-radius: 10px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.status-info-box svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }

/* Lunas Cash */
.box-cash-paid  { background: var(--green-bg);  border: 1px solid #a7f3d0; color: var(--green-text); }
/* Lunas QRIS */
.box-qris-paid  { background: var(--indigo-bg); border: 1px solid #c7d2fe;  color: var(--indigo-text); }
/* QRIS Diproses */
.box-qris-process { background: var(--green-bg); border: 1px solid #a7f3d0; color: var(--green-text); }
/* Cash pending */
.box-cash-pending { background: var(--amber-bg); border: 1px solid #fde68a; color: var(--amber-text); }

/* ── LABEL LUNAS BESAR di dalam card ── */
.lunas-banner { margin: 0 22px 14px; padding: 14px 18px; border-radius: 12px; display: flex; align-items: center; gap: 12px; }
.lunas-banner-cash  { background: linear-gradient(135deg, #d1fae5, #a7f3d0); border: 1.5px solid #6ee7b7; }
.lunas-banner-qris  { background: linear-gradient(135deg, #ede9fe, #ddd6fe); border: 1.5px solid #a5b4fc; }
.lunas-banner-icon  { font-size: 24px; flex-shrink: 0; }
.lunas-banner-text  { flex: 1; }
.lunas-banner-title { font-size: 14px; font-weight: 800; letter-spacing: -0.2px; }
.lunas-banner-cash  .lunas-banner-title { color: var(--green-text); }
.lunas-banner-qris  .lunas-banner-title { color: var(--indigo-text); }
.lunas-banner-sub   { font-size: 11.5px; font-family: 'Inter', sans-serif; margin-top: 2px; }
.lunas-banner-cash  .lunas-banner-sub  { color: #047857; }
.lunas-banner-qris  .lunas-banner-sub  { color: #5b21b6; }

.cash-alert { margin: 0 22px 14px; padding: 10px 14px; background: var(--amber-bg); border: 1px solid #fde68a; border-radius: 10px; font-size: 12px; color: var(--amber-text); font-weight: 600; display: flex; align-items: center; gap: 8px; }
.cash-alert svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }

.order-footer { padding: 14px 22px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; background: var(--surface); }
.status-pills { display: flex; gap: 6px; flex-wrap: wrap; }
.action-btns { display: flex; gap: 8px; flex-shrink: 0; }
.act-btn { padding: 8px 16px; border-radius: 10px; font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all 0.18s; border: none; font-family: 'Plus Jakarta Sans', sans-serif; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
.act-btn:hover { transform: translateY(-1px); }
.act-btn:active { transform: scale(0.96); }
.ab-primary { background: linear-gradient(135deg, #3b82f6, #1d55d8); color: white; box-shadow: 0 3px 10px rgb(37 99 235/.28); }
.ab-primary:hover { box-shadow: 0 6px 16px rgb(37 99 235/.38); filter: brightness(1.07); }
.ab-success { background: linear-gradient(135deg, #10b981, #059669); color: white; box-shadow: 0 3px 10px rgb(5 150 105/.25); }
.ab-success:hover { box-shadow: 0 6px 16px rgb(5 150 105/.35); filter: brightness(1.07); }
.ab-outline { background: var(--surface); color: var(--text-secondary); border: 1.5px solid var(--border-strong); }
.ab-outline:hover { background: var(--surface-2); color: var(--text-primary); }
.ab-orange { background: linear-gradient(135deg, #f97316, #ea580c); color: white; box-shadow: 0 3px 10px rgb(234 88 12/.28); }
.ab-orange:hover { box-shadow: 0 6px 16px rgb(234 88 12/.38); filter: brightness(1.07); }

.empty-state { grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow); }
.empty-state p { font-size: 15px; color: var(--text-muted); margin-top: 12px; }

.toast { position: fixed; bottom: 24px; right: 24px; background: #1e293b; color: white; padding: 12px 20px; border-radius: 12px; font-size: 13px; font-weight: 600; z-index: 9999; opacity: 0; transform: translateY(10px); transition: all 0.3s; pointer-events: none; }
.toast.show { opacity: 1; transform: translateY(0); }

/* ── MODAL PEMBAYARAN CASH ── */
.modal-overlay { position: fixed; inset: 0; background: rgba(15, 22, 35, 0.55); backdrop-filter: blur(6px); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; transition: opacity 0.25s; }
.modal-overlay.show { opacity: 1; pointer-events: all; }
.modal-box { background: var(--surface); border-radius: 24px; width: 100%; max-width: 420px; box-shadow: 0 32px 80px rgb(0 0 0/.22), 0 0 0 1px rgb(0 0 0/.06); overflow: hidden; transform: translateY(20px) scale(.97); transition: transform 0.28s cubic-bezier(.34,1.56,.64,1); }
.modal-overlay.show .modal-box { transform: translateY(0) scale(1); }
.modal-head { padding: 24px 28px 20px; background: linear-gradient(135deg, #fff7ed, #fffbeb); border-bottom: 1px solid #fde68a; display: flex; align-items: center; gap: 14px; }
.modal-icon { width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #f97316, #ea580c); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; box-shadow: 0 4px 12px rgb(234 88 12/.3); }
.modal-head-info h2 { font-size: 16px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.3px; }
.modal-head-info p { font-size: 12.5px; color: var(--text-secondary); margin-top: 2px; font-family: 'Inter', sans-serif; }
.modal-body { padding: 24px 28px; }
.modal-total-block { background: linear-gradient(135deg, var(--accent-bg), #e8eeff); border: 1px solid #bfdbfe; border-radius: 14px; padding: 16px 20px; text-align: center; margin-bottom: 22px; }
.modal-total-label { font-size: 11.5px; font-weight: 700; color: var(--accent-text); text-transform: uppercase; letter-spacing: 0.8px; font-family: 'Inter', sans-serif; margin-bottom: 4px; }
.modal-total-amount { font-size: 30px; font-weight: 800; color: var(--accent); font-variant-numeric: tabular-nums; letter-spacing: -1px; }
.modal-field { margin-bottom: 18px; }
.modal-field label { display: block; font-size: 12.5px; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px; font-family: 'Inter', sans-serif; }
.input-money-wrap { position: relative; }
.input-money-prefix { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; font-weight: 700; color: var(--text-secondary); font-family: 'Inter', sans-serif; pointer-events: none; user-select: none; }
.input-money { width: 100%; padding: 13px 14px 13px 42px; border: 2px solid var(--border-strong); border-radius: 12px; font-size: 18px; font-weight: 700; color: var(--text-primary); font-family: 'Inter', sans-serif; font-variant-numeric: tabular-nums; background: var(--surface); transition: border-color 0.18s, box-shadow 0.18s; outline: none; -moz-appearance: textfield; }
.input-money::-webkit-inner-spin-button, .input-money::-webkit-outer-spin-button { -webkit-appearance: none; }
.input-money:focus { border-color: var(--accent); box-shadow: 0 0 0 4px rgba(37,99,235,.12); }
.input-money.error { border-color: var(--red); box-shadow: 0 0 0 4px rgba(220,38,38,.1); }
.quick-cash { display: flex; gap: 7px; flex-wrap: wrap; margin-top: 10px; }
.qc-btn { padding: 6px 12px; border-radius: 8px; background: var(--surface-2); border: 1.5px solid var(--border-strong); font-size: 12px; font-weight: 700; color: var(--text-secondary); cursor: pointer; font-family: 'Inter', sans-serif; transition: all .15s; }
.qc-btn:hover { background: var(--accent-bg); border-color: #bfcfff; color: var(--accent); }
.modal-change-block { border-radius: 14px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s; background: var(--green-bg); border: 1px solid #a7f3d0; }
.modal-change-block.insuf { background: var(--red-bg); border-color: #fecaca; }
.modal-change-block.exact { background: var(--indigo-bg); border-color: #c7d2fe; }
.modal-change-label { font-size: 12px; font-weight: 700; color: var(--green-text); font-family: 'Inter', sans-serif; display: flex; align-items: center; gap: 6px; }
.modal-change-block.insuf .modal-change-label { color: var(--red-text); }
.modal-change-block.exact .modal-change-label { color: var(--indigo-text); }
.modal-change-value { font-size: 20px; font-weight: 800; color: var(--green); font-variant-numeric: tabular-nums; }
.modal-change-block.insuf .modal-change-value { color: var(--red); }
.modal-change-block.exact .modal-change-value { color: var(--indigo); font-size: 15px; font-weight: 700; }
.modal-foot { padding: 0 28px 24px; display: flex; gap: 10px; }
.modal-btn { flex: 1; padding: 13px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.18s; display: flex; align-items: center; justify-content: center; gap: 7px; }
.modal-btn:active { transform: scale(0.97); }
.modal-btn-cancel { background: var(--surface-2); color: var(--text-secondary); border: 1.5px solid var(--border-strong); }
.modal-btn-cancel:hover { background: var(--bg); color: var(--text-primary); }
.modal-btn-confirm { background: linear-gradient(135deg, #10b981, #059669); color: white; box-shadow: 0 4px 14px rgb(5 150 105/.3); }
.modal-btn-confirm:hover { filter: brightness(1.07); box-shadow: 0 6px 20px rgb(5 150 105/.4); }
.modal-btn-confirm:disabled { background: #d1d5db; color: #9ca3af; box-shadow: none; cursor: not-allowed; filter: none; }

@media (max-width: 960px) { .order-grid { grid-template-columns: 1fr; } }
@media (max-width: 640px) {
  .main { padding: 24px 16px 48px; }
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
</head>
<body>

<header class="header">
  <div class="logo">
    <div class="logo-mark">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
    <div class="logo-text">Kasir<span></span></div>
  </div>
  <div class="header-right">

  <div class="header-clock">
  <svg viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="10"/>
    <polyline points="12 6 12 12 16 14"/>
  </svg>

  <span id="liveClock">00:00:00</span>
</div>

<div class="profile-wrap">

    <div
        class="user-btn"
        id="profileBtn"
        onclick="toggleDropdown()"
    >

        <div class="avatar">

    @if(Auth::user()->avatar)
        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
    @else
        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
    @endif

</div>

        <div class="user-info">

            <div class="user-name">

                {{ Auth::user()->name }}

            </div>

            <div class="user-role">

                {{ ucfirst(Auth::user()->role) }}

            </div>

        </div>

        <svg
            class="chevron"
            viewBox="0 0 24 24"
        >

            <polyline points="6 9 12 15 18 9"/>

        </svg>

    </div>


    <div
        class="dropdown"
        id="profileDropdown"
    >

        <div class="dropdown-header">

          <div class="dropdown-avatar">

    @if(Auth::user()->avatar)
        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
    @else
        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
    @endif

</div>

            <div>

                <div class="dropdown-name">

                    {{ Auth::user()->name }}

                </div>

                <div class="dropdown-role">

                    {{ ucfirst(Auth::user()->role) }} · Online

                </div>

            </div>

        </div>


        <div class="dropdown-body">

            <a
                href="/kasir/account/profil"
                class="dropdown-item"
            >

                <div class="item-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                        />

                        <circle
                            cx="12"
                            cy="7"
                            r="4"
                        />

                    </svg>

                </div>

                Profil Saya

            </a>


            <a
                href="/kasir/account/ganti-sandi"
                class="dropdown-item"
            >

                <div class="item-icon">

                    <svg viewBox="0 0 24 24">

                        <rect
                            x="3"
                            y="11"
                            width="18"
                            height="11"
                            rx="2"
                            ry="2"
                        />

                        <path
                            d="M7 11V7a5 5 0 0 1 10 0v4"
                        />

                    </svg>

                </div>

                Ganti Password

            </a>


            <div class="dropdown-divider"></div>


            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="dropdown-item danger"
                >

                    <div class="item-icon">

                        <svg viewBox="0 0 24 24">

                            <path
                                d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"
                            />

                            <polyline
                                points="16 17 21 12 16 7"
                            />

                            <line
                                x1="21"
                                y1="12"
                                x2="9"
                                y2="12"
                            />

                        </svg>

                    </div>

                    Logout

                </button>

            </form>

        </div>

    </div>

</div>
</header>

<nav class="topnav">
  <div class="nav-container">
    <a href="/kasir/dashboard" class="nav-link {{ request()->is('kasir/dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      <span>Dashboard</span>
    </a>
    <a href="/kasir/pesanan" class="nav-link {{ request()->is('kasir/pesanan') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      <span>Pesanan</span>
    </a>
    <a href="/kasir/transaksi" class="nav-link {{ request()->is('kasir/transaksi') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      <span>Transaksi</span>
    </a>
    <a href="/kasir/laporan" class="nav-link {{ request()->is('kasir/laporan') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      <span>Laporan</span>
    </a>
  </div>
</nav>

<main class="main">
  <div class="container">
    <div class="page-header">
      <div>
        <div class="page-title">Pesanan Aktif</div>
        <div class="page-sub">{{ now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp; Kelola pesanan masuk</div>
      </div>
    </div>

    <div class="section-header">
      <div class="section-title">
        <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        Daftar Pesanan
      </div>
      <div class="section-actions">
        <span class="pill pill-blue">{{ $orders->count() }} pesanan</span>
        @if($orders->where('status','pending')->where('payment_method','cash')->count() > 0)
          <span class="pill pill-amber">
            ⏳ {{ $orders->where('status','pending')->where('payment_method','cash')->count() }} tunggu konfirmasi
          </span>
        @endif
      </div>
    </div>

    <div class="order-grid">
      @forelse($orders as $order)

      @php
        // Logika card class:
        // pending + cash       → is-pending-cash  (kuning/amber, butuh aksi)
        // paid/process + cash  → is-cash-paid     (hijau, sudah lunas)
        // qris (status apapun) → is-qris          (indigo/ungu, sudah lunas otomatis)
        if ($order->payment_method === 'cash' && $order->status === 'pending') {
            $cardClass = 'is-pending-cash';
        } elseif ($order->payment_method === 'cash' && in_array($order->status, ['paid', 'process', 'done', 'delivered'])) {
            $cardClass = 'is-cash-paid';
        } elseif ($order->payment_method === 'qris') {
            $cardClass = 'is-qris';
        } else {
            $cardClass = '';
        }
      @endphp

      <div class="order-card {{ $cardClass }}">

        {{-- TOP --}}
<div class="order-card-top">

    <div class="oc-left">

        <div class="table-badge">🍽️</div>

        <div class="oc-info">

            <h3>🍽️ Meja {{ $order->table_number ?? '-' }}

            </h3>

            <p>

                {{ $order->note ?? 'Tidak ada catatan' }}

            </p>

        </div>

    </div>


    <div class="oc-right">

        <div class="oc-time">

            {{ $order->created_at->translatedFormat('H:i') }}

        </div>

        @if($order->payment_method === 'cash')

            <span class="pay-badge pay-cash">

                💵 Cash

            </span>

        @elseif($order->payment_method === 'qris')

            <span class="pay-badge pay-qris">

                📱 QRIS

            </span>

        @endif

    </div>

</div>

        {{-- ITEM LIST --}}
        <div class="order-items">
          @if($order->items && $order->items->count() > 0)
            @foreach($order->items as $item)
            <div class="item-row">
              <div>
                <div class="item-name">
                  {{ $item->name ?? $item->menu->name ?? $item->menu->nama ?? '-' }}
                  <span style="color:var(--text-muted)">×{{ $item->qty ?? $item->quantity ?? 1 }}</span>
                </div>
                @if(!empty($item->notes))
                  <div class="item-notes-small">📝 {{ $item->notes }}</div>
                @endif
              </div>
              <span class="item-price">Rp {{ number_format($item->subtotal ?? ($item->price * ($item->qty ?? 1))) }}</span>
            </div>
            @endforeach
          @else
            <div class="item-row">
              <span class="item-name">Total Pesanan</span>
              <span class="item-price">Rp {{ number_format($order->total) }}</span>
            </div>
          @endif
        </div>

        {{-- TOTAL --}}
        <div class="total-row">
          <span class="total-label">Total</span>
          <span class="total-value">Rp {{ number_format($order->total) }}</span>
        </div>

        {{-- ════════════════════════════════════════
             BANNER STATUS: muncul sesuai kondisi
             ════════════════════════════════════════ --}}

        @if($order->payment_method === 'cash' && $order->status === 'pending')
          {{-- Cash: belum bayar, kasir perlu terima uang --}}
          <div class="cash-alert">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Terima uang cash dari customer sebelum konfirmasi!
          </div>

        @elseif($order->payment_method === 'cash' && in_array($order->status, ['paid', 'process', 'done', 'delivered']))
          {{-- Cash: SUDAH LUNAS — tampilkan banner hijau besar --}}
          <div class="lunas-banner lunas-banner-cash">
            <div class="lunas-banner-icon">✅</div>
            <div class="lunas-banner-text">
              <div class="lunas-banner-title">LUNAS — Pembayaran Cash Diterima</div>
              <div class="lunas-banner-sub">Pesanan sudah masuk dapur &amp; dicatat ke transaksi</div>
            </div>
          </div>

        @elseif($order->payment_method === 'qris' && in_array($order->status, ['paid', 'process', 'done', 'delivered']))
          {{-- QRIS: sudah lunas otomatis — tampilkan banner indigo besar --}}
          <div class="lunas-banner lunas-banner-qris">
            <div class="lunas-banner-icon">📱</div>
            <div class="lunas-banner-text">
              <div class="lunas-banner-title">LUNAS — Pembayaran QRIS Terverifikasi</div>
              <div class="lunas-banner-sub">Pembayaran dikonfirmasi otomatis, pesanan masuk dapur</div>
            </div>
          </div>

        @elseif($order->payment_method === 'qris' && $order->status === 'pending')
          {{-- QRIS pending (edge case: menunggu verifikasi gateway) --}}
          <div class="status-info-box box-cash-pending">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Menunggu konfirmasi pembayaran QRIS dari sistem...
          </div>
        @endif

        {{-- FOOTER --}}
        <div class="order-footer">
          <div class="status-pills">

            @if($order->payment_method === 'cash' && $order->status === 'pending')
              <span class="pill pill-amber">⏳ Menunggu Bayar</span>
              <span class="pill pill-orange">💵 Cash</span>

            @elseif($order->payment_method === 'cash' && $order->status === 'paid')
              <span class="pill pill-green">✅ Lunas</span>
              <span class="pill pill-orange">💵 Cash</span>

            @elseif($order->payment_method === 'cash' && $order->status === 'process')
              <span class="pill pill-green">✅ Lunas</span>
              <span class="pill pill-blue">🍳 Diproses Dapur</span>

            @elseif($order->payment_method === 'cash' && in_array($order->status, ['done', 'delivered']))
              <span class="pill pill-green">✅ Lunas &amp; Selesai</span>
              <span class="pill pill-orange">💵 Cash</span>

            @elseif($order->payment_method === 'qris' && $order->status === 'pending')
              <span class="pill pill-amber">⏳ Menunggu QRIS</span>
              <span class="pill pill-indigo">📱 QRIS</span>

            @elseif($order->payment_method === 'qris' && $order->status === 'process')
              <span class="pill pill-green">✅ Lunas</span>
              <span class="pill pill-blue">🔥 Diproses Dapur</span>

            @elseif($order->payment_method === 'qris' && $order->status === 'paid')
              <span class="pill pill-green">✅ Lunas</span>
              <span class="pill pill-indigo">📱 QRIS</span>

            @elseif($order->payment_method === 'qris' && in_array($order->status, ['done', 'delivered']))
              <span class="pill pill-green">✅ Lunas &amp; Selesai</span>
              <span class="pill pill-indigo">📱 QRIS</span>

            @else
              <span class="pill pill-amber">{{ ucfirst($order->status) }}</span>
            @endif

          </div>

            @if($order->payment_method === 'cash' && $order->status === 'pending')
              {{-- Hanya cash pending yang perlu aksi konfirmasi kasir --}}
              <button type="button" class="act-btn ab-orange"
                onclick="openCashModal({{ $order->id }}, {{ $order->total }}, '{{ ($order->order_type ?? 'dine_in') === 'takeaway' ? 'Takeaway' : ($order->table_number ?? '-') }}')">
                💵 Konfirmasi Bayar
              </button>

            @elseif($order->status === 'process')
              {{-- Tombol selesai untuk pesanan yang sedang diproses dapur --}}
              <form action="/kasir/pesanan/{{ $order->id }}/selesai" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="act-btn ab-success">✅ Selesai</button>
              </form>
            @endif
            {{-- Cash paid & QRIS: tidak ada tombol aksi tambahan --}}
          </div>
        </div>
      </div>

      @empty
      <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
        </svg>
        <p>Belum ada pesanan masuk saat ini.</p>
      </div>
      @endforelse
    </div>
  </div>
</main>

{{-- MODAL PEMBAYARAN CASH --}}
<div class="modal-overlay" id="cashModal" onclick="handleOverlayClick(event)">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-icon">💵</div>
      <div class="modal-head-info">
        <h2>Terima Pembayaran Cash</h2>
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
          <input type="number" id="cashInput" class="input-money" placeholder="0" min="0" autocomplete="off"
            oninput="calcChange()" onfocus="this.select()">
        </div>
        <div class="quick-cash" id="quickCashBtns"></div>
      </div>
      <div class="modal-change-block" id="changeBlock">
        <div class="modal-change-label">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          <span id="changeLabel">Kembalian</span>
        </div>
        <div class="modal-change-value" id="changeValue">Rp 0</div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="modal-btn modal-btn-cancel" onclick="closeCashModal()">Batal</button>
      <form id="cashConfirmForm" method="POST" style="flex:1; display:flex;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="uang_diterima" id="hiddenUangDiterima" value="0">
        <button type="submit" class="modal-btn modal-btn-confirm" id="confirmBtn" disabled>
          ✅ Konfirmasi &amp; Proses
        </button>
      </form>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

@if(session('success'))
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');
    toast.textContent = "{{ session('success') }}";
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  });
</script>
@endif

<script>
function updateClock(){

    const now = new Date();

    const h = String(now.getHours()).padStart(2,'0');
    const m = String(now.getMinutes()).padStart(2,'0');
    const s = String(now.getSeconds()).padStart(2,'0');

    document.getElementById('liveClock')
        .textContent = `${h}:${m}:${s}`;

}

setInterval(updateClock,1000);

updateClock();

function toggleDropdown() {
  const btn      = document.getElementById('profileBtn');
  const dropdown = document.getElementById('profileDropdown');
  const isOpen   = dropdown.classList.contains('show');
  dropdown.classList.toggle('show', !isOpen);
  btn.classList.toggle('open', !isOpen);
}
document.addEventListener('click', function(e) {
  const wrap = document.querySelector('.profile-wrap');
  if (wrap && !wrap.contains(e.target)) {
    document.getElementById('profileDropdown').classList.remove('show');
    document.getElementById('profileBtn').classList.remove('open');
  }
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.getElementById('profileDropdown').classList.remove('show');
    document.getElementById('profileBtn').classList.remove('open');
    closeCashModal();
  }
});

let _modalTotal = 0;

function formatRp(n) {
  return 'Rp ' + Math.abs(n).toLocaleString('id-ID');
}

function openCashModal(orderId, total, tableLabel) {
  _modalTotal = total;
  document.getElementById('modalTableLabel').textContent = 'Meja ' + tableLabel;
  document.getElementById('modalTotalDisplay').textContent = formatRp(total);
  document.getElementById('cashConfirmForm').action = '/kasir/pesanan/' + orderId + '/konfirmasi';
  const input = document.getElementById('cashInput');
  input.value = '';
  input.classList.remove('error');
  buildQuickCash(total);
  calcChange();
  document.getElementById('cashModal').classList.add('show');
  setTimeout(() => input.focus(), 280);
}

function closeCashModal() {
  document.getElementById('cashModal').classList.remove('show');
}

function handleOverlayClick(e) {
  if (e.target === document.getElementById('cashModal')) closeCashModal();
}

function buildQuickCash(total) {
  const container = document.getElementById('quickCashBtns');
  container.innerHTML = '';
  const nominals = [total];
  const rounds = [5000, 10000, 20000, 50000, 100000];
  for (const r of rounds) {
    const val = Math.ceil(total / r) * r;
    if (val > total && !nominals.includes(val)) nominals.push(val);
    if (nominals.length >= 5) break;
  }
  nominals.sort((a, b) => a - b);
  for (const n of nominals) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'qc-btn';
    btn.textContent = n === total ? '💯 Pas ' + formatRp(n) : formatRp(n);
    btn.onclick = () => { document.getElementById('cashInput').value = n; calcChange(); };
    container.appendChild(btn);
  }
}

function calcChange() {
  const input       = document.getElementById('cashInput');
  const changeBlock = document.getElementById('changeBlock');
  const changeLabel = document.getElementById('changeLabel');
  const changeValue = document.getElementById('changeValue');
  const confirmBtn  = document.getElementById('confirmBtn');
  const hidden      = document.getElementById('hiddenUangDiterima');
  const received    = parseInt(input.value) || 0;
  const change      = received - _modalTotal;
  hidden.value      = received;
  input.classList.remove('error');
  changeBlock.classList.remove('insuf', 'exact');
  if (received === 0) { changeLabel.textContent = 'Kembalian'; changeValue.textContent = 'Rp 0'; confirmBtn.disabled = true; return; }
  if (change < 0)    { changeBlock.classList.add('insuf'); changeLabel.textContent = '⚠️ Kurang'; changeValue.textContent = '−' + formatRp(change); confirmBtn.disabled = true; input.classList.add('error'); }
  else if (change === 0) { changeBlock.classList.add('exact'); changeLabel.textContent = '✨ Pas, tidak ada kembalian'; changeValue.textContent = ''; confirmBtn.disabled = false; }
  else               { changeLabel.textContent = '💰 Kembalian'; changeValue.textContent = formatRp(change); confirmBtn.disabled = false; }
}
</script>
</body>
</html>