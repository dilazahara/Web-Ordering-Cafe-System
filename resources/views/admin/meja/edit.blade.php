<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Meja</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
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
.status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.status-opt { position: relative; cursor: pointer; }
.status-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.status-card { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 14px; border: 2px solid #e5e7eb; background: #f9fafb; transition: all .18s; cursor: pointer; }
.status-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.status-icon i { width: 18px; height: 18px; }
.status-label { font-size: 13.5px; font-weight: 700; color: #374151; }
.status-desc  { font-size: 11px; color: #9ca3af; margin-top: 2px; }

.status-opt.s-kosong .status-icon { background: #f0fdf4; }
.status-opt.s-kosong .status-icon i { stroke: #22c55e; }
.status-opt.s-terisi .status-icon { background: #fef2f2; }
.status-opt.s-terisi .status-icon i { stroke: #ef4444; }

.status-opt.s-kosong input:checked + .status-card { border-color: #22c55e; background: #f0fdf4; }
.status-opt.s-terisi input:checked + .status-card { border-color: #ef4444; background: #fef2f2; }

.status-check { margin-left: auto; width: 18px; height: 18px; border-radius: 50%; border: 2px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .18s; }
.status-opt.s-kosong input:checked + .status-card .status-check { border-color: #22c55e; background: #22c55e; }
.status-opt.s-terisi input:checked + .status-card .status-check { border-color: #ef4444; background: #ef4444; }
.status-check i { width: 10px; height: 10px; stroke: white; display: none; }
.status-opt input:checked + .status-card .status-check i { display: block; }

/* INFO BOX */
.info-box { background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px; padding: 14px 16px; }
.info-row { display: flex; justify-content: space-between; font-size: 13px; color: #6b7280; padding: 3px 0; }
.info-row span { font-weight: 700; color: #111827; }

/* QR PREVIEW */
.qr-preview-wrap { background: #f8fafc; border: 1.5px solid #e5e7eb; border-radius: 16px; padding: 20px; text-align: center; }
.qr-preview-label { display: inline-flex; align-items: center; gap: 7px; background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; border-radius: 30px; padding: 4px 14px; font-size: 12px; font-weight: 700; margin-bottom: 14px; }
.qr-frame { background: white; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 16px; display: inline-flex; align-items: center; justify-content: center; }
.qr-url-text { font-size: 11px; color: #94a3b8; word-break: break-all; background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 7px 10px; margin-top: 12px; font-family: monospace; line-height: 1.6; text-align: left; }
.qr-actions { display: flex; gap: 8px; margin-top: 12px; }
.btn-qr { flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 9px; border-radius: 11px; border: none; cursor: pointer; font-size: 12px; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; transition: all .18s; }
.btn-dl { background: #6366f1; color: white; }
.btn-dl:hover { background: #4f46e5; }
.btn-print { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
.btn-print:hover { background: #e2e8f0; }

.alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; border-radius: 12px; padding: 12px 16px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }

/* BUTTONS */
.button-group { display: flex; gap: 12px; margin-top: 28px; }
.btn { flex: 1; padding: 13px; border-radius: 14px; border: none; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; transition: all .2s; display: inline-flex; align-items: center; justify-content: center; gap: 7px; font-family: 'Plus Jakarta Sans', sans-serif; }
.btn i { width: 16px; height: 16px; }
.btn-save { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; box-shadow: 0 8px 20px rgba(99,102,241,0.25); }
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 12px 24px rgba(99,102,241,0.3); }
.btn-back { background: white; color: #374151; border: 1.5px solid #e5e7eb; }
.btn-back:hover { background: #f9fafb; }
.btn-delete-full { width: 100%; margin-top: 12px; padding: 12px; border-radius: 14px; border: 1.5px solid #fee2e2; background: white; color: #dc2626; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all .18s; }
.btn-delete-full:hover { background: #fef2f2; border-color: #fca5a5; }

@media(max-width:480px) { .status-grid { grid-template-columns: 1fr; } .button-group { flex-direction: column; } }
</style>
</head>
<body>
<div class="container">

    <a href="/admin/meja" class="back-link">
    </a>

    <div class="card">
        <div class="title">
            <h2>Edit Meja</h2>
            <p>Perbarui data meja restoran</p>
        </div>

        @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="/admin/meja/update/{{ $meja->id }}" method="POST" id="mejaForm">
        @csrf
        @method('PUT')

        <div class="divider"><span>Informasi Meja</span></div>

        <div class="form-group">
            <label class="form-label">Nomor Meja <span style="color:#ef4444;">*</span></label>
            <input type="text" name="nomor_meja" id="nomorMejaInput"
                class="form-input"
                value="{{ old('nomor_meja', $meja->nomor_meja) }}"
                placeholder="Contoh: A1, B2, VIP-01..."
                oninput="updateQRPreview()"
                required>
            <p style="font-size:12px; color:#9ca3af; margin-top:6px;">Ubah nomor meja untuk memperbarui QR code secara otomatis.</p>
        </div>

        <div class="divider"><span>Status Meja</span></div>

        <div class="form-group">
            <div class="status-grid">
                <label class="status-opt s-kosong">
                    <input type="radio" name="status" value="kosong" {{ $meja->status == 'kosong' ? 'checked' : '' }}>
                    <div class="status-card">
                        <div class="status-icon"><i data-lucide="circle-check"></i></div>
                        <div>
                            <div class="status-label">Kosong</div>
                            <div class="status-desc">Siap digunakan</div>
                        </div>
                        <div class="status-check"><i data-lucide="check"></i></div>
                    </div>
                </label>
                <label class="status-opt s-terisi">
                    <input type="radio" name="status" value="terisi" {{ $meja->status == 'terisi' ? 'checked' : '' }}>
                    <div class="status-card">
                        <div class="status-icon"><i data-lucide="users"></i></div>
                        <div>
                            <div class="status-label">Terisi</div>
                            <div class="status-desc">Sedang dipakai</div>
                        </div>
                        <div class="status-check"><i data-lucide="check"></i></div>
                    </div>
                </label>
            </div>
            <p style="font-size:12px; color:#9ca3af; margin-top:8px;">Status meja akan otomatis berubah mengikuti order aktif.</p>
        </div>

        <div class="divider"><span>Info & QR Code</span></div>

        <div class="info-box" style="margin-bottom:16px;">
            <div class="info-row">ID Meja <span>#{{ $meja->id }}</span></div>
            <div class="info-row">Dibuat <span>{{ $meja->created_at->format('d M Y') }}</span></div>
            <div class="info-row">Diperbarui <span>{{ $meja->updated_at->format('d M Y') }}</span></div>
        </div>

        <div class="qr-preview-wrap">
            <div class="qr-preview-label">
                <i data-lucide="armchair" style="width:14px;height:14px;"></i>
                <span id="qrLabelText">Meja {{ $meja->nomor_meja }}</span>
            </div>
            <div class="qr-frame">
                <div id="qrPreview"></div>
            </div>
            <div class="qr-url-text" id="qrUrlDisplay">{{ url('/order?meja=' . $meja->nomor_meja) }}</div>
            <div class="qr-actions">
                <button type="button" class="btn-qr btn-dl" onclick="downloadQR()">
                    <i data-lucide="download" style="width:14px;height:14px;"></i> Download PNG
                </button>
                <button type="button" class="btn-qr btn-print" onclick="printQR()">
                    <i data-lucide="printer" style="width:14px;height:14px;"></i> Print
                </button>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-save">
                <i data-lucide="save"></i>
                Update Meja
            </button>
            <a href="/admin/meja" class="btn btn-back">Batal</a>
        </div>

        </form>
    </div>
</div>

<script>
lucide.createIcons();

let currentNomor = '{{ $meja->nomor_meja }}';
let qrDebounce = null;

function getQrUrl(nomor) {
    return window.location.origin + '/order?meja=' + encodeURIComponent(nomor);
}

function renderQR(nomor) {
    currentNomor = nomor;
    const url = getQrUrl(nomor);
    document.getElementById('qrLabelText').textContent = 'Meja ' + nomor;
    document.getElementById('qrUrlDisplay').textContent = url;
    const wrap = document.getElementById('qrPreview');
    wrap.innerHTML = '';
    new QRCode(wrap, { text: url, width: 160, height: 160, colorDark: '#0f172a', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.H });
}

function updateQRPreview() {
    clearTimeout(qrDebounce);
    qrDebounce = setTimeout(() => {
        const nomor = document.getElementById('nomorMejaInput').value.trim();
        if (!nomor) return;
        renderQR(nomor);
    }, 300);
}

function confirmDelete() {
    if (confirm('Yakin ingin menghapus meja ini? Tindakan ini tidak bisa dibatalkan.')) {
        document.getElementById('deleteForm').submit();
    }
}

function downloadQR() {
    const wrap = document.getElementById('qrPreview');
    const cnv  = wrap.querySelector('canvas');
    const img  = wrap.querySelector('img');
    if (cnv) {
        const pad = 20, labelH = 34;
        const out = document.createElement('canvas');
        out.width  = cnv.width  + pad * 2;
        out.height = cnv.height + pad * 2 + labelH;
        const ctx  = out.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, out.width, out.height);
        ctx.drawImage(cnv, pad, pad);
        ctx.fillStyle = '#0f172a';
        ctx.font = 'bold 14px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Meja ' + currentNomor, out.width / 2, cnv.height + pad + labelH - 8);
        const link = document.createElement('a');
        link.download = 'QR-Meja-' + currentNomor + '.png';
        link.href = out.toDataURL('image/png');
        link.click();
    } else if (img) {
        const link = document.createElement('a');
        link.download = 'QR-Meja-' + currentNomor + '.png';
        link.href = img.src;
        link.click();
    }
}

function printQR() {
    const wrap = document.getElementById('qrPreview');
    const cnv  = wrap.querySelector('canvas');
    const img  = wrap.querySelector('img');
    const src  = cnv ? cnv.toDataURL() : (img ? img.src : '');
    const url  = getQrUrl(currentNomor);
    const win  = window.open('', '_blank', 'width=480,height=560');
    win.document.write(`<!DOCTYPE html><html><head><title>QR Meja ${currentNomor}</title>
    <style>body{margin:0;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif;background:#fff;}img{width:220px;height:220px;}h2{margin-top:14px;font-size:18px;color:#0f172a;}p{font-size:11px;color:#64748b;margin-top:5px;word-break:break-all;max-width:240px;text-align:center;}</style>
    </head><body><img src="${src}"><h2>Meja ${currentNomor}</h2><p>${url}</p><script>window.onload=()=>window.print()<\/script></body></html>`);
    win.document.close();
}

// Render QR awal
document.addEventListener('DOMContentLoaded', () => renderQR('{{ $meja->nomor_meja }}'));
</script>
</body>
</html>