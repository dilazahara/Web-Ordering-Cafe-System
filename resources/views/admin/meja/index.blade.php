@extends('layouts.admin')

@section('title', 'Data Meja')

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; }

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
.sidebar a,
.menu-parent {
    display: flex; align-items: center; gap: 15px;
    padding: 12px 14px; border-radius: 12px;
    text-decoration: none; color: #94a3b8;
    font-weight: 400; font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.sidebar svg { width: 20px; height: 20px; stroke-width: 2.5; color: #c4b5fd; flex-shrink: 0; }
.menu-parent { cursor: pointer; }
.menu-parent:hover,
.sidebar a:hover { background: rgba(255,255,255,0.06); color: white; transform: translateX(4px); }
.sidebar a.active {
    background: rgba(139, 92, 246, 0.25); color: #c4b5fd;
    box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4);
}

/* ✅ FIX: padding-top dikurangi dari 110px → 30px */
.main { padding: 50px 50px 50px; max-width: 1100px; margin: 0 auto; }

.page-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
}
.page-header h1 { font-size: 32px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
.page-header p  { font-size: 14px; color: #64748b; }

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
.btn-add svg { width: 15px; height: 15px; }

.alert-success {
    background: #f0fdf4; color: #15803d;
    border: 1px solid #bbf7d0; border-radius: 12px;
    padding: 12px 16px; margin-bottom: 20px;
    font-size: 14px; font-weight: 500;
    display: flex; align-items: center; gap: 8px;
    transition: opacity 0.4s;
}
.alert-success svg { width: 17px; height: 17px; flex-shrink: 0; }

.card {
    background: white; border-radius: 20px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
    overflow: hidden;
}

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

.status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-kosong { background: #f0fdf4; color: #15803d; }
.status-terisi { background: #fef2f2; color: #dc2626; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; }
.dot-kosong { background: #22c55e; }
.dot-terisi { background: #ef4444; }

.action-wrap { display: flex; align-items: center; gap: 6px; justify-content: center; }
.act-btn {
    width: 34px; height: 34px; border-radius: 9px; border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s; text-decoration: none; flex-shrink: 0;
    background: none;
}
.act-btn svg { width: 15px; height: 15px; }
.act-btn:active { transform: scale(0.9); }
.act-edit   { background: #eff6ff; color: #2563eb; border: 1.5px solid #dbeafe; }
.act-edit:hover   { background: #dbeafe; border-color: #93c5fd; }
.act-delete { background: #fef2f2; color: #dc2626; border: 1.5px solid #fee2e2; }
.act-delete:hover { background: #fee2e2; border-color: #fca5a5; }
.act-qr     { background: #f5f3ff; color: #7c3aed; border: 1.5px solid #ddd6fe; }
.act-qr:hover     { background: #ede9fe; border-color: #c4b5fd; }

.empty-state { text-align: center; padding: 56px 20px; }
.empty-state svg { width: 40px; height: 40px; color: #e5e7eb; margin: 0 auto 10px; display: block; }
.empty-state p { color: #9ca3af; font-size: 14px; margin-top: 10px; }

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
    background: #f1f5f9; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #64748b; transition: all .15s;
}
.modal-close:hover { background: #e2e8f0; color: #1e293b; }
.modal-close svg { width: 16px; height: 16px; }

.modal-body { padding: 20px 24px 0; text-align: center; }
.modal-table-label {
    display: inline-flex; align-items: center; gap: 8px;
    background: #f5f3ff; color: #6d28d9;
    border: 1px solid #ddd6fe; border-radius: 30px;
    padding: 5px 16px; font-size: 13px; font-weight: 700;
    margin-bottom: 20px;
}
.modal-table-label svg { width: 15px; height: 15px; }

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
.btn-download svg { width: 17px; height: 17px; }
.btn-dl-png { background: #6366f1; color: #fff; }
.btn-dl-png:hover { background: #4f46e5; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(99,102,241,.3); }
.btn-dl-print { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
.btn-dl-print:hover { background: #e2e8f0; color: #1e293b; }

.delete-icon-wrap {
    width: 64px; height: 64px; border-radius: 18px;
    background: #fef2f2; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 16px;
}
.delete-icon-wrap svg { width: 28px; height: 28px; color: #dc2626; }

.btn-secondary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 11px;
    background: #f1f5f9; color: #475569;
    border: 1.5px solid #e2e8f0; font-family: 'Inter', sans-serif;
    font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.btn-secondary:hover { background: #e2e8f0; }

.btn-danger {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 11px;
    background: #dc2626; color: white; border: none;
    font-family: 'Inter', sans-serif;
    font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.btn-danger:hover { background: #b91c1c; }
.btn-danger svg { width: 15px; height: 15px; }
</style>
@endpush

@section('content')
<div class="main">

    <div class="page-header">
        <div>
            <h1>Data Meja</h1>
            <p>Kelola semua meja dan QR code untuk pemesanan pelanggan</p>
        </div>
        <a href="/admin/meja/create" class="btn-add">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Meja
        </a>
    </div>

    @if(session('success'))
    <div class="alert-success" id="alertSuccess">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
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
                                onclick="openQR('{{ $meja->nomor_meja }}', '{{ rtrim(config('app.url'), '/') }}/customer/scan/{{ $meja->nomor_meja }}/{{ $meja->qr_token }}', '{{ $meja->id }}')"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                                    <rect x="3" y="14" width="7" height="7"/>
                                    <line x1="14" y1="14" x2="14" y2="14"/><line x1="17" y1="14" x2="17" y2="14"/>
                                    <line x1="20" y1="14" x2="20" y2="14"/><line x1="14" y1="17" x2="14" y2="17"/>
                                    <line x1="17" y1="17" x2="17" y2="17"/><line x1="20" y1="17" x2="20" y2="17"/>
                                    <line x1="14" y1="20" x2="14" y2="20"/><line x1="17" y1="20" x2="17" y2="20"/>
                                    <line x1="20" y1="20" x2="20" y2="20"/>
                                </svg>
                            </button>
                        </td>
                        <td>
                            <div class="action-wrap">
                                <a href="/admin/meja/edit/{{ $meja->id }}" class="act-btn act-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>

                                <button
                                    type="button"
                                    class="act-btn act-delete"
                                    title="Hapus"
                                    onclick="openDeleteModal({{ $meja->id }}, '{{ $meja->nomor_meja }}')"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        <path d="M10 11v6"/><path d="M14 11v6"/>
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3"/>
                                    <path d="M2 11v5a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v2H6v-2a2 2 0 0 0-4 0z"/>
                                </svg>
                                <p>Belum ada data meja</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>{{-- /main --}}


{{-- MODAL: QR CODE --}}
<div class="modal-backdrop" id="qrModal">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">

        <div class="modal-header">
            <div class="modal-title" id="modalTitle">QR Code Meja</div>
            <button class="modal-close" onclick="closeModal('qrModal')" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <div class="modal-table-label">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3"/>
                    <path d="M2 11v5a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v2H6v-2a2 2 0 0 0-4 0z"/>
                </svg>
                <span id="qrMejaLabel">Meja —</span>
            </div>

            <div class="qr-wrap">
                <div id="qrCanvas"></div>
            </div>

            <div class="qr-url" id="qrUrlText">—</div>
        </div>

        <div class="modal-footer">
            <button class="btn-download btn-dl-png" onclick="downloadQR()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Download PNG
            </button>
            <button class="btn-download btn-dl-print" onclick="printQR()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                Print
            </button>
        </div>

        {{-- Form tersembunyi untuk Refresh QR --}}
        <form id="refreshQrForm" method="POST" action="" style="display:none;">
            @csrf
        </form>

        <div style="padding: 0 28px 20px; text-align:center;">
            <button
                onclick="confirmRefreshQR()"
                style="background:none; border:none; color:#ef4444; font-size:12px; cursor:pointer;
                       text-decoration:underline; padding:4px 8px;"
                title="Buat token QR baru — QR lama dan semua sesi aktif meja ini akan langsung tidak berlaku"
            >
                &#x1F504; Refresh QR (invalidate QR lama)
            </button>
        </div>

    </div>
</div>


{{-- MODAL: HAPUS MEJA --}}
<div class="modal-backdrop" id="modalHapus">
    <div class="modal" style="max-width:400px;" role="dialog" aria-modal="true">

        <div class="modal-body" style="padding: 32px 28px 8px; text-align: center;">
            <div class="delete-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
            </div>
            <h3 style="font-size:18px; font-weight:700; margin-bottom:8px; color:#0f172a;">Hapus Meja?</h3>
            <p style="font-size:14px; color:#64748b; margin-bottom:4px;">Kamu akan menghapus:</p>
            <p style="font-weight:700; color:#dc2626; font-size:15px;" id="deleteMejaText"></p>
        </div>

        <div class="modal-footer" style="justify-content:center; gap:12px; padding: 20px 28px 28px;">
            <button type="button" class="btn-secondary" onclick="closeModal('modalHapus')">Batal</button>
            <form id="deleteForm" action="" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6"/><path d="M14 11v6"/>
                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                    Ya, Hapus
                </button>
            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}

document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
    backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) {
            backdrop.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-backdrop.open').forEach(function(m) {
            m.classList.remove('open');
        });
        document.body.style.overflow = '';
    }
});

function openDeleteModal(id, nomorMeja) {
    document.getElementById('deleteMejaText').textContent = 'Meja ' + nomorMeja;
    document.getElementById('deleteForm').action = '/admin/meja/delete/' + id;
    openModal('modalHapus');
}

var currentQRUrl     = '';
var currentMejaNomor = '';
var currentMejaId    = '';
var qrInstance       = null;

function openQR(nomorMeja, url, mejaId) {
    currentQRUrl     = url;
    currentMejaNomor = nomorMeja;
    currentMejaId    = mejaId || '';

    document.getElementById('qrMejaLabel').textContent = 'Meja ' + nomorMeja;
    document.getElementById('qrUrlText').textContent   = url;

    var canvas = document.getElementById('qrCanvas');
    canvas.innerHTML = '';

    qrInstance = new QRCode(canvas, {
        text:         url,
        width:        220,
        height:       220,
        colorDark:    '#0f172a',
        colorLight:   '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });

    openModal('qrModal');
}

function downloadQR() {
    var canvas  = document.getElementById('qrCanvas');
    var img     = canvas.querySelector('img');
    var cvs     = canvas.querySelector('canvas');
    var dataUrl;

    if (cvs) {
        dataUrl = cvs.toDataURL('image/png');
    } else if (img) {
        dataUrl = img.src;
    } else {
        alert('QR belum siap, coba lagi.');
        return;
    }

    var link      = document.createElement('a');
    link.href     = dataUrl;
    link.download = 'qr-meja-' + currentMejaNomor + '.png';
    link.click();
}

function printQR() {
    var canvas  = document.getElementById('qrCanvas');
    var img     = canvas.querySelector('img');
    var cvs     = canvas.querySelector('canvas');
    var dataUrl;

    if (cvs) {
        dataUrl = cvs.toDataURL('image/png');
    } else if (img) {
        dataUrl = img.src;
    } else {
        alert('QR belum siap, coba lagi.');
        return;
    }

    var win = window.open('', '_blank');
    win.document.write(
        '<html><head><title>QR Meja ' + currentMejaNomor + '</title>' +
        '<style>' +
            'body { display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:100vh; margin:0; font-family:sans-serif; }' +
            'img  { width:260px; height:260px; border:2px solid #e2e8f0; border-radius:12px; padding:12px; }' +
            'h2   { margin:16px 0 6px; font-size:20px; color:#0f172a; }' +
            'p    { font-size:12px; color:#94a3b8; word-break:break-all; max-width:300px; text-align:center; }' +
        '</style></head><body>' +
        '<img src="' + dataUrl + '" alt="QR Code">' +
        '<h2>Meja ' + currentMejaNomor + '</h2>' +
        '<p>' + currentQRUrl + '</p>' +
        '<script>window.onload=function(){window.print();window.close();}<\/script>' +
        '</body></html>'
    );
    win.document.close();
}

var alertEl = document.getElementById('alertSuccess');
if (alertEl) {
    setTimeout(function() {
        alertEl.style.opacity = '0';
        setTimeout(function() { alertEl.remove(); }, 400);
    }, 4000);
}

// ── Refresh QR Token ──────────────────────────────────────────────────────────
// Membuat token QR baru — QR fisik lama dan semua sesi aktif meja ini
// akan langsung tidak berlaku.
function confirmRefreshQR() {
    if (!currentMejaId) {
        alert('ID meja tidak ditemukan. Tutup modal dan coba lagi.');
        return;
    }
    var konfirmasi = confirm(
        'Refresh QR Meja ' + currentMejaNomor + '?\n\n' +
        'PERHATIAN: QR Code fisik yang sudah dicetak akan TIDAK BERLAKU.\n' +
        'Semua tamu yang sedang aktif dengan QR lama akan diminta scan ulang.\n\n' +
        'Lanjutkan?'
    );
    if (!konfirmasi) return;

    var form   = document.getElementById('refreshQrForm');
    form.action = '/admin/meja/refresh-qr/' + currentMejaId;
    form.submit();
}
</script>
@endpush