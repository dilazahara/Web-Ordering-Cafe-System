<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Lupa Password</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:
    linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.6)),
    url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085');
    background-size:cover;
}

.box{
    width:380px;
    padding:35px;
    border-radius:24px;
    backdrop-filter:blur(18px);
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.15);
    color:white;
    box-shadow:0 20px 60px rgba(0,0,0,.4);
}

h2{
    text-align:center;
    margin-bottom:25px;
}

.input-group{
    margin-bottom:18px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
}

.input-group input{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:rgba(255,255,255,.1);
    color:white;
}

.input-group input::placeholder{
    color:#ddd;
}

.btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:linear-gradient(45deg,#f59e0b,#f97316);
    color:white;
    font-weight:600;
    cursor:pointer;
}

.btn:hover{
    opacity:.9;
}

.error{
    background:#ef4444;
    padding:12px;
    border-radius:12px;
    margin-bottom:15px;
    font-size:13px;
}

.success{
    background:#22c55e;
    padding:12px;
    border-radius:12px;
    margin-bottom:15px;
    font-size:13px;
}

.back{
    display:block;
    text-align:center;
    margin-top:18px;
    color:#fff;
    text-decoration:none;
}

</style>
</head>
<body>

<form class="box" method="POST" action="/forgot-password">
@csrf

<h2>Lupa Password</h2>

@if(session('error'))
<div class="error">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="success">
    {{ session('success') }}
</div>
@endif

<div class="input-group">
<label>Email</label>
<input type="email" name="email" placeholder="Masukkan email" required>
</div>

<div class="input-group">
<label>Password Baru</label>
<input type="password" name="password" placeholder="Password baru" required>
</div>

<div class="input-group">
<label>Konfirmasi Password</label>
<input type="password" name="password_confirmation" placeholder="Ulang password" required>
</div>

<button class="btn">
Reset Password
</button>

<a href="/login" class="back">
Kembali ke Login
</a>

</form>

</body>
</html>