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

        /* ── ORDER TYPE ── */
        .otype-btn {
            flex: 1; padding: 14px 10px; border-radius: 16px; border: 2px solid #f1f5f9;
            background: #fff; cursor: pointer; transition: all .22s; text-align: center;
            display: flex; flex-direction: column; align-items: center; gap: 6px;
        }
        .otype-btn:hover { border-color: #f97316; background: #fffbf5; }
        .otype-btn.selected-dine { border-color: #f97316; background: #fff7ed; box-shadow: 0 4px 14px rgba(249,115,22,.14); }
        .otype-btn.selected-take { border-color: #6366f1; background: #eef2ff; box-shadow: 0 4px 14px rgba(99,102,241,.14); }
        .otype-icon { font-size: 28px; line-height: 1; }
        .otype-label { font-size: 12px; font-weight: 800; letter-spacing: .3px; }
        .otype-desc  { font-size: 10.5px; color: #94a3b8; font-weight: 500; }

        /* Payment option cards */
        .payment-item { padding: 14px 16px; border-radius: 16px; cursor: pointer; background: #ffffff; transition: all 0.25s cubic-bezier(0.4,0,0.2,1); border: 2px solid #f1f5f9; display: flex; align-items: center; gap: 12px; }
        .payment-item:hover { border-color: #fdba74; background: #fffbf5; }
        .payment-item.selected { background: #fff7ed; border-color: #f97316; box-shadow: 0 4px 12px rgba(249,115,22,0.12); }
        .payment-item .check-icon { width: 22px; height: 22px; border-radius: 50%; border: 2px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-left: auto; transition: all 0.2s; }
        .payment-item.selected .check-icon { background: #f97316; border-color: #f97316; }
        .payment-item.selected .check-icon::after { content: ''; width: 10px; height: 6px; border-left: 2px solid white; border-bottom: 2px solid white; transform: rotate(-45deg) translateY(-1px); display: block; }

        .qty-btn { width: 30px; height: 30px; border-radius: 9px; display: flex; align-items: center; justify-content: center; transition: all 0.18s; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.08); font-size: 18px; line-height: 1; color: #475569; border: none; cursor: pointer; }
        .qty-btn:hover { background: #f1f5f9; }
        .qty-btn:active { transform: scale(0.9); }

        .btn-order { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); box-shadow: 0 10px 20px -4px rgba(249,115,22,0.35); transition: all 0.2s; }
        .btn-order:active { transform: scale(0.98); box-shadow: none; }
        .btn-order:disabled { opacity: 0.7; cursor: not-allowed; }

        textarea:focus { outline: none; border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.1); }

        @keyframes fadeUp { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform: translateY(0); } }
        .animate-fade { animation: fadeUp 0.35s ease-out forwards; }

        .info-box { border-radius: 18px; padding: 16px 18px; display: flex; gap: 14px; align-items: flex-start; }
        .info-box-icon { width: 44px; height: 44px; border-radius: 14px; background: white; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .item-row { display: flex; gap: 14px; align-items: center; }
        .item-img { width: 56px; height: 56px; border-radius: 14px; object-fit: cover; border: 1px solid #f1f5f9; flex-shrink: 0; }
        .divider-dash { border: none; border-top: 2px dashed #f1f5f9; margin: 10px 0; }

        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.8s linear infinite; display: inline-block; }

        /* meja panel */
        #mejaPanelWrap { transition: all .3s ease; }
    </style>
</head>
<body class="pb-12">

<!-- ══ TOPBAR ══ -->
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


    {{-- ── NOMOR MEJA (hanya tampil jika Dine In) ── --}}
    <div id="mejaPanelWrap" class="animate-fade">
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
            <a href="/customer/home"
               class="text-sm px-4 py-2 bg-orange-50 text-orange-600 font-bold rounded-xl active:scale-95 transition-all hover:bg-orange-100">
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

    {{-- ── ITEM PESANAN ── --}}
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

    {{-- ── CATATAN ── --}}
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

    {{-- ── RINCIAN PEMBAYARAN ── --}}
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
    @if($paymentMethods->isEmpty())
    <div class="bg-red-50 border border-red-100 p-5 rounded-2xl text-center animate-fade">
        <p class="text-red-600 font-bold text-sm">⚠️ Tidak ada metode pembayaran aktif</p>
        <p class="text-red-400 text-xs mt-1">Hubungi kasir untuk bantuan.</p>
    </div>
    @else
    <div class="bg-white p-5 rounded-2xl card-shadow animate-fade">
        <h2 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Metode Pembayaran
        </h2>
        <div class="space-y-3" id="paymentOptions">
            @foreach($paymentMethods as $i => $pm)
            @php
                $icon = match($pm->kode) {
                    'cash'  => '💵', 'qris'  => '📱', 'bank'  => '🏦', default => '💳',
                };
                $label = match($pm->kode) {
                    'cash'  => 'Tunai / Cash', 'qris'  => 'QRIS', 'bank'  => 'Transfer Bank', default => $pm->nama,
                };
                $desc = match($pm->kode) {
                    'cash'  => 'Bayar langsung ke kasir', 'qris'  => 'Scan QR Code untuk bayar', 'bank'  => 'Transfer ke rekening', default => 'Metode pembayaran lain',
                };
            @endphp
            <div class="payment-item {{ $i === 0 ? 'selected' : '' }}"
                 id="opt_{{ $pm->kode }}"
                 onclick="selectPayment('{{ $pm->kode }}')">
                <span class="text-2xl">{{ $icon }}</span>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-slate-800">{{ $label }}</p>
                    <p class="text-xs text-slate-400">{{ $desc }}</p>
                </div>
                <div class="check-icon"></div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── INFO BOX (dinamis) ── --}}
    <div id="infoBoxWrap" class="animate-fade"></div>
    @endif

    {{-- ── FORM PESAN ── --}}
    <form action="/customer/order" method="POST" id="orderForm" class="pt-2">
        @csrf
        <input type="hidden" name="cart"           id="cartInput">
        <input type="hidden" name="note"           id="noteHidden">
        <input type="hidden" name="payment_method" id="paymentMethodInput"
               value="{{ $paymentMethods->first()?->kode ?? 'cash' }}">
        <input type="hidden" name="table_number"   id="tableNumberInput"
               value="{{ $tableNumber ?? '' }}">
        <input type="hidden" name="order_type" value="dine_in">

        @if($paymentMethods->isEmpty())
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

</div>{{-- /max-w --}}

<script>
// ══════════════════════════════════════════════════════
//  DATA FROM BLADE
// ══════════════════════════════════════════════════════
const paymentMethods = @json($paymentMethods->values());
const pmMap = {};
paymentMethods.forEach(pm => { pmMap[pm.kode] = pm; });

// ── SERVER-SIDE table number (dari session PHP) ──
const serverTableNumber = @json($tableNumber ?? null);

// ══════════════════════════════════════════════════════
//  INFO BOX TEMPLATES
// ══════════════════════════════════════════════════════
function getInfoBox(kode) {
    const pm = pmMap[kode];
    if (!pm) return '';
    const configs = {
        cash: { bg:'bg-orange-50', border:'border-orange-100', icon:'💵', title:'Bayar ke Kasir', titleColor:'text-orange-900', textColor:'text-orange-700', body:'Setelah klik <strong>Pesan Sekarang</strong>, tunjukkan nomor pesananmu ke kasir untuk melakukan pembayaran tunai.' },
        qris: { bg:'bg-indigo-50', border:'border-indigo-100', icon:'📱', title:'Bayar via QRIS', titleColor:'text-indigo-900', textColor:'text-indigo-700', body:'Setelah klik <strong>Pesan Sekarang</strong>, halaman QR Code akan muncul. Scan menggunakan aplikasi dompet digitalmu.' },
        bank: { bg:'bg-blue-50', border:'border-blue-100', icon:'🏦', title:'Transfer Bank', titleColor:'text-blue-900', textColor:'text-blue-700', body:'Setelah memesan, lakukan transfer ke rekening yang tertera dan konfirmasi ke kasir.' },
    };
    const cfg = configs[kode] ?? { bg:'bg-slate-50', border:'border-slate-100', icon:'💳', title:pm.nama, titleColor:'text-slate-800', textColor:'text-slate-600', body:'Setelah memesan, ikuti instruksi pembayaran dari kasir.' };
    return `<div class="info-box ${cfg.bg} border ${cfg.border}"><div class="info-box-icon">${cfg.icon}</div><div class="space-y-1 flex-1"><p class="font-bold text-sm ${cfg.titleColor}">${cfg.title}</p><p class="text-xs leading-relaxed ${cfg.textColor}">${cfg.body}</p></div></div>`;
}

// ══════════════════════════════════════════════════════
//  STATE & CART
// ══════════════════════════════════════════════════════
let cart = JSON.parse(localStorage.getItem('checkoutCart')) || JSON.parse(localStorage.getItem('cart')) || [];
let selectedKode = paymentMethods.length > 0 ? paymentMethods[0].kode : 'cash';

// ══════════════════════════════════════════════════════
//  INIT
// ══════════════════════════════════════════════════════
(function init() {
    // Prioritaskan server-side session, fallback ke localStorage
    const tableNum = serverTableNumber || localStorage.getItem('table_number');
    if (tableNum) {
        localStorage.setItem('table_number', tableNum);
        document.getElementById('tableNumberInput').value = tableNum;
        const disp = document.getElementById('tableNumberDisplay');
        if (disp && !serverTableNumber) {
            disp.textContent = 'Meja ' + tableNum;
        }
    }

    renderCart();
    renderInfoBox(selectedKode);
})();

// ══════════════════════════════════════════════════════
//  RENDER CART
// ══════════════════════════════════════════════════════
function renderCart() {
    const container = document.getElementById('checkoutItems');
    const detail    = document.getElementById('detailItems');
    let subtotal    = 0;

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
    if (item.quantity <= 0) cart = cart.filter(i => i.id !== id);
    saveCart(); renderCart();
}

function updateItemNote(id, value) {
    const item = cart.find(i => i.id === id);
    if (item) { item.notes = value; saveCart(); }
}

function saveCart() {
    localStorage.setItem('checkoutCart', JSON.stringify(cart));
}

// ══════════════════════════════════════════════════════
//  PAYMENT SELECTION
// ══════════════════════════════════════════════════════
function selectPayment(kode) {
    document.querySelectorAll('.payment-item').forEach(el => el.classList.remove('selected'));
    const el = document.getElementById('opt_' + kode);
    if (el) el.classList.add('selected');
    selectedKode = kode;
    document.getElementById('paymentMethodInput').value = kode;
    renderInfoBox(kode);
}

function renderInfoBox(kode) {
    const wrap = document.getElementById('infoBoxWrap');
    if (wrap) wrap.innerHTML = getInfoBox(kode);
}

// ══════════════════════════════════════════════════════
//  FORM SUBMIT
// ══════════════════════════════════════════════════════
document.getElementById('orderForm')?.addEventListener('submit', function(e) {
    if (!cart || cart.length === 0) {
        e.preventDefault();
        showToast('Keranjang masih kosong! Tambah menu dulu ya. 🛒');
        return;
    }

    // wajib scan meja
const tn = document.getElementById('tableNumberInput').value;

if (!tn) {
    e.preventDefault();
    showToast('⚠️ Scan QR Code meja dulu!');
    return;
}

    document.getElementById('cartInput').value  = JSON.stringify(cart);
    document.getElementById('noteHidden').value = document.getElementById('noteInput').value;

    const btn = document.getElementById('btnSubmitOrder');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner"></span> Memproses...`;
    }
});

// ══════════════════════════════════════════════════════
//  HELPERS
// ══════════════════════════════════════════════════════
function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function showToast(msg) {
    const t = document.createElement('div');
    t.textContent = msg;
    t.style.cssText = `position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#1e293b;color:white;padding:12px 20px;border-radius:14px;font-size:13px;font-weight:600;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,0.2);animation:fadeUp .3s ease;white-space:nowrap;`;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2800);
}
</script>
</body>
</html>
