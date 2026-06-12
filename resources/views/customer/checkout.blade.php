<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover, user-scalable=no">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { background: #f4f6f9; }

        /* Topbar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            position: sticky; top: 0; z-index: 50;
            padding-top: max(env(safe-area-inset-top), 12px);
        }

        /* Card */
        .card { background: #fff; border-radius: 20px; }

        /* Section label */
        .section-label {
            font-size: 11px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: #9ca3af;
        }

        /* Payment option */
        .pay-card {
            border: 2px solid #e5e7eb;
            border-radius: 18px;
            padding: 14px 16px;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            display: flex; align-items: center; gap-x-12px;
        }
        .pay-card:active { transform: scale(0.98); }
        .pay-card.sel-cash   { border-color: #f97316; background: #fff7ed; }
        .pay-card.sel-online { border-color: #22c55e; background: #f0fdf4; }

        .pay-radio {
            width: 22px; height: 22px;
            border-radius: 50%;
            border: 2.5px solid #d1d5db;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: border-color 0.2s, background 0.2s;
        }
        .pay-card.sel-cash   .pay-radio { border-color: #f97316; background: #f97316; }
        .pay-card.sel-online .pay-radio { border-color: #22c55e; background: #22c55e; }
        .pay-radio::after {
            content: '';
            width: 8px; height: 8px;
            border-radius: 50%;
            background: transparent;
        }
        .pay-card.sel-cash   .pay-radio::after,
        .pay-card.sel-online .pay-radio::after { background: #fff; }

        /* Input */
        .inp {
            width: 100%;
            background: #f4f6f9;
            border: 2px solid transparent;
            border-radius: 14px;
            padding: 13px 16px;
            font-size: 15px;
            transition: border-color 0.2s, background 0.2s;
        }
        .inp:focus { background: #fff; border-color: #f97316; outline: none; }

        /* Qty */
        .qty-pill { display: flex; align-items: center; background: #f4f6f9; border-radius: 12px; padding: 2px; gap: 2px; }
        .q-btn {
            width: 34px; height: 34px;
            border-radius: 10px; border: none; background: #fff;
            font-size: 18px; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.15s;
        }
        .q-btn:active { background: #e5e7eb; transform: scale(0.90); }
        .q-btn.plus { background: #f97316; color: #fff; }
        .q-btn.plus:active { background: #ea580c; }

        /* Submit button */
        .btn-submit {
            width: 100%; padding: 17px;
            border-radius: 20px; border: none;
            font-size: 16px; font-weight: 800;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit:active { transform: scale(0.97); }
        .btn-cash   { background: linear-gradient(135deg, #f97316, #ea580c); color: #fff; box-shadow: 0 8px 24px -4px rgba(249,115,22,.4); }
        .btn-online { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; box-shadow: 0 8px 24px -4px rgba(34,197,94,.4); }
        .btn-submit:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

        /* Spinner */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { width: 20px; height: 20px; border: 3px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .8s linear infinite; }

        /* Dash divider */
        .dash { border: none; border-top: 2px dashed #e5e7eb; }

        /* Toast */
        #toastWrap {
            position: fixed; top: 16px; left: 50%; transform: translateX(-50%);
            z-index: 9999; display: flex; flex-direction: column; gap: 8px;
            pointer-events: none; width: max-content; max-width: calc(100vw - 32px);
        }
        .toast { display:flex; align-items:center; gap:8px; padding:10px 16px; border-radius:14px; font-size:13px; font-weight:600; box-shadow:0 4px 20px rgba(0,0,0,.15); opacity:0; transform:translateY(-8px) scale(.95); transition:all .25s; }
        .toast.show { opacity:1; transform:translateY(0) scale(1); }
        .t-success { background:#111827; color:#fff; }
        .t-error   { background:#ef4444; color:#fff; }
        .t-warning { background:#f59e0b; color:#fff; }
        .t-info    { background:#111827; color:#fff; }

        @keyframes fadeUp { from{opacity:0;transform:translateY(12px);} to{opacity:1;transform:translateY(0);} }
        .fade-up { animation: fadeUp .35s ease-out; }

        /* Back Popup */
        .back-popup-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            z-index: 9998; opacity: 0; pointer-events: none;
            transition: opacity 0.25s;
            display: flex; align-items: flex-end; justify-content: center;
        }
        .back-popup-overlay.show { opacity: 1; pointer-events: auto; }
        .back-popup-box {
            background: #fff; width: 100%; max-width: 480px;
            border-radius: 24px 24px 0 0;
            padding: 20px 20px max(env(safe-area-inset-bottom), 24px);
            transform: translateY(100%);
            transition: transform 0.35s cubic-bezier(0.32,0.72,0,1);
        }
        .back-popup-overlay.show .back-popup-box { transform: translateY(0); }
        .back-popup-handle { width: 40px; height: 4px; background: #e5e7eb; border-radius: 2px; margin: 0 auto 18px; }

        /* Confirm Order Popup */
        .confirm-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.55);
            z-index: 9999; opacity: 0; pointer-events: none;
            transition: opacity 0.25s;
            display: flex; align-items: flex-end; justify-content: center;
        }
        .confirm-overlay.show { opacity: 1; pointer-events: auto; }
        .confirm-box {
            background: #fff; width: 100%; max-width: 480px;
            border-radius: 24px 24px 0 0;
            padding: 0 20px max(env(safe-area-inset-bottom), 28px);
            transform: translateY(100%);
            transition: transform 0.38s cubic-bezier(0.32,0.72,0,1);
            max-height: 85vh; overflow-y: auto;
        }
        .confirm-overlay.show .confirm-box { transform: translateY(0); }
        .confirm-handle { width: 40px; height: 4px; background: #e5e7eb; border-radius: 2px; margin: 14px auto 20px; }
        .confirm-item-row { display:flex; justify-content:space-between; align-items:center; padding: 6px 0; border-bottom: 1px solid #f3f4f6; }
        .confirm-item-row:last-child { border-bottom: none; }
        .btn-confirm-yes {
            flex: 1; padding: 15px; border-radius: 16px; border: none;
            font-size: 15px; font-weight: 800; cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .btn-confirm-yes:active { transform: scale(0.97); }
        .btn-confirm-cash   { background: linear-gradient(135deg,#f97316,#ea580c); color:#fff; box-shadow: 0 6px 20px -4px rgba(249,115,22,.4); }
        .btn-confirm-online { background: linear-gradient(135deg,#22c55e,#16a34a); color:#fff; box-shadow: 0 6px 20px -4px rgba(34,197,94,.4); }
        .btn-confirm-no {
            flex: 1; padding: 15px; border-radius: 16px; border: 2px solid #e5e7eb;
            font-size: 15px; font-weight: 700; background: #fff; color: #374151;
            cursor: pointer; transition: background 0.15s;
        }
        .btn-confirm-no:active { background: #f9fafb; }
        /* VALIDASI INLINE */
        .inp.input-error { border-color: #ef4444 !important; }
        .field-error-msg { font-size: 11px; color: #ef4444; margin-top: 4px; display: none; font-weight: 600; }
        .field-error-msg.show { display: block; }
    </style>
</head>
<body class="pb-8">

<!-- Topbar -->
<div class="topbar px-4 py-3 flex items-center gap-3">
    <button onclick="openBackPopup()" class="w-11 h-11 flex items-center justify-center bg-gray-100 rounded-2xl active:scale-90 transition-transform">
        <svg class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <div>
        <h1 class="font-extrabold text-lg text-gray-900 leading-tight">Checkout</h1>
        <p class="text-xs text-gray-400 font-semibold">Konfirmasi pesananmu</p>
    </div>
</div>

<div class="max-w-xl mx-auto px-4 pt-4 space-y-3">

    <!-- Error alert -->
    @if(session('error'))
    <div class="fade-up bg-red-50 border border-red-200 rounded-2xl px-4 py-3 flex items-center gap-3">
        <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
        </div>
        <div><p class="font-bold text-red-700 text-sm">Pesanan Gagal</p><p class="text-red-500 text-xs mt-0.5">{{ session('error') }}</p></div>
    </div>
    @endif

    <!-- Meja -->
    <div class="card px-4 py-4 fade-up shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="6" width="18" height="3" rx="1.5"/><path stroke-linecap="round" d="M5 9v9m14-9v9M9 9v5m6-5v5"/>
                </svg>
            </div>
            <div>
                <p class="section-label mb-0.5">Nomor Meja</p>
                <p id="tableNumberDisplay" class="font-extrabold text-lg text-gray-900">
                    @if($tableNumber) Meja {{ $tableNumber }} @else Belum dipilih @endif
                </p>
            </div>
        </div>
        <a href="/customer/home" class="flex items-center gap-1.5 bg-orange-50 text-orange-600 font-bold text-sm px-4 py-2.5 rounded-2xl active:scale-95 transition-all">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            Menu
        </a>
    </div>

    @if(!$tableNumber)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/><path stroke-linecap="round" d="M12 15.75h.007v.008H12v-.008z"/></svg>
        <p class="text-amber-700 text-xs font-semibold">Scan QR Code di meja kamu terlebih dahulu</p>
    </div>
    @endif

    <!-- Items pesanan -->
    <div class="card px-4 py-4 fade-up shadow-sm">
        <p class="section-label mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            Item Pesanan
        </p>
        <div id="checkoutItems" class="space-y-3">
            <p class="text-gray-400 text-sm text-center py-6">Memuat...</p>
        </div>
    </div>

    <!-- Nama pelanggan -->
    <div class="card px-4 py-4 fade-up shadow-sm">
        <p class="section-label mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            Nama Pelanggan
        </p>
        <input type="text" id="customerNameInput" maxlength="100" placeholder="Masukkan nama kamu" class="inp">
        <span class="field-error-msg" id="errCustomerName">Nama wajib diisi sebelum memesan.</span>
    </div>

    <!-- Catatan -->
    <div class="card px-4 py-4 fade-up shadow-sm">
        <p class="section-label mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
            Catatan Tambahan
        </p>
        <textarea id="noteInput" rows="2" placeholder="Contoh: tidak pakai es, level pedas, dll." class="inp resize-none"></textarea>
    </div>

    <!-- Rincian biaya -->
    <div class="card px-4 py-4 fade-up shadow-sm">
        <p class="section-label mb-4">Rincian Biaya</p>
        <div id="detailItems" class="space-y-2 text-sm text-gray-500 mb-3"></div>
        <hr class="dash mb-3">
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Subtotal</span>
                <span id="subTotal" class="font-bold text-gray-800">Rp 0</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Biaya Layanan</span>
                <span class="font-bold text-gray-800">Rp 2.000</span>
            </div>
        </div>
        <hr class="dash my-3">
        <div class="flex justify-between items-center">
            <span class="font-bold text-gray-900">Total</span>
            <span id="grandTotal" class="font-extrabold text-xl text-orange-500">Rp 0</span>
        </div>
    </div>

    <!-- Metode pembayaran -->
    @if($hasCash || $hasMidtrans)
    <div class="card px-4 py-4 fade-up shadow-sm">
        <p class="section-label mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
            Metode Pembayaran
        </p>
        <div class="space-y-3" id="payOpts">
            @if($hasCash)
            <div class="pay-card sel-cash gap-3" id="opt_kasir" onclick="selectPayment('cash','kasir')">
                <div class="pay-radio"></div>
                <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-900 text-sm">Bayar di Kasir</p>
                    <p class="text-xs text-gray-400 mt-0.5">Tunjukkan pesanan ke kasir & bayar tunai</p>
                </div>
            </div>
            @endif
            @if($hasMidtrans)
            <div class="pay-card {{ !$hasCash ? 'sel-online' : '' }} gap-3" id="opt_midtrans" onclick="selectPayment('{{ $midtransMethod->kode }}','midtrans')">
                <div class="pay-radio"></div>
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-900 text-sm">Bayar Online</p>
                    <p class="text-xs text-gray-400 mt-0.5">GoPay, OVO, DANA, VA Bank & lainnya</p>
                </div>
            </div>
            @endif
        </div>
        <!-- Info box -->
        <div id="infoPayBox" class="mt-3"></div>
    </div>
    @else
    <div class="card px-4 py-5 text-center fade-up shadow-sm">
        <p class="text-red-500 font-bold text-sm">⚠️ Tidak ada metode pembayaran aktif</p>
        <p class="text-gray-400 text-xs mt-1">Hubungi kasir untuk bantuan.</p>
    </div>
    @endif

    <!-- Form submit -->
    <form action="{{ route('customer.order.store') }}" method="POST" id="orderForm">
        @csrf
        <input type="hidden" name="cart"           id="cartInput">
        <input type="hidden" name="note"           id="noteHidden">
        <input type="hidden" name="customer_name"  id="customerNameHidden">
        <input type="hidden" name="payment_method" id="paymentMethodInput" value="{{ $hasCash ? 'cash' : ($hasMidtrans ? $midtransMethod->kode : '') }}">
        <input type="hidden" name="table_number"   id="tableNumberInput"   value="{{ $tableNumber ?? '' }}">
        <input type="hidden" name="order_type"     value="dine_in">

        @if(!$hasCash && !$hasMidtrans)
        <button type="button" disabled class="btn-submit bg-gray-200 text-gray-400 cursor-not-allowed">Tidak Dapat Memesan</button>
        @else
        <button type="button" id="btnSubmitOrder" onclick="openConfirmPopup()" class="btn-submit btn-cash">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            Pesan Sekarang
        </button>
        @endif
    </form>

</div>

<!-- Back Popup -->
<div id="backPopup" class="back-popup-overlay" onclick="closeBackPopup(event)">
    <div class="back-popup-box">
        <div class="back-popup-handle"></div>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            </div>
            <div>
                <p class="font-extrabold text-gray-900 text-base">Batalkan checkout?</p>
                <p class="text-sm text-gray-400 mt-0.5">Pesananmu belum dikirim</p>
            </div>
        </div>
        <p class="text-sm text-gray-500 mb-5 bg-gray-50 rounded-2xl px-4 py-3">Kembali ke menu? Keranjang kamu akan tetap tersimpan kok 😊</p>
        <div class="flex gap-3">
            <button onclick="closeBackPopup(null)" class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 font-bold text-gray-700 text-sm active:bg-gray-50 transition-all">Lanjutkan Checkout</button>
            <button onclick="confirmBackCheckout()" class="flex-1 py-3.5 rounded-2xl bg-orange-500 font-bold text-white text-sm active:bg-orange-600 active:scale-[0.98] transition-all">Kembali ke Menu</button>
        </div>
    </div>
</div>

<!-- Confirm Order Popup -->
<div id="confirmPopup" class="confirm-overlay" onclick="closeConfirmPopup(event)">
    <div class="confirm-box">
        <div class="confirm-handle"></div>

        <!-- Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-orange-50 rounded-3xl flex items-center justify-center">
                <svg class="w-8 h-8 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Teks -->
        <div class="text-center mb-6 px-2">
            <p class="font-extrabold text-gray-900 text-lg mb-2">Lanjutkan pesanan ini?</p>
            <p class="text-sm text-gray-500 leading-relaxed">Pastikan pilihan menu Anda sudah benar sebelum melanjutkan ke proses berikutnya</p>
        </div>

        <!-- Tombol -->
        <div class="flex gap-3">
            <button onclick="closeConfirmPopup(null)" class="btn-confirm-no">Cek Lagi</button>
            <button id="btnConfirmYes" onclick="doSubmitOrder()" class="btn-confirm-yes btn-confirm-cash">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7l7 7-7 7"/></svg>
                <span id="btnConfirmYesText">Ya, Pesan Sekarang</span>
            </button>
        </div>
    </div>
</div>

<div id="toastWrap"></div>

<script>
// Toast
function toast(msg, type = 'info', ms = 2200) {
    const w = document.getElementById('toastWrap');
    const icons = {success:'✓',info:'•',warning:'!',error:'✕'};
    const el = document.createElement('div');
    el.className = `toast t-${type}`;
    el.innerHTML = `<span>${icons[type]}</span><span>${msg}</span>`;
    w.appendChild(el);
    requestAnimationFrame(()=>el.classList.add('show'));
    setTimeout(()=>{ el.classList.remove('show'); setTimeout(()=>el.remove(),280); }, ms);
}

const serverTableNumber = @json($tableNumber ?? null);
const hasCash     = @json($hasCash);
const hasMidtrans = @json($hasMidtrans);
const midtransKode = @json($hasMidtrans ? $midtransMethod->kode : 'midtrans');

let cart = JSON.parse(localStorage.getItem('checkoutCart')) || JSON.parse(localStorage.getItem('cart')) || [];
let selType = hasCash ? 'kasir' : 'midtrans';
let selKode = hasCash ? 'cash' : midtransKode;

// Init
(function(){
    const t = serverTableNumber || localStorage.getItem('table_number');
    if(t) { localStorage.setItem('table_number', t); document.getElementById('tableNumberInput').value = t; }
    document.getElementById('paymentMethodInput').value = selKode;
    renderCart();
    updatePayUI(selType);
})();

function selectPayment(kode, type) {
    selType = type; selKode = type === 'kasir' ? 'cash' : midtransKode;
    document.querySelectorAll('.pay-card').forEach(c => c.className = c.className.replace(/sel-\w+/g,'').trim());
    const el = type === 'kasir' ? document.getElementById('opt_kasir') : document.getElementById('opt_midtrans');
    if(el) el.classList.add(type === 'kasir' ? 'sel-cash' : 'sel-online');
    document.getElementById('paymentMethodInput').value = selKode;
    updatePayUI(type);
}

function updatePayUI(type) {
    const btn = document.getElementById('btnSubmitOrder');
    if(btn) {
        if(type === 'midtrans') {
            btn.className = 'btn-submit btn-online';
            btn.innerHTML = `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3"/></svg> Pesan & Bayar Online`;
            btn.setAttribute('onclick', 'openConfirmPopup()');
        } else {
            btn.className = 'btn-submit btn-cash';
            btn.innerHTML = `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg> Pesan Sekarang`;
            btn.setAttribute('onclick', 'openConfirmPopup()');
        }
    }
    const box = document.getElementById('infoPayBox');
    if(!box) return;
    if(type === 'kasir') {
        box.innerHTML = `<div class="flex gap-2.5 items-start bg-orange-50 rounded-2xl p-3.5 text-xs">
            <svg class="w-4 h-4 text-orange-400 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
            <p class="text-orange-700 font-semibold">Setelah memesan, tunjukkan nomor antrian ke kasir dan bayar tunai di tempat.</p></div>`;
    } else {
        box.innerHTML = `<div class="flex gap-2.5 items-start bg-green-50 rounded-2xl p-3.5 text-xs">
            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
            <p class="text-green-700 font-semibold">Kamu akan diarahkan ke halaman pembayaran Midtrans. Pilih GoPay, VA Bank, dll.</p></div>`;
    }
}

function renderCart() {
    const container = document.getElementById('checkoutItems');
    const detail    = document.getElementById('detailItems');
    let subtotal = 0;
    if(!cart.length) {
        container.innerHTML = `<div class="text-center py-8">
            <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            <p class="text-gray-400 font-semibold text-sm">Keranjang kosong</p>
            <a href="/customer/home" class="text-orange-500 font-bold text-sm mt-1 inline-block">← Kembali ke Menu</a></div>`;
        detail.innerHTML = '';
        updateTotals(0); return;
    }
    container.innerHTML = cart.map(item => {
        subtotal += item.price * item.quantity;
        const img = item.image ? `/storage/${item.image}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(item.name)}&background=ffecd2&color=f97316&bold=true&size=56`;
        return `<div class="flex gap-3 items-center py-1" id="row_${item.id}">
            <img src="${img}" class="w-14 h-14 rounded-2xl object-cover flex-shrink-0 border border-gray-100">
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm text-gray-900 truncate">${item.name}</p>
                <p class="text-orange-500 font-extrabold text-xs mt-0.5">Rp ${item.price.toLocaleString('id-ID')}</p>
                <input type="text" value="${esc(item.notes||'')}" placeholder="Catatan item ini..."
                    oninput="updateNote(${item.id}, this.value)"
                    class="mt-1 w-full bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs focus:outline-none focus:border-orange-300 transition-colors">
            </div>
            <div class="qty-pill flex-shrink-0">
                <button onclick="changeQty(${item.id},-1)" class="q-btn">−</button>
                <span class="text-sm font-extrabold text-gray-800 w-6 text-center">${item.quantity}</span>
                <button onclick="changeQty(${item.id},1)" class="q-btn plus">+</button>
            </div>
        </div>`;
    }).join('');
    detail.innerHTML = cart.map(i => `
        <div class="flex justify-between text-xs">
            <span class="text-gray-500 truncate pr-4">${i.name} <span class="font-bold text-gray-600">×${i.quantity}</span></span>
            <span class="font-bold text-gray-700 flex-shrink-0">Rp ${(i.price*i.quantity).toLocaleString('id-ID')}</span>
        </div>`).join('');
    updateTotals(subtotal);
}

function updateTotals(s) {
    document.getElementById('subTotal').textContent = 'Rp ' + s.toLocaleString('id-ID');
    document.getElementById('grandTotal').textContent = 'Rp ' + (s+2000).toLocaleString('id-ID');
}

function changeQty(id, d) {
    const item = cart.find(i=>i.id===id);
    if(!item) return;
    item.quantity += d;
    if(item.quantity <= 0) { cart = cart.filter(i=>i.id!==id); toast('Item dihapus','warning'); }
    saveCart(); renderCart();
}
function updateNote(id, v) { const i = cart.find(x=>x.id===id); if(i){i.notes=v;saveCart();} }
function saveCart() { localStorage.setItem('checkoutCart', JSON.stringify(cart)); localStorage.setItem('cart', JSON.stringify(cart)); }
function esc(s) { return String(s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

// ── Confirm Order Popup ───────────────────────────
function openConfirmPopup() {
    if(!cart.length) { toast('Keranjang kosong! Tambah menu dulu 🛒','error',2800); return; }
    const nama = document.getElementById('customerNameInput').value.trim();
    if(!nama) {
        var inp = document.getElementById('customerNameInput');
        inp.classList.add('input-error');
        inp.focus();
        document.getElementById('errCustomerName').classList.add('show');
        toast('Nama pelanggan wajib diisi!','error',2800); return;
    }
    const tn = document.getElementById('tableNumberInput').value;
    if(!tn) { toast('Scan QR Code meja dulu!','warning',2800); return; }

    // Sesuaikan warna & teks tombol dengan metode bayar
    const isMidtrans = selType === 'midtrans';
    const btnYes = document.getElementById('btnConfirmYes');
    document.getElementById('btnConfirmYesText').textContent = isMidtrans ? 'Ya, Bayar Online' : 'Ya, Pesan Sekarang';
    btnYes.className = `btn-confirm-yes ${isMidtrans ? 'btn-confirm-online' : 'btn-confirm-cash'}`;

    document.getElementById('confirmPopup').classList.add('show');
    document.body.classList.add('no-scroll');
}

function closeConfirmPopup(e) {
    if (e && e.target !== document.getElementById('confirmPopup')) return;
    document.getElementById('confirmPopup').classList.remove('show');
    document.body.classList.remove('no-scroll');
}

function doSubmitOrder() {
    document.getElementById('confirmPopup').classList.remove('show');
    document.body.classList.remove('no-scroll');

    document.getElementById('cartInput').value = JSON.stringify(cart);
    document.getElementById('noteHidden').value = document.getElementById('noteInput').value;
    document.getElementById('customerNameHidden').value = document.getElementById('customerNameInput').value.trim();
    document.getElementById('paymentMethodInput').value = selKode;

    toast(selType === 'midtrans' ? 'Mengarahkan ke pembayaran... 💳' : 'Memproses pesanan... 🍽️','success',2500);
    const btn = document.getElementById('btnSubmitOrder');
    if(btn) { btn.disabled=true; btn.innerHTML=`<span class="spinner"></span> Memproses...`; }
    document.getElementById('orderForm').submit();
}


document.getElementById('customerNameInput').addEventListener('input', function() {
    this.classList.remove('input-error');
    document.getElementById('errCustomerName').classList.remove('show');
});

window.addEventListener('pageshow', e => {
    if(e.persisted) { const btn = document.getElementById('btnSubmitOrder'); if(btn){btn.disabled=false; updatePayUI(selType);} }
});

// ── Back popup ────────────────────────────────────
let _backPopupOpen = false;
function openBackPopup() {
    _backPopupOpen = true;
    document.getElementById('backPopup').classList.add('show');
    history.pushState({ popup: true }, '', location.href);
}
function closeBackPopup(e) {
    if (e && e.target !== document.getElementById('backPopup')) return;
    _backPopupOpen = false;
    document.getElementById('backPopup').classList.remove('show');
}
function confirmBackCheckout() {
    _backPopupOpen = false;
    document.getElementById('backPopup').classList.remove('show');
    window.location.href = '/customer/home';
}
window.addEventListener('popstate', function() {
    if (_backPopupOpen) {
        _backPopupOpen = false;
        document.getElementById('backPopup').classList.remove('show');
        return;
    }
    openBackPopup();
});
history.pushState(null, '', location.href);
</script>
</body>
</html>