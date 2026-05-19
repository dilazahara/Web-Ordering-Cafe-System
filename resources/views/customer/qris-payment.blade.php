<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Bayar dengan QRIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #eef2ff 0%, #ffffff 50%, #ede9fe 100%); min-height: 100vh; }

        .glass-card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.7);
        }

        @keyframes fadeUp {
            from { opacity:0; transform: translateY(20px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.55s ease forwards; }

        @keyframes scanLine {
            0%   { top: 8px; }
            50%  { top: calc(100% - 8px); }
            100% { top: 8px; }
        }
        .scan-line {
            animation: scanLine 2.5s ease-in-out infinite;
            position: absolute;
            left: 8px; right: 8px; height: 2px;
            background: linear-gradient(90deg, transparent, #6366f1, transparent);
            border-radius: 999px; z-index: 10;
        }

        @keyframes pulseRing {
            0%   { box-shadow: 0 0 0 0 rgba(99,102,241,0.4); }
            70%  { box-shadow: 0 0 0 14px rgba(99,102,241,0); }
            100% { box-shadow: 0 0 0 0 rgba(99,102,241,0); }
        }
        .qr-wrapper { animation: pulseRing 2.5s infinite; border-radius: 24px; }

        /* Confirm button */
        .btn-confirm {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            box-shadow: 0 10px 20px -4px rgba(99,102,241,0.4);
            transition: all 0.25s;
        }
        .btn-confirm:hover  { filter: brightness(1.07); transform: translateY(-1px); }
        .btn-confirm:active { transform: scale(0.97); box-shadow: none; }
        .btn-confirm:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .btn-cancel { background:#f1f5f9; color:#64748b; transition: all 0.2s; }
        .btn-cancel:hover { background: #e2e8f0; }
        .btn-cancel:active { transform: scale(0.97); }

        /* Countdown bar */
        @keyframes shrink {
            from { width: 100%; }
            to   { width: 0%; }
        }

        /* Spinner */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner {
            width: 18px; height: 18px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: inline-block;
        }
    </style>
</head>
<body class="flex items-center justify-center p-5 min-h-screen">

<div class="w-full max-w-sm fade-up">

    <div class="glass-card rounded-[32px] shadow-2xl overflow-hidden">

        {{-- HEADER --}}
        <div class="relative bg-gradient-to-r from-indigo-500 to-violet-600 px-8 pt-8 pb-14 text-center">
            <div class="absolute top-0 left-0 w-36 h-36 bg-white/10 rounded-full -translate-x-16 -translate-y-16"></div>
            <div class="absolute bottom-0 right-0 w-28 h-28 bg-white/10 rounded-full translate-x-10 translate-y-10"></div>
            <div class="relative z-10">
                <span class="text-4xl">📱</span>
                <h1 class="mt-3 text-2xl font-extrabold text-white tracking-tight">Bayar dengan QRIS</h1>
                <p class="mt-1 text-indigo-100 text-sm">Scan barcode di bawah untuk membayar</p>
                <div class="mt-4 inline-flex items-center gap-2 bg-white/20 px-4 py-1.5 rounded-full text-white text-xs font-bold">
                    <span>Pesanan {{ $order->queue_number }}</span>
                    <span>·</span>
                    <span>Meja {{ $order->table_number ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="px-6 pb-7 -mt-8 relative z-20">

            {{-- TOTAL --}}
            <div class="bg-white rounded-2xl shadow-md border border-indigo-50 p-4 mb-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pembayaran</p>
                    <p class="text-2xl font-black text-indigo-600 mt-1">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-2xl">💳</div>
            </div>

            {{-- QR CODE --}}
            <div class="qr-wrapper bg-white p-4 rounded-3xl shadow-lg border-2 border-indigo-100 relative">
                <div class="relative" style="line-height:0;">
                    <div class="scan-line"></div>

                    @if(isset($qrisImageUrl) && $qrisImageUrl)
                        <img src="{{ $qrisImageUrl }}"
                             alt="QRIS Barcode"
                             class="w-full rounded-2xl"
                             style="image-rendering: pixelated;">
                    @else
                        <div class="w-full aspect-square bg-gradient-to-br from-slate-50 to-indigo-50 rounded-2xl flex flex-col items-center justify-center gap-3 border-2 border-dashed border-indigo-200">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.5">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <path d="M14 14h.01M14 17h.01M17 14h.01M20 14h.01M17 17h3M20 20h.01M17 20h.01"/>
                            </svg>
                            <p class="text-xs text-indigo-400 font-semibold text-center px-4 leading-relaxed">
                                QRIS belum dikonfigurasi.<br>Upload gambar QR di menu admin → Pembayaran.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Corner decorators --}}
                <div class="absolute top-3 left-3 w-6 h-6 border-t-4 border-l-4 border-indigo-500 rounded-tl-lg pointer-events-none"></div>
                <div class="absolute top-3 right-3 w-6 h-6 border-t-4 border-r-4 border-indigo-500 rounded-tr-lg pointer-events-none"></div>
                <div class="absolute bottom-3 left-3 w-6 h-6 border-b-4 border-l-4 border-indigo-500 rounded-bl-lg pointer-events-none"></div>
                <div class="absolute bottom-3 right-3 w-6 h-6 border-b-4 border-r-4 border-indigo-500 rounded-br-lg pointer-events-none"></div>
            </div>

            {{-- INSTRUKSI --}}
            <div class="mt-4 bg-indigo-50 rounded-2xl p-4 space-y-2">
                <p class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2">Cara Pembayaran</p>
                @foreach([
                    ['1', 'Buka aplikasi dompet digital atau mobile banking kamu'],
                    ['2', 'Pilih fitur Scan QR / QRIS'],
                    ['3', 'Arahkan kamera ke barcode di atas'],
                    ['4', 'Masukkan nominal & selesaikan pembayaran'],
                    ['5', 'Klik tombol <strong>Sudah Bayar</strong> di bawah'],
                ] as [$num, $text])
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded-full bg-indigo-500 text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 mt-0.5">{{ $num }}</div>
                    <p class="text-xs text-indigo-700 leading-relaxed">{!! $text !!}</p>
                </div>
                @endforeach
            </div>

            {{-- STATUS --}}
            <div id="statusBadge" class="mt-4 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-amber-50 border border-amber-100">
                <span class="text-amber-500 text-base" id="statusIcon">⏳</span>
                <p class="text-xs font-bold text-amber-700" id="statusText">Menunggu pembayaran QRIS...</p>
            </div>

            {{-- TOMBOL KONFIRMASI --}}
            <form action="{{ route('customer.order.success', $order->id) }}" method="GET" id="confirmForm" class="mt-4">
                <button type="submit" id="btnConfirm"
                        class="btn-confirm w-full text-white py-4 rounded-2xl font-extrabold text-base flex items-center justify-center gap-2">
                    ✅ Sudah Bayar — Konfirmasi
                </button>
            </form>

            {{-- KEMBALI --}}
            <a href="/customer/checkout"
               class="btn-cancel mt-3 w-full py-3 rounded-2xl font-bold text-sm text-center block text-center">
                ← Kembali ke Checkout
            </a>

        </div>
    </div>

    <p class="text-center text-xs text-slate-400 mt-4 px-4 leading-relaxed">
        Tekan <strong>Sudah Bayar</strong> hanya setelah pembayaran QRIS berhasil dikonfirmasi di aplikasimu.
    </p>

</div>

<script>
// ═══ TOAST ═══
(function(){
    const el = document.createElement('div');
    el.id = 'toastContainer';
    el.style.cssText = 'position:fixed;top:16px;left:50%;transform:translateX(-50%);z-index:99999;display:flex;flex-direction:column;gap:8px;align-items:center;pointer-events:none;width:max-content;max-width:calc(100vw - 32px);';
    document.body.appendChild(el);
})();
function showToast(msg, type='success', duration=2200){
    const c=document.getElementById('toastContainer');
    const colors={success:'background:#22c55e;color:white;',info:'background:#1e293b;color:white;',warning:'background:#f59e0b;color:white;',error:'background:#ef4444;color:white;'};
    const icons={success:'✅',info:'ℹ️',warning:'⚠️',error:'❌'};
    const t=document.createElement('div');
    t.style.cssText=`pointer-events:auto;display:flex;align-items:center;gap:8px;padding:10px 18px;border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,0.15);font-size:13px;font-weight:600;white-space:nowrap;opacity:0;transform:translateY(-10px) scale(0.95);transition:all 0.25s ease;${colors[type]||colors.info}`;
    t.innerHTML=`<span>${icons[type]||'📢'}</span><span>${msg}</span>`;
    c.appendChild(t);
    requestAnimationFrame(()=>{t.style.opacity='1';t.style.transform='translateY(0) scale(1)';});
    setTimeout(()=>{t.style.opacity='0';t.style.transform='translateY(-10px) scale(0.95)';setTimeout(()=>t.remove(),260);},duration);
}

// Klik konfirmasi → tampilkan loading state
document.getElementById('confirmForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnConfirm');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner"></span> Mengonfirmasi...`;

    // Update status badge
    document.getElementById('statusIcon').textContent = '✅';
    document.getElementById('statusText').textContent = 'Pembayaran dikonfirmasi!';
    document.getElementById('statusBadge').className =
        'mt-4 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-emerald-50 border border-emerald-100';
    document.getElementById('statusText').className = 'text-xs font-bold text-emerald-700';

    showToast('Pembayaran QRIS dikonfirmasi! 🎉', 'success', 3000);
    // Bersihkan cart
    localStorage.removeItem('cart');
    localStorage.removeItem('checkoutCart');
});
</script>
</body>
</html>