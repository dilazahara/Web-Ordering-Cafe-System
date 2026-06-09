<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pembayaran QRIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #eef2ff 0%, #ffffff 50%, #ede9fe 100%); min-height: 100vh; }

        /* ── QRIS Card ── */
        .qris-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(99,102,241,0.18);
            overflow: hidden;
        }

        /* Header merah-pink seperti QRIS bank asli */
        .qris-header {
            background: linear-gradient(135deg, #e11d48, #be123c);
            padding: 18px 20px 14px;
            position: relative;
        }

        /* QR wrapper */
        #qrcode canvas, #qrcode img { display: block; margin: 0 auto; }

        /* Timer */
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
            70%  { box-shadow: 0 0 0 10px rgba(239,68,68,0); }
            100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
        }
        .timer-pulse { animation: pulse-ring 1.5s infinite; }

        /* Spinner */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spin-anim { animation: spin 0.8s linear infinite; }

        /* Fade in */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }

        /* Success overlay */
        .success-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
            opacity: 0; pointer-events: none;
            transition: opacity 0.3s;
        }
        .success-overlay.show { opacity: 1; pointer-events: auto; }
        .success-box {
            background: #fff;
            border-radius: 24px;
            padding: 36px 28px;
            text-align: center;
            max-width: 320px;
            width: 90%;
            transform: scale(0.85);
            transition: transform 0.3s;
        }
        .success-overlay.show .success-box { transform: scale(1); }

        @keyframes checkmark {
            from { stroke-dashoffset: 100; }
            to   { stroke-dashoffset: 0; }
        }
        .check-path { stroke-dasharray: 100; stroke-dashoffset: 100; animation: checkmark 0.6s 0.3s ease forwards; }
    </style>
</head>
<body class="flex items-center justify-center p-4 min-h-screen">

{{-- ── SUCCESS OVERLAY ── --}}
<div class="success-overlay" id="successOverlay">
    <div class="success-box fade-up">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none">
                <circle cx="20" cy="20" r="18" stroke="#22c55e" stroke-width="3" fill="#dcfce7"/>
                <path class="check-path" d="M11 20l7 7 11-13" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2 class="text-xl font-black text-gray-800 mb-1">Pembayaran Berhasil!</h2>
        <p class="text-sm text-gray-500 mb-1">Pesanan <span class="font-bold text-indigo-600">{{ $order->queue_number }}</span></p>
        <p class="text-sm text-gray-400">Mengalihkan ke halaman pesanan...</p>
    </div>
</div>

{{-- ── MAIN CARD ── --}}
<div class="w-full max-w-sm fade-up">
    <div class="qris-card">

        {{-- HEADER (mirip tampilan QRIS resmi) --}}
        <div class="qris-header">
            <div class="flex items-center justify-between">
                <div>
                    {{-- Logo QRIS --}}
                    <div class="flex items-center gap-2 mb-1">
                        <div class="bg-white rounded px-2 py-0.5">
                            <span class="text-red-600 font-black text-xs tracking-widest">QRIS</span>
                        </div>
                        <span class="text-white/70 text-xs">Bayar dengan QRIS</span>
                    </div>
                    <p class="text-white font-bold text-lg leading-none">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </p>
                    <p class="text-white/60 text-xs mt-0.5">Pesanan {{ $order->queue_number }}
                        @if($order->table_number)· Meja {{ $order->table_number }}@endif
                    </p>
                </div>
                <div class="text-right">
                    {{-- Timer --}}
                    <div class="bg-white/20 rounded-xl px-3 py-2 text-center timer-pulse" id="timerBox">
                        <p class="text-white text-[10px] font-semibold mb-0.5">Batas waktu</p>
                        <p class="text-white font-black text-base tabular-nums" id="timerDisplay">05:00</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- QR CODE AREA --}}
        <div class="bg-gray-50 px-6 py-5 text-center border-b border-gray-100">
            {{-- Merchant name badge --}}
            <div class="flex items-center justify-center gap-1.5 mb-4">
                <div class="w-5 h-5 bg-red-600 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <span class="text-sm font-bold text-gray-700">Cafe Tugas Akhir</span>
                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full">✓ Terverifikasi</span>
            </div>

            {{-- QR Code --}}
            <div class="relative inline-block">
                <div class="bg-white p-3 rounded-2xl shadow-md border border-gray-200 inline-block">
                    <div id="qrcode"></div>
                </div>
                {{-- Corner decorations (mirip QRIS asli) --}}
                <div class="absolute top-1 left-1 w-6 h-6 border-t-4 border-l-4 border-red-500 rounded-tl-lg"></div>
                <div class="absolute top-1 right-1 w-6 h-6 border-t-4 border-r-4 border-red-500 rounded-tr-lg"></div>
                <div class="absolute bottom-1 left-1 w-6 h-6 border-b-4 border-l-4 border-red-500 rounded-bl-lg"></div>
                <div class="absolute bottom-1 right-1 w-6 h-6 border-b-4 border-r-4 border-red-500 rounded-br-lg"></div>
            </div>

            <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                Scan QR di atas menggunakan aplikasi<br>
                <span class="font-semibold text-gray-600">GoPay · OVO · Dana · ShopeePay · M-Banking</span>
            </p>

            {{-- NMID / merchant ID (realistis) --}}
            <p class="text-[10px] text-gray-300 mt-2 font-mono">
                NMID: ID{{ str_pad($order->id, 10, '0', STR_PAD_LEFT) }}{{ strtoupper(substr(md5($order->id), 0, 6)) }}
            </p>
        </div>

        {{-- INFO & AKSI --}}
        <div class="px-5 py-4 space-y-3">

            {{-- Status badge --}}
            <div id="statusBadge" class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5">
                <span id="statusIcon" class="text-base">⏳</span>
                <p id="statusText" class="text-xs font-semibold text-amber-700">Menunggu pembayaran...</p>
            </div>

            {{-- Cara bayar ringkas --}}
            <div class="bg-gray-50 rounded-xl p-3 space-y-1.5">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cara Pembayaran</p>
                @foreach([
                    ['📱', 'Buka aplikasi dompet digital / m-banking kamu'],
                    ['📷', 'Pilih "Bayar / Scan QR" lalu arahkan ke QR di atas'],
                    ['✅', 'Konfirmasi nominal & selesaikan pembayaran'],
                ] as [$icon, $text])
                <div class="flex items-start gap-2">
                    <span class="text-sm leading-tight">{{ $icon }}</span>
                    <p class="text-xs text-gray-600 leading-tight">{{ $text }}</p>
                </div>
                @endforeach
            </div>

            {{-- TOMBOL SIMULASI (untuk demo sidang) --}}
            <button id="btnSimulate" onclick="simulatePayment()"
                class="w-full py-3.5 rounded-2xl font-extrabold text-sm text-white flex items-center justify-center gap-2 transition-all active:scale-95"
                style="background: linear-gradient(135deg, #22c55e, #16a34a); box-shadow: 0 8px 20px -4px rgba(34,197,94,0.45);">
                ✅ Simulasi Pembayaran Berhasil
            </button>

            {{-- Tombol kembali dihapus: pesanan sudah dibuat --}}

        </div>

        {{-- Footer logo QRIS --}}
        <div class="px-5 pb-4 flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <div class="bg-red-600 rounded px-1.5 py-0.5">
                    <span class="text-white font-black text-[10px] tracking-widest">QRIS</span>
                </div>
                <span class="text-[10px] text-gray-400">by Bank Indonesia</span>
            </div>
            <span class="text-[10px] text-gray-300">Aman & Terpercaya 🔒</span>
        </div>

    </div>

    <p class="text-center text-[11px] text-slate-400 mt-3 px-4">
        Pembayaran aman diproses melalui jaringan <strong>QRIS</strong> Bank Indonesia
    </p>
</div>

<script>
// ── Data dari controller ──
const qrisString  = "{{ $qrisString }}";
const confirmUrl  = "{{ route('customer.order.qris.confirm', $order->id) }}";
const successUrl  = "{{ route('customer.order.success', $order->id) }}";
const csrfToken   = "{{ csrf_token() }}";

// ── Generate QR Code ──
new QRCode(document.getElementById('qrcode'), {
    text:           qrisString,
    width:          200,
    height:         200,
    colorDark:      '#000000',
    colorLight:     '#ffffff',
    correctLevel:   QRCode.CorrectLevel.M,
});

// ── Timer 5 menit ──
let seconds = 300;
const timerDisplay = document.getElementById('timerDisplay');
const timerBox     = document.getElementById('timerBox');

const timerInterval = setInterval(() => {
    seconds--;
    if (seconds <= 0) {
        clearInterval(timerInterval);
        timerDisplay.textContent = '00:00';
        timerBox.style.background = 'rgba(239,68,68,0.3)';
        setStatus('error', '❌', 'Waktu pembayaran habis. Silakan buat pesanan baru.');
        document.getElementById('btnSimulate').disabled = true;
        document.getElementById('btnSimulate').style.opacity = '0.5';
        return;
    }
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    timerDisplay.textContent = m + ':' + s;

    // Warna timer merah saat < 1 menit
    if (seconds < 60) {
        timerDisplay.style.color = '#fca5a5';
    }
}, 1000);

// ── Set Status Badge ──
function setStatus(type, icon, text) {
    const badge = document.getElementById('statusBadge');
    const map = {
        waiting: 'bg-amber-50 border-amber-200',
        success: 'bg-green-50 border-green-200',
        error:   'bg-red-50 border-red-200',
        loading: 'bg-blue-50 border-blue-200',
    };
    const textMap = {
        waiting: 'text-amber-700',
        success: 'text-green-700',
        error:   'text-red-700',
        loading: 'text-blue-700',
    };
    badge.className = 'flex items-center gap-2 rounded-xl px-4 py-2.5 border ' + (map[type] || map.loading);
    document.getElementById('statusIcon').textContent  = icon;
    document.getElementById('statusText').className   = 'text-xs font-semibold ' + (textMap[type] || textMap.loading);
    document.getElementById('statusText').textContent = text;
}

// ── Simulasi Bayar ──
async function simulatePayment() {
    const btn = document.getElementById('btnSimulate');
    btn.disabled = true;
    btn.innerHTML = '<span class="spin-anim inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span> Memproses...';

    setStatus('loading', '🔄', 'Memproses pembayaran...');

    try {
        const res = await fetch(confirmUrl, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  csrfToken,
                'Accept':        'application/json',
            },
        });
        const data = await res.json();

        if (data.status === 'ok') {
            clearInterval(timerInterval);
            setStatus('success', '✅', 'Pembayaran berhasil!');

            // Tampilkan success overlay
            document.getElementById('successOverlay').classList.add('show');

            // Redirect ke halaman sukses
            setTimeout(() => {
                window.location.href = data.redirect || successUrl;
            }, 2000);
        } else {
            throw new Error('Unexpected response');
        }
    } catch (e) {
        setStatus('error', '❌', 'Gagal konfirmasi. Coba lagi.');
        btn.disabled = false;
        btn.innerHTML = '✅ Simulasi Pembayaran Berhasil';
    }
}

// ── Blokir gesture back / swipe-right ────────────────────────────
(function blockAllBack() {
    for (var i = 0; i < 50; i++) { history.pushState({ blocked: true }, '', location.href); }
    window.addEventListener('popstate', function () {
        for (var i = 0; i < 50; i++) { history.pushState({ blocked: true }, '', location.href); }
    });
    var _tsx = 0;
    document.addEventListener('touchstart', function(e){ _tsx = e.touches[0].clientX; }, { passive: true });
    document.addEventListener('touchmove', function(e){ if (_tsx < 30) e.preventDefault(); }, { passive: false });
})();
</script>
</body>
</html>