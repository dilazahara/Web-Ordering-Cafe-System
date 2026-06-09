<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover, user-scalable=no">
    <title>{{ $menu->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { background: #fff; overflow-x: hidden; }

        /* Hero image */
        .hero { position: relative; width: 100%; height: 45vw; max-height: 280px; min-height: 200px; overflow: hidden; background: #f3f4f6; }
        .hero img { width: 100%; height: 100%; object-fit: cover; }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,.35) 0%, transparent 30%, transparent 65%, rgba(0,0,0,.55) 100%); }

        /* Back btn */
        .btn-back {
            position: absolute; top: max(env(safe-area-inset-top), 12px); left: 16px; z-index: 10;
            width: 44px; height: 44px;
            background: rgba(255,255,255,.92); backdrop-filter: blur(10px);
            border-radius: 14px; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 12px rgba(0,0,0,.15);
        }
        .btn-back:active { transform: scale(0.90); }

        /* Expand btn */
        .btn-expand {
            position: absolute; bottom: 14px; right: 14px;
            width: 38px; height: 38px;
            background: rgba(255,255,255,.85); backdrop-filter: blur(8px);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
        }

        /* Content */
        .content { background: #fff; border-radius: 28px 28px 0 0; margin-top: -20px; position: relative; padding: 24px 16px 200px; }

        /* Badge */
        .badge-req  { background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px; text-transform:uppercase; letter-spacing:.04em; }
        .badge-opt  { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px; text-transform:uppercase; letter-spacing:.04em; }
        .badge-max  { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px; text-transform:uppercase; letter-spacing:.04em; }

        /* Addon row */
        .addon-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 13px 0; border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
        }
        .addon-row:last-child { border-bottom: none; }
        .addon-row:active { background: #fff7ed; margin: 0 -16px; padding-left: 16px; padding-right: 16px; border-radius: 12px; }

        /* Custom radio/checkbox */
        input[type=radio], input[type=checkbox] {
            appearance: none; -webkit-appearance: none;
            width: 24px; height: 24px;
            border: 2.5px solid #d1d5db;
            background: #fff;
            cursor: pointer; transition: all 0.2s;
            position: relative; flex-shrink: 0;
        }
        input[type=radio] { border-radius: 50%; }
        input[type=checkbox] { border-radius: 8px; }
        input[type=radio]:checked, input[type=checkbox]:checked { background: #f97316; border-color: #f97316; }
        input[type=radio]:checked::after {
            content: ''; position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 9px; height: 9px; border-radius: 50%; background: white;
        }
        input[type=checkbox]:checked::after {
            content: ''; position: absolute; top: 5px; left: 8px;
            width: 6px; height: 10px;
            border: 2.5px solid white; border-top: none; border-left: none;
            transform: rotate(45deg);
        }

        /* Qty */
        .qty-row { display:flex; align-items:center; justify-content:space-between; background:#f8fafc; border-radius:18px; padding:14px 16px; }
        .qty-ctrl { display:flex; align-items:center; gap:4px; }
        .q-btn {
            width: 42px; height: 42px;
            border-radius: 14px; border: none;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 500; cursor: pointer; transition: all 0.15s;
        }
        .q-btn.minus { background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.08); color: #374151; }
        .q-btn.minus:active { background: #f1f5f9; transform: scale(.90); }
        .q-btn.plus  { background: #f97316; color: #fff; }
        .q-btn.plus:active { background: #ea580c; transform: scale(.90); }

        /* Bottom bar */
        .bottom-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: #fff;
            padding: 16px 16px max(env(safe-area-inset-bottom), 20px);
            border-top: 1px solid #f0f0f0;
            z-index: 50;
        }
        .btn-addcart {
            width: 100%; padding: 17px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff; border: none; border-radius: 20px;
            font-size: 16px; font-weight: 800;
            display: flex; align-items: center; justify-content: space-between;
            cursor: pointer; transition: all 0.2s;
            box-shadow: 0 8px 24px -4px rgba(249,115,22,.4);
        }
        .btn-addcart:active { transform: scale(0.97); box-shadow: none; }

        /* Image modal */
        #imgModal {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.92); z-index: 200;
            align-items: center; justify-content: center; padding: 20px;
        }
        #imgModal.open { display: flex; }
        #imgModal img { max-width: 100%; max-height: 82vh; border-radius: 16px; }

        /* Textarea */
        .note-input { width:100%; background:#f4f6f9; border:2px solid transparent; border-radius:14px; padding:12px 14px; font-size:14px; resize:none; transition:border-color .2s, background .2s; }
        .note-input:focus { background:#fff; border-color:#f97316; outline:none; }

        /* Toast */
        #toastWrap { position:fixed; top:16px; left:50%; transform:translateX(-50%); z-index:9999; display:flex; flex-direction:column; gap:8px; pointer-events:none; width:max-content; max-width:calc(100vw - 32px); }
        .toast { display:flex; align-items:center; gap:8px; padding:10px 16px; border-radius:14px; font-size:13px; font-weight:600; box-shadow:0 4px 20px rgba(0,0,0,.15); opacity:0; transform:translateY(-8px) scale(.95); transition:all .25s; background:#111827; color:#fff; }
        .toast.show { opacity:1; transform:translateY(0) scale(1); }
        .toast.warn  { background:#f59e0b; }
        .toast.err   { background:#ef4444; }

        @keyframes slideUp { from{opacity:0;transform:translateY(20px);} to{opacity:1;transform:translateY(0);} }
        .slide-up { animation: slideUp 0.4s ease-out; }
    </style>
</head>
<body>

<!-- Hero -->
<div class="hero">
    <img src="/storage/{{ $menu->image }}" alt="{{ $menu->name }}" id="heroImg">
    <div class="hero-overlay"></div>
    <button class="btn-back" onclick="window.location.href='/customer/home'">
        <svg class="w-5 h-5 text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button class="btn-expand" onclick="openImg()">
        <svg class="w-4 h-4 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/>
        </svg>
    </button>
</div>

<!-- Content -->
<div class="content slide-up">

    <!-- Menu info -->
    <div class="mb-6">
        <h1 class="font-extrabold text-2xl text-gray-900 leading-tight mb-1">{{ $menu->name }}</h1>
        <p class="font-extrabold text-2xl text-orange-500 mb-3">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
        @if($menu->description)
        <p class="text-gray-500 text-sm leading-relaxed bg-gray-50 rounded-2xl px-4 py-3 border-l-4 border-orange-300">{{ $menu->description }}</p>
        @endif
    </div>

    <!-- Addon groups -->
    @if($menu->addonGroups->count())
    <div class="space-y-4 mb-6">
        @foreach($menu->addonGroups as $group)
        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="font-extrabold text-sm text-gray-900">{{ $group->name }}</span>
                @if($group->required)
                    <span class="badge-req">Wajib</span>
                @elseif($group->max)
                    <span class="badge-max">Maks. {{ $group->max }}</span>
                @else
                    <span class="badge-opt">Opsional</span>
                @endif
            </div>
            <div>
                @forelse($group->addons as $addon)
                <label class="addon-row">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $addon->name }}</p>
                        @if($addon->price > 0)
                        <p class="text-xs font-bold text-orange-500 mt-0.5">+Rp {{ number_format($addon->price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    @if($group->max == 1 || $group->required)
                    <input type="radio" name="group_{{ $group->id }}" value="{{ $addon->id }}" data-price="{{ $addon->price }}" data-name="{{ $addon->name }}" onchange="updateTotal()">
                    @else
                    <input type="checkbox" name="group_{{ $group->id }}[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}" data-name="{{ $addon->name }}" data-group="{{ $group->id }}" onchange="handleCb(this, {{ $group->max ?? 999 }}, {{ $group->id }})">
                    @endif
                </label>
                @empty
                <p class="text-gray-400 text-xs py-2">Pilihan tidak tersedia</p>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Notes -->
    <div class="mb-5">
        <p class="font-extrabold text-sm text-gray-900 mb-2 flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
            Catatan
        </p>
        <textarea id="notes" rows="2" placeholder="Contoh: Es dipisah, tidak pakai gula..." class="note-input"></textarea>
    </div>

    <!-- Quantity -->
    <div class="qty-row mb-2">
        <p class="font-extrabold text-sm text-gray-900">Jumlah</p>
        <div class="qty-ctrl">
            <button onclick="changeQty(-1)" class="q-btn minus">−</button>
            <span id="qty" class="text-xl font-extrabold text-gray-900 w-10 text-center">1</span>
            <button onclick="changeQty(1)" class="q-btn plus">+</button>
        </div>
    </div>

</div>

<!-- Bottom bar -->
<div class="bottom-bar">
    <button id="btnAddCart" class="btn-addcart" onclick="addToCart()">
        <span class="text-sm font-extrabold">Tambah ke Keranjang</span>
        <span id="totalPrice" class="font-extrabold text-lg">Rp 0</span>
    </button>
</div>

<!-- Image modal -->
<div id="imgModal" onclick="closeImg()">
    <img src="/storage/{{ $menu->image }}" alt="Preview" onclick="event.stopPropagation()">
</div>

<div id="toastWrap"></div>

<script>
const menu = @json($menu);
let qty = 1;

function toast(msg, cls = '') {
    const w = document.getElementById('toastWrap');
    const el = document.createElement('div');
    el.className = `toast ${cls}`;
    el.textContent = msg;
    w.appendChild(el);
    requestAnimationFrame(()=>el.classList.add('show'));
    setTimeout(()=>{ el.classList.remove('show'); setTimeout(()=>el.remove(),280); }, 2000);
}

function openImg()  { document.getElementById('imgModal').classList.add('open'); document.body.style.overflow='hidden'; }
function closeImg() { document.getElementById('imgModal').classList.remove('open'); document.body.style.overflow=''; }

function updateTotal() {
    let addon = 0;
    document.querySelectorAll('input[type=radio]:checked,input[type=checkbox]:checked').forEach(el => addon += Number(el.dataset.price));
    const total = (Number(menu.price) + addon) * qty;
    document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function handleCb(el, max, groupId) {
    if(max < 999) {
        const n = document.querySelectorAll(`input[data-group="${groupId}"]:checked`).length;
        if(n > max) { el.checked = false; toast(`Maks. pilih ${max}`, 'warn'); return; }
    }
    if(el.checked) toast(`${el.dataset.name} ✓`);
    updateTotal();
}

function changeQty(v) {
    qty = Math.min(20, Math.max(1, qty + v));
    document.getElementById('qty').textContent = qty;
    updateTotal();
}

function addToCart() {
    @foreach($menu->addonGroups->where('required', true) as $group)
    if(!document.querySelector('input[name="group_{{ $group->id }}"]:checked')) {
        toast('Pilih "{{ $group->name }}" dulu!', 'err'); return;
    }
    @endforeach

    const addons = [];
    document.querySelectorAll('input[type=radio]:checked,input[type=checkbox]:checked').forEach(el => {
        addons.push({ id: Number(el.value), name: el.dataset.name, price: Number(el.dataset.price) });
    });

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.push({
        id: menu.id, name: menu.name,
        price: Number(menu.price) + addons.reduce((s,a)=>s+a.price,0),
        image: menu.image, quantity: qty,
        notes: document.getElementById('notes').value, addons
    });
    localStorage.setItem('cart', JSON.stringify(cart));

    const btn = document.getElementById('btnAddCart');
    btn.style.background = '#16a34a';
    btn.innerHTML = `<span class="font-extrabold text-sm">Berhasil Ditambah!</span><svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;
    toast('Ditambahkan ke keranjang 🛒');
    setTimeout(()=>{ window.location.href = '/customer/home'; }, 700);
}

document.addEventListener('DOMContentLoaded', updateTotal);

// Back gesture guard
history.replaceState(null,'',location.href);
for(var i=0;i<50;i++) history.pushState(null,'',location.href);
window.addEventListener('popstate',()=>history.pushState(null,'',location.href));
var _sx=0;
window.addEventListener('touchstart',e=>{ _sx=e.touches[0].clientX; },{passive:true});
window.addEventListener('touchmove',e=>{ if(_sx<40) e.preventDefault(); },{passive:false});
</script>
</body>
</html>
