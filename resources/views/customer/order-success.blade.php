<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Berhasil</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body { font-family: 'Plus Jakarta Sans', sans-serif; }

.glass-card {
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(18px);
    border: 1px solid rgba(255,255,255,0.6);
}

@keyframes pulse-green {
    0%   { transform: scale(1);    box-shadow: 0 0 0 0 rgba(34,197,94,0.45); }
    70%  { transform: scale(1.04); box-shadow: 0 0 0 20px rgba(34,197,94,0); }
    100% { transform: scale(1);    box-shadow: 0 0 0 0 rgba(34,197,94,0); }
}
.success-ring { animation: pulse-green 2.2s infinite; }

@keyframes fadeUp {
    from { opacity:0; transform: translateY(18px); }
    to   { opacity:1; transform: translateY(0); }
}
.fade-up { animation: fadeUp 0.6s ease forwards; }

/* Stagger children */
.fade-up:nth-child(1) { animation-delay: 0s; }
.fade-up:nth-child(2) { animation-delay: .1s; }
.fade-up:nth-child(3) { animation-delay: .2s; }

.item-card {
    transition: all 0.2s;
    border: 1px solid #f1f5f9;
}
.item-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); transform: translateY(-1px); }

.btn-back {
    background: linear-gradient(135deg, #f97316, #ea580c);
    box-shadow: 0 10px 20px -4px rgba(249,115,22,0.35);
    transition: all 0.25s;
}
.btn-back:hover { filter: brightness(1.07); transform: scale(1.02); }
.btn-back:active { transform: scale(0.98); }
</style>
</head>

<body class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-100 flex items-center justify-center p-5">

@php
    $pm = $order->payment_method;

    // Label tampil di kartu
    $pmLabel = match($pm) {
        'cash' => 'Tunai (Cash)',
        'qris' => 'QRIS',
        'bank' => 'Transfer Bank',
        default => ucfirst($pm),
    };

    // Icon metode
    $pmIcon = match($pm) {
        'cash' => '💵',
        'qris' => '📱',
        'bank' => '🏦',
        default => '💳',
    };

    // Status badge
    // QRIS → sudah lunas (konfirmasi di halaman QRIS sebelumnya)
    // Cash/bank/lainnya → pending (kasir yang proses)
    $isLunas = $pm === 'qris';
    $badgeClass = $isLunas
        ? 'bg-emerald-100 text-emerald-700'
        : 'bg-amber-100 text-amber-700';
    $badgeText  = $isLunas ? '✅ Lunas' : '⏳ Menunggu Pembayaran';
@endphp

<div class="w-full max-w-md fade-up">

    <div class="glass-card rounded-[32px] shadow-2xl overflow-hidden">

        {{-- TOP GRADIENT --}}
        <div class="relative bg-gradient-to-r from-orange-500 to-orange-600 px-8 pt-10 pb-16 text-center">
            <div class="absolute top-0 left-0 w-40 h-40 bg-white/10 rounded-full -translate-x-20 -translate-y-20 pointer-events-none"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/10 rounded-full translate-x-10 translate-y-10 pointer-events-none"></div>

            <div class="relative z-10">
                <div class="success-ring mx-auto w-24 h-24 rounded-full bg-white flex items-center justify-center shadow-xl">
                    <span class="text-5xl">✅</span>
                </div>
                <h1 class="mt-6 text-3xl font-extrabold text-white tracking-tight">Pesanan Berhasil!</h1>
                <p class="mt-2 text-orange-100 text-sm leading-relaxed">
                    Order <strong>{{ $order->queue_number }}</strong> diterima dan sedang diproses 🍽️
                </p>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="px-7 pb-7 -mt-8 relative z-20">

            {{-- SUMMARY CARD --}}
            <div class="bg-white rounded-3xl shadow-lg border border-orange-100 p-5 space-y-4 fade-up">

                {{-- MEJA --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nomor Meja</p>
                        <h3 class="mt-1 text-lg font-black text-gray-800">{{ $order->table_number ?? '-' }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center text-2xl">🍴</div>
                </div>

                <div class="border-t border-dashed border-gray-200"></div>

                {{-- METODE PEMBAYARAN (dinamis) --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Metode Pembayaran</p>
                        <h3 class="mt-1 text-base font-bold text-gray-800 flex items-center gap-2">
                            <span>{{ $pmIcon }}</span> {{ $pmLabel }}
                        </h3>
                    </div>
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                        {{ $badgeText }}
                    </span>
                </div>

                <div class="border-t border-dashed border-gray-200"></div>

                {{-- TOTAL --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pembayaran</p>
                        <h3 class="mt-1 text-2xl font-extrabold text-orange-500">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-orange-500 text-white flex items-center justify-center text-2xl shadow-lg">
                        💳
                    </div>
                </div>

            </div>

            {{-- INFO INSTRUKSI (per metode) --}}
            @if($pm === 'cash')
            <div class="mt-5 bg-orange-50 border border-orange-100 rounded-2xl p-4 flex gap-3 items-start fade-up">
                <span class="text-2xl">💵</span>
                <div>
                    <p class="text-sm font-bold text-orange-800">Bayar ke Kasir</p>
                    <p class="text-xs text-orange-600 mt-1 leading-relaxed">
                        Tunjukkan nomor pesanan <strong>{{ $order->queue_number }}</strong> ke kasir untuk menyelesaikan pembayaran tunai.
                    </p>
                </div>
            </div>
            @elseif($pm === 'bank')
            <div class="mt-5 bg-blue-50 border border-blue-100 rounded-2xl p-4 flex gap-3 items-start fade-up">
                <span class="text-2xl">🏦</span>
                <div>
                    <p class="text-sm font-bold text-blue-800">Selesaikan Transfer</p>
                    <p class="text-xs text-blue-600 mt-1 leading-relaxed"> Lakukan transfer ke rekening yang tertera dan konfirmasikan ke kasir dengan nomor pesanan <strong>{{ $order->queue_number }}</strong></p>
                </div>
            </div>
            @elseif($pm === 'qris')
            <div class="mt-5 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex gap-3 items-start fade-up">
                <span class="text-2xl">✅</span>
                <div>
                    <p class="text-sm font-bold text-emerald-800">Pembayaran QRIS Dikonfirmasi</p>
                    <p class="text-xs text-emerald-600 mt-1 leading-relaxed">
                        Terima kasih! Pembayaran QRIS sudah tercatat. Pesananmu sedang diproses dapur.
                    </p>
                </div>
            </div>
            @else
            <div class="mt-5 bg-slate-50 border border-slate-100 rounded-2xl p-4 flex gap-3 items-start fade-up">
                <span class="text-2xl">💳</span>
                <div>
                    <p class="text-sm font-bold text-slate-700">Selesaikan Pembayaran</p>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Hubungi kasir untuk menyelesaikan pembayaran dengan nomor pesanan <strong>{{ $order->queue_number }}</strong>.
                    </p>
                </div>
            </div>
            @endif

            {{-- ITEM PESANAN --}}
            <div class="mt-6 fade-up">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xs font-extrabold text-gray-500 uppercase tracking-wider">Item Pesanan</h2>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full font-semibold">
                        {{ $order->items->count() }} item
                    </span>
                </div>

                <div class="space-y-2.5">
                    @foreach($order->items as $item)
                    <div class="item-card bg-white rounded-2xl p-3.5 flex items-center justify-between">
                        <div class="flex-1 min-w-0 pr-3">
                            <h3 class="font-bold text-gray-800 text-sm truncate">
                                {{ $item->menu->name ?? $item->name ?? 'Menu #'.$item->menu_id }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                × {{ $item->qty }}
                                @if($item->notes)
                                <span class="ml-1 italic">· {{ $item->notes }}</span>
                                @endif
                            </p>
                        </div>
                        <p class="text-sm font-extrabold text-orange-500 flex-shrink-0">
                            Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- TOMBOL KEMBALI --}}
            <button
                onclick="
                    localStorage.removeItem('cart');
                    localStorage.removeItem('checkoutCart');
                    window.location.href='/customer/home';
                "
                class="btn-back mt-7 w-full text-white py-4 rounded-2xl font-extrabold text-sm tracking-wide">
                🏠 Kembali ke Menu
            </button>

        </div>
    </div>
</div>

</body>
</html>