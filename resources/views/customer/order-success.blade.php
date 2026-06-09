<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Pesanan Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background: #fff7ed; min-height: 100vh; }
        @keyframes popIn { 0%{transform:scale(0);opacity:0;} 70%{transform:scale(1.1);} 100%{transform:scale(1);opacity:1;} }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px);} to{opacity:1;transform:translateY(0);} }
        .pop { animation: popIn 0.5s cubic-bezier(.34,1.56,.64,1) forwards; }
        .fade-up { animation: fadeUp 0.5s ease-out forwards; }
        .fade-up-2 { animation: fadeUp 0.5s 0.15s ease-out both; }
        .fade-up-3 { animation: fadeUp 0.5s 0.3s ease-out both; }
        .dash { border:none; border-top:2px dashed #fed7aa; }
        .item-row { transition:all .2s; }
        .item-row:active { background:#fff7ed; border-radius:16px; }
        .btn-home {
            width:100%; padding:17px; background:linear-gradient(135deg,#f97316,#ea580c); color:#fff;
            border:none; border-radius:20px; font-size:16px; font-weight:800; cursor:pointer;
            display:flex; align-items:center; justify-content:center; gap:8px;
            box-shadow:0 8px 24px -4px rgba(249,115,22,.4); transition:all .2s;
        }
        .btn-home:active { transform:scale(.97); box-shadow:none; }
    </style>
</head>
@php
    $pm = $order->payment_method;
    $pmLabel = match($pm) { 'cash' => 'Tunai (Cash)', default => ucfirst($pm) };
    $pmIcon  = match($pm) { 'cash' => 'cash', default => 'online' };
@endphp
<body class="flex flex-col items-center justify-start p-4 pb-10" style="padding-top:max(env(safe-area-inset-top),24px)">

<div class="w-full max-w-sm">

    <!-- Success icon -->
    <div class="text-center py-8 fade-up">
        <div class="mx-auto w-24 h-24 rounded-full bg-orange-100 flex items-center justify-center pop">
            <svg class="w-12 h-12 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="mt-5 font-extrabold text-2xl text-gray-900">Pesanan Diterima!</h1>
        <p class="mt-1.5 text-gray-500 text-sm">Order <strong class="text-orange-500">{{ $order->queue_number }}</strong> sedang diproses</p>
    </div>

    <!-- Summary card -->
    <div class="bg-white rounded-3xl shadow-sm border border-orange-100 p-5 fade-up-2 mb-3">

        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nomor Antrian</p>
                <p class="font-extrabold text-3xl text-gray-900 mt-0.5">{{ $order->queue_number }}</p>
            </div>
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="6" width="18" height="3" rx="1.5"/><path stroke-linecap="round" d="M5 9v9m14-9v9M9 9v5m6-5v5"/>
                </svg>
            </div>
        </div>

        <hr class="dash mb-4">

        <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="bg-gray-50 rounded-2xl p-3">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Meja</p>
                <p class="font-extrabold text-gray-900 text-base">{{ $order->table_number ?? '-' }}</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-3">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pembayaran</p>
                <p class="font-extrabold text-gray-900 text-sm">{{ $pmLabel }}</p>
            </div>
            <div class="bg-orange-50 rounded-2xl p-3 col-span-2">
                <p class="text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-1">Total</p>
                <p class="font-extrabold text-orange-500 text-xl">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Instruction -->
    @if($pm === 'cash')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3.5 flex gap-3 items-start mb-3 fade-up-2">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
        </div>
        <div>
            <p class="font-bold text-amber-800 text-sm">Tunjukkan ke Kasir</p>
            <p class="text-amber-700 text-xs mt-0.5 leading-relaxed">Tunjukkan nomor antrian <strong>{{ $order->queue_number }}</strong> ke kasir dan selesaikan pembayaran tunai di tempat.</p>
        </div>
    </div>
    @endif

    <!-- Items -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 px-4 py-4 mb-5 fade-up-3">
        <div class="flex items-center justify-between mb-3">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Item Pesanan</p>
            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">{{ $order->items->count() }} item</span>
        </div>
        <div class="space-y-2.5">
            @foreach($order->items as $item)
            <div class="item-row flex items-center justify-between py-1">
                <div class="flex-1 min-w-0 pr-3">
                    <p class="font-bold text-sm text-gray-900 truncate">{{ $item->menu->name ?? $item->name ?? 'Menu' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">× {{ $item->qty }}@if($item->notes) · <em>{{ $item->notes }}</em>@endif</p>
                </div>
                <p class="font-extrabold text-sm text-orange-500 flex-shrink-0">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <button class="btn-home fade-up-3" onclick="backToHome()">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
        Kembali ke Menu
    </button>

</div>

<script>
localStorage.removeItem('cart');
localStorage.removeItem('checkoutCart');
function backToHome() {
    sessionStorage.setItem('orderSuccess', '1');
    window.location.href = '/customer/home';
}
// Saat back dari halaman sukses, langsung ke home
window.addEventListener('popstate', function() {
    window.location.replace('/customer/home');
});
history.pushState(null, '', location.href);
var _sx=0;
window.addEventListener('touchstart',e=>{_sx=e.touches[0].clientX;},{passive:true});
window.addEventListener('touchmove',e=>{if(_sx<40)e.preventDefault();},{passive:false});
</script>
</body>
</html>
