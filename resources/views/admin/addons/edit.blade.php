@extends('layouts.admin')

@section('title', 'Edit Add-on')

@push('styles')
<style>
/* ════ CSS KHUSUS HALAMAN EDIT ADD-ON PREMIUM ════ */
.form-wrapper { max-width: 780px; margin: 0 auto; padding: 20px 0; }

/* BACK LINK */
.back-link {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: var(--text-base); font-weight: 600; color: var(--text-light);
    text-decoration: none; margin-bottom: 24px; transition: all .2s;
}
.back-link:hover { color: var(--text-dark); transform: translateX(-2px); }
.back-link svg { width: 16px; height: 16px; }

/* HEADER HALAMAN */
.page-header { margin-bottom: 28px; }
.page-header h1 { font-size: 26px; color: var(--text-dark); font-weight: 800; letter-spacing: -0.5px; margin: 0; }
.page-header p  { margin-top: 5px; color: var(--text-light); font-size: 14px; }

/* SECTION CARD */
.section-card {
    background: var(--bg-white, #fff); 
    padding: 28px;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    border: 1px solid var(--border-light, #f1f5f9);
    margin-bottom: 24px;
    transition: box-shadow 0.3s ease;
}
.section-card:hover {
    box-shadow: 0 6px 32px rgba(0,0,0,0.06);
}

/* CARD HEADER */
.section-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1.5px dashed var(--border-light, #e2e8f0);
}
.section-icon {
    display: flex; align-items: center; justify-content: center;
    width: 42px; height: 42px;
    border-radius: 12px;
    background: #f8fafc;
    color: var(--text-mid);
    border: 1px solid #e2e8f0;
}
.section-icon svg { width: 20px; height: 20px; stroke-width: 2.2; }
.section-title h3 { font-size: 16px; font-weight: 800; color: var(--text-dark); margin: 0; }
.section-title p { font-size: 12px; color: var(--text-muted); margin-top: 3px; font-weight: 500; }

/* FORM ELEMENTS */
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-label { font-size: 14px; font-weight: 700; color: var(--text-dark); }
.form-input, .form-select, .form-textarea {
    width: 100%; padding: 13px 16px; 
    border-radius: 12px; border: 1.5px solid var(--border, #cbd5e1);
    background: var(--bg, #f8fafc); font-size: 15px; color: var(--text-dark);
    outline: none; transition: all .2s ease; font-family: var(--font);
}
.form-input:focus, .form-select:focus, .form-textarea:focus { 
    border-color: var(--primary); background: var(--bg-white, #fff); 
    box-shadow: 0 0 0 4px rgba(99,102,241,0.15); 
}
.form-textarea { resize: vertical; min-height: 100px; }
.form-input::placeholder, .form-textarea::placeholder { color: var(--text-muted); font-weight: 400; }

/* VALIDATION */
.form-input.is-invalid, .form-select.is-invalid { border-color: #ef4444 !important; background: #fef2f2 !important; box-shadow: 0 0 0 4px rgba(239,68,68,0.1) !important; }
.field-error { font-size: 13px; color: #dc2626; font-weight: 600; display: none; margin-top: 2px; }
.field-error.show { display: block; animation: slideDown .2s ease-out; }
@keyframes slideDown { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }

/* STATUS TOGGLE */
.status-toggle { display: flex; gap: 14px; }
.status-opt {
    flex: 1; padding: 14px; border-radius: 14px; text-align: center;
    border: 2px solid var(--border, #cbd5e1); background: var(--bg, #f8fafc);
    cursor: pointer; font-size: 14px; font-weight: 800; color: var(--text-muted); 
    transition: all 0.2s ease; user-select: none;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.status-opt svg { width: 18px; height: 18px; stroke-width: 2.5; }
.status-opt.active-status { 
    border-color: #22c55e; color: #16a34a; background: #f0fdf4; 
    box-shadow: 0 4px 12px rgba(34,197,94,0.1); 
}
.status-opt.inactive-status { 
    border-color: #ef4444; color: #dc2626; background: #fef2f2; 
    box-shadow: 0 4px 12px rgba(239,68,68,0.1); 
}

/* INFO BOX */
.info-box { 
    background: #f8fafc; border: 1px solid #e2e8f0; 
    border-radius: 14px; padding: 18px 20px; margin-top: 4px;
}
.info-row { 
    display: flex; justify-content: space-between; align-items: center;
    font-size: 13.5px; color: #64748b; padding: 6px 0; 
}
.info-row:not(:last-child) { border-bottom: 1px solid #e2e8f0; margin-bottom: 4px; padding-bottom: 10px; }
.info-row span { font-weight: 700; color: #0f172a; background: #fff; padding: 4px 10px; border-radius: 6px; border: 1px solid #e2e8f0; }

/* ALERTS */
.alert-error { 
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; 
    border-radius: 14px; padding: 14px 18px; font-size: 14px; 
    font-weight: 600; margin-bottom: 24px; line-height: 1.6;
}

/* ACTION CARD */
.action-card {
    background: white; padding: 20px 28px; border-radius: 20px;
    border: 1px solid var(--border-light, #f1f5f9); box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    display: flex; justify-content: flex-end; align-items: center;
}
.footer-right { display: flex; gap: 14px; width: 100%; max-width: 380px; margin-left: auto; }
.btn-cancel {
    flex: 1; padding: 14px; border-radius: 14px; border: 1.5px solid var(--border, #cbd5e1); 
    background: white; color: var(--text-mid); font-size: 15px; font-weight: 700; 
    text-decoration: none; text-align: center; transition: all .2s; font-family: var(--font);
}
.btn-cancel:hover { background: #f8fafc; border-color: #94a3b8; }
.btn-save {
    flex: 1; padding: 14px; border-radius: 14px; border: none;
    font-size: 15px; font-weight: 700; cursor: pointer; text-align: center; 
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    font-family: var(--font); transition: all .2s;
    background: linear-gradient(135deg, var(--primary), var(--primary-hover, #4f46e5)); color: white; 
    box-shadow: 0 8px 20px rgba(99,102,241,0.25);
}
.btn-save svg { width: 18px; height: 18px; stroke-width: 2.5; }
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(99,102,241,0.35); }

@media (max-width: 600px) { 
    .form-grid-2 { grid-template-columns: 1fr; gap: 16px; } 
    .footer-right { flex-direction: column; max-width: 100%; }
}
</style>
@endpush

@section('content')
<div class="form-wrapper">

    <div class="page-header">
        <h1>Edit Add-on</h1>
        <p>Perbarui informasi harga, grup, atau status ketersediaan add-on.</p>
    </div>

    @if($errors->any())
    <div class="alert-error">
        @foreach($errors->all() as $error)
            <div>• {{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form action="/admin/addons/update/{{ $addon->id }}" method="POST" id="formEditAddon" novalidate>
        @csrf
        @method('PUT')

        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <div class="section-title">
                    <h3>Informasi Add-on</h3>
                    <p>Nama, harga, dan deskripsi tambahan menu</p>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Add-on <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="name" id="fieldName" class="form-input"
                        value="{{ old('name', $addon->name) }}"
                        placeholder="Contoh: Keju Ekstra, Saus Pedas..." required>
                    <span class="field-error" id="errName"></span>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="price" id="fieldPrice" class="form-input"
                        value="{{ old('price', $addon->price) }}" placeholder="0" required>
                    <span class="field-error" id="errPrice"></span>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi Tambahan</label>
                <textarea name="description" class="form-textarea" rows="3"
                    placeholder="Deskripsi singkat mengenai add-on ini (Opsional)...">{{ old('description', $addon->description) }}</textarea>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                </div>
                <div class="section-title">
                    <h3>Kategori Group</h3>
                    <p>Kelompokkan add-on berdasarkan jenisnya</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Group Add-on <span style="color:#EF4444;">*</span></label>
                <select name="addon_group_id" id="fieldGroup" class="form-select" required>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}"
                            {{ old('addon_group_id', $addon->addon_group_id) == $group->id ? 'selected' : '' }}>
                            {{ $group->name }} {{ $group->required ? '(Wajib)' : '(Opsional)' }}
                        </option>
                    @endforeach
                </select>
                <span class="field-error" id="errGroup"></span>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <div class="section-title">
                    <h3>Status Ketersediaan</h3>
                    <p>Tentukan apakah add-on ini bisa dipesan pelanggan</p>
                </div>
            </div>

            <div class="form-group">
                <div class="status-toggle">
                    <div class="status-opt {{ $addon->status ? 'active-status' : '' }}"
                         id="statusAktif" onclick="setStatus(1)">
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Aktif
                    </div>
                    <div class="status-opt {{ !$addon->status ? 'inactive-status' : '' }}"
                         id="statusNonaktif" onclick="setStatus(0)">
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Nonaktif
                    </div>
                </div>
                <input type="hidden" name="status" id="statusVal" value="{{ $addon->status }}">
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </div>
                <div class="section-title">
                    <h3>Metadata Add-on</h3>
                    <p>Informasi sistem terkait entri data ini</p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-row">ID Sistem <span>#{{ $addon->id }}</span></div>
                <div class="info-row">Ditambahkan Pada <span>{{ $addon->created_at->format('d M Y') }}</span></div>
                <div class="info-row">Terakhir Diperbarui <span>{{ $addon->updated_at->format('d M Y') }}</span></div>
            </div>
        </div>

        <div class="action-card">
            <div class="footer-right">
                <a href="/admin/addons" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Update Add-on
                </button>
            </div>
        </div>

    </form>

    <form id="deleteForm" action="/admin/addons/delete/{{ $addon->id }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

</div>
@endsection

@push('scripts')
<script>
// Logic Validasi dan Set Status Dipertahankan Secara Penuh
document.getElementById('formEditAddon').addEventListener('submit', function(e) {
    var valid = true;
    var name  = document.getElementById('fieldName');
    var price = document.getElementById('fieldPrice');
    var group = document.getElementById('fieldGroup');

    [name, price, group].forEach(function(el) { el.classList.remove('is-invalid'); });
    ['errName','errPrice','errGroup'].forEach(function(id) {
        var el = document.getElementById(id); el.textContent = ''; el.classList.remove('show');
    });

    if (!name.value.trim()) {
        name.classList.add('is-invalid');
        showErr('errName', 'Nama add-on wajib diisi.'); valid = false;
    }
    if (price.value === '' || Number(price.value) < 0) {
        price.classList.add('is-invalid');
        showErr('errPrice', 'Harga wajib diisi dan tidak boleh negatif.'); valid = false;
    }
    if (!group.value) {
        group.classList.add('is-invalid');
        showErr('errGroup', 'Group add-on wajib dipilih.'); valid = false;
    }
    if (!valid) e.preventDefault();
});

function showErr(id, msg) {
    var el = document.getElementById(id); el.textContent = msg; el.classList.add('show');
}

document.getElementById('fieldName').addEventListener('input', function() {
    this.classList.remove('is-invalid'); document.getElementById('errName').classList.remove('show');
});
document.getElementById('fieldPrice').addEventListener('input', function() {
    this.classList.remove('is-invalid'); document.getElementById('errPrice').classList.remove('show');
});
document.getElementById('fieldGroup').addEventListener('change', function() {
    this.classList.remove('is-invalid'); document.getElementById('errGroup').classList.remove('show');
});

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