<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profil Saya</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Plus Jakarta Sans',sans-serif;
}

body{
    background:#f3f4f6;
    min-height:100vh;
    padding:40px 20px;
}

.container{
    max-width:700px;
    margin:auto;
}

.card{
    background:white;
    border-radius:24px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.header{
    margin-bottom:30px;
}

.header h1{
    font-size:30px;
    font-weight:800;
    color:#111827;
}

.header p{
    margin-top:6px;
    color:#6b7280;
    font-size:14px;
}

.profile-top{
    display:flex;
    align-items:center;
    gap:18px;
    margin-bottom:30px;
}

.avatar{
    width:80px;
    height:80px;
    border-radius:20px;
    background:linear-gradient(135deg,#10b981,#059669);
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:28px;
    font-weight:800;
}

.profile-info h2{
    font-size:22px;
    font-weight:800;
    color:#111827;
}

.profile-info p{
    color:#6b7280;
    margin-top:4px;
}

.badge{
    display:inline-block;
    margin-top:8px;
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:700;
    color:#111827;
}

input{
    width:100%;
    padding:14px 16px;
    border-radius:14px;
    border:1px solid #d1d5db;
    background:#f9fafb;
    font-size:14px;
}

input:focus{
    outline:none;
    border-color:#10b981;
    background:white;
    box-shadow:0 0 0 4px rgba(16,185,129,0.1);
}

.button-group{
    display:flex;
    gap:14px;
    margin-top:25px;
}

.btn{
    flex:1;
    padding:14px;
    border-radius:14px;
    text-align:center;
    text-decoration:none;
    font-size:14px;
    font-weight:700;
    transition:.2s;
    cursor:pointer;
    border:none;
}

.btn-save{
    background:linear-gradient(135deg,#10b981,#059669);
    color:white;
}

.btn-save:hover{
    transform:translateY(-2px);
}

.btn-back{
    background:white;
    border:1px solid #d1d5db;
    color:#111827;
}

.btn-back:hover{
    background:#111827;
    color:white;
}

.alert{
    padding:14px 18px;
    border-radius:14px;
    margin-bottom:20px;
    font-size:14px;
    font-weight:600;
}

.alert-success{
    background:#dcfce7;
    color:#166534;
}

</style>
</head>
<body>

<div class="container">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <div class="header">
            <h1>Profil Saya</h1>
            <p>Kelola informasi akun pelayan Anda</p>
        </div>

        <div class="profile-top">

            <div class="avatar">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>

            <div class="profile-info">

                <h2>
                    {{ Auth::user()->name }}
                </h2>

                <p>
                    {{ Auth::user()->email }}
                </p>

                <div class="badge">
                    {{ ucfirst(Auth::user()->role) }}
                </div>

            </div>

        </div>

        <form
            action="{{ route('pelayan.account.update') }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="form-group">

                <label>Nama Lengkap</label>

                <input
                    type="text"
                    name="name"
                    value="{{ Auth::user()->name }}"
                    required
                >

            </div>

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ Auth::user()->email }}"
                    required
                >

            </div>

            <div class="form-group">

                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    value="{{ Auth::user()->username }}"
                >

            </div>

            <div class="form-group">

                <label>No Telepon</label>

                <input
                    type="text"
                    name="phone"
                    value="{{ Auth::user()->phone }}"
                >

            </div>

            <div class="button-group">

                <button
                    type="submit"
                    class="btn btn-save"
                >

                    Simpan Perubahan

                </button>

                <a
                    href="/pelayan/antar"
                    class="btn btn-back"
                >

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>