@extends('layouts.admin')

@section('title', 'Tambah User')

@push('styles')
<style>
/* ════ CSS KHUSUS HALAMAN CREATE USER ════ */
.create-container { max-width: 680px; margin: auto; padding: 20px 0; }

/* BACK LINK */
.back-link {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: var(--text-base); font-weight: 600; color: var(--text-light);
    text-decoration: none; margin-bottom: 24px; transition: all .2s;
}
.back-link:hover { color: var(--text-dark); transform: translateX(-2px); }

/* HEADER HALAMAN */
.page-header { margin-bottom: 28px; }
.page-header h2 { font-size: var(--text-3xl); color: var(--text-dark); font-weight: 800; }
.page-header p  { margin-top: 5px; color: var(--text-light); font-size: 14px; }

/* SECTION CARD */
.form-section-card {
    background: var(--bg-white); 
    padding: 28px;
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    margin-bottom: 24px;
    transition: box-shadow 0.3s ease;
}
.form-section-card:hover {
    box-shadow: var(--shadow-md);
}

/* CARD HEADER */
.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border-light);
}
.section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: var(--radius-lg);
    background: var(--bg);
    color: var(--text-mid);
}
.section-title h3 {
    font-size: var(--text-md);
    font-weight: 700;
    color: var(--text-dark);
}
.section-title p {
    font-size: var(--text-xs);
    color: var(--text-muted);
    margin-top: 2px;
}

/* FORM ELEMENTS */
.form-group { margin-bottom: 20px; }
.form-group:last-child { margin-bottom: 0; }
.form-label { display: block; margin-bottom: 8px; font-size: var(--text-base); font-weight: 700; color: var(--text-dark); }

.input-wrap { position: relative; }
.form-input {
    width: 100%; padding: 12px 16px;
    border-radius: var(--radius-lg); border: 1.5px solid var(--border);
    background: var(--bg); font-size: var(--text-md); color: var(--text-dark);
    outline: none; transition: .2s; font-family: var(--font);
}
.form-input:focus {
    border-color: #f97316; background: white;
    box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
}
.form-input::placeholder { color: var(--text-muted); }
.form-input.is-error { border-color: #ef4444 !important; background: #fef2f2 !important; box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }

/* ERRORS */
.field-error {
    display: flex; align-items: center; gap: 5px;
    margin-top: 6px; font-size: var(--text-sm); font-weight: 600; color: #dc2626;
    animation: slideDown .2s ease-out;
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-4px); }
    to   { opacity: 1; transform: translateY(0); }
}

.eye-btn {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: var(--text-muted);
    display: flex; align-items: center;
}

/* ROLE CARDS */
.role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.role-opt { position: relative; cursor: pointer; }
.role-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.role-card {
    display: flex; align-items: center; gap: 12px;
    padding: 16px; border-radius: var(--radius-xl);
    border: 2px solid var(--border); background: var(--bg);
    transition: all .18s; cursor: pointer;
    height: 100%;
}
.role-icon {
    width: 40px; height: 40px; border-radius: var(--radius-lg);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.role-label { font-size: 14px; font-weight: 700; color: var(--text-mid); }
.role-desc   { font-size: var(--text-xs); color: var(--text-muted); margin-top: 2px; }

.role-opt.r-admin  input:checked + .role-card  { border-color: var(--primary); background: var(--primary-light); }
.role-opt.r-kasir  input:checked + .role-card  { border-color: #f97316; background: #fff7ed; }
.role-opt.r-dapur  input:checked + .role-card  { border-color: #22c55e; background: #f0fdf4; }
.role-opt.r-pelayan input:checked + .role-card { border-color: #06b6d4; background: #ecfeff; }

.role-opt.r-admin   .role-icon { background: var(--primary-light); color: var(--primary); }
.role-opt.r-kasir   .role-icon { background: #fff7ed; color: #f97316; }
.role-opt.r-dapur   .role-icon { background: #f0fdf4; color: #22c55e; }
.role-opt.r-pelayan .role-icon { background: #ecfeff; color: #06b6d4; }

.role-check {
    margin-left: auto; width: 18px; height: 18px;
    border-radius: 50%; border: 2px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: all .18s;
}
.role-opt.r-admin   input:checked + .role-card .role-check { border-color: var(--primary); background: var(--primary); }
.role-opt.r-kasir   input:checked + .role-card .role-check { border-color: #f97316; background: #f97316; }
.role-opt.r-dapur   input:checked + .role-card .role-check { border-color: #22c55e; background: #22c55e; }
.role-opt.r-pelayan input:checked + .role-card .role-check { border-color: #06b6d4; background: #06b6d4; }
.role-check i { width: 10px; height: 10px; color: white; display: none; }
.role-opt input:checked + .role-card .role-check i { display: block; }

.role-grid.is-error .role-card { border-color: #fca5a5; }
.role-error {
    display: flex; align-items: center; gap: 5px;
    margin-top: 12px;
    font-size: var(--text-sm); font-weight: 600; color: #dc2626;
}

/* ALERTS */
.alert-error {
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
    border-radius: var(--radius-lg); padding: 14px 16px; font-size: var(--text-base);
    font-weight: 600; margin-bottom: 24px;
    display: flex; align-items: flex-start; gap: 10px; line-height: 1.5;
}
.alert-error ul { margin: 4px 0 0 4px; padding-left: 16px; }

/* ACTION BUTTONS CARD */
.action-card {
    background: var(--bg-white);
    padding: 20px 28px;
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-sm);
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
.button-group { display: flex; gap: 12px; width: 100%; max-width: 320px; margin-left: auto; }
.btn {
    flex: 1; padding: 13px; border-radius: var(--radius-xl); border: none;
    font-size: var(--text-md); font-weight: 700; cursor: pointer;
    text-decoration: none; text-align: center; transition: all .2s;
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    font-family: var(--font);
}
.btn-save {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white; box-shadow: 0 4px 12px rgba(249,115,22,0.2);
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(249,115,22,0.3); }
.btn-back { background: white; color: var(--text-mid); border: 1.5px solid var(--border); }
.btn-back:hover { background: var(--bg); }

/* STATUS CARD QUICK FIX */
.status-badge-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f0fdf4;
    padding: 12px 16px;
    border-radius: var(--radius-lg);
    border: 1px solid #bbf7d0;
    color: #16a34a;
    font-size: var(--text-sm);
    font-weight: 600;
}

@media(max-width:480px) {
    .role-grid { grid-template-columns: 1fr; }
    .action-card { padding: 20px; }
    .button-group { flex-direction: column; max-width: 100%; }
}
</style>
@endpush

@section('content')
<div class="create-container">

    <div class="page-header">
        <h2>Tambah User Baru</h2>
        <p>Silakan isi informasi detail di bawah ini untuk membuat akun baru.</p>
    </div>

    @if($errors->any())
    <div class="alert-error">
        <i data-lucide="alert-circle" style="width:18px;height:18px;flex-shrink:0;"></i>
        <div>
            <strong>Gagal menyimpan! Periksa isian berikut:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="/admin/user/store" method="POST" id="userCreateForm" novalidate>
        @csrf

        <div class="form-section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i data-lucide="user" style="width:18px;height:18px;"></i>
                </div>
                <div class="section-title">
                    <h3>Informasi Pengguna</h3>
                    <p>Nama lengkap dari user yang akan didaftarkan</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" id="fieldName"
                    class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                    placeholder="Contoh: Tegar" value="{{ old('name') }}">
                
                @error('name')
                    <div class="field-error"><i data-lucide="alert-circle" style="width:13px;height:13px;"></i> {{ $message }}</div>
                @enderror
                <div class="field-error" id="errorName" style="display:none;"><i data-lucide="alert-circle" style="width:13px;height:13px;"></i> <span></span></div>
            </div>
        </div>

        <div class="form-section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i data-lucide="key-round" style="width:18px;height:18px;"></i>
                </div>
                <div class="section-title">
                    <h3>Data Kredensial Akun</h3>
                    <p>Digunakan untuk kebutuhan autentikasi masuk ke sistem</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email <span style="color:#ef4444;">*</span></label>
                <input type="email" name="email" id="fieldEmail"
                    class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                    placeholder="Contoh: tegar@example.com" value="{{ old('email') }}">
                
                @error('email')
                    <div class="field-error"><i data-lucide="alert-circle" style="width:13px;height:13px;"></i> {{ $message }}</div>
                @enderror
                <div class="field-error" id="errorEmail" style="display:none;"><i data-lucide="alert-circle" style="width:13px;height:13px;"></i> <span></span></div>
            </div>

            <div class="form-group">
                <label class="form-label">Password <span style="color:#ef4444;">*</span></label>
                <div class="input-wrap">
                    <input type="password" name="password" id="pwInput"
                        class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                        style="padding-right:42px;" placeholder="Minimal 6 karakter">
                    <button type="button" class="eye-btn" onclick="togglePw()">
                        <i data-lucide="eye" id="eyeIcon" style="width:16px;height:16px;"></i>
                    </button>
                </div>
                
                @error('password')
                    <div class="field-error"><i data-lucide="alert-circle" style="width:13px;height:13px;"></i> {{ $message }}</div>
                @enderror
                <div class="field-error" id="errorPassword" style="display:none;"><i data-lucide="alert-circle" style="width:13px;height:13px;"></i> <span></span></div>
            </div>
        </div>

        <div class="form-section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i data-lucide="fingerprint" style="width:18px;height:18px;"></i>
                </div>
                <div class="section-title">
                    <h3>Role Pengguna</h3>
                    <p>Pilih hak akses kontrol yang sesuai untuk user ini</p>
                </div>
            </div>

            <div class="role-grid {{ $errors->has('role') ? 'is-error' : '' }}">
                <label class="role-opt r-admin">
                    <input type="radio" name="role" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
                    <div class="role-card">
                        <div class="role-icon"><i data-lucide="shield-check" style="width:18px;height:18px;"></i></div>
                        <div>
                            <div class="role-label">Admin</div>
                            <div class="role-desc">Akses penuh</div>
                        </div>
                        <div class="role-check"><i data-lucide="check"></i></div>
                    </div>
                </label>

                <label class="role-opt r-kasir">
                    <input type="radio" name="role" value="kasir" {{ old('role', 'kasir') == 'kasir' ? 'checked' : '' }}>
                    <div class="role-card">
                        <div class="role-icon"><i data-lucide="credit-card" style="width:18px;height:18px;"></i></div>
                        <div>
                            <div class="role-label">Kasir</div>
                            <div class="role-desc">Transaksi & bayar</div>
                        </div>
                        <div class="role-check"><i data-lucide="check"></i></div>
                    </div>
                </label>

                <label class="role-opt r-dapur">
                    <input type="radio" name="role" value="dapur" {{ old('role') == 'dapur' ? 'checked' : '' }}>
                    <div class="role-card">
                        <div class="role-icon"><i data-lucide="chef-hat" style="width:18px;height:18px;"></i></div>
                        <div>
                            <div class="role-label">Dapur</div>
                            <div class="role-desc">Kelola pesanan masuk</div>
                        </div>
                        <div class="role-check"><i data-lucide="check"></i></div>
                    </div>
                </label>

                <label class="role-opt r-pelayan">
                    <input type="radio" name="role" value="pelayan" {{ old('role') == 'pelayan' ? 'checked' : '' }}>
                    <div class="role-card">
                        <div class="role-icon"><i data-lucide="user-check" style="width:18px;height:18px;"></i></div>
                        <div>
                            <div class="role-label">Pelayan</div>
                            <div class="role-desc">Antar & layani meja</div>
                        </div>
                        <div class="role-check"><i data-lucide="check"></i></div>
                    </div>
                </label>
            </div>

            @error('role')
                <div class="role-error"><i data-lucide="alert-circle" style="width:13px;height:13px;"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="form-section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i data-lucide="activity" style="width:18px;height:18px;"></i>
                </div>
                <div class="section-title">
                    <h3>Status Pengguna</h3>
                    <p>Status default untuk user baru yang dibuat</p>
                </div>
            </div>
            
            <div class="status-badge-wrapper">
                <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
                <span>Secara default, akun baru yang dibuat akan langsung berstatus Aktif.</span>
            </div>
        </div>

        <div class="action-card">
            <div class="button-group">
                <a href="/admin/user" class="btn btn-back">Batal</a>
                <button type="submit" class="btn btn-save">
                    <i data-lucide="user-plus" style="width:16px;height:16px;"></i> Buat Akun
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
function togglePw() {
    const input = document.getElementById('pwInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
}

/* ── VALIDASI SUBMIT ── */
document.getElementById('userCreateForm').addEventListener('submit', function(e) {
    let valid = true;

    function setError(inputId, errorId, msg) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        if (error && error.style.display === 'none') {
            if (msg) {
                input.classList.add('is-error');
                error.querySelector('span').textContent = msg;
                error.style.display = 'flex';
                valid = false;
            } else {
                input.classList.remove('is-error');
                error.style.display = 'none';
            }
        }
    }

    // Reset error JS
    ['errorName','errorEmail','errorPassword'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
    ['fieldName','fieldEmail','pwInput'].forEach(id => {
        const el = document.getElementById(id);
        if (el && !el.classList.contains('is-error')) el.classList.remove('is-error');
    });

    const name  = document.getElementById('fieldName').value.trim();
    const email = document.getElementById('fieldEmail').value.trim();
    const pw    = document.getElementById('pwInput').value;

    if (!name)  setError('fieldName',  'errorName',     'Nama lengkap wajib diisi.');
    if (!email) setError('fieldEmail', 'errorEmail',    'Email wajib diisi.');
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email))
                setError('fieldEmail', 'errorEmail',    'Format email tidak valid.');
    if (!pw)    setError('pwInput',    'errorPassword', 'Password wajib diisi.');
    else if (pw.length < 6)
                setError('pwInput',    'errorPassword', 'Password minimal 6 karakter.');

    if (!valid) {
        e.preventDefault();
        lucide.createIcons();
    }
});

/* ── CLEAR ERROR SAAT INPUT ── */
['fieldName','fieldEmail','pwInput'].forEach(function(id) {
    const errorMap = { fieldName: 'errorName', fieldEmail: 'errorEmail', pwInput: 'errorPassword' };
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('is-error');
            const err = document.getElementById(errorMap[id]);
            if (err) err.style.display = 'none';
        }
    });
});
</script>
@endpush