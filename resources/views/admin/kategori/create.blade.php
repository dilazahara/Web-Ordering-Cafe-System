@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@push('styles')
<style>
/* ── LAYOUT & CARDS ── */
.form-page-wrap { max-width: 560px; margin: 0 auto; padding-bottom: 40px; }
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
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 700; color: #374151; }
.form-input {
    width: 100%; padding: 12px 16px; border-radius: var(--radius-lg, 12px); 
    border: 1.5px solid var(--border, #d1d5db); background: #fafafa; 
    font-size: var(--text-md, 15px); color: var(--text-dark, #1f2937); font-family: var(--font);
    outline: none; transition: all .2s ease;
}
.form-input:focus { border-color: #6366f1; background: white; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
.form-input::placeholder { color: #9ca3af; }

/* ── INPUT ERROR STATE ── */
.form-input.is-invalid {
    border-color: #ef4444 !important; background: #fff5f5 !important;
    box-shadow: 0 0 0 4px rgba(239,68,68,0.1) !important;
}
.field-error {
    color: #ef4444; font-size: 13px; margin-top: 6px; font-weight: 500;
    display: flex; align-items: center; gap: 5px;
}
.field-error::before { content: '⚠'; font-size: 12px; }

/* ── TIPS BOX ── */
.tips-box { 
    background: #f8fafc; border: 1px dashed #cbd5e1; 
    border-radius: var(--radius-lg, 12px); padding: 16px 18px; 
}
.tips-title { font-size: 14px; font-weight: 700; color: #4f46e5; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
.tips-title svg { width: 18px; height: 18px; }
.tips-list { list-style: none; display: flex; flex-direction: column; gap: 8px; padding-left: 0; margin: 0; }
.tips-list li { font-size: 13.5px; color: #475569; display: flex; align-items: center; gap: 8px; line-height: 1.4; }
.tips-list li::before { content: ''; width: 6px; height: 6px; background: #a5b4fc; border-radius: 50%; flex-shrink: 0; }

/* ── BUTTONS ── */
.btn-group { display: flex; gap: 16px; margin-top: 8px; }
.btn-save {
    flex: 1; padding: 14px; border-radius: var(--radius-xl, 14px); border: none;
    font-size: 16px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    font-family: var(--font);
    background: #4f46e5;
    color: white; box-shadow: 0 4px 12px rgba(79,70,229,.25);
    transition: all .2s;
}
.btn-save:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(79,70,229,.35); }
.btn-save svg { width: 18px; height: 18px; stroke: white; fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
.btn-back {
    flex: 1; padding: 14px; border-radius: var(--radius-xl, 14px);
    border: 1.5px solid #d1d5db; font-size: 16px; font-weight: 700;
    background: white; color: #4b5563; cursor: pointer; font-family: var(--font);
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    text-decoration: none; transition: all .2s;
}
.btn-back:hover { background: #f3f4f6; color: #1f2937; border-color: #9ca3af; }

@media(max-width:480px) {
    .btn-group { flex-direction: column; }
    .section-card { padding: 20px; }
}
</style>
@endpush

@section('content')
<div class="form-page-wrap">

    <div class="page-header">
        <div class="page-title">
            <h1>Tambah Kategori</h1>
            <p>Buat kategori baru untuk mengelompokkan menu Anda.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error" style="background:#fef2f2; color:#dc2626; border:1px solid #fee2e2; border-radius:14px; padding:16px 20px; margin-bottom:24px; box-shadow: 0 2px 10px rgba(220,38,38,0.05);">
        <div style="font-weight:700; margin-bottom:6px; display:flex; align-items:center; gap:8px;">
            <i data-lucide="alert-triangle" style="width:18px; height:18px;"></i>
            Terdapat kesalahan input:
        </div>
        <div style="margin:0; font-size:14px; line-height:1.6; padding-left:26px;">
            {{ $errors->first() }}
        </div>
    </div>
    @endif

    <form action="/admin/kategori/store" method="POST" id="kategoriForm" novalidate>
        @csrf

        <div class="form-layout">
            
            {{-- ── CARD: INFORMASI KATEGORI ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon"><i data-lucide="tags"></i></div>
                    <div class="section-title-wrap">
                        <h2>Informasi Kategori</h2>
                        <p>Masukkan identitas kategori menu</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Kategori <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" id="fieldName" class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        placeholder="Contoh: Minuman, Makanan Utama, Snack..."
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="field-error" style="display:flex;">{{ $message }}</p>
                    @enderror
                    <p class="field-error" id="errorName" style="display:none;"></p>
                </div>

                <div class="tips-box">
                    <div class="tips-title">
                        <i data-lucide="lightbulb"></i>
                        Tips Penamaan Kategori
                    </div>
                    <ul class="tips-list">
                        <li>Gunakan nama yang singkat, padat, dan jelas</li>
                        <li>Contoh yang baik: Minuman, Makanan Utama, Dessert</li>
                        <li>Kategori ini akan langsung tampil di halaman menu pelanggan</li>
                    </ul>
                </div>
            </div>

            {{-- ── TOMBOL AKSI ── --}}
            <div class="btn-group">
                <a href="/admin/kategori" class="btn-back">
                    <i data-lucide="x" style="width:18px;height:18px;"></i>
                    Batal Kembali
                </a>
                <button type="submit" class="btn-save">
                    <i data-lucide="save"></i>
                    Simpan Kategori
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('kategoriForm').addEventListener('submit', function(e) {
        const name  = document.getElementById('fieldName');
        const error = document.getElementById('errorName');
        name.classList.remove('is-invalid');
        error.style.display = 'none';

        if (!name.value.trim()) {
            e.preventDefault();
            name.classList.add('is-invalid');
            error.textContent   = 'Nama kategori wajib diisi.';
            error.style.display = 'flex';
            name.focus();
        }
    });

    document.getElementById('fieldName').addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
            document.getElementById('errorName').style.display = 'none';
        }
    });

    // Inisialisasi icon lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endpush