<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tagihan Cash – {{ $order->queue_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { background: linear-gradient(135deg, #fff7ed 0%, #ffffff 50%, #fef3c7 100%); min-height: 100vh; }

        /* ── Bill Card ── */
        .bill-card {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 24px 64px rgba(249,115,22,0.15), 0 4px 16px rgba(0,0,0,0.06);
            overflow: hidden;
            position: relative;
        }

        /* ── Header orange gradient ── */
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

        /* ── Dashed divider ── */
        .dash-line {
            border: none;
            border-top: 2.5px dashed #f1f5f9;
            margin: 14px 0;
        }
        .dash-line-orange {
            border: none;
            border-top: 2px dashed #fed7aa;
            margin: 14px 0;
        }

        /* ── Queue number badge ── */
        .queue-badge {
            background: rgba(255,255,255,0.22);
            border: 2px solid rgba(255,255,255,0.45);
            border-radius: 18px;
            padding: 10px 22px;
            display: inline-block;
            backdrop-filter: blur(8px);
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

        /* ── Status chip ── */
        .chip-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1.5px solid #fcd34d;
            border-radius: 999px;
            padding: 4px 14px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* ── Pulse dot ── */
        @keyframes blink { 0%,100%{ opacity:1; } 50%{ opacity:0.3; } }
        .blink { animation: blink 1.4s ease-in-out infinite; }

        /* ── Button ── */
        .btn-kasir {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            box-shadow: 0 10px 24px -4px rgba(249,115,22,0.38);
            transition: all 0.2s;
            border-radius: 18px;
        }
        .btn-kasir:active { transform: scale(0.97); box-shadow: none; }

        .btn-outline {
            border: 2px solid #f97316;
            color: #ea580c;
            border-radius: 18px;
            background: transparent;
            transition: all 0.2s;
        }
        .btn-outline:active { background: #fff7ed; }

        /* ── Animations ── */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .fade-up-2 { animation: fadeUp 0.5s ease 0.1s forwards; opacity: 0; }
        .fade-up-3 { animation: fadeUp 0.5s ease 0.2s forwards; opacity: 0; }

        @keyframes popIn { 0%{ transform:scale(0.7); opacity:0; } 70%{ transform:scale(1.08); } 100%{ transform:scale(1); opacity:1; } }
        .pop-in { animation: popIn 0.55s cubic-bezier(0.34,1.56,0.64,1) forwards; }

        /* Print styles */
        @media print {
            body { background: #fff !important; }
            .no-print { display: none !important; }
            .bill-card { box-shadow: none; border: 1px solid #e2e8f0; }
        }
    </style>
</head>

<body class="p-5 pb-10 flex flex-col items-center">

@php
    $subtotal = $order->items->sum(fn($i) => $i->price * $i->qty);
    $service  = $order->total - $subtotal;
    $now      = now()->locale('id')->isoFormat('dddd, D MMMM YYYY • HH:mm');
@endphp

<div class="w-full max-w-sm fade-up">

    {{-- ════════════════════════════════ --}}
    {{--  BILL CARD                       --}}
    {{-- ════════════════════════════════ --}}
    <div class="bill-card">

        {{-- ── HEADER ── --}}
        <div class="bill-header text-center relative z-10">
            {{-- Icon --}}
            <div class="pop-in mx-auto w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center mb-3 shadow-inner">
                <span class="text-3xl">🧾</span>
            </div>

            <h1 class="text-white font-extrabold text-xl tracking-tight leading-none">Tagihan Pembayaran</h1>
            <p class="text-orange-100 text-xs mt-1">Bayar Tunai ke Kasir</p>

            {{-- Queue number --}}
            <div class="queue-badge mt-5 relative z-10">
                <p class="text-white/70 text-[10px] font-semibold uppercase tracking-widest leading-none mb-1">Nomor Antrian</p>
                <p class="text-white text-3xl font-black tracking-wider leading-none">{{ $order->queue_number }}</p>
            </div>
        </div>

        {{-- Zigzag edge --}}
        <div class="zigzag-top"></div>

        {{-- ── BODY ── --}}
        <div class="px-6 pb-6 -mt-2">

            {{-- Meta info --}}
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

            {{-- Tanggal & waktu --}}
            <p class="text-center text-[11px] text-slate-400 mt-4 font-medium">{{ $now }}</p>

            <hr class="dash-line">

            {{-- ── ITEM LIST ── --}}
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
                @if(!$loop->last)
                <hr class="dash-line-orange my-0">
                @endif
                @endforeach
            </div>

            <hr class="dash-line mt-3">

            {{-- ── RINCIAN BIAYA ── --}}
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

            {{-- TOTAL --}}
            <div class="flex items-center justify-between fade-up-3">
                <span class="font-black text-slate-800 text-sm">TOTAL BAYAR</span>
                <span class="text-2xl font-black text-orange-500">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>

            <hr class="dash-line mt-3">

            {{-- ── BARCODE DEKORATIF ── --}}
            <div class="text-center mt-4 fade-up-3">
                <div class="barcode-wrap mb-2">
                    @php
                        $heights = [38,28,44,32,48,24,40,28,42,36,48,28,38,44,24,36,42,30,46,28,40,34,48,26,36,44,30,42,28,46];
                    @endphp
                    @foreach($heights as $h)
                    <span style="height:{{ $h }}px;"></span>
                    @endforeach
                </div>
                <p class="text-[10px] font-mono text-slate-500 tracking-widest">{{ $order->queue_number }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            {{-- ── INFO INSTRUKSI ── --}}
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

        </div>{{-- /body --}}
    </div>{{-- /bill-card --}}

    {{-- ════════════════════════════════ --}}
    {{--  ACTION BUTTONS                  --}}
    {{-- ════════════════════════════════ --}}
    <div class="mt-5 space-y-3 no-print fade-up-3">

        {{-- Tombol utama: simpan/screenshot --}}
        <button onclick="window.print()"
                class="btn-kasir w-full py-4 text-white font-black text-sm flex items-center justify-center gap-2">
            📄 Simpan / Cetak Tagihan
        </button>

        {{-- Tombol kembali ke menu --}}
        <button
            onclick="localStorage.removeItem('cart'); localStorage.removeItem('checkoutCart'); window.location.href='/customer/home';"
            class="btn-outline w-full py-3.5 font-bold text-sm flex items-center justify-center gap-2">
            🏠 Kembali ke Menu
        </button>
    </div>

    {{-- Footer note --}}
    <p class="text-center text-[11px] text-slate-400 mt-5 leading-relaxed no-print">
        Simpan atau screenshot tagihan ini<br>sebelum menutup halaman.
    </p>

</div>

</body>
</html>