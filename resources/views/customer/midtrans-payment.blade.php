<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pembayaran Online – {{ $order->queue_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Snap.js Midtrans harus load sebelum konten lain --}}
    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 50%, #ecfdf5 100%); min-height: 100vh; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease forwards; }

        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
        .pulse { animation: pulse 1.4s ease-in-out infinite; }

        .success-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
            opacity: 0; pointer-events: none;
            transition: opacity 0.3s;
        }
        .success-overlay.show { opacity: 1; pointer-events: auto; }
        .success-box {
            background: #fff; border-radius: 24px; padding: 36px 28px;
            text-align: center; max-width: 320px; width: 90%;
            transform: scale(0.85); transition: transform 0.3s;
        }
        .success-overlay.show .success-box { transform: scale(1); }

        @keyframes checkmark { from { stroke-dashoffset: 100; } to { stroke-dashoffset: 0; } }
        .check-path { stroke-dasharray: 100; stroke-dashoffset: 100; animation: checkmark 0.6s 0.3s ease forwards; }

        .pay-card { background: #fff; border-radius: 24px; box-shadow: 0 20px 60px rgba(16,185,129,0.15); overflow: hidden; }

        .btn-primary {
            width: 100%; padding: 15px 20px; border-radius: 16px;
            font-weight: 800; font-size: 15px; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            box-shadow: 0 8px 20px -4px rgba(34,197,94,0.45);
            transition: transform 0.15s, opacity 0.15s;
        }
        .btn-primary:active { transform: scale(0.97); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .btn-secondary {
            width: 100%; padding: 13px 20px; border-radius: 14px;
            font-weight: 600; font-size: 13px; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            background: #f1f5f9; color: #64748b;
            transition: background 0.15s;
            text-decoration: none;
        }
        .btn-secondary:hover { background: #e2e8f0; }
    </style>
</head>
<body class="flex items-center justify-center p-4 min-h-screen">

{{-- SUCCESS OVERLAY --}}
<div class="success-overlay" id="successOverlay">
    <div class="success-box">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none">
                <circle cx="20" cy="20" r="18" stroke="#22c55e" stroke-width="3" fill="#dcfce7"/>
                <path class="check-path" d="M11 20l7 7 11-13" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2 class="text-xl font-black text-gray-800 mb-1">Pembayaran Berhasil!</h2>
        <p class="text-sm text-gray-500 mb-1">Pesanan <span class="font-bold text-green-600">{{ $order->queue_number }}</span></p>
        <p class="text-sm text-gray-400">Mengalihkan ke struk...</p>
    </div>
</div>

{{-- MAIN CARD --}}
<div class="w-full max-w-sm fade-up">
    <div class="pay-card">

        {{-- HEADER --}}
        <div class="px-5 pt-5 pb-4" style="background: linear-gradient(135deg, #1a1a2e, #16213e);">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-xl">💳</div>
                <div>
                    <p class="text-white font-black text-sm">Bayar Online</p>
                    <p class="text-white/60 text-xs mt-0.5">GoPay · OVO · DANA · VA Bank & lainnya</p>
                </div>
            </div>
            <p class="text-white font-black text-2xl">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            <div class="flex items-center gap-2 mt-1.5 flex-wrap text-xs">
                <span class="text-white/50">{{ $order->queue_number }}</span>
                @if($order->table_number)
                    <span class="text-white/30">·</span>
                    <span class="text-white/50">Meja {{ $order->table_number }}</span>
                @endif
                @if($order->customer_name)
                    <span class="text-white/30">·</span>
                    <span class="text-white/50">{{ $order->customer_name }}</span>
                @endif
            </div>
        </div>

        <div class="px-5 py-5 space-y-3">

            {{-- STATUS BADGE --}}
            <div id="statusBadge" class="flex items-center gap-2 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
                <span id="statusIcon" class="pulse">⏳</span>
                <p id="statusText" class="text-xs font-semibold text-blue-700">Menyiapkan pembayaran...</p>
            </div>

            {{-- SIMULATOR PANEL — muncul setelah pilih metode --}}
            <div class="sim-panel" id="simPanel">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-black text-amber-800">🧪 Mode Sandbox — Simulator</p>
                    <span class="sim-method-badge" id="simMethodBadge">⏳ Menunggu...</span>
                </div>

                <p class="text-xs text-amber-700 mb-3 leading-relaxed">
                    Ini adalah mode <strong>testing</strong>. Gunakan simulator Midtrans untuk mensimulasikan pembayaran berhasil.
                </p>

                <ol id="simInstructions" class="space-y-2 mb-4 pl-0 list-none">
                    <li class="step-item"><div class="step-num">1</div><span>Klik <strong>Buka Simulator</strong> di bawah</span></li>
                    <li class="step-item"><div class="step-num">2</div><span>Masukkan <strong>VA Number / Order ID</strong>, klik <strong>Inquire</strong></span></li>
                    <li class="step-item"><div class="step-num">3</div><span>Klik <strong>Pay</strong> untuk simulasi pembayaran berhasil</span></li>
                    <li class="step-item"><div class="step-num">4</div><span>Kembali ke sini, klik <strong>Cek Status Pembayaran</strong></span></li>
                </ol>

                <div class="bg-white/70 rounded-xl px-3 py-2 mb-3 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wide">Order ID Midtrans</p>
                        <p class="text-xs font-black text-amber-900 mt-0.5" id="simOrderId">–</p>
                    </div>
                    <button onclick="copyOrderId()" class="text-xs bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold px-3 py-1.5 rounded-lg transition-colors">
                        📋 Copy
                    </button>
                </div>

                <a id="btnSimulator" href="#" target="_blank" class="btn-simulator" onclick="onSimulatorClick()">
                    🧪 Buka Simulator Midtrans
                </a>

                {{-- AUTO POLLING INDICATOR --}}
                <div id="pollingWrap" style="display:none" class="mt-3">
                    <div class="polling-info">
                        <span><span class="spin-icon">🔄</span> Mengecek status otomatis...</span>
                        <span id="pollingCountdown" class="font-bold">Cek dalam 5 detik</span>
                    </div>
                    <div class="polling-bar-wrap">
                        <div class="polling-bar" id="pollingBar"></div>
                    </div>
                    <p class="text-[10px] text-amber-600 mt-1 text-center">Halaman akan otomatis update setelah pembayaran simulator berhasil</p>
                </div>
            </div>

            {{-- TOMBOL BAYAR — selalu terlihat --}}
            <button id="btnPay" onclick="openSnap()" class="btn-primary">
                💳 Buka Halaman Pembayaran
            </button>

            {{-- RINGKASAN PESANAN --}}
            <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ringkasan Pesanan</p>
                @foreach($order->items as $item)
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">{{ $item->name ?? 'Item' }}
                        <span class="font-semibold text-gray-600">×{{ $item->qty }}</span>
                    </span>
                    <span class="font-semibold text-gray-700">
                        Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
                <div class="border-t border-gray-200 pt-2 mt-1 flex justify-between text-sm font-bold">
                    <span class="text-gray-600">Total</span>
                    <span class="text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- TOMBOL KEMBALI --}}
            <a href="{{ url('/customer/checkout') }}" class="btn-secondary">← Kembali ke Checkout</a>

        </div>

        <div class="px-5 pb-4 flex items-center justify-between text-[10px]">
            <span class="text-gray-400">Diproses oleh <strong class="text-gray-500">Midtrans</strong></span>
            <span class="text-gray-300">Aman & Terpercaya 🔒</span>
        </div>
    </div>
</div>

<script>
const SNAP_TOKEN        = @json($snapToken);
const CONFIRM_URL       = @json(route('customer.order.midtrans.confirm', $order->id));
const RECEIPT_URL       = @json(route('customer.order.midtrans.receipt', $order->id));
const CSRF_TOKEN        = @json(csrf_token());
const MIDTRANS_ORDER_ID = @json($order->midtrans_order_id ?? '');

// ── Simulator helpers ──────────────────────────────────────────────
const SIMULATOR_URLS = {
    // E-wallet
    gopay:        'https://simulator.sandbox.midtrans.com/gopay/ui/index',
    shopeepay:    'https://simulator.sandbox.midtrans.com/shopeepay/index',
    qris:         'https://simulator.sandbox.midtrans.com/qris/index',
    // VA Bank — masing-masing punya halaman sendiri
    bca:          'https://simulator.sandbox.midtrans.com/bca/va/index',
    bni:          'https://simulator.sandbox.midtrans.com/bni/va/index',
    bri:          'https://simulator.sandbox.midtrans.com/bri/va/index',
    permata:      'https://simulator.sandbox.midtrans.com/permata/va/index',
    mandiri:      'https://simulator.sandbox.midtrans.com/mandiri/bill/index',
    bank_transfer:'https://simulator.sandbox.midtrans.com/bca/va/index',
    // Default fallback
    default:      'https://simulator.sandbox.midtrans.com/bca/va/index',
};

function showSimulatorPanel(paymentType, bankCode) {
    const panel  = document.getElementById('simPanel');
    const badge  = document.getElementById('simMethodBadge');
    const elId   = document.getElementById('simOrderId');
    const btnSim = document.getElementById('btnSimulator');
    const instrEl = document.getElementById('simInstructions');

    // Tentukan key untuk URL — prioritaskan bankCode jika ada
    const key = bankCode || paymentType || 'default';

    const labels = {
        gopay: '💚 GoPay', shopeepay: '🟠 ShopeePay',
        qris: '📱 QRIS',
        bca: '🏦 VA BCA', bni: '🏦 VA BNI', bri: '🏦 VA BRI',
        permata: '🏦 VA Permata', mandiri: '🏦 Mandiri Bill',
        bank_transfer: '🏦 VA Bank', credit_card: '💳 Kartu Kredit',
    };
    badge.textContent = labels[key] || labels[paymentType] || '💳 Online';

    elId.textContent = MIDTRANS_ORDER_ID || '(belum tersedia)';
    btnSim.href      = SIMULATOR_URLS[key] || SIMULATOR_URLS[paymentType] || SIMULATOR_URLS.default;

    // Instruksi khusus per metode
    if (instrEl) {
        if (['gopay','shopeepay'].includes(key)) {
            instrEl.innerHTML = `
                <li>Klik <strong>Buka Simulator</strong> di bawah — halaman simulator terbuka di tab baru</li>
                <li>Masukkan <strong>nomor HP</strong>: <code class="bg-amber-200 px-1 rounded font-mono">08123456789</code>, klik <strong>Pay</strong></li>
                <li>Masukkan <strong>PIN</strong>: <code class="bg-amber-200 px-1 rounded font-mono">12345</code>, klik <strong>Confirm</strong></li>
                <li>Kembali ke tab ini — halaman akan <strong>otomatis update</strong> setelah pembayaran terdeteksi</li>`;
        } else if (key === 'qris') {
            instrEl.innerHTML = `
                <li>Klik <strong>Buka Simulator</strong> di bawah — halaman simulator terbuka di tab baru</li>
                <li>Paste <strong>URL gambar QR</strong> dari halaman pembayaran ke kolom yang tersedia</li>
                <li>Klik <strong>Scan QR</strong> lalu klik <strong>Pay</strong></li>
                <li>Kembali ke tab ini — halaman akan <strong>otomatis update</strong> setelah pembayaran terdeteksi</li>`;
        } else if (key === 'mandiri') {
            instrEl.innerHTML = `
                <li>Klik <strong>Buka Simulator</strong> di bawah — halaman simulator terbuka di tab baru</li>
                <li>Masukkan <strong>Biller Code</strong> dan <strong>Bill Key</strong> dari halaman pembayaran</li>
                <li>Klik <strong>Inquire</strong>, lalu klik <strong>Pay</strong></li>
                <li>Kembali ke tab ini — halaman akan <strong>otomatis update</strong> setelah pembayaran terdeteksi</li>`;
        } else {
            // VA Bank (BCA, BNI, BRI, Permata, dll)
            instrEl.innerHTML = `
                <li>Klik <strong>Buka Simulator</strong> di bawah — halaman simulator terbuka di tab baru</li>
                <li>Copy <strong>Order ID</strong> di bawah, paste ke kolom <strong>VA Number</strong> di simulator</li>
                <li>Klik <strong>Inquire</strong> — data transaksi akan muncul</li>
                <li>Klik <strong>Pay</strong> untuk simulasi pembayaran berhasil</li>
                <li>Kembali ke tab ini — halaman akan <strong>otomatis update</strong> setelah pembayaran terdeteksi</li>`;
        }
    }

    panel.classList.add('show');

    // Mulai auto polling begitu panel muncul (belum klik simulator pun sudah poll)
    startAutoPolling();
}

function copyOrderId() {
    const id = document.getElementById('simOrderId').textContent;
    if (!id || id === '–' || id === '(belum tersedia)') return;
    navigator.clipboard.writeText(id).then(() => {
        const btn = event.target;
        const orig = btn.textContent;
        btn.textContent = '✅ Copied!';
        setTimeout(() => { btn.textContent = orig; }, 2000);
    });
}

// ── AUTO POLLING ───────────────────────────────────────────────────
let pollingTimer   = null;
let pollingActive  = false;
const POLL_INTERVAL = 5000; // cek setiap 5 detik

function startAutoPolling() {
    if (pollingActive) return;
    pollingActive = true;

    const wrap = document.getElementById('pollingWrap');
    if (wrap) wrap.style.display = 'block';

    schedulePoll();
}

function stopAutoPolling() {
    pollingActive = false;
    clearTimeout(pollingTimer);
    const wrap = document.getElementById('pollingWrap');
    if (wrap) wrap.style.display = 'none';
}

function schedulePoll() {
    if (!pollingActive) return;

    let elapsed = 0;
    const bar   = document.getElementById('pollingBar');
    const label = document.getElementById('pollingCountdown');
    const step  = 100;

    // Animasi progress bar
    const ticker = setInterval(() => {
        elapsed += step;
        const pct = Math.min((elapsed / POLL_INTERVAL) * 100, 100);
        if (bar)   bar.style.width = pct + '%';
        const remaining = Math.ceil((POLL_INTERVAL - elapsed) / 1000);
        if (label) label.textContent = remaining > 0 ? `Cek dalam ${remaining} detik` : 'Mengecek...';
        if (elapsed >= POLL_INTERVAL) clearInterval(ticker);
    }, step);

    pollingTimer = setTimeout(async () => {
        if (!pollingActive) return;
        try {
            const res  = await fetch(CONFIRM_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();

            if (data.status === 'ok') {
                // BERHASIL — stop polling, tampilkan success overlay & redirect
                stopAutoPolling();
                setStatus('success', '✅', 'Pembayaran berhasil! Mengalihkan...');
                setBtn('Pembayaran Berhasil ✅', true);
                const overlay = document.getElementById('successOverlay');
                if (overlay) overlay.classList.add('show');
                setTimeout(() => { window.location.href = data.redirect || RECEIPT_URL; }, 2000);

            } else if (data.status === 'pending') {
                // Masih pending — jadwalkan polling berikutnya
                schedulePoll();

            } else {
                // Gagal / cancel — stop polling
                stopAutoPolling();
                setStatus('error', '❌', data.message || 'Pembayaran gagal atau dibatalkan.');
                setBtn('Coba Lagi');
            }
        } catch (err) {
            console.error('[poll]', err);
            // Network error — coba lagi
            schedulePoll();
        }
    }, POLL_INTERVAL);
}

function onSimulatorClick() {
    // Mulai auto polling saat user klik tombol simulator
    setTimeout(startAutoPolling, 1000);
}

let snapOpened = false;

// ── Tampilan status ────────────────────────────────────────────────
function setStatus(type, icon, text, pulse) {
    const map = {
        loading: ['bg-blue-50 border-blue-200',   'text-blue-700'],
        waiting: ['bg-amber-50 border-amber-200', 'text-amber-700'],
        success: ['bg-green-50 border-green-200', 'text-green-700'],
        error:   ['bg-red-50 border-red-200',     'text-red-700'],
    };
    const [bg, tc] = map[type] || map.loading;
    document.getElementById('statusBadge').className =
        `flex items-center gap-2 rounded-xl px-4 py-3 border ${bg}`;
    const ico = document.getElementById('statusIcon');
    ico.textContent = icon;
    ico.className = pulse ? 'pulse' : '';
    const st = document.getElementById('statusText');
    st.className = `text-xs font-semibold ${tc}`;
    st.textContent = text;
}

function setBtn(label, disabled) {
    const btn = document.getElementById('btnPay');
    btn.textContent = label ? '💳 ' + label : '💳 Buka Halaman Pembayaran';
    btn.disabled = !!disabled;
}

// ── Verifikasi ke server ───────────────────────────────────────────
async function verifyPayment() {
    setStatus('loading', '🔄', 'Memverifikasi pembayaran...', true);
    setBtn('Memverifikasi...', true);
    try {
        const res  = await fetch(CONFIRM_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        });
        const data = await res.json();

        if (data.status === 'ok') {
            stopAutoPolling();
            setStatus('success', '✅', 'Pembayaran berhasil! Mengalihkan...');
            setBtn('Pembayaran Berhasil ✅', true);
            const overlay = document.getElementById('successOverlay');
            if (overlay) overlay.classList.add('show');
            setTimeout(() => { window.location.href = data.redirect || RECEIPT_URL; }, 2000);

        } else if (data.status === 'pending') {
            setStatus('waiting', '⏳', 'Belum ada pembayaran terdeteksi. Selesaikan di simulator.');
            setBtn('Cek Status Pembayaran');

        } else {
            setStatus('error', '❌', data.message || 'Pembayaran gagal atau dibatalkan.');
            setBtn('Coba Lagi');
        }
    } catch (err) {
        console.error('[verify]', err);
        setStatus('error', '❌', 'Koneksi bermasalah. Hubungi kasir.');
        setBtn('Coba Lagi');
    }
}

// ── Buka Snap popup ───────────────────────────────────────────────
function openSnap() {
    if (!SNAP_TOKEN) {
        setStatus('error', '❌', 'Token tidak tersedia. Hubungi kasir.');
        return;
    }
    if (typeof window.snap === 'undefined') {
        setStatus('error', '⚠️', 'Midtrans belum siap. Tunggu sebentar lalu coba lagi.');
        return;
    }

    snapOpened = true;
    setStatus('loading', '⏳', 'Membuka halaman pembayaran...', true);
    setBtn('Sedang Membuka...', true);

    window.snap.pay(SNAP_TOKEN, {
        onSuccess: function(result) {
            console.log('[snap] success', result);
            // Langsung tampilkan success & redirect
            stopAutoPolling();
            setStatus('success', '✅', 'Pembayaran berhasil! Mengalihkan...');
            setBtn('Pembayaran Berhasil ✅', true);
            document.getElementById('successOverlay').classList.add('show');
            // Verifikasi ke server untuk update status order, lalu redirect
            verifyPayment();
        },
        onPending: function(result) {
            console.log('[snap] pending', result);
            const pType    = result.payment_type || 'default';
            const bankCode = result.va_numbers?.[0]?.bank || result.payment_type || null;
            showSimulatorPanel(pType, bankCode);
            setStatus('waiting', '⏳', 'Menunggu pembayaran. Gunakan simulator di bawah.');
            setBtn('Cek Status Pembayaran');
            // Tetap verifikasi untuk VA bank yang perlu dicek manual
            if (!['gopay','shopeepay','qris'].includes(pType)) {
                verifyPayment();
            }
        },
        onError: function(result) {
            console.log('[snap] error', result);
            setStatus('error', '❌', 'Pembayaran gagal. Silakan coba lagi.');
            setBtn('Coba Lagi');
        },
        onClose: function() {
            console.log('[snap] closed');
            // Snap onClose terpicu saat user klik "Return to merchant's page"
            // Langsung verifikasi ke server — jika sudah dibayar di simulator, akan langsung redirect
            setStatus('loading', '🔄', 'Memverifikasi status pembayaran...', true);
            setBtn('Memverifikasi...', true);
            verifyPayment();
        },
    });
}

// ── AUTO-OPEN: tunggu snap.js siap lalu langsung buka ─────────────
(function autoOpen() {
    let elapsed = 0;
    const timer = setInterval(function() {
        elapsed += 200;
        if (typeof window.snap !== 'undefined') {
            clearInterval(timer);
            openSnap(); // langsung buka popup
        } else if (elapsed >= 6000) {
            clearInterval(timer);
            // snap.js gagal load — tombol tetap aktif, user klik manual
            setStatus('error', '⚠️', 'Gagal memuat Midtrans. Klik tombol di bawah.');
            setBtn('Buka Halaman Pembayaran');
        }
    }, 200);
})();
</script>
</body>
</html>