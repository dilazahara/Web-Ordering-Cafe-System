<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Meja</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; }

/* =======================
   TOPBAR
======================= */
.topbar {
    position: fixed; top: 0; left: 0; right: 0; height: 80px;
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.95);
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 30px; z-index: 1000;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.topbar-left { display: flex; align-items: center; gap: 20px; }
.topbar-left i {
    width: 24px; height: 24px; padding: 8px; border-radius: 12px;
    color: #475569; cursor: pointer; transition: all 0.3s ease;
}
.topbar-left i:hover { background: #f1f5f9; color: #1e293b; transform: scale(1.05); }

/* =======================
   SIDEBAR
======================= */
.sidebar {
    width: 240px; height: 100vh; position: fixed;
    background: linear-gradient(180deg, #0f172a, #1e1b4b);
    padding: 30px; padding-top: 100px;
    color: white; overflow-y: auto;
    transform: translateX(-100%);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 999; box-shadow: 4px 0 20px rgba(0,0,0,0.1);
    display: flex; flex-direction: column; gap: 8px;
}
.menu-section {
    font-size: 11px; letter-spacing: 1px;
    color: #a78bfa; margin: 18px 10px 8px; opacity: 0.7;
}
.sidebar.show { transform: translateX(0); }

/* =======================
   SIDEBAR MENU
======================= */
.sidebar a,
.menu-parent {
    display: flex; align-items: center; gap: 15px;
    padding: 12px 14px; border-radius: 12px;
    text-decoration: none; color: #94a3b8;
    font-weight: 400; font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.sidebar i { width: 20px; height: 20px; stroke-width: 2.5; color: #c4b5fd; }
.menu-parent { cursor: pointer; }
.menu-parent:hover,
.sidebar a:hover { background: rgba(255,255,255,0.06); color: white; transform: translateX(4px); }
.sidebar a.active {
    background: rgba(139, 92, 246, 0.25); color: #c4b5fd;
    box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4);
}

/* =======================
   MAIN
======================= */
.main { padding: 110px 30px 30px; max-width: 1100px; margin: 0 auto; }

/* =======================
   PAGE HEADER
======================= */
.page-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
}
.page-header h1 { font-size: 32px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
.page-header p  { font-size: 14px; color: #64748b; }

/* =======================
   BUTTON
======================= */
.btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 12px;
    background: #6366f1; color: white; border: none;
    font-size: 13px; font-weight: 600; cursor: pointer;
    text-decoration: none; font-family: 'Inter', sans-serif;
    transition: all 0.2s; white-space: nowrap;
}
.btn-add:hover { background: #4f46e5; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.35); }
.btn-add:active { transform: scale(0.97); }

/* =======================
   ALERT
======================= */
.alert-success {
    background: #f0fdf4; color: #15803d;
    border: 1px solid #bbf7d0; border-radius: 12px;
    padding: 12px 16px; margin-bottom: 20px;
    font-size: 14px; font-weight: 500;
    display: flex; align-items: center; gap: 8px;
    transition: opacity 0.4s;
}

/* =======================
   CARD
======================= */
.card {
    background: white; border-radius: 20px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
    overflow: hidden;
}

/* =======================
   TABLE
======================= */
.tbl-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead { background: #f8fafc; }
th {
    padding: 16px 16px; text-align: left;
    font-size: 12px; font-weight: 600; color: #475569;
    text-transform: uppercase; letter-spacing: 0.6px;
    border-bottom: 2px solid #e2e8f0; white-space: nowrap;
}
td { padding: 16px 16px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; }
tbody tr { transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #f8fafc; }

/* =======================
   STATUS BADGE
======================= */
.status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-kosong { background: #f0fdf4; color: #15803d; }
.status-terisi { background: #fef2f2; color: #dc2626; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; }
.dot-kosong { background: #22c55e; }
.dot-terisi { background: #ef4444; }

/* =======================
   ACTION BUTTONS
======================= */
.action-wrap { display: flex; align-items: center; gap: 6px; justify-content: center; }
.act-btn {
    width: 34px; height: 34px; border-radius: 9px; border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s; text-decoration: none; flex-shrink: 0;
}
.act-btn:active { transform: scale(0.9); }
.act-edit   { background: #eff6ff; color: #2563eb; border: 1.5px solid #dbeafe; }
.act-edit:hover   { background: #dbeafe; border-color: #93c5fd; }
.act-delete { background: #fef2f2; color: #dc2626; border: 1.5px solid #fee2e2; }
.act-delete:hover { background: #fee2e2; border-color: #fca5a5; }
.act-qr     { background: #f5f3ff; color: #7c3aed; border: 1.5px solid #ddd6fe; }
.act-qr:hover     { background: #ede9fe; border-color: #c4b5fd; }

/* =======================
   EMPTY STATE
======================= */
.empty-state { text-align: center; padding: 56px 20px; }
.empty-state p { color: #9ca3af; font-size: 14px; margin-top: 10px; }

/* ════════════════════════════
   QR MODAL
════════════════════════════ */
.modal-backdrop {
  display: none;
  position: fixed; inset: 0; z-index: 2000;
  background: rgba(15,23,42,.55);
  backdrop-filter: blur(4px);
  align-items: center; justify-content: center;
}
.modal-backdrop.open { display: flex; }

.modal {
  background: #fff;
  border-radius: 24px;
  width: 100%; max-width: 400px;
  margin: 16px;
  box-shadow: 0 24px 64px rgba(0,0,0,.2);
  overflow: hidden;
  animation: modalIn .25s cubic-bezier(.34,1.56,.64,1);
}
@keyframes modalIn {
  from { opacity: 0; transform: scale(.88) translateY(16px); }
  to   { opacity: 1; transform: scale(1)   translateY(0); }
}

.modal-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 24px 0;
}
.modal-title { font-size: 17px; font-weight: 700; color: #0f172a; }
.modal-close {
  width: 34px; height: 34px; border-radius: 9px; border: none;
  background: #f1f5f9; cursor: pointer; display: flex; align-items: center; justify-content: center;
  color: #64748b; transition: all .15s;
}
.modal-close:hover { background: #e2e8f0; color: #1e293b; }

.modal-body { padding: 20px 24px 0; text-align: center; }
.modal-table-label {
  display: inline-flex; align-items: center; gap: 8px;
  background: #f5f3ff; color: #6d28d9;
  border: 1px solid #ddd6fe; border-radius: 30px;
  padding: 5px 16px; font-size: 13px; font-weight: 700;
  margin-bottom: 20px;
}

.qr-wrap {
  background: #fff;
  border: 2px solid #e2e8f0;
  border-radius: 18px;
  padding: 20px;
  display: inline-flex;
  align-items: center; justify-content: center;
  margin-bottom: 16px;
}
#qrCanvas { display: block; }

.qr-url {
  font-size: 11.5px; color: #94a3b8; word-break: break-all;
  background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
  padding: 8px 12px; margin-bottom: 20px; text-align: left;
  font-family: monospace; line-height: 1.6;
}

.modal-footer {
  padding: 16px 24px 24px;
  display: flex; gap: 10px;
}
.btn-download {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 11px; border-radius: 12px; border: none; cursor: pointer;
  font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif;
  transition: all .18s;
}
.btn-dl-png { background: #6366f1; color: #fff; }
.btn-dl-png:hover { background: #4f46e5; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(99,102,241,.3); }
.btn-dl-print { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
.btn-dl-print:hover { background: #e2e8f0; color: #1e293b; }
</style>
</head>
<body>

<!-- ══ TOPBAR ══ -->
<div class="topbar">
    <div class="topbar-left">
        <i data-lucide="menu" onclick="toggleSidebar()"></i>
    </div>
</div>

<!-- ══ SIDEBAR ══ -->
<div class="sidebar" id="sidebar">

    <div class="menu-section">MAIN</div>
    <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i data-lucide="layout-dashboard"></i> Dashboard
    </a>

    <a href="/admin/order" class="{{ request()->is('admin/order*') ? 'active' : '' }}">
        <i data-lucide="clipboard-list"></i> Order
    </a>

    <div class="menu-section">KATALOG</div>

    <a href="/admin/menu" class="{{ request()->is('admin/menu*') ? 'active' : '' }}">
        <i data-lucide="utensils"></i> Menu
    </a>

    <a href="/admin/kategori" class="{{ request()->is('admin/kategori*') ? 'active' : '' }}">
        <i data-lucide="folder"></i> Kategori
    </a>

    <a href="/admin/addons" class="{{ request()->is('admin/addons*') ? 'active' : '' }}">
        <i data-lucide="plus-circle"></i> Add-ons
    </a>

    <div class="menu-section">OPERASIONAL</div>

    <a href="/admin/meja" class="{{ request()->is('admin/meja*') ? 'active' : '' }}">
        <i data-lucide="armchair"></i> Meja
    </a>

    <a href="/admin/pembayaran" class="{{ request()->is('admin/pembayaran*') ? 'active' : '' }}">
        <i data-lucide="credit-card"></i> Pembayaran
    </a>

    <div class="menu-section">ANALITIK</div>

    <a href="/admin/laporan" class="{{ request()->is('admin/laporan*') ? 'active' : '' }}">
        <i data-lucide="bar-chart-3"></i> Laporan
    </a>

    <div class="menu-section">SYSTEM</div>

    <a href="/admin/user" class="{{ request()->is('admin/user*') ? 'active' : '' }}">
        <i data-lucide="users"></i> User
    </a>

</div>

<!-- ══ MAIN ══ -->
<div class="main">

    <div class="page-header">
        <div>
            <h1>Data Meja</h1>
            <p>Kelola semua meja dan QR code untuk pemesanan pelanggan</p>
        </div>
        <a href="/admin/meja/create" class="btn-add">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            Tambah Meja
        </a>
    </div>

    @if(session('success'))
    <div class="alert-success" id="alertSuccess">
        <i data-lucide="check-circle" style="width:17px;height:17px;flex-shrink:0;"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Nomor Meja</th>
                        <th>Status</th>
                        <th>QR Code</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mejas as $meja)
                    <tr>
                        <td style="color:#9ca3af; font-size:13px;">{{ $loop->iteration }}</td>
                        <td style="font-weight:600; color:#111827;">Meja {{ $meja->nomor_meja }}</td>
                        <td>
                            @if($meja->status == 'kosong')
                                <span class="status-badge status-kosong">
                                    <span class="status-dot dot-kosong"></span> Kosong
                                </span>
                            @else
                                <span class="status-badge status-terisi">
                                    <span class="status-dot dot-terisi"></span> Terisi
                                </span>
                            @endif
                        </td>
                        <td>
                            <button
                                class="act-btn act-qr"
                                title="Lihat QR Code"
                                onclick="openQR('{{ $meja->nomor_meja }}', '{{ config('app.url') }}/customer/scan/{{ $meja->nomor_meja }}')"
                            >
                                <i data-lucide="qr-code" style="width:15px;height:15px;"></i>
                            </button>
                        </td>
                        <td>
                            <div class="action-wrap">
                                <a href="/admin/meja/edit/{{ $meja->id }}" class="act-btn act-edit" title="Edit">
                                    <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                                </a>
                                <form action="/admin/meja/delete/{{ $meja->id }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit" class="act-btn act-delete" title="Hapus"
                                        onclick="return confirm('Yakin ingin menghapus meja ini?')"
                                    >
                                        <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i data-lucide="armchair" style="width:40px;height:40px;color:#e5e7eb;"></i>
                                <p>Belum ada data meja</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ══ QR MODAL ══ -->
<div class="modal-backdrop" id="qrModal" onclick="closeOnBackdrop(event)">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">

        <div class="modal-header">
            <div class="modal-title" id="modalTitle">QR Code Meja</div>
            <button class="modal-close" onclick="closeQR()" aria-label="Tutup">
                <i data-lucide="x" style="width:16px;height:16px;"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="modal-table-label">
                <i data-lucide="armchair" style="width:15px;height:15px;"></i>
                <span id="qrMejaLabel">Meja —</span>
            </div>

            <div class="qr-wrap">
                <div id="qrCanvas"></div>
            </div>

            <div class="qr-url" id="qrUrlText">—</div>
        </div>

        <div class="modal-footer">
            <button class="btn-download btn-dl-png" onclick="downloadQR()">
                <i data-lucide="download" style="width:17px;height:17px;"></i>
                Download PNG
            </button>
            <button class="btn-download btn-dl-print" onclick="printQR()">
                <i data-lucide="printer" style="width:17px;height:17px;"></i>
                Print
            </button>
        </div>

    </div>
</div>

<script>
lucide.createIcons();

/* ── SIDEBAR ── */
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
}

/* ── ALERT AUTO-HIDE ── */
setTimeout(() => {
    const el = document.getElementById('alertSuccess');
    if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }
}, 3000);

/* ── QR MODAL ── */
let currentQrUrl   = '';
let currentMejaNom = '';
let qrInstance     = null;

function openQR(nomorMeja, url) {
    currentQrUrl   = url;
    currentMejaNom = nomorMeja;

    document.getElementById('qrMejaLabel').textContent = 'Meja ' + nomorMeja;
    document.getElementById('modalTitle').textContent  = 'QR Code — Meja ' + nomorMeja;
    document.getElementById('qrUrlText').textContent   = url;

    const canvas = document.getElementById('qrCanvas');
    canvas.innerHTML = '';

    qrInstance = new QRCode(canvas, {
        text:         url,
        width:        200,
        height:       200,
        colorDark:    '#0f172a',
        colorLight:   '#ffffff',
        correctLevel: QRCode.CorrectLevel.H,
    });

    document.getElementById('qrModal').classList.add('open');
    document.body.style.overflow = 'hidden';

    // Re-render lucide icons inside modal
    lucide.createIcons();
}

function closeQR() {
    document.getElementById('qrModal').classList.remove('open');
    document.body.style.overflow = '';
}

function closeOnBackdrop(e) {
    if (e.target === document.getElementById('qrModal')) closeQR();
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeQR();
});

/* ── DOWNLOAD PNG ── */
function downloadQR() {
    const canvas = document.getElementById('qrCanvas');
    const img    = canvas.querySelector('img');
    const cnv    = canvas.querySelector('canvas');

    if (cnv) {
        const pad = 24; const labelH = 36;
        const out = document.createElement('canvas');
        out.width  = cnv.width  + pad * 2;
        out.height = cnv.height + pad * 2 + labelH;
        const ctx  = out.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, out.width, out.height);
        ctx.drawImage(cnv, pad, pad);
        ctx.fillStyle = '#0f172a';
        ctx.font      = 'bold 15px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Meja ' + currentMejaNom, out.width / 2, cnv.height + pad + labelH - 10);
        const link    = document.createElement('a');
        link.download = `QR-Meja-${currentMejaNom}.png`;
        link.href     = out.toDataURL('image/png');
        link.click();
    } else if (img) {
        const link    = document.createElement('a');
        link.download = `QR-Meja-${currentMejaNom}.png`;
        link.href     = img.src;
        link.click();
    }
}

/* ── PRINT ── */
function printQR() {
    const canvas = document.getElementById('qrCanvas');
    const img    = canvas.querySelector('img');
    const cnv    = canvas.querySelector('canvas');
    const src    = cnv ? cnv.toDataURL() : (img ? img.src : '');

    const win = window.open('', '_blank', 'width=480,height=560');
    win.document.write(`
        <!DOCTYPE html><html><head>
        <title>QR Code Meja ${currentMejaNom}</title>
        <style>
            body { margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; font-family: Inter, sans-serif; background: #fff; }
            img  { width: 240px; height: 240px; image-rendering: pixelated; }
            h2   { margin-top: 16px; font-size: 20px; color: #0f172a; }
            p    { font-size: 12px; color: #64748b; margin-top: 6px; word-break: break-all; max-width: 260px; text-align: center; }
        </style></head><body>
        <img src="${src}" alt="QR Code Meja ${currentMejaNom}">
        <h2>Meja ${currentMejaNom}</h2>
        <p>${currentQrUrl}</p>
        <script>window.onload=()=>{ window.print(); }<\/script>
        </body></html>
    `);
    win.document.close();
}
</script>
</body>
</html>