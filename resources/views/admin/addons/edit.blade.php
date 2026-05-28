@extends('layouts.admin')

@section('title', 'Edit Add-on')

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #F8F9FC; color: #1e293b; }

.main { padding: 30px 30px 40px; max-width: 780px; margin: 0 auto; }

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

.card { background: white; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07); overflow: hidden; }
.card-section { padding: 22px 24px; border-bottom: 1px solid #f8fafc; }
.card-section:last-child { border-bottom: none; }
.card-section-title { font-size: 11.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 18px; }

.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 600px) { .form-grid-2 { grid-template-columns: 1fr; } }

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

.status-toggle { display: flex; gap: 8px; }
.status-opt {
    flex: 1; padding: 11px; border-radius: 11px; text-align: center;
    border: 1.5px solid #e5e7eb; cursor: pointer; font-size: 13px;
    font-weight: 600; color: #9ca3af; transition: all 0.18s; user-select: none;
}
.status-opt.active-status   { border-color: #22c55e; color: #15803d; background: #f0fdf4; }
.status-opt.inactive-status { border-color: #f87171; color: #dc2626; background: #fef2f2; }

.info-box { padding: 13px 15px; background: #f8fafc; border-radius: 11px; border: 1px solid #f1f5f9; margin-top: 14px; }
.info-box-title { font-size: 11px; color: #9ca3af; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.info-box p { font-size: 12px; color: #6b7280; margin-top: 3px; }
.info-box span { font-weight: 600; color: #374151; }

.alert-error {
    background: #fef2f2; color: #dc2626;
    border: 1px solid #fee2e2; border-radius: 14px;
    padding: 13px 17px; margin-bottom: 18px; font-size: 13px; font-weight: 500;
}

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
<div class="main">

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

        <!-- INFORMASI ADD-ON -->
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

        <!-- GROUP & STATUS -->
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
                        <div class="status-opt {{ $addon->status ? 'active-status' : '' }}"
                             id="statusAktif" onclick="setStatus(1)">✓ Aktif</div>
                        <div class="status-opt {{ !$addon->status ? 'inactive-status' : '' }}"
                             id="statusNonaktif" onclick="setStatus(0)">✕ Nonaktif</div>
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

        <!-- FOOTER -->
        <div class="card-footer">
        
            <div class="footer-right">
                <a href="/admin/addons" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save">
                    💾 Update Add-on
                </button>
            </div>
        </div>
    </div>
    </form>

    <form id="deleteForm" action="/admin/addons/delete/{{ $addon->id }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

</div>{{-- /main --}}
@endsection

@push('scripts')
<script>
/* ══ STATUS TOGGLE ══ */
function setStatus(val) {
    document.getElementById('statusVal').value = val;
    var aktif    = document.getElementById('statusAktif');
    var nonaktif = document.getElementById('statusNonaktif');
    if (val == 1) {
        aktif.className    = 'status-opt active-status';
        nonaktif.className = 'status-opt';
    } else {
        aktif.className    = 'status-opt';
        nonaktif.className = 'status-opt inactive-status';
    }
}
</script>
@endpush