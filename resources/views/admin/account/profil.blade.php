<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profil Saya</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<script src="https://unpkg.com/lucide@latest"></script>

<style>

*{
    box-sizing:border-box;
    margin:0;
    padding:0;
    font-family:'Plus Jakarta Sans',sans-serif;
}

body{
    background:#F8F9FC;
}

/* ── MAIN ── */

.main{
    padding:40px 20px;
    max-width:780px;
    margin:auto;
}

/* ── PAGE HEADER ── */

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
}

.page-header h1{
    font-size:28px;
    font-weight:700;
    color:#111827;
}

.page-header p{
    font-size:14px;
    color:#9CA3AF;
    margin-top:4px;
}

/* ── CARD ── */

.card{
    background:white;
    border-radius:18px;
    border:1px solid #F0F0F0;
    box-shadow:0 2px 12px rgba(0,0,0,0.04);
    overflow:hidden;
    margin-bottom:16px;
}

.card-section{
    padding:24px;
    border-bottom:1px solid #F8F9FC;
}

.card-section:last-child{
    border-bottom:none;
}

.card-section-title{
    font-size:11px;
    font-weight:700;
    color:#9CA3AF;
    text-transform:uppercase;
    letter-spacing:0.8px;
    margin-bottom:18px;
}

/* ── AVATAR ── */

.avatar-row{
    display:flex;
    align-items:center;
    gap:20px;
}

.big-avatar{
    width:72px;
    height:72px;
    border-radius:18px;
    flex-shrink:0;
    background:linear-gradient(135deg,#F97316,#FB923C);
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:26px;
    font-weight:800;
    box-shadow:0 4px 16px rgba(249,115,22,0.3);
}

.avatar-info h3{
    font-size:17px;
    font-weight:700;
    color:#111827;
}

.avatar-info p{
    font-size:13px;
    color:#6B7280;
    margin-top:3px;
}

.online-dot{
    display:inline-flex;
    align-items:center;
    gap:5px;
    font-size:12px;
    color:#16A34A;
    font-weight:600;
    margin-top:6px;
}

/* ── FORM ── */

.form-grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}

.form-group{
    display:flex;
    flex-direction:column;
    gap:6px;
}

.form-label{
    font-size:13px;
    font-weight:600;
    color:#374151;
}

.form-input{
    width:100%;
    padding:11px 14px;
    border:1.5px solid #E5E7EB;
    border-radius:10px;
    font-size:14px;
    color:#111827;
    background:white;
    outline:none;
    transition:.2s;
}

.form-input:focus{
    border-color:#F97316;
    box-shadow:0 0 0 3px rgba(249,115,22,0.1);
}

.form-input[readonly]{
    background:#F9FAFB;
    color:#6B7280;
}

/* ── BADGE ── */

.info-badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:5px 10px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    background:#DCFCE7;
    color:#16A34A;
}

/* ── FOOTER ── */

.card-footer{
    padding:18px 24px;
    background:#FAFAFA;
    border-top:1px solid #F0F0F0;
    display:flex;
    justify-content:flex-end;
    gap:10px;
    align-items:center;
}

.btn-cancel{
    padding:10px 20px;
    border-radius:10px;
    border:1.5px solid #E5E7EB;
    background:white;
    color:#374151;
    font-size:14px;
    font-weight:600;
    text-decoration:none;
    transition:.15s;
}

.btn-cancel:hover{
    background:#F3F4F6;
}

.btn-save{
    padding:10px 24px;
    border-radius:10px;
    border:none;
    background:#F97316;
    color:white;
    font-size:14px;
    font-weight:700;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:7px;
}

.btn-save:hover{
    background:#EA6A0A;
}

/* ── ALERT ── */

.alert-success{
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px 16px;
    border-radius:10px;
    background:#F0FDF4;
    border:1px solid #BBF7D0;
    color:#15803D;
    font-size:13px;
    font-weight:600;
    margin-bottom:16px;
}

/* ── RESPONSIVE ── */

@media(max-width:580px){

    .form-grid-2{
        grid-template-columns:1fr;
    }

    .avatar-row{
        flex-direction:column;
        text-align:center;
    }

}

</style>
</head>

<body>

<div class="main">

    <div class="page-header">

        <div>

            <h1>Profil Saya</h1>

            <p>
                Kelola informasi akun dan data pribadi Anda
            </p>

        </div>

    </div>

    @if(session('success'))

    <div class="alert-success">

        <i data-lucide="check-circle"
            style="width:16px;height:16px;"></i>

        {{ session('success') }}

    </div>

    @endif

    <form
        action="{{ route('admin.account.update') }}"
        method="POST"
    >

    @csrf
    @method('PUT')

    <!-- CARD -->

    <div class="card">

        <!-- IDENTITAS -->

        <div class="card-section">

            <p class="card-section-title">

                Identitas Akun

            </p>

            <div class="avatar-row">

                <div class="big-avatar">

                    {{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}

                </div>

                <div class="avatar-info">

                    <h3>

                        {{ auth()->user()->name ?? 'Admin' }}

                    </h3>

                    <p>

                        {{ auth()->user()->email ?? 'admin@example.com' }}

                    </p>

                    <div class="online-dot">

                        ● Online sekarang

                    </div>

                </div>

            </div>

        </div>

        <!-- DATA DIRI -->

        <div class="card-section">

            <p class="card-section-title">

                Data Diri

            </p>

            <div style="display:grid; gap:16px;">

                <div class="form-grid-2">

                    <div class="form-group">

                        <label class="form-label">

                            Nama Lengkap

                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-input"
                            value="{{ old('name', auth()->user()->name) }}"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label class="form-label">

                            Username

                        </label>

                        <input
                            type="text"
                            name="username"
                            class="form-input"
                            value="{{ old('username', auth()->user()->username ?? '') }}"
                        >

                    </div>

                </div>

                <div class="form-group">

                    <label class="form-label">

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-input"
                        value="{{ old('email', auth()->user()->email) }}"
                        required
                    >

                </div>

                <div class="form-group">

                    <label class="form-label">

                        No Telepon

                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-input"
                        value="{{ old('phone', auth()->user()->phone ?? '') }}"
                    >

                </div>

            </div>

        </div>

        <!-- INFO AKUN -->

        <div class="card-section">

            <p class="card-section-title">

                Info Akun

            </p>

            <div class="form-grid-2">

                <div class="form-group">

                    <label class="form-label">

                        Role

                    </label>

                    <input
                        type="text"
                        class="form-input"
                        value="{{ auth()->user()->role ?? 'Administrator' }}"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label class="form-label">

                        Status

                    </label>

                    <div style="padding-top:6px;">

                        <span class="info-badge">

                            <i data-lucide="check-circle"
                                style="width:13px;height:13px;"></i>

                            Aktif

                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- FOOTER -->

        <div class="card-footer">

            <a href="/admin/dashboard"
                class="btn-cancel">

                Kembali

            </a>

            <button type="submit"
                class="btn-save">

                <i data-lucide="save"
                    style="width:16px;height:16px;"></i>

                Simpan Perubahan

            </button>

        </div>

    </div>

    </form>

</div>

<script>

lucide.createIcons();

</script>

</body>
</html>