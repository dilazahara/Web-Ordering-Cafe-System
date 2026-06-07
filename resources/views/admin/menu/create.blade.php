@extends('layouts.admin')

@section('title', 'Tambah Menu')

@push('styles')
<style>
/* ── DIVIDER ── */
.divider { display: flex; align-items: center; gap: 12px; margin: 24px 0 20px; }
.divider span { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .8px; white-space: nowrap; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #f0f0f0; }

/* ── FORM ── */
.form-input, .form-select, .form-textarea {
    width: 100%; padding: 11px 14px; border-radius: 12px; border: 1.5px solid #e5e7eb;
    background: #f9fafb; font-size: 14px; color: #111827; font-family: 'Plus Jakarta Sans', sans-serif;
    outline: none; transition: .2s;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: #6366f1; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
}
.form-input::placeholder, .form-textarea::placeholder { color: #9ca3af; }
.form-textarea { resize: none; }
.form-label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 700; color: #111827; }

/* ── IMAGE UPLOAD ── */
.img-upload-box {
    border: 2px dashed #e5e7eb; border-radius: 16px; background: #fafafa;
    text-align: center; cursor: pointer; position: relative; overflow: hidden;
    transition: all .2s; min-height: 160px;
    display: flex; align-items: center; justify-content: center;
}
.img-upload-box:hover { border-color: #6366f1; background: #eef2ff; }
.img-upload-box input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; }
.upload-icon { color: #d1d5db; margin-bottom: 10px; }
.upload-text { font-size: 13px; font-weight: 700; color: #6b7280; }
.upload-hint { font-size: 11.5px; color: #c4c9d4; margin-top: 4px; }
.img-preview { width: 100%; max-height: 220px; object-fit: cover; border-radius: 12px; display: none; }
.img-preview.show { display: block; }

/* ── TOGGLE STATUS ── */
.toggle-status-wrap {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 18px; border-radius: 14px; border: 1.5px solid #e5e7eb;
    background: #f9fafb; transition: all .25s; cursor: default;
}
.toggle-status-wrap.is-on  { border-color: #22c55e; background: #f0fdf4; }
.toggle-status-wrap.is-off { border-color: #f87171; background: #fef2f2; }
.toggle-status-left { display: flex; align-items: center; gap: 14px; }
.toggle-status-icon {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .25s;
}
.toggle-status-wrap.is-on  .toggle-status-icon { background: #dcfce7; }
.toggle-status-wrap.is-off .toggle-status-icon { background: #fee2e2; }
.toggle-status-icon svg { width: 20px; height: 20px; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.toggle-status-wrap.is-on  .toggle-status-icon svg { stroke: #16a34a; }
.toggle-status-wrap.is-off .toggle-status-icon svg { stroke: #dc2626; }
.ts-label { font-size: 14px; font-weight: 700; color: #111827; }
.ts-desc  { font-size: 12px; color: #9ca3af; margin-top: 3px; transition: color .25s; }
.toggle-status-wrap.is-on  .ts-desc { color: #16a34a; }
.toggle-status-wrap.is-off .ts-desc { color: #dc2626; }

/* Switch knob */
.ts-switch { position: relative; width: 54px; height: 30px; flex-shrink: 0; cursor: pointer; }
.ts-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
.ts-track {
    position: absolute; inset: 0; border-radius: 99px;
    background: #d1d5db; transition: background .25s;
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
    padding: 13px 15px; border-radius: 13px; border: 1.5px solid #f1f5f9;
    background: #fafafa; cursor: pointer; transition: all .2s; margin-bottom: 8px;
}
.addon-item:hover { border-color: #ddd6fe; background: #f5f3ff; }
.addon-item.selected { border-color: #8b5cf6; background: #f5f3ff; }
.addon-item input[type=checkbox] {
    appearance: none; -webkit-appearance: none;
    width: 20px; height: 20px; flex-shrink: 0; margin-top: 1px;
    border: 2px solid #d1d5db; border-radius: 6px; background: white;
    cursor: pointer; transition: all .18s;
}
.addon-item input[type=checkbox]:checked {
    background: #8b5cf6; border-color: #8b5cf6;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5 10l3.5 3.5L15 7' stroke='white' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: center;
}
.addon-name { font-size: 14px; font-weight: 700; color: #111827; }
.addon-badge { display: inline-flex; align-items: center; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px; margin-left: 6px; }
.badge-wajib { background: #fee2e2; color: #dc2626; }
.badge-maks  { background: #dbeafe; color: #1d4ed8; }
.addon-list  { font-size: 12px; color: #9ca3af; margin-top: 4px; }

/* ── BUTTONS ── */
.btn-group { display: flex; gap: 12px; margin-top: 28px; }
.btn-simpan {
    flex: 1; padding: 13px; border-radius: 14px; border: none;
    font-size: 14px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white; box-shadow: 0 6px 18px rgba(99,102,241,.28);
    transition: transform .2s, box-shadow .2s;
}
.btn-simpan:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(99,102,241,.34); }
.btn-simpan svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
.btn-batal {
    flex: 1; padding: 13px; border-radius: 14px;
    border: 1.5px solid #e5e7eb; font-size: 14px; font-weight: 600;
    background: white; color: #374151; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    text-decoration: none; transition: background .15s;
}
.btn-batal:hover { background: #f9fafb; }

@media(max-width:540px) {
    .form-grid-2 { grid-template-columns: 1fr !important; }
    .btn-group { flex-direction: column; }
}
</style>
@endpush

@section('content')

<div class="form-page-wrap">

    {{-- Page header --}}
    <div class="page-header" style="margin-bottom: 20px;">
        <div>
            <div class="page-title" style="font-size:22px;">Tambah Menu Baru</div>
            <div class="page-sub">Isi informasi menu yang akan ditampilkan ke pelanggan</div>
        </div>
    </div>

    {{-- Alert error --}}
    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:20px;">
        <i data-lucide="alert-circle"></i>
        <div>
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="form-card">

        <form action="/admin/menu/store" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ── INFORMASI MENU ── --}}
            <div class="divider"><span>Informasi Menu</span></div>

            <div class="form-grid-2" style="gap:14px; margin-bottom:16px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Nama Menu <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" class="form-input"
                           value="{{ old('name') }}" placeholder="Contoh: Es Teh Manis" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Harga <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="price" class="form-input"
                           value="{{ old('price') }}" placeholder="15000" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                <select name="kategori_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-textarea" rows="3"
                          placeholder="Deskripsi singkat menu ini...">{{ old('description') }}</textarea>
            </div>

            {{-- ── FOTO MENU ── --}}
            <div class="divider"><span>Foto Menu</span></div>

            <div class="form-group">
                <div class="img-upload-box" id="uploadBox">
                    <input type="file" name="image" accept=".jpg,.jpeg,.png" onchange="previewImage(event)">
                    <div id="uploadPlaceholder" style="padding: 28px 16px;">
                        <div class="upload-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:40px;height:40px;">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <p class="upload-text" style="margin-top:10px;">Klik untuk upload foto menu</p>
                        <p class="upload-hint">JPG, JPEG, PNG — Maks 2MB</p>
                    </div>
                    <img id="preview" class="img-preview" alt="Preview">
                </div>
                @error('image')
                    <p style="color:#ef4444;font-size:12px;margin-top:5px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── STATUS MENU ── --}}
            <div class="divider"><span>Status Menu</span></div>

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

            {{-- ── ADD-ON GROUPS ── --}}
            @if(isset($groups) && $groups->count() > 0)
            <div class="divider"><span>Add-on Groups</span></div>
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
            @endif

            {{-- ── TOMBOL ── --}}
            <div class="btn-group">
                <button type="submit" class="btn-simpan">
                    <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Menu
                </button>
                <a href="/admin/menu" class="btn-batal">Batal</a>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ── PREVIEW GAMBAR ── */
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
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

/* ── TOGGLE STATUS ── */
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

/* ── ADDON TOGGLE ── */
function toggleAddon(cb, id) {
    document.getElementById(id).classList.toggle('selected', cb.checked);
}

lucide.createIcons();
</script>
@endpush