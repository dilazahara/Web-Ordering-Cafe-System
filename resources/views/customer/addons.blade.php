<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Detail Menu - {{ $menu->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            box-sizing: border-box; 
            -webkit-tap-highlight-color: transparent;
        }
        
        /* ── Body diubah agar support desktop (berwarna abu-abu) ── */
        body {
            background-color: #e5e7eb; /* Warna background luar untuk layar besar */
            margin: 0;
            display: flex;
            justify-content: center;
        }

        /* ── App Container (Pembungkus agar tidak over-stretch di PC) ── */
        .app-container {
            width: 100%;
            max-width: 480px; /* Lebar maksimal layaknya layar HP */
            background-color: #ffffff;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 40px rgba(0,0,0,0.08);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* ── Image Header Modern ── */
        .menu-img-wrap {
            position: relative;
            width: 100%;
            height: 40vh;
            min-height: 280px;
            max-height: 400px;
            overflow: hidden;
            background: #f3f4f6;
            cursor: pointer;
        }
        .menu-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .img-overlay {
            position: absolute; 
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, transparent 20%, transparent 70%, rgba(0,0,0,0.6) 100%);
        }

        /* ── Preview Icon ── */
        .preview-trigger {
            position: absolute;
            bottom: 40px;
            right: 16px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            padding: 8px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        /* ── Back Button Floating ── */
        .back-btn {
            position: absolute; /* Ubah ke absolute agar relatif terhadap app-container */
            top: 16px; 
            left: 16px; 
            z-index: 100;
            width: 42px; 
            height: 42px; 
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            display: flex; 
            align-items: center; 
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.2s;
        }
        .back-btn:active { transform: scale(0.9); }

        /* ── Content Card ── */
        .content-card {
            position: relative;
            margin-top: -24px;
            background: white;
            border-radius: 28px 28px 0 0;
            padding: 24px 20px 140px;
            box-shadow: 0 -10px 25px rgba(0,0,0,0.05);
            flex: 1; /* Penuhi sisa ruang */
        }

        /* ── Badge Styling ── */
        .badge {
            font-size: 10px; 
            font-weight: 700;
            padding: 4px 10px; 
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        .badge-required { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }
        .badge-max { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
        .badge-free { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }

        /* ── Addon Group Card ── */
        .group-card {
            background: #ffffff;
            border: 1px solid #f3f4f6;
            border-radius: 20px;
            padding: 16px;
            margin-bottom: 20px;
        }

        /* ── Custom Input Controls ── */
        input[type=radio], input[type=checkbox] {
            appearance: none;
            width: 22px; height: 22px;
            border: 2px solid #e5e7eb;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        input[type=radio] { border-radius: 50%; }
        input[type=checkbox] { border-radius: 7px; }
        input[type=radio]:checked, input[type=checkbox]:checked {
            background: #f97316;
            border-color: #f97316;
        }
        input[type=radio]:checked::after {
            content: ''; position: absolute; top: 5px; left: 5px;
            width: 8px; height: 8px; border-radius: 50%; background: white;
        }
        input[type=checkbox]:checked::after {
            content: '✓'; position: absolute; top: -1px; left: 3px;
            color: white; font-size: 14px; font-weight: bold;
        }

        /* ── Quantity Controls ── */
        .qty-container {
            display: flex;
            align-items: center;
            background: #f9fafb;
            padding: 6px;
            border-radius: 16px;
            border: 1px solid #f3f4f6;
        }
        .qty-btn {
            width: 38px; height: 38px;
            border-radius: 12px;
            background: white;
            color: #1f2937;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            font-weight: 700;
        }

        /* ── Sticky Bottom Bar ── */
        .bottom-bar {
            position: fixed; 
            bottom: 0; 
            width: 100%;
            max-width: 480px; /* Ikuti lebar app-container */
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 20px 20px calc(20px + env(safe-area-inset-bottom));
            border-top: 1px solid rgba(0,0,0,0.05);
            z-index: 100;
        }
        .cta-btn {
            width: 100%;
            background: #f97316;
            color: white;
            border-radius: 18px;
            padding: 16px 24px;
            font-weight: 800;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.25);
        }

        /* ── Image Preview Modal ── */
        #imageModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.9);
            z-index: 200;
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        #imageModal.active {
            display: flex;
            opacity: 1;
        }
        #imageModal img {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 16px;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        #imageModal.active img {
            transform: scale(1);
        }
        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
        }

        /* Animations */
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-up { animation: slideUp 0.5s ease-out forwards; }
    </style>
</head>

<body class="animate-slide-up">

<!-- Semua konten dibungkus dalam div.app-container agar responsif di layar besar -->
<div class="app-container">

    <!-- ══ Tombol Kembali Floating ══ -->
    <button class="back-btn" onclick="goBack()">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <!-- ══ IMAGE HEADER (Click to Preview) ══ -->
    <div class="menu-img-wrap" onclick="openPreview()">
        <img src="/storage/{{ $menu->image }}" alt="{{ $menu->name }}" id="mainImage">
        <div class="img-overlay"></div>
        <!-- Preview Icon -->
        <div class="preview-trigger">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
            </svg>
        </div>
    </div>

    <!-- ══ CONTENT CARD ══ -->
    <div class="content-card">

        <!-- MENU INFO -->
        <div class="mb-8">
            <div class="flex justify-between items-start mb-2">
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight flex-1">{{ $menu->name }}</h1>
            </div>
            <p class="text-2xl font-black text-orange-500 mb-3">
                Rp {{ number_format($menu->price, 0, ',', '.') }}
            </p>
            @if($menu->description)
            <p class="text-gray-500 text-sm leading-relaxed bg-gray-50 p-3 rounded-xl border-l-4 border-orange-200">
                {{ $menu->description }}
            </p>
            @endif
        </div>

        <!-- ADDON GROUPS -->
        @if($menu->addonGroups->count())
        <div class="flex items-center gap-2 mb-4">
            <div class="h-1 w-8 bg-orange-500 rounded-full"></div>
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Kustomisasi Menu</h2>
        </div>

        @foreach($menu->addonGroups as $group)
        <div class="group-card">
            <div class="flex justify-between items-center mb-4">
                <span class="text-base font-bold text-gray-800">{{ $group->name }}</span>
                @if($group->required)
                    <span class="badge badge-required">Wajib</span>
                @elseif($group->max)
                    <span class="badge badge-max">Pilih Maks. {{ $group->max }}</span>
                @else
                    <span class="badge badge-free">Opsional</span>
                @endif
            </div>

            <div class="space-y-1">
                @forelse($group->addons as $addon)
                <label class="flex justify-between items-center py-3 group cursor-pointer border-b border-gray-50 last:border-0">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-700 group-active:text-orange-600 transition-colors">{{ $addon->name }}</span>
                        @if($addon->price > 0)
                        <span class="text-xs font-bold text-orange-500 mt-0.5">+Rp {{ number_format($addon->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    @if($group->max == 1 || $group->required)
                    <input type="radio" name="group_{{ $group->id }}" value="{{ $addon->id }}" data-price="{{ $addon->price }}" data-name="{{ $addon->name }}" onchange="updateTotal()">
                    @else
                    <input type="checkbox" name="group_{{ $group->id }}[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}" data-name="{{ $addon->name }}" data-group="{{ $group->id }}" onchange="handleCheckbox(this, {{ $group->max ?? 999 }}, {{ $group->id }})">
                    @endif
                </label>
                @empty
                <p class="text-gray-400 text-xs italic">Pilihan tidak tersedia</p>
                @endforelse
            </div>
        </div>
        @endforeach
        @endif

        <!-- NOTES -->
        <div class="mb-8">
            <label class="block text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Catatan Tambahan
            </label>
            <textarea id="notes" rows="3" class="notes-input w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 text-sm outline-none focus:border-orange-500 transition-all" placeholder="Contoh: Takaran es sedikit saja..."></textarea>
        </div>

        <!-- QUANTITY -->
        <div class="flex justify-between items-center bg-white border border-gray-100 p-5 rounded-2xl shadow-sm">
            <p class="text-sm font-extrabold text-gray-900">Jumlah Pesanan</p>
            <div class="qty-container">
                <button class="qty-btn" onclick="changeQty(-1)"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M20 12H4"/></svg></button>
                <span class="text-lg font-black px-5 text-gray-800" id="qty">1</span>
                <button class="qty-btn" onclick="changeQty(1)"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4"/></svg></button>
            </div>
        </div>
    </div>

    <!-- ══ BOTTOM BAR ══ -->
    <div class="bottom-bar">
        <button class="cta-btn" onclick="addToCart()">
            <span class="text-[10px] opacity-80 font-bold uppercase tracking-widest leading-tight">Tambah Pesanan</span>
            <span id="totalPrice" class="text-lg font-black tracking-tight">Rp 0</span>
        </button>
    </div>

</div> <!-- End of .app-container -->

<!-- ══ IMAGE PREVIEW MODAL (Diluar app-container agar fullscreen seutuhnya) ══ -->
<div id="imageModal" onclick="closePreview()">
    <div class="close-modal">
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#1f2937" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </div>
    <img src="/storage/{{ $menu->image }}" alt="Preview" onclick="event.stopPropagation()">
</div>

<!-- ══ TOAST FEEDBACK ══ -->
<div id="toastContainer" class="fixed top-4 left-1/2 -translate-x-1/2 z-[99999] flex flex-col gap-2 items-center pointer-events-none" style="min-width:0;width:max-content;max-width:calc(100vw - 32px);"></div>

<script>
// ═══════════════════════════════
//  TOAST SYSTEM
// ═══════════════════════════════
function showToast(msg, type = 'success', duration = 2000) {
    const container = document.getElementById('toastContainer');
    const colors = { success:'bg-green-500 text-white', info:'bg-gray-800 text-white', warning:'bg-amber-500 text-white', error:'bg-red-500 text-white' };
    const icons  = { success:'✅', info:'ℹ️', warning:'⚠️', error:'❌' };
    const toast = document.createElement('div');
    toast.className = `pointer-events-auto flex items-center gap-2 px-4 py-2.5 rounded-2xl shadow-lg text-sm font-semibold ${colors[type]||colors.info}`;
    toast.style.cssText = 'opacity:0;transform:translateY(-10px) scale(0.95);transition:all 0.25s ease;white-space:nowrap;';
    toast.innerHTML = `<span>${icons[type]||'📢'}</span><span>${msg}</span>`;
    container.appendChild(toast);
    requestAnimationFrame(()=>{ toast.style.opacity='1'; toast.style.transform='translateY(0) scale(1)'; });
    setTimeout(()=>{ toast.style.opacity='0'; toast.style.transform='translateY(-10px) scale(0.95)'; setTimeout(()=>toast.remove(),260); }, duration);
}

const menu = @json($menu);
let qty = 1;

// --- PREVIEW LOGIC ---
function openPreview() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden'; 
}

function closePreview() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('active');
    document.body.style.overflow = ''; 
}

// --- PRICE LOGIC ---
function updateTotal(){
    let addonTotal = 0;
    document.querySelectorAll('input[type=radio]:checked, input[type=checkbox]:checked')
        .forEach(el => { addonTotal += Number(el.dataset.price); });
    const total = (Number(menu.price) + addonTotal) * qty;
    document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function handleCheckbox(el, max, groupId){
    if(max && max < 999){
        const checked = document.querySelectorAll(`input[data-group="${groupId}"]:checked`);
        if(checked.length > max){
            el.checked = false;
            showToast(`Maksimal pilih ${max} pilihan`, 'warning', 2000);
            return;
        }
    }
    if(el.checked) showToast(`${el.dataset.name} dipilih ✓`, 'success', 1500);
    updateTotal();
}

function changeQty(val){
    qty = Math.min(20, Math.max(1, qty + val));
    document.getElementById('qty').textContent = qty;
    showToast(val > 0 ? `Jumlah: ${qty} 🔢` : `Jumlah: ${qty} 🔢`, 'info', 1200);
    updateTotal();
}

function addToCart(){
    @foreach($menu->addonGroups->where('required', true) as $group)
    if(!document.querySelector('input[name="group_{{ $group->id }}"]:checked')){
        showToast('Mohon pilih "{{ $group->name }}"', 'error', 2500); return;
    }
    @endforeach

    const selectedAddons = [];
    document.querySelectorAll('input[type=radio]:checked, input[type=checkbox]:checked').forEach(el => {
        selectedAddons.push({ id: Number(el.value), name: el.dataset.name, price: Number(el.dataset.price) });
    });

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.push({
        id: menu.id, name: menu.name, price: (Number(menu.price) + selectedAddons.reduce((s, a) => s + a.price, 0)),
        image: menu.image, quantity: qty, notes: document.getElementById('notes').value, addons: selectedAddons,
    });

    localStorage.setItem('cart', JSON.stringify(cart));
    const btn = document.querySelector('.cta-btn');
    btn.innerHTML = "<span>Berhasil Ditambah! 🎉</span>";
    btn.style.background = "#16a34a";
    showToast(`${menu.name} ditambahkan ke keranjang! 🛒`, 'success', 2000);
    setTimeout(() => { window.location.href = '/customer/home'; }, 800);
}

function goBack(){ window.location.href = '/customer/home'; }
document.addEventListener('DOMContentLoaded', updateTotal);
</script>

</body>
</html>