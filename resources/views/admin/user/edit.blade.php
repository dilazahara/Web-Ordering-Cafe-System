@extends('layouts.admin')

@section('title', 'Edit User')

@push('styles')
<style>
.eu-wrap { max-width: 540px; margin: 0 auto; }

/* ── CARD ── */
.eu-card {
    background: white; border-radius: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 24px rgba(0,0,0,0.05);
    overflow: hidden;
}

/* ── CARD HEADER ── */
.eu-head {
    padding: 24px 28px 20px;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, #eef2ff 0%, #fff 60%);
}
.eu-head h2 { font-size: 20px; font-weight: 800; color: #111827; }
.eu-head p  { font-size: 13px; color: #6b7280; margin-top: 4px; }

/* ── USER INFO BOX ── */
.eu-info {
    display: flex; align-items: center; gap: 14px;
    background: #f9fafb; border: 1px solid #f0f0f0;
    border-radius: 14px; padding: 14px 16px;
    margin: 24px 28px 0;
}
.eu-av {
    width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, #818cf8, #4f46e5);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 18px; font-weight: 800;
    box-shadow: 0 4px 12px rgba(99,102,241,0.25);
}
.eu-meta h4 { font-size: 14px; font-weight: 700; color: #111827; }
.eu-meta p  { font-size: 12px; color: #9ca3af; margin-top: 2px; }
.eu-id      { font-size: 11px; font-family: monospace; color: #6366f1; font-weight: 700; margin-top: 3px; }

/* ── SECTION ── */
.eu-section { padding: 20px 28px; border-bottom: 1px solid #f8f9fc; }
.eu-section:last-of-type { border-bottom: none; }
.eu-section-title {
    font-size: 11px; font-weight: 700; color: #9ca3af;
    text-transform: uppercase; letter-spacing: .8px; margin-bottom: 16px;
}

/* ── ROLE GRID ── */
.role-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
}
.role-opt { position: relative; cursor: pointer; display: block; }
.role-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.role-card {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 14px; border-radius: 13px;
    border: 2px solid #e5e7eb; background: #f9fafb;
    transition: all .18s; cursor: pointer;
}
.role-icon {
    width: 34px; height: 34px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.role-icon i { width: 17px; height: 17px; }
.role-label { font-size: 13px; font-weight: 700; color: #374151; }
.role-desc  { font-size: 11px; color: #9ca3af; margin-top: 1px; }

.role-opt.r-admin  input:checked + .role-card { border-color: #6366f1; background: #eef2ff; }
.role-opt.r-kasir  input:checked + .role-card { border-color: #f97316; background: #fff7ed; }
.role-opt.r-dapur  input:checked + .role-card { border-color: #22c55e; background: #f0fdf4; }
.role-opt.r-pelayan input:checked + .role-card { border-color: #06b6d4; background: #ecfeff; }

.role-opt.r-admin   .role-icon { background: #eef2ff; }
.role-opt.r-kasir   .role-icon { background: #fff7ed; }
.role-opt.r-dapur   .role-icon { background: #f0fdf4; }
.role-opt.r-pelayan .role-icon { background: #ecfeff; }

.role-opt.r-admin   .role-icon i { stroke: #6366f1; }
.role-opt.r-kasir   .role-icon i { stroke: #f97316; }
.role-opt.r-dapur   .role-icon i { stroke: #22c55e; }
.role-opt.r-pelayan .role-icon i { stroke: #06b6d4; }

.role-check {
    margin-left: auto; width: 18px; height: 18px;
    border-radius: 50%; border: 2px solid #e5e7eb;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: all .18s;
}
.role-opt.r-admin   input:checked + .role-card .role-check { border-color: #6366f1; background: #6366f1; }
.role-opt.r-kasir   input:checked + .role-card .role-check { border-color: #f97316; background: #f97316; }
.role-opt.r-dapur   input:checked + .role-card .role-check { border-color: #22c55e; background: #22c55e; }
.role-opt.r-pelayan input:checked + .role-card .role-check { border-color: #06b6d4; background: #06b6d4; }
.role-check i { width: 10px; height: 10px; stroke: white; display: none; }
.role-opt input:checked + .role-card .role-check i { display: block; }

/* ── PASSWORD TOGGLE AREA ── */
.pw-toggle-btn {
    width: 100%; padding: 11px 16px; border-radius: 11px;
    border: 1.5px dashed #e5e7eb; background: #f9fafb;
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; font-weight: 600; color: #6366f1;
    cursor: pointer; transition: all .2s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.pw-toggle-btn:hover { background: #eef2ff; border-color: #c7d2fe; }
.pw-toggle-btn svg { width: 16px; height: 16px; flex-shrink: 0; }
.pw-toggle-btn .pw-toggle-arrow {
    margin-left: auto; width: 16px; height: 16px;
    transition: transform .2s;
}
.pw-toggle-btn.open .pw-toggle-arrow { transform: rotate(180deg); }

/* ── PASSWORD FIELDS ── */
.pw-fields {
    display: none; margin-top: 14px;
}
.pw-fields.open { display: block; }

.pw-verified-note {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 14px; border-radius: 10px;
    background: #f0fdf4; border: 1px solid #bbf7d0;
    color: #15803d; font-size: 12px; font-weight: 600;
    margin-bottom: 14px;
}
.pw-verified-note svg { width: 14px; height: 14px; flex-shrink: 0; }

.eu-form-group { margin-bottom: 14px; }
.eu-form-label {
    display: block; margin-bottom: 6px;
    font-size: 12px; font-weight: 700; color: #6b7280;
    text-transform: uppercase; letter-spacing: .5px;
}
.eu-input-wrap { position: relative; }
.eu-input {
    width: 100%; padding: 11px 42px 11px 14px;
    border: 1.5px solid #e5e7eb; border-radius: 11px;
    background: #f9fafb; font-size: 14px; color: #111827;
    outline: none; transition: .2s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.eu-input:focus { border-color: #6366f1; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.eu-input::placeholder { color: #9ca3af; }
.eu-input.is-invalid { border-color: #ef4444 !important; background: #fff5f5 !important; }
.eu-field-error { font-size: 12px; font-weight: 600; color: #dc2626; margin-top: 5px; display: none; }
.eu-eye {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #9ca3af;
    display: flex; align-items: center; transition: color .15s;
}
.eu-eye:hover { color: #6366f1; }
.eu-eye i { width: 16px; height: 16px; }

/* ── PASSWORD STRENGTH ── */
.pw-strength { margin-top: 7px; }
.pw-strength-bar { display: flex; gap: 4px; margin-bottom: 4px; }
.pw-seg { height: 4px; flex: 1; border-radius: 99px; background: #e5e7eb; transition: background .3s; }
.pw-strength-label { font-size: 11px; font-weight: 600; color: #9ca3af; }

.pw-match-note {
    font-size: 12px; font-weight: 600; margin-top: 5px; display: none;
}

/* ── ALERT ── */
.eu-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 13px 16px; border-radius: 11px;
    background: #fef2f2; border: 1px solid #fecaca;
    color: #dc2626; font-size: 13px; font-weight: 600;
    margin: 16px 28px 0;
}
.eu-alert i { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

/* ── FOOTER ── */
.eu-footer {
    padding: 20px 28px;
    background: #fafafa; border-top: 1px solid #f0f0f0;
    display: flex; gap: 12px;
}
.eu-btn {
    flex: 1; padding: 12px; border-radius: 12px; border: none;
    font-size: 14px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    font-family: 'Plus Jakarta Sans', sans-serif; transition: all .2s;
    text-decoration: none; text-align: center;
}
.eu-btn i { width: 15px; height: 15px; }
.eu-btn-save {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white; box-shadow: 0 6px 16px rgba(99,102,241,0.25);
}
.eu-btn-save:hover { transform: translateY(-1px); box-shadow: 0 10px 20px rgba(99,102,241,0.3); }
.eu-btn-back { background: white; color: #374151; border: 1.5px solid #e5e7eb; }
.eu-btn-back:hover { background: #f9fafb; }

@media (max-width: 480px) {
    .role-grid { grid-template-columns: 1fr; }
    .eu-footer  { flex-direction: column-reverse; }
}
</style>
@endpush

@section('content')
<div class="eu-wrap">

    {{-- ALERT ERROR --}}
    @if($errors->any())
    <div class="eu-alert" style="margin-bottom:16px;margin-left:0;margin-right:0;">
        <i data-lucide="alert-circle"></i>
        <div>
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:16px;">
        <i data-lucide="check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="eu-card">

        {{-- HEAD --}}
        <div class="eu-head">
            <h2>Edit User</h2>
            <p>Ubah role dan / atau reset password akun ini</p>
        </div>

        {{-- USER INFO --}}
        <div class="eu-info">
            <div class="eu-av">{{ strtoupper(substr($item->name, 0, 1)) }}</div>
            <div class="eu-meta">
                <h4>{{ $item->name }}</h4>
                <p>{{ $item->email }}</p>
                @if(isset($item->formatted_id))
                    <div class="eu-id">{{ $item->formatted_id }}</div>
                @endif
            </div>
        </div>

        <form action="/admin/user/update/{{ $item->id }}" method="POST" id="userEditForm" novalidate>
            @csrf

            {{-- ── ROLE ── --}}
            <div class="eu-section">
                <p class="eu-section-title">Atur Role</p>
                <div class="role-grid">

                    <label class="role-opt r-admin">
                        <input type="radio" name="role" value="admin"
                            {{ $item->role == 'admin' ? 'checked' : '' }}>
                        <div class="role-card">
                            <div class="role-icon"><i data-lucide="shield-check"></i></div>
                            <div>
                                <div class="role-label">Admin</div>
                                <div class="role-desc">Akses penuh</div>
                            </div>
                            <div class="role-check"><i data-lucide="check"></i></div>
                        </div>
                    </label>

                    <label class="role-opt r-kasir">
                        <input type="radio" name="role" value="kasir"
                            {{ $item->role == 'kasir' ? 'checked' : '' }}>
                        <div class="role-card">
                            <div class="role-icon"><i data-lucide="credit-card"></i></div>
                            <div>
                                <div class="role-label">Kasir</div>
                                <div class="role-desc">Transaksi & bayar</div>
                            </div>
                            <div class="role-check"><i data-lucide="check"></i></div>
                        </div>
                    </label>

                    <label class="role-opt r-dapur">
                        <input type="radio" name="role" value="dapur"
                            {{ $item->role == 'dapur' ? 'checked' : '' }}>
                        <div class="role-card">
                            <div class="role-icon"><i data-lucide="chef-hat"></i></div>
                            <div>
                                <div class="role-label">Dapur</div>
                                <div class="role-desc">Kelola pesanan masuk</div>
                            </div>
                            <div class="role-check"><i data-lucide="check"></i></div>
                        </div>
                    </label>

                    <label class="role-opt r-pelayan">
                        <input type="radio" name="role" value="pelayan"
                            {{ $item->role == 'pelayan' ? 'checked' : '' }}>
                        <div class="role-card">
                            <div class="role-icon"><i data-lucide="user-check"></i></div>
                            <div>
                                <div class="role-label">Pelayan</div>
                                <div class="role-desc">Antar & layani meja</div>
                            </div>
                            <div class="role-check"><i data-lucide="check"></i></div>
                        </div>
                    </label>

                </div>
            </div>

            {{-- ── GANTI PASSWORD (opsional, collapsible) ── --}}
            <div class="eu-section">
                <p class="eu-section-title">Reset Password</p>

                <button type="button" class="pw-toggle-btn" id="pwToggleBtn" onclick="togglePwSection()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Klik untuk reset password user ini
                    <svg class="pw-toggle-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <div class="pw-fields" id="pwFields">

                    <div class="pw-verified-note">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        Sebagai admin, kamu bisa langsung reset password tanpa password lama.
                    </div>

                    {{-- PASSWORD BARU --}}
                    <div class="eu-form-group">
                        <label class="eu-form-label">Password Baru</label>
                        <div class="eu-input-wrap">
                            <input type="password" name="password" id="pwNew"
                                   class="eu-input" placeholder="Min. 6 karakter"
                                   autocomplete="new-password"
                                   oninput="checkPwStrength(this.value)">
                                   @error('password')
    <p class="eu-field-error" style="display:block;">{{ $message }}</p>
@enderror
<p class="eu-field-error" id="errorPwNew"></p>
                            <button type="button" class="eu-eye"
                                    onclick="toggleEye('pwNew','eyePwNew')">
                                <i data-lucide="eye" id="eyePwNew"></i>
                            </button>
                        </div>
                        <div class="pw-strength" id="pwStrengthWrap" style="display:none;">
                            <div class="pw-strength-bar">
                                <div class="pw-seg" id="ps1"></div>
                                <div class="pw-seg" id="ps2"></div>
                                <div class="pw-seg" id="ps3"></div>
                                <div class="pw-seg" id="ps4"></div>
                            </div>
                            <span class="pw-strength-label" id="pwStrengthLabel"></span>
                        </div>
                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="eu-form-group" style="margin-bottom:0;">
                        <label class="eu-form-label">Konfirmasi Password Baru</label>
                        <div class="eu-input-wrap">
                            <input type="password" name="password_confirmation" id="pwConfirm"
                                   class="eu-input" placeholder="Ulangi password baru"
                                   autocomplete="new-password"
                                   oninput="checkPwMatch()">
                            <button type="button" class="eu-eye"
                                    onclick="toggleEye('pwConfirm','eyePwConfirm')">
                                <i data-lucide="eye" id="eyePwConfirm"></i>
                            </button>
                        </div>
                        <span class="pw-match-note" id="pwMatchNote"></span>

                        @error('password_confirmation')
    <p class="eu-field-error" style="display:block;">{{ $message }}</p>
@enderror
<p class="eu-field-error" id="errorPwConfirm"></p>

                    </div>

                </div>
            </div>

            {{-- ── FOOTER ── --}}
            <div class="eu-footer">
                <button type="submit" class="eu-btn eu-btn-save">
                    <i data-lucide="save"></i> Simpan Perubahan
                </button>
                <a href="/admin/user" class="eu-btn eu-btn-back">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
lucide.createIcons();

/* ── TOGGLE SECTION PASSWORD ── */
function togglePwSection() {
    const btn    = document.getElementById('pwToggleBtn');
    const fields = document.getElementById('pwFields');
    const isOpen = fields.classList.toggle('open');
    btn.classList.toggle('open', isOpen);
    if (isOpen) {
        setTimeout(() => document.getElementById('pwNew').focus(), 150);
    } else {
        // Kosongkan field jika ditutup
        document.getElementById('pwNew').value      = '';
        document.getElementById('pwConfirm').value  = '';
        document.getElementById('pwStrengthWrap').style.display = 'none';
        document.getElementById('pwMatchNote').style.display    = 'none';
    }
}

/* ── TOGGLE EYE ── */
function toggleEye(inputId, iconId) {
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

/* ── PASSWORD STRENGTH ── */
function checkPwStrength(val) {
    const wrap  = document.getElementById('pwStrengthWrap');
    const label = document.getElementById('pwStrengthLabel');
    const segs  = ['ps1','ps2','ps3','ps4'].map(id => document.getElementById(id));

    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'block';

    let score = 0;
    if (val.length >= 6)           score++;
    if (val.length >= 10)          score++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    const labels = ['Lemah','Sedang','Kuat','Sangat Kuat'];
    segs.forEach((s, i) => { s.style.background = i < score ? colors[score-1] : '#e5e7eb'; });
    label.textContent = labels[score-1] ?? '';
    label.style.color = colors[score-1] ?? '#9ca3af';
    checkPwMatch();
}

/* ── PASSWORD MATCH ── */
function checkPwMatch() {
    const newVal     = document.getElementById('pwNew').value;
    const confirmVal = document.getElementById('pwConfirm').value;
    const note       = document.getElementById('pwMatchNote');
    if (!confirmVal) { note.style.display = 'none'; return; }
    note.style.display = 'block';
    if (newVal === confirmVal) {
        note.textContent = '✓ Password cocok';
        note.style.color = '#16a34a';
    } else {
        note.textContent = '✗ Password tidak cocok';
        note.style.color = '#dc2626';
    }
}

/* ── AUTO BUKA SECTION JIKA ADA ERROR PASSWORD ── */
@if($errors->has('password') || $errors->has('password_confirmation'))
    document.addEventListener('DOMContentLoaded', function() {
        const btn    = document.getElementById('pwToggleBtn');
        const fields = document.getElementById('pwFields');
        fields.classList.add('open');
        btn.classList.add('open');
    });
@endif

/* ── VALIDASI SUBMIT ── */
document.getElementById('userEditForm').addEventListener('submit', function(e) {
    const isOpen = document.getElementById('pwFields').classList.contains('open');
    if (!isOpen) return; // section password tertutup = tidak perlu validasi

    let valid = true;
    const pwNew     = document.getElementById('pwNew');
    const pwConfirm = document.getElementById('pwConfirm');
    const errNew    = document.getElementById('errorPwNew');
    const errConfirm= document.getElementById('errorPwConfirm');

    // Reset
    pwNew.classList.remove('is-invalid');
    pwConfirm.classList.remove('is-invalid');
    errNew.style.display     = 'none';
    errConfirm.style.display = 'none';

    if (!pwNew.value) {
        pwNew.classList.add('is-invalid');
        errNew.textContent   = 'Password baru wajib diisi.';
        errNew.style.display = 'block';
        valid = false;
    } else if (pwNew.value.length < 6) {
        pwNew.classList.add('is-invalid');
        errNew.textContent   = 'Password minimal 6 karakter.';
        errNew.style.display = 'block';
        valid = false;
    }

    if (!pwConfirm.value) {
        pwConfirm.classList.add('is-invalid');
        errConfirm.textContent   = 'Konfirmasi password wajib diisi.';
        errConfirm.style.display = 'block';
        valid = false;
    } else if (pwNew.value !== pwConfirm.value) {
        pwConfirm.classList.add('is-invalid');
        errConfirm.textContent   = 'Konfirmasi password tidak cocok.';
        errConfirm.style.display = 'block';
        valid = false;
    }

    if (!valid) e.preventDefault();
});

/* ── CLEAR ERROR SAAT INPUT ── */
document.getElementById('pwNew').addEventListener('input', function() {
    if (this.value) {
        this.classList.remove('is-invalid');
        document.getElementById('errorPwNew').style.display = 'none';
    }
});
document.getElementById('pwConfirm').addEventListener('input', function() {
    if (this.value) {
        this.classList.remove('is-invalid');
        document.getElementById('errorPwConfirm').style.display = 'none';
    }
});

</script>
@endpush