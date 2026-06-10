<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Struk Pembayaran – {{ $order->queue_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 50%, #ecfdf5 100%); min-height: 100vh; }
        .receipt-card { background: #fff; border-radius: 28px; box-shadow: 0 24px 64px rgba(16,185,129,0.15), 0 4px 16px rgba(0,0,0,0.06); overflow: hidden; }
        .divider-dashed { border: none; border-top: 2px dashed #e5e7eb; margin: 0; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .bounce { animation: bounce 1.5s infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spin { display:inline-block; animation:spin 0.8s linear infinite; }
        .btn-action { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px 20px; border-radius: 16px; font-weight: 800; font-size: 14px; border: none; cursor: pointer; transition: transform 0.15s; text-decoration: none; }
        .btn-action:active { transform: scale(0.97); }
        .btn-print { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; box-shadow: 0 8px 20px -4px rgba(99,102,241,0.45); }
        .btn-home  { background: linear-gradient(135deg, #10b981, #059669); color: white; box-shadow: 0 8px 20px -4px rgba(16,185,129,0.45); }
        /* ── WAITING POLLING BAR ── */
        .poll-bar-wrap { height:3px; border-radius:99px; background:#fde68a; overflow:hidden; margin-top:8px; }
        .poll-bar { height:100%; border-radius:99px; background:#f59e0b; width:0%; transition:width 0.1s linear; }
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .receipt-card { box-shadow: none !important; border-radius: 0 !important; border: 1px solid #e5e7eb; }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 min-h-screen">

@php
    $methodIcons = [
        'gopay'       => ['icon' => '💚', 'color' => '#00AED6', 'label' => 'GoPay'],
        'ovo'         => ['icon' => '💜', 'color' => '#4C3494', 'label' => 'OVO'],
        'dana'        => ['icon' => '💙', 'color' => '#118EEA', 'label' => 'DANA'],
        'shopeepay'   => ['icon' => '🧡', 'color' => '#EE4D2D', 'label' => 'ShopeePay'],
        'bca'         => ['icon' => '🏦', 'color' => '#003D8F', 'label' => 'BCA VA'],
        'bni'         => ['icon' => '🏦', 'color' => '#FF6600', 'label' => 'BNI VA'],
        'bri'         => ['icon' => '🏦', 'color' => '#00529C', 'label' => 'BRI VA'],
        'mandiri'     => ['icon' => '🏦', 'color' => '#003087', 'label' => 'Mandiri Bill'],
        'permata'     => ['icon' => '🏦', 'color' => '#E31837', 'label' => 'Permata VA'],
        'credit_card' => ['icon' => '💳', 'color' => '#6366f1', 'label' => 'Kartu Kredit'],
        'midtrans'    => ['icon' => '💳', 'color' => '#6366f1', 'label' => 'Midtrans'],
        'qris'        => ['icon' => '📱', 'color' => '#dc2626', 'label' => 'QRIS'],
    ];

    $methodInfo   = $methodIcons[$order->payment_method] ?? ['icon' => '💳', 'color' => '#6366f1', 'label' => strtoupper($order->payment_method)];
    $subtotal     = $order->items->sum(fn($i) => $i->price * $i->qty);
    $biayaLayanan = max(0, $order->total - $subtotal);

    $isPaid      = in_array($order->status, ['process', 'done', 'delivered', 'completed']);
    $isWaiting   = $order->status === 'waiting_payment';
    $isCancelled = $order->status === 'cancelled';

    $headerColor = $methodInfo['color'];
    if ($isWaiting)   $headerColor = '#d97706';
    if ($isCancelled) $headerColor = '#dc2626';
@endphp

<div class="w-full max-w-sm fade-up">
    <div class="receipt-card">

        {{-- HEADER --}}
        <div class="px-5 pt-7 pb-5 text-center" style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $headerColor }}cc);">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 bounce">
                @if($isPaid)
                    <svg class="w-9 h-9" viewBox="0 0 40 40" fill="none">
                        <circle cx="20" cy="20" r="18" stroke="white" stroke-width="2.5" fill="rgba(255,255,255,0.2)"/>
                        <path d="M11 20l7 7 11-13" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @elseif($isWaiting)
                    <span class="text-3xl text-white">⏳</span>
                @else
                    <span class="text-3xl text-white">❌</span>
                @endif
            </div>
            <h1 class="text-white font-black text-xl">
                @if($isPaid) Pembayaran Berhasil!
                @elseif($isWaiting) Menunggu Verifikasi
                @else Transaksi Gagal
                @endif
            </h1>
            <p class="text-white/70 text-xs mt-1">via {{ $methodInfo['icon'] }} {{ $methodInfo['label'] }}</p>
        </div>

        {{-- NOMOR ANTRIAN --}}
        <div class="bg-slate-50 px-5 py-4 text-center">
            <p class="text-xs text-gray-500 mb-1">Nomor Antrian</p>
            <p class="text-4xl font-black text-blue-600 tracking-widest">{{ $order->queue_number }}</p>
            @if($order->customer_name)
                <p class="text-xs text-gray-600 mt-1 font-bold">{{ $order->customer_name }}</p>
            @endif
            @if($order->table_number)
                <p class="text-xs text-gray-400 mt-0.5">Meja Nomor {{ $order->table_number }}</p>
            @endif
        </div>

        <hr class="divider-dashed mx-5">

        {{-- INFO RESTORAN --}}
        <div class="px-5 pt-4 pb-2 text-center">
            <p class="font-black text-gray-800 text-base">Cafe Tugas Akhir</p>
            <p class="text-xs text-gray-400">Batam, Kepulauan Riau</p>
            <p class="text-[10px] text-gray-400 mt-1">
                {{ now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
            </p>
        </div>

        <hr class="divider-dashed mx-5">

        {{-- DAFTAR MENU --}}
        <div class="px-5 py-4 space-y-2">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Daftar Menu</p>
            @foreach($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">
                        {{ $item->menu->nama ?? $item->name }}
                        <span class="text-gray-400 font-semibold">×{{ $item->qty }}</span>
                    </span>
                    <span class="font-bold text-gray-700">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                </div>
            @endforeach

            <div class="border-t border-gray-100 my-2 pt-2 space-y-1">
                <div class="flex justify-between text-xs text-gray-400">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-400">
                    <span>Biaya Layanan</span>
                    <span>Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                </div>
                <div class="mt-1 flex justify-between items-center">
                    <span class="font-bold text-gray-700 text-sm">Total Dibayar</span>
                    <span class="font-black text-xl text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-2 space-y-1">
                <div class="flex justify-between items-center text-xs text-gray-400">
                    <span>Status Keuangan</span>
                    @if($isPaid)
                        <span class="font-bold text-green-600">✅ LUNAS</span>
                    @elseif($isWaiting)
                        <span class="font-bold text-amber-500">⏳ PENDING</span>
                    @else
                        <span class="font-bold text-red-500">❌ BATAL</span>
                    @endif
                </div>
                <div class="flex justify-between items-center text-xs text-gray-400">
                    <span>ID Transaksi</span>
                    <span class="font-mono text-gray-500">#{{ $order->id }}</span>
                </div>
            </div>
        </div>

        <hr class="divider-dashed mx-5">

        {{-- STATUS SECTION --}}
        <div class="px-5 py-4">
            @if($isPaid)
                <div class="bg-green-50 rounded-xl p-3 text-center">
                    <p class="text-sm font-bold text-green-700">🔥 Pesanan telah dikirim ke dapur!</p>
                    <p class="text-xs text-green-600 mt-0.5">Pesanan sedang diproses juru masak. Mohon menunggu ya.</p>
                </div>

            @elseif($isWaiting)
                {{-- ✅ FIX: Tampilkan info menunggu + polling JS smart (bukan reload setiap 3 detik) --}}
                <div class="bg-amber-50 rounded-xl p-3 text-center" id="waitingBox">
                    <p class="text-sm font-bold text-amber-700">
                        <span class="spin">🔄</span> Menunggu konfirmasi pembayaran...
                    </p>
                    <p class="text-xs text-amber-600 mt-0.5" id="waitingHint">Halaman otomatis memperbarui status.</p>
                    <div class="poll-bar-wrap mt-2">
                        <div class="poll-bar" id="pollBar"></div>
                    </div>
                </div>
                <button onclick="cekSekarang()" id="btnCekSekarang"
                        class="mt-2 w-full py-3 rounded-xl bg-amber-100 text-amber-800 font-bold text-sm border border-amber-200 cursor-pointer"
                        style="border:none;">
                    🔍 Cek Status Sekarang
                </button>

            @else
                <div class="bg-red-50 rounded-xl p-3 text-center">
                    <p class="text-sm font-bold text-red-700">Transaksi Gagal</p>
                    <p class="text-xs text-red-600 mt-0.5">Silakan lakukan pemesanan ulang atau hubungi kasir.</p>
                </div>
            @endif
        </div>

        {{-- ACTIONS --}}
        <div class="px-5 pb-6 space-y-2 no-print">
            @if($isPaid)
                <button onclick="bukaModal()" class="btn-action btn-print">
                    🧾 Lihat Struk
                </button>
            @endif
            <a href="{{ route('customer.home') }}" class="btn-action btn-home">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg> Kembali ke Menu
            </a>
        </div>

    </div>
</div>

@if($isWaiting)
{{-- ✅ FIX: Ganti setTimeout reload() dengan polling JSON ke endpoint confirm --}}
{{-- Lebih hemat battery & data dibanding reload halaman penuh setiap 3 detik --}}
<script>
const CONFIRM_URL = @json(route('customer.order.midtrans.confirm', $order->id));
const RECEIPT_URL = @json(route('customer.order.midtrans.receipt', $order->id));
const CSRF_TOKEN  = @json(csrf_token());

// Polling setiap 4 detik, maksimal 15 menit (225 kali)
const POLL_INTERVAL    = 4000;
const MAX_POLL_COUNT   = 225;
let   pollCount        = 0;
let   pollTimer        = null;
let   isChecking       = false;

function animatePollBar(durationMs) {
    const bar = document.getElementById('pollBar');
    if (!bar) return;
    bar.style.width = '0%';
    bar.style.transition = 'none';
    setTimeout(() => {
        bar.style.transition = `width ${durationMs}ms linear`;
        bar.style.width = '100%';
    }, 50);
}

async function cekStatus() {
    if (isChecking) return;
    isChecking = true;

    try {
        const res  = await fetch(CONFIRM_URL, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  CSRF_TOKEN,
                'Accept':        'application/json',
            }
        });
        const data = await res.json();

        if (data.status === 'ok') {
            // Pembayaran berhasil — reload halaman ini untuk tampilkan struk lunas
            clearTimeout(pollTimer);
            window.location.reload();
            return;

        } else if (data.status === 'cancelled') {
            clearTimeout(pollTimer);
            window.location.reload();
            return;
        }
        // 'pending' → lanjut polling
    } catch(e) {
        // Abaikan error jaringan sementara, tetap lanjut polling
    } finally {
        isChecking = false;
    }

    jadwalkanPoll();
}

function jadwalkanPoll() {
    clearTimeout(pollTimer);
    pollCount++;

    if (pollCount >= MAX_POLL_COUNT) {
        // 15 menit sudah lewat — berhenti auto-polling
        const hint = document.getElementById('waitingHint');
        if (hint) hint.textContent = 'Auto-cek berhenti. Tap "Cek Status Sekarang" jika sudah bayar.';
        const bar = document.getElementById('pollBar');
        if (bar) { bar.style.width = '0%'; bar.style.transition = 'none'; }
        return;
    }

    // Animasi bar untuk interval berikutnya
    animatePollBar(POLL_INTERVAL - 200);
    pollTimer = setTimeout(cekStatus, POLL_INTERVAL);
}

// Tombol cek manual
function cekSekarang() {
    const btn = document.getElementById('btnCekSekarang');
    if (btn) btn.disabled = true;
    clearTimeout(pollTimer);
    pollCount = 0; // Reset counter supaya auto-polling jalan lagi
    cekStatus();
    setTimeout(() => { if (btn) btn.disabled = false; }, 5000);
}

// Mulai polling pertama kali
jadwalkanPoll();

// Cek ulang saat kembali ke tab
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        clearTimeout(pollTimer);
        setTimeout(cekStatus, 500);
    }
});
</script>
@endif

{{-- ════════════════════════════════════════ --}}
{{-- MODAL BOTTOM SHEET — STRUK DOWNLOAD     --}}
{{-- ════════════════════════════════════════ --}}
@if($isPaid)
<style>
.struk-modal-overlay {
    display: none; position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,0.65); backdrop-filter: blur(6px);
    align-items: flex-end; justify-content: center;
}
.struk-modal-overlay.active { display: flex; }
.struk-modal-sheet {
    background: #fff; border-radius: 28px 28px 0 0;
    width: 100%; max-width: 420px; max-height: 92vh; overflow-y: auto;
    padding-bottom: env(safe-area-inset-bottom, 16px);
    animation: struKSlideUp 0.35s cubic-bezier(0.34,1.2,0.64,1) forwards;
}
@keyframes struKSlideUp   { from { transform:translateY(100%); opacity:0; } to { transform:translateY(0); opacity:1; } }
@keyframes struKSlideDown { from { transform:translateY(0); opacity:1; } to { transform:translateY(100%); opacity:0; } }
.struk-modal-sheet.closing { animation: struKSlideDown 0.25s ease forwards; }
.receipt-zigzag-modal {
    width:100%; height:20px; background:#fff;
    margin-top:-18px; z-index:2; position:relative;
    clip-path: polygon(
        0% 100%, 3.33% 0%, 6.66% 100%, 10% 0%, 13.33% 100%, 16.66% 0%,
        20% 100%, 23.33% 0%, 26.66% 100%, 30% 0%, 33.33% 100%, 36.66% 0%,
        40% 100%, 43.33% 0%, 46.66% 100%, 50% 0%, 53.33% 100%, 56.66% 0%,
        60% 100%, 63.33% 0%, 66.66% 100%, 70% 0%, 73.33% 100%, 76.66% 0%,
        80% 100%, 83.33% 0%, 86.66% 100%, 90% 0%, 93.33% 100%, 96.66% 0%, 100% 100%
    );
}
.receipt-divider-modal { border:none; border-top:1.5px dashed #e2e8f0; margin:10px 0; }
@keyframes thumbIn { from { opacity:0; transform:scale(0.8) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
.preview-thumb-wrap { animation: thumbIn 0.3s ease forwards; }
</style>

<div id="modalStruk" class="struk-modal-overlay" onclick="if(event.target===this)tutupModal()">
    <div id="modalSheet" class="struk-modal-sheet">

        <div style="display:flex;justify-content:center;padding-top:12px;padding-bottom:4px;">
            <div style="width:40px;height:4px;background:#e2e8f0;border-radius:99px;"></div>
        </div>

        {{-- ISI RECEIPT (di-capture html2canvas) — Format sama dengan struk kasir --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @php
            $mIcons2  = ['gopay'=>'💚','ovo'=>'💜','dana'=>'💙','shopeepay'=>'🧡','bca'=>'🏦','bni'=>'🏦','bri'=>'🏦','mandiri'=>'🏦','permata'=>'🏦','credit_card'=>'💳','midtrans'=>'💳','qris'=>'📱'];
            $mLabels2 = ['gopay'=>'GoPay (Midtrans)','ovo'=>'OVO (Midtrans)','dana'=>'DANA (Midtrans)','shopeepay'=>'ShopeePay (Midtrans)','bca'=>'BCA Virtual Account','bni'=>'BNI Virtual Account','bri'=>'BRI Virtual Account','mandiri'=>'Mandiri Virtual Account','permata'=>'Permata Virtual Account','credit_card'=>'Kartu Kredit (Midtrans)','midtrans'=>'Online (Midtrans)','qris'=>'QRIS'];
            $mIcon2   = $mIcons2[$order->payment_method]  ?? '💳';
            $mLabel2  = $mLabels2[$order->payment_method] ?? strtoupper($order->payment_method);
            $subtotalStruk = $order->items->sum(fn($i) => $i->price * $i->qty);
            $serviceStruk  = max(0, $order->total - $subtotalStruk);
        @endphp

        <div id="isiReceipt" style="margin:0 16px 8px;background:#fff;border-radius:16px;overflow:hidden;border:1px solid #e4e8f0;box-shadow:0 4px 16px rgba(0,0,0,0.08);font-family:'Poppins',sans-serif;font-size:12px;color:#111;">

            {{-- ── Header sama persis dengan kasir ── --}}
            <div style="text-align:center;padding:20px 16px 10px;">
                <div style="font-size:16px;font-weight:700;letter-spacing:2px;font-family:'Poppins',sans-serif;">CAFE MOMOO</div>
                <div style="font-size:11px;color:#555;margin-top:2px;font-family:'Poppins',sans-serif;">Terima kasih atas kunjungan Anda</div>
                <div style="border-top:2px dashed #bbb;margin:10px 0 0;"></div>
            </div>

            {{-- ── Info order ── --}}
            <div style="padding:0 16px;">
                <table style="width:100%;font-size:12px;border-collapse:collapse;margin-bottom:4px;font-family:'Poppins',sans-serif;">
                    <tr><td style="padding:3px 0;">No. Order</td><td style="text-align:right;font-weight:700;">{{ $order->queue_number }}</td></tr>
                    <tr><td style="padding:3px 0;">Nama</td><td style="text-align:right;">{{ $order->customer_name ?? '—' }}</td></tr>
                    <tr><td style="padding:3px 0;">Meja</td><td style="text-align:right;">{{ $order->table_number ? 'Meja '.$order->table_number : 'Take Away' }}</td></tr>
                    <tr><td style="padding:3px 0;">Waktu</td><td style="text-align:right;font-size:11px;">{{ now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td></tr>
                    <tr><td style="padding:3px 0;">Metode</td><td style="text-align:right;">{{ $mIcon2 }} {{ $mLabel2 }}</td></tr>
                </table>

                <div style="border-top:1px dashed #bbb;margin:8px 0;"></div>

                {{-- ── Item Pesanan ── --}}
                <div style="font-weight:700;margin-bottom:6px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:#555;font-family:'Poppins',sans-serif;">Item Pesanan</div>
                @foreach($order->items as $item)
                <div style="display:flex;justify-content:space-between;margin-bottom:5px;font-family:'Poppins',sans-serif;">
                    <span style="flex:1;padding-right:8px;">{{ $item->qty }}x {{ $item->name }}</span>
                    <span style="white-space:nowrap;font-weight:600;">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                </div>
                @endforeach

                <div style="border-top:1px dashed #bbb;margin:8px 0;"></div>

                {{-- ── Subtotal & Biaya Layanan ── --}}
                <table style="width:100%;font-size:12px;border-collapse:collapse;font-family:'Poppins',sans-serif;">
                    <tr><td style="padding:3px 0;">Subtotal</td><td style="text-align:right;">Rp {{ number_format($subtotalStruk, 0, ',', '.') }}</td></tr>
                    @if($serviceStruk > 0)
                    <tr><td style="padding:3px 0;">Biaya Layanan</td><td style="text-align:right;">Rp {{ number_format($serviceStruk, 0, ',', '.') }}</td></tr>
                    @endif
                </table>

                <div style="border-top:1px dashed #bbb;margin:8px 0;"></div>

                {{-- ── TOTAL ── --}}
                <table style="width:100%;font-size:13px;border-collapse:collapse;font-family:'Poppins',sans-serif;">
                    <tr style="font-weight:700;">
                        <td style="padding:3px 0;">TOTAL</td>
                        <td style="text-align:right;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                </table>

                {{-- ── Info Lunas Online (meniru sMidtransBlock kasir) ── --}}
                <div style="margin-top:6px;">
                    <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:8px;padding:8px 10px;text-align:center;font-family:'Poppins',sans-serif;">
                        <span style="font-size:11px;font-weight:700;color:#065f46;">✅ Lunas via Online Payment ({{ $mLabel2 }})</span>
                    </div>
                </div>

                <div style="border-top:2px dashed #bbb;margin:10px 0 8px;"></div>

                {{-- ── Footer sama dengan kasir ── --}}
                <div style="text-align:center;font-size:11px;color:#666;line-height:1.8;font-family:'Poppins',sans-serif;padding-bottom:16px;">
                    Terima kasih telah memesan!<br>
                    Semoga makanan Anda lezat 😊
                    <div style="font-size:10px;margin-top:6px;color:#999;font-family:'Poppins',sans-serif;">Dicetak: {{ now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}</div>
                </div>
            </div>

        </div>{{-- /isiReceipt --}}

        {{-- Tombol area --}}
        <div style="padding:8px 16px 20px;position:sticky;bottom:0;background:#fff;border-top:1px solid #f1f5f9;">

            <div id="previewHasil" style="display:none;background:#eef2ff;border:1.5px solid #c7d2fe;border-radius:14px;padding:12px;margin-bottom:10px;align-items:center;gap:12px;" class="preview-thumb-wrap">
                <img id="previewImg" src="" alt="Preview"
                     style="width:56px;height:56px;object-fit:cover;border-radius:10px;border:1.5px solid #a5b4fc;flex-shrink:0;cursor:pointer;"
                     onclick="bukaGambarPenuh()" title="Tap untuk lihat penuh">
                <div style="flex:1;min-width:0;">
                    <div style="font-size:12px;font-weight:800;color:#4f46e5;margin-bottom:2px;">✅ Struk berhasil diunduh!</div>
                    <div id="previewNama" style="font-size:10px;color:#64748b;word-break:break-all;font-family:monospace;"></div>
                    <div style="font-size:10px;color:#94a3b8;margin-top:2px;">Tap gambar untuk lihat penuh</div>
                </div>
            </div>

            <div style="display:flex;gap:10px;">
                <button onclick="tutupModal()"
                        style="flex:1;padding:14px;border-radius:50px;border:0;background:white;color:#64748b;font-weight:700;font-size:13px;cursor:pointer;letter-spacing:1.5px;text-transform:uppercase;box-shadow:rgb(0 0 0/5%) 0 0 8px;transition:all 0.5s ease;font-family:'Plus Jakarta Sans',sans-serif;"
                        onmouseover="this.style.letterSpacing='3px';this.style.backgroundColor='hsl(261deg 80% 48%)';this.style.color='white';this.style.boxShadow='rgb(93 24 220) 0px 7px 29px 0px';"
                        onmouseout="this.style.letterSpacing='1.5px';this.style.backgroundColor='white';this.style.color='#64748b';this.style.boxShadow='rgb(0 0 0/5%) 0 0 8px';">
                    Tutup
                </button>
                <button id="btnDownload" onclick="downloadStruk()"
                        style="flex:2.5;padding:14px;border-radius:50px;border:0;background:white;color:#6366f1;font-weight:800;font-size:13px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;letter-spacing:1.5px;text-transform:uppercase;box-shadow:rgb(0 0 0/5%) 0 0 8px;transition:all 0.5s ease;font-family:'Plus Jakarta Sans',sans-serif;"
                        onmouseover="this.style.letterSpacing='3px';this.style.backgroundColor='#6366f1';this.style.color='white';this.style.boxShadow='rgb(99 102 241) 0px 7px 29px 0px';"
                        onmouseout="this.style.letterSpacing='1.5px';this.style.backgroundColor='white';this.style.color='#6366f1';this.style.boxShadow='rgb(0 0 0/5%) 0 0 8px';">
                    📥 Unduh Struk (PNG)
                </button>
            </div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function bukaModal() {
    document.getElementById('modalStruk').classList.add('active');
    document.getElementById('modalSheet').classList.remove('closing');
    document.body.style.overflow = 'hidden';
}
function tutupModal() {
    const sheet = document.getElementById('modalSheet');
    sheet.classList.add('closing');
    setTimeout(function() {
        document.getElementById('modalStruk').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('previewHasil').style.display = 'none';
    }, 240);
}
function downloadStruk() {
    const btn = document.getElementById('btnDownload');
    const origHTML = btn.innerHTML;
    btn.innerHTML = '<span style="display:inline-block;animation:spin2 0.8s linear infinite;">⏳</span>&nbsp;Memproses...';
    btn.disabled = true; btn.style.opacity = '0.75';

    const el = document.getElementById('isiReceipt');
    const namaFile = 'struk-{{ $order->queue_number }}-{{ now()->format("YmdHis") }}.png';

    html2canvas(el, { scale:3, backgroundColor:'#ffffff', useCORS:true, logging:false }).then(function(canvas) {
        const dataUrl = canvas.toDataURL('image/png');
        const a = document.createElement('a');
        a.download = namaFile; a.href = dataUrl; a.click();

        btn.innerHTML = '✅ Berhasil Diunduh!';
        btn.style.background = 'linear-gradient(135deg,#22c55e,#16a34a)';
        btn.style.color = 'white'; btn.style.opacity = '1'; btn.disabled = false;

        const prev = document.getElementById('previewHasil');
        document.getElementById('previewImg').src = dataUrl;
        document.getElementById('previewNama').textContent = namaFile;
        prev.style.display = 'flex';

        setTimeout(function() {
            btn.innerHTML = origHTML; btn.style.background = '';
            btn.style.color = '#6366f1';
        }, 3000);
    }).catch(function() {
        btn.innerHTML = '❌ Gagal, coba lagi';
        btn.style.background = 'linear-gradient(135deg,#ef4444,#dc2626)';
        btn.style.color = 'white'; btn.style.opacity = '1'; btn.disabled = false;
        setTimeout(function() { btn.innerHTML = origHTML; btn.style.background = ''; btn.style.color = '#6366f1'; }, 2500);
    });
}
function bukaGambarPenuh() {
    const img = document.getElementById('previewImg').src;
    const w = window.open('');
    w.document.write('<style>body{margin:0;background:#1e293b;display:flex;justify-content:center;padding:20px;}</style>');
    w.document.write('<img src="' + img + '" style="max-width:100%;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.4);">');
}
</script>
<style>@keyframes spin2 { to { transform:rotate(360deg); } }</style>
@endif

</body>
</html>