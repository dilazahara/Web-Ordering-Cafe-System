<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ganti Password</title>

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
    padding:40px 20px;
}

.main{
    max-width:620px;
    margin:auto;
}

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

.card{
    background:white;
    border-radius:16px;
    border:1px solid #F0F0F0;
    box-shadow:0 2px 12px rgba(0,0,0,0.04);
    overflow:hidden;
    margin-bottom:16px;
}

.card-section{
    padding:22px 24px;
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

.form-group{
    display:flex;
    flex-direction:column;
    gap:6px;
    margin-bottom:16px;
}

.form-label{
    font-size:13px;
    font-weight:600;
    color:#374151;
}

.input-wrap{
    position:relative;
}

.form-input{
    width:100%;
    padding:10px 42px 10px 13px;
    border:1.5px solid #E5E7EB;
    border-radius:10px;
    font-size:14px;
    color:#111827;
    background:white;
    outline:none;
    transition:0.2s;
}

.form-input:focus{
    border-color:#10B981;
    box-shadow:0 0 0 3px rgba(16,185,129,0.1);
}

.eye-btn{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    background:none;
    border:none;
    cursor:pointer;
    color:#9CA3AF;
}

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
}

.btn-save{
    padding:10px 24px;
    border-radius:10px;
    border:none;
    background:#10B981;
    color:white;
    font-size:14px;
    font-weight:700;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:7px;
}

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

.alert-error{
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px 16px;
    border-radius:10px;
    background:#FEF2F2;
    border:1px solid #FECACA;
    color:#DC2626;
    font-size:13px;
    font-weight:600;
    margin-bottom:16px;
}

</style>
</head>

<body>

<div class="main">

    <div class="page-header">

        <div>

            <h1>Ganti Password</h1>

            <p>
                Perbarui password akun pelayan Anda
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

    @if($errors->any())

    <div class="alert-error">

        <i data-lucide="alert-circle"
            style="width:16px;height:16px;"></i>

        {{ $errors->first() }}

    </div>

    @endif

    <form
        action="{{ route('pelayan.account.update-password') }}"
        method="POST"
    >

    @csrf
    @method('PUT')

    <div class="card">

        <div class="card-section">

            <p class="card-section-title">
                Ubah Password
            </p>

            <div class="form-group">

                <label class="form-label">
                    Password Lama
                </label>

                <div class="input-wrap">

                    <input
                        type="password"
                        name="current_password"
                        id="currentPw"
                        class="form-input"
                        required
                    >

                    <button
                        type="button"
                        class="eye-btn"
                        onclick="toggleEye('currentPw', this)"
                    >

                        <i data-lucide="eye"
                            style="width:16px;height:16px;"></i>

                    </button>

                </div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    Password Baru
                </label>

                <div class="input-wrap">

                    <input
                        type="password"
                        name="new_password"
                        id="newPw"
                        class="form-input"
                        required
                    >

                    <button
                        type="button"
                        class="eye-btn"
                        onclick="toggleEye('newPw', this)"
                    >

                        <i data-lucide="eye"
                            style="width:16px;height:16px;"></i>

                    </button>

                </div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    Konfirmasi Password Baru
                </label>

                <div class="input-wrap">

                    <input
                        type="password"
                        name="new_password_confirmation"
                        id="confirmPw"
                        class="form-input"
                        required
                    >

                    <button
                        type="button"
                        class="eye-btn"
                        onclick="toggleEye('confirmPw', this)"
                    >

                        <i data-lucide="eye"
                            style="width:16px;height:16px;"></i>

                    </button>

                </div>

            </div>

        </div>

        <div class="card-footer">

            <a href="/pelayan/antar"
                class="btn-cancel">

                Kembali

            </a>

            <button
                type="submit"
                class="btn-save"
            >

                <i data-lucide="lock"
                    style="width:16px;height:16px;"></i>

                Simpan Password

            </button>

        </div>

    </div>

    </form>

</div>

<script>

lucide.createIcons();

function toggleEye(inputId, btn){

    const input = document.getElementById(inputId);

    if(input.type === 'password'){

        input.type = 'text';

        btn.innerHTML =
        '<i data-lucide="eye-off" style="width:16px;height:16px;"></i>';

    }else{

        input.type = 'password';

        btn.innerHTML =
        '<i data-lucide="eye" style="width:16px;height:16px;"></i>';

    }

    lucide.createIcons();

}

</script>

</body>
</html>