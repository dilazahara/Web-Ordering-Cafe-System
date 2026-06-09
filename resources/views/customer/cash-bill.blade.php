<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tagihan Cash – {{ $order->queue_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { background: linear-gradient(135deg, #fff7ed 0%, #ffffff 50%, #fef3c7 100%); min-height: 100vh; }

        .bill-card {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 24px 64px rgba(249,115,22,0.15), 0 4px 16px rgba(0,0,0,0.06);
            overflow: hidden;
            position: relative;
        }

        .bill-header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            padding: 28px 24px 48px;
            position: relative;
            overflow: hidden;
        }
        .bill-header::before {
            content: '';
            position: absolute;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.12);
            border-radius: 50%;
            top: -60px; right: -40px;
        }
        .bill-header::after {
            content: '';
            position: absolute;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            bottom: -30px; left: -30px;
        }

        .zigzag-top {
            width: 100%;
            height: 24px;
            background: #fff;
            position: relative;
            margin-top: -22px;
            z-index: 2;
            clip-path: polygon(
                0% 100%, 3.33% 0%, 6.66% 100%, 10% 0%, 13.33% 100%, 16.66% 0%,
                20% 100%, 23.33% 0%, 26.66% 100%, 30% 0%, 33.33% 100%, 36.66% 0%,
                40% 100%, 43.33% 0%, 46.66% 100%, 50% 0%, 53.33% 100%, 56.66% 0%,
                60% 100%, 63.33% 0%, 66.66% 100%, 70% 0%, 73.33% 100%, 76.66% 0%,
                80% 100%, 83.33% 0%, 86.66% 100%, 90% 0%, 93.33% 100%, 96.66% 0%, 100% 100%
            );
        }

        .dash-line { border: none; border-top: 2.5px dashed #f1f5f9; margin: 14px 0; }
        .dash-line-orange { border: none; border-top: 2px dashed #fed7aa; margin: 14px 0; }

        .queue-badge {
            background: rgba(255,255,255,0.22);
            border: 2px solid rgba(255,255,255,0.45);
            border-radius: 18px;
            padding: 10px 22px;
            display: inline-block;
            backdrop-filter: blur(8px);
        }

        .barcode-wrap {
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 48px;
            justify-content: center;
        }
        .barcode-wrap span { display: block; width: 3px; background: #1e293b; border-radius: 1px; }

        .item-row { display: flex; align-items: flex-start; gap: 12px; padding: 10px 0; }
        .item-img { width: 44px; height: 44px; border-radius: 12px; object-fit: cover; border: 1px solid #f1f5f9; flex-shrink: 0; }

        .chip-pending {
            background: #fef3c7; color: #92400e;
            border: 1.5px solid #fcd34d;
            border-radius: 999px; padding: 4px 14px;
            font-size: 12px; font-weight: 700;
            display: inline-flex; align-items: center; gap: 5px;
        }

        @keyframes blink { 0%,100%{ opacity:1; } 50%{ opacity:0.3; } }
        .blink { animation: blink 1.4s ease-in-out infinite; }

        /* ── UIverse Button ── */
        .btn-kasir, .btn-outline {
            padding: 17px 40px;
            border-radius: 50px;
            cursor: pointer;
            border: 0;
            background-color: white;
            box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-size: 15px;
            transition: all 0.5s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-kasir { color: #f97316; }
        .btn-outline { color: #64748b; }
        .btn-kasir:hover {
            letter-spacing: 3px;
            background-color: #f97316;
            color: white;
            box-shadow: rgb(249 115 22) 0px 7px 29px 0px;
        }
        .btn-kasir:active {
            letter-spacing: 3px;
            background-color: #f97316;
            color: white;
            box-shadow: rgb(249 115 22) 0px 0px 0px 0px;
            transform: translateY(10px);
            transition: 100ms;
        }
        .btn-outline:hover {
            letter-spacing: 3px;
            background-color: hsl(261deg 80% 48%);
            color: white;
            box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
        }
        .btn-outline:active {
            letter-spacing: 3px;
            background-color: hsl(261deg 80% 48%);
            color: white;
            box-shadow: rgb(93 24 220) 0px 0px 0px 0px;
            transform: translateY(10px);
            transition: 100ms;
        }

        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .fade-up-2 { animation: fadeUp 0.5s ease 0.1s forwards; opacity: 0; }
        .fade-up-3 { animation: fadeUp 0.5s ease 0.2s forwards; opacity: 0; }

        @keyframes popIn { 0%{ transform:scale(0.7); opacity:0; } 70%{ transform:scale(1.08); } 100%{ transform:scale(1); opacity:1; } }
        .pop-in { animation: popIn 0.55s cubic-bezier(0.34,1.56,0.64,1) forwards; }

        /* ── MODAL OVERLAY ── */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(6px);
            align-items: flex-end;
            justify-content: center;
            padding: 0;
        }
        .modal-overlay.active { display: flex; }

        .modal-sheet {
            background: #fff;
            border-radius: 28px 28px 0 0;
            width: 100%;
            max-width: 420px;
            max-height: 92vh;
            overflow-y: auto;
            padding-bottom: env(safe-area-inset-bottom, 16px);
            animation: slideUp 0.35s cubic-bezier(0.34,1.2,0.64,1) forwards;
        }

        @keyframes slideUp { from { transform: translateY(100%); opacity:0; } to { transform: translateY(0); opacity:1; } }
        @keyframes slideDown { from { transform: translateY(0); opacity:1; } to { transform: translateY(100%); opacity:0; } }
        .modal-sheet.closing { animation: slideDown 0.25s ease forwards; }

        /* ── RECEIPT INSIDE MODAL ── */
        .receipt-inner {
            background: #fff;
        }

        .receipt-header-cash {
            background: linear-gradient(135deg, #f97316 0%, #c2410c 100%);
            padding: 24px 20px 36px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        .receipt-header-cash::before {
            content:''; position:absolute;
            width:160px;height:160px;
            background:rgba(255,255,255,0.1);
            border-radius:50%; top:-50px; right:-30px;
        }

        /* Zigzag bottom of receipt header */
        .receipt-zigzag {
            width: 100%; height: 20px;
            background: #fff;
            margin-top: -18px; z-index: 2;
            position: relative;
            clip-path: polygon(
                0% 100%, 3.33% 0%, 6.66% 100%, 10% 0%, 13.33% 100%, 16.66% 0%,
                20% 100%, 23.33% 0%, 26.66% 100%, 30% 0%, 33.33% 100%, 36.66% 0%,
                40% 100%, 43.33% 0%, 46.66% 100%, 50% 0%, 53.33% 100%, 56.66% 0%,
                60% 100%, 63.33% 0%, 66.66% 100%, 70% 0%, 73.33% 100%, 76.66% 0%,
                80% 100%, 83.33% 0%, 86.66% 100%, 90% 0%, 93.33% 100%, 96.66% 0%, 100% 100%
            );
        }

        .receipt-divider { border: none; border-top: 1.5px dashed #e2e8f0; margin: 10px 0; }

        /* Preview thumbnail feedback */
        @keyframes thumbIn { from { opacity:0; transform:scale(0.8) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
        .preview-thumb-wrap { animation: thumbIn 0.3s ease forwards; }

        @keyframes spin { to { transform: rotate(360deg); } }

        @media print {
            body { background: #fff !important; }
            .no-print { display: none !important; }
            .bill-card { box-shadow: none; }
        }

        /* Back Popup */
        .back-popup-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.5);
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
        }
        .back-popup-overlay.show .back-popup-box { transform: translateY(0); }
        .back-popup-handle { width: 40px; height: 4px; background: #e5e7eb; border-radius: 2px; margin: 0 auto 18px; }
    </style>
</head>

<body class="p-5 pb-10 flex flex-col items-center">

@php
    $subtotal = $order->items->sum(fn($i) => $i->price * $i->qty);
    $service  = $order->total - $subtotal;
    $now      = now()->locale('id')->isoFormat('dddd, D MMMM YYYY • HH:mm');
    $nowSimple = now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i');
@endphp

<div class="w-full max-w-sm fade-up">

    {{-- ════════ BILL CARD ════════ --}}
    <div class="bill-card">

        {{-- HEADER --}}
        <div class="bill-header text-center relative z-10">
            <div class="pop-in mx-auto w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center mb-3 shadow-inner">
                <span class="text-3xl">🧾</span>
            </div>
            <h1 class="text-white font-extrabold text-xl tracking-tight leading-none">Tagihan Pembayaran</h1>
            <p class="text-orange-100 text-xs mt-1">Bayar Tunai ke Kasir</p>
            <div class="queue-badge mt-5 relative z-10">
                <p class="text-white/70 text-[10px] font-semibold uppercase tracking-widest leading-none mb-1">Nomor Antrian</p>
                <p class="text-white text-3xl font-black tracking-wider leading-none">{{ $order->queue_number }}</p>
            </div>
        </div>

        <div class="zigzag-top"></div>

        {{-- BODY --}}
        <div class="px-6 pb-6 -mt-2">

            <div class="grid grid-cols-2 gap-3 mt-2 fade-up-2">
                <div class="bg-slate-50 rounded-2xl p-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Nomor Meja</p>
                    <p class="font-black text-slate-800 text-base leading-none">{{ $order->table_number ?? '-' }}</p>
                </div>
                <div class="bg-orange-50 rounded-2xl p-3">
                    <p class="text-[10px] font-bold text-orange-400 uppercase tracking-wider leading-none mb-1">Status</p>
                    <div class="chip-pending mt-0.5">
                        <span class="blink w-2 h-2 rounded-full bg-amber-500 inline-block"></span>
                        Belum Bayar
                    </div>
                </div>
            </div>

            @if($order->customer_name)
            <div class="bg-blue-50 rounded-2xl p-3 mt-3 fade-up-2">
                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-wider leading-none mb-1">Nama Pemesan</p>
                <p class="font-black text-blue-800 text-base leading-none">{{ $order->customer_name }}</p>
            </div>
            @endif

            <p class="text-center text-[11px] text-slate-400 mt-4 font-medium">{{ $now }}</p>
            <hr class="dash-line">

            <h2 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1 fade-up-2">Detail Pesanan</h2>
            <div class="space-y-1 fade-up-3">
                @foreach($order->items as $item)
                <div class="item-row">
                    @php
                        $imgSrc = $item->menu?->image
                            ? asset('storage/' . $item->menu->image)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($item->name) . '&background=ffecd2&color=f97316&bold=true&size=44';
                    @endphp
                    <img src="{{ $imgSrc }}" alt="{{ $item->name }}" class="item-img"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->name) }}&background=ffecd2&color=f97316&bold=true&size=44'">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate leading-tight">{{ $item->name }}</p>
                        @if($item->notes)
                        <p class="text-[10px] text-slate-400 italic leading-none mt-0.5">{{ $item->notes }}</p>
                        @endif
                        <p class="text-xs text-slate-500 mt-0.5">× {{ $item->qty }}
                            <span class="text-slate-400 ml-1">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </p>
                    </div>
                    <p class="text-sm font-extrabold text-orange-500 flex-shrink-0">
                        Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                    </p>
                </div>
                @if(!$loop->last)<hr class="dash-line-orange my-0">@endif
                @endforeach
            </div>

            <hr class="dash-line mt-3">

            <div class="space-y-2 fade-up-3">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Subtotal ({{ $order->items->sum('qty') }} item)</span>
                    <span class="font-bold text-slate-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Biaya Layanan</span>
                    <span class="font-bold text-slate-700">Rp {{ number_format($service, 0, ',', '.') }}</span>
                </div>
            </div>

            <hr class="dash-line">

            <div class="flex items-center justify-between fade-up-3">
                <span class="font-black text-slate-800 text-sm">TOTAL BAYAR</span>
                <span class="text-2xl font-black text-orange-500">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>

            <hr class="dash-line mt-3">

            <div class="text-center mt-4 fade-up-3">
                <div class="barcode-wrap mb-2">
                    @php $heights = [38,28,44,32,48,24,40,28,42,36,48,28,38,44,24,36,42,30,46,28,40,34,48,26,36,44,30,42,28,46]; @endphp
                    @foreach($heights as $h)<span style="height:{{ $h }}px;"></span>@endforeach
                </div>
                <p class="text-[10px] font-mono text-slate-500 tracking-widest">{{ $order->queue_number }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="mt-5 bg-orange-50 border border-orange-100 rounded-2xl p-4 fade-up-3">
                <div class="flex gap-3 items-start">
                    <span class="text-2xl flex-shrink-0">💵</span>
                    <div>
                        <p class="text-sm font-bold text-orange-800">Cara Bayar ke Kasir</p>
                        <ol class="text-xs text-orange-700 mt-1.5 space-y-1 leading-relaxed list-decimal list-inside">
                            <li>Tunjukkan halaman ini ke kasir</li>
                            <li>Kasir akan memverifikasi pesananmu</li>
                            <li>Lakukan pembayaran tunai</li>
                            <li>Pesanan diproses setelah lunas ✅</li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ════════ ACTION BUTTONS ════════ --}}
    <div class="mt-5 space-y-3 no-print fade-up-3">

        {{-- LIHAT BILLS → buka modal --}}
        <button onclick="bukaModal()"
                class="btn-kasir w-full py-4 text-black font-black text-sm flex items-center justify-center gap-2">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
            Lihat Tagihan
        </button>

        <button
            onclick="localStorage.removeItem('cart'); localStorage.removeItem('checkoutCart'); window.location.href='/customer/home';"
            class="btn-outline w-full py-3.5 font-bold text-sm flex items-center justify-center gap-2">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            Kembali ke Menu
        </button>
    </div>

    <p class="text-center text-[11px] text-slate-400 mt-5 leading-relaxed no-print">
        Tap <strong>Lihat Bills</strong> untuk melihat & download tagihan.<br>Tunjukkan ke kasir saat pembayaran.
    </p>

</div>


{{-- ════════════════════════════════════════════════ --}}
{{--  MODAL BOTTOM SHEET – BILLS RECEIPT              --}}
{{-- ════════════════════════════════════════════════ --}}
<div id="modalBills" class="modal-overlay" onclick="handleOverlayClick(event)">
    <div id="modalSheet" class="modal-sheet">

        {{-- Handle bar --}}
        <div class="flex justify-center pt-3 pb-1">
            <div class="w-10 h-1 bg-gray-200 rounded-full"></div>
        </div>

        {{-- ════ ISI RECEIPT (yang di-capture html2canvas) ════ --}}
        <div id="isiReceipt" class="receipt-inner mx-4 mb-2 rounded-3xl overflow-hidden border border-orange-100" style="box-shadow:0 8px 32px rgba(249,115,22,0.12);">

            {{-- RECEIPT HEADER --}}
            <div class="receipt-header-cash relative z-10">
                <div style="width:52px;height:52px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:26px;">
                    💵
                </div>
                <p style="color:#fff;font-weight:900;font-size:18px;margin:0;letter-spacing:-0.3px;">Tagihan Cash</p>
                <p style="color:rgba(255,255,255,0.75);font-size:11px;margin:4px 0 0;">Bayar Tunai ke Kasir</p>

                {{-- Queue number badge --}}
                <div style="display:inline-block;margin-top:14px;background:rgba(255,255,255,0.2);border:1.5px solid rgba(255,255,255,0.4);border-radius:14px;padding:8px 20px;">
                    <p style="color:rgba(255,255,255,0.7);font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin:0 0 3px;">NOMOR ANTRIAN</p>
                    <p style="color:#fff;font-size:28px;font-weight:900;letter-spacing:3px;margin:0;line-height:1;">{{ $order->queue_number }}</p>
                </div>
            </div>
            <div class="receipt-zigzag"></div>

            {{-- RECEIPT BODY --}}
            <div style="padding:4px 18px 18px; background:#fff;">

                {{-- Nama cafe --}}
                <div style="text-align:center;padding:10px 0 8px;">
                    <p style="font-weight:900;color:#1e293b;font-size:15px;margin:0;">Cafe Tugas Akhir</p>
                    <p style="color:#94a3b8;font-size:10px;margin:2px 0 0;">Batam, Kepulauan Riau</p>
                    <p style="color:#94a3b8;font-size:10px;margin:2px 0 0;">{{ $nowSimple }} WIB</p>
                </div>

                <hr class="receipt-divider">

                {{-- Meta: meja & nama --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:8px 0;">
                    <div style="background:#f8fafc;border-radius:10px;padding:8px 10px;">
                        <p style="font-size:9px;font-weight:700;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin:0 0 3px;">Nomor Meja</p>
                        <p style="font-weight:900;color:#1e293b;font-size:14px;margin:0;">{{ $order->table_number ?? '-' }}</p>
                    </div>
                    <div style="background:#fff7ed;border-radius:10px;padding:8px 10px;">
                        <p style="font-size:9px;font-weight:700;color:#f97316;letter-spacing:1px;text-transform:uppercase;margin:0 0 3px;">Status</p>
                        <p style="font-weight:800;color:#92400e;font-size:12px;margin:0;">⏳ Belum Bayar</p>
                    </div>
                </div>

                @if($order->customer_name)
                <div style="background:#eff6ff;border-radius:10px;padding:8px 10px;margin-bottom:8px;">
                    <p style="font-size:9px;font-weight:700;color:#60a5fa;letter-spacing:1px;text-transform:uppercase;margin:0 0 3px;">Nama Pemesan</p>
                    <p style="font-weight:900;color:#1e40af;font-size:14px;margin:0;">{{ $order->customer_name }}</p>
                </div>
                @endif

                <hr class="receipt-divider">

                {{-- Detail item --}}
                <p style="font-size:9px;font-weight:800;color:#94a3b8;letter-spacing:1.5px;text-transform:uppercase;margin:8px 0 6px;">DETAIL PESANAN</p>
                @foreach($order->items as $item)
                <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:5px 0;{{ !$loop->last ? 'border-bottom:1px dashed #fed7aa;' : '' }}">
                    <div style="flex:1;">
                        <p style="font-weight:700;color:#1e293b;font-size:13px;margin:0;">{{ $item->name }}</p>
                        @if($item->notes)
                        <p style="font-size:10px;color:#94a3b8;font-style:italic;margin:1px 0 0;">* {{ $item->notes }}</p>
                        @endif
                        <p style="font-size:10px;color:#64748b;margin:2px 0 0;">× {{ $item->qty }} @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <p style="font-weight:800;color:#f97316;font-size:13px;margin:0;flex-shrink:0;padding-left:8px;">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</p>
                </div>
                @endforeach

                <hr class="receipt-divider" style="margin-top:10px;">

                {{-- Rincian biaya --}}
                <div style="padding:4px 0;">
                    <div style="display:flex;justify-content:space-between;font-size:12px;padding:3px 0;">
                        <span style="color:#64748b;">Subtotal ({{ $order->items->sum('qty') }} item)</span>
                        <span style="font-weight:700;color:#1e293b;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;padding:3px 0;">
                        <span style="color:#64748b;">Biaya Layanan</span>
                        <span style="font-weight:700;color:#1e293b;">Rp {{ number_format($service, 0, ',', '.') }}</span>
                    </div>
                </div>

                <hr class="receipt-divider">

                {{-- Total --}}
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
                    <span style="font-weight:900;color:#1e293b;font-size:14px;">TOTAL BAYAR</span>
                    <span style="font-weight:900;color:#f97316;font-size:22px;">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>

                <hr class="receipt-divider">

                {{-- Barcode dekoratif --}}
                <div style="text-align:center;padding:12px 0 6px;">
                    <div style="display:flex;align-items:flex-end;gap:2px;height:40px;justify-content:center;margin-bottom:6px;">
                        @php $heights2 = [32,22,38,26,42,20,34,24,36,30,42,22,32,38,20,30,36,26,40,22,34,28,42,20,30,38,24,36,22,40]; @endphp
                        @foreach($heights2 as $h2)
                        <span style="display:block;width:3px;height:{{ $h2 }}px;background:#1e293b;border-radius:1px;"></span>
                        @endforeach
                    </div>
                    <p style="font-size:9px;font-family:monospace;color:#94a3b8;letter-spacing:2px;">{{ $order->queue_number }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>

                {{-- Instruksi Cash --}}
                <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:14px;padding:12px 14px;margin-top:10px;">
                    <p style="font-weight:800;color:#9a3412;font-size:12px;margin:0 0 6px;">💵 Cara Bayar ke Kasir</p>
                    <ol style="color:#c2410c;font-size:11px;padding-left:16px;margin:0;line-height:1.7;">
                        <li>Tunjukkan bills ini ke kasir</li>
                        <li>Kasir verifikasi pesananmu</li>
                        <li>Lakukan pembayaran tunai</li>
                        <li>Pesanan diproses setelah lunas ✅</li>
                    </ol>
                </div>

                {{-- Footer receipt --}}
                <div style="text-align:center;padding:14px 0 4px;">
                    <p style="font-size:11px;color:#94a3b8;margin:0;">Terima kasih telah memesan! 🙏</p>
                    <p style="font-size:10px;color:#cbd5e1;margin:3px 0 0;">Simpan bills ini sebagai bukti tagihan</p>
                </div>

            </div>{{-- /receipt body --}}
        </div>{{-- /isiReceipt --}}


        {{-- ════ AREA FEEDBACK + TOMBOL ════ --}}
        <div style="padding:8px 16px 20px; position:sticky; bottom:0; background:#fff; border-top:1px solid #f1f5f9;">

            {{-- Preview thumbnail (muncul setelah download) --}}
            <div id="previewHasil" style="display:none; background:#fff7ed; border:1.5px solid #fed7aa; border-radius:14px; padding:12px; margin-bottom:10px; align-items:center; gap:12px;"
                 class="preview-thumb-wrap">
                <img id="previewImg" src="" alt="Preview Bills"
                     style="width:56px; height:56px; object-fit:cover; border-radius:10px; border:1.5px solid #fcd34d; flex-shrink:0; cursor:pointer;"
                     onclick="bukaGambarPenuh()" title="Tap untuk lihat ukuran penuh">
                <div style="flex:1; min-width:0;">
                    <div style="font-size:12px; font-weight:800; color:#ea580c; margin-bottom:2px;">✅ Bills berhasil diunduh!</div>
                    <div id="previewNama" style="font-size:10px; color:#64748b; word-break:break-all; font-family:monospace;"></div>
                    <div style="font-size:10px; color:#94a3b8; margin-top:2px;">Tap gambar untuk lihat penuh</div>
                </div>
            </div>

            {{-- Tombol baris --}}
            <div style="display:flex; gap:10px;">
                <button onclick="tutupModal()"
                        style="flex:1; padding:14px; border-radius:50px; border:0; background:white; color:#64748b; font-weight:700; font-size:13px; cursor:pointer; letter-spacing:1.5px; text-transform:uppercase; box-shadow:rgb(0 0 0 / 5%) 0 0 8px; transition:all 0.5s ease;"
                        onmouseover="this.style.letterSpacing='3px';this.style.backgroundColor='hsl(261deg 80% 48%)';this.style.color='white';this.style.boxShadow='rgb(93 24 220) 0px 7px 29px 0px';"
                        onmouseout="this.style.letterSpacing='1.5px';this.style.backgroundColor='white';this.style.color='#64748b';this.style.boxShadow='rgb(0 0 0 / 5%) 0 0 8px';">
                    Tutup
                </button>
                <button id="btnDownload" onclick="downloadBills()"
                        style="flex:2.5; padding:14px; border-radius:50px; border:0; background:white; color:#f97316; font-weight:800; font-size:13px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:7px; letter-spacing:1.5px; text-transform:uppercase; box-shadow:rgb(0 0 0 / 5%) 0 0 8px; transition:all 0.5s ease;"
                        onmouseover="this.style.letterSpacing='3px';this.style.backgroundColor='#f97316';this.style.color='white';this.style.boxShadow='rgb(249 115 22) 0px 7px 29px 0px';"
                        onmouseout="this.style.letterSpacing='1.5px';this.style.backgroundColor='white';this.style.color='#f97316';this.style.boxShadow='rgb(0 0 0 / 5%) 0 0 8px';">
                    📥 Unduh Bills (PNG)
                </button>
            </div>
        </div>

    </div>{{-- /modal-sheet --}}
</div>{{-- /modal-overlay --}}


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
// Hapus keranjang otomatis
localStorage.removeItem('cart');
localStorage.removeItem('checkoutCart');

// ── Modal ──
function bukaModal() {
    const overlay = document.getElementById('modalBills');
    const sheet   = document.getElementById('modalSheet');
    overlay.classList.add('active');
    sheet.classList.remove('closing');
    document.body.style.overflow = 'hidden';
}

function tutupModal() {
    const overlay = document.getElementById('modalBills');
    const sheet   = document.getElementById('modalSheet');
    sheet.classList.add('closing');
    setTimeout(function() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('previewHasil').style.display = 'none';
    }, 240);
}

function handleOverlayClick(e) {
    if (e.target === document.getElementById('modalBills')) tutupModal();
}

// ── Download PNG ──
function downloadBills() {
    const btn = document.getElementById('btnDownload');
    const originalHTML = btn.innerHTML;
    const originalBg   = btn.style.background;

    btn.innerHTML = '<span style="display:inline-block;animation:spin 0.8s linear infinite;">⏳</span>&nbsp;Memproses...';
    btn.disabled  = true;
    btn.style.opacity = '0.75';

    const el      = document.getElementById('isiReceipt');
    const namaFile = 'bills-cash-{{ $order->queue_number }}-{{ now()->format("YmdHis") }}.png';

    html2canvas(el, {
        scale: 3,
        backgroundColor: '#ffffff',
        useCORS: true,
        logging: false,
        windowWidth: el.scrollWidth,
        windowHeight: el.scrollHeight,
    }).then(function(canvas) {
        const dataUrl = canvas.toDataURL('image/png');

        // Trigger download
        const link    = document.createElement('a');
        link.download = namaFile;
        link.href     = dataUrl;
        link.click();

        // Feedback tombol berhasil
        btn.innerHTML = '✅ Berhasil Diunduh!';
        btn.style.background = 'linear-gradient(135deg,#22c55e,#16a34a)';
        btn.style.boxShadow  = '0 6px 16px -4px rgba(34,197,94,0.4)';
        btn.style.opacity    = '1';
        btn.disabled = false;

        // Tampilkan preview thumbnail
        const prev    = document.getElementById('previewHasil');
        const prevImg = document.getElementById('previewImg');
        const prevNama= document.getElementById('previewNama');
        prevImg.src   = dataUrl;
        prevNama.textContent = namaFile;
        prev.style.display = 'flex';

        // Reset tombol setelah 3 detik
        setTimeout(function() {
            btn.innerHTML    = originalHTML;
            btn.style.background = originalBg;
            btn.style.boxShadow  = '0 6px 16px -4px rgba(249,115,22,0.4)';
        }, 3000);

    }).catch(function() {
        btn.innerHTML = '❌ Gagal, coba lagi';
        btn.style.background = 'linear-gradient(135deg,#ef4444,#dc2626)';
        btn.style.opacity = '1';
        btn.disabled = false;
        setTimeout(function() {
            btn.innerHTML    = originalHTML;
            btn.style.background = originalBg;
        }, 2500);
    });
}

// Buka gambar penuh di tab baru
function bukaGambarPenuh() {
    const img = document.getElementById('previewImg').src;
    const w   = window.open('');
    w.document.write('<style>body{margin:0;background:#1e293b;display:flex;justify-content:center;align-items:flex-start;padding:20px;}</style>');
    w.document.write('<img src="' + img + '" style="max-width:100%;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.4);">');
}

// ── Back popup di halaman bill ──
let _billBackPopupOpen = false;
function openBillBackPopup() {
    _billBackPopupOpen = true;
    document.getElementById('billBackPopup').classList.add('show');
    history.pushState({ popup: true }, '', location.href);
}
function closeBillBackPopup(e) {
    if (e && e.target !== document.getElementById('billBackPopup')) return;
    _billBackPopupOpen = false;
    document.getElementById('billBackPopup').classList.remove('show');
}
window.addEventListener('popstate', function() {
    if (_billBackPopupOpen) {
        _billBackPopupOpen = false;
        document.getElementById('billBackPopup').classList.remove('show');
        return;
    }
    openBillBackPopup();
});
history.pushState(null, '', location.href);
</script>

<!-- Back Popup Cash Bill -->
<div id="billBackPopup" class="back-popup-overlay" onclick="closeBillBackPopup(event)">
    <div class="back-popup-box">
        <div class="back-popup-handle"></div>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="font-extrabold text-gray-900 text-base">Pesanan sudah tercatat!</p>
                <p class="text-sm text-gray-400 mt-0.5">Kembali ke halaman utama?</p>
            </div>
        </div>
        <p class="text-sm text-gray-500 mb-5 bg-gray-50 rounded-2xl px-4 py-3">Struk ini bisa kamu screenshot dulu sebelum keluar 📸</p>
        <div class="flex gap-3">
            <button onclick="closeBillBackPopup(null)" class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 font-bold text-gray-700 text-sm active:bg-gray-50 transition-all">Lihat Struk</button>
            <button onclick="localStorage.removeItem('cart'); localStorage.removeItem('checkoutCart'); window.location.href='/customer/home';" class="flex-1 py-3.5 rounded-2xl bg-green-500 font-bold text-white text-sm active:bg-green-600 active:scale-[0.98] transition-all">Ke Halaman Utama</button>
        </div>
    </div>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>

</body>
</html>