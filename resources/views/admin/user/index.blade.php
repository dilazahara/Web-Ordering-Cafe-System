@extends('layouts.admin')

@section('title', 'User')

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
.topbar-left i {
    width: 24px; height: 24px; padding: 8px; border-radius: 12px;
    color: #475569; cursor: pointer; transition: all 0.3s ease;
}
.topbar-left i:hover { background: #f1f5f9; color: #1e293b; transform: scale(1.05); }

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
.sidebar i { width: 20px; height: 20px; stroke-width: 2.5; color: #c4b5fd; }
.menu-parent { cursor: pointer; }
.menu-parent:hover,
.sidebar a:hover { background: rgba(255,255,255,0.06); color: white; transform: translateX(4px); }
.sidebar a.active {
    background: rgba(139, 92, 246, 0.25); color: #c4b5fd;
    box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4);
}

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

.main { padding: 110px 30px 30px; }

.page-header {
    display: flex; justify-content: space-between;
    align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
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

.alert-success {
    background: #f0fdf4; color: #15803d;
    border: 1px solid #bbf7d0; border-radius: 12px;
    padding: 12px 16px; margin-bottom: 20px;
    font-size: 14px; font-weight: 500;
    display: flex; align-items: center; gap: 8px;
    transition: opacity 0.4s;
}

.table-box {
    background: white; border-radius: 20px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
    overflow: hidden;
}

table { width: 100%; border-collapse: collapse; }
thead { background: #f8fafc; }
th {
    text-align: left; font-weight: 600; color: #475569;
    padding: 16px 16px; font-size: 12px;
    text-transform: uppercase; letter-spacing: 0.6px;
    border-bottom: 2px solid #e2e8f0; white-space: nowrap;
}
td { padding: 16px 16px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; }
tbody tr { transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #f8fafc; }

.role {
    padding: 5px 12px; border-radius: 999px;
    font-size: 12px; font-weight: 600; display: inline-block;
}
.admin   { background: #fef3c7; color: #92400e; }
.kasir   { background: #dbeafe; color: #1e40af; }
.pelayan { background: #dcfce7; color: #166534; }
.dapur   { background: #fce7f3; color: #9d174d; }

.action { display: flex; gap: 8px; }
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

.empty-state { text-align: center; padding: 56px 20px; }
.empty-state p { color: #9ca3af; font-size: 14px; margin-top: 10px; }

.last-login-wrap { display: flex; align-items: center; gap: 6px; }
.last-login-wrap svg { flex-shrink: 0; color: #94a3b8; }
.last-login-text { font-size: 13px; color: #475569; }
.last-login-never { font-size: 13px; color: #cbd5e1; font-style: italic; }

.pwd-wrap { display: flex; align-items: center; gap: 6px; }
.pwd-text  { font-size: 13px; color: #334155; font-family: monospace; letter-spacing: 2px; }
.pwd-toggle {
    background: none; border: none; cursor: pointer; padding: 2px;
    color: #94a3b8; display: flex; align-items: center; transition: color 0.15s;
}
.pwd-toggle:hover { color: #475569; }

.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 999px;
    font-size: 12px; font-weight: 600;
}
.status-aktif    { background: #dcfce7; color: #15803d; }
.status-nonaktif { background: #f1f5f9; color: #94a3b8; }
.status-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.dot-aktif   { background: #22c55e; }
.dot-nonaktif { background: #cbd5e1; }

.modal-backdrop {
    display: none; position: fixed; inset: 0; z-index: 2000;
    background: rgba(0,0,0,0.45); backdrop-filter: blur(3px);
    align-items: center; justify-content: center; padding: 20px;
}
.modal-backdrop.open { display: flex; }
.modal-hapus {
    background: white; border-radius: 24px; width: 100%; max-width: 400px;
    padding: 32px 28px 28px; text-align: center;
    box-shadow: 0 24px 60px rgba(0,0,0,0.2);
    animation: mhIn 0.3s cubic-bezier(0.34,1.56,0.64,1);
}
@keyframes mhIn {
    from { opacity:0; transform: scale(0.88) translateY(20px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
}
.mh-icon-wrap {
    width: 64px; height: 64px; border-radius: 18px;
    background: #fef2f2; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 16px;
}
.mh-icon-wrap svg { width: 28px; height: 28px; color: #dc2626; }
.mh-title { font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
.mh-sub { font-size: 14px; color: #64748b; margin-bottom: 4px; }
.mh-name { font-weight: 700; color: #dc2626; font-size: 15px; margin-bottom: 8px; }
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
<div class="page-header">
    <div>
        <h1>Manajemen User</h1>
        <p>Kelola akun pengguna sistem</p>
    </div>
    <a href="{{ route('admin.user.create') }}" class="btn-add">
        <i data-lucide="plus" style="width:15px;height:15px;"></i>
        Tambah User
    </a>
</div>

@if(session('success'))
<div class="alert-success" id="alertSuccess">
    <i data-lucide="check-circle" style="width:17px;height:17px;flex-shrink:0;"></i>
    {{ session('success') }}
</div>
@endif

<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Password</th>
                <th>Terakhir Login</th>
                <th>Status</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $item)
            <tr>
                <td style="color:#6366f1; font-size:13px; font-weight:600; font-family:monospace;">{{ $item->formatted_id }}</td>
                <td style="font-weight:600; color:#111827;">{{ $item->name }}</td>
                <td style="color:#6b7280;">{{ $item->email }}</td>
                <td>
                    <span class="role {{ $item->role }}">
                        {{ ucfirst($item->role) }}
                    </span>
                </td>
                <td>
                    <div class="pwd-wrap">
                        <span class="pwd-text" id="pwd-{{ $item->id }}">••••••••</span>
                        <button type="button" class="pwd-toggle"
                            onclick="togglePassword({{ $item->id }}, '{{ addslashes($item->password) }}')"
                            id="pwd-btn-{{ $item->id }}" title="Lihat / Sembunyikan">
                            <svg id="pwd-eye-{{ $item->id }}" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="pwd-eyeoff-{{ $item->id }}" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </td>
                <td>
                    @if($item->last_login_at)
                    <div class="last-login-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span class="last-login-text" title="{{ $item->last_login_at->format('d M Y, H:i:s') }}">
                            {{ $item->last_login_at->diffForHumans() }}
                        </span>
                    </div>
                    @else
                    <span class="last-login-never">Belum pernah login</span>
                    @endif
                </td>
                <td>
                    @if($item->is_online)
                    <span class="status-badge status-aktif">
                        <span class="status-dot dot-aktif"></span>
                        Aktif
                    </span>
                    @else
                    <span class="status-badge status-nonaktif">
                        <span class="status-dot dot-nonaktif"></span>
                        Tidak Aktif
                    </span>
                    @endif
                </td>
                <td>
                    <div class="action" style="justify-content:center;">
                        <a href="/admin/user/edit/{{ $item->id }}" class="act-btn act-edit" title="Edit">
                            <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                        </a>
                        <button type="button" class="act-btn act-delete" title="Hapus"
                            onclick="openDeleteUser({{ $item->id }}, '{{ addslashes($item->name) }}')">
                            <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach

            @if(count($users) == 0)
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i data-lucide="users" style="width:40px;height:40px;color:#e5e7eb;"></i>
                        <p>Belum ada user yang ditambahkan</p>
                    </div>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

{{-- MODAL HAPUS USER --}}
<div class="modal-backdrop" id="modalHapusUser">
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
        <h3 class="mh-title">Hapus User?</h3>
        <p class="mh-sub">Kamu akan menghapus user:</p>
        <p class="mh-name" id="deleteUserNama"></p>
        <p class="mh-sub" style="margin-bottom:20px;">Tindakan ini tidak dapat dipulihkan</p>
        <div class="mh-footer">
            <button type="button" class="mh-btn-batal" onclick="closeDeleteUser()">Batal</button>
            <form id="deleteUserForm" action="" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
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
function openDeleteUser(id, nama) {
    document.getElementById('deleteUserNama').textContent = nama;
    document.getElementById('deleteUserForm').action = '/admin/user/delete/' + id;
    document.getElementById('modalHapusUser').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDeleteUser() {
    document.getElementById('modalHapusUser').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('modalHapusUser').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteUser();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteUser();
});
var alertEl = document.getElementById('alertSuccess');
if (alertEl) {
    setTimeout(function() {
        alertEl.style.opacity = '0';
        setTimeout(function() { alertEl.remove(); }, 400);
    }, 4000);
}

// ✅ Toggle tampil/sembunyikan hash password
var pwdVisible = {};
function togglePassword(id, hash) {
    var el    = document.getElementById('pwd-' + id);
    var eye   = document.getElementById('pwd-eye-' + id);
    var eyeOff= document.getElementById('pwd-eyeoff-' + id);

    if (pwdVisible[id]) {
        el.textContent = '••••••••';
        eye.style.display    = '';
        eyeOff.style.display = 'none';
        pwdVisible[id] = false;
    } else {
        el.textContent = hash;
        el.style.letterSpacing = '0';
        el.style.fontSize = '11px';
        eye.style.display    = 'none';
        eyeOff.style.display = '';
        pwdVisible[id] = true;
    }
}
</script>
@endpush