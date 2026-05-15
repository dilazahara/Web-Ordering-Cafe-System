<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- ICON -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>
/* =======================
   BASE
======================= */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f5f6fa;
}

/* =======================
   TOPBAR
======================= */
.topbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 70px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    z-index: 1000;
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.topbar-left i {
    cursor: pointer;
}

/* =======================
   SIDEBAR
======================= */
.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    background: linear-gradient(180deg, #0f172a, #1e1b4b);
    padding: 20px;
    padding-top: 80px;
    color: white;
    overflow-y: auto;
    transform: translateX(-100%);
    transition: 0.3s;
    z-index: 999;
    /* 🔥 RAPII */
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.menu-section {
    font-size: 11px;
    letter-spacing: 1px;
    color: #a78bfa;
    margin: 18px 10px 8px;
    opacity: 0.7;
}

.sidebar.show {
    transform: translateX(0);
}

.sidebar h2 {
    margin-bottom: 15px;
}

/* =======================
   MENU SIDEBAR
======================= */
.sidebar a,
.menu-parent {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border-radius: 12px;
    text-decoration: none;
    color: #c4b5fd;
    font-size: 14px;
    font-weight: 400;
    transition: 0.3s;
}

/* ❌ hapus margin lama */
.sidebar a {
    margin-bottom: 0;
}

/* ICON */
.sidebar i {
    width: 18px;
    height: 18px;
    color: #c4b5fd;
}

/* HOVER & ACTIVE */
.sidebar a:hover,
.menu-parent:hover {
    background: rgba(255,255,255,0.06);
    color: white;
}

.sidebar a.active {
  background: rgba(139, 92, 246, 0.25);
    color: #c4b5fd;
    box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4);
}

/* =======================
   DROPDOWN MENU
======================= */
.menu-parent {
    justify-content: flex-start;
    cursor: pointer;
}

.menu-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* ARROW */
.arrow {
    margin-left: auto;
    transition: 0.3s;
}

.arrow.rotate {
    transform: rotate(180deg);
}

/* =======================
   SUBMENU
======================= */
.submenu {
    display: none;
    flex-direction: column;
    margin-left: 28px;
    gap: 5px;
}

.submenu-item {
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    color: #c4b5fd;
}

.submenu-item:hover {
    background: rgba(255,255,255,0.1);
    color: white;
}

.submenu-item.active {
    background: rgba(255,255,255,0.15);
    color: white;
}

/* =======================
   MAIN
======================= */
.main {
    margin-left: 0;
    padding: 100px 0 30px;
}

/* =======================
   HEADER FORM
======================= */
.header {
    max-width: 850px;
    margin: auto;
    margin-bottom: 20px;

    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* =======================
   FORM
======================= */
.form-box {
    background: white;
    padding: 30px;
    border-radius: 20px;
    max-width: 800px;
    width: 100%;
    margin: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.form-group {
    margin-bottom: 18px;
}

label {
    font-weight: 500;
    font-size: 14px;
    color: #374151;
}

input, select {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    transition: 0.2s;
}

input:focus {
    outline: none;
    border-color: #f97316;
    box-shadow: 0 0 0 2px rgba(249,115,22,0.2);
}

/* =======================
   BUTTON
======================= */
.btn {
    margin-top: 15px;
    padding: 12px 18px;
    border: none;
    border-radius: 12px;
    background: #f97316;
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: 0.2s;
}

.btn:hover {
    background: #ea580c;
}
</style>
</head>

<body>
    <!-- HEADER -->
<div class="topbar">
    <div class="topbar-left">
        <i data-lucide="menu" onclick="toggleSidebar()"></i>
    </div>
</div>

<!-- SIDEBAR -->
<div class="sidebar">

  <div class="menu-section">MAIN</div>

    <a href="/admin/dashboard">
        <i data-lucide="layout-dashboard"></i> Dashboard
    </a>

    <a href="/admin/order">
        <i data-lucide="receipt"></i> Orders
    </a>

       <div class="menu-section">KATALOG</div>

    <a href="/admin/menu">
        <i data-lucide="utensils"></i> Menu
    </a>

    <a href="/admin/kategori">
        <i data-lucide="folder"></i> Kategori
    </a>

    <a href="/admin/addons">
        <i data-lucide="plus-circle"></i> Add-ons
    </a>

    <div class="menu-section">OPERASIONAL</div>

    <div class="menu-group">

    <div class="menu-parent" onclick="toggleMejaMenu()">
        <div class="menu-left">
            <i data-lucide="armchair"></i>
            <span>Meja</span>
        </div>
        <i data-lucide="chevron-down" class="arrow" id="arrowMeja"></i>
    </div>

    <div class="submenu" id="submenuMeja">

        <a href="/admin/meja" class="submenu-item {{ request()->is('admin/meja') ? 'active' : '' }}">
            Data Meja
        </a>

        <a href="/admin/meja/monitor" class="submenu-item {{ request()->is('admin/meja/monitor') ? 'active' : '' }}">
            Monitor Meja
        </a>

    </div>

</div>

    <a href="/admin/pembayaran">
        <i data-lucide="credit-card"></i> Pembayaran
    </a>

        <div class="menu-section">ANALITIK</div>

    <a href="/admin/laporan">
        <i data-lucide="bar-chart-3"></i> Laporan
    </a>


    <div class="menu-section">SYSTEM</div>

    <a href="/admin/user">
        <i data-lucide="users"></i> User
    </a>

    <a href="/admin/settings" class="active">
        <i data-lucide="settings"></i> Settings
    </a>
</div>

<!-- MAIN -->
<div class="main">

    <div class="header">
        <h1>Pengaturan Sistem</h1>
    </div>

@if(session('success'))
<div style="background:#dcfce7;color:#166534;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:14px;">
    ✅ {{ session('success') }}
</div>
@endif

<form method="POST" action="/admin/settings">
    @csrf
    <div class="form-box">

        <div class="form-group">
            <label>Nama Cafe</label>
            <input type="text" name="nama_cafe" value="{{ $settings['nama_cafe'] ?? 'Tjap Nyonya' }}" required>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" value="{{ $settings['alamat'] ?? '' }}">
        </div>

        <div class="form-group">
            <label>No. Telepon</label>
            <input type="text" name="no_telp" value="{{ $settings['no_telp'] ?? '' }}">
        </div>

        <div class="form-group">
            <label>Jam Buka</label>
            <input type="time" name="jam_buka" value="{{ $settings['jam_buka'] ?? '08:00' }}">
        </div>

        <div class="form-group">
            <label>Jam Tutup</label>
            <input type="time" name="jam_tutup" value="{{ $settings['jam_tutup'] ?? '22:00' }}">
        </div>

        <div class="form-group">
            <label>Biaya Layanan (Rp)</label>
            <input type="number" name="biaya_layanan" value="{{ $settings['biaya_layanan'] ?? 2000 }}" min="0">
        </div>

        <button type="submit" style="margin-top:16px;padding:10px 28px;background:#4f46e5;color:white;border:none;border-radius:10px;font-size:14px;cursor:pointer;font-family:'Poppins',sans-serif;">
            Simpan Settings
        </button>

    </div>
</form>

<script>
function toggleSidebar() {
    document.querySelector(".sidebar").classList.toggle("show");
}

function simpanData() {
    const notif = document.createElement("div");
    notif.innerText = "✅ Berhasil disimpan!";

    notif.style.position = "fixed";
    notif.style.top = "20px";        // 🔥 posisi dari atas
    notif.style.left = "50%";        // tengah horizontal
    notif.style.transform = "translateX(-50%)"; // biar benar-benar tengah

    notif.style.background = "#16a34a";
    notif.style.color = "white";
    notif.style.padding = "14px 20px";
    notif.style.borderRadius = "12px";
    notif.style.boxShadow = "0 10px 25px rgba(0,0,0,0.2)";
    notif.style.zIndex = "9999";
    notif.style.fontSize = "14px";

    document.body.appendChild(notif);

    setTimeout(() => {
        notif.remove();
    }, 2000);
}

lucide.createIcons();

function toggleMejaMenu(){
    const menu = document.getElementById('submenuMeja');
    const arrow = document.getElementById('arrowMeja');

    menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    arrow.classList.toggle('rotate');
}
</script>

</body>
</html>