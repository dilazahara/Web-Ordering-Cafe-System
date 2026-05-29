@extends('layouts.admin')

@section('title', 'Tambah Add-on')

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #F8F9FC; color: #1e293b; }

.main { padding: 30px 30px 40px; max-width: 700px; margin: 0 auto; }

.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-header h1 { font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
.page-header p  { font-size: 14px; color: #64748b; }

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

.group-row { display: flex; gap: 8px; align-items: stretch; }
.group-row .form-select { flex: 1; }
.btn-add-group {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 11px 15px; border-radius: 11px;
    border: 1.5px solid #8b5cf6; background: #f5f3ff;
    color: #7c3aed; font-size: 13px; font-weight: 600;
    cursor: pointer; white-space: nowrap;
    transition: all 0.18s; font-family: 'Inter', sans-serif;
}
.btn-add-group:hover { background: #ede9fe; border-color: #7c3aed; }

.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);
    z-index: 1100; justify-content: center; align-items: center;
}
.modal-overlay.show { display: flex; }
.modal {
    background: white; padding: 30px; border-radius: 20px;
    width: 400px; max-width: 95vw;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18);
}
.modal-title { font-size: 17px; font-weight: 700; color: #111827; margin-bottom: 22px; }
.modal-actions { display: flex; gap: 10px; margin-top: 22px; }
.btn-modal-cancel {
    flex: 1; padding: 11px; border-radius: 11px;
    border: 1.5px solid #e5e7eb; background: white;
    color: #374151; font-size: 14px; font-weight: 600;
    cursor: pointer; font-family: 'Inter', sans-serif; transition: all 0.15s;
}
.btn-modal-cancel:hover { background: #f3f4f6; }
.btn-modal-save {
    flex: 1; padding: 11px; border-radius: 11px;
    border: none; background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white;
    font-size: 14px; font-weight: 700; cursor: pointer;
    font-family: 'Inter', sans-serif; transition: all 0.18s;
    box-shadow: 0 4px 12px rgba(99,102,241,0.3);
}
.btn-modal-save:hover { transform: translateY(-1px); }
.btn-modal-save:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

.card-footer {
    padding: 18px 24px; background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    display: flex; justify-content: flex-end; gap: 10px; align-items: center;
}
.btn-cancel {
    padding: 11px 22px; border-radius: 11px;
    border: 1.5px solid #e5e7eb; background: white;
    color: #374151; font-size: 14px; font-weight: 600;
    text-decoration: none; transition: all 0.15s; font-family: 'Inter', sans-serif;
}
.btn-cancel:hover { background: #f3f4f6; }
.btn-save {
    padding: 11px 26px; border-radius: 11px;
    border: none; background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white;
    font-size: 14px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 8px;
    transition: all 0.2s; font-family: 'Inter', sans-serif;
    box-shadow: 0 4px 12px rgba(99,102,241,0.3);
}
.btn-save:hover  { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(99,102,241,0.4); }
.btn-save:active { transform: scale(0.97); }

/* TOAST */
#t-wrap { position:fixed; top:24px; right:24px; z-index:99999; display:flex; flex-direction:column; gap:10px; pointer-events:none; }
.t-box { display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:14px; min-width:280px; max-width:390px; font-size:13.5px; font-weight:600; pointer-events:all; box-shadow:0 8px 30px rgba(0,0,0,.13); transform:translateX(120%); transition:transform .35s cubic-bezier(.4,0,.2,1), opacity .35s ease; opacity:0; }
.t-box.show { transform:translateX(0); opacity:1; }
.t-ok  { background:#f0fdf4; border:1.5px solid #86efac; color:#15803d; }
.t-err { background:#fef2f2; border:1.5px solid #fca5a5; color:#dc2626; }
.t-x   { margin-left:auto; background:none; border:none; font-size:18px; cursor:pointer; opacity:.55; color:inherit; padding:0 0 0 6px; line-height:1; }
.t-x:hover { opacity:1; }
</style>
@endpush

@section('content')
<div class="main">

    <div class="page-header">
        <div>
            <h1>Tambah Add-on</h1>
            <p>Buat add-on baru sebagai opsi tambahan untuk menu</p>
        </div>
    </div>

    @if($errors->any())
    <div style="background:#fef2f2;color:#dc2626;border:1px solid #fee2e2;border-radius:14px;padding:13px 17px;margin-bottom:18px;font-size:13px;font-weight:500;">
        @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
    </div>
    @endif

    <form action="/admin/addons/store" method="POST">
    @csrf
    <div class="card">

        <!-- INFORMASI ADD-ON -->
        <div class="card-section">
            <p class="card-section-title">Informasi Add-on</p>
            <div style="display:grid; gap:16px;">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Add-on <span style="color:#EF4444;">*</span></label>
                        <input type="text" name="name" class="form-input"
                            placeholder="Contoh: Extra Keju, Pedas Level 2..." required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga <span style="color:#EF4444;">*</span></label>
                        <input type="number" name="price" class="form-input" placeholder="5000" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-textarea" rows="3"
                        placeholder="Deskripsi singkat add-on ini..."></textarea>
                </div>
            </div>
        </div>

        <!-- GROUP & STATUS -->
        <div class="card-section">
            <p class="card-section-title">Group & Status</p>
            <div style="display:grid; gap:16px;">
                <div class="form-group">
                    <label class="form-label">Group Add-on <span style="color:#EF4444;">*</span></label>
                    <div class="group-row">
                        <select name="addon_group_id" id="selectGroup" class="form-select" required>
                            <option value="">-- Pilih Group --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn-add-group" onclick="bukaModal()">
                            + Buat Group
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Add-on</label>
                    <div class="status-toggle">
                        <div class="status-opt active-status" id="statusAktif" onclick="setStatus(1)">✓ Aktif</div>
                        <div class="status-opt" id="statusNonaktif" onclick="setStatus(0)">✕ Nonaktif</div>
                    </div>
                    <input type="hidden" name="status" id="statusVal" value="1">
                    <p style="font-size:12px; color:#9ca3af; margin-top:10px;">Add-on aktif akan tersedia untuk dipilih pelanggan.</p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="card-footer">
            <a href="/admin/addons" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                💾 Simpan Add-on
            </button>
        </div>
    </div>
    </form>

</div>{{-- /main --}}

<!-- MODAL BUAT GROUP -->
<div class="modal-overlay" id="modalGroup">
    <div class="modal">
        <p class="modal-title">Buat Group Baru</p>
        <div class="form-group" style="margin-bottom:14px;">
            <label class="form-label">Nama Group <span style="color:#EF4444;">*</span></label>
            <input type="text" id="inputNamaGroup" class="form-input" placeholder="Contoh: Ukuran, Topping...">
        </div>
        <div class="form-group" style="margin-bottom:14px;">
            <label class="form-label">Tipe Pilihan</label>
            <select id="inputMax" class="form-select">
                <option value="">Pilih bebas (tidak terbatas)</option>
                <option value="1">Wajib pilih 1</option>
                <option value="3">Maks. 3 pilihan</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Wajib Dipilih?</label>
            <select id="inputRequired" class="form-select">
                <option value="0">Tidak</option>
                <option value="1">Ya</option>
            </select>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="tutupModal()">Batal</button>
            <button type="button" class="btn-modal-save" id="btnSimpanGroup" onclick="simpanGroup()">Simpan Group</button>
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="t-wrap"></div>

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

/* ══ MODAL GROUP ══ */
function bukaModal() {
    document.getElementById('modalGroup').classList.add('show');
}

function tutupModal() {
    document.getElementById('modalGroup').classList.remove('show');
    document.getElementById('inputNamaGroup').value = '';
    document.getElementById('inputMax').value       = '';
    document.getElementById('inputRequired').value  = '0';
    var btn = document.getElementById('btnSimpanGroup');
    btn.disabled    = false;
    btn.textContent = 'Simpan Group';
}

// Tutup modal klik backdrop
document.getElementById('modalGroup').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

function simpanGroup() {
    var nama     = document.getElementById('inputNamaGroup').value.trim();
    var max      = document.getElementById('inputMax').value;
    var required = document.getElementById('inputRequired').value;

    if (!nama) {
        showToast('Nama group tidak boleh kosong!', 'err');
        return;
    }

    // Ambil CSRF token - coba dari meta tag dulu, fallback ke hidden input di form
    var csrfToken = '';
    var metaTag = document.querySelector('meta[name="csrf-token"]');
    var inputToken = document.querySelector('input[name="_token"]');

    if (metaTag) {
        csrfToken = metaTag.getAttribute('content');
    } else if (inputToken) {
        csrfToken = inputToken.value;
    }

    if (!csrfToken) {
        showToast('Token tidak ditemukan, silakan refresh halaman.', 'err');
        return;
    }

    // Disable tombol supaya tidak dobel klik
    var btn = document.getElementById('btnSimpanGroup');
    btn.disabled    = true;
    btn.textContent = 'Menyimpan...';

    fetch('/admin/addon-groups/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            name: nama,
            max_select: max,
            required: required
        })
    })
    .then(function(res) {
        if (!res.ok) {
            return res.text().then(function(text) {
                throw new Error('Server error ' + res.status + ': ' + text);
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
        } else {
            showToast(data.message || 'Gagal membuat group.', 'err');
            btn.disabled    = false;
            btn.textContent = 'Simpan Group';
        }
    })
    .catch(function(err) {
        console.error('Error simpan group:', err);
        showToast('Terjadi kesalahan, coba lagi.', 'err');
        btn.disabled    = false;
        btn.textContent = 'Simpan Group';
    });
}

/* ══ TOAST ══ */
function showToast(msg, type) {
    var wrap = document.getElementById('t-wrap');
    var box  = document.createElement('div');
    box.className = 't-box t-' + type;
    box.innerHTML = msg + '<button class="t-x" onclick="this.parentElement.remove()">×</button>';
    wrap.appendChild(box);
    requestAnimationFrame(function() {
        requestAnimationFrame(function() { box.classList.add('show'); });
    });
    setTimeout(function() {
        box.classList.remove('show');
        setTimeout(function() { if (box.parentElement) box.remove(); }, 400);
    }, 3500);
}
</script>
@endpush