@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: #1e293b; }

/* ── TOPBAR ── */
.topbar {
    position: fixed; top: 0; left: 0; right: 0; height: 72px;
    backdrop-filter: blur(20px);
    background: rgba(255,255,255,0.97);
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 28px; z-index: 1000;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.topbar-left { display: flex; align-items: center; gap: 16px; }
.topbar-left button {
    background: none; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    width: 38px; height: 38px; border-radius: 10px;
    color: #475569; transition: all 0.2s;
}
.topbar-left button:hover { background: #f1f5f9; color: #1e293b; }
.topbar-brand { font-size: 16px; font-weight: 700; color: #0f172a; letter-spacing: -0.3px; }

/* ── SIDEBAR ── */
.sidebar {
    width: 248px; height: 100vh; position: fixed;
    background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
    padding: 24px 20px; padding-top: 92px;
    color: white; overflow-y: auto;
    transform: translateX(-100%);
    transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
    z-index: 999;
    box-shadow: 4px 0 24px rgba(0,0,0,0.15);
}
.sidebar.show { transform: translateX(0); }
.menu-section {
    font-size: 10.5px; letter-spacing: 1.2px; font-weight: 700;
    color: #7c3aed; margin: 20px 10px 8px; opacity: 0.8;
    text-transform: uppercase;
}
.sidebar a, .menu-parent {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 14px; border-radius: 12px;
    text-decoration: none; color: #94a3b8;
    font-size: 14px; font-weight: 500;
    transition: all 0.25s; margin-bottom: 2px;
}
.sidebar a:hover, .menu-parent:hover {
    background: rgba(255,255,255,0.07); color: white; transform: translateX(3px);
}
.sidebar a.active {
    background: rgba(124,58,237,0.22); color: #c4b5fd;
    box-shadow: inset 0 0 0 1px rgba(124,58,237,0.35);
}
.sidebar i { width: 18px; height: 18px; stroke-width: 2; color: #a78bfa; flex-shrink: 0; }

/* ── OVERLAY ── */
.overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.35); z-index: 998; backdrop-filter: blur(2px);
}
.overlay.show { display: block; }

/* ── MAIN ── */
.main { padding: 100px 28px 40px; max-width: 1180px; margin: 0 auto; }

/* ── PAGE HEADER ── */
.page-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 28px; flex-wrap: wrap; gap: 14px;
}
.page-header-text h1 {
    font-size: 28px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; margin-bottom: 4px;
}
.page-header-text p { font-size: 14px; color: #64748b; }
.btn-add {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 20px; border-radius: 12px;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: white; border: none; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 14px; font-weight: 600; cursor: pointer;
    transition: all 0.2s; white-space: nowrap;
    box-shadow: 0 4px 12px rgba(109,40,217,0.3);
}
.btn-add:hover { filter: brightness(1.1); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(109,40,217,0.4); }
.btn-add:active { transform: scale(0.97); }

/* ── ALERT ── */
.alert {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 16px; border-radius: 12px;
    margin-bottom: 24px; font-size: 14px; font-weight: 500;
    animation: fadeIn 0.3s ease; transition: opacity 0.3s;
}
.alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.alert-error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
@keyframes fadeIn { from { opacity:0; transform: translateY(-8px); } to { opacity:1; transform: translateY(0); } }

/* ── STATS ROW ── */
.stats-row {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr));
    gap: 16px; margin-bottom: 28px;
}
.stat-card {
    background: white; border-radius: 16px; padding: 18px 20px;
    border: 1px solid #f1f5f9; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    display: flex; align-items: center; gap: 14px;
}
.stat-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;
}
.stat-val { font-size: 22px; font-weight: 800; color: #0f172a; }
.stat-lbl { font-size: 12px; color: #64748b; margin-top: 1px; }

/* ── TABLE CARD ── */
.table-card {
    background: white; border-radius: 20px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06); overflow: hidden;
}
.table-card-header {
    padding: 20px 24px; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
}
.table-card-header h2 { font-size: 16px; font-weight: 700; color: #0f172a; }
.search-wrap {
    position: relative; display: flex; align-items: center;
}
.search-wrap i { position: absolute; left: 12px; width: 15px; height: 15px; color: #94a3b8; }
.search-input {
    padding: 9px 14px 9px 36px; border: 1.5px solid #e2e8f0;
    border-radius: 10px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif;
    outline: none; width: 220px; color: #1e293b; transition: border-color 0.2s;
}
.search-input:focus { border-color: #a78bfa; box-shadow: 0 0 0 3px rgba(167,139,250,0.12); }

/* ── TABLE ── */
table { width: 100%; border-collapse: collapse; }
thead th {
    background: #f8fafc; padding: 12px 20px;
    font-size: 11.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.7px; color: #64748b; text-align: left;
    border-bottom: 1px solid #f1f5f9;
}
tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fafbfc; }
tbody td { padding: 14px 20px; font-size: 14px; color: #334155; vertical-align: middle; }

/* ── PAYMENT ICON CELL ── */
.pay-cell { display: flex; align-items: center; gap: 12px; }
.pay-thumb {
    width: 42px; height: 42px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.pay-thumb.cash { background: #fff7ed; }
.pay-thumb.qris { background: #f5f3ff; }
.pay-thumb.bank { background: #eff6ff; }
.pay-thumb.custom { background: #f0fdf4; }
.pay-cell-name { font-weight: 600; color: #0f172a; font-size: 14px; }
.pay-cell-kode {
    font-size: 11px; color: #94a3b8; font-weight: 500;
    background: #f1f5f9; padding: 2px 7px; border-radius: 6px; margin-top: 2px; display: inline-block;
}

/* ── STATUS BADGE ── */
.badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
}
.badge-on  { background: #f0fdf4; color: #15803d; }
.badge-off { background: #fef2f2; color: #dc2626; }
.badge-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.dot-on  { background: #22c55e; }
.dot-off { background: #ef4444; }

/* ── TOGGLE ── */
.toggle-form { margin: 0; }
.switch { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
    position: absolute; cursor: pointer;
    top:0; left:0; right:0; bottom:0;
    background: #cbd5e1; border-radius: 24px; transition: 0.25s;
}
.slider:before {
    position: absolute; content: "";
    height: 18px; width: 18px; left: 3px; bottom: 3px;
    background: white; border-radius: 50%; transition: 0.25s;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}
input:checked + .slider { background: #059669; }
input:checked + .slider:before { transform: translateX(20px); }

/* ── ACTION BUTTONS ── */
.actions { display: flex; align-items: center; gap: 6px; }
.btn-icon {
    width: 34px; height: 34px; border-radius: 9px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1.5px solid; cursor: pointer; transition: all 0.2s;
    text-decoration: none; background: none;
}
.btn-icon svg { width: 15px; height: 15px; }
.btn-edit { border-color: #dbeafe; background: #eff6ff; color: #2563eb; }
.btn-edit:hover { background: #dbeafe; border-color: #93c5fd; }
.btn-config { border-color: #e0e7ff; background: #f5f3ff; color: #7c3aed; }
.btn-config:hover { background: #e0e7ff; border-color: #c4b5fd; }
.btn-delete { border-color: #fee2e2; background: #fef2f2; color: #dc2626; }
.btn-delete:hover { background: #fee2e2; border-color: #fca5a5; }

/* ── EMPTY STATE ── */
.empty-state {
    text-align: center; padding: 64px 24px;
    color: #94a3b8;
}
.empty-state svg { width: 48px; height: 48px; margin: 0 auto 16px; display: block; color: #cbd5e1; }
.empty-state h3 { font-size: 16px; font-weight: 600; color: #64748b; margin-bottom: 6px; }
.empty-state p { font-size: 13px; }

/* ═══════════════════════════════
   MODAL
═══════════════════════════════ */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.45); z-index: 2000;
    align-items: center; justify-content: center;
    backdrop-filter: blur(3px); padding: 20px;
}
.modal-overlay.open { display: flex; }
.modal {
    background: white; border-radius: 24px;
    width: 100%; max-width: 560px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.2);
    animation: modalIn 0.3s cubic-bezier(0.34,1.56,0.64,1);
    max-height: 90vh; overflow-y: auto;
}
@keyframes modalIn {
    from { opacity:0; transform: scale(0.88) translateY(20px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
}
.modal-header {
    padding: 24px 28px 20px; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
}
.modal-header h3 { font-size: 18px; font-weight: 700; color: #0f172a; }
.modal-close {
    width: 36px; height: 36px; border-radius: 10px;
    border: none; background: #f1f5f9; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #64748b; transition: all 0.2s; flex-shrink: 0;
}
.modal-close:hover { background: #e2e8f0; color: #1e293b; }
.modal-close svg { width: 16px; height: 16px; }
.modal-body { padding: 24px 28px; }
.modal-footer {
    padding: 16px 28px 24px;
    display: flex; gap: 10px; justify-content: flex-end;
}

/* ── FORM ELEMENTS ── */
.form-row { display: grid; gap: 16px; }
.form-row.cols-2 { grid-template-columns: 1fr 1fr; }
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 13px; font-weight: 600; color: #374151; }
.form-group small { font-size: 11.5px; color: #94a3b8; margin-top: -2px; }
.form-control {
    padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 11px;
    font-size: 13.5px; font-family: 'Plus Jakarta Sans', sans-serif;
    outline: none; color: #1e293b; transition: border-color 0.2s; background: #fff;
}
.form-control:focus { border-color: #a78bfa; box-shadow: 0 0 0 3px rgba(167,139,250,0.12); }
select.form-control { cursor: pointer; }

/* ── KODE TYPE PICKER ── */
.type-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.type-opt { display: none; }
.type-label {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    padding: 14px 10px; border-radius: 14px;
    border: 2px solid #e2e8f0; cursor: pointer;
    transition: all 0.2s; text-align: center;
}
.type-label:hover { border-color: #c4b5fd; background: #f5f3ff; }
.type-opt:checked + .type-label {
    border-color: #7c3aed; background: #f5f3ff;
    box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
}
.type-emoji { font-size: 28px; }
.type-name { font-size: 13px; font-weight: 600; color: #374151; }
.type-sub  { font-size: 11px; color: #94a3b8; }

/* ── QR PREVIEW ── */
.qr-preview-area { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 14px; }
.qr-preview-box {
    width: 90px; height: 90px; border-radius: 12px;
    border: 2px dashed #e2e8f0; background: #fff;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex-shrink: 0;
}
.qr-preview-box img { width: 100%; height: 100%; object-fit: cover; }
.qr-empty-text { font-size: 11px; color: #cbd5e1; text-align: center; }
.qr-upload-side { flex: 1; }
.qr-upload-side p { font-size: 12px; color: #64748b; margin-bottom: 10px; line-height: 1.5; }
.file-input-wrap { position: relative; display: inline-block; }
.file-input-wrap input[type="file"] { position: absolute; inset:0; opacity:0; cursor:pointer; }
.file-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; background: white;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    font-size: 12px; font-weight: 500; color: #475569; cursor: pointer; transition: all 0.2s;
}
.file-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
.file-btn svg { width: 13px; height: 13px; }

/* ── BUTTONS ── */
.btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 11px;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: white; border: none; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); }
.btn-primary svg { width: 15px; height: 15px; }
.btn-secondary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 11px;
    background: #f1f5f9; color: #475569;
    border: 1.5px solid #e2e8f0; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.btn-secondary:hover { background: #e2e8f0; }
.btn-danger {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 11px;
    background: #dc2626; color: white; border: none;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.btn-danger:hover { background: #b91c1c; }
.btn-danger svg { width: 15px; height: 15px; }

/* ── DELETE CONFIRM ── */
.delete-icon-wrap {
    width: 64px; height: 64px; border-radius: 18px;
    background: #fef2f2; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 16px;
}
.delete-icon-wrap svg { width: 28px; height: 28px; color: #dc2626; }

/* ── QRIS CONFIG MODAL ── */
.qris-modal-preview {
    width: 120px; height: 120px; border-radius: 16px;
    border: 2px dashed #e2e8f0; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    background: #f8fafc; margin: 0 auto 16px;
}
.qris-modal-preview img { width: 100%; height: 100%; object-fit: cover; }
.qris-modal-preview svg { width: 36px; height: 36px; color: #cbd5e1; }

@media (max-width: 640px) {
    .form-row.cols-2 { grid-template-columns: 1fr; }
    .table-card { overflow-x: auto; }
    table { min-width: 600px; }
    .main { padding: 88px 16px 30px; }
    .page-header { flex-direction: column; }
}
</style>
@endpush

@section('content')
<div class="main">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-text">
            <h1>Kelola Pembayaran</h1>
            <p>Tambah, ubah, hapus, dan aktifkan metode pembayaran.</p>
        </div>
        <button class="btn-add" onclick="openModal('modalTambah')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Metode
        </button>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success" id="alertMsg">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error" id="alertMsg">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
    <div class="alert alert-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div>
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f5f3ff;">💳</div>
            <div>
                <div class="stat-val">{{ $paymentMethods->count() }}</div>
                <div class="stat-lbl">Total Metode</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">✅</div>
            <div>
                <div class="stat-val">{{ $paymentMethods->where('aktif', true)->count() }}</div>
                <div class="stat-lbl">Aktif</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;">🚫</div>
            <div>
                <div class="stat-val">{{ $paymentMethods->where('aktif', false)->count() }}</div>
                <div class="stat-lbl">Nonaktif</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed;">📱</div>
            <div>
                <div class="stat-val">{{ $paymentMethods->where('kode', 'qris')->count() }}</div>
                <div class="stat-lbl">QRIS</div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <h2>Daftar Metode Pembayaran</h2>
            <div class="search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:12px;width:15px;height:15px;color:#94a3b8;pointer-events:none;"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" class="search-input" id="searchInput"
                    placeholder="Cari metode..." onkeyup="filterTable()">
            </div>
        </div>

        @if($paymentMethods->isEmpty())
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            <h3>Belum ada metode pembayaran</h3>
            <p>Klik "Tambah Metode" untuk menambahkan metode pembayaran pertama.</p>
        </div>
        @else
        <table id="payTable">
            <thead>
                <tr>
                    <th>Metode</th>
                    <th>Kode</th>
                    <th>Status</th>
                    <th>Aktif/Nonaktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentMethods as $pm)
                <tr data-search="{{ strtolower($pm->nama . ' ' . $pm->kode) }}">
                    <td>
                        <div class="pay-cell">
                            <div class="pay-thumb {{ in_array($pm->kode, ['cash','qris','bank']) ? $pm->kode : 'custom' }}">
                                @if($pm->kode === 'cash') 💵
                                @elseif($pm->kode === 'qris') 📱
                                @elseif($pm->kode === 'bank') 🏦
                                @else 💳
                                @endif
                            </div>
                            <div>
                                <div class="pay-cell-name">{{ $pm->nama }}</div>
                                <span class="pay-cell-kode">{{ $pm->kode }}</span>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;font-size:13px;color:#7c3aed;">{{ $pm->kode }}</td>
                    <td>
                        <span class="badge {{ $pm->aktif ? 'badge-on' : 'badge-off' }}">
                            <span class="badge-dot {{ $pm->aktif ? 'dot-on' : 'dot-off' }}"></span>
                            {{ $pm->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.pembayaran.toggle', $pm->id) }}" method="POST" class="toggle-form">
                            @csrf
                            <label class="switch" title="{{ $pm->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <input type="checkbox"
                                    {{ $pm->aktif ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <span class="slider"></span>
                            </label>
                        </form>
                    </td>
                    <td>
                        <div class="actions">
                            <!-- Edit -->
                            <button class="btn-icon btn-edit" title="Edit"
                                onclick="openEdit({{ $pm->id }}, '{{ addslashes($pm->nama) }}', '{{ $pm->kode }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>

                            {{-- Config QRIS --}}
                            @if($pm->kode === 'qris')
                            <button class="btn-icon btn-config" title="Konfigurasi QRIS"
                                onclick="openQrisConfig({{ $pm->id }}, '{{ addslashes($pm->nama_rekening ?? '') }}', '{{ addslashes($pm->no_rekening ?? '') }}', '{{ $pm->qris_image ? asset('storage/'.$pm->qris_image) : '' }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                            </button>
                            @endif

                            <!-- Delete -->
                            <button class="btn-icon btn-delete" title="Hapus"
                                onclick="openDelete({{ $pm->id }}, '{{ addslashes($pm->nama) }}')">
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
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>{{-- /main --}}


{{-- ════════════════════════════════
     MODAL: TAMBAH METODE
════════════════════════════════ --}}
<div class="modal-overlay" id="modalTambah">
    <div class="modal">
        <div class="modal-header">
            <h3>Tambah Metode Pembayaran</h3>
            <button class="modal-close" onclick="closeModal('modalTambah')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.pembayaran.store') }}" method="POST">
            @csrf
            <div class="modal-body">

                {{-- PILIH TIPE --}}
                <div class="form-group" style="margin-bottom:18px;">
                    <label>Tipe Pembayaran</label>
                    <div class="type-grid" id="typeGrid">
                        <input type="radio" name="kode" id="t_cash" value="cash" class="type-opt" checked>
                        <label for="t_cash" class="type-label">
                            <span class="type-emoji">💵</span>
                            <span class="type-name">Tunai</span>
                            <span class="type-sub">Bayar di kasir</span>
                        </label>

                        <input type="radio" name="kode" id="t_qris" value="qris" class="type-opt">
                        <label for="t_qris" class="type-label">
                            <span class="type-emoji">📱</span>
                            <span class="type-name">QRIS</span>
                            <span class="type-sub">Scan QR Code</span>
                        </label>

                        <input type="radio" name="kode" id="t_bank" value="bank" class="type-opt">
                        <label for="t_bank" class="type-label">
                            <span class="type-emoji">🏦</span>
                            <span class="type-name">Transfer Bank</span>
                            <span class="type-sub">Via rekening</span>
                        </label>

                        <input type="radio" name="kode" id="t_lain" value="lain" class="type-opt">
                        <label for="t_lain" class="type-label">
                            <span class="type-emoji">💳</span>
                            <span class="type-name">Lainnya</span>
                            <span class="type-sub">Metode custom</span>
                        </label>
                    </div>
                </div>

                {{-- Kode custom (muncul jika pilih "lain") --}}
                <div class="form-group" id="customKodeWrap" style="display:none;margin-bottom:16px;">
                    <label>Kode Unik <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="kode_custom" class="form-control"
                        placeholder="Contoh: ewallet, ovo, gopay"
                        pattern="[a-z0-9_]+" title="Huruf kecil, angka, underscore">
                    <small>Huruf kecil, angka, underscore. Contoh: <code>gopay</code></small>
                </div>

                <div class="form-row cols-2">
                    <div class="form-group">
                        <label>Nama Metode <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="nama" class="form-control" required
                            placeholder="Contoh: Tunai (Cash)" id="tambahNamaInput">
                        <small>Nama yang tampil di checkout</small>
                    </div>
                    <div class="form-group">
                        <label>Status Awal</label>
                        <select name="aktif" class="form-control">
                            <option value="1">✅ Aktif</option>
                            <option value="0">🚫 Nonaktif</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ════════════════════════════════
     MODAL: EDIT METODE
════════════════════════════════ --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Metode Pembayaran</h3>
            <button class="modal-close" onclick="closeModal('modalEdit')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form id="editForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label>Nama Metode <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kode</label>
                        <input type="text" name="kode" id="editKode" class="form-control">
                        <small>Tidak wajib diubah</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ════════════════════════════════
     MODAL: CONFIG QRIS
════════════════════════════════ --}}
<div class="modal-overlay" id="modalQris">
    <div class="modal">
        <div class="modal-header">
            <h3>⚙️ Konfigurasi QRIS</h3>
            <button class="modal-close" onclick="closeModal('modalQris')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form id="qrisForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">

                {{-- QR Preview --}}
                <div class="form-group" style="margin-bottom:20px;">
                    <label>Gambar QR Code</label>
                    <div class="qris-modal-preview" id="qrisPreviewBox">
                        <svg id="qrisEmptyIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <img id="qrisPreviewImg" src="" alt="QR Preview" style="display:none;width:100%;height:100%;object-fit:cover;">
                    </div>
                    <div style="text-align:center;">
                        <div class="file-input-wrap">
                            <div class="file-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="16 16 12 12 8 16"/>
                                    <line x1="12" y1="12" x2="12" y2="21"/>
                                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                                </svg>
                                Pilih Gambar
                            </div>
                            <input type="file" name="image" accept="image/*" onchange="previewQrisModal(this)">
                        </div>
                        <p style="font-size:11.5px;color:#94a3b8;margin-top:8px;">PNG/JPG, maks 2MB</p>
                    </div>
                </div>

                <div class="form-row cols-2">
                    <div class="form-group">
                        <label>Nama Merchant / Rekening</label>
                        <input type="text" name="nama_merchant" id="qrisNamaMerchant" class="form-control"
                            placeholder="Contoh: Warung Makan Bahagia">
                    </div>
                    <div class="form-group">
                        <label>Nomor Rekening / ID Merchant</label>
                        <input type="text" name="nomor_merchant" id="qrisNomorMerchant" class="form-control"
                            placeholder="Contoh: 08123456789">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modalQris')">Batal</button>
                <button type="submit" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan QRIS
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ════════════════════════════════
     MODAL: HAPUS
════════════════════════════════ --}}
<div class="modal-overlay" id="modalHapus">
    <div class="modal" style="max-width:400px;">
        <div class="modal-body" style="padding-top:32px;text-align:center;">
            <div class="delete-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
            </div>
            <h3 style="font-size:18px;font-weight:700;margin-bottom:8px;color:#0f172a;">Hapus Metode?</h3>
            <p style="font-size:14px;color:#64748b;margin-bottom:4px;">
                Kamu akan menghapus metode pembayaran:
            </p>
            <p style="font-weight:700;color:#dc2626;font-size:15px;" id="deleteNamaText"></p>
            <p style="font-size:13px;color:#94a3b8;margin-top:8px;">
                Tindakan ini tidak bisa dibatalkan.
            </p>
        </div>
        <div class="modal-footer" style="justify-content:center;gap:12px;">
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
<script>
/* ═══════════════════════════════════════
   MODAL HELPERS
═══════════════════════════════════════ */
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}

// Tutup modal jika klik overlay (luar modal)
document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
});

// Tutup modal dengan tombol ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.open').forEach(function(m) {
            m.classList.remove('open');
        });
        document.body.style.overflow = '';
    }
});

/* ═══════════════════════════════════════
   MODAL: EDIT
═══════════════════════════════════════ */
function openEdit(id, nama, kode) {
    document.getElementById('editNama').value = nama;
    document.getElementById('editKode').value = kode;
    document.getElementById('editForm').action = '/admin/pembayaran/' + id;
    openModal('modalEdit');
}

/* ═══════════════════════════════════════
   MODAL: HAPUS
═══════════════════════════════════════ */
function openDelete(id, nama) {
    document.getElementById('deleteNamaText').textContent = nama;
    document.getElementById('deleteForm').action = '/admin/pembayaran/' + id;
    openModal('modalHapus');
}

/* ═══════════════════════════════════════
   MODAL: QRIS CONFIG
═══════════════════════════════════════ */
function openQrisConfig(id, namaMerchant, nomorMerchant, imageUrl) {
    document.getElementById('qrisNamaMerchant').value = namaMerchant;
    document.getElementById('qrisNomorMerchant').value = nomorMerchant;
    document.getElementById('qrisForm').action = '/admin/pembayaran/' + id + '/qris';

    var img = document.getElementById('qrisPreviewImg');
    var icon = document.getElementById('qrisEmptyIcon');

    if (imageUrl && imageUrl !== '') {
        img.src = imageUrl;
        img.style.display = 'block';
        icon.style.display = 'none';
    } else {
        img.src = '';
        img.style.display = 'none';
        icon.style.display = 'block';
    }

    openModal('modalQris');
}

/* ═══════════════════════════════════════
   QRIS IMAGE PREVIEW
═══════════════════════════════════════ */
function previewQrisModal(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.getElementById('qrisPreviewImg');
            var icon = document.getElementById('qrisEmptyIcon');
            img.src = e.target.result;
            img.style.display = 'block';
            icon.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/* ═══════════════════════════════════════
   TYPE PICKER: tampilkan/sembunyikan kode custom
═══════════════════════════════════════ */
document.querySelectorAll('input[name="kode"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var wrap = document.getElementById('customKodeWrap');
        if (this.value === 'lain') {
            wrap.style.display = 'flex';
        } else {
            wrap.style.display = 'none';
        }
    });
});

/* ═══════════════════════════════════════
   SEARCH / FILTER TABLE
═══════════════════════════════════════ */
function filterTable() {
    var keyword = document.getElementById('searchInput').value.toLowerCase();
    var rows = document.querySelectorAll('#payTable tbody tr');
    rows.forEach(function(row) {
        var text = row.getAttribute('data-search') || '';
        row.style.display = text.includes(keyword) ? '' : 'none';
    });
}

/* ═══════════════════════════════════════
   AUTO-HIDE ALERT
═══════════════════════════════════════ */
var alertEl = document.getElementById('alertMsg');
if (alertEl) {
    setTimeout(function() {
        alertEl.style.opacity = '0';
        setTimeout(function() { alertEl.remove(); }, 300);
    }, 4000);
}

/* ═══════════════════════════════════════
   BUKA MODAL TAMBAH JIKA ADA VALIDATION ERROR
   (supaya user tidak kebingungan)
═══════════════════════════════════════ */
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openModal('modalTambah');
    });
@endif
</script>
@endpush