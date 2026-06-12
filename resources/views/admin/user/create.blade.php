<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah User</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
body { background: #f3f4f6; min-height: 100vh; padding: 50px 20px; }

.container { max-width: 520px; margin: auto; }

/* BACK LINK */
.back-link {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600; color: #6b7280;
    text-decoration: none; margin-bottom: 20px; transition: color .15s;
}
.back-link:hover { color: #111827; }
.back-link i { width: 15px; height: 15px; }

/* CARD */
.card {
    background: white; padding: 32px;
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

/* TITLE */
.title { margin-bottom: 28px; }
.title h2 { font-size: 26px; color: #111827; font-weight: 800; }
.title p  { margin-top: 5px; color: #6b7280; font-size: 13.5px; }

/* DIVIDER */
.divider {
    display: flex; align-items: center; gap: 12px;
    margin: 24px 0;
}
.divider span { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .8px; white-space: nowrap; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #f0f0f0; }

/* FORM */
.form-group { margin-bottom: 16px; }
.form-label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 700; color: #111827; }

.input-wrap { position: relative; }
.form-input {
    width: 100%; padding: 12px 16px;
    border-radius: 12px; border: 1.5px solid #e5e7eb;
    background: #f9fafb; font-size: 14px; color: #111827;
    outline: none; transition: .2s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.form-input:focus {
    border-color: #f97316; background: white;
    box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
}
.form-input::placeholder { color: #9ca3af; }

/* INPUT ERROR STATE */
.form-input.is-error {
    border-color: #ef4444 !important;
    background: #fef2f2 !important;
    box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important;
}

/* FIELD ERROR MESSAGE */
.field-error {
    display: flex; align-items: center; gap: 5px;
    margin-top: 6px; font-size: 12px; font-weight: 600; color: #dc2626;
    animation: slideDown .2s ease-out;
}
.field-error i { width: 13px; height: 13px; flex-shrink: 0; }

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-4px); }
    to   { opacity: 1; transform: translateY(0); }
}

.eye-btn {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #9ca3af;
    display: flex; align-items: center;
}
.eye-btn i { width: 16px; height: 16px; }

/* ROLE CARDS */
.role-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 10px; margin-bottom: 28px;
}
.role-opt { position: relative; cursor: pointer; }
.role-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.role-card {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px; border-radius: 14px;
    border: 2px solid #e5e7eb; background: #f9fafb;
    transition: all .18s; cursor: pointer;
}
.role-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.role-icon i { width: 18px; height: 18px; }
.role-label { font-size: 13.5px; font-weight: 700; color: #374151; }
.role-desc   { font-size: 11px; color: #9ca3af; margin-top: 2px; }

.role-opt.r-admin  input:checked + .role-card  { border-color: #6366f1; background: #eef2ff; }
.role-opt.r-kasir  input:checked + .role-card  { border-color: #f97316; background: #fff7ed; }
.role-opt.r-dapur  input:checked + .role-card  { border-color: #22c55e; background: #f0fdf4; }
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

/* ROLE ERROR STATE */
.role-grid.is-error .role-card {
    border-color: #fca5a5;
}
.role-error {
    display: flex; align-items: center; gap: 5px;
    margin-top: -20px; margin-bottom: 20px;
    font-size: 12px; font-weight: 600; color: #dc2626;
    animation: slideDown .2s ease-out;
}
.role-error i { width: 13px; height: 13px; flex-shrink: 0; }

/* ALERT GLOBAL */
.alert-error {
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
    border-radius: 12px; padding: 14px 16px; font-size: 13px;
    font-weight: 600; margin-bottom: 20px;
    display: flex; align-items: flex-start; gap: 10px; line-height: 1.5;
}
.alert-error i { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; }
.alert-error ul { margin: 4px 0 0 4px; padding-left: 16px; }
.alert-error ul li { margin-bottom: 2px; }

/* BUTTONS */
.button-group { display: flex; gap: 12px; }
.btn {
    flex: 1; padding: 13px; border-radius: 14px; border: none;
    font-size: 14px; font-weight: 700; cursor: pointer;
    text-decoration: none; text-align: center; transition: all .2s;
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.btn i { width: 16px; height: 16px; }
.btn-save {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white; box-shadow: 0 8px 20px rgba(249,115,22,0.25);
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 12px 24px rgba(249,115,22,0.3); }
.btn-back { background: white; color: #374151; border: 1.5px solid #e5e7eb; }
.btn-back:hover { background: #f9fafb; }

@media(max-width:480px) {
    .role-grid { grid-template-columns: 1fr; }
    .button-group { flex-direction: column; }
}
</style>
</head>
<body>

<div class="container">

    <a href="/admin/user" class="back-link">
    </a>

    <div class="card">

        <div class="title">
            <h2>Tambah User</h2>
            <p>Buat akun baru dan tentukan role-nya</p>
        </div>

        {{-- ALERT GLOBAL — tampil jika ada error --}}
        @if($errors->any())
        <div class="alert-error">
            <i data-lucide="alert-circle"></i>
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

        <div class="divider"><span>Data Akun</span></div>

        <!-- NAMA -->
        <div class="form-group">
            <label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" id="fieldName"
    class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
    placeholder="Contoh: Tegar"
    value="{{ old('name') }}">
            @error('name')
                <div class="field-error">
                    <i data-lucide="alert-circle"></i> {{ $message }}
                </div>
            @enderror

            <div class="field-error" id="errorName" style="display:none;">
    <i data-lucide="alert-circle"></i> <span></span>
</div>
        </div>

        <!-- EMAIL -->
        <div class="form-group">
            <label class="form-label">Email <span style="color:#ef4444;">*</span></label>
            <input type="email" name="email" id="fieldEmail"
    class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
    placeholder="Contoh: tegar@example.com"
    value="{{ old('email') }}">
            @error('email')
                <div class="field-error">
                    <i data-lucide="alert-circle"></i> {{ $message }}
                </div>
            @enderror

            <div class="field-error" id="errorEmail" style="display:none;">
    <i data-lucide="alert-circle"></i> <span></span>
</div>
        </div>

        <!-- PASSWORD -->
        <div class="form-group">
            <label class="form-label">Password <span style="color:#ef4444;">*</span></label>
            <div class="input-wrap">
                <input type="password" name="password" id="pwInput"
                    class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                    style="padding-right:42px;"
                    placeholder="Minimal 6 karakter">
                <button type="button" class="eye-btn" onclick="togglePw()">
                    <i data-lucide="eye" id="eyeIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="field-error">
                    <i data-lucide="alert-circle"></i> {{ $message }}
                </div>
            @enderror

            <div class="field-error" id="errorPassword" style="display:none;">
    <i data-lucide="alert-circle"></i> <span></span>
</div>

        </div>

        <div class="divider"><span>Pilih Role</span></div>

        <!-- ROLE CARDS -->
        <div class="role-grid {{ $errors->has('role') ? 'is-error' : '' }}">

            <label class="role-opt r-admin">
                <input type="radio" name="role" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
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
                <input type="radio" name="role" value="kasir" {{ old('role', 'kasir') == 'kasir' ? 'checked' : '' }}>
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
                <input type="radio" name="role" value="dapur" {{ old('role') == 'dapur' ? 'checked' : '' }}>
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
                <input type="radio" name="role" value="pelayan" {{ old('role') == 'pelayan' ? 'checked' : '' }}>
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

        @error('role')
            <div class="role-error">
                <i data-lucide="alert-circle"></i> {{ $message }}
            </div>
        @enderror

        <div class="button-group">
            <button type="submit" class="btn btn-save">
                <i data-lucide="user-plus"></i>
                Buat Akun
            </button>
            <a href="/admin/user" class="btn btn-back">Batal</a>
        </div>

        </form>
    </div>
</div>

<script>
lucide.createIcons();

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
        // Hanya tampilkan error JS jika belum ada error backend
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

    // Reset error JS (jangan sentuh yg dari backend)
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
</body>
</html>