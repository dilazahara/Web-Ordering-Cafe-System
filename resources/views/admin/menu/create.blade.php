@extends('layouts.admin')

@section('title', 'Tambah Menu')

@push('styles')
<style>
/* ── SECTION CARDS (NEW) ── */
.form-layout { display: flex; flex-direction: column; gap: 24px; margin-bottom: 40px; }
.section-card {
    background: #ffffff;
    border: 1px solid var(--border-light, #e5e7eb);
    border-radius: var(--radius-xl, 16px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    overflow: hidden;
}
.section-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-light, #e5e7eb);
    background: #fafafa;
    display: flex;
    align-items: center;
    gap: 14px;
}
.section-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    background: white;
    border: 1px solid var(--border-light, #e5e7eb);
    display: flex; align-items: center; justify-content: center;
    color: var(--primary, #6366f1);
    flex-shrink: 0;
}
.section-icon svg { width: 20px; height: 20px; stroke-width: 2.2; }
.section-title { margin: 0; font-size: 16px; font-weight: 700; color: var(--text-dark, #1f2937); }
.section-desc { margin: 2px 0 0 0; font-size: 13px; color: var(--text-muted, #6b7280); }
.section-body { padding: 24px; }

/* ── FORM ── */
.form-input, .form-select, .form-textarea {
    width: 100%; padding: 11px 14px; border-radius: var(--radius-lg, 12px); border: 1.5px solid var(--border, #d1d5db);
    background: var(--bg, #f9fafb); font-size: var(--text-md, 14px); color: var(--text-dark, #1f2937); font-family: var(--font, inherit);
    outline: none; transition: .2s;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: var(--primary, #6366f1); background: white; box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
}
.form-input::placeholder, .form-textarea::placeholder { color: var(--text-muted, #6b7280); }
.form-textarea { resize: none; }
.form-label { display: block; margin-bottom: 7px; font-size: var(--text-base, 15px); font-weight: 700; color: var(--text-dark, #1f2937); }

/* ── INPUT ERROR STATE ── */
.form-input.is-invalid,
.form-select.is-invalid,
.form-textarea.is-invalid {
    border-color: #ef4444 !important;
    background: #fff5f5 !important;
    box-shadow: 0 0 0 3px rgba(239,68,68,0.08);
}
.field-error {
    color: #ef4444; font-size: var(--text-sm, 13px); margin-top: 5px;
    display: flex; align-items: center; gap: 4px;
}
.field-error::before { content: '⚠'; font-size: 11px; }

/* ── IMAGE UPLOAD ── */
.img-upload-box {
    border: 2px dashed var(--border, #d1d5db); border-radius: var(--radius-xl, 16px); background: #fafafa;
    text-align: center; cursor: pointer; position: relative; overflow: hidden;
    transition: all .2s; min-height: 160px;
    display: flex; align-items: center; justify-content: center;
}
.img-upload-box:hover { border-color: var(--primary, #6366f1); background: var(--primary-light, #eef2ff); }
.img-upload-box.is-invalid { border-color: #ef4444 !important; background: #fff5f5 !important; }
.img-upload-box input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; }
.upload-icon { color: var(--text-muted, #6b7280); margin-bottom: 10px; }
.upload-text { font-size: var(--text-base, 15px); font-weight: 700; color: var(--text-light, #4b5563); }
.upload-hint { font-size: 11.5px; color: #c4c9d4; margin-top: 4px; }
.img-preview { width: 100%; max-height: 220px; object-fit: cover; border-radius: var(--radius-lg, 12px); display: none; }
.img-preview.show { display: block; }

/* ── TOGGLE STATUS ── */
.toggle-status-wrap {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 18px; border-radius: var(--radius-xl, 16px); border: 1.5px solid var(--border, #d1d5db);
    background: var(--bg, #f9fafb); transition: all .25s; cursor: default;
}
.toggle-status-wrap.is-on  { border-color: #22c55e; background: #f0fdf4; }
.toggle-status-wrap.is-off { border-color: #f87171; background: #fef2f2; }
.toggle-status-left { display: flex; align-items: center; gap: 14px; }
.toggle-status-icon {
    width: 40px; height: 40px; border-radius: var(--radius-lg, 12px);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .25s;
}
.toggle-status-wrap.is-on  .toggle-status-icon { background: #dcfce7; }
.toggle-status-wrap.is-off .toggle-status-icon { background: #fee2e2; }
.toggle-status-icon svg { width: 20px; height: 20px; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.toggle-status-wrap.is-on  .toggle-status-icon svg { stroke: #16a34a; }
.toggle-status-wrap.is-off .toggle-status-icon svg { stroke: #dc2626; }
.ts-label { font-size: var(--text-md, 14px); font-weight: 700; color: var(--text-dark, #1f2937); }
.ts-desc  { font-size: var(--text-sm, 13px); color: var(--text-muted, #6b7280); margin-top: 3px; transition: color .25s; }
.toggle-status-wrap.is-on  .ts-desc { color: #16a34a; }
.toggle-status-wrap.is-off .ts-desc { color: #dc2626; }

/* Switch knob */
.ts-switch { position: relative; width: 54px; height: 30px; flex-shrink: 0; cursor: pointer; }
.ts-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
.ts-track {
    position: absolute; inset: 0; border-radius: 99px;
    background: var(--border, #d1d5db); transition: background .25s;
}
.ts-switch input:checked ~ .ts-track { background: #22c55e; }
.ts-knob {
    position: absolute; top: 3px; left: 3px;
    width: 24px; height: 24px; border-radius: 50%;
    background: white; box-shadow: 0 1px 5px rgba(0,0,0,0.2);
    transition: transform .25s cubic-bezier(.34,1.56,.64,1);
}
.ts-switch input:checked ~ .ts-knob { transform: translateX(24px); }

/* ── ADDON ── */
.addon-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 13px 15px; border-radius: var(--radius-lg, 12px); border: 1.5px solid var(--border-light, #e5e7eb);
    background: #fafafa; cursor: pointer; transition: all .2s; margin-bottom: 8px;
}
.addon-item:hover { border-color: #ddd6fe; background: #f5f3ff; }
.addon-item.selected { border-color: #8b5cf6; background: #f5f3ff; }
.addon-item input[type=checkbox] {
    appearance: none; -webkit-appearance: none;
    width: 20px; height: 20px; flex-shrink: 0; margin-top: 1px;
    border: 2px solid var(--border, #d1d5db); border-radius: 6px; background: white;
    cursor: pointer; transition: all .18s;
}
.addon-item input[type=checkbox]:checked {
    background: #8b5cf6; border-color: #8b5cf6;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5 10l3.5 3.5L15 7' stroke='white' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: center;
}
.addon-name { font-size: var(--text-md, 14px); font-weight: 700; color: var(--text-dark, #1f2937); }
.addon-badge { display: inline-flex; align-items: center; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px; margin-left: 6px; }
.badge-wajib { background: #fee2e2; color: #dc2626; }
.badge-maks  { background: #dbeafe; color: #1d4ed8; }
.addon-list  { font-size: var(--text-sm, 13px); color: var(--text-muted, #6b7280); margin-top: 4px; }

/* ── BUTTONS ── */
.btn-group { display: flex; gap: 12px; margin-top: 28px; }
.btn-simpan {
    flex: 1; padding: 13px; border-radius: var(--radius-xl, 16px); border: none;
    font-size: var(--text-md, 14px); font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    font-family: var(--font, inherit);
    background: linear-gradient(135deg, var(--primary, #6366f1), var(--primary-dark, #4f46e5));
    color: white; box-shadow: 0 6px 18px rgba(99,102,241,.28);
    transition: transform .2s, box-shadow .2s;
}
.btn-simpan:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(99,102,241,.34); }
.btn-simpan svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
.btn-batal {
    flex: 1; padding: 13px; border-radius: var(--radius-xl, 16px);
    border: 1.5px solid var(--border, #d1d5db); font-size: var(--text-md, 14px); font-weight: 600;
    background: white; color: var(--text-mid, #4b5563); cursor: pointer; font-family: var(--font, inherit);
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    text-decoration: none; transition: background .15s;
}
.btn-batal:hover { background: var(--bg, #f9fafb); }

.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-page-wrap { max-width: 800px; margin: 0 auto; }

@media(max-width:540px) {
    .form-grid-2 { grid-template-columns: 1fr !important; }
    .btn-group { flex-direction: column; }
}
</style>
@endpush

@section('content')

<div class="form-page-wrap">

    <div class="page-header" style="margin-bottom: 24px;">
        <div class="page-title">
            <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark, #1f2937); margin-bottom: 6px;">Tambah Menu Baru</h1>
            <p style="color: var(--text-muted, #6b7280); font-size: 14px; margin: 0;">Isi informasi menu yang akan ditampilkan ke pelanggan</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error" style="background:#fef2f2; color:#dc2626; border:1px solid #fee2e2; border-radius:14px; padding:13px 17px; margin-bottom:24px;">
        @foreach($errors->all() as $err)
            <div style="font-size: 14px; margin-bottom: 4px;">• {{ $err }}</div>
        @endforeach
    </div>
    @endif

    <form action="/admin/menu/store" method="POST" enctype="multipart/form-data" id="menuForm" novalidate>
        @csrf
        <div class="form-layout">

            {{-- ── INFORMASI MENU CARD ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="file-text"></i></div>
                    <div>
                        <h2 class="section-title">Informasi Menu</h2>
                        <p class="section-desc">Detail dasar mengenai menu yang dijual</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="form-grid-2" style="margin-bottom:16px;">
                        <div class="form-group">
                            <label class="form-label">Nama Menu <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" id="fieldName" class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name') }}" placeholder="Contoh: Es Teh Manis">
                            @error('name')
                                <p class="field-error">{{ $message }}</p>
                            @else
                                <p class="field-error" id="errorName" style="display:none;"></p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga <span style="color:#ef4444;">*</span></label>
                            <input type="number" name="price" id="fieldPrice" class="form-input {{ $errors->has('price') ? 'is-invalid' : '' }}"
                                   value="{{ old('price') }}" placeholder="15000" min="0">
                            @error('price')
                                <p class="field-error">{{ $message }}</p>
                            @else
                                <p class="field-error" id="errorPrice" style="display:none;"></p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:16px;">
                        <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                        <select name="kategori_id" id="fieldKategori" class="form-select {{ $errors->has('kategori_id') ? 'is-invalid' : '' }}">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="field-error">{{ $message }}</p>
                        @else
                            <p class="field-error" id="errorKategori" style="display:none;"></p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi <span style="color:#ef4444;">*</span></label>
                        <textarea name="description" id="fieldDescription" class="form-textarea {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="3"
                                  placeholder="Deskripsi singkat menu ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="field-error">{{ $message }}</p>
                        @else
                            <p class="field-error" id="errorDescription" style="display:none;"></p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── FOTO MENU CARD ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="image"></i></div>
                    <div>
                        <h2 class="section-title">Foto Menu</h2>
                        <p class="section-desc">Visual menu untuk menarik pelanggan (Maks 2MB)</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="form-group">
                        <label class="form-label">Upload Foto <span style="color:#ef4444;">*</span></label>
                        <div class="img-upload-box {{ $errors->has('image') ? 'is-invalid' : '' }}" id="uploadBox">
                            <input type="file" name="image" id="fieldImage" accept=".jpg,.jpeg,.png,.webp" onchange="previewImage(event)">
                            <div id="uploadPlaceholder" style="padding: 28px 16px;">
                                <div class="upload-icon">
                                    <i data-lucide="upload-cloud" style="width:40px;height:40px;color:var(--border, #d1d5db);"></i>
                                </div>
                                <p class="upload-text" style="margin-top:10px;">Klik untuk upload foto menu</p>
                                <p class="upload-hint">JPG, JPEG, PNG, WEBP — Maks 2MB</p>
                            </div>
                            <img id="preview" class="img-preview" alt="Preview">
                        </div>
                        @error('image')
                            <p class="field-error">{{ $message }}</p>
                        @else
                            <p class="field-error" id="errorImage" style="display:none;"></p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── STATUS MENU CARD ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="toggle-right"></i></div>
                    <div>
                        <h2 class="section-title">Status Menu</h2>
                        <p class="section-desc">Atur ketersediaan menu di halaman pelanggan</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="form-group">
                        <div class="toggle-status-wrap is-on" id="statusWrap">
                            <div class="toggle-status-left">
                                <div class="toggle-status-icon">
                                    <svg viewBox="0 0 24 24" id="statusSvg">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="ts-label" id="statusLabel">Aktif</div>
                                    <div class="ts-desc"  id="statusDesc">Menu tampil ke pelanggan</div>
                                </div>
                            </div>
                            <label class="ts-switch" title="Klik untuk ubah status">
                                <input type="checkbox" id="statusToggle" checked onchange="handleStatusToggle(this)">
                                <div class="ts-track"></div>
                                <div class="ts-knob"></div>
                            </label>
                            <input type="hidden" name="status" id="statusHidden" value="1">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── ADD-ON GROUPS CARD ── --}}
            @if(isset($groups) && $groups->count() > 0)
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="layers"></i></div>
                    <div>
                        <h2 class="section-title">Add-on Groups</h2>
                        <p class="section-desc">Pilih grup tambahan opsional atau wajib untuk menu ini</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="form-group">
                        @foreach($groups as $group)
                        <label class="addon-item" id="groupItem_{{ $group->id }}">
                            <input type="checkbox" name="addon_groups[]" value="{{ $group->id }}"
                                   onchange="toggleAddon(this, 'groupItem_{{ $group->id }}')">
                            <div>
                                <div>
                                    <span class="addon-name">{{ $group->name }}</span>
                                    @if($group->required)<span class="addon-badge badge-wajib">Wajib</span>@endif
                                    @if($group->max)<span class="addon-badge badge-maks">Maks {{ $group->max }}</span>@endif
                                </div>
                                <div class="addon-list">
                                    @foreach($group->addons as $addon)
                                        {{ $addon->name }} (+Rp {{ number_format($addon->price) }})@if(!$loop->last), @endif
                                    @endforeach
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- ── TOMBOL AKSI ── --}}
            <div class="btn-group">
                <button type="submit" class="btn-simpan" id="btnSimpan">
                    <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Menu
                </button>
                <a href="/admin/menu" class="btn-batal">Batal</a>
            </div>

        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
// Script logic yang sama persis
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    clearError('fieldImage', 'errorImage', 'uploadBox');
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview     = document.getElementById('preview');
        const placeholder = document.getElementById('uploadPlaceholder');
        preview.src = e.target.result;
        preview.classList.add('show');
        placeholder.style.display = 'none';
        document.getElementById('uploadBox').style.border = 'none';
        document.getElementById('uploadBox').style.padding = '0';
    };
    reader.readAsDataURL(file);
}

function handleStatusToggle(cb) {
    const wrap  = document.getElementById('statusWrap');
    const label = document.getElementById('statusLabel');
    const desc  = document.getElementById('statusDesc');
    const hidden = document.getElementById('statusHidden');
    const svg   = document.getElementById('statusSvg');
    if (cb.checked) {
        wrap.className    = 'toggle-status-wrap is-on';
        label.textContent = 'Aktif';
        desc.textContent  = 'Menu tampil ke pelanggan';
        hidden.value      = '1';
        svg.innerHTML     = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>';
    } else {
        wrap.className    = 'toggle-status-wrap is-off';
        label.textContent = 'Nonaktif';
        desc.textContent  = 'Menu disembunyikan dari pelanggan';
        hidden.value      = '0';
        svg.innerHTML     = '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>';
    }
}

function toggleAddon(cb, id) {
    document.getElementById(id).classList.toggle('selected', cb.checked);
}

function showError(inputId, errorId, message, uploadBoxId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);
    if (input)  input.classList.add('is-invalid');
    if (error)  { error.textContent = message; error.style.display = 'flex'; }
    if (uploadBoxId) {
        const box = document.getElementById(uploadBoxId);
        if (box) box.classList.add('is-invalid');
    }
}

function clearError(inputId, errorId, uploadBoxId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);
    if (input)  input.classList.remove('is-invalid');
    if (error)  { error.textContent = ''; error.style.display = 'none'; }
    if (uploadBoxId) {
        const box = document.getElementById(uploadBoxId);
        if (box) box.classList.remove('is-invalid');
    }
}

document.getElementById('menuForm').addEventListener('submit', function(e) {
    let valid = true;

    clearError('fieldName',        'errorName');
    clearError('fieldPrice',       'errorPrice');
    clearError('fieldKategori',    'errorKategori');
    clearError('fieldDescription', 'errorDescription');
    clearError('fieldImage',       'errorImage', 'uploadBox');

    const name = document.getElementById('fieldName').value.trim();
    if (!name) { showError('fieldName', 'errorName', 'Nama menu wajib diisi.'); valid = false; }

    const price = document.getElementById('fieldPrice').value.trim();
    if (!price || isNaN(price) || parseFloat(price) < 0) { showError('fieldPrice', 'errorPrice', 'Harga wajib diisi dengan angka yang valid.'); valid = false; }

    const kategori = document.getElementById('fieldKategori').value;
    if (!kategori) { showError('fieldKategori', 'errorKategori', 'Kategori wajib dipilih.'); valid = false; }

    const description = document.getElementById('fieldDescription').value.trim();
    if (!description) { showError('fieldDescription', 'errorDescription', 'Deskripsi wajib diisi.'); valid = false; }

    const image = document.getElementById('fieldImage');
    if (!image.files || image.files.length === 0) { showError('fieldImage', 'errorImage', 'Gambar wajib diisi.', 'uploadBox'); valid = false; }

    if (!valid) {
        e.preventDefault();
        const firstError = document.querySelector('.field-error[style*="flex"], .field-error:not([style])');
        if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

document.getElementById('fieldName').addEventListener('input', function() { if (this.value.trim()) clearError('fieldName', 'errorName'); });
document.getElementById('fieldPrice').addEventListener('input', function() { const v = this.value.trim(); if (v && !isNaN(v) && parseFloat(v) >= 0) clearError('fieldPrice', 'errorPrice'); });
document.getElementById('fieldKategori').addEventListener('change', function() { if (this.value) clearError('fieldKategori', 'errorKategori'); });
document.getElementById('fieldDescription').addEventListener('input', function() { if (this.value.trim()) clearError('fieldDescription', 'errorDescription'); });

lucide.createIcons();
</script>
@endpush