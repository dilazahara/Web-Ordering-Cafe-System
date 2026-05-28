@extends('layouts.admin')

@section('title', 'Admin - Addons')

@push('styles')
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
   SUBMENU
======================= */
.submenu {
    display: none; flex-direction: column;
    margin-left: 35px; gap: 5px;
    animation: slideDown 0.3s ease;
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.submenu-item {
    padding: 12px 16px; border-radius: 12px;
    font-size: 14px; color: #cbd5e1;
    text-decoration: none; display: block; transition: all 0.3s ease;
}
.submenu-item:hover { background: #334155; color: white; padding-left: 20px; }
.submenu-item.active { background: #3b82f6; color: white; }
.arrow { margin-left: auto; transition: all 0.4s ease; }
.arrow.rotate { transform: rotate(180deg); }

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
   BADGES
======================= */
.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}
.status-active   { background: #f0fdf4; color: #15803d; }
.status-inactive { background: #f3f4f6; color: #6b7280; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; }
.dot-active   { background: #22c55e; }
.dot-inactive { background: #9ca3af; }

.group-badge {
    display: inline-block; padding: 4px 10px; border-radius: 8px;
    background: #ede9fe; color: #5b21b6;
    font-size: 12px; font-weight: 600;
}

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
</style>
@endpush

@section('content')
<div class="page-header">
        <div>
            <h1>Data Add-ons</h1>
            <p>Kelola semua tambahan pilihan menu cafe</p>
        </div>
        <a href="/admin/addons/create" class="btn-add">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            Tambah Add-on
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
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Group</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($addons as $i => $addon)
                <tr>
                    <td style="color:#9ca3af; font-size:13px;">{{ $i + 1 }}</td>
                    <td style="font-weight:600; color:#111827;">{{ $addon->name }}</td>
                    <td style="color:#6b7280;">{{ $addon->description }}</td>
                    <td><span class="group-badge">{{ $addon->group->name }}</span></td>
                    <td style="font-weight:700; color:#6366f1;">Rp {{ number_format($addon->price) }}</td>
                    <td>
                        @if($addon->status)
                        <span class="status-badge status-active">
                            <span class="status-dot dot-active"></span> Aktif
                        </span>
                        @else
                        <span class="status-badge status-inactive">
                            <span class="status-dot dot-inactive"></span> Nonaktif
                        </span>
                        @endif
                    </td>
                    <td>
                        <div class="action-wrap" style="justify-content:center;">
                            <a href="/admin/addons/edit/{{ $addon->id }}" class="act-btn act-edit" title="Edit">
                                <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                            </a>
                            <button type="button" class="act-btn act-delete" title="Hapus"
                                onclick="openDeleteAddon({{ $addon->id }}, '{{ addslashes($addon->name) }}')">
                                <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if(count($addons) == 0)
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i data-lucide="plus-circle" style="width:40px;height:40px;color:#e5e7eb;"></i>
                            <p>Belum ada add-on yang ditambahkan</p>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
        </div>
    </div>
@endsection

{{-- ════ MODAL HAPUS ADDON ════ --}}
<div class="modal-backdrop" id="modalHapusAddon">
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
        <h3 class="mh-title">Hapus Add-on?</h3>
        <p class="mh-sub">Kamu akan menghapus add-on:</p>
        <p class="mh-name" id="deleteAddonNama"></p>
        <div class="mh-footer">
            <button type="button" class="mh-btn-batal" onclick="closeDeleteAddon()">Batal</button>
            <form id="deleteAddonForm" action="" method="POST" style="margin:0;">
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

@push('styles')
<style>
.modal-backdrop { display:none; position:fixed; inset:0; z-index:2000; background:rgba(0,0,0,0.45); backdrop-filter:blur(3px); align-items:center; justify-content:center; padding:20px; }
.modal-backdrop.open { display:flex; }
.modal-hapus { background:white; border-radius:24px; width:100%; max-width:400px; padding:32px 28px 28px; text-align:center; box-shadow:0 24px 60px rgba(0,0,0,0.2); animation:mhIn 0.3s cubic-bezier(0.34,1.56,0.64,1); }
@keyframes mhIn { from{opacity:0;transform:scale(0.88) translateY(20px);}to{opacity:1;transform:scale(1) translateY(0);} }
.mh-icon-wrap { width:64px;height:64px;border-radius:18px;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px; }
.mh-icon-wrap svg { width:28px;height:28px;color:#dc2626; }
.mh-title { font-size:18px;font-weight:700;color:#0f172a;margin-bottom:8px; }
.mh-sub { font-size:14px;color:#64748b;margin-bottom:4px; }
.mh-name { font-weight:700;color:#dc2626;font-size:15px;margin-bottom:8px; }
.mh-warn { font-size:13px;color:#94a3b8;margin-bottom:24px; }
.mh-footer { display:flex;gap:12px;justify-content:center; }
.mh-btn-batal { padding:10px 22px;border-radius:11px;border:1.5px solid #e2e8f0;background:#f1f5f9;color:#475569;font-size:13.5px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:all 0.2s; }
.mh-btn-batal:hover { background:#e2e8f0; }
.mh-btn-hapus { display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:11px;border:none;background:#dc2626;color:white;font-size:13.5px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:all 0.2s; }
.mh-btn-hapus:hover { background:#b91c1c; }
</style>
@endpush

@push('scripts')
<script>
function openDeleteAddon(id, nama) {
    document.getElementById('deleteAddonNama').textContent = nama;
    document.getElementById('deleteAddonForm').action = '/admin/addons/delete/' + id;
    document.getElementById('modalHapusAddon').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDeleteAddon() {
    document.getElementById('modalHapusAddon').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('modalHapusAddon').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteAddon();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteAddon();
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