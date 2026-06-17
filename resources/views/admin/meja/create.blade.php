@extends('layouts.admin')

@section('title', 'Tambah Meja')

@push('styles')
<style>
/* ════ CSS KHUSUS HALAMAN CREATE MEJA PREMIUM ════ */
.create-container { max-width: 680px; margin: auto; padding: 20px 0; }

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
.page-header h2 { font-size: 26px; color: var(--text-dark); font-weight: 800; letter-spacing: -0.5px; }
.page-header p  { margin-top: 5px; color: var(--text-light); font-size: 14px; }

/* SECTION CARD */
.form-section-card {
    background: var(--bg-white); 
    padding: 28px;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    border: 1px solid var(--border-light, #f1f5f9);
    margin-bottom: 24px;
    transition: box-shadow 0.3s ease;
}
.form-section-card:hover {
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
.form-group { margin-bottom: 0; }
.form-label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 700; color: var(--text-dark); }
.form-input {
    width: 100%; padding: 13px 16px; 
    border-radius: 12px; border: 1.5px solid var(--border, #cbd5e1);
    background: var(--bg, #f8fafc); font-size: 15px; color: var(--text-dark);
    outline: none; transition: all .2s ease; font-family: var(--font);
}
.form-input:focus { border-color: var(--primary); background: var(--bg-white, #fff); box-shadow: 0 0 0 4px rgba(99,102,241,0.15); }
.form-input::placeholder { color: var(--text-muted); font-weight: 400; }
.form-input.is-invalid { border-color: #ef4444 !important; background: #fef2f2 !important; box-shadow: 0 0 0 4px rgba(239,68,68,0.1) !important; }
.field-error { color: #dc2626; font-size: 13px; font-weight: 600; margin-top: 6px; display: none; }

/* STATUS CARDS */
.status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.status-opt { position: relative; cursor: pointer; display: block; }
.status-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.status-card {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 18px; border-radius: 16px;
    border: 2px solid var(--border, #cbd5e1); background: var(--bg, #f8fafc);
    transition: all .2s ease; cursor: pointer;
}
.status-icon { 
    width: 42px; height: 42px; border-radius: 12px; 
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; 
}
.status-icon svg { width: 22px; height: 22px; stroke-width: 2; }
.status-label { font-size: 14px; font-weight: 800; color: var(--text-mid); }
.status-desc  { font-size: 12px; color: var(--text-muted); margin-top: 2px; font-weight: 500; }

.status-opt.s-kosong .status-icon { background: #f0fdf4; }
.status-opt.s-kosong .status-icon svg { stroke: #16a34a; }
.status-opt.s-terisi .status-icon { background: #fef2f2; }
.status-opt.s-terisi .status-icon svg { stroke: #dc2626; }

.status-opt.s-kosong input:checked + .status-card { border-color: #22c55e; background: #f0fdf4; box-shadow: 0 4px 12px rgba(34,197,94,0.1); }
.status-opt.s-terisi input:checked + .status-card { border-color: #ef4444; background: #fef2f2; box-shadow: 0 4px 12px rgba(239,68,68,0.1); }

.status-check {
    margin-left: auto; width: 20px; height: 20px;
    border-radius: 50%; border: 2px solid var(--border, #cbd5e1);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .2s;
}
.status-opt.s-kosong input:checked + .status-card .status-check { border-color: #16a34a; background: #16a34a; }
.status-opt.s-terisi input:checked + .status-card .status-check { border-color: #dc2626; background: #dc2626; }
.status-check svg { width: 12px; height: 12px; stroke: white; display: none; stroke-width: 3; }
.status-opt input:checked + .status-card .status-check svg { display: block; }

/* QR PREVIEW WRAPPER */
.qr-preview-wrap { 
    background: #f8fafc; border: 1.5px solid #e2e8f0; 
    border-radius: 16px; padding: 24px; text-align: center; 
}
.qr-preview-label {
    display: inline-flex; align-items: center; gap: 8px;
    background: #eef2ff; color: #4f46e5;
    border: 1.5px solid #c7d2fe; border-radius: 30px;
    padding: 6px 16px; font-size: 13px; font-weight: 800; margin-bottom: 20px;
}
.qr-preview-label svg { width: 16px; height: 16px; stroke-width: 2.5; }
.qr-frame {
    background: white; border: 1.5px dashed #cbd5e1; border-radius: 16px;
    padding: 20px; display: inline-flex; align-items: center; justify-content: center;
    min-height: 180px; min-width: 180px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}
.qr-empty { display: flex; flex-direction: column; align-items: center; gap: 10px; color: #94a3b8; font-size: 13px; font-weight: 600; }
.qr-empty svg { width: 48px; height: 48px; opacity: .4; }
.qr-url-text { 
    font-size: 12px; color: #64748b; word-break: break-all; 
    background: white; border: 1px solid #e2e8f0; border-radius: 10px; 
    padding: 10px 14px; margin-top: 16px; font-family: monospace; 
    line-height: 1.5; text-align: left; font-weight: 600;
}
.qr-hint { font-size: 12px; color: #94a3b8; margin-top: 12px; font-weight: 500; }

/* ALERTS */
.alert-error { 
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; 
    border-radius: 14px; padding: 14px 18px; font-size: 14px; 
    font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
}
.alert-error svg { width: 20px; height: 20px; flex-shrink: 0; }

/* ACTION CARD */
.action-card {
    background: white;
    padding: 20px 28px;
    border-radius: 20px;
    border: 1px solid var(--border-light, #f1f5f9);
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
.button-group { display: flex; gap: 14px; width: 100%; max-width: 380px; margin-left: auto; }
.btn {
    flex: 1; padding: 14px; border-radius: 14px; border: none;
    font-size: 15px; font-weight: 700; cursor: pointer;
    text-decoration: none; text-align: center; transition: all .2s;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    font-family: var(--font);
}
.btn svg { width: 18px; height: 18px; stroke-width: 2.5; }
.btn-save { background: linear-gradient(135deg, var(--primary), var(--primary-hover, #4f46e5)); color: white; box-shadow: 0 8px 20px rgba(99,102,241,0.25); }
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(99,102,241,0.35); }
.btn-back { background: white; color: var(--text-mid); border: 1.5px solid var(--border, #cbd5e1); }
.btn-back:hover { background: #f8fafc; border-color: #94a3b8; }

@media(max-width:480px) { 
    .status-grid { grid-template-columns: 1fr; } 
    .action-card { padding: 20px; }
    .button-group { flex-direction: column; max-width: 100%; } 
}
</style>
@endpush

@section('content')
<div class="create-container">

    <div class="page-header">
        <h2>Tambah Meja Baru</h2>
        <p>Daftarkan meja baru untuk digunakan oleh pelanggan saat memesan.</p>
    </div>

    @if($errors->any())
    <div class="alert-error">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        {{ $errors->first() }}
    </div>
    @endif

    <form action="/admin/meja/store" method="POST" id="mejaForm" novalidate>
        @csrf

        <div class="form-section-card">
            <div class="section-header">
                <div class="section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </div>
                <div class="section-title">
                    <h3>Informasi Meja</h3>
                    <p>Tentukan identitas dan penomoran meja</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor / Nama Meja <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nomor_meja" id="nomorMejaInput"
                    class="form-input {{ $errors->has('nomor_meja') ? 'is-invalid' : '' }}"
                    placeholder="Contoh: A1, B2, VIP-01..."
                    value="{{ old('nomor_meja') }}"
                    oninput="updateQRPreview()">
                
                @error('nomor_meja')
                    <p class="field-error" style="display:block;">{{ $message }}</p>
                @enderror
                <p class="field-error" id="errorNomorMeja"></p>
                <p style="font-size:12.5px; color:var(--text-muted); margin-top:8px; font-weight: 500;">
                    QR code akan otomatis diperbarui setiap kali nomor meja diisi/diubah.
                </p>
            </div>
        </div>

        <div class="form-section-card">
            <div class="section-header">
                <div class="section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <div class="section-title">
                    <h3>Status Awal Meja</h3>
                    <p>Kondisi meja saat pertama kali ditambahkan</p>
                </div>
            </div>

            <div class="form-group">
                <div class="status-grid">
                    <label class="status-opt s-kosong">
                        <input type="radio" name="status" value="kosong" checked>
                        <div class="status-card">
                            <div class="status-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                            </div>
                            <div>
                                <div class="status-label">Kosong</div>
                                <div class="status-desc">Siap digunakan</div>
                            </div>
                            <div class="status-check">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                    <label class="status-opt s-terisi">
                        <input type="radio" name="status" value="terisi">
                        <div class="status-card">
                            <div class="status-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </div>
                            <div>
                                <div class="status-label">Terisi</div>
                                <div class="status-desc">Sedang dipakai</div>
                            </div>
                            <div class="status-check">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-section-card">
            <div class="section-header">
                <div class="section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <rect x="7" y="7" width="3" height="3"></rect>
                        <rect x="14" y="7" width="3" height="3"></rect>
                        <rect x="7" y="14" width="3" height="3"></rect>
                        <rect x="14" y="14" width="3" height="3"></rect>
                    </svg>
                </div>
                <div class="section-title">
                    <h3>Preview QR Code</h3>
                    <p>Hasil generate otomatis berdasarkan nomor meja</p>
                </div>
            </div>

            <div class="qr-preview-wrap">
                <div class="qr-preview-label">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3"/>
                        <path d="M2 11v5a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v2H6v-2a2 2 0 0 0-4 0z"/>
                    </svg>
                    <span id="qrLabelText">Meja —</span>
                </div>
                <div class="qr-frame">
                    <div id="qrPreview">
                        <div class="qr-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                                <rect x="3" y="14" width="7" height="7"/>
                                <rect x="14" y="14" width="1" height="1"/><rect x="17" y="14" width="1" height="1"/>
                                <rect x="20" y="14" width="1" height="1"/><rect x="14" y="17" width="1" height="1"/>
                                <rect x="17" y="17" width="1" height="1"/><rect x="20" y="17" width="1" height="1"/>
                                <rect x="14" y="20" width="1" height="1"/><rect x="17" y="20" width="1" height="1"/>
                                <rect x="20" y="20" width="1" height="1"/>
                            </svg>
                            <span>Isi nomor meja untuk melihat QR</span>
                        </div>
                    </div>
                </div>
                <div class="qr-url-text" id="qrUrlDisplay">—</div>
                <p class="qr-hint">Gambar QR ini nantinya akan dicetak dan ditempel di meja pelanggan.</p>
            </div>
        </div>

        <div class="action-card">
            <div class="button-group">
                <a href="/admin/meja" class="btn btn-back">Batal</a>
                <button type="submit" class="btn btn-save">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Meja
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
var qrDebounce = null;

function updateQRPreview() {
    clearTimeout(qrDebounce);
    qrDebounce = setTimeout(function() {
        var nomor = document.getElementById('nomorMejaInput').value.trim();
        var label = document.getElementById('qrLabelText');
        var urlEl = document.getElementById('qrUrlDisplay');
        var wrap  = document.getElementById('qrPreview');

        if (!nomor) {
            label.textContent = 'Meja —';
            urlEl.textContent = '—';
            wrap.innerHTML =
                '<div class="qr-empty">' +
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">' +
                '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>' +
                '</svg>' +
                '<span>Isi nomor meja untuk melihat QR</span>' +
                '</div>';
            return;
        }

        var appUrl = '{{ rtrim(config("app.url"), "/") }}';
        var url = appUrl + '/customer/scan/' + encodeURIComponent(nomor);

        label.textContent = 'Meja ' + nomor;
        urlEl.textContent = url;
        wrap.innerHTML = '';
        new QRCode(wrap, {
            text: url,
            width: 160, height: 160,
            colorDark: '#0f172a', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }, 300);
}

// Jika ada old value (setelah validation error)
var oldVal = document.getElementById('nomorMejaInput').value;
if (oldVal) updateQRPreview();

// ── VALIDASI SUBMIT ──
document.getElementById('mejaForm').addEventListener('submit', function(e) {
    const input = document.getElementById('nomorMejaInput');
    const error = document.getElementById('errorNomorMeja');
    input.classList.remove('is-invalid');
    error.style.display = 'none';

    if (!input.value.trim()) {
        e.preventDefault();
        input.classList.add('is-invalid');
        error.textContent   = 'Nomor meja wajib diisi.';
        error.style.display = 'block';
        input.focus();
    }
});

document.getElementById('nomorMejaInput').addEventListener('input', function() {
    if (this.value.trim()) {
        this.classList.remove('is-invalid');
        document.getElementById('errorNomorMeja').style.display = 'none';
    }
});
</script>
@endpush