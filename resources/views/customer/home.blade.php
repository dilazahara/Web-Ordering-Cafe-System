<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Menu - Cafe Coffee</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f9fafb; /* Latar belakang abu-abu sangat muda agar card lebih pop */
            -webkit-tap-highlight-color: transparent;
        }

        .fixed button {
            transition: transform 0.2s ease, background-color 0.2s;
        }

        #cartBar {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ✨ Glass modern */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* 🔥 Card animasi */
        .menu-card {
            transition: all 0.3s ease;
        }
        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }

        /* 🎯 Category aktif bergaya Pill (Kapsul) */
        .category-tab {
            color: #6b7280;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 9999px;
            padding: 0.4rem 1.25rem;
            transition: all 0.3s ease;
        }

        .category-tab.active {
            color: #ffffff;
            background-color: #f97316;
            border-color: #f97316;
            box-shadow: 0 4px 10px rgba(249, 115, 22, 0.25);
        }

        /* ⚡ Fade */
        .fade-in {
            animation: fadeIn .4s ease;
        }
        @keyframes fadeIn {
            from {opacity:0; transform:translateY(10px);}
            to {opacity:1; transform:translateY(0);}
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* click feel */
        button:active {
            transform: scale(0.94);
        }

        /* 🔥 POPUP ANIMATION */
        #cartPopup {
            transition: opacity 0.3s ease;
        }

        #cartPopup .panel {
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #cartPopup.show .panel {
            transform: translateY(0);
        }
    </style>
</head>

<body class="min-h-screen pb-24">

    <!-- 🔥 HEADER FIX -->
    <div class="max-w-7xl mx-auto px-4 pt-4">
        <header class="bg-white border border-gray-100 shadow-sm rounded-2xl glass relative overflow-hidden">
            <!-- Aksen warna orange di atas header -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-orange-500"></div>
            
            <div class="px-4 py-3 flex items-center justify-between">
                <!-- LEFT -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" class="h-10 sm:h-12 w-10 sm:w-12 object-contain rounded-full shadow-sm">
                    <div class="leading-tight">
                        <h1 class="font-bold text-base sm:text-lg text-gray-800 tracking-tight">
                            Momoo Juice Bar Coffee Windsor
                        </h1>
                        <p id="greetingText" class="text-[11px] sm:text-xs text-gray-500 font-medium mt-0.5">
                            Order menu favoritmu ✨
                        </p>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="text-right leading-tight flex flex-col items-end">
                    <div id="currentTime" class="font-bold text-sm sm:text-base text-gray-800"></div>
                    <div class="flex items-center gap-1 mt-1 bg-green-50 px-2 py-0.5 rounded-md border border-green-100">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span id="storeStatus" class="text-[10px] sm:text-xs font-semibold text-green-600">Buka</span>
                    </div>
                </div>
            </div>
        </header>
    </div>

    <!-- 🔥 INFO MEJA -->
    @if(session('qr_success'))
    <div class="max-w-7xl mx-auto px-4 mt-2" id="qrSuccessAlert">
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 px-4 py-2.5 rounded-xl text-sm">
            <span class="text-green-500 text-lg">✅</span>
            <span class="text-green-700 font-semibold">{{ session('qr_success') }}</span>
        </div>
    </div>
    <script>setTimeout(()=>{ const el=document.getElementById('qrSuccessAlert'); if(el){el.style.opacity='0';el.style.transition='opacity .4s';setTimeout(()=>el.remove(),400);} }, 3000);</script>
    @endif
    <div class="max-w-7xl mx-auto px-4 mt-3 flex justify-between items-center">
        <div class="inline-flex items-center gap-2 bg-white border border-orange-100 px-4 py-2 rounded-full text-sm shadow-sm">
            <i class="fas fa-chair text-orange-400"></i>
            <span class="text-gray-600 font-medium">Meja:</span>
            <span id="tableNumber" class="text-orange-500 font-bold text-base">
                {{ $tableNumber ?? '-' }}
            </span>
        </div>
        @if($tableNumber)
        <div class="inline-flex items-center gap-1 bg-green-50 border border-green-100 px-3 py-1.5 rounded-full text-xs">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            <span class="text-green-700 font-semibold">Meja terdeteksi</span>
        </div>
        @endif
    </div>

    <!-- CONTENT -->
    <div class="max-w-7xl mx-auto px-4 py-5">

        <!-- SEARCH -->
        <div class="mb-6 relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
            </div>
            <input id="searchInput"
                placeholder="Mau minum atau ngemil apa hari ini?"
                class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl shadow-sm outline-none text-sm focus:border-orange-400 focus:ring-4 focus:ring-orange-50 transition-all">
        </div>

        <!-- CATEGORY -->
        <div class="flex items-center gap-3 mb-6">
            <!-- 🔽 DROPDOWN -->
            <div class="relative shrink-0">
                <button onclick="toggleCategoryDropdown()" 
                    class="flex items-center gap-2 px-4 py-2 bg-orange-50 rounded-full text-sm text-orange-600 font-semibold shadow-sm border border-orange-100 hover:bg-orange-100 transition">
                    <i class="fas fa-layer-group text-xs"></i>
                    <span id="selectedCategory">Semua Kategori</span>
                    <i id="arrowIcon" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                </button>

                <!-- DROPDOWN LIST -->
                <div id="categoryDropdown" 
                    class="hidden absolute left-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-lg z-50 w-48 overflow-hidden">
                    <button onclick="selectCategory('all')" 
                        class="block w-full text-left px-4 py-2.5 text-sm hover:bg-orange-50 hover:text-orange-600 transition-colors border-b border-gray-50">
                         Semua Kategori
                    </button>
                    @foreach($kategoris as $kategori)
                    <button onclick="selectCategory('{{ $kategori->name }}')" 
                        class="block w-full text-left px-4 py-2.5 text-sm hover:bg-orange-50 hover:text-orange-600 transition-colors border-b border-gray-50 last:border-0">
                        {{ $kategori->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- GARIS PEMISAH -->
            <div class="w-px h-6 bg-gray-300 hidden sm:block"></div>

            <!-- 🔥 TAB KATEGORI (HORIZONTAL SCROLL) -->
            <div class="flex gap-3 overflow-x-auto whitespace-nowrap no-scrollbar pb-1">
                <button onclick="selectCategory('all', event)" 
                    class="category-tab active text-sm font-semibold">
                    Semua
                </button>
                @foreach($kategoris as $kategori)
                <button onclick="selectCategory('{{ $kategori->name }}', event)" 
                    class="category-tab text-sm font-semibold">
                    {{ strtoupper($kategori->name) }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- MENU GRID -->
        <div id="menuGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Menu will be rendered here by JS -->
        </div>

    </div>

    <!-- FLOAT MOBILE CART BUTTON (Fall back if bar is not used) -->
    <div class="fixed bottom-24 right-5 lg:hidden z-40">
        <button onclick="toggleCart()" id="cartButton" class="bg-white shadow-lg text-gray-400 p-4 rounded-full border border-gray-100 hover:shadow-xl transition">
            <i class="fas fa-shopping-cart text-lg"></i>
        </button>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CART POPUP (BOTTOM SHEET) -->
    <div id="cartPopup"
        onclick="if(event.target.id==='cartPopup') toggleCart()"
        class="fixed inset-0 pb-0 md:pb-20 bg-black/50 backdrop-blur-sm hidden z-[9999] flex items-end justify-center">

        <div class="panel w-full max-w-3xl bg-white p-5 shadow-2xl max-h-[85vh] overflow-y-auto rounded-t-3xl relative">
            
            <!-- Pull Indicator -->
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-5"></div>

            <div class="flex justify-between items-center mb-6">
                <h2 class="font-bold text-gray-800 text-xl flex items-center gap-2">
                    <i class="fas fa-shopping-basket text-orange-500"></i> Keranjang Saya
                </h2>
                <!-- CLOSE -->
                <button onclick="toggleCart()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-red-100 hover:text-red-500 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="cartItems" class="min-h-[150px]"></div>

            <div class="border-t border-gray-100 pt-5 mt-2 sticky bottom-0 bg-white pb-2">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 font-medium">Total Pembayaran</span>
                    <span id="cartTotal" class="font-bold text-2xl text-orange-500">Rp 0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔥 STICKY CART BAR (FLOATING STYLE) -->
    <div id="cartBar"
        class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:w-96 glass rounded-2xl px-4 py-3 flex items-center justify-between shadow-2xl border hidden z-50">

        <!-- LEFT: ICON + BADGE -->
        <div class="relative">
            <button onclick="toggleCart()" class="bg-orange-500 text-white p-3.5 rounded-xl hover:bg-orange-600 transition shadow-md">
                <i id="cartIcon" class="fas fa-shopping-basket text-lg"></i>
            </button>
            <!-- BADGE -->
            <span id="cartBarCount" class="absolute -top-2 -right-2 bg-red-500 border-2 border-white text-white font-bold text-[10px] w-6 h-6 flex items-center justify-center rounded-full shadow-sm">
                0
            </span>
        </div>

        <!-- MIDDLE: TOTAL -->
        <div class="flex flex-col ml-3 flex-1">
            <span class="text-xs text-gray-500 font-medium">Total Harga</span>
            <div class="text-gray-800 font-bold text-lg leading-none mt-0.5">
                <span id="cartBarTotal">Rp 0</span>
            </div>
        </div>

        <!-- RIGHT: BUTTON -->
        <button onclick="goToCheckout()" class="bg-gray-900 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:bg-black transition flex items-center gap-2">
            Checkout <i class="fas fa-arrow-right text-xs"></i>
        </button>
    </div>

    <!-- 🔔 TOAST FEEDBACK CONTAINER -->
    <div id="toastContainer" class="fixed top-4 left-1/2 -translate-x-1/2 z-[99999] flex flex-col gap-2 items-center pointer-events-none" style="min-width:0;width:max-content;max-width:calc(100vw - 32px);"></div>

    <script>
        // ══════════════════════════════════
        //  TOAST NOTIFICATION SYSTEM
        // ══════════════════════════════════
        function showToast(msg, type = 'success', duration = 2200) {
            const container = document.getElementById('toastContainer');
            const colors = {
                success: 'bg-green-500 text-white',
                info:    'bg-gray-800 text-white',
                warning: 'bg-amber-500 text-white',
                error:   'bg-red-500 text-white',
            };
            const icons = {
                success: '✅',
                info:    'ℹ️',
                warning: '⚠️',
                error:   '❌',
            };
            const toast = document.createElement('div');
            toast.className = `pointer-events-auto flex items-center gap-2 px-4 py-2.5 rounded-2xl shadow-lg text-sm font-semibold ${colors[type] || colors.info}`;
            toast.style.cssText = 'opacity:0;transform:translateY(-10px) scale(0.95);transition:all 0.25s ease;white-space:nowrap;';
            toast.innerHTML = `<span>${icons[type] || '📢'}</span><span>${msg}</span>`;
            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0) scale(1)';
            });
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px) scale(0.95)';
                setTimeout(() => toast.remove(), 260);
            }, duration);
        }

        const menuData = JSON.parse('@json($menus)');
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let currentCategory = 'all';

        const fromSuccess = sessionStorage.getItem('orderSuccess');
        if(fromSuccess){
            localStorage.removeItem('cart');
            localStorage.removeItem('checkoutCart');
            sessionStorage.removeItem('orderSuccess');
        }

        // INIT
        document.addEventListener('DOMContentLoaded', () => {
            renderMenu(menuData);
            updateCartUI();
            initEvents();
            updateTime();
            setInterval(updateTime, 1000);
            getTableNumber();
        });

        function getTableNumber(){
            // Prioritaskan server-side session (dari QR scan)
            const serverTable = @json($tableNumber ?? null);

            if (serverTable) {
                // Sync session ke localStorage agar checkout bisa baca
                localStorage.setItem('table_number', serverTable);
                localStorage.setItem('tableNumber', serverTable);
                // Tampilan sudah di-render Blade, tapi update JS state juga
            } else {
                const params = new URLSearchParams(window.location.search);
                const table = params.get('meja') || params.get('table');
                if(table){
                    localStorage.setItem('table_number', table);
                    localStorage.setItem('tableNumber', table);
                }
            }
        }

        function initEvents(){
            document.getElementById('searchInput').addEventListener('input', filterMenu);
        }

        // RENDER MENU LENGKAP DENGAN UI BARU
        function renderMenu(items){
            const grid = document.getElementById('menuGrid');

            if(items.length === 0){
                grid.innerHTML = `<div class="col-span-full text-center py-10 text-gray-400"><i class="fas fa-box-open text-4xl mb-3"></i><p>Menu tidak ditemukan</p></div>`;
                return;
            }

            grid.innerHTML = items.map(item => `
            <div class="menu-card bg-white p-3.5 rounded-2xl border border-gray-100 flex gap-4 relative overflow-hidden">
                <!-- IMAGE -->
                <div class="relative w-24 h-24 shrink-0">
                    <img src="/storage/${item.image}" 
                    class="w-full h-full object-cover rounded-xl ${item.status == 0 ? 'grayscale opacity-50' : 'shadow-sm'}">
                    ${item.status == 0 ? `<div class="absolute inset-0 flex items-center justify-center bg-black/10 rounded-xl"><span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded">HABIS</span></div>` : ''}
                </div>

                <!-- CONTENT -->
                <div class="flex-1 flex flex-col justify-between py-0.5">
                    <div>
                        <h3 class="font-bold text-sm text-gray-800 leading-snug pr-6">
                            ${item.name}
                        </h3>
                        <p class="text-xs text-gray-400 line-clamp-2 mt-1 leading-relaxed">
                            ${item.description ?? ''}
                        </p>
                    </div>

                    <div class="flex items-end justify-between mt-2">
                        <p class="text-orange-500 font-bold text-sm">
                            Rp ${Number(item.price).toLocaleString('id-ID')}
                        </p>
                        <!-- ACTION BUTTON -->
                        ${item.status == 1 ? `
                        <button onclick="goToAddon(${item.id})"
                        class="bg-orange-50 hover:bg-orange-500 text-orange-500 hover:text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors shadow-sm">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                        ` : `
                        <span class="text-gray-400 text-xs font-semibold bg-gray-50 px-2 py-1 rounded">Habis</span>
                        `}
                    </div>
                </div>
            </div>
            `).join('');
        }

        function changeQty(index, val){
            cart[index].quantity += val;
            if(cart[index].quantity <= 0){
                cart.splice(index, 1);
                showToast('Item dihapus dari keranjang', 'info', 1800);
            } else {
                showToast(val > 0 ? 'Jumlah ditambah' : 'Jumlah dikurangi', 'info', 1400);
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartUI();
        }

        function removeItem(index){
            const el = document.querySelectorAll('#cartItems > div')[index];
            if(el){
                el.style.transition = 'all 0.3s ease';
                el.style.opacity = 0;
                el.style.transform = 'translateX(-20px)';
            }
            showToast('Item dihapus dari keranjang', 'warning', 1800);
            setTimeout(()=>{
                cart.splice(index,1);
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartUI();
            },300);
        }

        function addToCart(id, e){
            const item = menuData.find(m => m.id === id);
            const existing = cart.find(c => c.id === id);

            if(existing){
                existing.quantity++;
            } else {
                cart.push({
                    id: item.id,
                    name: item.name,
                    price: Number(item.price),
                    image: item.image,
                    quantity: 1,
                    notes: ''
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartUI();
        }

        function filterMenu(){
            const search = document.getElementById('searchInput').value.toLowerCase();

            let filtered = menuData.filter(item=>{
                return (
                    (item.name.toLowerCase().includes(search) ||
                    (item.description ?? '').toLowerCase().includes(search))
                    &&
                    (currentCategory === 'all' || item.kategori?.name === currentCategory)
                );
            });

            renderMenu(filtered);
        }

        function updateCartUI(){
            renderCartItems();

            let total = 0;
            let count = 0;

            cart.forEach(item=>{
                total += (item.price || 0) * (item.quantity || 0);
                count += item.quantity || 0;
            });

            document.getElementById('cartTotal').textContent = "Rp " + total.toLocaleString('id-ID');
            document.getElementById('cartBarTotal').textContent = "Rp " + total.toLocaleString('id-ID');
            document.getElementById('cartBarCount').textContent = count;

            const bar = document.getElementById('cartBar');
            if(count > 0){
                bar.classList.remove('hidden');
            } else {
                bar.classList.add('hidden');
                // Auto close cart if empty
                if(document.getElementById('cartPopup').classList.contains('show')){
                    toggleCart();
                }
            }
        }

        function renderCartItems(){
            const el = document.getElementById('cartItems');

            if(cart.length === 0){
                el.innerHTML = `
                <div class="flex flex-col items-center justify-center py-8">
                    <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" class="w-20 h-20 opacity-30 mb-4">
                    <p class="text-gray-400 font-medium">Keranjang masih kosong nih.</p>
                </div>`;
                return;
            }

            el.innerHTML = cart.map((item, index)=>{
                return `
                <div class="flex gap-4 mb-4 bg-white border border-gray-50 p-3 rounded-2xl shadow-sm">
                    <!-- GAMBAR -->
                    <img src="/storage/${item.image ?? ''}" class="w-16 h-16 rounded-xl object-cover shadow-sm">

                    <!-- CONTENT -->
                    <div class="flex-1 flex flex-col justify-between">
                        <!-- NAMA + HAPUS -->
                        <div class="flex justify-between items-start">
                            <p class="font-bold text-gray-800 text-sm leading-tight pr-2">${item.name}</p>
                            <button onclick="removeItem(${index})" class="text-red-400 hover:text-red-500 text-xs bg-red-50 px-2 py-1 rounded">
                                Hapus
                            </button>
                        </div>

                        <!-- CATATAN -->
                        <div class="mt-1">
                            ${item.isEditing ? `
                                <div class="flex gap-2">
                                    <input type="text" value="${item.notes || ''}"
                                    onblur="saveNote(${index}, this.value)"
                                    onkeydown="if(event.key==='Enter'){saveNote(${index}, this.value)}"
                                    class="text-xs border border-orange-200 bg-orange-50 px-2 py-1.5 rounded-lg w-full outline-none focus:ring-1 focus:ring-orange-400"
                                    placeholder="Tulis catatan (cth: Es dipisah)" autofocus>
                                </div>
                            ` : `
                                <p onclick="startEditNote(${index})" class="text-xs text-gray-400 cursor-pointer inline-flex items-center gap-1 hover:text-orange-500 transition">
                                    <i class="fas fa-edit"></i> ${item.notes ? item.notes : 'Tambah catatan...'}
                                </p>
                            `}
                        </div>

                        <!-- HARGA + QTY -->
                        <div class="flex justify-between items-end mt-2">
                            <p class="text-orange-500 font-bold text-sm">
                                Rp ${(item.price).toLocaleString('id-ID')}
                            </p>
                            <!-- Qty Controls -->
                            <div class="flex items-center gap-3 bg-gray-50 border border-gray-100 rounded-lg px-1 py-1">
                                <button onclick="changeQty(${index}, -1)" class="w-6 h-6 flex items-center justify-center bg-white rounded shadow-sm text-gray-600 hover:bg-gray-100">
                                    <i class="fas fa-minus text-[10px]"></i>
                                </button>
                                <span class="font-bold text-sm w-4 text-center text-gray-700">${item.quantity}</span>
                                <button onclick="changeQty(${index}, 1)" class="w-6 h-6 flex items-center justify-center bg-orange-500 rounded shadow-sm text-white hover:bg-orange-600">
                                    <i class="fas fa-plus text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            }).join('');
        }

        function toggleCart(){
            const popup = document.getElementById('cartPopup');
            const icon = document.getElementById('cartIcon');
            
            if(popup.classList.contains('hidden')){
                popup.classList.remove('hidden');
                requestAnimationFrame(()=>{
                    popup.classList.add('show');
                });
                icon.classList.remove('fa-shopping-basket');
                icon.classList.add('fa-times');
                document.body.style.overflow = 'hidden'; // Prevent scrolling bg
                showToast('Keranjang dibuka 🛒', 'info', 1400);
            } else {
                popup.classList.remove('show');
                setTimeout(()=>{
                    popup.classList.add('hidden');
                }, 300);
                icon.classList.remove('fa-times');
                icon.classList.add('fa-shopping-basket');
                document.body.style.overflow = '';
            }
        }

        function startEditNote(index){
            cart[index].isEditing = true;
            updateCartUI();
        }

        function saveNote(index, value){
            cart[index].notes = value;
            cart[index].isEditing = false;
            localStorage.setItem('cart', JSON.stringify(cart));
            showToast('Catatan disimpan 📝', 'success', 1600);
            updateCartUI();
        }

        function updateTime(){
            const now = new Date();
            const jam = now.getHours().toString().padStart(2,'0');
            const menit = now.getMinutes().toString().padStart(2,'0');
            
            document.getElementById('currentTime').textContent = `${jam}:${menit}`; // Hapus detik agar lebih elegan

            let statusText = '';
            let statusColor = '';
            let bgPulseColor = '';

            if(jam >= 8 && jam < 22){
                statusText = 'Buka Sekarang';
                statusColor = 'text-green-600';
                bgPulseColor = 'bg-green-500';
            } else {
                statusText = 'Toko Tutup';
                statusColor = 'text-red-600';
                bgPulseColor = 'bg-red-500';
            }

            const statusEl = document.getElementById('storeStatus');
            statusEl.textContent = statusText;
            statusEl.className = 'text-[10px] sm:text-xs font-semibold ' + statusColor;
            statusEl.previousElementSibling.className = `w-2 h-2 rounded-full ${bgPulseColor} ${jam >= 8 && jam < 22 ? 'animate-pulse' : ''}`;
            statusEl.parentElement.className = `flex items-center gap-1.5 mt-1 px-2.5 py-1 rounded-md border ${jam >= 8 && jam < 22 ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100'}`;
        }

        function goToAddon(id){
            window.location.href = '/customer/addons?menu_id=' + id;
        }

        function goToCheckout(){
            localStorage.setItem('checkoutCart', JSON.stringify(cart));
            window.location.href='/customer/checkout';
        }

        function toggleCategoryDropdown(){
            const dropdown = document.getElementById('categoryDropdown');
            const arrow = document.getElementById('arrowIcon');
            dropdown.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }

        function selectCategory(category, event){
            currentCategory = category;

            // update dropdown text
            document.getElementById('selectedCategory').textContent = category === 'all' ? 'Semua Kategori' : category;
            document.getElementById('categoryDropdown').classList.add('hidden');
            document.getElementById('arrowIcon').classList.remove('rotate-180');

            // Reset tab
            document.querySelectorAll('.category-tab').forEach(tab=>{
                tab.classList.remove('active');
                // Jika dari dropdown, coba sinkronkan ke tab horizontal jika ada kecocokan text
                if(tab.textContent.trim().toLowerCase() === category.toLowerCase() || (category === 'all' && tab.textContent.trim().toLowerCase() === 'semua')){
                    tab.classList.add('active');
                }
            });

            if(event && event.currentTarget){
                document.querySelectorAll('.category-tab').forEach(tab=> tab.classList.remove('active'));
                event.currentTarget.classList.add('active');
            }

            const label = category === 'all' ? 'Semua Menu' : category;
            showToast(`Kategori: ${label}`, 'info', 1500);
            filterMenu();
        }
    </script>
</body>
</html>