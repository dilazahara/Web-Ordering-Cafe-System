<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Checkout - Konfirmasi Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { background-color: #f8fafc; color: #1e293b; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .glass-header { background: rgba(255,255,255,0.85); backdrop-filter: blur(14px); border-bottom: 1px solid rgba(226,232,240,0.8); }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }

        /* ── Payment Option Cards ── */
        .pay-opt {
            position: relative;
            cursor: pointer;
            background: #ffffff;
            border-radius: 18px;
            border: 2px solid #f1f5f9;
            transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
            overflow: hidden;
        }
        .pay-opt:hover { border-color: #fdba74; background: #fffbf5; }
        .pay-opt.selected { border-color: #f97316; box-shadow: 0 4px 16px rgba(249,115,22,0.15); }
        .pay-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }

        /* Kasir option */
        .pay-opt-kasir.selected { background: #fff7ed; border-color: #f97316; }
        /* Midtrans option */
        .pay-opt-midtrans.selected { background: #f0fdf4; border-color: #22c55e; box-shadow: 0 4px 16px rgba(34,197,94,0.12); }
        .pay-opt-midtrans:hover { border-color: #86efac; background: #f0fdf4; }

        .radio-dot {
            width: 22px; height: 22px; border-radius: 50%;
            border: 2px solid #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: all 0.2s;
        }
        .pay-opt-kasir.selected .radio-dot { background: #f97316; border-color: #f97316; }
        .pay-opt-midtrans.selected .radio-dot { background: #22c55e; border-color: #22c55e; }
        .pay-opt.selected .radio-dot::after {
            content: ''; width: 8px; height: 8px;
            background: white; border-radius: 50%; display: block;
        }

        .qty-btn { width: 30px; height: 30px; border-radius: 9px; display: flex; align-items: center; justify-content: center; transition: all 0.18s; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.08); font-size: 18px; line-height: 1; color: #475569; border: none; cursor: pointer; }
        .qty-btn:hover { background: #f1f5f9; }
        .qty-btn:active { transform: scale(0.9); }
        .btn-order { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); box-shadow: 0 10px 20px -4px rgba(249,115,22,0.35); transition: all 0.2s; }
        .btn-order-midtrans { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 10px 20px -4px rgba(34,197,94,0.35); transition: all 0.2s; }
        .btn-order:active, .btn-order-midtrans:active { transform: scale(0.98); box-shadow: none; }
        .btn-order:disabled, .btn-order-midtrans:disabled { opacity: 0.7; cursor: not-allowed; }
        textarea:focus { outline: none; border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.1); }
        @keyframes fadeUp { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform: translateY(0); } }
        .animate-fade { animation: fadeUp 0.35s ease-out forwards; }
        .item-row { display: flex; gap: 14px; align-items: center; }
        .item-img { width: 56px; height: 56px; border-radius: 14px; object-fit: cover; border: 1px solid #f1f5f9; flex-shrink: 0; }
        .divider-dash { border: none; border-top: 2px dashed #f1f5f9; margin: 10px 0; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.8s linear infinite; display: inline-block; }

        /* Info box bawah pilihan */
        .info-pay {
            border-radius: 14px; padding: 12px 14px;
            display: flex; gap: 10px; align-items: flex-start;
            font-size: 12px; line-height: 1.5;
        }
    </style>
</head>
<body class="pb-12">

<!-- TOPBAR -->
<div class="glass-header px-4 py-3.5 flex items-center gap-4 sticky top-0 z-50">
    <button onclick="history.back()" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl shadow-sm active:scale-90 transition-transform">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <div>
        <h1 class="font-extrabold text-lg tracking-tight text-slate-800 leading-none">Checkout</h1>
    </div>
</div>

<div class="max-w-xl mx-auto p-4 space-y-4">

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-2xl px-4 py-3 flex items-center gap-3 animate-fade">
        <span class="text-red-500 text-xl flex-shrink-0">❌</span>
        <div>
            <p class="text-red-700 font-bold text-sm">Pesanan Gagal</p>
            <p class="text-red-600 text-xs mt-0.5">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl px-4 py-3 animate-fade">
        <p class="text-red-700 font-bold text-sm mb-1">⚠️ Ada masalah saat memesan:</p>
        <ul class="text-red-600 text-xs space-y-1">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- NOMOR MEJA --}}
    <div class="animate-fade">
        <div class="bg-white p-4 rounded-2xl card-shadow flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Meja</p>
                    <p id="tableNumberDisplay" class="text-base font-black text-slate-800">
                        @if($tableNumber) Meja {{ $tableNumber }} @else Belum dipilih @endif
                    </p>
                </div>
            </div>
            <a href="/customer/home" class="text-sm px-4 py-2 bg-orange-50 text-orange-600 font-bold rounded-xl active:scale-95 transition-all hover:bg-orange-100">
                + Menu
            </a>
        </div>
        @if(!$tableNumber)
        <div class="mt-2 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-center gap-2">
            <span class="text-amber-500 text-lg">⚠️</span>
            <p class="text-amber-700 text-xs font-semibold">Nomor meja belum terdeteksi. Scan QR Code di meja kamu dulu</p>
        </div>
        @endif
    </div>

    {{-- ITEM PESANAN --}}
    <div class="bg-white p-5 rounded-2xl card-shadow animate-fade">
        <h2 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Item Pesanan
        </h2>
        <div id="checkoutItems" class="space-y-4">
            <p class="text-slate-400 text-sm text-center py-6">Memuat keranjang...</p>
        </div>
    </div>

    {{-- NAMA PEMESAN --}}
    <div class="bg-white p-5 rounded-2xl card-shadow animate-fade">
        <h2 class="font-bold text-slate-700 mb-3 text-sm uppercase tracking-wider flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Nama Pemesan
        </h2>
        <input type="text" id="customerNameInput" maxlength="100" required
               class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm transition-all focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
               placeholder="Masukkan nama kamu">
    </div>

    {{-- CATATAN --}}
    <div class="bg-white p-5 rounded-2xl card-shadow animate-fade">
        <h2 class="font-bold text-slate-700 mb-3 text-sm uppercase tracking-wider flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Catatan Tambahan
        </h2>
        <textarea id="noteInput" rows="2"
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm transition-all resize-none"
                  placeholder="Contoh: tidak pakai sambal, level pedas, dll."></textarea>
    </div>

    {{-- RINCIAN BIAYA --}}
    <div class="bg-white p-5 rounded-2xl card-shadow animate-fade">
        <h2 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider">Rincian Biaya</h2>
        <div id="detailItems" class="space-y-2 text-sm text-slate-500 mb-3"></div>
        <hr class="divider-dash">
        <div class="space-y-2 text-sm mt-3">
            <div class="flex justify-between">
                <span class="text-slate-500">Subtotal</span>
                <span id="subTotal" class="font-bold text-slate-800">Rp 0</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Biaya Layanan</span>
                <span class="font-bold text-slate-800">Rp 2.000</span>
            </div>
        </div>
        <hr class="divider-dash mt-3">
        <div class="flex justify-between items-center mt-3">
            <span class="font-bold text-slate-800">Total</span>
            <span id="grandTotal" class="text-xl font-black text-orange-600">Rp 0</span>
        </div>
    </div>

    {{-- ── METODE PEMBAYARAN ── --}}
    <div class="bg-white p-5 rounded-2xl card-shadow animate-fade">
        <h2 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Metode Pembayaran
        </h2>

        @php
            $midtransCodes  = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans'];
            $kasirMethod    = $paymentMethods->firstWhere('kode', 'cash');
            $midtransMethod = $paymentMethods->whereIn('kode', $midtransCodes)->first();
            $hasCash        = $kasirMethod && $kasirMethod->aktif;
            $hasMidtrans    = $midtransMethod && $midtransMethod->aktif;
        @endphp

        @if(!$hasCash && !$hasMidtrans)
        <div class="bg-red-50 border border-red-100 p-4 rounded-2xl text-center">
            <p class="text-red-600 font-bold text-sm">⚠️ Tidak ada metode pembayaran aktif</p>
            <p class="text-red-400 text-xs mt-1">Hubungi kasir untuk bantuan.</p>
        </div>
        @else

        <div class="space-y-3" id="paymentOptions">

            {{-- ── OPSI 1: BAYAR DI KASIR ── --}}
            @if($hasCash)
            <label class="pay-opt pay-opt-kasir selected block p-4 cursor-pointer" id="opt_kasir" onclick="selectPayment('cash', 'kasir')">
                <input type="radio" name="pay_choice" value="cash" checked>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                        🏪
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 text-sm">Bayar di Kasir</p>
                        <p class="text-xs text-slate-400 mt-0.5">Tunjukkan pesanan ke kasir &amp; bayar tunai</p>
                    </div>
                    <div class="radio-dot"></div>
                </div>
            </label>
            @endif

            {{-- ── OPSI 2: BAYAR ONLINE MIDTRANS ── --}}
            @if($hasMidtrans)
            <label class="pay-opt pay-opt-midtrans {{ !$hasCash ? 'selected' : '' }} block p-4 cursor-pointer" id="opt_midtrans" onclick="selectPayment('midtrans_snap', 'midtrans')">
                <input type="radio" name="pay_choice" value="midtrans_snap" {{ !$hasCash ? 'checked' : '' }}>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                        💳
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 text-sm">Bayar Online Midtrans</p>
                        <p class="text-xs text-slate-400 mt-0.5">GoPay, OVO, DANA, VA Bank &amp; lainnya</p>
                    </div>
                    <div class="radio-dot"></div>
                </div>
            </label>
            @endif

        </div>

        {{-- ── INFO BOX DINAMIS ── --}}
        <div id="infoPayBox" class="mt-3"></div>

        @endif
    </div>

    {{-- ── FORM PESAN ── --}}
    <form action="{{ route('customer.order.store') }}" method="POST" id="orderForm" class="pt-2">
        @csrf
        <input type="hidden" name="cart"           id="cartInput">
        <input type="hidden" name="note"           id="noteHidden">
        <input type="hidden" name="customer_name"  id="customerNameHidden">
        <input type="hidden" name="payment_method" id="paymentMethodInput" value="{{ $hasCash ? 'cash' : ($hasMidtrans ? $midtransMethod->kode : '') }}">
        <input type="hidden" name="table_number"   id="tableNumberInput"   value="{{ $tableNumber ?? '' }}">
        <input type="hidden" name="order_type"     value="dine_in">

        @if(!$hasCash && !$hasMidtrans)
        <button type="button" disabled
                class="w-full py-4 rounded-2xl font-black text-lg bg-slate-200 text-slate-400 cursor-not-allowed">
            Tidak Dapat Memesan
        </button>
        @else
        <button type="submit" id="btnSubmitOrder"
                class="w-full btn-order text-white py-4 rounded-2xl font-black text-lg flex items-center justify-center gap-2">
            🛒 Pesan Sekarang
        </button>
        @endif
    </form>

</div>

<script>
// ══ TOAST ═══════════════════════════════════════════
(function initToast() {
    const el = document.createElement('div');
    el.id = 'toastContainer';
    el.style.cssText = 'position:fixed;top:16px;left:50%;transform:translateX(-50%);z-index:99999;display:flex;flex-direction:column;gap:8px;align-items:center;pointer-events:none;min-width:0;width:max-content;max-width:calc(100vw - 32px);';
    document.body.appendChild(el);
})();

function showToast(msg, type = 'success', duration = 2200) {
    const container = document.getElementById('toastContainer');
    const colors = { success:'background:#22c55e;color:white;', info:'background:#1e293b;color:white;', warning:'background:#f59e0b;color:white;', error:'background:#ef4444;color:white;' };
    const icons  = { success:'✅', info:'ℹ️', warning:'⚠️', error:'❌' };
    const t = document.createElement('div');
    t.style.cssText = `pointer-events:auto;display:flex;align-items:center;gap:8px;padding:10px 18px;border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,0.15);font-size:13px;font-weight:600;white-space:nowrap;opacity:0;transform:translateY(-10px) scale(0.95);transition:all 0.25s ease;${colors[type]||colors.info}`;
    t.innerHTML = `<span>${icons[type]||'📢'}</span><span>${msg}</span>`;
    container.appendChild(t);
    requestAnimationFrame(()=>{ t.style.opacity='1'; t.style.transform='translateY(0) scale(1)'; });
    setTimeout(()=>{ t.style.opacity='0'; t.style.transform='translateY(-10px) scale(0.95)'; setTimeout(()=>t.remove(),260); }, duration);
}

// ══ DATA FROM BLADE ═══════════════════════════════
const paymentMethods   = @json($paymentMethods->values());
const serverTableNumber = @json($tableNumber ?? null);

// State aktif dari server
const hasCash          = @json($hasCash);
const hasMidtrans      = @json($hasMidtrans);
const midtransKode     = @json($hasMidtrans ? $midtransMethod->kode : 'midtrans');
const midtransCodes    = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans'];

function getDefaultMidtransKode() {
    // Gunakan kode aktual dari database, bukan hardcode 'midtrans'
    return midtransKode;
}

// ══ STATE ═════════════════════════════════════════
let cart = JSON.parse(localStorage.getItem('checkoutCart')) || JSON.parse(localStorage.getItem('cart')) || [];
// 'kasir' atau 'midtrans'
let selectedType = hasCash ? 'kasir' : 'midtrans';
// kode aktual yang dikirim ke server
let selectedKode = hasCash ? 'cash' : getDefaultMidtransKode();
// Set initial value untuk hidden input
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('paymentMethodInput').value = selectedKode;
});

// ══ INIT ══════════════════════════════════════════
(function init() {
    const tableNum = serverTableNumber || localStorage.getItem('table_number');
    if (tableNum) {
        localStorage.setItem('table_number', tableNum);
        document.getElementById('tableNumberInput').value = tableNum;
        const disp = document.getElementById('tableNumberDisplay');
        if (disp && !serverTableNumber) disp.textContent = 'Meja ' + tableNum;
    }
    renderCart();
    updatePaymentUI(selectedType, selectedKode);
})();

// ══ PILIH PEMBAYARAN ══════════════════════════════
function selectPayment(kodeValue, type) {
    selectedType = type;

    if (type === 'kasir') {
        selectedKode = 'cash';
        document.getElementById('opt_kasir')?.classList.add('selected');
        document.getElementById('opt_midtrans')?.classList.remove('selected');
    } else {
        selectedKode = getDefaultMidtransKode();
        document.getElementById('opt_kasir')?.classList.remove('selected');
        document.getElementById('opt_midtrans')?.classList.add('selected');
    }

    document.getElementById('paymentMethodInput').value = selectedKode;
    updatePaymentUI(type, selectedKode);
}

function updatePaymentUI(type, kode) {
    // Update tombol submit
    const btn = document.getElementById('btnSubmitOrder');
    if (btn) {
        if (type === 'midtrans') {
            btn.className = 'w-full btn-order-midtrans text-white py-4 rounded-2xl font-black text-lg flex items-center justify-center gap-2';
            btn.innerHTML = '💳 Pesan &amp; Bayar Online';
        } else {
            btn.className = 'w-full btn-order text-white py-4 rounded-2xl font-black text-lg flex items-center justify-center gap-2';
            btn.innerHTML = '🛒 Pesan Sekarang';
        }
    }

    // Info box
    const box = document.getElementById('infoPayBox');
    if (!box) return;
    if (type === 'kasir') {
        box.innerHTML = `
        <div class="info-pay bg-orange-50 border border-orange-100">
            <span class="text-xl">🏪</span>
            <div>
                <p class="font-bold text-orange-900 text-xs">Bayar di Kasir</p>
                <p class="text-orange-700 text-xs mt-0.5">Setelah klik <strong>Pesan Sekarang</strong>, tunjukkan nomor antrianmu ke kasir dan bayar tunai di tempat.</p>
            </div>
        </div>`;
    } else {
        box.innerHTML = `
        <div class="info-pay bg-green-50 border border-green-100">
            <span class="text-xl">💳</span>
            <div>
                <p class="font-bold text-green-900 text-xs">Bayar Online via Midtrans</p>
                <p class="text-green-700 text-xs mt-0.5">Setelah klik <strong>Pesan &amp; Bayar Online</strong>, kamu akan diarahkan ke halaman pembayaran Midtrans. Pilih metode (GoPay, VA Bank, dll.) lalu selesaikan pembayaran.</p>
            </div>
        </div>`;
    }
}

// ══ RENDER CART ═══════════════════════════════════
function renderCart() {
    const container = document.getElementById('checkoutItems');
    const detail    = document.getElementById('detailItems');
    let subtotal = 0;

    if (!cart || cart.length === 0) {
        container.innerHTML = `<div class="text-center py-8"><p class="text-4xl mb-2">🛒</p><p class="text-slate-400 text-sm font-medium">Keranjang kosong</p><a href="/customer/home" class="text-orange-500 text-sm font-bold mt-1 inline-block">Kembali ke Menu →</a></div>`;
        detail.innerHTML = '';
        updateTotals(0);
        return;
    }

    container.innerHTML = cart.map(item => {
        subtotal += item.price * item.quantity;
        const imgSrc = item.image ? `/storage/${item.image}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(item.name)}&background=ffecd2&color=f97316&bold=true&size=56`;
        return `
        <div class="item-row animate-fade" id="itemRow_${item.id}">
            <img src="${imgSrc}" alt="${item.name}" class="item-img" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(item.name)}&background=ffecd2&color=f97316&bold=true&size=56'">
            <div class="flex-1 min-w-0">
                <p class="font-bold text-slate-800 text-sm leading-tight truncate">${item.name}</p>
                <p class="text-orange-500 font-extrabold text-xs mt-0.5">Rp ${item.price.toLocaleString('id-ID')}</p>
                <input type="text" value="${escHtml(item.notes || '')}" placeholder="Catatan item ini..." oninput="updateItemNote(${item.id}, this.value)"
                       class="text-xs mt-1.5 w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-orange-300 transition-colors">
            </div>
            <div class="flex items-center gap-1.5 bg-slate-50 p-1 rounded-xl flex-shrink-0">
                <button onclick="changeQty(${item.id}, -1)" class="qty-btn">−</button>
                <span class="text-sm font-black text-slate-700 w-5 text-center">${item.quantity}</span>
                <button onclick="changeQty(${item.id}, 1)" class="qty-btn">+</button>
            </div>
        </div>`;
    }).join('');

    detail.innerHTML = cart.map(item => `
        <div class="flex justify-between text-xs">
            <span class="truncate pr-4 text-slate-500">${item.name} <span class="font-semibold text-slate-600">×${item.quantity}</span></span>
            <span class="font-bold text-slate-700 flex-shrink-0">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
        </div>`).join('');

    updateTotals(subtotal);
}

function updateTotals(subtotal) {
    const grand = subtotal + 2000;
    document.getElementById('subTotal').textContent   = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('grandTotal').textContent = 'Rp ' + grand.toLocaleString('id-ID');
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.quantity += delta;
    if (item.quantity <= 0) {
        cart = cart.filter(i => i.id !== id);
        showToast('Item dihapus dari pesanan', 'warning', 1800);
    } else {
        showToast(`Jumlah ${item.name}: ${item.quantity}`, 'info', 1400);
    }
    saveCart(); renderCart();
}

function updateItemNote(id, value) {
    const item = cart.find(i => i.id === id);
    if (item) { item.notes = value; saveCart(); }
}

function saveCart() {
    localStorage.setItem('checkoutCart', JSON.stringify(cart));
    localStorage.setItem('cart', JSON.stringify(cart));
}

// ══ FORM SUBMIT ═══════════════════════════════════
document.getElementById('orderForm')?.addEventListener('submit', function(e) {
    if (!cart || cart.length === 0) {
        e.preventDefault();
        showToast('Keranjang masih kosong! Tambah menu dulu ya. 🛒', 'error', 2800);
        return;
    }

    const nama = document.getElementById('customerNameInput').value;
    if (!nama || nama.trim() === '') {
        e.preventDefault();
        document.getElementById('customerNameInput').focus();
        document.getElementById('customerNameInput').style.borderColor = '#ef4444';
        showToast('⚠️ Nama pemesan wajib diisi!', 'error', 2800);
        return;
    }

    const tn = document.getElementById('tableNumberInput').value;
    if (!tn || tn.trim() === '') {
        e.preventDefault();
        showToast('⚠️ Scan QR Code meja dulu!', 'warning', 2800);
        return;
    }

    document.getElementById('cartInput').value           = JSON.stringify(cart);
    document.getElementById('noteHidden').value          = document.getElementById('noteInput').value;
    document.getElementById('customerNameHidden').value  = document.getElementById('customerNameInput').value;
    document.getElementById('paymentMethodInput').value  = selectedKode;

    const msg = selectedType === 'midtrans' ? 'Mengarahkan ke pembayaran... 💳' : 'Pesanan sedang diproses... 🍽️';
    showToast(msg, 'success', 2500);

    const btn = document.getElementById('btnSubmitOrder');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner"></span> Memproses...`;
    }
});

// Reset tombol saat browser back (bfcache)
window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
        const btn = document.getElementById('btnSubmitOrder');
        if (btn) {
            btn.disabled = false;
            updatePaymentUI(selectedType, selectedKode);
        }
    }
});

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
</script>
</body>
</html>