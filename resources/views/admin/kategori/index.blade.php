@extends('layouts.admin')

@section('title', 'Kategori Menu')

@push('styles')
<style>
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
   CARD & TABLE
======================= */
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

/* =======================
   ACTION BUTTONS
======================= */
.action-wrap { display: flex; align-items: center; gap: 6px; }
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

/* =======================
   EMPTY STATE
======================= */
.empty-state { text-align: center; padding: 56px 20px; }
.empty-state p { color: #9ca3af; font-size: 14px; margin-top: 10px; }

/* =======================
   MODAL
======================= */
.modal-backdrop {
    display: none;
    position: fixed; inset: 0; z-index: 2000;
    background: rgba(0,0,0,0.45); backdrop-filter: blur(3px);
    align-items: center; justify-content: center; padding: 20px;
}
.modal-backdrop.open { display: flex; }
.modal-hapus {
    background: white; border-radius: 24px; width: 100%; max-width: 400px;
    padding: 32px 28px 28px; text-align: center;
    box-shadow: 0 24px 60px rgba(0,0,0,0.2);
    animation: none;
}
.modal-backdrop.open .modal-hapus {
    animation: mhIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes mhIn {
    from { opacity: 0; transform: scale(0.88) translateY(20px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.mh-icon-wrap {
    width: 64px; height: 64px; border-radius: 18px;
    background: #fef2f2; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 16px;
}
.mh-icon-wrap svg { width: 28px; height: 28px; color: #dc2626; }
.mh-title { font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
.mh-sub   { font-size: 14px; color: #64748b; margin-bottom: 4px; }
.mh-name  { font-weight: 700; color: #dc2626; font-size: 15px; margin-bottom: 8px; }
.mh-warn  { font-size: 13px; color: #94a3b8; margin-bottom: 24px; }
.mh-footer { display: flex; gap: 12px; justify-content: center; }
.mh-btn-batal {
    padding: 10px 22px; border-radius: 11px; border: 1.5px solid #e2e8f0;
    background: #f1f5f9; color: #475569; font-size: 13.5px; font-weight: 600;
    cursor: pointer; font-family: 'Inter', sans-serif; transition: all 0.2s;
}
.mh-btn-batal:hover { background: #e2e8f0; }
.mh-btn-hapus {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 11px; border: none;
    background: #dc2626; color: white; font-size: 13.5px; font-weight: 600;
    cursor: pointer; font-family: 'Inter', sans-serif; transition: all 0.2s;
}
.mh-btn-hapus:hover { background: #b91c1c; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-title">
        <h1>Kategori Menu</h1>
        <p>Kelola semua kategori menu cafe</p>
    </div>
    <a href="{{ route('admin.kategori.create') }}" class="btn-add">
        <i data-lucide="plus" style="width:15px;height:15px;"></i>
        Tambah Kategori
    </a>
</div>

{{-- ALERT --}}
@if(session('success'))
<div class="alert-success" id="alertSuccess">
    <i data-lucide="check-circle" style="width:17px;height:17px;flex-shrink:0;"></i>
    {{ session('success') }}
</div>
@endif

{{-- TABLE CARD --}}
<div class="card">
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th>Nama Kategori</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategori as $index => $item)
                <tr>
                    <td style="color:#9ca3af; font-size:13px;">{{ $index + 1 }}</td>
                    <td style="font-weight:600; color:#111827;">{{ $item->name }}</td>
                    <td>
                        <div class="action-wrap" style="justify-content:center;">
                            <a href="{{ route('admin.kategori.edit', $item->id) }}" class="act-btn act-edit" title="Edit">
                                <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                            </a>
                            <button type="button" class="act-btn act-delete" title="Hapus"
                                onclick="openDeleteKat({{ $item->id }}, '{{ addslashes($item->name) }}')">
                                <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">
                        <div class="empty-state">
                            <i data-lucide="folder" style="width:40px;height:40px;color:#e5e7eb;"></i>
                            <p>Belum ada kategori yang ditambahkan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL HAPUS KATEGORI --}}
<div class="modal-backdrop" id="modalHapusKat">
    <div class="modal-hapus">
        <div class="mh-icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6"/><path d="M14 11v6"/>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
        </div>
        <h3 class="mh-title">Hapus Kategori?</h3>
        <p class="mh-sub">Kamu akan menghapus kategori:</p>
        <p class="mh-name" id="deleteKatNama"></p>
        <p class="mh-warn">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="mh-footer">
            <button type="button" class="mh-btn-batal" onclick="closeDeleteKat()">Batal</button>
            <form id="deleteKatForm" action="" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="mh-btn-hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="width:15px;height:15px;">
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
function openDeleteKat(id, nama) {
    document.getElementById('deleteKatNama').textContent = nama;
    document.getElementById('deleteKatForm').action = '/admin/kategori/delete/' + id;
    document.getElementById('modalHapusKat').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDeleteKat() {
    document.getElementById('modalHapusKat').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('modalHapusKat').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteKat();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteKat();
});
var alertEl = document.getElementById('alertSuccess');
if (alertEl) {
    setTimeout(function() {
        alertEl.style.opacity = '0';
        setTimeout(function() { alertEl.remove(); }, 400);
    }, 4000);
}
</script>
@endpush