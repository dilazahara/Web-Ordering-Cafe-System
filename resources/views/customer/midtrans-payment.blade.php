<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pembayaran – {{ $order->queue_number }}</title>
    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}" async></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f0f9ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Loading awal (sebelum Snap terbuka) ── */
        #screenLoading {
            text-align: center;
            color: #64748b;
        }

        /* ── Overlay sukses (muncul setelah onSuccess) ── */
        #screenSuccess {
            display: none;
            position: fixed;
            inset: 0;
            background: #fff;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 32px;
            animation: fadeIn 0.3s ease;
        }
        #screenSuccess.show {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.97); }
            to   { opacity: 1; transform: scale(1); }
        }

        /* Spinner */
        .spinner {
            width: 52px;
            height: 52px;
            border: 5px solid #e2e8f0;
            border-top-color: #22c55e;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 20px;
        }
        .spinner-blue {
            border-top-color: #3b82f6;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* Centang sukses */
        .check-circle {
            width: 72px;
            height: 72px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: popIn 0.4s cubic-bezier(.34,1.56,.64,1);
        }
        @keyframes popIn {
            from { transform: scale(0); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
        .check-circle svg {
            width: 36px;
            height: 36px;
        }

        h2 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }
        .sub {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 28px;
        }
        .redirect-hint {
            font-size: 13px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }
        .dot-pulse span {
            display: inline-block;
            width: 6px; height: 6px;
            background: #9ca3af;
            border-radius: 50%;
            margin: 0 2px;
            animation: dotBounce 1.2s ease-in-out infinite;
        }
        .dot-pulse span:nth-child(2) { animation-delay: 0.2s; }
        .dot-pulse span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes dotBounce {
            0%, 80%, 100% { transform: translateY(0); opacity: 0.4; }
            40%            { transform: translateY(-5px); opacity: 1; }
        }

        /* loading awal spinner kecil */
        #screenLoading .spinner { width: 44px; height: 44px; margin-bottom: 14px; }
        #screenLoading p { font-size: 14px; }

        /* Back Popup */
        .back-popup-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.55);
            z-index: 9997; opacity: 0; pointer-events: none;
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .back-popup-overlay.show .back-popup-box { transform: translateY(0); }
        .back-popup-handle {
            width: 40px; height: 4px; background: #e5e7eb;
            border-radius: 2px; margin: 0 auto 20px;
        }
        .back-popup-header {
            display: flex; align-items: center; gap: 14px; margin-bottom: 16px;
        }
        .back-popup-icon {
            width: 48px; height: 48px; min-width: 48px;
            background: #fef2f2; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
        }
        .back-popup-icon svg { width: 24px; height: 24px; }
        .back-popup-title {
            font-size: 16px; font-weight: 800; color: #111827; margin-bottom: 3px;
        }
        .back-popup-subtitle { font-size: 13px; color: #9ca3af; }
        .back-popup-warning {
            font-size: 13px; color: #92400e;
            background: #fffbeb; border-left: 4px solid #f59e0b;
            border-radius: 14px; padding: 12px 14px;
            margin-bottom: 20px; line-height: 1.5;
        }
        .back-popup-btns {
            display: flex; gap: 12px;
        }
        .back-popup-btns button {
            flex: 1; padding: 14px 8px;
            border-radius: 16px; font-size: 14px; font-weight: 700;
            cursor: pointer; border: none; transition: opacity 0.15s, transform 0.1s;
        }
        .back-popup-btns button:active { opacity: 0.85; transform: scale(0.97); }
        .btn-cancel-back {
            background: #f3f4f6; color: #374151;
        }
        .btn-confirm-back {
            background: #ef4444; color: #fff;
        }
        .btn-reopen-snap {
            background: #22c55e; color: #fff;
        }
    </style>
</head>
<body>

{{-- SCREEN 1: Loading awal sebelum Snap terbuka --}}
<div id="screenLoading">
    <div class="spinner spinner-blue"></div>
    <p>Membuka halaman pembayaran...</p>
</div>

{{-- SCREEN 2: Muncul setelah pembayaran sukses & Snap ditutup --}}
<div id="screenSuccess">
    <div class="check-circle">
        <svg viewBox="0 0 50 50" fill="none">
            <path d="M13 25l9 9 15-16" stroke="#16a34a" stroke-width="3.5"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <h2>Pembayaran Berhasil! 🎉</h2>
    <p class="sub">Pesanan kamu sedang diproses</p>

    <div class="redirect-hint">
        <div class="spinner" style="width:18px;height:18px;border-width:3px;margin:0;"></div>
        <span>Mengalihkan ke struk</span>
        <div class="dot-pulse">
            <span></span><span></span><span></span>
        </div>
    </div>
</div>

<script>
const SNAP_TOKEN  = @json($snapToken);
const CONFIRM_URL = @json(route('customer.order.midtrans.confirm', $order->id));
const RECEIPT_URL = @json(route('customer.order.midtrans.receipt', $order->id));
const CSRF_TOKEN  = @json(csrf_token());

let paymentDone = false;

// ── Tampilkan screen sukses lalu redirect ──────────────────────────────────
function tampilSukses(redirectUrl) {
    paymentDone = true;
    bersihkanCart();

    // Sembunyikan loading awal, tampilkan screen sukses
    document.getElementById('screenLoading').style.display = 'none';
    document.getElementById('screenSuccess').classList.add('show');

    // Redirect setelah 2 detik (biar user sempat lihat animasi)
    setTimeout(() => {
        window.location.href = redirectUrl || RECEIPT_URL;
    }, 2000);
}

// ── Buka Snap ─────────────────────────────────────────────────────────────
function bukaSnap() {
    if (!SNAP_TOKEN || typeof window.snap === 'undefined') {
        document.querySelector('#screenLoading p').textContent =
            'Gagal memuat pembayaran. Silakan hubungi kasir.';
        return;
    }

    window.snap.pay(SNAP_TOKEN, {

        // User klik OK setelah "Payment successfully" di simulator
        onSuccess: function(result) {
            tampilSukses(RECEIPT_URL);
        },

        // Pembayaran pending (VA Bank dll) — mulai polling di background
        onPending: function(result) {
            mulaiPolling();
        },

        onError: function(result) {
            document.querySelector('#screenLoading p').textContent =
                'Terjadi kesalahan. Silakan hubungi kasir.';
        },

        onClose: function() {
            if (paymentDone) return;
            // Tampilkan popup peringatan saat user swipe back / tutup Snap
            openSnapClosePopup();
        }
    });
}

// ── Popup saat Snap ditutup / swipe back ──────────────────────────────────
let _snapPopupOpen = false;
function openSnapClosePopup() {
    if (_snapPopupOpen) return;
    _snapPopupOpen = true;
    document.getElementById('snapClosePopup').classList.add('show');
}
function closeSnapClosePopup() {
    _snapPopupOpen = false;
    document.getElementById('snapClosePopup').classList.remove('show');
}
function reopenSnap() {
    closeSnapClosePopup();
    // Tunggu animasi popup tutup dulu baru buka Snap lagi
    setTimeout(() => { bukaSnap(); }, 300);
}

// ── Bersihkan cart ────────────────────────────────────────────────────────
function bersihkanCart() {
    try {
        localStorage.removeItem('cart');
        localStorage.removeItem('checkoutCart');
    } catch(e) {}
}

// ── Polling untuk pembayaran pending (VA Bank) ────────────────────────────
async function cekKonfirmasi() {
    if (paymentDone) return;
    try {
        const res  = await fetch(CONFIRM_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();

        if (data.status === 'ok') {
            tampilSukses(data.redirect || RECEIPT_URL);
        } else if (data.status === 'cancelled') {
            window.location.href = '/';
        }
    } catch(e) { /* abaikan error jaringan sementara */ }
}

function mulaiPolling() {
    setInterval(cekKonfirmasi, 5000);
}

// Cek saat user balik ke tab (e.g. setelah bayar di GoPay)
window.addEventListener('focus', () => { if (!paymentDone) cekKonfirmasi(); });
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible' && !paymentDone) cekKonfirmasi();
});

// ── Tunggu Snap.js siap lalu buka otomatis ────────────────────────────────
(function waitForSnap() {
    let t = 0;
    const iv = setInterval(() => {
        t += 200;
        if (typeof window.snap !== 'undefined') {
            clearInterval(iv);
            bukaSnap();
        } else if (t >= 15000) {
            clearInterval(iv);
            document.querySelector('#screenLoading p').textContent =
                'Gagal memuat Midtrans. Silakan refresh halaman.';
        }
    }, 200);
})();

// ── Back popup pembayaran Midtrans ────────────────────────────────
let _midtransBackPopupOpen = false;
function openMidtransBackPopup() {
    _midtransBackPopupOpen = true;
    document.getElementById('midtransBackPopup').classList.add('show');
    history.pushState({ popup: true }, '', location.href);
}
function closeMidtransBackPopup(e) {
    if (e && e.target !== document.getElementById('midtransBackPopup')) return;
    _midtransBackPopupOpen = false;
    document.getElementById('midtransBackPopup').classList.remove('show');
}
window.addEventListener('popstate', function() {
    if (_midtransBackPopupOpen) {
        _midtransBackPopupOpen = false;
        document.getElementById('midtransBackPopup').classList.remove('show');
        return;
    }
    if (!paymentDone) openMidtransBackPopup();
    else window.location.href = '/customer/home';
});
history.pushState(null, '', location.href);
</script>

<!-- Back Popup Midtrans -->
<div id="midtransBackPopup" class="back-popup-overlay" onclick="closeMidtransBackPopup(event)">
    <div class="back-popup-box">
        <div class="back-popup-handle"></div>
        <div class="back-popup-header">
            <div class="back-popup-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
                    <path stroke-linecap="round" d="M12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <div>
                <div class="back-popup-title">Batalkan pembayaran?</div>
                <div class="back-popup-subtitle">Transaksi online sedang berlangsung</div>
            </div>
        </div>
        <div class="back-popup-warning">
            ⚠️ Keluar sekarang bisa menyebabkan pembayaran tidak terproses. Pastikan sudah menyelesaikan pembayaran terlebih dahulu.
        </div>
        <div class="back-popup-btns">
            <button class="btn-cancel-back" onclick="closeMidtransBackPopup(null)">Lanjutkan Bayar</button>
            <button class="btn-confirm-back" onclick="window.location.href='/customer/home'">Keluar</button>
        </div>
    </div>
</div>