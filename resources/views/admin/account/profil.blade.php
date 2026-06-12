@extends('layouts.admin')

@section('title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ── CARD ── */
.card { background: white; border-radius: 18px; border: 1px solid #F0F0F0; box-shadow: 0 2px 12px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 16px; }
.card-section { padding: 24px; border-bottom: 1px solid #F8F9FC; }
.card-section:last-child { border-bottom: none; }
.card-section-title { font-size: 12px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 20px; }

/* ── CARD PAGE HEADER ── */
.card-page-header { padding: 24px 24px 20px; border-bottom: 1px solid #F0F0F0; background: linear-gradient(135deg, #eef2ff 0%, #fff 60%); }
.card-page-header h1 { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 4px; }
.card-page-header p { font-size: 13px; color: #6B7280; }

/* ── AVATAR ROW ── */
.avatar-row { display: flex; align-items: center; gap: 24px; }
.avatar-wrapper { position: relative; display: inline-block; flex-shrink: 0; }

.avatar-upload-container {
    position: relative; width: 85px; height: 85px; border-radius: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08); overflow: hidden;
    background: #F3F4F6; cursor: default;
}

.avatar-preview {
    width: 100%; height: 100%; background-size: cover; background-position: center;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 800; color: white; background-color: #6366f1;
}

/* Spinner overlay saat upload */
.avatar-spinner {
    position: absolute; inset: 0; background: rgba(0,0,0,0.45);
    display: none; align-items: center; justify-content: center; border-radius: 20px;
}
.avatar-spinner.show { display: flex; }
.spinner-ring {
    width: 28px; height: 28px; border: 3px solid rgba(255,255,255,0.3);
    border-top-color: white; border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── TOMBOL EDIT ── */
.btn-edit-avatar {
    position: absolute; bottom: -11px; left: 50%; transform: translateX(-50%);
    background: #6366f1; color: white; border: 2.5px solid white; border-radius: 20px;
    padding: 3px 10px; font-size: 11px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; gap: 4px; white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18); transition: background .2s; z-index: 10;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.btn-edit-avatar:hover { background: #4f46e5; }
.btn-edit-avatar svg { width: 11px; height: 11px; }

/* ── DROPDOWN ── */
.avatar-dropdown {
    position: absolute; top: calc(100% + 16px); left: 0;
    background: white; border-radius: 14px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.16); border: 1px solid #F0F0F0;
    min-width: 185px; z-index: 1000; overflow: hidden; display: none;
    animation: dropIn .15s ease;
}
.avatar-dropdown.open { display: block; }
@keyframes dropIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.dropdown-item {
    display: flex; align-items: center; gap: 10px; padding: 11px 16px;
    font-size: 13px; font-weight: 600; color: #374151;
    cursor: pointer; transition: background .15s;
    border: none; background: none; width: 100%; text-align: left;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.dropdown-item:hover { background: #F9FAFB; }
.dropdown-item svg { width: 16px; height: 16px; color: #6B7280; flex-shrink: 0; }
.dropdown-item.danger { color: #DC2626; }
.dropdown-item.danger svg { color: #DC2626; }
.dropdown-divider { height: 1px; background: #F0F0F0; margin: 4px 0; }

/* ── AVATAR INFO ── */
.avatar-info h3 { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px; }
.avatar-info p { font-size: 14px; color: #6B7280; }
.online-dot { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; color: #16A34A; font-weight: 600; margin-top: 8px; background: #DCFCE7; padding: 4px 10px; border-radius: 20px; }

/* ── FORM ── */
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-label { font-size: 13px; font-weight: 600; color: #374151; }
.form-input { width: 100%; padding: 12px 16px; border: 1.5px solid #E5E7EB; border-radius: 10px; font-size: 14px; color: #111827; background: white; outline: none; transition: .2s; font-family: 'Plus Jakarta Sans', sans-serif; }
.form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.form-input[readonly] { background: #F9FAFB; color: #9CA3AF; cursor: not-allowed; }

/* ── PASSWORD TOGGLE ── */
.input-password-wrap { position: relative; }
.input-password-wrap .form-input { padding-right: 44px; }
.btn-eye {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #9CA3AF;
    display: flex; align-items: center; padding: 0; transition: color .2s;
}
.btn-eye:hover { color: #6366f1; }

/* ── PASSWORD STRENGTH ── */
.password-strength { margin-top: 6px; }
.strength-bar { display: flex; gap: 4px; margin-bottom: 4px; }
.strength-segment { height: 4px; flex: 1; border-radius: 99px; background: #E5E7EB; transition: background .3s; }
.strength-label { font-size: 11px; font-weight: 600; color: #9CA3AF; }

/* ── BADGE ── */
.info-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; background: #EEF2FF; color: #4338ca; }

/* ── FOOTER ── */
.card-footer { padding: 20px 24px; background: #FAFAFA; border-top: 1px solid #F0F0F0; display: flex; justify-content: flex-end; gap: 12px; align-items: center; }
.btn-cancel { padding: 10px 20px; border-radius: 10px; border: 1.5px solid #E5E7EB; background: white; color: #374151; font-size: 14px; font-weight: 600; text-decoration: none; transition: .2s; display: inline-flex; align-items: center; }
.btn-cancel:hover { background: #F3F4F6; }
.btn-save { padding: 10px 24px; border-radius: 10px; border: none; background: #6366f1; color: white; font-size: 14px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: .2s; box-shadow: 0 4px 12px rgba(99,102,241,0.25); font-family: 'Plus Jakarta Sans', sans-serif; }
.btn-save:hover { background: #4f46e5; transform: translateY(-1px); }

/* ── ALERT ── */
.alert-success { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-radius: 10px; background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D; font-size: 14px; font-weight: 600; margin-bottom: 20px; }
.alert-error { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-radius: 10px; background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; font-size: 14px; font-weight: 600; margin-bottom: 20px; }
.password-note { font-size: 12px; color: #9CA3AF; margin-top: 4px; }

/* ── SECTION DIVIDER ── */
.section-note {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 10px;
    background: #eef2ff; border: 1px solid #c7d2fe;
    color: #4338ca; font-size: 12px; font-weight: 600;
    margin-bottom: 20px;
}
.section-note svg { width: 15px; height: 15px; flex-shrink: 0; }

/* ── PASSWORD LOCK STATE ── */
.pw-locked-box {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 14px; padding: 32px 24px; border-radius: 14px;
    background: #f8fafc; border: 2px dashed #e2e8f0; text-align: center;
}
.pw-lock-icon {
    width: 56px; height: 56px; border-radius: 50%;
    background: #eef2ff; display: flex; align-items: center; justify-content: center;
}
.pw-lock-icon svg { width: 26px; height: 26px; color: #6366f1; }
.pw-locked-box h4 { font-size: 15px; font-weight: 700; color: #111827; }
.pw-locked-box p  { font-size: 13px; color: #6B7280; max-width: 280px; line-height: 1.5; }
.btn-verify {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 22px; border-radius: 10px; border: none;
    background: #6366f1; color: white; font-size: 14px; font-weight: 700;
    cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
    transition: background .2s, transform .15s;
    box-shadow: 0 4px 12px rgba(99,102,241,0.25);
}
.btn-verify:hover { background: #4f46e5; transform: translateY(-1px); }
.btn-verify svg { width: 15px; height: 15px; }

.pw-verified-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 8px;
    background: #f0fdf4; border: 1px solid #bbf7d0;
    color: #15803d; font-size: 12px; font-weight: 700;
    margin-bottom: 16px;
}
.pw-verified-badge svg { width: 13px; height: 13px; }

/* ── VERIFY MODAL ── */
#verifyPasswordModal .modal-box { max-width: 380px; }
.verify-body { padding: 20px 24px 8px; }
.verify-body p { font-size: 13px; color: #6B7280; margin-bottom: 18px; line-height: 1.5; }
.verify-error {
    display: none; align-items: center; gap: 8px;
    padding: 10px 14px; border-radius: 8px;
    background: #fef2f2; border: 1px solid #fecaca;
    color: #dc2626; font-size: 12px; font-weight: 600;
    margin-top: 10px;
}
.verify-error.show { display: flex; }
.verify-error svg { width: 14px; height: 14px; flex-shrink: 0; }

/* ══════════════════════════════════
   MODAL BASE
══════════════════════════════════ */
.modal-overlay {
    display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55);
    z-index: 9999; align-items: center; justify-content: center; padding: 20px;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: white; border-radius: 18px; max-width: 420px; width: 100%;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    animation: modalIn .2s ease; overflow: hidden;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(.95) translateY(10px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px 0; }
.modal-header h3 { font-size: 16px; font-weight: 700; color: #111827; }
.btn-modal-close {
    background: none; border: none; cursor: pointer; color: #9CA3AF;
    display: flex; align-items: center; padding: 4px;
    border-radius: 8px; transition: background .15s, color .15s;
}
.btn-modal-close:hover { background: #F3F4F6; color: #374151; }
.btn-modal-close svg { width: 20px; height: 20px; }

/* ── VIEW PHOTO MODAL ── */
#viewPhotoModal .modal-box { max-width: 500px; }
.view-photo-body { padding: 16px 24px 24px; }
.view-photo-img-wrap {
    width: 100%; border-radius: 12px; overflow: hidden;
    background: #F3F4F6; display: flex; align-items: center;
    justify-content: center; min-height: 200px;
}
.view-photo-img-wrap img { width: 100%; height: auto; display: block; max-height: 400px; object-fit: contain; }

/* ── CROPPER MODAL ── */
#cropperModal .modal-box { max-width: 400px; }
.cropper-body { padding: 12px 24px 20px; }
.cropper-body p { font-size: 13px; color: #6B7280; margin-bottom: 16px; }
.cropper-img-wrap { max-height: 280px; width: 100%; overflow: hidden; background: #F3F4F6; border-radius: 8px; margin-bottom: 4px; }

.modal-footer { padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #F0F0F0; }
.btn-secondary { padding: 10px 16px; border-radius: 10px; border: 1.5px solid #E5E7EB; background: white; font-size: 14px; font-weight: 600; cursor: pointer; color: #374151; transition: background .15s; font-family: 'Plus Jakarta Sans', sans-serif; }
.btn-secondary:hover { background: #F3F4F6; }
.btn-primary { padding: 10px 20px; border-radius: 10px; border: none; background: #6366f1; color: white; font-size: 14px; font-weight: 700; cursor: pointer; transition: background .15s; font-family: 'Plus Jakarta Sans', sans-serif; }
.btn-primary:hover { background: #4f46e5; }
.btn-danger-solid { padding: 10px 20px; border-radius: 10px; border: none; background: #DC2626; color: white; font-size: 14px; font-weight: 700; cursor: pointer; transition: background .15s; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; gap: 6px; }
.btn-danger-solid:hover { background: #B91C1C; }

/* ── DELETE CONFIRM MODAL ── */
#deleteConfirmModal .modal-box { max-width: 380px; }
.delete-body { padding: 24px 24px 8px; text-align: center; }
.delete-icon-wrap { width: 64px; height: 64px; border-radius: 50%; background: #FEF2F2; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.delete-icon-wrap svg { width: 30px; height: 30px; color: #DC2626; }
.delete-body h3 { font-size: 17px; font-weight: 700; color: #111827; margin-bottom: 8px; }
.delete-body p { font-size: 13px; color: #6B7280; line-height: 1.6; }

/* ── TOAST ── */
.toast {
    position: fixed; bottom: 28px; left: 50%;
    transform: translateX(-50%) translateY(80px);
    background: #111827; color: white; padding: 12px 20px; border-radius: 12px;
    font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;
    z-index: 99999; box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .3s;
    opacity: 0; pointer-events: none; white-space: nowrap;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
.toast.success { background: #16A34A; }
.toast.error { background: #DC2626; }
.toast svg { width: 16px; height: 16px; }

/* ── KAMERA MODAL ── */
#cameraModal .modal-box { max-width: 460px; }
.camera-body { padding: 16px 24px 20px; }
.camera-body p { font-size: 13px; color: #6B7280; margin-bottom: 14px; }
#cameraVideo {
    width: 100%; border-radius: 10px; background: #000;
    display: block; max-height: 300px; object-fit: cover;
}
#cameraCanvas { display: none; }
.camera-actions { display: flex; gap: 10px; margin-top: 14px; }
.btn-capture {
    flex: 1; padding: 12px; border-radius: 10px; border: none;
    background: #6366f1; color: white; font-size: 14px; font-weight: 700;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif; transition: background .15s;
}
.btn-capture:hover { background: #4f46e5; }
.btn-capture svg { width: 16px; height: 16px; }

/* VALIDASI */
.form-input.is-invalid { border-color: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.field-error { font-size: 12px; color: #ef4444; margin-top: 4px; display: none; font-weight: 500; }
.field-error.show { display: block; }

@media (max-width: 580px) {
    .form-grid-2 { grid-template-columns: 1fr; }
    .avatar-row { flex-direction: column; text-align: center; }
    .card-footer { justify-content: stretch; flex-direction: column-reverse; }
    .card-footer > * { width: 100%; text-align: center; justify-content: center; }
    .avatar-dropdown { left: 50%; transform: translateX(-50%); }
}
</style>
@endpush

@section('content')

    @if(session('success'))
    <div class="alert-success">
        <i data-lucide="check-circle" style="width:18px;height:18px;"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error">
        <i data-lucide="x-circle" style="width:18px;height:18px;"></i>
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert-error">
        <i data-lucide="x-circle" style="width:18px;height:18px;flex-shrink:0;"></i>
        <div>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════
         FORM UTAMA — Data Diri + Password
         (Foto profil auto-save via AJAX, tidak perlu klik Simpan)
    ══════════════════════════════════════ --}}
    <form action="{{ route('admin.account.update') }}" method="POST" id="formProfil" novalidate>
        @csrf
        @method('PUT')

        <div class="card">

            <div class="card-page-header">
                <h1>Profil Saya</h1>
                <p>Kelola informasi akun dan foto profil Anda</p>
            </div>

            {{-- ── IDENTITAS (avatar — auto-save, tidak ikut form utama) ── --}}
            <div class="card-section">
                <p class="card-section-title">Foto Profil</p>

                <div class="section-note">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Foto profil tersimpan <strong>otomatis</strong> setelah diunggah — tidak perlu klik tombol Simpan.
                </div>

                <div class="avatar-row">

                    @php
                        $hasAvatar = auth()->user()->avatar ?? false;
                        $avatarUrl = $hasAvatar ? asset('storage/' . auth()->user()->avatar) : '';
                        $initial   = strtoupper(substr(auth()->user()->name ?? 'A', 0, 1));
                    @endphp

                    <div class="avatar-wrapper" id="avatarWrapper">
                        <div class="avatar-upload-container">
                            <div class="avatar-preview" id="avatarPreview"
                                 style="{{ $hasAvatar ? "background-image:url('{$avatarUrl}');background-color:transparent;" : '' }}">
                                @if(!$hasAvatar)
                                    <span id="avatarInitial">{{ $initial }}</span>
                                @endif
                            </div>
                            {{-- Spinner --}}
                            <div class="avatar-spinner" id="avatarSpinner">
                                <div class="spinner-ring"></div>
                            </div>
                        </div>

                        {{-- Tombol Edit --}}
                        <button type="button" class="btn-edit-avatar" id="btnEditAvatar">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                            Edit
                        </button>

                        {{-- Dropdown --}}
                        <div class="avatar-dropdown" id="avatarDropdown">
                            <button type="button" class="dropdown-item" id="ddLihatFoto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Lihat foto
                            </button>
                            <button type="button" class="dropdown-item" id="ddAmbilFoto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                    <circle cx="12" cy="13" r="4"/>
                                </svg>
                                Ambil foto 
                            </button>
                            <button type="button" class="dropdown-item" id="ddUnggahFoto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                Unggah foto
                            </button>
                            <div class="dropdown-divider"></div>
                            <button type="button" class="dropdown-item danger" id="ddHapusFoto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6"/>
                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                </svg>
                                Hapus foto
                            </button>
                        </div>
                    </div>

                    {{-- Input file tersembunyi (hanya untuk unggah) --}}
                    <input type="file" id="avatarInput" accept="image/*" style="display:none;">

                    <div class="avatar-info">
                        <h3 id="displayName">{{ auth()->user()->name ?? 'Admin' }}</h3>
                        <p>{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                        <div class="online-dot">● Online sekarang</div>
                    </div>
                </div>
                <small style="display:block;margin-top:20px;color:#9CA3AF;font-size:12px;">
                    Max 2MB · Format: JPG / PNG
                </small>
            </div>

            {{-- ── DATA DIRI ── --}}
            <div class="card-section">
                <p class="card-section-title">Data Diri</p>
                <div style="display:grid;gap:20px;">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="fieldName" class="form-input"
                               value="{{ old('name', auth()->user()->name) }}">
                        <span class="field-error" id="errName"></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="fieldEmail" class="form-input"
                               value="{{ old('email', auth()->user()->email) }}">
                        <span class="field-error" id="errEmail"></span>
                    </div>
                </div>
            </div>

            {{-- ── INFO AKUN ── --}}
            <div class="card-section">
                <p class="card-section-title">Info Akun</p>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-input"
                               value="{{ ucfirst(auth()->user()->role ?? 'Administrator') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div style="padding-top:6px;">
                            <span class="info-badge">
                                <i data-lucide="shield-check" style="width:14px;height:14px;"></i>
                                Akun Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── GANTI PASSWORD ── --}}
            <div class="card-section">
                <p class="card-section-title">Ganti Password</p>

                {{-- INPUT HIDDEN: current_password diisi otomatis setelah verifikasi --}}
                <input type="hidden" name="current_password" id="currentPasswordHidden">

                {{-- STATE TERKUNCI (default) --}}
                <div id="pwLockedState">
                    <div class="pw-locked-box">
                        <div class="pw-lock-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h4>Password Terkunci</h4>
                        <p>Untuk keamanan akun, kamu perlu verifikasi password saat ini terlebih dahulu sebelum bisa menggantinya.</p>
                        <button type="button" class="btn-verify" id="btnOpenVerify">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            Verifikasi Identitas
                        </button>
                    </div>
                </div>

                {{-- STATE TERBUKA (setelah verifikasi berhasil) --}}
                <div id="pwUnlockedState" style="display:none;">
                    <div class="pw-verified-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        Identitas terverifikasi — silakan ganti password
                    </div>
                    <div style="display:grid;gap:20px;">
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Password Baru</label>
                                <div class="input-password-wrap">
                                    <input type="password" name="new_password" id="newPassword"
                                           class="form-input" placeholder="Min. 8 karakter"
                                           autocomplete="new-password"
                                           oninput="checkStrength(this.value)">
                                    <button type="button" class="btn-eye"
                                            onclick="togglePassword('newPassword','eyeNew')">
                                        <i data-lucide="eye" id="eyeNew" style="width:18px;height:18px;"></i>
                                    </button>
                                </div>
                                <div class="password-strength" id="strengthWrap" style="display:none;">
                                    <div class="strength-bar">
                                        <div class="strength-segment" id="seg1"></div>
                                        <div class="strength-segment" id="seg2"></div>
                                        <div class="strength-segment" id="seg3"></div>
                                        <div class="strength-segment" id="seg4"></div>
                                    </div>
                                    <span class="strength-label" id="strengthLabel"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-password-wrap">
                                    <input type="password" name="new_password_confirmation" id="confirmPassword"
                                           class="form-input" placeholder="Ulangi password baru"
                                           autocomplete="new-password"
                                           oninput="checkConfirm()">
                                    <button type="button" class="btn-eye"
                                            onclick="togglePassword('confirmPassword','eyeConfirm')">
                                        <i data-lucide="eye" id="eyeConfirm" style="width:18px;height:18px;"></i>
                                    </button>
                                </div>
                                <span class="password-note" id="confirmNote" style="display:none;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer — Simpan hanya untuk Data Diri + Password --}}
            <div class="card-footer">
                <a href="/admin/dashboard" class="btn-cancel">Kembali</a>
                <button type="submit" class="btn-save">
                    <i data-lucide="save" style="width:16px;height:16px;"></i>
                    Simpan Data Diri &amp; Password
                </button>
            </div>
        </div>
    </form>

{{-- ══════════════════════════════════════
     MODAL: LIHAT FOTO
══════════════════════════════════════ --}}
<div class="modal-overlay" id="viewPhotoModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Foto Profil</h3>
            <button type="button" class="btn-modal-close" id="btnCloseViewPhoto">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="view-photo-body">
            <div class="view-photo-img-wrap" id="viewPhotoImgWrap"></div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL: CROPPER (setelah unggah file)
══════════════════════════════════════ --}}
<div class="modal-overlay" id="cropperModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Sesuaikan Foto Profil</h3>
            <button type="button" class="btn-modal-close" id="btnCloseCropper">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="cropper-body">
            <p>Geser dan zoom untuk menyesuaikan foto.</p>
            <div class="cropper-img-wrap">
                <img id="cropperImage" src="" style="max-width:100%;display:block;">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="btnCancelCrop">Batal</button>
            <button type="button" class="btn-primary"   id="btnApplyCrop">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                     style="width:14px;height:14px;display:inline;margin-right:4px;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                Terapkan &amp; Simpan
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL: KAMERA LANGSUNG
══════════════════════════════════════ --}}
<div class="modal-overlay" id="cameraModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Ambil Foto</h3>
            <button type="button" class="btn-modal-close" id="btnCloseCamera">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="camera-body">
            <p>Posisikan wajah Anda di tengah frame, lalu klik <strong>Ambil Foto</strong>.</p>
            <video id="cameraVideo" autoplay playsinline muted></video>
            <canvas id="cameraCanvas"></canvas>
            <div class="camera-actions">
                <button type="button" class="btn-secondary" id="btnCancelCamera" style="flex:0.4;">
                    Batal
                </button>
                <button type="button" class="btn-capture" id="btnCapture">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                    Ambil Foto
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL: VERIFIKASI PASSWORD
     (muncul sebelum user bisa ganti password)
══════════════════════════════════════ --}}
<div class="modal-overlay" id="verifyPasswordModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Verifikasi Identitas</h3>
            <button type="button" class="btn-modal-close" id="btnCloseVerify">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="verify-body">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:44px;height:44px;border-radius:50%;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         style="width:22px;height:22px;">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:#111827;">Konfirmasi Password Sekarang</div>
                    <div style="font-size:12px;color:#6B7280;margin-top:2px;">Masukkan password login kamu saat ini</div>
                </div>
            </div>
            <div class="input-password-wrap">
                <input type="password" id="verifyPasswordInput"
                       class="form-input" placeholder="Password saat ini..."
                       autocomplete="current-password"
                       style="padding-right:44px;">
                <button type="button" class="btn-eye" onclick="togglePassword('verifyPasswordInput','eyeVerify')">
                    <i data-lucide="eye" id="eyeVerify" style="width:18px;height:18px;"></i>
                </button>
            </div>
            <div class="verify-error" id="verifyError">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span id="verifyErrorMsg">Password salah, coba lagi.</span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="btnCancelVerify">Batal</button>
            <button type="button" class="btn-primary" id="btnConfirmVerify"
                    style="display:flex;align-items:center;gap:8px;min-width:120px;justify-content:center;">
                <span id="verifyBtnText">Verifikasi</span>
                <svg id="verifySpinner" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
                     style="display:none;width:15px;height:15px;animation:spin .7s linear infinite;">
                    <circle cx="12" cy="12" r="10" stroke-opacity=".25"/>
                    <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL: KONFIRMASI HAPUS FOTO
══════════════════════════════════════ --}}
<div class="modal-overlay" id="deleteConfirmModal">
    <div class="modal-box">
        <div class="delete-body">
            <div class="delete-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
            </div>
            <h3>Hapus Foto Profil?</h3>
            <p>Foto profil Anda akan dihapus dan diganti dengan inisial nama. Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer" style="justify-content:center;">
            <button type="button" class="btn-secondary" id="btnCancelDelete">Batal</button>
            <button type="button" class="btn-danger-solid" id="btnConfirmDelete">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="width:15px;height:15px;">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
                Hapus Foto
            </button>
        </div>
    </div>
</div>

{{-- ── TOAST ── --}}
<div class="toast" id="toastNotif">
    <svg id="toastIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <span id="toastMsg"></span>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
@endsection

@push('scripts')
<script>
/* ══ VALIDASI FORM PROFIL ══ */
document.getElementById('formProfil').addEventListener('submit', function(e) {
    var valid = true;

    var name  = document.getElementById('fieldName');
    var email = document.getElementById('fieldEmail');

    // Reset
    [name, email].forEach(function(el) { el.classList.remove('is-invalid'); });
    ['errName','errEmail'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) { el.textContent = ''; el.classList.remove('show'); }
    });

    if (!name.value.trim()) {
        name.classList.add('is-invalid');
        showProfilErr('errName', 'Nama lengkap wajib diisi.'); valid = false;
    }
    if (!email.value.trim()) {
        email.classList.add('is-invalid');
        showProfilErr('errEmail', 'Email wajib diisi.'); valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        email.classList.add('is-invalid');
        showProfilErr('errEmail', 'Format email tidak valid.'); valid = false;
    }

    // Validasi password hanya jika form ganti password sudah terbuka
    var pwUnlocked = document.getElementById('pwUnlockedState');
    if (pwUnlocked && pwUnlocked.style.display !== 'none') {
        var newPw  = document.getElementById('newPassword');
        var confPw = document.getElementById('confirmPassword');
        if (newPw && newPw.value && newPw.value.length < 8) {
            newPw.classList.add('is-invalid');
            valid = false;
        }
        if (newPw && confPw && newPw.value && newPw.value !== confPw.value) {
            confPw.classList.add('is-invalid');
            valid = false;
        }
    }

    if (!valid) e.preventDefault();
});

function showProfilErr(id, msg) {
    var el = document.getElementById(id);
    if (el) { el.textContent = msg; el.classList.add('show'); }
}

document.getElementById('fieldName').addEventListener('input', function() {
    this.classList.remove('is-invalid');
    var el = document.getElementById('errName'); if (el) el.classList.remove('show');
});
document.getElementById('fieldEmail').addEventListener('input', function() {
    this.classList.remove('is-invalid');
    var el = document.getElementById('errEmail'); if (el) el.classList.remove('show');
});

lucide.createIcons();

/* ── DATA AWAL ── */
const AVATAR_URL      = "{{ $hasAvatar ? asset('storage/' . auth()->user()->avatar) : '' }}";
const AVATAR_INITIAL  = "{{ $initial }}";
const AVATAR_AJAX_URL = "{{ route('admin.account.update-avatar') }}";
const CSRF_TOKEN      = "{{ csrf_token() }}";
let   currentAvatarSrc = AVATAR_URL;

/* ══════════════════════════════════
   TOAST
══════════════════════════════════ */
function showToast(msg, type = 'success') {
    const toast    = document.getElementById('toastNotif');
    const toastMsg = document.getElementById('toastMsg');
    const icon     = document.getElementById('toastIcon');
    const icons = {
        success: '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        error:   '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>',
    };
    icon.innerHTML       = icons[type] || icons.success;
    toast.className      = 'toast ' + type;
    toastMsg.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3200);
}

/* ══════════════════════════════════
   SPINNER
══════════════════════════════════ */
function showSpinner() { document.getElementById('avatarSpinner').classList.add('show'); }
function hideSpinner() { document.getElementById('avatarSpinner').classList.remove('show'); }

/* ══════════════════════════════════
   AJAX — kirim ke server & update topbar
══════════════════════════════════ */
async function saveAvatarAjax(payload) {
    showSpinner();
    try {
        const res  = await fetch(AVATAR_AJAX_URL, {
            method : 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : CSRF_TOKEN,
                'Accept'       : 'application/json',
            },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        hideSpinner();

        if (data.success) {
            showToast(data.message, 'success');

            // Update avatar di topbar & dropdown layout juga
            if (data.avatar_url) {
                currentAvatarSrc = data.avatar_url;
                updateTopbarAvatar(data.avatar_url);
            } else if (payload.delete_avatar) {
                updateTopbarAvatarInitial();
            }
        } else {
            showToast(data.message || 'Terjadi kesalahan.', 'error');
        }
    } catch (err) {
        hideSpinner();
        showToast('Koneksi gagal. Coba lagi.', 'error');
    }
}

/* Update avatar di topbar layout tanpa reload */
function updateTopbarAvatar(url) {
    // user-avatar di topbar
    document.querySelectorAll('.user-avatar, .dp-av').forEach(function(el) {
        el.classList.add('has-photo');
        el.innerHTML = '<img src="' + url + '" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">';
    });
}
function updateTopbarAvatarInitial() {
    document.querySelectorAll('.user-avatar, .dp-av').forEach(function(el) {
        el.classList.remove('has-photo');
        el.innerHTML = AVATAR_INITIAL;
    });
}

/* ══════════════════════════════════
   DROPDOWN
══════════════════════════════════ */
const btnEditAvatar  = document.getElementById('btnEditAvatar');
const avatarDropdown = document.getElementById('avatarDropdown');

btnEditAvatar.addEventListener('click', function(e) {
    e.stopPropagation();
    avatarDropdown.classList.toggle('open');
});
document.addEventListener('click', function(e) {
    if (!document.getElementById('avatarWrapper').contains(e.target)) {
        avatarDropdown.classList.remove('open');
    }
});
function closeDropdown() { avatarDropdown.classList.remove('open'); }

/* ══════════════════════════════════
   1. LIHAT FOTO
══════════════════════════════════ */
document.getElementById('ddLihatFoto').addEventListener('click', function() {
    closeDropdown();
    if (!currentAvatarSrc) {
        showToast('Belum ada foto profil.', 'error');
        return;
    }
    const wrap = document.getElementById('viewPhotoImgWrap');
    wrap.innerHTML = '';
    const img = document.createElement('img');
    img.src = currentAvatarSrc;
    img.alt = 'Foto Profil';
    wrap.appendChild(img);
    openModal('viewPhotoModal');
});
document.getElementById('btnCloseViewPhoto').addEventListener('click', function() {
    closeModal('viewPhotoModal');
});

/* ══════════════════════════════════
   2. AMBIL FOTO — buka kamera langsung di modal
══════════════════════════════════ */
let cameraStream = null;

document.getElementById('ddAmbilFoto').addEventListener('click', function() {
    closeDropdown();
    openCameraModal();
});

async function openCameraModal() {
    openModal('cameraModal');
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
            audio: false
        });
        document.getElementById('cameraVideo').srcObject = cameraStream;
    } catch (err) {
        closeModal('cameraModal');
        // Fallback: buka file picker kamera (mobile)
        const input = document.getElementById('avatarInput');
        input.setAttribute('capture', 'user');
        input.click();
        showToast('Kamera tidak tersedia, pilih dari galeri.', 'error');
    }
}

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(t => t.stop());
        cameraStream = null;
    }
    document.getElementById('cameraVideo').srcObject = null;
}

document.getElementById('btnCloseCamera').addEventListener('click', function() {
    stopCamera();
    closeModal('cameraModal');
});
document.getElementById('btnCancelCamera').addEventListener('click', function() {
    stopCamera();
    closeModal('cameraModal');
});

document.getElementById('btnCapture').addEventListener('click', function() {
    const video  = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    canvas.width  = video.videoWidth  || 640;
    canvas.height = video.videoHeight || 480;
    canvas.getContext('2d').drawImage(video, 0, 0);

    const base64 = canvas.toDataURL('image/jpeg', 0.9);

    stopCamera();
    closeModal('cameraModal');

    // Langsung buka cropper dari hasil kamera
    const cropperImage = document.getElementById('cropperImage');
    cropperImage.src = base64;
    openModal('cropperModal');
    if (cropper) { cropper.destroy(); }
    cropper = new Cropper(cropperImage, {
        aspectRatio: 1, viewMode: 1,
        background: false, autoCropArea: 1,
        responsive: true, zoomable: true,
    });
});

/* ══════════════════════════════════
   3. UNGGAH FOTO
══════════════════════════════════ */
document.getElementById('ddUnggahFoto').addEventListener('click', function() {
    closeDropdown();
    const input = document.getElementById('avatarInput');
    input.removeAttribute('capture');
    input.click();
});

/* ══════════════════════════════════
   4. HAPUS FOTO
══════════════════════════════════ */
document.getElementById('ddHapusFoto').addEventListener('click', function() {
    closeDropdown();
    if (!currentAvatarSrc) {
        showToast('Belum ada foto profil untuk dihapus.', 'error');
        return;
    }
    openModal('deleteConfirmModal');
});

document.getElementById('btnCancelDelete').addEventListener('click', function() {
    closeModal('deleteConfirmModal');
});

document.getElementById('btnConfirmDelete').addEventListener('click', async function() {
    closeModal('deleteConfirmModal');

    // Optimistic UI: reset ke inisial
    const preview = document.getElementById('avatarPreview');
    preview.style.backgroundImage = '';
    preview.style.backgroundColor = '#6366f1';
    let initial = document.getElementById('avatarInitial');
    if (initial) {
        initial.style.display = '';
    } else {
        initial = document.createElement('span');
        initial.id = 'avatarInitial';
        initial.textContent = AVATAR_INITIAL;
        preview.appendChild(initial);
    }
    currentAvatarSrc = '';

    await saveAvatarAjax({ delete_avatar: '1' });
});

/* ══════════════════════════════════
   CROPPER (dari file unggah)
══════════════════════════════════ */
let cropper;
const avatarInput  = document.getElementById('avatarInput');
const cropperImage = document.getElementById('cropperImage');

avatarInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        showToast('Ukuran file maksimal adalah 2MB.', 'error');
        this.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        cropperImage.src = e.target.result;
        openModal('cropperModal');
        if (cropper) { cropper.destroy(); }
        cropper = new Cropper(cropperImage, {
            aspectRatio: 1, viewMode: 1,
            background: false, autoCropArea: 1,
            responsive: true, zoomable: true,
        });
    };
    reader.readAsDataURL(file);
});

document.getElementById('btnCloseCropper').addEventListener('click', cancelCrop);
document.getElementById('btnCancelCrop').addEventListener('click', cancelCrop);

function cancelCrop() {
    closeModal('cropperModal');
    if (cropper) { cropper.destroy(); cropper = null; }
    avatarInput.value = '';
}

document.getElementById('btnApplyCrop').addEventListener('click', async function() {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
    const base64 = canvas.toDataURL('image/jpeg', 0.85);

    // Optimistic UI: update preview
    const preview = document.getElementById('avatarPreview');
    const initial = document.getElementById('avatarInitial');
    preview.style.backgroundImage = `url('${base64}')`;
    preview.style.backgroundColor = 'transparent';
    if (initial) { initial.style.display = 'none'; }
    currentAvatarSrc = base64;

    closeModal('cropperModal');
    cropper.destroy();
    cropper = null;
    avatarInput.value = '';

    // Auto-save ke server (tidak perlu klik Simpan Perubahan)
    await saveAvatarAjax({ avatar_cropped: base64 });
});

/* ══════════════════════════════════
   MODAL HELPERS
══════════════════════════════════ */
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target !== overlay) return;
        if (overlay.id === 'cameraModal') { stopCamera(); }
        if (overlay.id === 'cropperModal') { cancelCrop(); return; }
        overlay.classList.remove('open');
    });
});

/* ══════════════════════════════════
   VERIFY PASSWORD SEBELUM GANTI PASSWORD
   — AJAX check ke route verify-password
══════════════════════════════════ */
const VERIFY_URL = "{{ route('admin.account.verify-password') }}";

document.getElementById('btnOpenVerify').addEventListener('click', function() {
    document.getElementById('verifyPasswordInput').value = '';
    document.getElementById('verifyError').classList.remove('show');
    openModal('verifyPasswordModal');
    setTimeout(() => document.getElementById('verifyPasswordInput').focus(), 200);
});

document.getElementById('btnCloseVerify').addEventListener('click', function() {
    closeModal('verifyPasswordModal');
});
document.getElementById('btnCancelVerify').addEventListener('click', function() {
    closeModal('verifyPasswordModal');
});

// Enter key di input verifikasi
document.getElementById('verifyPasswordInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); doVerify(); }
});

document.getElementById('btnConfirmVerify').addEventListener('click', doVerify);

async function doVerify() {
    const input   = document.getElementById('verifyPasswordInput');
    const errBox  = document.getElementById('verifyError');
    const errMsg  = document.getElementById('verifyErrorMsg');
    const btnText = document.getElementById('verifyBtnText');
    const spinner = document.getElementById('verifySpinner');
    const pw      = input.value.trim();

    if (!pw) {
        errBox.classList.add('show');
        errMsg.textContent = 'Password tidak boleh kosong.';
        input.focus();
        return;
    }

    // Loading state
    btnText.textContent = 'Memverifikasi...';
    spinner.style.display = 'block';
    document.getElementById('btnConfirmVerify').style.pointerEvents = 'none';
    errBox.classList.remove('show');

    try {
        const res  = await fetch(VERIFY_URL, {
            method : 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : CSRF_TOKEN,
                'Accept'       : 'application/json',
            },
            body: JSON.stringify({ password: pw }),
        });
        const data = await res.json();

        btnText.textContent = 'Verifikasi';
        spinner.style.display = 'none';
        document.getElementById('btnConfirmVerify').style.pointerEvents = '';

        if (data.success) {
            // Simpan password ke hidden input agar ikut terkirim saat form submit
            document.getElementById('currentPasswordHidden').value = pw;

            closeModal('verifyPasswordModal');

            // Tampilkan form ganti password
            document.getElementById('pwLockedState').style.display   = 'none';
            document.getElementById('pwUnlockedState').style.display = 'block';
            lucide.createIcons();

            showToast('Identitas terverifikasi! Silakan ganti password.', 'success');
        } else {
            errBox.classList.add('show');
            errMsg.textContent = data.message || 'Password salah, coba lagi.';
            input.select();
        }
    } catch (err) {
        btnText.textContent = 'Verifikasi';
        spinner.style.display = 'none';
        document.getElementById('btnConfirmVerify').style.pointerEvents = '';
        errBox.classList.add('show');
        errMsg.textContent = 'Koneksi gagal. Coba lagi.';
    }
}

/* ══════════════════════════════════
   TOGGLE PASSWORD
══════════════════════════════════ */
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
}

/* ══════════════════════════════════
   PASSWORD STRENGTH
══════════════════════════════════ */
function checkStrength(val) {
    const wrap  = document.getElementById('strengthWrap');
    const label = document.getElementById('strengthLabel');
    const segs  = ['seg1','seg2','seg3','seg4'].map(id => document.getElementById(id));

    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'block';

    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#EF4444','#F97316','#EAB308','#22C55E'];
    const labels = ['Sangat Lemah','Lemah','Sedang','Kuat'];
    segs.forEach((s, i) => { s.style.background = i < score ? colors[score-1] : '#E5E7EB'; });
    label.textContent = labels[score-1] ?? '';
    label.style.color = colors[score-1] ?? '#9CA3AF';
    checkConfirm();
}

/* ══════════════════════════════════
   CONFIRM PASSWORD MATCH
══════════════════════════════════ */
function checkConfirm() {
    const newVal     = document.getElementById('newPassword').value;
    const confirmVal = document.getElementById('confirmPassword').value;
    const note       = document.getElementById('confirmNote');
    if (!confirmVal) { note.style.display = 'none'; return; }
    note.style.display = 'block';
    if (newVal === confirmVal) {
        note.textContent = '✓ Password cocok';
        note.style.color = '#16A34A';
    } else {
        note.textContent = '✗ Password tidak cocok';
        note.style.color = '#DC2626';
    }
}
</script>
@endpush