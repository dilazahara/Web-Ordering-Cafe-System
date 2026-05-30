<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Struk QRIS – {{ $order->queue_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { background: linear-gradient(135deg, #ecfdf5 0%, #ffffff 50%, #d1fae5 100%); min-height: 100vh; }

        /* ── Receipt Card ── */
        .receipt-card {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 24px 64px rgba(16,185,129,0.15), 0 4px 16px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        /* ── Header emerald gradient ── */
        .receipt-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 28px 24px 52px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        .receipt-header::before {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.10);
            border-radius: 50%;
            top: -70px; right: -50px;
        }
        .receipt-header::after {
            content: '';
            position: absolute;
            width: 130px; height: 130px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
            bottom: -40px; left: -30px;
        }

        /* ── Checkmark animation ── */
        @keyframes popIn {
            0%  { transform: scale(0.4); opacity: 0; }
            70% { transform: scale(1.12); }
            100%{ transform: scale(1);   opacity: 1; }
        }
        .pop-in { animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

        @keyframes checkmark {
            from { stroke-dashoffset: 80; }
            to   { stroke-dashoffset: 0;  }
        }
        .check-path {
            stroke-dasharray: 80;
            stroke-dashoffset: 80;
            animation: checkmark 0.5s 0.4s ease forwards;
        }

        /* ── Zigzag tear edge ── */
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

        /* ── Dashed dividers ── */
        .dash-line {
            border: none;
            border-top: 2.5px dashed #f1f5f9;
            margin: 14px 0;
        }
        .dash-line-green {
            border: none;
            border-top: 2px dashed #d1fae5;
            margin: 10px 0;
        }

        /* ── Status chip ── */
        .chip-lunas {
            background: #dcfce7;
            color: #065f46;
            border: 1.5px solid #6ee7b7;
            border-radius: 999px;
            padding: 5px 16px;
            font-size: 12px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Item row ── */
        .item-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
        }
        .item-img {
            width: 44px; height: 44px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid #f1f5f9;
            flex-shrink: 0;
        }

        /* ── Barcode strips (decorative) ── */
        .barcode-wrap {
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 48px;
            justify-content: center;
        }
        .barcode-wrap span {
            display: block;
            width: 3px;
            background: #1e293b;
            border-radius: 1px;
        }

        /* ── QRIS badge ── */
        .qris-badge {
            background: #e11d48;
            color: #fff;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 2px;
            padding: 3px 8px;
            border-radius: 5px;
            display: inline-block;
        }

        /* ── Buttons ── */
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 10px 24px -4px rgba(16,185,129,0.38);
            border-radius: 18px;
            transition: all 0.2s;
        }
        .btn-primary:active { transform: scale(0.97); box-shadow: none; }

        .btn-outline-green {
            border: 2px solid #10b981;
            color: #059669;
            border-radius: 18px;
            background: transparent;
            transition: all 0.2s;
        }
        .btn-outline-green:active { background: #ecfdf5; }

        /* ── Animations ── */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up   { animation: fadeUp 0.5s ease forwards; }
        .fade-up-2 { animation: fadeUp 0.5s ease 0.1s forwards; opacity: 0; }
        .fade-up-3 { animation: fadeUp 0.5s ease 0.2s forwards; opacity: 0; }

        /* Print */
        @media print {
            body { background: #fff !important; }
            .no-print { display: none !important; }
            .receipt-card { box-shadow: none; border: 1px solid #e2e8f0; }
        }
    </style>
</head>

<body class="p-5 pb-10 flex flex-col items-center">

@php
    $subtotal   = $order->items->sum(fn($i) => $i->price * $i->qty);
    $service    = $order->total - $subtotal;
    $paidAt     = $order->confirmed_at ?? $order->updated_at ?? now();
    $paidAtFmt  = \Carbon\Carbon::parse($paidAt)->locale('id')->isoFormat('dddd, D MMMM YYYY • HH:mm');
    $txId       = 'QR' . strtoupper(substr(md5($order->id . $order->total), 0, 12));
@endphp

<div class="w-full max-w-sm fade-up">

    {{-- ════════════════════════════════ --}}
    {{--  RECEIPT CARD                    --}}
    {{-- ════════════════════════════════ --}}
    <div class="receipt-card">

        {{-- ── HEADER ── --}}
        <div class="receipt-header relative z-10">
            {{-- Animated checkmark --}}
            <div class="pop-in mx-auto w-20 h-20 rounded-full bg-white/20 backdrop-blur flex items-center justify-center shadow-xl mb-4">
                <svg class="w-11 h-11" viewBox="0 0 44 44" fill="none">
                    <circle cx="22" cy="22" r="20" stroke="rgba(255,255,255,0.5)" stroke-width="2.5" fill="rgba(255,255,255,0.15)"/>
                    <path class="check-path" d="M12 22l8 8 13-14"
                          stroke="#ffffff" stroke-width="3.5"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <h1 class="text-white font-extrabold text-2xl tracking-tight leading-none">Pembayaran Lunas!</h1>
            <p class="text-emerald-100 text-xs mt-1.5">Struk Digital QRIS</p>

            {{-- Total besar --}}
            <div class="mt-5 bg-white/15 backdrop-blur border border-white/30 rounded-2xl px-6 py-4 inline-block relative z-10">
                <p class="text-emerald-100 text-[11px] font-semibold uppercase tracking-widest leading-none mb-1">Total Dibayar</p>
                <p class="text-white text-3xl font-black tracking-tight leading-none">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Zigzag edge --}}
        <div class="zigzag-top"></div>

        {{-- ── BODY ── --}}
        <div class="px-6 pb-6 -mt-2">

            {{-- Meta info grid --}}
            <div class="grid grid-cols-2 gap-3 mt-2 fade-up-2">
                <div class="bg-slate-50 rounded-2xl p-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">No. Antrian</p>
                    <p class="font-black text-slate-800 text-base leading-none">{{ $order->queue_number }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Nomor Meja</p>
                    <p class="font-black text-slate-800 text-base leading-none">{{ $order->table_number ?? '-' }}</p>
                </div>
            </div>

            {{-- Status lunas --}}
            <div class="flex items-center justify-between mt-4 fade-up-2">
                <div class="flex items-center gap-2">
                    <span class="qris-badge">QRIS</span>
                    <span class="text-xs text-slate-500 font-medium">by Bank Indonesia</span>
                </div>
                <div class="chip-lunas">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                    LUNAS
                </div>
            </div>

            {{-- Waktu & TX ID --}}
            <div class="mt-3 bg-emerald-50 border border-emerald-100 rounded-2xl p-3.5 space-y-2 fade-up-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500 font-medium">Waktu Bayar</span>
                    <span class="font-bold text-slate-700">{{ $paidAtFmt }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500 font-medium">ID Transaksi</span>
                    <span class="font-bold text-slate-700 font-mono tracking-wide">{{ $txId }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500 font-medium">Merchant</span>
                    <span class="font-bold text-slate-700">Cafe Tugas Akhir</span>
                </div>
            </div>

            <hr class="dash-line mt-4">

            {{-- ── ITEM LIST ── --}}
            <h2 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1 fade-up-2">Detail Pesanan</h2>
            <div class="space-y-0 fade-up-3">
                @foreach($order->items as $item)
                <div class="item-row">
                    @php
                        $imgSrc = $item->menu?->image
                            ? asset('storage/' . $item->menu->image)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($item->name) . '&background=d1fae5&color=059669&bold=true&size=44';
                    @endphp
                    <img src="{{ $imgSrc }}" alt="{{ $item->name }}" class="item-img"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->name) }}&background=d1fae5&color=059669&bold=true&size=44'">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate leading-tight">{{ $item->name }}</p>
                        @if($item->notes)
                        <p class="text-[10px] text-slate-400 italic leading-none mt-0.5">{{ $item->notes }}</p>
                        @endif
                        <p class="text-xs text-slate-500 mt-0.5">× {{ $item->qty }}
                            <span class="text-slate-400 ml-1">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </p>
                    </div>
                    <p class="text-sm font-extrabold text-emerald-600 flex-shrink-0">
                        Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                    </p>
                </div>
                @if(!$loop->last)<hr class="dash-line-green my-0">@endif
                @endforeach
            </div>

            <hr class="dash-line mt-2">

            {{-- Rincian biaya --}}
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

            {{-- Total --}}
            <div class="flex items-center justify-between fade-up-3">
                <span class="font-black text-slate-800 text-sm">TOTAL BAYAR</span>
                <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>

            <hr class="dash-line mt-3">

            {{-- Barcode dekoratif --}}
            <div class="text-center mt-4 fade-up-3">
                <div class="barcode-wrap mb-2">
                    @php
                        $heights = [36,26,44,30,48,22,42,28,40,36,48,26,38,44,22,36,44,28,46,26,40,32,48,24,38,42,30,44,26,48];
                    @endphp
                    @foreach($heights as $h)
                    <span style="height:{{ $h }}px;"></span>
                    @endforeach
                </div>
                <p class="text-[10px] font-mono text-slate-400 tracking-widest">{{ $txId }}</p>
            </div>

            {{-- Info pesanan diproses --}}
            <div class="mt-5 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex gap-3 items-start fade-up-3">
                <span class="text-2xl flex-shrink-0">🍽️</span>
                <div>
                    <p class="text-sm font-bold text-emerald-800">Pesanan Sedang Diproses</p>
                    <p class="text-xs text-emerald-700 mt-1 leading-relaxed">
                        Pembayaran QRIS sudah tercatat. Dapur sedang menyiapkan pesananmu. Nomor antrian <strong>{{ $order->queue_number }}</strong>.
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════ --}}
    {{--  ACTION BUTTONS                  --}}
    {{-- ════════════════════════════════ --}}
    <div class="mt-5 space-y-3 no-print fade-up-3">
        <button onclick="window.print()"
                class="btn-primary w-full py-4 text-white font-black text-sm flex items-center justify-center gap-2">
            📄 Simpan / Cetak Struk
        </button>
        <button
            onclick="localStorage.removeItem('cart'); localStorage.removeItem('checkoutCart'); window.location.href='/customer/home';"
            class="btn-outline-green w-full py-3.5 font-bold text-sm flex items-center justify-center gap-2">
            🏠 Kembali ke Menu
        </button>
    </div>

    <p class="text-center text-[11px] text-slate-400 mt-5 leading-relaxed no-print">
        Simpan atau screenshot struk ini<br>sebagai bukti pembayaran.
    </p>

</div>

</body>
</html>