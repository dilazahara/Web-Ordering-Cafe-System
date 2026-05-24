@extends('layouts.admin')

@section('title', 'Edit Add-on')

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #F8F9FC; color: #1e293b; }

/* ══ TOPBAR ══ */
.topbar {
    position: fixed; top: 0; left: 0; right: 0; height: 80px;
    backdrop-filter: blur(20px);
    background: rgba(255,255,255,0.95);
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 30px; z-index: 1000;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.topbar-left { display: flex; align-items: center; gap: 20px; }
.topbar-left span { font-weight: 600; font-size: 18px; color: #1e293b; }
.menu-icon-btn {
    display: flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 12px;
    border: none; background: transparent; cursor: pointer;
    color: #475569; transition: all 0.3s ease;
}
.menu-icon-btn:hover { background: #f1f5f9; color: #1e293b; transform: scale(1.05); }
.topbar-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, #F97316, #FB923C);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 13px; font-weight: 700;
}

/* ══ SIDEBAR ══ */
.sidebar {
    width: 240px; height: 100vh; position: fixed;
    background: linear-gradient(180deg, #0f172a, #1e1b4b);
    padding: 30px 20px; padding-top: 100px;
    color: white; overflow-y: auto;
    transform: translateX(-100%);
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    z-index: 999; box-shadow: 4px 0 20px rgba(0,0,0,0.15);
    display: flex; flex-direction: column; gap: 4px;
}
.sidebar.show { transform: translateX(0); }
.sidebar-overlay {
    display: none; position: fixed; inset: 0; z-index: 998;
    background: rgba(0,0,0,0.35); backdrop-filter: blur(2px);
}
.sidebar-overlay.show { display: block; }
.menu-section {
    font-size: 11px; letter-spacing: 1px; font-weight: 600;
    color: #a78bfa; margin: 18px 10px 6px; opacity: 0.8; text-transform: uppercase;
}
.sidebar a, .menu-parent {
    display: flex; align-items: center; gap: 14px;
    padding: 11px 14px; border-radius: 12px;
    text-decoration: none; color: #94a3b8;
    font-weight: 400; font-size: 14.5px;
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
}
.sidebar a i, .menu-parent i { width: 18px; height: 18px; stroke-width: 2.2; color: #c4b5fd; flex-shrink: 0; }
.menu-parent { cursor: pointer; }
.menu-parent:hover, .sidebar a:hover { background: rgba(255,255,255,0.06); color: white; transform: translateX(4px); }
.sidebar a.active { background: rgba(139,92,246,0.25); color: #c4b5fd; box-shadow: inset 0 0 0 1px rgba(139,92,246,0.4); }
.submenu { display: none; flex-direction: column; margin-left: 32px; gap: 3px; margin-top: 3px; }
.submenu-item {
    padding: 10px 14px; border-radius: 10px;
    font-size: 13.5px; color: #cbd5e1;
    text-decoration: none; transition: all 0.25s ease; display: block;
}
.submenu-item:hover { background: #334155; color: white; padding-left: 18px; }
.submenu-item.active { background: #3b82f6; color: white; }
.s-arrow { margin-left: auto; transition: all 0.35s ease; width: 16px !important; height: 16px !important; }
.s-arrow.open { transform: rotate(180deg); }

/* ══ MAIN ══ */
.main { padding: 110px 30px 40px; max-width: 780px; margin: 0 auto; }

/* ══ PAGE HEADER ══ */
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-header h1 { font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
.page-header p  { font-size: 14px; color: #64748b; }
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 12px;
    border: 1.5px solid #e2e8f0; background: white;
    color: #374151; font-size: 13px; font-weight: 600;
    text-decoration: none; transition: all 0.2s; white-space: nowrap; font-family: 'Inter', sans-serif;
}
.btn-back:hover { background: #f8fafc; border-color: #cbd5e1; transform: translateY(-1px); }

/* ══ CARD ══ */
.card { background: white; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07); overflow: hidden; }
.card-section { padding: 22px 24px; border-bottom: 1px solid #f8fafc; }
.card-section:last-child { border-bottom: none; }
.card-section-title { font-size: 11.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 18px; }

/* ══ FORM GRID ══ */
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 600px) { .form-grid-2 { grid-template-columns: 1fr; } }

/* ══ FORM ELEMENTS ══ */
.form-group { display: flex; flex-direction: column; gap: 7px; }
.form-label { font-size: 13px; font-weight: 600; color: #374151; }
.form-input, .form-select, .form-textarea {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid #e5e7eb; border-radius: 11px;
    font-family: 'Inter', sans-serif; font-size: 14px; color: #111827;
    background: white; outline: none; transition: border-color 0.2s, box-shadow 0.2s;
}
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.1); }
.form-textarea { resize: none; }
.form-input::placeholder, .form-textarea::placeholder { color: #9ca3af; }

/* ══ STATUS TOGGLE ══ */
.status-toggle { display: flex; gap: 8px; }
.status-opt {
    flex: 1; padding: 11px; border-radius: 11px; text-align: center;
    border: 1.5px solid #e5e7eb; cursor: pointer; font-size: 13px;
    font-weight: 600; color: #9ca3af; transition: all 0.18s;
}
.status-opt.active-status   { border-color: #22c55e; color: #15803d; background: #f0fdf4; }
.status-opt.inactive-status { border-color: #f87171; color: #dc2626; background: #fef2f2; }
.status-opt:hover { background: #f9fafb; }

/* ══ INFO BOX ══ */
.info-box { padding: 13px 15px; background: #f8fafc; border-radius: 11px; border: 1px solid #f1f5f9; margin-top: 14px; }
.info-box-title { font-size: 11px; color: #9ca3af; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.info-box p { font-size: 12px; color: #6b7280; margin-top: 3px; }
.info-box span { font-weight: 600; color: #374151; }

/* ══ ALERT ══ */
.alert-error {
    background: #fef2f2; color: #dc2626;
    border: 1px solid #fee2e2; border-radius: 14px;
    padding: 13px 17px; margin-bottom: 18px; font-size: 13px; font-weight: 500;
}

/* ══ CARD FOOTER ══ */
.card-footer {
    padding: 18px 24px; background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center; gap: 10px;
}
.footer-right { display: flex; gap: 10px; }
.btn-delete {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 11px 18px; border-radius: 11px;
    border: 1.5px solid #fee2e2; background: white;
    color: #dc2626; font-size: 13px; font-weight: 600;
    cursor: pointer; font-family: 'Inter', sans-serif; transition: all 0.15s;
}
.btn-delete:hover { background: #fef2f2; border-color: #fca5a5; }
.btn-cancel {
    display: inline-flex; align-items: center;
    padding: 11px 20px; border-radius: 11px;
    border: 1.5px solid #e5e7eb; background: white;
    color: #374151; font-size: 13px; font-weight: 600;
    text-decoration: none; transition: all 0.15s; font-family: 'Inter', sans-serif;
}
.btn-cancel:hover { background: #f3f4f6; }
.btn-save {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: 11px;
    background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white; border: none;
    font-size: 13px; font-weight: 700; cursor: pointer;
    font-family: 'Inter', sans-serif; transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(99,102,241,0.3);
}
.btn-save:hover  { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(99,102,241,0.4); }
.btn-save:active { transform: scale(0.97); }
</style>
@endpush

@section('content')
<div class="page-header">
        <div>
            <h1>Edit Add-on</h1>
            <p>Perbarui data add-on yang sudah ada</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert-error">
        @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
    </div>
    @endif

    <form action="/admin/addons/update/{{ $addon->id }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">

        <!-- ── INFORMASI ADD-ON ── -->
        <div class="card-section">
            <p class="card-section-title">Informasi Add-on</p>
            <div style="display:grid; gap:16px;">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Add-on <span style="color:#EF4444;">*</span></label>
                        <input type="text" name="name" class="form-input"
                            value="{{ old('name', $addon->name) }}"
                            placeholder="Contoh: Keju Ekstra, Saus Pedas..." required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga <span style="color:#EF4444;">*</span></label>
                        <input type="number" name="price" class="form-input"
                            value="{{ old('price', $addon->price) }}" placeholder="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-textarea" rows="3"
                        placeholder="Deskripsi singkat add-on ini...">{{ old('description', $addon->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- ── GROUP & STATUS ── -->
        <div class="card-section">
            <p class="card-section-title">Group & Status</p>
            <div class="form-grid-2" style="align-items:start;">
                <div class="form-group">
                    <label class="form-label">Group Add-on <span style="color:#EF4444;">*</span></label>
                    <select name="addon_group_id" class="form-select" required>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}"
                                {{ old('addon_group_id', $addon->addon_group_id) == $group->id ? 'selected' : '' }}>
                                {{ $group->name }} {{ $group->required ? '(Wajib)' : '(Opsional)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="status-toggle">
                        <div class="status-opt {{ $addon->status ? 'active-status' : '' }}" id="statusAktif" onclick="setStatus(1)">✓ Aktif</div>
                        <div class="status-opt {{ !$addon->status ? 'inactive-status' : '' }}" id="statusNonaktif" onclick="setStatus(0)">✕ Nonaktif</div>
                    </div>
                    <input type="hidden" name="status" id="statusVal" value="{{ $addon->status }}">
                    <div class="info-box">
                        <p class="info-box-title">Info</p>
                        <p>ID: <span>#{{ $addon->id }}</span></p>
                        <p>Dibuat: <span>{{ $addon->created_at->format('d M Y') }}</span></p>
                        <p>Diperbarui: <span>{{ $addon->updated_at->format('d M Y') }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── FOOTER ── -->
        <div class="card-footer">
            <div class="footer-right">
                <a href="/admin/addons" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save">
                    <i data-lucide="save" style="width:16px;height:16px;"></i> Update Add-on
                </button>
            </div>
        </div>
    </div>
    </form>

    <form id="deleteForm" action="/admin/addons/delete/{{ $addon->id }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endsection