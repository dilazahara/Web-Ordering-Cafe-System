<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profil Dapur</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Plus Jakarta Sans',sans-serif;
}

body{
    min-height:100vh;
    background:
        radial-gradient(circle at top left,#dbeafe,transparent 30%),
        radial-gradient(circle at bottom right,#e0e7ff,transparent 35%),
        #f5f7fb;
    padding:50px 20px;
}

.container{
    width:100%;
    max-width:920px;
    margin:auto;
}

.page-header{
    margin-bottom:25px;
}

.page-header h1{
    font-size:38px;
    font-weight:800;
    color:#111827;
    letter-spacing:-1px;
}

.page-header p{
    margin-top:8px;
    color:#6b7280;
    font-size:15px;
}

.card{
    position:relative;
    overflow:hidden;
    background:rgba(255,255,255,0.78);
    backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.5);
    border-radius:32px;
    padding:42px;
    box-shadow:
        0 20px 50px rgba(15,23,42,0.08),
        inset 0 1px 0 rgba(255,255,255,0.4);
}

.card::before{
    content:'';
    position:absolute;
    top:-120px;
    right:-120px;
    width:280px;
    height:280px;
    border-radius:50%;
    background:linear-gradient(135deg,#3b82f6,#6366f1);
    opacity:.08;
}

.profile-top{
    position:relative;
    z-index:2;
    display:flex;
    align-items:center;
    gap:26px;
    margin-bottom:38px;
    padding-bottom:30px;
    border-bottom:1px solid rgba(226,232,240,0.7);
}

.avatar-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.big-avatar{
    width:110px;
    height:110px;
    border-radius:30px;
    background: linear-gradient(135deg,#2563eb,#4f46e5);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:44px;
    font-weight:800;
    box-shadow: 0 15px 35px rgba(37,99,235,0.35);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.big-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: 0.2s;
    color: white;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
}

.big-avatar:hover .avatar-overlay {
    opacity: 1;
}

.profile-info h2{
    font-size:32px;
    font-weight:800;
    color:#111827;
    letter-spacing:-1px;
}

.profile-info p{
    margin-top:6px;
    color:#6b7280;
    font-size:15px;
}

.online{
    margin-top:14px;
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:#dcfce7;
    color:#166534;
    padding:9px 15px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
    box-shadow:0 4px 10px rgba(22,101,52,0.08);
}

.online::before{
    content:'';
    width:10px;
    height:10px;
    border-radius:50%;
    background:#22c55e;
}

.alert-success,
.alert-error{
    margin-bottom:20px;
    padding:16px 18px;
    border-radius:16px;
    font-size:14px;
    font-weight:600;
}

.alert-success{
    background:#dcfce7;
    color:#166534;
    border:1px solid #bbf7d0;
}

.alert-error{
    background:#fee2e2;
    color:#991b1b;
    border:1px solid #fecaca;
}

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:22px;
}

.form-group{
    margin-bottom:22px;
}

.form-group label{
    display:block;
    margin-bottom:10px;
    font-size:14px;
    font-weight:700;
    color:#111827;
}

.form-group input{
    width:100%;
    padding:16px 18px;
    border-radius:18px;
    border:1px solid #dbe1ea;
    background:rgba(255,255,255,0.8);
    font-size:14px;
    color:#111827;
    transition:.25s;
}

.form-group input:focus{
    outline:none;
    border-color:#2563eb;
    background:white;
    box-shadow:
        0 0 0 5px rgba(37,99,235,0.10),
        0 10px 20px rgba(37,99,235,0.08);
}

.form-group input[readonly]{
    background:#f3f4f6;
    color:#6b7280;
}

/* ── PASSWORD ── */
.section-divider {
    position: relative;
    margin: 10px 0 28px;
    border: none;
    border-top: 1px solid rgba(226,232,240,0.8);
}

.section-label {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #9ca3af;
    margin-bottom: 20px;
}

.section-note {
    font-size: 13px;
    color: #9ca3af;
    margin-bottom: 22px;
    margin-top: -10px;
}

.input-eye-wrap {
    position: relative;
}

.input-eye-wrap input {
    padding-right: 50px !important;
}

.btn-eye {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #9ca3af;
    font-size: 18px;
    line-height: 1;
    padding: 0;
    transition: color .2s;
}

.btn-eye:hover { color: #2563eb; }

/* strength */
.strength-wrap { margin-top: 8px; display: none; }
.strength-bar { display: flex; gap: 4px; margin-bottom: 5px; }
.seg {
    height: 4px; flex: 1; border-radius: 99px;
    background: #e5e7eb; transition: background .3s;
}
.strength-text { font-size: 11px; font-weight: 700; color: #9ca3af; }

/* confirm note */
.confirm-note { font-size: 12px; font-weight: 600; margin-top: 6px; display: none; }

.button-group{
    display:flex;
    gap:16px;
    margin-top:14px;
}

.btn-save{
    flex:1;
    border:none;
    border-radius:18px;
    padding:16px 26px;
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    font-size:14px;
    font-weight:800;
    cursor:pointer;
    transition:.25s;
    box-shadow: 0 14px 30px rgba(37,99,235,0.25);
}

.btn-save:hover{
    transform:translateY(-2px);
    box-shadow: 0 18px 38px rgba(37,99,235,0.35);
}

.btn-back{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    border-radius:18px;
    padding:16px 26px;
    background:white;
    border:1px solid #dbe1ea;
    color:#111827;
    font-size:14px;
    font-weight:700;
    transition:.25s;
}

.btn-back:hover{
    background:#111827;
    color:white;
    transform:translateY(-2px);
}

@media(max-width:768px){
    .grid{ grid-template-columns:1fr; }
    .profile-top{ flex-direction:column; text-align:center; }
    .button-group{ flex-direction:column; }
    .card{ padding:28px; }
    .page-header h1{ font-size:30px; }
}
</style>
</head>

<body>

<div class="container">

    <div class="page-header">
        <h1>Profil Dapur</h1>
        <p>Kelola informasi akun dan data pribadi Anda</p>
    </div>

    <div class="card">

        @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('dapur.account.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-top">
                <div class="avatar-container">
                    <div class="big-avatar" onclick="document.getElementById('profileInput').click()">
                        @if(auth()->user()->avatar)
                            <img id="avatarPreview" src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Foto Profil">
                        @else
                            <div id="avatarInitials">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <img id="avatarPreview" src="" alt="Foto Profil" style="display:none;">
                        @endif
                        <div class="avatar-overlay">Ubah Foto</div>
                    </div>
                    <input type="file" id="profileInput" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none;">
                </div>

                <div class="profile-info">
                    <h2>{{ auth()->user()->name }}</h2>
                    <p>{{ auth()->user()->email }}</p>
                    <div class="online">Online Sekarang</div>
                </div>
            </div>

            {{-- DATA DIRI --}}
            <div class="grid">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}">
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
            </div>

            

            <div class="form-group">
                <label>Role</label>
                <input type="text" value="{{ ucfirst(auth()->user()->role) }}" readonly>
            </div>

            {{-- GANTI PASSWORD --}}
            <hr class="section-divider">
            <p class="section-label">Ganti Password</p>
            <p class="section-note">Kosongkan jika tidak ingin mengganti password.</p>

            <div class="form-group">
                <label>Password Saat Ini</label>
                <div class="input-eye-wrap">
                    <input type="password" name="current_password" id="dpCurrentPwd" placeholder="Masukkan password saat ini" autocomplete="current-password">
                    <button type="button" class="btn-eye" onclick="toggleEye('dpCurrentPwd','dpEye0')">
                        <span id="dpEye0">👁</span>
                    </button>
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Password Baru</label>
                    <div class="input-eye-wrap">
                        <input type="password" name="new_password" id="dpNewPwd" placeholder="Min. 8 karakter" autocomplete="new-password" oninput="dpCheckStrength(this.value)">
                        <button type="button" class="btn-eye" onclick="toggleEye('dpNewPwd','dpEye1')">
                            <span id="dpEye1">👁</span>
                        </button>
                    </div>
                    <div class="strength-wrap" id="dpStrengthWrap">
                        <div class="strength-bar">
                            <div class="seg" id="dpSeg1"></div>
                            <div class="seg" id="dpSeg2"></div>
                            <div class="seg" id="dpSeg3"></div>
                            <div class="seg" id="dpSeg4"></div>
                        </div>
                        <span class="strength-text" id="dpStrengthText"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <div class="input-eye-wrap">
                        <input type="password" name="new_password_confirmation" id="dpConfirmPwd" placeholder="Ulangi password baru" autocomplete="new-password" oninput="dpCheckConfirm()">
                        <button type="button" class="btn-eye" onclick="toggleEye('dpConfirmPwd','dpEye2')">
                            <span id="dpEye2">👁</span>
                        </button>
                    </div>
                    <div class="confirm-note" id="dpConfirmNote"></div>
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-save">Simpan Perubahan</button>
                <a href="/dapur/proses" class="btn-back">Kembali</a>
            </div>

        </form>

    </div>

</div>

<script>
    // Preview foto
    const profileInput = document.getElementById('profileInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarInitials = document.getElementById('avatarInitials');

    profileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                avatarPreview.src = event.target.result;
                avatarPreview.style.display = 'block';
                if(avatarInitials) avatarInitials.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    // Toggle show/hide password
    function toggleEye(inputId, spanId) {
        const input = document.getElementById(inputId);
        const span  = document.getElementById(spanId);
        if (input.type === 'password') {
            input.type = 'text';
            span.textContent = '🙈';
        } else {
            input.type = 'password';
            span.textContent = '👁';
        }
    }

    // Strength meter
    function dpCheckStrength(val) {
        const wrap = document.getElementById('dpStrengthWrap');
        const text = document.getElementById('dpStrengthText');
        const segs = ['dpSeg1','dpSeg2','dpSeg3','dpSeg4'].map(id => document.getElementById(id));
        if (!val) { wrap.style.display = 'none'; return; }
        wrap.style.display = 'block';
        let score = 0;
        if (val.length >= 8)           score++;
        if (/[A-Z]/.test(val))         score++;
        if (/[0-9]/.test(val))         score++;
        if (/[^A-Za-z0-9]/.test(val))  score++;
        const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
        const labels = ['Sangat Lemah','Lemah','Sedang','Kuat'];
        segs.forEach((s,i) => s.style.background = i < score ? colors[score-1] : '#e5e7eb');
        text.textContent = labels[score-1] || '';
        text.style.color = colors[score-1] || '#9ca3af';
        dpCheckConfirm();
    }

    function dpCheckConfirm() {
        const newVal  = document.getElementById('dpNewPwd').value;
        const confVal = document.getElementById('dpConfirmPwd').value;
        const note    = document.getElementById('dpConfirmNote');
        if (!confVal) { note.style.display = 'none'; return; }
        note.style.display = 'block';
        if (newVal === confVal) {
            note.textContent = '✓ Password cocok';
            note.style.color = '#16a34a';
        } else {
            note.textContent = '✗ Password tidak cocok';
            note.style.color = '#dc2626';
        }
    }
</script>

</body>
</html>