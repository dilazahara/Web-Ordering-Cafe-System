@extends('layouts.admin')

@section('title', 'Tambah Add-on')

@push('styles')
<style>
/* ── LAYOUT & CARDS ── */
.form-page-wrap { max-width: 700px; margin: 0 auto; padding-bottom: 40px; }
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
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 700; color: #374151; }
.form-input, .form-select, .form-textarea {
    width: 100%; padding: 12px 16px; border-radius: var(--radius-lg, 12px); 
    border: 1.5px solid var(--border, #d1d5db); background: #fafafa; 
    font-size: var(--text-md, 15px); color: var(--text-dark, #1f2937); font-family: var(--font);
    outline: none; transition: all .2s ease;
}
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color: #6366f1; background: white; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
.form-input::placeholder, .form-textarea::placeholder { color: #9ca3af; }
.form-textarea { resize: vertical; min-height: 100px; }

/* ── VALIDASI ── */
.form-input.is-invalid, .form-select.is-invalid { border-color: #ef4444 !important; background: #fff5f5 !important; box-shadow: 0 0 0 4px rgba(239,68,68,0.1) !important; }
.field-error { color: #ef4444; font-size: 13px; margin-top: 6px; font-weight: 500; display: none; align-items: center; gap: 5px; }
.field-error.show { display: flex; }
.field-error::before { content: '⚠'; font-size: 12px; }

/* ── GROUP ADD-ON ROW ── */
.group-row { display: flex; gap: 10px; align-items: center; }
.group-row .form-select { flex: 1; margin: 0; }
.btn-group-controls { display: flex; gap: 8px; }
.btn-action-sm {
    display: inline-flex; align-items: center; justify-content: center;
    width: 45px; height: 45px; border-radius: 12px;
    border: 1.5px solid #d1d5db; background: white;
    color: #4b5563; cursor: pointer; transition: all 0.2s;
}
.btn-action-sm:hover:not(:disabled) { background: #f3f4f6; border-color: #9ca3af; }
.btn-action-sm:disabled { opacity: 0.5; cursor: not-allowed; background: #f9fafb; }
.btn-add-group {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0 16px; height: 45px; border-radius: 12px;
    border: 1.5px dashed #4f46e5; background: #eef2ff;
    color: #4f46e5; font-size: 14px; font-weight: 700;
    cursor: pointer; white-space: nowrap; transition: all 0.2s; font-family: var(--font);
}
.btn-add-group:hover { background: #e0e7ff; color: #4338ca; border-style: solid; }
.btn-add-group svg { width: 18px; height: 18px; }

/* ── STATUS TOGGLE (Segmented Control Style) ── */
.status-toggle { display: flex; gap: 12px; }
.status-opt {
    flex: 1; padding: 12px; border-radius: 12px; text-align: center;
    border: 1.5px solid #d1d5db; cursor: pointer; font-size: 14px;
    font-weight: 700; color: #6b7280; transition: all 0.2s; user-select: none;
    display: flex; align-items: center; justify-content: center; gap: 8px; background: #fafafa;
}
.status-opt svg { width: 18px; height: 18px; stroke-width: 2.5; }
.status-opt.active-status { border-color: #22c55e; color: #16a34a; background: #f0fdf4; box-shadow: 0 2px 10px rgba(34,197,94,0.1); }
.status-opt.inactive-status { border-color: #ef4444; color: #dc2626; background: #fef2f2; box-shadow: 0 2px 10px rgba(239,68,68,0.1); }

/* ── BUTTONS ── */
.btn-group { display: flex; gap: 16px; margin-top: 8px; }
.btn-save {
    flex: 1; padding: 14px; border-radius: var(--radius-xl, 14px); border: none;
    font-size: 16px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    font-family: var(--font); background: #4f46e5; color: white;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
}
.btn-save:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(79,70,229,.35); }
.btn-save:active { transform: scale(0.98); }
.btn-save svg { width: 18px; height: 18px; stroke: white; fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
.btn-cancel {
    flex: 1; padding: 14px; border-radius: var(--radius-xl, 14px);
    border: 1.5px solid #d1d5db; font-size: 16px; font-weight: 700;
    background: white; color: #4b5563; cursor: pointer; font-family: var(--font);
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    text-decoration: none; transition: all .2s;
}
.btn-cancel:hover { background: #f3f4f6; color: #1f2937; border-color: #9ca3af; }

/* ── MODAL ── */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(17,24,39,0.6); backdrop-filter: blur(4px); z-index: 1100; justify-content: center; align-items: center; padding: 20px; }
.modal-overlay.show { display: flex; animation: fadeIn 0.2s ease; }
.modal { background: white; padding: 32px; border-radius: 20px; width: 440px; max-width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.15); transform: scale(0.95); transition: transform 0.2s; }
.modal-overlay.show .modal { transform: scale(1); }
.modal-title { font-size: 20px; font-weight: 800; color: #111827; margin: 0 0 24px; display: flex; align-items: center; gap: 10px; }
.modal-actions { display: flex; gap: 12px; margin-top: 28px; }
.btn-modal-cancel { flex: 1; padding: 12px; border-radius: 12px; border: 1.5px solid #d1d5db; background: white; color: #4b5563; font-weight: 700; cursor: pointer; transition: all 0.2s; }
.btn-modal-cancel:hover { background: #f3f4f6; }
.btn-modal-save { flex: 1; padding: 12px; border-radius: 12px; border: none; background: #4f46e5; color: white; font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px; }
.btn-modal-save:hover { background: #4338ca; }
.btn-modal-save:disabled { opacity: 0.6; cursor: not-allowed; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

/* ── TOAST ── */
#t-wrap { position:fixed; top:24px; right:24px; z-index:99999; display:flex; flex-direction:column; gap:10px; pointer-events:none; }
.t-box { display:flex; align-items:center; gap:12px; padding:16px 20px; border-radius:14px; min-width:280px; max-width:390px; font-size:14px; font-weight:600; pointer-events:all; box-shadow:0 8px 30px rgba(0,0,0,.13); transform:translateX(120%); transition:transform .35s cubic-bezier(.4,0,.2,1), opacity .35s ease; opacity:0; }
.t-box.show { transform:translateX(0); opacity:1; }
.t-ok  { background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; }
.t-err { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }
.t-x   { margin-left:auto; background:none; border:none; font-size:20px; cursor:pointer; opacity:.6; color:inherit; padding:0; line-height:1; }
.t-x:hover { opacity:1; }

@media(max-width:640px) {
    .form-grid-2 { grid-template-columns: 1fr; }
    .btn-group { flex-direction: column; }
    .group-row { flex-direction: column; align-items: stretch; }
    .btn-group-controls { justify-content: stretch; }
    .section-card { padding: 20px; }
}
</style>
@endpush

@section('content')
<div class="form-page-wrap">

    <div class="page-header">
        <div class="page-title">
            <h1>Tambah Add-on</h1>
            <p>Buat add-on baru sebagai opsi tambahan untuk menu Anda.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error" style="background:#fef2f2; color:#dc2626; border:1px solid #fee2e2; border-radius:14px; padding:16px 20px; margin-bottom:24px; box-shadow: 0 2px 10px rgba(220,38,38,0.05);">
        <div style="font-weight:700; margin-bottom:6px; display:flex; align-items:center; gap:8px;">
            <i data-lucide="alert-triangle" style="width:18px; height:18px;"></i>
            Terdapat kesalahan input:
        </div>
        <ul style="margin:0; padding-left:24px; font-size:14px; line-height:1.6;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="/admin/addons/store" method="POST" id="formCreateAddon" novalidate>
        @csrf
        
        <div class="form-layout">
            
            {{-- ── CARD 1: INFORMASI ADD-ON ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="plus-square"></i></div>
                    <div class="section-title-wrap">
                        <h2>Informasi Add-on</h2>
                        <p>Lengkapi nama dan harga varian menu</p>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Add-on <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" id="fieldName" class="form-input"
                            placeholder="Contoh: Extra Keju, Pedas Level 2..." required>
                        <span class="field-error" id="errName"></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga (Rp) <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="price" id="fieldPrice" class="form-input" placeholder="Contoh: 5000" min="0" required>
                        <span class="field-error" id="errPrice"></span>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-textarea" rows="3"
                        placeholder="Deskripsi singkat mengenai add-on ini (opsional)..."></textarea>
                </div>
            </div>

            {{-- ── CARD 2: GROUP & STATUS ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="layers"></i></div>
                    <div class="section-title-wrap">
                        <h2>Group & Status</h2>
                        <p>Kelompokkan add-on dan atur ketersediaannya</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Group Add-on <span style="color:#ef4444;">*</span></label>
                    <div class="group-row">
                        <select name="addon_group_id" id="selectGroup" class="form-select" required>
                            <option value="">-- Pilih Group --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        <div class="btn-group-controls">
                            <a href="#" id="btnEditGroup" class="btn-action-sm" title="Edit Group" style="text-decoration:none;" onclick="event.preventDefault();">
                                <i data-lucide="edit-3" style="width:18px;height:18px;"></i>
                            </a>
                            <button
    type="button"
    id="btnDeleteGroup"
    class="btn-action-sm"
    title="Hapus Group"
    onclick="openDeleteModal()">
    <i data-lucide="trash-2"
       style="width:18px;height:18px;"></i>
</button>
            
                            <button type="button" class="btn-add-group" onclick="bukaModal()">
                                <i data-lucide="folder-plus"></i> Buat
                            </button>
                        </div>
                    </div>
                    <span class="field-error" id="errGroup"></span>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Status Add-on</label>
                    <div class="status-toggle">
                        <div class="status-opt active-status" id="statusAktif" onclick="setStatus(1)">
                            <i data-lucide="check-circle-2"></i> Aktif
                        </div>
                        <div class="status-opt" id="statusNonaktif" onclick="setStatus(0)">
                            <i data-lucide="x-circle"></i> Nonaktif
                        </div>
                    </div>
                    <input type="hidden" name="status" id="statusVal" value="1">
                    <p style="font-size:13px; color:var(--text-muted); margin-top:8px;">Add-on aktif akan langsung tersedia dan bisa dipilih pelanggan.</p>
                </div>
            </div>

            {{-- ── TOMBOL AKSI ── --}}
            <div class="btn-group">
                <a href="/admin/addons" class="btn-cancel">
                    <i data-lucide="x" style="width:18px;height:18px;"></i>
                    Batal Kembali
                </a>
                <button type="submit" class="btn-save">
                    <i data-lucide="save"></i>
                    Simpan Add-on
                </button>
            </div>

        </div>
    </form>
</div>

{{-- ── FORM HAPUS TERSEMBUNYI ── --}}
<form id="formDeleteGroup" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- ── MODAL BUAT GROUP ── --}}
{{-- ── MODAL BUAT / EDIT GROUP ── --}}
<div class="modal-overlay" id="modalGroup">
    <div class="modal">
        <h3 class="modal-title">
            <i data-lucide="folder-plus" style="color:#4f46e5;"></i>
            Buat Group Baru
        </h3>

        <div class="form-group">
            <label class="form-label">
                Nama Group <span style="color:#ef4444;">*</span>
            </label>

            <input
                type="text"
                id="inputNamaGroup"
                class="form-input"
                placeholder="Contoh: Ukuran Gelas, Topping...">
        </div>

        <div class="form-group">
            <label class="form-label">Tipe Pilihan</label>

            <select id="inputMax" class="form-select">
                <option value="">
                    Pilihan bebas (tidak terbatas)
                </option>
                <option value="1">
                    Wajib pilih 1 (Radio)
                </option>
                <option value="3">
                    Maksimal 3 pilihan
                </option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Wajib Dipilih?</label>

            <select id="inputRequired" class="form-select">
                <option value="0">
                    Tidak Wajib
                </option>
                <option value="1">
                    Ya, Wajib Dipilih
                </option>
            </select>
        </div>

        <div class="modal-actions">
            <button
                type="button"
                class="btn-modal-cancel"
                onclick="tutupModal()">
                Batal
            </button>

            <button
                type="button"
                class="btn-modal-save"
                id="btnSimpanGroup"
                onclick="simpanGroup()">

                <i data-lucide="save"
                   style="width:16px;height:16px;"></i>

                Simpan Group
            </button>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal" style="max-width:430px;">
        <h3 class="modal-title">
            <i data-lucide="trash-2" style="color:#ef4444;"></i>
            Hapus Group
        </h3>

        <p style="
            color:#6b7280;
            font-size:14px;
            line-height:1.7;
            margin-bottom:24px;
        ">
            Apakah Anda yakin ingin menghapus group ini?<br>
            Semua data add-on dalam group ini mungkin terpengaruh.
        </p>

        <div class="modal-actions">
            <button
                type="button"
                class="btn-modal-cancel"
                onclick="closeDeleteModal()">
                Batal
            </button>

            <button
                type="button"
                class="btn-modal-save"
                style="background:#ef4444;"
                onclick="deleteGroup()">
                <i data-lucide="trash-2"
                   style="width:16px;height:16px;"></i>
                Hapus
            </button>
        </div>
    </div>
</div>

<div id="t-wrap"></div>
@endsection

@push('scripts')
<script>
// ==========================
// STATE EDIT GROUP
// ==========================
let editingGroupId = null;

// Logic Form Add-on
document.getElementById('formCreateAddon').addEventListener('submit', function(e) {
    var valid = true;
    var name  = document.getElementById('fieldName');
    var price = document.getElementById('fieldPrice');
    var group = document.getElementById('selectGroup');

    [name, price, group].forEach(function(el) { el.classList.remove('is-invalid'); });
    ['errName','errPrice','errGroup'].forEach(function(id) {
        var el = document.getElementById(id); el.textContent = ''; el.classList.remove('show');
    });

    if (!name.value.trim()) {
        name.classList.add('is-invalid');
        showFieldError('errName', 'Nama add-on wajib diisi.'); valid = false;
    }
    if (price.value === '' || Number(price.value) < 0) {
        price.classList.add('is-invalid');
        showFieldError('errPrice', 'Harga wajib diisi dan tidak boleh negatif.'); valid = false;
    }
    if (!group.value) {
        group.classList.add('is-invalid');
        showFieldError('errGroup', 'Group add-on wajib dipilih.'); valid = false;
    }
    if (!valid) e.preventDefault();
});

// Logic Pengelolaan Group
const selectGroup = document.getElementById('selectGroup');
const btnEdit = document.getElementById('btnEditGroup');
const btnDelete = document.getElementById('btnDeleteGroup');

function updateGroupControls() {
    const val = selectGroup.value;
    if (val) {
        btnEdit.disabled = false;
        btnEdit.onclick = function (e) {
    e.preventDefault();

    const id = selectGroup.value;

    if (!id) return;

    editingGroupId = id;

    fetch('/admin/addon-groups/' + id + '/edit')
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            document.getElementById('inputNamaGroup').value =
                data.group.name ?? '';

            document.getElementById('inputMax').value =
                data.group.max ?? '';

            document.getElementById('inputRequired').value =
                data.group.required ?? 0;

            document.querySelector('.modal-title').innerHTML =
    '<i data-lucide="edit-3" style="color:#4f46e5;"></i> Edit Group';

document.getElementById('btnSimpanGroup').innerHTML =
    '<i data-lucide="save" style="width:16px;height:16px;"></i> Update Group';

lucide.createIcons();

            bukaModal();
        })
        .catch(err => {
            console.error(err);
            showToast('Gagal mengambil data group.', 'err');
        });
};
        btnDelete.disabled = false;
    } else {
        btnEdit.disabled = true;
        btnEdit.removeAttribute('href');
        btnDelete.disabled = true;
    }
}

selectGroup.addEventListener('change', updateGroupControls);
updateGroupControls(); // Initial state

function openDeleteModal() {
    if (!selectGroup.value) return;

    document
        .getElementById('deleteModal')
        .classList.add('show');

    lucide.createIcons();
}

function closeDeleteModal() {
    document
        .getElementById('deleteModal')
        .classList.remove('show');
}

function deleteGroup() {
    const form =
        document.getElementById('formDeleteGroup');

    form.action =
        "{{ route('admin.addon-groups.destroy', ':id') }}"
            .replace(':id', selectGroup.value);

    form.submit();
}

document
    .getElementById('deleteModal')
    .addEventListener('click', function (e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

function showFieldError(id, msg) {
    var el = document.getElementById(id); el.textContent = msg; el.classList.add('show');
}

document.getElementById('fieldName').addEventListener('input', function() {
    this.classList.remove('is-invalid'); document.getElementById('errName').classList.remove('show');
});
document.getElementById('fieldPrice').addEventListener('input', function() {
    this.classList.remove('is-invalid'); document.getElementById('errPrice').classList.remove('show');
});
document.getElementById('selectGroup').addEventListener('change', function() {
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

function bukaModal() { document.getElementById('modalGroup').classList.add('show'); }
function tutupModal() {
    editingGroupId = null;

    document.getElementById('modalGroup').classList.remove('show');
    document.getElementById('inputNamaGroup').value = '';
    document.getElementById('inputMax').value = '';
    document.getElementById('inputRequired').value = '0';

    document.querySelector('.modal-title').innerHTML =
        '<i data-lucide="folder-plus" style="color:#4f46e5;"></i> Buat Group Baru';

    document.getElementById('btnSimpanGroup').innerHTML =
        '<i data-lucide="save" style="width:16px;height:16px;"></i> Simpan Group';

    lucide.createIcons();
}

function simpanGroup() {
    var nama     = document.getElementById('inputNamaGroup').value.trim();
    var max      = document.getElementById('inputMax').value;
    var required = document.getElementById('inputRequired').value;

    if (!nama) { showToast('Nama group tidak boleh kosong!', 'err'); return; }

    var csrfToken = '';
    var metaTag = document.querySelector('meta[name="csrf-token"]');
    var inputToken = document.querySelector('input[name="_token"]');

    if (metaTag) { csrfToken = metaTag.getAttribute('content'); } 
    else if (inputToken) { csrfToken = inputToken.value; }

    if (!csrfToken) { showToast('Token tidak ditemukan, silakan refresh halaman.', 'err'); return; }

    var btn = document.getElementById('btnSimpanGroup');
    btn.disabled    = true;
    btn.innerHTML   = '<i data-lucide="loader" style="width:16px;height:16px;"></i> Menyimpan...';
    lucide.createIcons();

    const url = editingGroupId
    ? '/admin/addon-groups/' + editingGroupId
    : '/admin/addon-groups/store';

  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
        _method: editingGroupId ? 'PUT' : 'POST',
        name: nama,
        max: max,
        required: required
    })
})
.then(function(res) {
    if (!res.ok) {
        return res.text().then(function(text) {
            throw new Error(text);
        });
    }
    return res.json();
})
    .then(function(data) {
        if (data.success) {
            var select = document.getElementById('selectGroup');
            var option = document.createElement('option');
            option.value       = data.group.id;
            option.textContent = data.group.name;
            option.selected    = true;
            select.appendChild(option);
            tutupModal();
            showToast('Group "' + nama + '" berhasil dibuat!', 'ok');
            updateGroupControls();
            select.classList.remove('is-invalid'); 
            document.getElementById('errGroup').classList.remove('show');
        } else {
            showToast(data.message || 'Gagal membuat group.', 'err');
            btn.disabled    = false;
            btn.innerHTML   = '<i data-lucide="save" style="width:16px;height:16px;"></i> Simpan Group';
            lucide.createIcons();
        }
    })
    .catch(function(err) {
    console.error('Error simpan group:', err);
    alert(err.message);

    btn.disabled = false;
    btn.innerHTML =
        '<i data-lucide="save" style="width:16px;height:16px;"></i> Simpan Group';
    lucide.createIcons();
});
}

function showToast(msg, type) {
    var wrap = document.getElementById('t-wrap');
    var box  = document.createElement('div');
    var icon = type === 'ok' ? '<i data-lucide="check-circle-2" style="color:#16a34a"></i>' : '<i data-lucide="alert-circle" style="color:#dc2626"></i>';
    box.className = 't-box t-' + type;
    box.innerHTML = icon + ' <span>' + msg + '</span><button class="t-x" onclick="this.parentElement.remove()">&times;</button>';
    wrap.appendChild(box);
    lucide.createIcons();
    
    requestAnimationFrame(function() {
        requestAnimationFrame(function() { box.classList.add('show'); });
    });
    setTimeout(function() {
        box.classList.remove('show');
        setTimeout(function() { if (box.parentElement) box.remove(); }, 400);
    }, 3500);
}

// Inisialisasi ikon lucide
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush