<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Saya</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">

<style>
*{ box-sizing:border-box; margin:0; padding:0; font-family:'Plus Jakarta Sans',sans-serif; }
body{ background:#F8F9FC; }

/* ── MAIN ── */
.main{ padding:40px 20px; max-width:780px; margin:auto; }

/* ── PAGE HEADER ── */
.page-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:22px; }
.page-header h1{ font-size:28px; font-weight:700; color:#111827; }
.page-header p{ font-size:14px; color:#6B7280; margin-top:4px; }

/* ── CARD ── */
.card{ background:white; border-radius:18px; border:1px solid #F0F0F0; box-shadow:0 2px 12px rgba(0,0,0,0.04); overflow:hidden; margin-bottom:16px; }
.card-section{ padding:24px; border-bottom:1px solid #F8F9FC; }
.card-section:last-child{ border-bottom:none; }
.card-section-title{ font-size:12px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:20px; }

/* ── AVATAR UPLOAD ── */
.avatar-row{ display:flex; align-items:center; gap:24px; }
.avatar-upload-container { position: relative; width: 85px; height: 85px; border-radius: 20px; flex-shrink: 0; box-shadow: 0 4px 16px rgba(0,0,0,0.08); transition: .3s ease; cursor: pointer; overflow: hidden; background: #F3F4F6; }
.avatar-upload-container:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }

.avatar-preview { width: 100%; height: 100%; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800; color: white; background-color: #F97316; }

.avatar-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity .2s; }
.avatar-upload-container:hover .avatar-overlay { opacity: 1; }
.avatar-overlay i { color: white; width: 24px; height: 24px; }

.avatar-info h3{ font-size:18px; font-weight:700; color:#111827; margin-bottom: 4px;}
.avatar-info p{ font-size:14px; color:#6B7280; }
.online-dot{ display:inline-flex; align-items:center; gap:5px; font-size:12px; color:#16A34A; font-weight:600; margin-top:8px; background: #DCFCE7; padding: 4px 10px; border-radius: 20px; }

/* ── FORM ── */
.form-grid-2{ display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.form-group{ display:flex; flex-direction:column; gap:8px; }
.form-label{ font-size:13px; font-weight:600; color:#374151; }
.form-input{ width:100%; padding:12px 16px; border:1.5px solid #E5E7EB; border-radius:10px; font-size:14px; color:#111827; background:white; outline:none; transition:.2s; }
.form-input:focus{ border-color:#F97316; box-shadow:0 0 0 3px rgba(249,115,22,0.1); }
.form-input[readonly]{ background:#F9FAFB; color:#9CA3AF; cursor: not-allowed; }

/* ── BADGE ── */
.info-badge{ display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:600; background:#EFF6FF; color:#2563EB; }

/* ── FOOTER ── */
.card-footer{ padding:20px 24px; background:#FAFAFA; border-top:1px solid #F0F0F0; display:flex; justify-content:flex-end; gap:12px; align-items:center; }
.btn-cancel{ padding:10px 20px; border-radius:10px; border:1.5px solid #E5E7EB; background:white; color:#374151; font-size:14px; font-weight:600; text-decoration:none; transition:.2s; }
.btn-cancel:hover{ background:#F3F4F6; }
.btn-save{ padding:10px 24px; border-radius:10px; border:none; background:#F97316; color:white; font-size:14px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:.2s; box-shadow: 0 4px 12px rgba(249,115,22,0.2); }
.btn-save:hover{ background:#EA6A0A; transform: translateY(-1px); }

/* ── ALERT ── */
.alert-success{ display:flex; align-items:center; gap:10px; padding:14px 18px; border-radius:10px; background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; font-size:14px; font-weight:600; margin-bottom:20px; }

@media(max-width:580px){
    .form-grid-2{ grid-template-columns:1fr; }
    .avatar-row{ flex-direction:column; text-align:center; }
    .card-footer{ justify-content: stretch; flex-direction: column-reverse; }
    .card-footer > * { width: 100%; text-align: center; justify-content: center;}
}
</style>
</head>
<body>

<div class="main">
    <div class="page-header">
        <div>
            <h1>Profil Saya</h1>
            <p>Kelola informasi akun dan foto profil Anda</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <i data-lucide="check-circle" style="width:18px;height:18px;"></i>
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.account.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Hidden input untuk menerima hasil crop sebagai base64 --}}
        <input type="hidden" name="avatar_cropped" id="avatarCroppedInput">

        <div class="card">
            <div class="card-section">
                <p class="card-section-title">Identitas Akun</p>
                <div class="avatar-row">
                    
                    <label class="avatar-upload-container" for="avatarInput">
                        @php
                            $hasAvatar = auth()->user()->avatar ?? false;
                            $avatarUrl = $hasAvatar ? asset('storage/' . auth()->user()->avatar) : '';
                            $initial = strtoupper(substr(auth()->user()->name ?? 'A', 0, 1));
                        @endphp
                        
                        <div class="avatar-preview" id="avatarPreview" 
                             style="{{ $hasAvatar ? "background-image: url('{$avatarUrl}'); background-color: transparent;" : '' }}">
                            @if(!$hasAvatar)
                                <span id="avatarInitial">{{ $initial }}</span>
                            @endif
                        </div>

                        <div class="avatar-overlay">
                            <i data-lucide="camera"></i>
                        </div>
                    </label>

                    {{-- Input file hanya digunakan untuk memicu cropper, TIDAK dikirim ke server --}}
                    <input type="file" name="avatar_file_trigger" id="avatarInput" accept="image/*" style="display: none;">

                    <div class="avatar-info">
                        <h3>{{ auth()->user()->name ?? 'Admin' }}</h3>
                        <p>{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                        <div class="online-dot">● Online sekarang</div>
                    </div>
                </div>
                <small style="display:block; margin-top:12px; color:#9CA3AF; font-size:12px;">
                    Klik foto di atas untuk mengubah foto profil (Max: 2MB, Format: JPG/PNG)
                </small>
            </div>

            <div class="card-section">
                <p class="card-section-title">Data Diri</p>
                <div style="display:grid; gap:20px;">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-input" value="{{ old('username', auth()->user()->username ?? '') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', auth()->user()->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No Telepon</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', auth()->user()->phone ?? '') }}">
                    </div>
                </div>
            </div>

            <div class="card-section">
                <p class="card-section-title">Info Akun</p>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-input" value="{{ ucfirst(auth()->user()->role ?? 'Administrator') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div style="padding-top:6px;">
                            <span class="info-badge">
                                <i data-lucide="shield-check" style="width:14px;height:14px;"></i> Akun Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="/admin/dashboard" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save">
                    <i data-lucide="save" style="width:16px;height:16px;"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

{{-- MODAL CROPPER --}}
<div id="cropperModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; padding: 24px; border-radius: 16px; max-width: 400px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 12px; font-size: 16px; font-weight: 700; color: #111827;">Sesuaikan Foto Profil</h3>
        <p style="font-size:13px; color:#6B7280; margin-bottom:16px;">Gunakan mouse/jari untuk menggeser dan memperbesar/memperkecil gambar.</p>
        <div style="max-height: 280px; width: 100%; overflow: hidden; background: #f3f4f6; border-radius: 8px; margin-bottom: 20px;">
            <img id="cropperImage" src="" style="max-width: 100%; display: block;">
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <button type="button" id="btnCancelCrop" style="padding: 10px 16px; border-radius: 10px; border: 1.5px solid #E5E7EB; background: white; font-size: 14px; font-weight: 600; cursor: pointer; color:#374151;">Batal</button>
            <button type="button" id="btnApplyCrop" style="padding: 10px 20px; border-radius: 10px; border: none; background: #F97316; color: white; font-size: 14px; font-weight: 700; cursor: pointer;">Terapkan</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<script>
    lucide.createIcons();

    let cropper;
    const avatarInput       = document.getElementById('avatarInput');
    const cropperModal      = document.getElementById('cropperModal');
    const cropperImage      = document.getElementById('cropperImage');
    const avatarCroppedInput = document.getElementById('avatarCroppedInput');

    // Listener ketika user memilih gambar
    avatarInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            if(file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal adalah 2MB');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                cropperModal.style.display = 'flex';
                
                if (cropper) {
                    cropper.destroy();
                }
                
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    background: false,
                    autoCropArea: 1,
                    responsive: true,
                    zoomable: true
                });
            }
            reader.readAsDataURL(file);
        }
    });

    // Tombol Batal
    document.getElementById('btnCancelCrop').addEventListener('click', function() {
        cropperModal.style.display = 'none';
        if (cropper) { cropper.destroy(); }
        avatarInput.value = '';
        avatarCroppedInput.value = ''; // hapus data sebelumnya jika batal
    });

    // Tombol Terapkan
    document.getElementById('btnApplyCrop').addEventListener('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });
            
            const base64 = canvas.toDataURL('image/jpeg', 0.85);

            // Simpan base64 ke hidden input agar dikirim ke server saat form submit
            avatarCroppedInput.value = base64;

            // Update tampilan preview
            const preview = document.getElementById('avatarPreview');
            const initial = document.getElementById('avatarInitial');
            
            preview.style.backgroundImage = `url('${base64}')`;
            preview.style.backgroundColor = 'transparent';
            if(initial) {
                initial.style.display = 'none';
            }
            
            cropperModal.style.display = 'none';
            cropper.destroy();
        }
    });
</script>

</body>
</html>