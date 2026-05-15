<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ganti Password</title>

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

/* CONTAINER */

.container{
    width:100%;
    max-width:760px;
    margin:auto;
}

/* TOP BAR */

.top-bar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:25px;
    gap:15px;
}

/* BACK */

.back-btn{
    display:inline-flex;
    align-items:center;
    gap:10px;
    text-decoration:none;
    background:
        linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    padding:14px 22px;
    border-radius:16px;
    font-size:14px;
    font-weight:700;
    transition:.25s;
    box-shadow:
        0 14px 30px rgba(37,99,235,0.25);
}

.back-btn:hover{
    transform:translateY(-2px);
    box-shadow:
        0 18px 38px rgba(37,99,235,0.35);
}

/* PAGE TITLE */

.page-title h1{
    font-size:38px;
    font-weight:800;
    color:#111827;
    letter-spacing:-1px;
}

.page-title p{
    margin-top:8px;
    color:#6b7280;
    font-size:15px;
}

/* CARD */

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

/* DECORATION */

.card::before{
    content:'';
    position:absolute;
    top:-120px;
    right:-120px;
    width:280px;
    height:280px;
    border-radius:50%;
    background:
        linear-gradient(135deg,#2563eb,#4f46e5);
    opacity:.08;
}

/* HEADER */

.card-header{
    position:relative;
    z-index:2;
    display:flex;
    align-items:center;
    gap:20px;
    margin-bottom:35px;
    padding-bottom:28px;
    border-bottom:1px solid rgba(226,232,240,0.7);
}

/* ICON */

.icon-wrap{
    width:90px;
    height:90px;
    border-radius:28px;
    background:
        linear-gradient(135deg,#2563eb,#4f46e5);
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:
        0 16px 35px rgba(37,99,235,0.35);
}

.icon-wrap svg{
    width:42px;
    height:42px;
    stroke:white;
    stroke-width:2.2;
    fill:none;
    stroke-linecap:round;
    stroke-linejoin:round;
}

/* INFO */

.header-info h2{
    font-size:32px;
    font-weight:800;
    color:#111827;
    letter-spacing:-1px;
}

.header-info p{
    margin-top:6px;
    color:#6b7280;
    font-size:15px;
    line-height:1.6;
}

/* ALERT */

.alert-success,
.alert-error{
    margin-bottom:22px;
    padding:16px 18px;
    border-radius:18px;
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

/* FORM */

.form-group{
    margin-bottom:24px;
}

.form-group label{
    display:block;
    margin-bottom:10px;
    font-size:14px;
    font-weight:700;
    color:#111827;
}

/* INPUT */

.form-group input{
    width:100%;
    padding:16px 18px;
    border-radius:18px;
    border:1px solid #dbe1ea;
    background:rgba(255,255,255,0.85);
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

/* BUTTON AREA */

.button-group{
    display:flex;
    gap:16px;
    margin-top:10px;
}

/* SAVE */

.btn-save{
    flex:1;
    border:none;
    border-radius:18px;
    padding:16px 26px;
    background:
        linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    font-size:14px;
    font-weight:800;
    cursor:pointer;
    transition:.25s;
    box-shadow:
        0 14px 30px rgba(37,99,235,0.25);
}

.btn-save:hover{
    transform:translateY(-2px);
    box-shadow:
        0 18px 38px rgba(37,99,235,0.35);
}

/* BACK */

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

/* RESPONSIVE */

@media(max-width:768px){

    .card{
        padding:28px;
    }

    .card-header{
        flex-direction:column;
        text-align:center;
    }

    .button-group{
        flex-direction:column;
    }

    .page-title h1{
        font-size:30px;
    }

    .top-bar{
        flex-direction:column;
        align-items:flex-start;
    }

}

</style>
</head>

<body>

<div class="container">

    <!-- TOP BAR -->

    <div class="top-bar">

    </div>

    <!-- PAGE TITLE -->

    <div class="page-title">

        <h1>Ganti Password</h1>

        <p>
            Tingkatkan keamanan akun Anda dengan password baru
        </p>

    </div>

    <!-- CARD -->

    <div class="card">

        <!-- HEADER -->

        <div class="card-header">

            <!-- ICON -->

            <div class="icon-wrap">

                <svg viewBox="0 0 24 24">

                    <rect
                        x="3"
                        y="11"
                        width="18"
                        height="11"
                        rx="2"
                        ry="2"
                    />

                    <path
                        d="M7 11V7a5 5 0 0 1 10 0v4"
                    />

                </svg>

            </div>

            <!-- INFO -->

            <div class="header-info">

                <h2>Keamanan Akun</h2>

                <p>
                    Pastikan password baru mudah diingat
                    namun sulit ditebak oleh orang lain
                </p>

            </div>

        </div>

        <!-- SUCCESS -->

        @if(session('success'))

        <div class="alert-success">

            {{ session('success') }}

        </div>

        @endif

        <!-- ERROR -->

        @if($errors->any())

        <div class="alert-error">

            @foreach($errors->all() as $error)

                <div>{{ $error }}</div>

            @endforeach

        </div>

        @endif

        <!-- FORM -->

        <form
            action="{{ route('kasir.account.update-password') }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <!-- PASSWORD LAMA -->

            <div class="form-group">

                <label>Password Lama</label>

                <input
                    type="password"
                    name="current_password"
                    placeholder="Masukkan password lama"
                    required
                >

            </div>

            <!-- PASSWORD BARU -->

            <div class="form-group">

                <label>Password Baru</label>

                <input
                    type="password"
                    name="new_password"
                    placeholder="Masukkan password baru"
                    required
                >

            </div>

            <!-- KONFIRMASI -->

            <div class="form-group">

                <label>Konfirmasi Password Baru</label>

                <input
                    type="password"
                    name="new_password_confirmation"
                    placeholder="Ulangi password baru"
                    required
                >

            </div>

            <!-- BUTTON -->

            <div class="button-group">

                <button
                    type="submit"
                    class="btn-save"
                >

                    Simpan Password

                </button>

                <a
                    href="/kasir/dashboard"
                    class="btn-back"
                >

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>