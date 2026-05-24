@extends('layouts.admin')

@section('title', 'User')

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
.main { padding: 110px 30px 30px; }

/* =======================
   PAGE HEADER
======================= */
.page-header {
    display: flex; justify-content: space-between;
    align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
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
   TABLE BOX
======================= */
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

/* =======================
   ROLE BADGE
======================= */
.role {
    padding: 5px 12px; border-radius: 999px;
    font-size: 12px; font-weight: 600; display: inline-block;
}
.admin   { background: #fef3c7; color: #92400e; }
.kasir   { background: #dbeafe; color: #1e40af; }
.pelayan { background: #dcfce7; color: #166534; }
.dapur   { background: #fce7f3; color: #9d174d; }

/* =======================
   ACTION BUTTONS
======================= */
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
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $item)
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
                        <div class="action" style="justify-content:center;">
                            <a href="/admin/user/edit/{{ $item->id }}" class="act-btn act-edit" title="Edit">
                                <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                            </a>
                            <a href="/admin/user/delete/{{ $item->id }}" class="act-btn act-delete" title="Hapus"
                                onclick="return confirm('Yakin ingin menghapus user ini?')">
                                <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if(count($users) == 0)
                <tr>
                    <td colspan="5">
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
@endsection