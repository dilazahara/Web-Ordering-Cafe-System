<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Meja</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
body { background: #f3f4f6; min-height: 100vh; padding: 50px 20px; }
.container { max-width: 560px; margin: auto; }

.back-link {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600; color: #6b7280;
    text-decoration: none; margin-bottom: 20px; transition: color .15s;
}
.back-link:hover { color: #111827; }
.back-link svg { width: 15px; height: 15px; }

.card { background: white; padding: 32px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
.title { margin-bottom: 28px; }
.title h2 { font-size: 26px; color: #111827; font-weight: 800; }
.title p  { margin-top: 5px; color: #6b7280; font-size: 13.5px; }

.divider { display: flex; align-items: center; gap: 12px; margin: 24px 0; }
.divider span { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .8px; white-space: nowrap; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #f0f0f0; }

.form-group { margin-bottom: 16px; }
.form-label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 700; color: #111827; }
.form-input {
    width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e5e7eb;
    background: #f9fafb; font-size: 14px; color: #111827;
    outline: none; transition: .2s; font-family: 'Plus Jakarta Sans', sans-serif;
}
.form-input:focus { border-color: #6366f1; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.form-input::placeholder { color: #9ca3af; }

/* STATUS CARDS */
.status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 4px; }
.status-opt { position: relative; cursor: pointer; }
.status-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.status-card {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px; border-radius: 14px;
    border: 2px solid #e5e7eb; background: #f9fafb;
    transition: all .18s; cursor: pointer;
}
.status-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.status-icon svg { width: 18px; height: 18px; }
.status-label { font-size: 13.5px; font-weight: 700; color: #374151; }
.status-desc  { font-size: 11px; color: #9ca3af; margin-top: 2px; }

.status-opt.s-kosong .status-icon { background: #f0fdf4; }
.status-opt.s-kosong .status-icon svg { stroke: #22c55e; }
.status-opt.s-terisi .status-icon { background: #fef2f2; }
.status-opt.s-terisi .status-icon svg { stroke: #ef4444; }

.status-opt.s-kosong input:checked + .status-card { border-color: #22c55e; background: #f0fdf4; }
.status-opt.s-terisi input:checked + .status-card { border-color: #ef4444; background: #fef2f2; }

.status-check {
    margin-left: auto; width: 18px; height: 18px;
    border-radius: 50%; border: 2px solid #e5e7eb;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .18s;
}
.status-opt.s-kosong input:checked + .status-card .status-check { border-color: #22c55e; background: #22c55e; }
.status-opt.s-terisi input:checked + .status-card .status-check { border-color: #ef4444; background: #ef4444; }
.status-check svg { width: 10px; height: 10px; stroke: white; display: none; }
.status-opt input:checked + .status-card .status-check svg { display: block; }

/* QR PREVIEW */
.qr-preview-wrap { background: #f8fafc; border: 1.5px solid #e5e7eb; border-radius: 16px; padding: 20px; text-align: center; margin-top: 4px; }
.qr-preview-label {
    display: inline-flex; align-items: center; gap: 7px;
    background: #f5f3ff; color: #6d28d9;
    border: 1px solid #ddd6fe; border-radius: 30px;
    padding: 4px 14px; font-size: 12px; font-weight: 700; margin-bottom: 14px;
}
.qr-preview-label svg { width: 14px; height: 14px; }
.qr-frame {
    background: white; border: 1.5px solid #e2e8f0; border-radius: 14px;
    padding: 16px; display: inline-flex; align-items: center; justify-content: center;
    min-height: 150px; min-width: 150px;
}
.qr-empty { display: flex; flex-direction: column; align-items: center; gap: 8px; color: #d1d5db; font-size: 12px; font-weight: 500; }
.qr-empty svg { width: 40px; height: 40px; opacity: .3; }
.qr-url-text { font-size: 11px; color: #94a3b8; word-break: break-all; background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 7px 10px; margin-top: 12px; font-family: monospace; line-height: 1.6; text-align: left; }
.qr-hint { font-size: 12px; color: #9ca3af; margin-top: 10px; }

.alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; border-radius: 12px; padding: 12px 16px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }

.button-group { display: flex; gap: 12px; margin-top: 28px; }
.btn {
    flex: 1; padding: 13px; border-radius: 14px; border: none;
    font-size: 14px; font-weight: 700; cursor: pointer;
    text-decoration: none; text-align: center; transition: all .2s;
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.btn svg { width: 16px; height: 16px; }
.btn-save { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; box-shadow: 0 8px 20px rgba(99,102,241,0.25); }
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 12px 24px rgba(99,102,241,0.3); }
.btn-back { background: white; color: #374151; border: 1.5px solid #e5e7eb; }
.btn-back:hover { background: #f9fafb; }

@media(max-width:480px) { .status-grid { grid-template-columns: 1fr; } .button-group { flex-direction: column; } }
</style>
</head>
<body>
<div class="container">

    <div class="card">
        <div class="title">
            <h2>Tambah Meja</h2>
            <p>Daftarkan meja baru ke dalam sistem cafe</p>
        </div>

        @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="/admin/meja/store" method="POST" id="mejaForm">
        @csrf

        <div class="divider"><span>Informasi Meja</span></div>

        <div class="form-group">
            <label class="form-label">Nomor Meja <span style="color:#ef4444;">*</span></label>
            <input type="text" name="nomor_meja" id="nomorMejaInput"
                class="form-input"
                placeholder="Contoh: A1, B2, VIP-01..."
                value="{{ old('nomor_meja') }}"
                oninput="updateQRPreview()"
                required>
            <p style="font-size:12px; color:#9ca3af; margin-top:6px;">QR code akan otomatis diperbarui saat nomor meja diisi.</p>
        </div>

        <div class="divider"><span>Status Awal</span></div>

        <div class="form-group">
            <div class="status-grid">
                <label class="status-opt s-kosong">
                    <input type="radio" name="status" value="kosong" checked>
                    <div class="status-card">
                        <div class="status-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
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
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="divider"><span>Preview QR Code</span></div>

        <div class="qr-preview-wrap">
            <div class="qr-preview-label">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <p class="qr-hint">QR ini akan dicetak dan dipasang di meja. Pelanggan scan untuk memesan.</p>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-save">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                Simpan Meja
            </button>
            <a href="/admin/meja" class="btn btn-back">Batal</a>
        </div>

        </form>
    </div>
</div>

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

        // Gunakan APP_URL dari Laravel agar pakai ngrok, bukan window.location.origin
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
</script>
</body>
</html>