<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Struk Pembayaran – {{ $order->queue_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 50%, #ecfdf5 100%); min-height: 100vh; }

        .receipt-card {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 24px 64px rgba(16,185,129,0.15), 0 4px 16px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .divider-dashed {
            border: none;
            border-top: 2px dashed #e5e7eb;
            margin: 0;
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }

        @keyframes checkmark {
            from { stroke-dashoffset: 100; }
            to   { stroke-dashoffset: 0; }
        }
        .check-path {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.6s 0.3s ease forwards;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }
        .bounce { animation: bounce 1.5s infinite; }

        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px 20px;
            border-radius: 16px;
            font-weight: 800;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            text-decoration: none;
        }
        .btn-action:active { transform: scale(0.97); }

        .btn-print {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            box-shadow: 0 8px 20px -4px rgba(99,102,241,0.45);
        }
        .btn-home {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 8px 20px -4px rgba(16,185,129,0.45);
        }

        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .receipt-card { box-shadow: none !important; border-radius: 0 !important; border: 1px solid #e5e7eb; }
            .fade-up { animation: none !important; }
            .bounce  { animation: none !important; }
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
        'bca'         => ['icon' => '🏦', 'color' => '#003D8F', 'label' => 'BCA Virtual Account'],
        'bni'         => ['icon' => '🏦', 'color' => '#FF6600', 'label' => 'BNI Virtual Account'],
        'bri'         => ['icon' => '🏦', 'color' => '#00529C', 'label' => 'BRI Virtual Account'],
        'mandiri'     => ['icon' => '🏦', 'color' => '#003087', 'label' => 'Mandiri Bill'],
        'permata'     => ['icon' => '🏦', 'color' => '#E31837', 'label' => 'Permata Virtual Account'],
        'credit_card' => ['icon' => '💳', 'color' => '#6366f1', 'label' => 'Kartu Kredit'],
        'midtrans'    => ['icon' => '💳', 'color' => '#6366f1', 'label' => 'Midtrans'],
    ];
    $methodInfo   = $methodIcons[$order->payment_method] ?? ['icon' => '💳', 'color' => '#6366f1', 'label' => strtoupper($order->payment_method)];
    $subtotal     = $order->items->sum(fn($i) => $i->price * $i->qty);
    $biayaLayanan = $order->total - $subtotal;
@endphp

<div class="w-full max-w-sm fade-up">
    <div class="receipt-card">

        {{-- HEADER SUKSES --}}
        <div class="px-5 pt-7 pb-5 text-center"
             style="background: linear-gradient(135deg, {{ $methodInfo['color'] }}, {{ $methodInfo['color'] }}cc);">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 bounce">
                <svg class="w-9 h-9" viewBox="0 0 40 40" fill="none">
                    <circle cx="20" cy="20" r="18" stroke="white" stroke-width="2.5" fill="rgba(255,255,255,0.2)"/>
                    <path class="check-path" d="M11 20l7 7 11-13" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="text-white font-black text-xl">Pembayaran Berhasil!</h1>
            <p class="text-white/70 text-xs mt-1">via {{ $methodInfo['icon'] }} {{ $methodInfo['label'] }}</p>
        </div>

        {{-- NOMOR ANTRIAN --}}
        <div class="bg-green-50 px-5 py-4 text-center">
            <p class="text-xs text-gray-500 mb-1">Nomor Antrian</p>
            <p class="text-4xl font-black text-green-600 tracking-widest">{{ $order->queue_number }}</p>
            @if($order->customer_name)
            <p class="text-xs text-gray-500 mt-1 font-semibold">{{ $order->customer_name }}</p>
            @endif
            @if($order->table_number)
            <p class="text-xs text-gray-400 mt-0.5">Meja {{ $order->table_number }}</p>
            @endif
        </div>

        <hr class="divider-dashed mx-5">

        {{-- HEADER STRUK --}}
        <div class="px-5 pt-4 pb-2 text-center">
            <p class="font-black text-gray-800 text-base">Cafe Tugas Akhir</p>
            <p class="text-xs text-gray-400">Batam, Kepulauan Riau</p>
            <p class="text-[10px] text-gray-400 mt-1">
                {{ now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
            </p>
        </div>

        <hr class="divider-dashed mx-5">

        {{-- DETAIL PESANAN --}}
        <div class="px-5 py-4 space-y-2">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Detail Pesanan</p>
            @foreach($order->items as $item)
            <div class="flex justify-between items-start text-sm">
                <div class="flex-1">
                    <p class="font-semibold text-gray-700">{{ $item->name }}</p>
                    @if($item->notes)
                    <p class="text-xs text-gray-400 italic">* {{ $item->notes }}</p>
                    @endif
                    <p class="text-xs text-gray-400">{{ $item->qty }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
                <div class="text-right ml-3 shrink-0">
                    <p class="font-bold text-gray-700">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <hr class="divider-dashed mx-5">

        {{-- RINCIAN HARGA --}}
        <div class="px-5 py-4 space-y-1.5">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            @if($biayaLayanan > 0)
            <div class="flex justify-between text-sm text-gray-500">
                <span>Biaya Layanan</span>
                <span>Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="border-t border-gray-200 pt-2 mt-1 flex justify-between items-center">
                <span class="font-bold text-gray-700 text-sm">Total Dibayar</span>
                <span class="font-black text-xl text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-xs text-gray-400 mt-0.5">
                <span>Metode Pembayaran</span>
                <span class="font-semibold text-gray-600">{{ $methodInfo['icon'] }} {{ $methodInfo['label'] }}</span>
            </div>
            <div class="flex justify-between items-center text-xs text-gray-400">
                <span>Status</span>
                @if(in_array($order->status, ['process', 'done', 'completed']))
                    <span class="font-bold text-green-600">✅ LUNAS</span>
                @elseif($order->status === 'waiting_payment')
                    <span class="font-bold text-amber-500">⏳ MENUNGGU PEMBAYARAN</span>
                @elseif($order->status === 'cancelled')
                    <span class="font-bold text-red-500">❌ DIBATALKAN</span>
                @else
                    <span class="font-bold text-blue-500">🔄 DIPROSES</span>
                @endif
            </div>
            <div class="flex justify-between items-center text-xs text-gray-400">
                <span>ID Pesanan</span>
                <span class="font-mono text-gray-500">#{{ $order->id }}</span>
            </div>
            @if($order->midtrans_order_id)
            <div class="flex justify-between items-center text-xs text-gray-400">
                <span>ID Transaksi</span>
                <span class="font-mono text-gray-500 text-[10px]">{{ $order->midtrans_order_id }}</span>
            </div>
            @endif
        </div>

        <hr class="divider-dashed mx-5">

        {{-- INFO STATUS --}}
        <div class="px-5 py-4">
            @if(in_array($order->status, ['process', 'done', 'delivered', 'completed']))
            <div class="bg-green-50 rounded-xl p-3 text-center">
                <p class="text-sm font-bold text-green-700">🔥 Pesanan sudah masuk dapur!</p>
                <p class="text-xs text-green-600 mt-0.5">Pesananmu sedang dimasak. Harap tunggu ya 😊</p>
            </div>
            @elseif($order->status === 'waiting_payment')
            <div class="bg-amber-50 rounded-xl p-3 text-center">
                <p class="text-sm font-bold text-amber-700">⏳ Menunggu konfirmasi pembayaran</p>
                <p class="text-xs text-amber-600 mt-0.5">Selesaikan pembayaranmu, pesanan akan otomatis diproses.</p>
            </div>
            @else
            <div class="bg-blue-50 rounded-xl p-3 text-center">
                <p class="text-sm font-bold text-blue-700">🔄 Pesanan sedang diverifikasi</p>
                <p class="text-xs text-blue-600 mt-0.5">Mohon tunggu sebentar.</p>
            </div>
            @endif
        </div>

        <hr class="divider-dashed mx-5">

        {{-- TOMBOL AKSI --}}
        <div class="px-5 py-4 space-y-2.5 no-print">
            <button onclick="cetakStruk()" class="btn-action btn-print">
                🖨️ Cetak / Simpan Struk
            </button>
            <a href="{{ url('/customer/home') }}" class="btn-action btn-home">
                🏠 Kembali ke Menu
            </a>
        </div>

        {{-- FOOTER --}}
        <div class="px-5 pb-5 flex items-center justify-between text-[10px] text-gray-300">
            <span>Diproses oleh Midtrans</span>
            <span>Terima kasih! 🙏</span>
        </div>
    </div>

    <p class="text-center text-[11px] text-slate-400 mt-3 no-print">
        Simpan struk ini sebagai bukti pembayaran
    </p>
</div>

<script>
function cetakStruk() {
    const noPrint = document.querySelectorAll('.no-print');
    noPrint.forEach(el => el.style.display = 'none');
    window.print();
    noPrint.forEach(el => el.style.display = '');
}
</script>
</body>
</html>