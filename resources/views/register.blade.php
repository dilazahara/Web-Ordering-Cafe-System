<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Momoo Order</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;

            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                        url('https://images.unsplash.com/photo-1509042239860-f550ce710b93');
            background-size: cover;
            background-position: center;
        }

        .register-box {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 20px;
            width: 350px;
            color: white;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .logo img {
            width: 60px;
            margin-bottom: 10px;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: none;
            margin-top: 5px;
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg,#d4af37,#f4e4bc);
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
        }

        .login-text {
            margin-top: 10px;
            font-size: 14px;
        }

        .login-link {
            color: #4da6ff;
            text-decoration: none;
        }

        /* BUTTON KEMBALI */
        .back-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 15px;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            backdrop-filter: blur(5px);
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #d4af37;
            color: black;
        }
    </style>
</head>

<body>

<form class="register-box" method="POST" action="/register">
    @csrf

    <div class="logo">
        <img src="{{ asset('logo.png') }}">
    </div>

    <h2>Daftar Akun</h2>

    <div class="input-group">
        <label>Daftar sebagai</label>
        <select name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="admin">Admin</option>
            <option value="kasir">Kasir</option>
            <option value="pelayan">Pelayan</option>
        </select>
    </div>

    <div class="input-group">
        <label>Nama</label>
        <input type="text" name="name" required>
    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <div class="input-group">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <button class="register-btn">Daftar</button>

    <p class="login-text">
        Sudah punya akun? 
        <a href="/login" class="login-link">Login disini</a>
    </p>

</form>

<!-- BUTTON KEMBALI KE Home -->
<a href="{{ url('/home') }}" class="back-btn">← Beranda</a>

</body>
</html>