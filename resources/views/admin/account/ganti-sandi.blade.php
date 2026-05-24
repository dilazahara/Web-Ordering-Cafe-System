@extends('layouts.admin')

@section('title', 'Ganti Password')

@push('styles')
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

/* ── MAIN ── */

.main{
    max-width:620px;
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

/* ── SECURITY TIPS ── */

.tips-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}

.tip-item{
    display:flex;
    align-items:flex-start;
    gap:10px;
    padding:12px 14px;
    border-radius:10px;
    background:#F9FAFB;
    border:1px solid #F0F0F0;
}

.tip-icon{
    width:28px;
    height:28px;
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

.tip-icon.green{ background:#DCFCE7; }
.tip-icon.blue{ background:#DBEAFE; }
.tip-icon.orange{ background:#FFEDD5; }
.tip-icon.red{ background:#FEE2E2; }

.tip-text h5{
    font-size:12px;
    font-weight:700;
    color:#111827;
}

.tip-text p{
    font-size:11px;
    color:#9CA3AF;
    margin-top:2px;
    line-height:1.4;
}

/* ── FORM ── */

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
    border-color:#F97316;
    box-shadow:0 0 0 3px rgba(249,115,22,0.1);
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

.field-error{
    font-size:12px;
    color:#EF4444;
    margin-top:3px;
}

/* ── STRENGTH BAR ── */

.strength-wrap{
    margin-top:8px;
}

.strength-bars{
    display:flex;
    gap:4px;
    margin-bottom:4px;
}

.strength-bar{
    height:4px;
    flex:1;
    border-radius:4px;
    background:#E5E7EB;
}

.strength-bar.weak{ background:#EF4444; }
.strength-bar.fair{ background:#F59E0B; }
.strength-bar.good{ background:#3B82F6; }
.strength-bar.strong{ background:#22C55E; }

.strength-label{
    font-size:11px;
    font-weight:600;
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
    transition:0.15s;
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

@media(max-width:520px){

    .tips-grid{
        grid-template-columns:1fr;
    }

}
</style>
@endpush

@section('content')
<!-- HEADER -->

    <div class="page-header">

        <div>

            <h1>Ganti Password</h1>

            <p>
                Perbarui kata sandi akun admin Anda
            </p>

        </div>

    </div>

    <!-- SUCCESS -->

    @if(session('success'))

    <div class="alert-success">

        <i data-lucide="check-circle"
            style="width:16px;height:16px;"></i>

        {{ session('success') }}

    </div>

    @endif

    <!-- ERROR -->

    @if($errors->any())

    <div class="alert-error">

        <i data-lucide="alert-circle"
            style="width:16px;height:16px;"></i>

        {{ $errors->first() }}

    </div>

    @endif

    <!-- TIPS -->

    <div class="card">

        <div class="card-section">

            <p class="card-section-title">

                Tips Keamanan Password

            </p>

            <div class="tips-grid">

                <div class="tip-item">

                    <div class="tip-icon green">

                        <i data-lucide="check"
                            style="width:14px;height:14px;color:#16A34A;"></i>

                    </div>

                    <div class="tip-text">

                        <h5>Minimal 8 Karakter</h5>

                        <p>
                            Semakin panjang semakin aman
                        </p>

                    </div>

                </div>

                <div class="tip-item">

                    <div class="tip-icon blue">

                        <i data-lucide="type"
                            style="width:14px;height:14px;color:#2563EB;"></i>

                    </div>

                    <div class="tip-text">

                        <h5>Huruf Besar & Kecil</h5>

                        <p>
                            Kombinasikan keduanya
                        </p>

                    </div>

                </div>

                <div class="tip-item">

                    <div class="tip-icon orange">

                        <i data-lucide="hash"
                            style="width:14px;height:14px;color:#EA580C;"></i>

                    </div>

                    <div class="tip-text">

                        <h5>Gunakan Angka</h5>

                        <p>
                            Tambahkan angka & simbol
                        </p>

                    </div>

                </div>

                <div class="tip-item">

                    <div class="tip-icon red">

                        <i data-lucide="x"
                            style="width:14px;height:14px;color:#DC2626;"></i>

                    </div>

                    <div class="tip-text">

                        <h5>Jangan Password Lama</h5>

                        <p>
                            Gunakan password baru
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- FORM -->

    <form
        action="{{ route('admin.account.update-password') }}"
        method="POST"
    >

    @csrf
    @method('PUT')

    <div class="card">

        <div class="card-section">

            <p class="card-section-title">

                Ubah Kata Sandi

            </p>

            <!-- PASSWORD LAMA -->

            <div class="form-group">

                <label class="form-label">

                    Password Saat Ini

                </label>

                <div class="input-wrap">

                    <input
                        type="password"
                        name="current_password"
                        id="currentPw"
                        class="form-input"
                        placeholder="Masukkan password lama"
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

            <!-- PASSWORD BARU -->

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
                        placeholder="Minimal 8 karakter"
                        oninput="checkStrength(this.value)"
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

                <div class="strength-wrap">

                    <div class="strength-bars">

                        <div class="strength-bar"
                            id="bar1"></div>

                        <div class="strength-bar"
                            id="bar2"></div>

                        <div class="strength-bar"
                            id="bar3"></div>

                        <div class="strength-bar"
                            id="bar4"></div>

                    </div>

                    <span class="strength-label"
                        id="strengthLabel">

                        —

                    </span>

                </div>

            </div>

            <!-- KONFIRMASI -->

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
                        placeholder="Ulangi password baru"
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

        <!-- FOOTER -->

        <div class="card-footer">

            <a href="/admin/dashboard"
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
@endsection

@push('scripts')
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

function checkStrength(password){

    const bars = [
        document.getElementById('bar1'),
        document.getElementById('bar2'),
        document.getElementById('bar3'),
        document.getElementById('bar4')
    ];

    const label =
        document.getElementById('strengthLabel');

    bars.forEach(bar => {
        bar.className = 'strength-bar';
    });

    let score = 0;

    if(password.length >= 8) score++;
    if(/[A-Z]/.test(password)) score++;
    if(/[0-9]/.test(password)) score++;
    if(/[^A-Za-z0-9]/.test(password)) score++;

    if(score === 1){

        bars[0].classList.add('weak');

        label.innerHTML = 'Lemah';
        label.style.color = '#EF4444';

    }

    if(score === 2){

        bars[0].classList.add('fair');
        bars[1].classList.add('fair');

        label.innerHTML = 'Cukup';
        label.style.color = '#F59E0B';

    }

    if(score === 3){

        bars[0].classList.add('good');
        bars[1].classList.add('good');
        bars[2].classList.add('good');

        label.innerHTML = 'Baik';
        label.style.color = '#2563EB';

    }

    if(score === 4){

        bars.forEach(bar => {
            bar.classList.add('strong');
        });

        label.innerHTML = 'Kuat';
        label.style.color = '#16A34A';

    }

}

</script>
@endpush