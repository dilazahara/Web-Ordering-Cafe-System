@extends('layouts.admin')

@section('title', 'Manajemen User')

@push('styles')
<style>
/* ════ CSS KHUSUS HALAMAN USER (INDEX) ════ */

.btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 12px;
    background: var(--primary); color: white; border: none;
    font-size: var(--text-md); font-weight: 600; cursor: pointer;
    text-decoration: none; font-family: var(--font);
    transition: all 0.2s; white-space: nowrap;
}
.btn-add:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.35); }
.btn-add:active { transform: scale(0.97); }

.alert-success {
    background: #f0fdf4; color: #15803d;
    border: 1px solid #bbf7d0; border-radius: var(--radius-lg);
    padding: 12px 16px; margin-bottom: var(--space-lg);
    font-size: var(--text-md); font-weight: 500;
    display: flex; align-items: center; gap: 8px;
    transition: opacity 0.4s;
}

/* ── TABLE ── */
.table-box {
    background: var(--bg-white); border-radius: var(--radius-3xl);
    border: 1px solid var(--border-light);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
    overflow: hidden;
}

table { width: 100%; border-collapse: collapse; }
thead { background: var(--bg); }
th {
    text-align: left; font-weight: 600; color: var(--text-mid);
    padding: var(--space-md) var(--space-md); font-size: var(--text-sm);
    text-transform: uppercase; letter-spacing: 0.6px;
    border-bottom: 2px solid var(--border); white-space: nowrap;
}
td { padding: var(--space-md); font-size: var(--text-md); color: var(--text-mid); border-bottom: 1px solid var(--border-light); }
tbody tr { transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--bg); }

/* ── BADGES & ELEMENTS ── */
.role {
    padding: 5px 12px; border-radius: var(--radius-full);
    font-size: var(--text-sm); font-weight: 600; display: inline-block;
}
.role.admin   { background: #fef3c7; color: #92400e; }
.role.kasir   { background: #dbeafe; color: #1e40af; }
.role.pelayan { background: #dcfce7; color: #166534; }
.role.dapur   { background: #fce7f3; color: #9d174d; }

.action { display: flex; gap: 8px; }
.act-btn {
    width: 34px; height: 34px; border-radius: var(--radius-md); border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s; text-decoration: none; flex-shrink: 0;
}
.act-btn:active { transform: scale(0.9); }
.act-edit   { background: #eff6ff; color: #2563eb; border: 1.5px solid #dbeafe; }
.act-edit:hover   { background: #dbeafe; border-color: #93c5fd; }
.act-delete { background: #fef2f2; color: #dc2626; border: 1.5px solid #fee2e2; }
.act-delete:hover { background: #fee2e2; border-color: #fca5a5; }

.empty-state { text-align: center; padding: 56px 20px; }
.empty-state p { color: var(--text-muted); font-size: var(--text-md); margin-top: 10px; }

.last-login-wrap { display: flex; align-items: center; gap: 6px; }
.last-login-wrap svg { flex-shrink: 0; color: var(--text-muted); }
.last-login-text { font-size: var(--text-base); color: var(--text-mid); }
.last-login-never { font-size: var(--text-base); color: var(--text-light); font-style: italic; }

.pwd-wrap { display: flex; align-items: center; gap: 6px; }
.pwd-text  { font-size: var(--text-base); color: var(--text-mid); font-family: monospace; letter-spacing: 2px; }
.pwd-toggle {
    background: none; border: none; cursor: pointer; padding: 2px;
    color: var(--text-muted); display: flex; align-items: center; transition: color 0.15s;
}
.pwd-toggle:hover { color: var(--text-mid); }

.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: var(--radius-full);
    font-size: var(--text-sm); font-weight: 600;
}
.status-aktif    { background: #dcfce7; color: #15803d; }
.status-nonaktif { background: #f1f5f9; color: var(--text-muted); }
.status-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.dot-aktif   { background: #22c55e; }
.dot-nonaktif { background: var(--text-light); }

/* ── MODAL ── */
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
    width: 64px; height: 64px; border-radius: var(--radius-2xl);
    background: #fef2f2; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 16px;
}
.mh-icon-wrap svg { width: 28px; height: 28px; color: #dc2626; }
.mh-title { font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
.mh-sub { font-size: var(--text-md); color: var(--text-light); margin-bottom: 4px; }
.mh-name { font-weight: 700; color: #dc2626; font-size: 15px; margin-bottom: 8px; }
.mh-footer { display: flex; gap: 12px; justify-content: center; }
.mh-btn-batal {
    padding: 10px 22px; border-radius: 11px; border: 1.5px solid var(--border);
    background: var(--border-light); color: var(--text-mid); font-size: 13.5px; font-weight: 600;
    cursor: pointer; font-family: var(--font); transition: all 0.2s;
}
.mh-btn-batal:hover { background: var(--border); }
.mh-btn-hapus {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 11px; border: none;
    background: #dc2626; color: white; font-size: 13.5px; font-weight: 600;
    cursor: pointer; font-family: var(--font); transition: all 0.2s;
}
.mh-btn-hapus:hover { background: #b91c1c; }

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
    th, td { padding: 13px 12px; }
    th:nth-child(5), td:nth-child(5) { display: none; }
}

@media (max-width: 768px) {
    .table-box { border-radius: var(--radius-xl); overflow-x: auto; }
    table { min-width: 580px; }
    th, td { font-size: 13px; padding: 12px 10px; }
    th:nth-child(5), td:nth-child(5), th:nth-child(6), td:nth-child(6) { display: none; }
}

@media (max-width: 480px) {
    .table-box { border-radius: var(--radius-lg); }
    table { min-width: 420px; }
    th:nth-child(3), td:nth-child(3), th:nth-child(5), td:nth-child(5),
    th:nth-child(6), td:nth-child(6), th:nth-child(7), td:nth-child(7) { display: none; }
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title">
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
            @forelse($users as $item)
            <tr>
                <td style="color:var(--primary); font-size:13px; font-weight:600; font-family:monospace;">{{ $item->formatted_id }}</td>
                <td style="font-weight:600; color:var(--text-dark);">{{ $item->name }}</td>
                <td style="color:var(--text-light);">{{ $item->email }}</td>
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
                            <i data-lucide="eye" id="pwd-eye-{{ $item->id }}" style="width:14px; height:14px;"></i>
                            <i data-lucide="eye-off" id="pwd-eyeoff-{{ $item->id }}" style="width:14px; height:14px; display:none;"></i>
                        </button>
                    </div>
                </td>
                <td>
                    @if($item->last_login_at)
                    <div class="last-login-wrap">
                        <i data-lucide="clock" style="width:14px;height:14px;"></i>
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
                        Online
                    </span>
                    @else
                    <span class="status-badge status-nonaktif">
                        <span class="status-dot dot-nonaktif"></span>
                        Offline
                    </span>
                    @endif
                </td>
                <td>
                    <div class="action" style="justify-content:center;">
                        <a href="/admin/user/edit/{{ $item->id }}" class="act-btn act-edit" title="Edit">
                            <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                        </a>
                        @if($item->id === auth()->id())
                        <span class="act-btn" title="Akun Anda sendiri"
                            style="background:#f1f5f9;color:#cbd5e1;cursor:default;border:1.5px solid #e2e8f0;">
                            <i data-lucide="shield" style="width:15px;height:15px;"></i>
                        </span>
                        @else
                        <button type="button" class="act-btn act-delete" title="Hapus"
                            onclick="openDeleteUser({{ $item->id }}, '{{ addslashes($item->name) }}')">
                            <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i data-lucide="users" style="width:40px;height:40px;color:var(--border);"></i>
                        <p>Belum ada user yang ditambahkan</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL HAPUS USER --}}
<div class="modal-backdrop" id="modalHapusUser">
    <div class="modal-hapus">
        <div class="mh-icon-wrap">
            <i data-lucide="trash-2"></i>
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
                    <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
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

// Toggle tampil/sembunyikan hash password
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