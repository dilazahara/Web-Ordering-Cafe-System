<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Menu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
body { background: #f3f4f6; min-height: 100vh; padding: 50px 20px; }

.container { max-width: 560px; margin: auto; }

.back-link { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #6b7280; text-decoration: none; margin-bottom: 20px; transition: color .15s; }
.back-link:hover { color: #111827; }

.card { background: white; padding: 32px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

.title { margin-bottom: 28px; }
.title h2 { font-size: 26px; color: #111827; font-weight: 800; }
.title p  { margin-top: 5px; color: #6b7280; font-size: 13.5px; }

.divider { display: flex; align-items: center; gap: 12px; margin: 24px 0; }
.divider span { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .8px; white-space: nowrap; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #f0f0f0; }

.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media(max-width:480px) { .form-grid-2 { grid-template-columns: 1fr; } }

.form-group { margin-bottom: 16px; }
.form-label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 700; color: #111827; }
.form-input, .form-select, .form-textarea {
    width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e5e7eb;
    background: #f9fafb; font-size: 14px; color: #111827;
    outline: none; transition: .2s; font-family: 'Plus Jakarta Sans', sans-serif;
}
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color: #6366f1; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.form-input::placeholder, .form-textarea::placeholder { color: #9ca3af; }
.form-textarea { resize: none; }

/* IMAGE UPLOAD */
.img-upload-box {
    border: 2px dashed #e5e7eb; border-radius: 16px;
    padding: 24px; text-align: center; cursor: pointer;
    background: #f9fafb; position: relative; transition: all .2s;
}
.img-upload-box:hover { border-color: #6366f1; background: #eef2ff; }
.img-upload-box input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; }
.upload-icon { color: #d1d5db; margin-bottom: 10px; }
.upload-text { font-size: 13.5px; font-weight: 600; color: #6b7280; }
.upload-hint { font-size: 11.5px; color: #9ca3af; margin-top: 4px; }
.img-preview { width: 100%; height: 180px; object-fit: cover; border-radius: 12px; display: none; margin-top: 12px; }
.img-preview.show { display: block; }

/* STATUS CARDS */
.status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.status-opt { position: relative; cursor: pointer; }
.status-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.status-card { display: flex; align-items: center; gap: 12px; padding: 13px 15px; border-radius: 13px; border: 2px solid #e5e7eb; background: #f9fafb; transition: all .18s; }
.status-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.status-icon i { width: 16px; height: 16px; }
.status-label { font-size: 13px; font-weight: 700; color: #374151; }
.status-desc  { font-size: 11px; color: #9ca3af; margin-top: 1px; }

.status-opt.s-aktif .status-icon { background: #f0fdf4; }
.status-opt.s-aktif .status-icon i { stroke: #22c55e; }
.status-opt.s-nonaktif .status-icon { background: #fef2f2; }
.status-opt.s-nonaktif .status-icon i { stroke: #ef4444; }

.status-opt.s-aktif input:checked + .status-card { border-color: #22c55e; background: #f0fdf4; }
.status-opt.s-nonaktif input:checked + .status-card { border-color: #ef4444; background: #fef2f2; }

.status-check { margin-left: auto; width: 18px; height: 18px; border-radius: 50%; border: 2px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .18s; }
.status-opt.s-aktif input:checked + .status-card .status-check { border-color: #22c55e; background: #22c55e; }
.status-opt.s-nonaktif input:checked + .status-card .status-check { border-color: #ef4444; background: #ef4444; }
.status-check i { width: 10px; height: 10px; stroke: white; display: none; }
.status-opt input:checked + .status-card .status-check i { display: block; }

/* ADDON GROUP */
.addon-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 13px 15px; border-radius: 13px;
    border: 1.5px solid #f1f5f9; background: #fafafa;
    cursor: pointer; transition: all .2s; margin-bottom: 8px;
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

.alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; border-radius: 12px; padding: 12px 16px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }

.button-group { display: flex; gap: 12px; margin-top: 28px; }
.btn { flex: 1; padding: 13px; border-radius: 14px; border: none; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; transition: all .2s; display: inline-flex; align-items: center; justify-content: center; gap: 7px; font-family: 'Plus Jakarta Sans', sans-serif; }
.btn i { width: 16px; height: 16px; }
.btn-save { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; box-shadow: 0 8px 20px rgba(99,102,241,0.25); }
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 12px 24px rgba(99,102,241,0.3); }
.btn-back { background: white; color: #374151; border: 1.5px solid #e5e7eb; }
.btn-back:hover { background: #f9fafb; }

@media(max-width:480px) { .status-grid { grid-template-columns: 1fr; } .button-group { flex-direction: column; } }
</style>
</head>
<body>
<div class="container">

    <a href="/admin/menu" class="back-link">
    </a>

    <div class="card">
        <div class="title">
            <h2>Tambah Menu</h2>
            <p>Isi data menu baru untuk ditampilkan ke pelanggan</p>
        </div>

        @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="/admin/menu/store" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="divider"><span>Informasi Menu</span></div>

        <div class="form-grid-2" style="margin-bottom:14px;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Nama Menu <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" class="form-input" placeholder="Contoh: Es Teh Manis" required>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Harga <span style="color:#ef4444;">*</span></label>
                <input type="number" name="price" class="form-input" placeholder="15000" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
            <select name="kategori_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-textarea" rows="3" placeholder="Deskripsi singkat menu ini..."></textarea>
        </div>

        <div class="divider"><span>Foto Menu</span></div>

        <div class="form-group">
            <div class="img-upload-box" id="uploadBox">
                <input type="file" name="image" accept=".jpg,.jpeg,.png" onchange="previewImage(event)">
                <div id="uploadPlaceholder">
                    <div class="upload-icon"><i data-lucide="image" style="width:36px;height:36px;"></i></div>
                    <p class="upload-text">Klik untuk upload foto menu</p>
                    <p class="upload-hint">JPG, JPEG, PNG — Maks 2MB</p>
                </div>
                <img id="preview" class="img-preview" alt="Preview">
            </div>
            @error('image')<p style="color:#ef4444;font-size:12px;margin-top:5px;">{{ $message }}</p>@enderror
        </div>

        <div class="divider"><span>Status Menu</span></div>

        <div class="form-group">
            <div class="status-grid">
                <label class="status-opt s-aktif">
                    <input type="radio" name="status" value="1" checked>
                    <div class="status-card">
                        <div class="status-icon"><i data-lucide="circle-check"></i></div>
                        <div>
                            <div class="status-label">Aktif</div>
                            <div class="status-desc">Tampil ke pelanggan</div>
                        </div>
                        <div class="status-check"><i data-lucide="check"></i></div>
                    </div>
                </label>
                <label class="status-opt s-nonaktif">
                    <input type="radio" name="status" value="0">
                    <div class="status-card">
                        <div class="status-icon"><i data-lucide="circle-x"></i></div>
                        <div>
                            <div class="status-label">Nonaktif</div>
                            <div class="status-desc">Disembunyikan</div>
                        </div>
                        <div class="status-check"><i data-lucide="check"></i></div>
                    </div>
                </label>
            </div>
        </div>

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

        <div class="button-group">
            <button type="submit" class="btn btn-save">
                <i data-lucide="save"></i>
                Simpan Menu
            </button>
            <a href="/admin/menu" class="btn btn-back">Batal</a>
        </div>

        </form>
    </div>
</div>

<script>
lucide.createIcons();

function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function() {
        const img = document.getElementById('preview');
        img.src = reader.result;
        img.classList.add('show');
        document.getElementById('uploadPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

function toggleAddon(cb, id) {
    const item = document.getElementById(id);
    item.classList.toggle('selected', cb.checked);
}
</script>
</body>
</html>