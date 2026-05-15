<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profil Kasir</title>

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
    max-width:920px;
    margin:auto;
}

/* HEADER */

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

/* DECOR */

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

/* PROFILE TOP */

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

/* AVATAR */

.big-avatar{
    width:110px;
    height:110px;
    border-radius:30px;
    background:
        linear-gradient(135deg,#2563eb,#4f46e5);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:44px;
    font-weight:800;
    box-shadow:
        0 15px 35px rgba(37,99,235,0.35);
}

/* PROFILE INFO */

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

/* ALERT */

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

/* GRID */

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:22px;
}

/* FORM */

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

/* BUTTONS */

.button-group{
    display:flex;
    gap:16px;
    margin-top:14px;
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

    .grid{
        grid-template-columns:1fr;
    }

    .profile-top{
        flex-direction:column;
        text-align:center;
    }

    .button-group{
        flex-direction:column;
    }

    .card{
        padding:28px;
    }

    .page-header h1{
        font-size:30px;
    }

}

</style>
</head>

<body>

<div class="container">

    <!-- HEADER -->

    <div class="page-header">

        <h1>Profil Kasir</h1>

        <p>
            Kelola informasi akun dan data pribadi Anda
        </p>

    </div>

    <!-- CARD -->

    <div class="card">

        <!-- PROFILE TOP -->

        <div class="profile-top">

            <!-- AVATAR -->

            <div class="big-avatar">

                {{ strtoupper(substr(auth()->user()->name,0,1)) }}

            </div>

            <!-- INFO -->

            <div class="profile-info">

                <h2>

                    {{ auth()->user()->name }}

                </h2>

                <p>

                    {{ auth()->user()->email }}

                </p>

                <div class="online">

                    Online Sekarang

                </div>

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
            action="{{ route('kasir.account.update') }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <!-- GRID -->

            <div class="grid">

                <!-- NAMA -->

                <div class="form-group">

                    <label>Nama Lengkap</label>

                    <input
                        type="text"
                        name="name"
                        value="{{ auth()->user()->name }}"
                    >

                </div>

                <!-- USERNAME -->

                <div class="form-group">

                    <label>Username</label>

                    <input
                        type="text"
                        name="username"
                        value="{{ auth()->user()->username }}"
                    >

                </div>

            </div>

            <!-- EMAIL -->

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ auth()->user()->email }}"
                >

            </div>

            <!-- PHONE -->

            <div class="form-group">

                <label>No Telepon</label>

                <input
                    type="text"
                    name="phone"
                    value="{{ auth()->user()->phone }}"
                >

            </div>

            <!-- ROLE -->

            <div class="form-group">

                <label>Role</label>

                <input
                    type="text"
                    value="{{ ucfirst(auth()->user()->role) }}"
                    readonly
                >

            </div>

            <!-- BUTTON -->

            <div class="button-group">

                <button
                    type="submit"
                    class="btn-save"
                >

                    Simpan Perubahan

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