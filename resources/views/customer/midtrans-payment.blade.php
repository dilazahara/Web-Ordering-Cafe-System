<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pembayaran {{ strtoupper($order->payment_method) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Snap.js dari Midtrans --}}
    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 50%, #ecfdf5 100%); min-height: 100vh; }

        .pay-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(16,185,129,0.18);
            overflow: hidden;
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }

        @keyframes spin { to { transform: rotate(360deg); } }
        .spin-anim { animation: spin 0.8s linear infinite; }

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
        .check-path {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.6s 0.3s ease forwards;
        }

        /* Tombol simulasi */
        .btn-sim {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: 16px;
            padding: 14px 20px;
            color: white;
            font-weight: 800;
            font-size: 14px;
            width: 100%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 20px -4px rgba(34,197,94,0.5);
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn-sim:active { transform: scale(0.97); }
        .btn-sim:disabled { opacity: 0.6; cursor: not-allowed; }

        /* Badge sandbox mode */
        .sandbox-badge {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 1px solid #93c5fd;
            border-radius: 14px;
            padding: 12px 14px;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 min-h-screen">

{{-- SUCCESS OVERLAY --}}
<div class="success-overlay" id="successOverlay">
    <div class="success-box fade-up">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none">
                <circle cx="20" cy="20" r="18" stroke="#22c55e" stroke-width="3" fill="#dcfce7"/>
                <path class="check-path" d="M11 20l7 7 11-13" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2 class="text-xl font-black text-gray-800 mb-1">Pembayaran Berhasil!</h2>
        <p class="text-sm text-gray-500 mb-1">Pesanan <span class="font-bold text-green-600">{{ $order->queue_number }}</span></p>
        <p class="text-sm text-gray-400">Mengalihkan ke halaman pesanan...</p>
    </div>
</div>

{{-- MAIN CARD --}}
<div class="w-full max-w-sm fade-up">
    <div class="pay-card">

        {{-- HEADER --}}
        @php
            $method = strtoupper($order->payment_method);
            $methodIcons = [
                'gopay'     => ['icon' => '💚', 'color' => '#00AED6', 'label' => 'GoPay'],
                'ovo'       => ['icon' => '💜', 'color' => '#4C3494', 'label' => 'OVO'],
                'dana'      => ['icon' => '💙', 'color' => '#118EEA', 'label' => 'DANA'],
                'shopeepay' => ['icon' => '🧡', 'color' => '#EE4D2D', 'label' => 'ShopeePay'],
                'bca'       => ['icon' => '🏦', 'color' => '#003D8F', 'label' => 'BCA Virtual Account'],
                'bni'       => ['icon' => '🏦', 'color' => '#FF6600', 'label' => 'BNI Virtual Account'],
                'bri'       => ['icon' => '🏦', 'color' => '#00529C', 'label' => 'BRI Virtual Account'],
                'mandiri'   => ['icon' => '🏦', 'color' => '#003087', 'label' => 'Mandiri Bill'],
                'permata'   => ['icon' => '🏦', 'color' => '#E31837', 'label' => 'Permata Virtual Account'],
            ];
            $methodInfo = $methodIcons[$order->payment_method] ?? ['icon' => '💳', 'color' => '#6366f1', 'label' => $method];
        @endphp
        <div class="px-5 pt-5 pb-4" style="background: linear-gradient(135deg, {{ $methodInfo['color'] }}, {{ $methodInfo['color'] }}cc);">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-2xl">{{ $methodInfo['icon'] }}</span>
                        <div>
                            <p class="text-white font-black text-sm leading-none">{{ $methodInfo['label'] }}</p>
                            <p class="text-white/70 text-xs">Bayar dengan {{ $methodInfo['label'] }}</p>
                        </div>
                    </div>
                    <p class="text-white font-bold text-xl leading-none mt-2">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </p>
                    <p class="text-white/60 text-xs mt-0.5">
                        Pesanan {{ $order->queue_number }}
                        @if($order->table_number) · Meja {{ $order->table_number }} @endif
                    </p>
                </div>
                <div class="bg-white/20 rounded-xl px-3 py-2 text-center">
                    <p class="text-white text-[10px] font-semibold mb-0.5">Status</p>
                    <p class="text-white font-black text-xs" id="headerStatus">Menunggu</p>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="px-5 py-5 space-y-3">

            {{-- Status badge --}}
            <div id="statusBadge" class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5">
                <span id="statusIcon">⏳</span>
                <p id="statusText" class="text-xs font-semibold text-amber-700">Menunggu pembayaran...</p>
            </div>

            @if(!config('midtrans.is_production'))
            {{-- ══════════════════════════════════════════════
                 SANDBOX MODE: Panduan + Tombol Simulasi
            ══════════════════════════════════════════════ --}}
            <div class="sandbox-badge space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-base">⚡</span>
                    <p class="text-[11px] font-black text-blue-700 uppercase tracking-wider">Mode Sandbox / Testing</p>
                </div>

                <div class="space-y-1.5 text-xs text-blue-800 leading-relaxed">
                    <p><strong>Cara 1 — Via Popup Midtrans:</strong></p>
                    <ol class="list-decimal list-inside space-y-1 text-blue-700 pl-1">
                        <li>Klik <strong>"Lanjutkan Pembayaran"</strong> di bawah</li>
                        <li>Popup Midtrans muncul → pilih metode</li>
                        <li>Isi simulasi (misal: kode OTP <code class="bg-blue-100 px-1 rounded">112233</code>)</li>
                        <li>Klik <strong>"Bayar"</strong> / konfirmasi di popup</li>
                    </ol>
                </div>

                <div class="border-t border-blue-200 pt-2 space-y-1.5 text-xs text-blue-800">
                    <p><strong>Cara 2 — Simulasi Langsung (tanpa popup):</strong></p>
                    <p class="text-blue-600">Tombol hijau di bawah akan langsung menandai pesanan sebagai <strong>LUNAS</strong> dan menampilkan struk — cocok untuk demo atau jika popup tidak muncul.</p>
                </div>
            </div>

            {{-- Tombol Simulasi Sukses --}}
            <button id="btnSimulate" class="btn-sim" onclick="simulateSukses()">
                ✅ Simulasi Pembayaran Sukses
            </button>
            @endif

            {{-- Ringkasan pesanan --}}
            <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ringkasan Pesanan</p>
                @foreach($order->items as $item)
                <div class="flex justify-between text-xs">
                    <span class="text-gray-600">{{ $item->name }} × {{ $item->qty }}</span>
                    <span class="font-semibold text-gray-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
                <div class="border-t border-gray-200 pt-2 mt-2 flex justify-between text-sm font-bold">
                    <span class="text-gray-700">Total</span>
                    <span class="text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Tombol buka Snap --}}
            <button id="btnSnap" onclick="openSnap()"
                class="w-full py-3.5 rounded-2xl font-extrabold text-sm text-white flex items-center justify-center gap-2 transition-all active:scale-95"
                style="background: linear-gradient(135deg, {{ $methodInfo['color'] }}, {{ $methodInfo['color'] }}cc); box-shadow: 0 8px 20px -4px {{ $methodInfo['color'] }}66;">
                {{ $methodInfo['icon'] }} Lanjutkan Pembayaran
            </button>

            <a href="/customer/checkout"
               class="block w-full py-2.5 rounded-2xl font-semibold text-xs text-center text-gray-400 bg-gray-50 hover:bg-gray-100 transition-colors">
                ← Kembali ke Checkout
            </a>
        </div>

        {{-- Footer --}}
        <div class="px-5 pb-4 flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <span class="text-gray-400 text-[10px]">Diproses oleh</span>
                <span class="font-bold text-gray-500 text-[10px]">Midtrans</span>
            </div>
            <span class="text-[10px] text-gray-300">Aman & Terpercaya 🔒</span>
        </div>
    </div>

    <p class="text-center text-[11px] text-slate-400 mt-3 px-4">
        Pembayaran aman diproses melalui <strong>Midtrans</strong>
    </p>
</div>

<script>
const snapToken  = "{{ $snapToken }}";
const confirmUrl = "{{ route('customer.order.midtrans.confirm', $order->id) }}";
const receiptUrl = "{{ route('customer.order.midtrans.receipt', $order->id) }}";
const csrfToken  = "{{ csrf_token() }}";

function setStatus(type, icon, text) {
    const badge   = document.getElementById('statusBadge');
    const classes = {
        waiting: 'bg-amber-50 border-amber-200',
        success: 'bg-green-50 border-green-200',
        error:   'bg-red-50 border-red-200',
        loading: 'bg-blue-50 border-blue-200',
    };
    const textClass = {
        waiting: 'text-amber-700',
        success: 'text-green-700',
        error:   'text-red-700',
        loading: 'text-blue-700',
    };
    badge.className = 'flex items-center gap-2 rounded-xl px-4 py-2.5 border ' + (classes[type] || classes.loading);
    document.getElementById('statusIcon').textContent = icon;
    const st = document.getElementById('statusText');
    st.className = 'text-xs font-semibold ' + (textClass[type] || textClass.loading);
    st.textContent = text;
    document.getElementById('headerStatus').textContent = text.split('.')[0];
}

async function onPaymentSuccess() {
    setStatus('loading', '🔄', 'Memproses pesanan...');
    try {
        const res  = await fetch(confirmUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept':       'application/json',
            },
        });
        const data = await res.json();
        if (data.status === 'ok') {
            setStatus('success', '✅', 'Pembayaran berhasil!');
            document.getElementById('successOverlay').classList.add('show');
            setTimeout(() => { window.location.href = data.redirect || receiptUrl; }, 2000);
        } else {
            throw new Error('Unexpected response');
        }
    } catch (e) {
        setStatus('error', '❌', 'Gagal konfirmasi, hubungi kasir.');
    }
}

// ── Simulasi sukses (sandbox only) ────────────────────────────────
async function simulateSukses() {
    const btn = document.getElementById('btnSimulate');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full" style="animation:spin 0.8s linear infinite"></span> Memproses simulasi...';
    }
    setStatus('loading', '🔄', 'Simulasi pembayaran...');
    await onPaymentSuccess();
}

// ── Buka Snap popup ───────────────────────────────────────────────
function openSnap() {
    if (!snapToken || snapToken === '') {
        setStatus('error', '❌', 'Token tidak tersedia. Gunakan tombol Simulasi.');
        return;
    }
    const btn = document.getElementById('btnSnap');
    btn.disabled = true;
    btn.innerHTML = '<span style="display:inline-block;width:16px;height:16px;border:2px solid white;border-top-color:transparent;border-radius:50%;animation:spin 0.8s linear infinite;margin-right:8px"></span> Membuka halaman pembayaran...';

    window.snap.pay(snapToken, {
        onSuccess: function(result) {
            console.log('Snap success:', result);
            onPaymentSuccess();
        },
        onPending: function(result) {
            console.log('Snap pending:', result);
            setStatus('loading', '⏳', 'Pembayaran pending...');
            btn.disabled = false;
            btn.innerHTML = '{{ $methodInfo['icon'] }} Lanjutkan Pembayaran';
        },
        onError: function(result) {
            console.log('Snap error:', result);
            setStatus('error', '❌', 'Pembayaran gagal. Coba lagi atau gunakan Simulasi.');
            btn.disabled = false;
            btn.innerHTML = '{{ $methodInfo['icon'] }} Coba Lagi';
        },
        onClose: function() {
            setStatus('waiting', '⏳', 'Popup ditutup. Klik tombol untuk melanjutkan.');
            btn.disabled = false;
            btn.innerHTML = '{{ $methodInfo['icon'] }} Lanjutkan Pembayaran';
        },
    });
}
</script>
</body>
</html>