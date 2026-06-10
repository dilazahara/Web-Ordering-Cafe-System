<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Menu - Cafe Momoo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { background: #faf9f7; color: #111827; }

        /* ── Safe area ── */
        .safe-top    { padding-top: max(env(safe-area-inset-top), 0px); }
        .safe-bottom { padding-bottom: max(env(safe-area-inset-bottom), 24px); }

        /* ── Header ── */
        .app-header {
            background: linear-gradient(160deg, #fff8f3 0%, #fff2e8 50%, #ffe8d4 100%);
            border-bottom: 1px solid rgba(249,115,22,0.10);
            position: sticky; top: 0; z-index: 100;
        }

        /* ── Search bar ── */
        .search-bar {
            background: #fff;
            border: 1.5px solid #ffe4cc;
            border-radius: 14px;
            color: #1f2937;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-bar::placeholder { color: #c4b5a5; }
        .search-bar:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.10); outline: none; }

        /* ── Category chips ── */
        .chip {
            display: inline-flex; align-items: center;
            padding: 7px 16px; border-radius: 50px;
            background: #fff;
            border: 1.5px solid #ffe0c8;
            font-size: 12px; font-weight: 700;
            color: #9a7a6a;
            white-space: nowrap; cursor: pointer;
            transition: all 0.2s; user-select: none;
        }
        .chip.active { background: #f97316; border-color: #f97316; color: #fff; }
        .chip:active { transform: scale(0.95); }

        /* ── Section title ── */
        .section-title { font-size: 15px; font-weight: 800; color: #1f2937; }

        /* ── Horizontal scroll row ── */
        .menu-row {
            display: flex; gap: 12px;
            overflow-x: auto;
            padding: 4px 16px 14px;
            scroll-padding-left: 16px;
            scroll-snap-type: x mandatory;
            align-items: stretch;   /* semua card sama tinggi */
        }
        /* Beri ruang ekstra di card pertama & terakhir agar tidak mepet */
        .menu-row > *:first-child { margin-left: 0; }
        .menu-row > *:last-child  { margin-right: 4px; }
        .menu-row::-webkit-scrollbar { display: none; }
        .menu-row { -ms-overflow-style: none; scrollbar-width: none; }

        /* ════════════════════════════════
           PRODUCT CARD — fixed equal height
        ════════════════════════════════ */
        .product-card {
            background: #fff;
            border-radius: 16px;
            width: 140px;
            min-width: 140px;
            flex-shrink: 0;
            overflow: hidden;
            scroll-snap-align: start;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            border: 1px solid #f3e8dc;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            height: 220px; /* fixed height agar semua card sejajar */
        }
        .product-card:active { transform: scale(0.97); }

        /* Image wrapper */
        .product-card .card-img-wrap {
            position: relative;
            width: 100%;
            height: 110px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .product-card .card-img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.3s;
        }
        .product-card:active .card-img-wrap img { transform: scale(1.03); }

        .product-card .card-body {
            padding: 9px 10px 10px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .product-card .card-name {
            font-size: 12px; font-weight: 700; color: #1f2937;
            line-height: 1.3; display: -webkit-box;
            -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
            min-height: 30px;
        }
        /* Harga di bawah nama */
        .card-price-text {
            font-size: 12px; font-weight: 800; color: #f97316;
            margin-top: 4px;
        }
        .product-card .card-desc {
            font-size: 9px; color: #c4b5a5; margin-top: 1px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            min-height: 12px;
        }

        /* spacer mendorong tombol ke bawah */
        .card-spacer { flex: 1; }

        /* Add button */
        .btn-add-sf {
            width: 100%;
            height: 30px;
            margin-top: 6px;
            background: #f97316;
            border: none;
            border-radius: 9px;
            color: #fff;
            font-size: 12px; font-weight: 800;
            display: flex; align-items: center; justify-content: center; gap: 4px;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
            flex-shrink: 0;
        }
        .btn-add-sf:active { background: #ea580c; transform: scale(0.96); }
        .btn-add-sf svg { width: 14px; height: 14px; stroke: #fff; }

        /* ── Sold overlay ── */
        .sold-overlay {
            position: absolute; inset: 0;
            background: rgba(0,0,0,0.40);
            display: flex; align-items: center; justify-content: center;
        }

        /* ════════════════════════════════
           SMALL TOAST — pojok atas tengah
        ════════════════════════════════ */
        #toastContainer {
            position: fixed;
            top: max(env(safe-area-inset-top), 12px);
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            pointer-events: none;
        }
        .toast-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            opacity: 0;
            transform: translateY(-8px) scale(0.95);
            transition: opacity 0.22s, transform 0.22s;
            max-width: 280px;
            pointer-events: none;
        }
        .toast-item.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        .toast-item.toast-success { background: #16a34a; color: #fff; }
        .toast-item.toast-info    { background: #374151; color: #fff; }
        .toast-item.toast-warning { background: #f59e0b; color: #fff; }
        .toast-item.toast-error   { background: #ef4444; color: #fff; }
        .toast-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: rgba(255,255,255,0.7);
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════
           FLOATING CART FAB — icon only
        ═══════════════════════════════════════ */
        .cart-fab {
            position: fixed;
            bottom: max(env(safe-area-inset-bottom), 20px);
            right: 20px;
            z-index: 200;
            transform: scale(0) translateY(20px);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s;
            pointer-events: none;
        }
        .cart-fab.show {
            transform: scale(1) translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        .cart-fab-btn {
            width: 60px; height: 60px;
            background: #f97316;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 30px rgba(249,115,22,0.40);
            cursor: pointer; border: none;
            transition: transform 0.15s, background 0.15s;
            position: relative;
        }
        .cart-fab-btn:active { transform: scale(0.93); background: #ea580c; }
        .cart-fab-btn svg { width: 26px; height: 26px; stroke: #fff; }
        .cart-fab-badge {
            position: absolute; top: -6px; right: -6px;
            background: #ef4444; color: #fff;
            font-size: 10px; font-weight: 800;
            min-width: 22px; height: 22px; border-radius: 11px;
            padding: 0 5px; display: flex; align-items: center; justify-content: center;
            border: 2.5px solid #faf9f7;
        }

        /* ══════════════════════════════════════════
           CART BOTTOM BAR
        ══════════════════════════════════════════ */
        .cart-bottom-bar {
            position: fixed;
            bottom: max(env(safe-area-inset-bottom), 12px);
            left: 14px; right: 14px;
            background: linear-gradient(135deg, #fff4ec 0%, #fff0e4 100%);
            border: 1.5px solid #fcd9bb;
            border-radius: 20px;
            display: flex; align-items: center;
            padding: 9px 9px 9px 12px;
            gap: 10px;
            z-index: 199;
            box-shadow: 0 8px 32px rgba(249,115,22,0.20), 0 2px 8px rgba(249,115,22,0.10);
            transform: translateY(130%);
            opacity: 0;
            transition: transform 0.38s cubic-bezier(0.34,1.56,0.64,1), opacity 0.25s;
            pointer-events: none;
        }
        .cart-bottom-bar.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        .cart-bottom-bar-icon {
            position: relative;
            width: 44px; height: 44px;
            background: #f97316;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(249,115,22,0.30);
        }
        .cart-bottom-bar-icon svg { width: 22px; height: 22px; stroke: #fff; }
        .cart-bottom-bar-badge {
            position: absolute; top: -6px; right: -6px;
            background: #ef4444; color: #fff;
            font-size: 10px; font-weight: 800;
            min-width: 20px; height: 20px; border-radius: 10px;
            padding: 0 4px; display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff4ec;
        }
        .cart-bottom-bar-info { flex: 1; min-width: 0; }
        .cart-bottom-bar-label { color: #c4956a; font-size: 10px; font-weight: 600; }
        .cart-bottom-bar-total { color: #c2440a; font-size: 17px; font-weight: 800; line-height: 1.1; }
        .cart-bottom-bar-btn {
            background: #f97316; color: #fff; border: none;
            padding: 12px 20px; border-radius: 14px;
            font-size: 13px; font-weight: 800;
            display: flex; align-items: center; gap: 6px;
            cursor: pointer; white-space: nowrap;
            transition: background 0.15s, transform 0.12s;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(249,115,22,0.35);
        }
        .cart-bottom-bar-btn:active { background: #ea580c; transform: scale(0.96); }
        .cart-bottom-bar-btn svg { width: 15px; height: 15px; stroke: #fff; }

        /* ── Bottom sheet ── */
        .sheet-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.4);
            z-index: 300; opacity: 0; pointer-events: none; transition: opacity 0.3s;
        }
        .sheet-overlay.show { opacity: 1; pointer-events: auto; }
        .bottom-sheet {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: #fff; border-radius: 24px 24px 0 0;
            z-index: 301;
            padding: 0 0 max(env(safe-area-inset-bottom), 24px);
            max-height: 85vh; overflow-y: auto;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.32, 0.72, 0, 1);
        }
        .bottom-sheet.show { transform: translateY(0); }
        .sheet-handle {
            width: 40px; height: 4px; background: #fde8d8;
            border-radius: 2px; margin: 12px auto 20px;
        }

        /* ── Qty pill ── */
        .qty-pill { display: flex; align-items: center; gap: 2px; background: #fef9f6; border-radius: 12px; padding: 2px; border: 1px solid #fde8d8; }
        .qty-btn { width: 32px; height: 32px; border-radius: 10px; border: none; background: #fff; color: #374151; font-size: 18px; font-weight: 600; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.15s, transform 0.1s; }
        .qty-btn:active { background: #fde8d8; transform: scale(0.90); }
        .qty-btn.plus { background: #f97316; color: #fff; }
        .qty-btn.plus:active { background: #ea580c; }

        /* ── Store badge ── */
        .badge-open { display: inline-flex; align-items: center; gap: 5px; background: rgba(16,185,129,0.12); color: #059669; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(5,150,105,0.2); }
        .badge-closed { display: inline-flex; align-items: center; gap: 5px; background: rgba(239,68,68,0.10); color: #dc2626; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(220,38,38,0.2); }
        .dot-pulse { width: 7px; height: 7px; border-radius: 50%; animation: pulse 1.4s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ── Back popup ── */
        .back-popup-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 9998; opacity: 0; pointer-events: none; transition: opacity 0.25s; display: flex; align-items: flex-end; justify-content: center; }
        .back-popup-overlay.show { opacity: 1; pointer-events: auto; }
        .back-popup-box { background: #fff; width: 100%; max-width: 480px; border-radius: 24px 24px 0 0; padding: 20px 20px max(env(safe-area-inset-bottom), 24px); transform: translateY(100%); transition: transform 0.35s cubic-bezier(0.32,0.72,0,1); }
        .back-popup-overlay.show .back-popup-box { transform: translateY(0); }
        .back-popup-handle { width: 40px; height: 4px; background: #fde8d8; border-radius: 2px; margin: 0 auto 18px; }

        /* ── Empty cart bottom bar ── */
        .cart-bottom-bar-empty {
            position: fixed;
            bottom: max(env(safe-area-inset-bottom), 12px);
            left: 14px; right: 14px;
            background: #f9fafb;
            border: 1.5px dashed #e5e7eb;
            border-radius: 20px;
            display: flex; align-items: center;
            padding: 9px 9px 9px 12px;
            gap: 10px;
            z-index: 199;
            box-shadow: none;
            pointer-events: none;
            opacity: 0.75;
        }
        .cart-bottom-bar-empty-icon {
            width: 44px; height: 44px;
            background: #f3f4f6;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .cart-bottom-bar-empty-icon svg { width: 22px; height: 22px; stroke: #9ca3af; }
        .cart-bottom-bar-empty-info { flex: 1; }
        .cart-bottom-bar-empty-label { color: #9ca3af; font-size: 10px; font-weight: 600; }
        .cart-bottom-bar-empty-text  { color: #d1d5db; font-size: 14px; font-weight: 700; }
        .cart-bottom-bar-empty-btn {
            background: #e5e7eb; color: #9ca3af; border: none;
            padding: 12px 18px; border-radius: 14px;
            font-size: 13px; font-weight: 800;
            white-space: nowrap; flex-shrink: 0;
        }

        body.no-scroll { overflow: hidden; }

        /* ── Header logo area ── */
        .header-logo-ring {
            background: linear-gradient(135deg, #f97316, #fb923c);
            border-radius: 18px; padding: 2px;
        }
        .header-logo-inner {
            background: #fff; border-radius: 16px; overflow: hidden;
            width: 44px; height: 44px;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Table badge ── */
        .table-badge {
            background: #fff;
            border: 1.5px solid #fde8d8;
            border-radius: 16px;
            padding: 10px 14px;
        }

        /* ── Note Modal ── */
        .note-modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.45);
            z-index: 9990; opacity: 0; pointer-events: none;
            transition: opacity 0.25s;
            display: flex; align-items: flex-end; justify-content: center;
        }
        .note-modal-overlay.show { opacity: 1; pointer-events: auto; }
        .note-modal-box {
            background: #fff; width: 100%; max-width: 480px;
            border-radius: 24px 24px 0 0;
            padding: 0 20px max(env(safe-area-inset-bottom), 24px);
            transform: translateY(100%);
            transition: transform 0.35s cubic-bezier(0.32,0.72,0,1);
        }
        .note-modal-overlay.show .note-modal-box { transform: translateY(0); }
        .note-modal-handle { width: 40px; height: 4px; background: #fde8d8; border-radius: 2px; margin: 14px auto 20px; }
        .note-textarea {
            width: 100%; min-height: 100px; max-height: 180px;
            border: 1.5px solid #ffe4cc; border-radius: 14px;
            padding: 12px 14px; font-size: 14px; font-family: inherit;
            color: #1f2937; resize: none; outline: none;
            background: #fff; line-height: 1.5;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .note-textarea:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249,115,22,0.10);
        }
        .note-textarea::placeholder { color: #c4b5a5; }
    </style>
</head>

<body class="pb-28">

<!-- ═══════════ SMALL TOAST CONTAINER ═══════════ -->
<div id="toastContainer"></div>

<!-- ═══════════ APP HEADER ═══════════ -->
<header class="app-header safe-top">
    <div class="px-4 pt-4 pb-0">
        <!-- Top row: logo + status -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="header-logo-ring">
                    <div class="header-logo-inner">
                        <img src="{{ asset('logo.png') }}" class="h-9 w-9 object-contain">
                    </div>
                </div>
                <div>
                    <p class="text-[11px] text-[#c4b5a5] font-semibold tracking-wide uppercase">Selamat datang 👋</p>
                    <h1 class="font-extrabold text-[#1f2937] text-[17px] leading-tight tracking-tight">Cafe Momoo</h1>
                </div>
            </div>
            <div class="flex flex-col items-end gap-1.5">
                <span id="currentTime" class="font-extrabold text-[#1f2937] text-base tracking-tight"></span>
                <div id="storeBadge" class="badge-open">
                    <span class="dot-pulse bg-green-500"></span>
                    <span id="storeStatusText">Buka</span>
                </div>
            </div>
        </div>

        <!-- Table info -->
        <div class="flex items-center gap-2 mb-4">
            <div class="table-badge flex-1 flex items-center gap-2.5">
                <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="6" width="18" height="3" rx="1.5"/><path d="M5 9v9m14-9v9M9 9v5m6-5v5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[#c4b5a5] text-[10px] font-semibold uppercase tracking-wide">Nomor Meja</p>
                    <p class="text-[#1f2937] font-extrabold text-base leading-none mt-0.5">{{ $tableNumber ?? '-' }}</p>
                </div>
                @if($tableNumber)
                <span class="ml-auto text-[10px] font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-full border border-green-100">✓ Aktif</span>
                @endif
            </div>
        </div>

        <!-- Search bar -->
        <div class="relative mb-4">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#c4b5a5] pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input id="searchInput" type="search" placeholder="Cari minuman, makanan..."
                class="search-bar w-full pl-10 pr-4 py-3 text-sm font-semibold">
        </div>

        <!-- Category chips -->
        <div class="flex gap-2 overflow-x-auto no-scrollbar pb-3">
            <button class="chip" onclick="selectCategory('all', this)">✦ Semua</button>
            @foreach($kategoris as $k)
            <button class="chip" onclick="selectCategory('{{ $k->name }}', this)">{{ $k->name }}</button>
            @endforeach
        </div>
    </div>
</header>

<!-- QR success alert -->
@if(session('qr_success'))
<div class="mx-4 mt-3" id="qrAlert">
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 px-4 py-3 rounded-2xl text-sm">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-green-700 font-semibold">{{ session('qr_success') }}</span>
    </div>
</div>
<script>setTimeout(()=>{const e=document.getElementById('qrAlert');if(e){e.style.transition='opacity .4s';e.style.opacity='0';setTimeout(()=>e.remove(),400);}},3000);</script>
@endif

<!-- ═══════════ MENU SECTIONS ═══════════ -->
<div id="menuSections" class="pt-2"></div>

<!-- ═══════════ BOTTOM SHEET OVERLAY ═══════════ -->
<div id="sheetOverlay" class="sheet-overlay" onclick="closeCart()"></div>

<!-- ═══════════ CART BOTTOM SHEET ═══════════ -->
<div id="cartSheet" class="bottom-sheet">
    <div class="sheet-handle"></div>
    <div class="px-4">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-extrabold text-xl text-gray-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                Keranjang
            </h2>
            <button onclick="closeCart()" class="w-9 h-9 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="cartItems" class="min-h-[120px]"></div>
        <div class="sticky bottom-0 bg-white pt-4 pb-2 border-t border-orange-50 mt-2">
            <div class="flex justify-between items-center">
                <span class="text-[#9a7a6a] font-semibold">Total</span>
                <span id="cartTotal" class="font-extrabold text-2xl text-orange-500">Rp 0</span>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ FLOATING CART FAB ═══════════ -->
<div id="cartFab" class="cart-fab">
    <button id="cartFabBtn" class="cart-fab-btn" onclick="toggleCart()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span id="cartFabBadge" class="cart-fab-badge">0</span>
    </button>
</div>

<!-- ═══════════ CART BOTTOM BAR ═══════════ -->
<div id="cartBottomBar" class="cart-bottom-bar">
    <button onclick="toggleCart()" class="cart-bottom-bar-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span id="cartBarBadge" class="cart-bottom-bar-badge">0</span>
    </button>
    <div class="cart-bottom-bar-info">
        <p class="cart-bottom-bar-label">Total Pesanan</p>
        <p id="cartBarTotal" class="cart-bottom-bar-total">Rp 0</p>
    </div>
    <button onclick="goToCheckout()" class="cart-bottom-bar-btn">
        Lanjut checkout
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14m-7-7l7 7-7 7"/>
        </svg>
    </button>
</div>

<!-- Back Popup -->
<div id="backPopup" class="back-popup-overlay" onclick="closeBackPopup(event)">
    <div class="back-popup-box">
        <div class="back-popup-handle"></div>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center flex-shrink-0 border border-orange-100">
                <svg class="w-6 h-6 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            </div>
            <div>
                <p class="font-extrabold text-gray-900 text-base">Keranjang belum checkout</p>
                <p class="text-sm text-[#c4b5a5] mt-0.5">Ada item yang belum dipesan nih 😊</p>
            </div>
        </div>
        <p class="text-sm text-[#9a7a6a] mb-5 bg-orange-50 rounded-2xl px-4 py-3 border border-orange-100">Kamu ingin melanjutkan belanja atau keluar dari halaman menu?</p>
        <div class="flex gap-3">
            <button onclick="closeBackPopup(null)" class="flex-1 py-3.5 rounded-2xl border-2 border-orange-100 font-bold text-[#9a7a6a] text-sm active:bg-orange-50 transition-all">Tetap di sini</button>
            <button onclick="confirmLeaveHome()" class="flex-1 py-3.5 rounded-2xl bg-orange-500 font-bold text-white text-sm active:bg-orange-600 active:scale-[0.98] transition-all">Keluar</button>
        </div>
    </div>
</div>

<!-- ═══════════ NOTE MODAL ═══════════ -->
<div id="noteModalOverlay" class="note-modal-overlay">
    <div class="note-modal-box">
        <div class="note-modal-handle"></div>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-orange-50 rounded-2xl flex items-center justify-center flex-shrink-0 border border-orange-100">
                <svg class="w-5 h-5 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <p class="font-extrabold text-gray-900 text-base">Tambah Catatan</p>
                <p id="noteModalItemName" class="text-sm text-[#c4b5a5] mt-0.5 truncate max-w-[220px]"></p>
            </div>
        </div>
        <textarea id="noteModalInput" class="note-textarea" placeholder="Contoh: less sugar, no ice, extra shot..."></textarea>
        <p class="text-[10px] text-[#c4b5a5] mt-2 mb-4">Catatan ini akan diteruskan ke dapur ☕</p>
        <div class="flex gap-3">
            <button onclick="closeNoteModal()" class="flex-1 py-3.5 rounded-2xl border-2 border-orange-100 font-bold text-[#9a7a6a] text-sm active:bg-orange-50 transition-all">Batal</button>
            <button onclick="saveNote()" class="flex-1 py-3.5 rounded-2xl bg-orange-500 font-bold text-white text-sm active:bg-orange-600 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- ═══════════ EMPTY CART BOTTOM BAR ═══════════ -->
<div id="cartEmptyBar" class="cart-bottom-bar-empty">
    <div class="cart-bottom-bar-empty-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
    </div>
    <div class="cart-bottom-bar-empty-info">
        <p class="cart-bottom-bar-empty-label">Keranjang</p>
        <p class="cart-bottom-bar-empty-text">Belum ada pesanan</p>
    </div>
    <div class="cart-bottom-bar-empty-btn">Pilih Menu</div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    const menuData = JSON.parse('@json($menus)');
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let currentCategory = 'all';
    let currentSearch = '';
    let cartSheetOpen = false;

    if(sessionStorage.getItem('orderSuccess')) {
        localStorage.removeItem('cart'); localStorage.removeItem('checkoutCart');
        sessionStorage.removeItem('orderSuccess'); cart = [];
    }

    /* ══════════════════════════════════════════
       SMALL TOAST — ganti dari modal besar
       Muncul di atas, kecil, hilang otomatis
    ══════════════════════════════════════════ */
    let _toastId = 0;
    function toast(msg, type = 'success') {
        const container = document.getElementById('toastContainer');
        const id = ++_toastId;
        const el = document.createElement('div');
        el.className = `toast-item toast-${type}`;
        el.id = 'toast_' + id;

        // icon dot + text
        el.innerHTML = `<span class="toast-dot"></span>${msg}`;
        container.appendChild(el);

        // trigger animation
        requestAnimationFrame(() => {
            requestAnimationFrame(() => el.classList.add('show'));
        });

        // auto-remove after 2s
        setTimeout(() => {
            el.classList.remove('show');
            setTimeout(() => el.remove(), 250);
        }, 2000);
    }

    // ── Time ──
    function updateTime() {
        const now = new Date();
        const h = now.getHours(), m = now.getMinutes();
        document.getElementById('currentTime').textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}`;
        const open = h >= 8 && h < 22;
        const badge = document.getElementById('storeBadge');
        const txt = document.getElementById('storeStatusText');
        badge.className = open ? 'badge-open' : 'badge-closed';
        badge.querySelector('.dot-pulse').className = `dot-pulse ${open ? 'bg-green-500' : 'bg-red-500'}`;
        txt.textContent = open ? 'Buka Sekarang' : 'Sedang Tutup';
    }
    updateTime(); setInterval(updateTime, 10000);

    // ── Table number ──
    (function() {
        const serverTable = @json($tableNumber ?? null);
        if(serverTable) {
            localStorage.setItem('table_number', serverTable);
            localStorage.setItem('tableNumber', serverTable);
        } else {
            const p = new URLSearchParams(location.search);
            const t = p.get('meja') || p.get('table');
            if(t) { localStorage.setItem('table_number', t); localStorage.setItem('tableNumber', t); }
        }
    })();

    // ── Render all sections ──
    function renderSections(items) {
        const container = document.getElementById('menuSections');
        const groups = {};
        const groupOrder = [];
        items.forEach(item => {
            const cat = item.kategori?.name || 'Lainnya';
            if(!groups[cat]) { groups[cat] = []; groupOrder.push(cat); }
            groups[cat].push(item);
        });

        if(items.length === 0) {
            container.innerHTML = `<div class="py-20 text-center">
                <div class="text-5xl mb-4">🔍</div>
                <p class="text-[#9a7a6a] font-semibold">Menu tidak ditemukan</p>
                <p class="text-[#c4b5a5] text-xs mt-1">Coba kata kunci lain</p></div>`;
            return;
        }

        container.innerHTML = groupOrder.map(cat => `
            <div class="mb-2">
                <div class="flex items-center justify-between px-4 mb-2 mt-3">
                    <h2 class="section-title">${cat}</h2>
                    <span class="text-xs text-[#c4b5a5] font-semibold">${groups[cat].length} menu</span>
                </div>
                <div class="menu-row">
                    ${groups[cat].map(item => renderCard(item)).join('')}
                </div>
            </div>
        `).join('');
    }

    function renderCard(item) {
        const sold = item.status == 0;
        return `
        <div class="product-card" onclick="${sold ? '' : `goToAddon(${item.id})`}">
            <div class="card-img-wrap">
                <img src="/storage/${item.image}" alt="${item.name}" loading="lazy">
                ${sold ? `<div class="sold-overlay">
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full">HABIS</span>
                </div>` : ''}
            </div>
            <div class="card-body">
                <p class="card-name">${item.name}</p>
                ${!sold
                    ? `<p class="card-price-text">Rp ${Number(item.price).toLocaleString('id-ID')}</p>`
                    : `<p class="card-price-text" style="color:#ef4444">Habis</p>`}
                <div class="card-spacer"></div>
                ${!sold ? `
                <button onclick="event.stopPropagation(); goToAddon(${item.id})" class="btn-add-sf">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Tambah
                </button>` : `<span class="block text-center text-[10px] text-red-400 font-bold py-1.5 bg-red-50 rounded-lg border border-red-100">Stok Habis</span>`}
            </div>
        </div>`;
    }

    // ── Filter ──
    function filterMenu() {
        const q = currentSearch.toLowerCase();
        const filtered = menuData.filter(item =>
            (item.name.toLowerCase().includes(q) || (item.description||'').toLowerCase().includes(q)) &&
            (currentCategory === 'all' || item.kategori?.name === currentCategory)
        );
        renderSections(filtered);
    }

    function selectCategory(cat, el) {
        currentCategory = cat;
        document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        filterMenu();
    }

    // ── Cart UI ──
    function updateCartUI() {
        let total = 0, count = 0;
        cart.forEach(i => { total += i.price * i.quantity; count += i.quantity; });
        const fmt = n => 'Rp ' + n.toLocaleString('id-ID');
        document.getElementById('cartTotal').textContent = fmt(total);
        document.getElementById('cartFabBadge').textContent = count;
        document.getElementById('cartBarBadge').textContent = count;
        document.getElementById('cartBarTotal').textContent = fmt(total);

        const fab = document.getElementById('cartFab');
        const bar = document.getElementById('cartBottomBar');
        const emptyBar = document.getElementById('cartEmptyBar');
        if(count > 0) {
            fab.classList.remove('show');
            bar.classList.add('show');
            emptyBar.style.display = 'none';
        } else {
            fab.classList.remove('show');
            bar.classList.remove('show');
            emptyBar.style.display = 'flex';
            if(cartSheetOpen) closeCart();
        }
        renderCartItems();
    }

    function renderCartItems() {
        const el = document.getElementById('cartItems');
        if(!cart.length) {
            el.innerHTML = `<div class="py-10 text-center">
                <div class="text-4xl mb-3">🛒</div>
                <p class="text-[#9a7a6a] font-semibold text-sm">Keranjang kosong</p>
                <p class="text-[#c4b5a5] text-xs mt-1">Yuk pilih menu dulu 😋</p></div>`;
            return;
        }
        el.innerHTML = cart.map((item, idx) => `
        <div class="flex gap-3 py-3 border-b border-orange-50">
            <img src="/storage/${item.image||''}" class="w-14 h-14 rounded-2xl object-cover flex-shrink-0 border border-orange-50">
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start">
                    <p class="font-bold text-sm text-gray-900 leading-tight pr-2">${item.name}</p>
                    <button onclick="removeItem(${idx})" class="w-7 h-7 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 border border-red-100">
                        <svg class="w-4 h-4 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                <p onclick="startNote(${idx})" class="text-xs text-[#c4b5a5] mt-0.5 truncate cursor-pointer">
                    <svg class="inline w-3 h-3 mr-0.5 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    ${item.notes || 'Tambah catatan...'}
                </p>
                <div class="flex items-center justify-between mt-2">
                    <p class="font-extrabold text-sm text-orange-500">Rp ${item.price.toLocaleString('id-ID')}</p>
                    <div class="qty-pill">
                        <button onclick="changeQty(${idx},-1)" class="qty-btn">−</button>
                        <span class="text-sm font-extrabold text-gray-800 w-6 text-center">${item.quantity}</span>
                        <button onclick="changeQty(${idx},1)" class="qty-btn plus">+</button>
                    </div>
                </div>
            </div>
        </div>`).join('');
    }

    function changeQty(idx, val) {
        cart[idx].quantity += val;
        if(cart[idx].quantity <= 0) { cart.splice(idx,1); toast('Item dihapus','warning'); }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
    }
    function removeItem(idx) {
        cart.splice(idx,1);
        localStorage.setItem('cart', JSON.stringify(cart));
        toast('Item dihapus 🗑️','warning');
        updateCartUI();
    }

    // ── Note Modal ──
    let _noteIdx = null;
    function startNote(idx) {
        _noteIdx = idx;
        document.getElementById('noteModalItemName').textContent = cart[idx].name;
        document.getElementById('noteModalInput').value = cart[idx].notes || '';
        document.getElementById('noteModalOverlay').classList.add('show');
        document.body.classList.add('no-scroll');
        setTimeout(() => document.getElementById('noteModalInput').focus(), 350);
    }
    function closeNoteModal() {
        document.getElementById('noteModalOverlay').classList.remove('show');
        document.body.classList.remove('no-scroll');
        _noteIdx = null;
    }
    function saveNote() {
        if(_noteIdx === null) return;
        cart[_noteIdx].notes = document.getElementById('noteModalInput').value.trim();
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
        toast('Catatan disimpan ✓','success');
        closeNoteModal();
    }
    document.getElementById('noteModalOverlay').addEventListener('click', function(e) {
        if(e.target === this) closeNoteModal();
    });
    document.getElementById('noteModalInput').addEventListener('keydown', function(e) {
        if(e.key === 'Enter' && e.ctrlKey) saveNote();
    });

    // ── Cart sheet open/close ──
    function openCart() {
        cartSheetOpen = true;
        const sheet = document.getElementById('cartSheet');
        const overlay = document.getElementById('sheetOverlay');
        const fabBtn = document.getElementById('cartFabBtn');
        renderCartItems();
        sheet.classList.add('show');
        overlay.classList.add('show');
        fabBtn.classList.add('cart-open');
        document.body.classList.add('no-scroll');
    }
    function closeCart() {
        cartSheetOpen = false;
        const sheet = document.getElementById('cartSheet');
        const overlay = document.getElementById('sheetOverlay');
        const fabBtn = document.getElementById('cartFabBtn');
        sheet.classList.remove('show');
        overlay.classList.remove('show');
        fabBtn.classList.remove('cart-open');
        document.body.classList.remove('no-scroll');
    }
    function toggleCart() {
        if(cartSheetOpen) closeCart();
        else openCart();
    }

    function goToAddon(id) { window.location.href = '/customer/addons?menu_id=' + id; }
    function goToCheckout() {
        localStorage.setItem('checkoutCart', JSON.stringify(cart));
        window.location.href = '/customer/checkout';
    }

    // ── Init ──
    document.getElementById('searchInput').addEventListener('input', function() {
        currentSearch = this.value;
        filterMenu();
    });
    const firstChip = document.querySelector('.chip');
    if(firstChip) firstChip.classList.add('active');
    renderSections(menuData);
    updateCartUI();

    // ── Back button popup ──
    let _backPopupOpen = false;
    function openBackPopup() {
        _backPopupOpen = true;
        document.getElementById('backPopup').classList.add('show');
        document.body.classList.add('no-scroll');
        history.pushState({ popup: true }, '', location.href);
    }
    function closeBackPopup(e) {
        if (e && e.target !== document.getElementById('backPopup')) return;
        _backPopupOpen = false;
        document.getElementById('backPopup').classList.remove('show');
        document.body.classList.remove('no-scroll');
    }
    function confirmLeaveHome() {
        _backPopupOpen = false;
        document.getElementById('backPopup').classList.remove('show');
        document.body.classList.remove('no-scroll');
        history.back();
    }
    window.addEventListener('popstate', function(e) {
        if (_backPopupOpen) {
            _backPopupOpen = false;
            document.getElementById('backPopup').classList.remove('show');
            document.body.classList.remove('no-scroll');
            return;
        }
        const cartCount = cart.reduce((s,i)=>s+i.quantity, 0);
        if (cartCount > 0) openBackPopup();
    });
    history.pushState(null, '', location.href);
</script>
</body>
</html>