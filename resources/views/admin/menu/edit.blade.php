@extends('layouts.admin')

@section('title', 'Edit Menu')

@push('styles')
<style>
/* ── LAYOUT & CARDS ── */
.form-page-wrap { max-width: 800px; margin: 0 auto; padding-bottom: 40px; }
.page-header { margin-bottom: 24px; }
.page-title h1 { font-size: 24px; font-weight: 800; color: var(--text-dark, #111827); margin: 0 0 6px; }
.page-title p { color: var(--text-muted, #6b7280); font-size: 14.5px; margin: 0; }

.form-layout { display: flex; flex-direction: column; gap: 24px; }

.section-card {
    background: var(--bg, #ffffff);
    border: 1px solid var(--border-light, #e5e7eb);
    border-radius: var(--radius-xl, 16px);
    padding: 24px 28px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    transition: box-shadow 0.2s ease;
}
.section-card:hover { box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05); }

.section-header {
    display: flex; align-items: center; gap: 16px;
    margin-bottom: 24px; padding-bottom: 16px;
    border-bottom: 1.5px dashed var(--border-light, #e5e7eb);
}
.section-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: #eef2ff; color: #4f46e5;
    display: flex; align-items: center; justify-content: center;
}
.section-icon svg { width: 22px; height: 22px; stroke-width: 2.2; }
.section-title-wrap h2 { font-size: 17px; font-weight: 700; color: var(--text-dark, #1f2937); margin: 0 0 3px; }
.section-title-wrap p { font-size: 13px; color: var(--text-muted, #6b7280); margin: 0; }

/* ── FORM ELEMENTS ── */
.form-input, .form-select, .form-textarea {
    width: 100%; padding: 12px 16px; border-radius: var(--radius-lg, 12px); 
    border: 1.5px solid var(--border, #d1d5db); background: #fafafa; 
    font-size: var(--text-md, 15px); color: var(--text-dark, #1f2937); font-family: var(--font);
    outline: none; transition: all .2s ease;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: #6366f1; background: white; box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
}
.form-input::placeholder, .form-textarea::placeholder { color: #9ca3af; }
.form-textarea { resize: vertical; min-height: 100px; }
.form-label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 700; color: #374151; }

/* ── INPUT ERROR STATE ── */
.form-input.is-invalid, .form-select.is-invalid, .form-textarea.is-invalid {
    border-color: #ef4444 !important; background: #fff5f5 !important;
    box-shadow: 0 0 0 4px rgba(239,68,68,0.1) !important;
}
.field-error {
    color: #ef4444; font-size: 13px; margin-top: 6px; font-weight: 500;
    display: flex; align-items: center; gap: 5px;
}
.field-error::before { content: '⚠'; font-size: 12px; }

/* ── IMAGE UPLOAD ── */
.img-current {
    position: relative; border-radius: var(--radius-xl, 16px); overflow: hidden;
    background: #f3f4f6; height: 220px; margin-bottom: 12px;
    border: 1px solid #e5e7eb;
}
.img-current img { width: 100%; height: 100%; object-fit: cover; display: block; }
.img-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,.5);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .2s; cursor: pointer;
    backdrop-filter: blur(2px);
}
.img-current:hover .img-overlay { opacity: 1; }
.img-overlay span { color: white; font-size: 15px; font-weight: 700; margin-top: 8px; }
.change-img-btn {
    width: 100%; padding: 12px; border-radius: var(--radius-lg, 12px);
    border: 1.5px dashed var(--border, #d1d5db); background: transparent;
    color: #4b5563; font-size: 14px; font-weight: 700;
    cursor: pointer; font-family: var(--font);
    transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px;
}
.change-img-btn:hover { border-color: #6366f1; color: #6366f1; background: #eef2ff; }
.img-upload-new {
    border: 2px dashed #cbd5e1; border-radius: var(--radius-xl, 16px); padding: 30px 20px;
    text-align: center; cursor: pointer; background: #f8fafc;
    display: none; position: relative; transition: all .2s;
}
.img-upload-new.visible { display: block; }
.img-upload-new:hover { border-color: #6366f1; background: #eef2ff; }
.img-upload-new input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.upload-text { font-size: 15px; color: #475569; font-weight: 700; margin-bottom: 4px; }
.upload-hint { font-size: 12px; color: #94a3b8; margin: 0; }
.img-preview-new { width: 100%; height: 220px; object-fit: cover; border-radius: var(--radius-xl, 16px); display: none; margin-top: 12px; border: 1px solid #e5e7eb; }
.img-preview-new.show { display: block; }

/* ── TOGGLE STATUS ── */
.toggle-status-wrap {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-radius: var(--radius-lg, 14px); border: 1.5px solid var(--border, #d1d5db);
    background: #fafafa; transition: all .25s; cursor: default;
}
.toggle-status-wrap.is-on  { border-color: #22c55e; background: #f0fdf4; }
.toggle-status-wrap.is-off { border-color: #f87171; background: #fef2f2; }
.toggle-status-left { display: flex; align-items: center; gap: 16px; }
.toggle-status-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .25s;
}
.toggle-status-wrap.is-on  .toggle-status-icon { background: #dcfce7; }
.toggle-status-wrap.is-off .toggle-status-icon { background: #fee2e2; }
.toggle-status-icon svg { width: 22px; height: 22px; fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
.toggle-status-wrap.is-on  .toggle-status-icon svg { stroke: #16a34a; }
.toggle-status-wrap.is-off .toggle-status-icon svg { stroke: #dc2626; }
.ts-label { font-size: 16px; font-weight: 700; color: var(--text-dark, #1f2937); }
.ts-desc  { font-size: 13px; color: var(--text-muted, #6b7280); margin-top: 2px; transition: color .25s; }
.toggle-status-wrap.is-on  .ts-desc { color: #15803d; }
.toggle-status-wrap.is-off .ts-desc { color: #b91c1c; }

/* Switch knob */
.ts-switch { position: relative; width: 56px; height: 32px; flex-shrink: 0; cursor: pointer; }
.ts-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
.ts-track {
    position: absolute; inset: 0; border-radius: 99px;
    background: #cbd5e1; transition: background .25s;
}
.ts-switch input:checked ~ .ts-track { background: #22c55e; }
.ts-knob {
    position: absolute; top: 3px; left: 3px;
    width: 26px; height: 26px; border-radius: 50%;
    background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transition: transform .25s cubic-bezier(.34,1.56,.64,1);
}
.ts-switch input:checked ~ .ts-knob { transform: translateX(24px); }

/* ── INFO BOX ── */
.info-box { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: var(--radius-lg, 12px); padding: 14px 18px; margin-top: 16px; }
.info-row { display: flex; justify-content: space-between; font-size: 14px; color: #64748b; padding: 4px 0; }
.info-row span { font-weight: 700; color: #334155; }

/* ── ADDON ── */
.addon-item {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 16px; border-radius: var(--radius-lg, 12px); border: 1.5px solid #e2e8f0;
    background: #f8fafc; cursor: pointer; transition: all .2s; margin-bottom: 10px;
}
.addon-item:hover { border-color: #c4b5fd; background: #f5f3ff; }
.addon-item.selected { border-color: #8b5cf6; background: #f5f3ff; box-shadow: 0 2px 10px rgba(139,92,246,0.08); }
.addon-item input[type=checkbox] {
    appearance: none; -webkit-appearance: none;
    width: 22px; height: 22px; flex-shrink: 0; margin-top: 2px;
    border: 2px solid #cbd5e1; border-radius: 6px; background: white;
    cursor: pointer; transition: all .2s;
}
.addon-item input[type=checkbox]:checked {
    background: #8b5cf6; border-color: #8b5cf6;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5 10l3.5 3.5L15 7' stroke='white' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: center;
}
.addon-name { font-size: 15px; font-weight: 700; color: #1e293b; }
.addon-badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; margin-left: 8px; vertical-align: middle; }
.badge-wajib { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
.badge-maks  { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
.addon-list  { font-size: 13px; color: #64748b; margin-top: 6px; line-height: 1.5; }

/* ── BUTTONS ── */
.btn-group { display: flex; gap: 16px; }
.btn-simpan {
    flex: 1; padding: 14px; border-radius: var(--radius-xl, 14px); border: none;
    font-size: 16px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    font-family: var(--font);
    background: #4f46e5;
    color: white; box-shadow: 0 4px 12px rgba(79,70,229,.25);
    transition: all .2s;
}
.btn-simpan:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(79,70,229,.35); }
.btn-simpan svg { width: 18px; height: 18px; stroke: white; fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
.btn-batal {
    flex: 1; padding: 14px; border-radius: var(--radius-xl, 14px);
    border: 1.5px solid #d1d5db; font-size: 16px; font-weight: 700;
    background: white; color: #4b5563; cursor: pointer; font-family: var(--font);
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    text-decoration: none; transition: all .2s;
}
.btn-batal:hover { background: #f3f4f6; color: #1f2937; border-color: #9ca3af; }

.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

@media(max-width:640px) {
    .form-grid-2 { grid-template-columns: 1fr !important; }
    .btn-group { flex-direction: column; }
    .section-card { padding: 20px; }
}
</style>
@endpush

@section('content')

<div class="form-page-wrap">

    <div class="page-header">
        <div class="page-title">
            <h1>Edit Menu</h1>
            <p>Perbarui informasi menu yang sudah ada pada sistem.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error" style="background:#fef2f2; color:#dc2626; border:1px solid #fee2e2; border-radius:14px; padding:16px 20px; margin-bottom:24px; box-shadow: 0 2px 10px rgba(220,38,38,0.05);">
        <div style="font-weight:700; margin-bottom:6px; display:flex; align-items:center; gap:8px;">
            <i data-lucide="alert-triangle" style="width:18px; height:18px;"></i>
            Terdapat kesalahan input:
        </div>
        <ul style="margin:0; padding-left:24px; font-size:14px; line-height:1.6;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="/admin/menu/update/{{ $menu->id }}" method="POST" enctype="multipart/form-data" id="menuEditForm" novalidate>
        @csrf
        @method('PUT')

        <div class="form-layout">
            
            {{-- ── CARD 1: INFORMASI MENU ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="utensils"></i></div>
                    <div class="section-title-wrap">
                        <h2>Informasi Menu</h2>
                        <p>Lengkapi identitas dasar dan harga menu</p>
                    </div>
                </div>

                <div class="form-grid-2" style="margin-bottom:20px;">
                    <div class="form-group">
                        <label class="form-label">Nama Menu <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" id="fieldName" class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $menu->name) }}" placeholder="Contoh: Nasi Goreng Spesial">
                        @error('name')
                            <p class="field-error">{{ $message }}</p>
                        @else
                            <p class="field-error" id="errorName" style="display:none;"></p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga (Rp) <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="price" id="fieldPrice" class="form-input {{ $errors->has('price') ? 'is-invalid' : '' }}"
                               value="{{ old('price', $menu->price) }}" min="0" placeholder="Contoh: 25000">
                        @error('price')
                            <p class="field-error">{{ $message }}</p>
                        @else
                            <p class="field-error" id="errorPrice" style="display:none;"></p>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:20px;">
                    <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                    <select name="kategori_id" id="fieldKategori" class="form-select {{ $errors->has('kategori_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ old('kategori_id', $menu->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                    <textarea name="description" id="fieldDescription" class="form-textarea {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              rows="3" placeholder="Jelaskan detail tentang menu ini...">{{ old('description', $menu->description) }}</textarea>
                    @error('description')
                        <p class="field-error">{{ $message }}</p>
                    @else
                        <p class="field-error" id="errorDescription" style="display:none;"></p>
                    @enderror
                </div>
            </div>

            {{-- ── CARD 2: FOTO MENU ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="image"></i></div>
                    <div class="section-title-wrap">
                        <h2>Foto Menu</h2>
                        <p>Visual menu yang akan ditampilkan ke pelanggan</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Saat Ini <small style="font-weight:400; color:var(--text-muted);">(Biarkan jika tidak ingin diganti)</small></label>
                    
                    <div class="img-current" id="currentImgWrap">
                        <img id="currentImg"
                             src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://placehold.co/560x220/f3f4f6/9ca3af?text=No+Image' }}"
                             alt="{{ $menu->name }}">
                        <div class="img-overlay" onclick="showUpload()">
                            <i data-lucide="image-plus" style="width:30px;height:30px;color:white;"></i>
                            <span>Ganti Foto Menu</span>
                        </div>
                    </div>
                    
                    <button type="button" class="change-img-btn" id="changeBtnEl" onclick="showUpload()">
                        <i data-lucide="refresh-cw" style="width:16px;height:16px;"></i>
                        Ganti Foto Baru
                    </button>

                    <div class="img-upload-new" id="uploadNew">
                        <input type="file" name="image" id="fieldImage" accept=".jpg,.jpeg,.png,.webp" onchange="previewNewImage(event)">
                        <div style="color:#94a3b8; margin-bottom:12px;">
                            <i data-lucide="upload-cloud" style="width:40px;height:40px;"></i>
                        </div>
                        <p class="upload-text">Klik atau Drag & Drop foto baru disini</p>
                        <p class="upload-hint">Format yang didukung: JPG, JPEG, PNG, WEBP (Maksimal 2MB)</p>
                    </div>
                    
                    <img id="previewNew" class="img-preview-new" alt="Preview baru">
                    
                    @error('image')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ── CARD 3: STATUS & DETAIL ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="activity"></i></div>
                    <div class="section-title-wrap">
                        <h2>Status & Detail Menu</h2>
                        <p>Atur visibilitas menu pada halaman pemesanan</p>
                    </div>
                </div>

                @php $isActive = old('status', $menu->status) == 1; @endphp
                <div class="form-group">
                    <div class="toggle-status-wrap {{ $isActive ? 'is-on' : 'is-off' }}" id="statusWrap">
                        <div class="toggle-status-left">
                            <div class="toggle-status-icon">
                                <svg viewBox="0 0 24 24" id="statusSvg">
                                    @if($isActive)
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    @else
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="15" y1="9" x2="9" y2="15"/>
                                        <line x1="9" y1="9" x2="15" y2="15"/>
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <div class="ts-label" id="statusLabel">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</div>
                                <div class="ts-desc"  id="statusDesc">
                                    {{ $isActive ? 'Menu akan ditampilkan kepada pelanggan' : 'Menu saat ini disembunyikan' }}
                                </div>
                            </div>
                        </div>
                        <label class="ts-switch" title="Klik untuk ubah status">
                            <input type="checkbox" id="statusToggle"
                                   {{ $isActive ? 'checked' : '' }}
                                   onchange="handleStatusToggle(this)">
                            <div class="ts-track"></div>
                            <div class="ts-knob"></div>
                        </label>
                        <input type="hidden" name="status" id="statusHidden" value="{{ $isActive ? '1' : '0' }}">
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-row">ID Referensi Menu <span>#{{ $menu->id }}</span></div>
                    <div class="info-row">Tanggal Dibuat <span>{{ $menu->created_at->format('d M Y, H:i') }}</span></div>
                    <div class="info-row">Terakhir Diperbarui <span>{{ $menu->updated_at->format('d M Y, H:i') }}</span></div>
                </div>
            </div>

            {{-- ── CARD 4: ADD-ON GROUPS ── --}}
            @if(isset($groups) && $groups->count() > 0)
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="layers"></i></div>
                    <div class="section-title-wrap">
                        <h2>Grup Add-on</h2>
                        <p>Tentukan pilihan tambahan yang tersedia untuk menu ini</p>
                    </div>
                </div>

                <div class="form-group" style="margin:0;">
                    @foreach($groups as $group)
                    @php $isChecked = $menu->addonGroups->contains($group->id); @endphp
                    <label class="addon-item {{ $isChecked ? 'selected' : '' }}" id="groupItem_{{ $group->id }}">
                        <input type="checkbox" name="addon_groups[]" value="{{ $group->id }}"
                               {{ $isChecked ? 'checked' : '' }}
                               onchange="toggleAddon(this, 'groupItem_{{ $group->id }}')">
                        <div style="flex:1;">
                            <div>
                                <span class="addon-name">{{ $group->name }}</span>
                                @if($group->required)<span class="addon-badge badge-wajib">Wajib Dipilih</span>@endif
                                @if($group->max)<span class="addon-badge badge-maks">Maks {{ $group->max }} Pilihan</span>@endif
                            </div>
                            <div class="addon-list">
                                @foreach($group->addons as $addon)
                                    {{ $addon->name }} <span style="font-weight:600; color:#94a3b8;">(+Rp {{ number_format($addon->price) }})</span>@if(!$loop->last), @endif
                                @endforeach
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── TOMBOL AKSI ── --}}
            <div class="btn-group">
                <a href="/admin/menu" class="btn-batal">
                    <i data-lucide="x" style="width:18px;height:18px;"></i>
                    Batal Kembali
                </a>
                <button type="submit" class="btn-simpan" id="btnSimpan">
                    <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Perubahan
                </button>
            </div>

        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
// Script logic yang sama persis dipertahankan
function showUpload() {
    document.getElementById('uploadNew').classList.toggle('visible');
    document.getElementById('currentImgWrap').style.display = 'none';
    document.getElementById('changeBtnEl').style.display = 'none';
}

function previewNewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('previewNew');
        img.src = e.target.result;
        img.classList.add('show');
        const uploadNew = document.getElementById('uploadNew');
        uploadNew.style.padding = '0';
        uploadNew.style.border  = 'none';
    };
    reader.readAsDataURL(file);
}

function handleStatusToggle(cb) {
    const wrap   = document.getElementById('statusWrap');
    const label  = document.getElementById('statusLabel');
    const desc   = document.getElementById('statusDesc');
    const hidden = document.getElementById('statusHidden');
    const svg    = document.getElementById('statusSvg');
    if (cb.checked) {
        wrap.className    = 'toggle-status-wrap is-on';
        label.textContent = 'Aktif';
        desc.textContent  = 'Menu akan ditampilkan kepada pelanggan';
        hidden.value      = '1';
        svg.innerHTML     = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>';
    } else {
        wrap.className    = 'toggle-status-wrap is-off';
        label.textContent = 'Nonaktif';
        desc.textContent  = 'Menu saat ini disembunyikan';
        hidden.value      = '0';
        svg.innerHTML     = '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>';
    }
}

function toggleAddon(cb, id) {
    document.getElementById(id).classList.toggle('selected', cb.checked);
}

function showError(inputId, errorId, message) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);
    if (input)  input.classList.add('is-invalid');
    if (error)  { error.textContent = message; error.style.display = 'flex'; }
}

function clearError(inputId, errorId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);
    if (input)  input.classList.remove('is-invalid');
    if (error)  { error.textContent = ''; error.style.display = 'none'; }
}

document.getElementById('menuEditForm').addEventListener('submit', function(e) {
    let valid = true;

    clearError('fieldName',        'errorName');
    clearError('fieldPrice',       'errorPrice');
    clearError('fieldKategori',    'errorKategori');
    clearError('fieldDescription', 'errorDescription');

    const name = document.getElementById('fieldName').value.trim();
    if (!name) { showError('fieldName', 'errorName', 'Nama menu wajib diisi.'); valid = false; }

    const price = document.getElementById('fieldPrice').value.trim();
    if (!price || isNaN(price) || parseFloat(price) < 0) { showError('fieldPrice', 'errorPrice', 'Harga wajib diisi dengan angka yang valid.'); valid = false; }

    const kategori = document.getElementById('fieldKategori').value;
    if (!kategori) { showError('fieldKategori', 'errorKategori', 'Kategori wajib dipilih.'); valid = false; }

    const description = document.getElementById('fieldDescription').value.trim();
    if (!description) { showError('fieldDescription', 'errorDescription', 'Deskripsi wajib diisi.'); valid = false; }

    if (!valid) {
        e.preventDefault();
        const firstError = document.querySelector('.field-error[style*="flex"]');
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