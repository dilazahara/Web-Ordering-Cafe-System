<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Momoo Order</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
        :root {
            --primary: #D4A017;
            --primary-dark: #B8860B;
            --primary-light: rgba(212, 160, 23, 0.1);
            --bg-light: #F8F9FD;
            --card-bg: #ffffff;
            --input-bg: #F3F4F6;
            --border: #E5E7EB;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --text-mid: #4B5563;
            --error: #DC2626;
            --error-light: #FEF2F2;
            --error-border: #FECACA;
            --success: #16A34A;
            --success-light: #F0FDF4;
            --success-border: #BBF7D0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(243,244,246,0.88) 100%),
                url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 20px;
            color: var(--text-main);
        }

        /* ── WRAPPER ─────────────────────────────── */
        .wrapper {
            width: 100%;
            max-width: 460px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        /* ── CARD SHARED ─────────────────────────── */
        .card {
            background: var(--card-bg);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 28px;
            padding: 36px 36px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.06);
        }

        /* ── STEP CONNECTOR ──────────────────────── */
        .step-connector {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 20px;
            margin: -1px 0;
            position: relative;
            z-index: 10;
        }
        .step-connector-line { flex: 1; height: 1px; background: var(--border); }
        .step-connector-dot {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            color: var(--primary);
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        /* ── HEADER ──────────────────────────────── */
        .header {
            text-align: center;
            margin-bottom: 28px;
            position: relative; z-index: 1;
        }

        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 16px;
        }
        .logo-wrapper img {
            width: 65px;
            height: auto;
            object-fit: contain;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: var(--text-main);
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }
        .subtitle {
            font-size: 14px;
            color: var(--text-muted);
        }

        /* ── ROLE GRID ───────────────────────────── */
        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            position: relative; z-index: 1;
        }
        .role-item {
            background: #F9FAFB;
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 18px 16px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 13px;
            position: relative;
        }
        .role-item:hover {
            border-color: var(--primary);
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.04);
        }
        .role-item.selected {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-light);
        }
        .role-item.selected .ri-check {
            opacity: 1; transform: scale(1);
        }

        .ri-check {
            position: absolute;
            top: 10px; right: 10px;
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            opacity: 0;
            transform: scale(0.6);
            transition: all 0.2s ease;
        }
        .ri-check i { font-size: 11px; color: #fff; }

        .ri-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .ri-icon i { font-size: 20px; }

        .icon-admin   { background: #EEF2FF; } .icon-admin i   { color: #4F46E5; }
        .icon-kasir   { background: #ECFDF5; } .icon-kasir i   { color: #10B981; }
        .icon-dapur   { background: #FFF7ED; } .icon-dapur i   { color: #F97316; }
        .icon-pelayan { background: #FEF9C3; } .icon-pelayan i { color: #CA8A04; }

        .ri-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 2px;
        }
        .ri-sub {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* ── LANJUTKAN BUTTON ───────────────────── */
        .btn-lanjutkan {
            width: 100%;
            margin-top: 24px;
            padding: 14px;
            background: var(--primary);
            border: none;
            border-radius: 16px;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(212,160,23,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-lanjutkan:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(212,160,23,0.35);
        }
        .btn-lanjutkan i { font-size: 18px; }

        /* ── LOGIN FORM ──────────────────────────── */
        .section-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            margin-bottom: 24px;
            text-align: center;
        }

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-mid);
            margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-wrap > i.icon-left {
            position: absolute;
            left: 16px; top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--text-muted);
            pointer-events: none;
            transition: color 0.2s;
        }
        .form-group input {
            width: 100%;
            background: var(--input-bg);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 14px 16px 14px 48px;
            color: var(--text-main);
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px var(--primary-light);
        }
        .form-group input:focus + .icon-left,
        .input-wrap:focus-within > i.icon-left {
            color: var(--primary);
        }

        /* Input error state */
        .form-group.has-error input {
            border-color: var(--error) !important;
            background: var(--error-light) !important;
            box-shadow: 0 0 0 4px rgba(220,38,38,0.08) !important;
        }
        .form-group.has-error .icon-left {
            color: var(--error) !important;
        }

        /* Pesan error per-field */
        .field-error {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 7px;
            font-size: 12px;
            font-weight: 600;
            color: var(--error);
            animation: slideDown 0.2s ease-out;
        }
        .field-error i { font-size: 13px; flex-shrink: 0; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── ALERT GLOBAL ───────────────────────── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 22px;
            line-height: 1.5;
        }
        .alert i { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
        .alert-error {
            background: var(--error-light);
            border: 1px solid var(--error-border);
            color: var(--error);
        }
        .alert-success {
            background: var(--success-light);
            border: 1px solid var(--success-border);
            color: var(--success);
        }

        /* ── SHAKE ANIMATION ─────────────────────── */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            15%       { transform: translateX(-7px); }
            30%       { transform: translateX(7px); }
            45%       { transform: translateX(-5px); }
            60%       { transform: translateX(5px); }
            75%       { transform: translateX(-3px); }
            90%       { transform: translateX(3px); }
        }
        .shake { animation: shake 0.5s ease-in-out; }

        .toggle-pw {
            position: absolute;
            right: 16px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; color: var(--text-muted);
            transition: color 0.2s;
            padding: 0;
            display: flex; align-items: center;
        }
        .toggle-pw:hover { color: var(--primary-dark); }
        .toggle-pw i { font-size: 18px; }

        /* Remember me */
        .remember-wrap { margin-bottom: 28px; }
        .remember {
            display: flex; align-items: center; gap: 8px;
            font-size: 14px; color: var(--text-mid); cursor: pointer;
        }
        .remember input { accent-color: var(--primary); width: 16px; height: 16px; }

        .role-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0;
        }
        .role-tag i { color: var(--primary-dark); }

        .btn-ganti-role {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: none;
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            padding: 0;
            margin-left: 8px;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        /* ── TOMBOL LOGIN ──────────────────────── */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--text-main);
            border: none;
            border-radius: 16px;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover:not(:disabled) {
            background: #000;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading spinner di dalam tombol */
        .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .register {
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 24px;
        }
        .register a { color: var(--primary-dark); font-weight: 700; text-decoration: none; }

        /* ── ANIMASI MASUK ───────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .role-card-wrap { animation: fadeUp 0.5s ease-out both; }
        .login-wrap { animation: fadeUp 0.5s ease-out both; }

        /* ── SHOW/HIDE CREDENTIAL SECTION ───────── */
        .step-connector,
        .login-wrap {
            display: none;
            opacity: 0;
            transform: translateY(16px);
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .step-connector.visible,
        .login-wrap.visible {
            display: flex;
            opacity: 1;
            transform: translateY(0);
        }
        .login-wrap.visible { display: block; }

        @media (max-width: 480px) {
            .card { padding: 30px 20px; }
            .role-grid { gap: 10px; }
        }

        /* ── PASSWORD STRENGTH ─────────────────── */
        .strength-bar-wrap {
            margin-top: 8px;
            display: none;
        }
        .strength-bar {
            display: flex;
            gap: 4px;
            margin-bottom: 4px;
        }
        .strength-seg {
            height: 3px;
            flex: 1;
            border-radius: 99px;
            background: #E5E7EB;
            transition: background 0.3s;
        }
        .strength-hint {
            font-size: 11px;
            font-weight: 600;
            color: #9CA3AF;
        }
    </style>
</head>
<body>

<div class="wrapper">

    {{-- ── KARTU PILIH ROLE ── --}}
    <div class="card role-card-wrap">
        <div class="header">
            <div class="logo-wrapper">
                <img src="{{ asset('logo.png') }}" alt="Logo Momoo Order">
            </div>
            <h1>Selamat Datang</h1>
            <p class="subtitle">Pilih akses masuk sesuai posisi Anda</p>
        </div>

        {{-- Alert sukses (setelah logout) --}}
        @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <i class="ti ti-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <div class="role-grid">
            <div class="role-item" onclick="selectRole(this, 'Admin', 'ti-shield-check')">
                <div class="ri-check"><i class="ti ti-check"></i></div>
                <div class="ri-icon icon-admin"><i class="ti ti-shield-check"></i></div>
                <div class="ri-text">
                    <p class="ri-name">Admin</p>
                    <p class="ri-sub">Kelola sistem</p>
                </div>
            </div>

            <div class="role-item" onclick="selectRole(this, 'Kasir', 'ti-receipt')">
                <div class="ri-check"><i class="ti ti-check"></i></div>
                <div class="ri-icon icon-kasir"><i class="ti ti-receipt"></i></div>
                <div class="ri-text">
                    <p class="ri-name">Kasir</p>
                    <p class="ri-sub">Transaksi</p>
                </div>
            </div>

            <div class="role-item" onclick="selectRole(this, 'Dapur', 'ti-flame')">
                <div class="ri-check"><i class="ti ti-check"></i></div>
                <div class="ri-icon icon-dapur"><i class="ti ti-flame"></i></div>
                <div class="ri-text">
                    <p class="ri-name">Dapur</p>
                    <p class="ri-sub">Masak pesanan</p>
                </div>
            </div>

            <div class="role-item" onclick="selectRole(this, 'Pelayan', 'ti-user-star')">
                <div class="ri-check"><i class="ti ti-check"></i></div>
                <div class="ri-icon icon-pelayan"><i class="ti ti-user-star"></i></div>
                <div class="ri-text">
                    <p class="ri-name">Pelayan</p>
                    <p class="ri-sub">Antar pesanan</p>
                </div>
            </div>
        </div>

        <button class="btn-lanjutkan" id="btn-lanjutkan" style="display:none;" onclick="showCredential()">
            Lanjutkan <i class="ti ti-arrow-right"></i>
        </button>
    </div>

    {{-- ── KONEKTOR STEP ── --}}
    <div class="step-connector" id="step-connector">
        <div class="step-connector-line"></div>
        <div class="step-connector-dot"><i class="ti ti-lock"></i></div>
        <div class="step-connector-line"></div>
    </div>

    {{-- ── KARTU LOGIN ── --}}
    <div class="card login-wrap" id="login-wrap">
        <p class="section-title">Kredensial Login</p>

        {{-- Alert error global dari session / errors --}}
        @if($errors->has('email') && !request()->old('email'))
        <div class="alert alert-error" id="global-error">
            <i class="ti ti-alert-circle"></i>
            <span>{{ $errors->first('email') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="ti ti-alert-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <div style="text-align: center; display: flex; align-items: center; justify-content: center; gap: 4px; margin-bottom: 0;">
            <div class="role-tag" id="role-tag" style="margin-bottom: 0;">
                <i class="ti ti-shield-check" id="tag-icon"></i>
                <span id="tag-label">Admin</span>
            </div>
            <button type="button" class="btn-ganti-role" onclick="gantiRole()" title="Ganti role">
                <i class="ti ti-refresh"></i> Ganti
            </button>
        </div>

        <br>

        <form method="POST" action="/login" id="login-form" novalidate>
            @csrf
            <input type="hidden" name="role" id="role-input" value="">

            {{-- ── EMAIL ── --}}
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}" id="group-email">
                <label for="email-input">Alamat Email</label>
                <div class="input-wrap">
                    <i class="ti ti-mail icon-left"></i>
                    <input
                        type="email"
                        name="email"
                        id="email-input"
                        placeholder="nama@email.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                        oninput="clearFieldError('group-email', 'err-email')"
                    >
                </div>
                @if($errors->has('email'))
                <div class="field-error" id="err-email">
                    <i class="ti ti-alert-circle"></i>
                    <span>{{ $errors->first('email') }}</span>
                </div>
                @else
                <div class="field-error" id="err-email" style="display:none;">
                    <i class="ti ti-alert-circle"></i>
                    <span id="err-email-text"></span>
                </div>
                @endif
            </div>

            {{-- ── PASSWORD ── --}}
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}" id="group-password">
                <label for="pw-input">Password</label>
                <div class="input-wrap">
                    <i class="ti ti-lock icon-left"></i>
                    <input
                        type="password"
                        name="password"
                        id="pw-input"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        oninput="clearFieldError('group-password', 'err-password')"
                    >
                    <button type="button" class="toggle-pw" onclick="togglePw()" tabindex="-1">
                        <i class="ti ti-eye" id="pw-icon"></i>
                    </button>
                </div>
                @if($errors->has('password'))
                <div class="field-error" id="err-password">
                    <i class="ti ti-alert-circle"></i>
                    <span>{{ $errors->first('password') }}</span>
                </div>
                @else
                <div class="field-error" id="err-password" style="display:none;">
                    <i class="ti ti-alert-circle"></i>
                    <span id="err-password-text"></span>
                </div>
                @endif
            </div>

            <div class="remember-wrap">
                <label class="remember">
                    <input type="checkbox" name="remember"> Ingat Sesi
                </label>
            </div>

            <button type="submit" class="btn-login" id="btn-login">
                <div class="spinner" id="login-spinner"></div>
                <span id="btn-login-text">Masuk ke Dashboard</span>
            </button>
        </form>

        <div class="register">
            Belum punya akun? <a href="#">Hubungi Admin</a>
        </div>
    </div>
</div>

<script>
    /* ── MAP ROLE ──────────────────────────── */
    const roleIconMap = {
        'Admin':   'ti-shield-check',
        'Kasir':   'ti-receipt',
        'Dapur':   'ti-flame',
        'Pelayan': 'ti-user-star',
    };

    let selectedRoleName = null;
    let selectedRoleIcon = null;

    /* ── Pilih role ─────────────────────────── */
    function selectRole(el, name, icon) {
        document.querySelectorAll('.role-item').forEach(r => r.classList.remove('selected'));
        el.classList.add('selected');
        selectedRoleName = name;
        selectedRoleIcon = icon;

        const btnLanjutkan = document.getElementById('btn-lanjutkan');
        btnLanjutkan.style.display = 'flex';
        btnLanjutkan.style.animation = 'none';
        void btnLanjutkan.offsetWidth;
        btnLanjutkan.style.animation = 'fadeUp 0.3s ease-out both';
    }

    /* ── Tampilkan form kredensial ──────────── */
    function showCredential() {
        if (!selectedRoleName) return;

        document.getElementById('btn-lanjutkan').style.display = 'none';
        document.getElementById('role-input').value = selectedRoleName;
        document.getElementById('tag-label').textContent = selectedRoleName;

        const tagIcon = document.getElementById('tag-icon');
        tagIcon.className = 'ti ' + (roleIconMap[selectedRoleName] || selectedRoleIcon);

        const connector = document.getElementById('step-connector');
        connector.classList.add('visible');

        setTimeout(() => {
            const loginWrap = document.getElementById('login-wrap');
            loginWrap.classList.add('visible');
            loginWrap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    }

    /* ── Ganti role ─────────────────────────── */
    function gantiRole() {
        document.getElementById('step-connector').classList.remove('visible');
        document.getElementById('login-wrap').classList.remove('visible');
        document.querySelectorAll('.role-item').forEach(r => r.classList.remove('selected'));
        document.getElementById('btn-lanjutkan').style.display = 'none';
        selectedRoleName = null;
        selectedRoleIcon = null;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* ── Toggle show/hide password ──────────── */
    function togglePw() {
        const pw   = document.getElementById('pw-input');
        const icon = document.getElementById('pw-icon');
        if (pw.type === 'password') {
            pw.type = 'text';
            icon.className = 'ti ti-eye-off';
        } else {
            pw.type = 'password';
            icon.className = 'ti ti-eye';
        }
    }

    /* ── Hapus error saat user mulai mengetik ── */
    function clearFieldError(groupId, errId) {
        const group = document.getElementById(groupId);
        const err   = document.getElementById(errId);
        if (group)  group.classList.remove('has-error');
        if (err)    err.style.display = 'none';

        // Hapus juga alert global
        const globalErr = document.getElementById('global-error');
        if (globalErr) globalErr.remove();
    }

    /* ── Tampilkan error pada field ─────────── */
    function showFieldError(groupId, errId, errTextId, message) {
        const group   = document.getElementById(groupId);
        const err     = document.getElementById(errId);
        const errText = document.getElementById(errTextId);

        if (group)   group.classList.add('has-error');
        if (err)     err.style.display = 'flex';
        if (errText) errText.textContent = message;
    }

    /* ── Shake animasi pada elemen ─────────── */
    function shake(el) {
        el.classList.remove('shake');
        void el.offsetWidth;
        el.classList.add('shake');
        el.addEventListener('animationend', () => el.classList.remove('shake'), { once: true });
    }

    /* ── Reset tombol login ke state normal ─────────────────── */
    function resetBtn() {
        const btn     = document.getElementById('btn-login');
        const spinner = document.getElementById('login-spinner');
        const btnText = document.getElementById('btn-login-text');
        if (btn) btn.disabled = false;
        if (spinner) spinner.style.display = 'none';
        if (btnText) btnText.textContent   = 'Masuk ke Dashboard';
    }

    // Reset tombol setiap kali halaman ditampilkan
    window.addEventListener('pageshow', resetBtn);
    document.addEventListener('DOMContentLoaded', resetBtn);

    /* ── Validasi & loading state via form submit event ──────── */
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('login-form');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            const email    = document.getElementById('email-input').value.trim();
            const password = document.getElementById('pw-input').value;
            let valid = true;

            // Cek role dipilih
            if (!document.getElementById('role-input').value) {
                shake(document.getElementById('login-wrap'));
                valid = false;
            }

            // Validasi email
            if (!email) {
                showFieldError('group-email', 'err-email', 'err-email-text', 'Email wajib diisi.');
                shake(document.getElementById('group-email'));
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showFieldError('group-email', 'err-email', 'err-email-text', 'Format email tidak valid.');
                shake(document.getElementById('group-email'));
                valid = false;
            }

            // Validasi password
            if (!password) {
                showFieldError('group-password', 'err-password', 'err-password-text', 'Password wajib diisi.');
                shake(document.getElementById('group-password'));
                valid = false;
            } else if (password.length < 6) {
                showFieldError('group-password', 'err-password', 'err-password-text', 'Password minimal 6 karakter.');
                shake(document.getElementById('group-password'));
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                return;
            }

            // Tampilkan loading state
            const btn     = document.getElementById('btn-login');
            const spinner = document.getElementById('login-spinner');
            const btnText = document.getElementById('btn-login-text');
            btn.disabled          = true;
            spinner.style.display = 'block';
            btnText.textContent   = 'Sedang masuk...';

            // Safety timeout 10 detik — reset kalau server tidak merespons
            setTimeout(resetBtn, 10000);
        });
    });

    /* ── Auto-buka form kredensial jika ada error dari server ── */
    @if($errors->any() || old('email'))
    window.addEventListener('DOMContentLoaded', function () {
        @php
            $oldRole = old('role') ?? '';
            $roleMap = ['Admin' => 'ti-shield-check', 'Kasir' => 'ti-receipt', 'Dapur' => 'ti-flame', 'Pelayan' => 'ti-user-star'];
            $roleIcon = $roleMap[$oldRole] ?? 'ti-user';
        @endphp

        selectedRoleName = '{{ $oldRole }}' || 'Admin';
        selectedRoleIcon = '{{ $roleIcon }}';

        // Tandai role yang sebelumnya dipilih
        document.querySelectorAll('.role-item').forEach(function(el) {
            const name = el.querySelector('.ri-name')?.textContent?.trim();
            if (name === selectedRoleName) el.classList.add('selected');
        });

        document.getElementById('role-input').value       = selectedRoleName;
        document.getElementById('tag-label').textContent  = selectedRoleName;
        document.getElementById('tag-icon').className     = 'ti ' + selectedRoleIcon;

        // Tampilkan langsung tanpa animasi
        const connector = document.getElementById('step-connector');
        const loginWrap = document.getElementById('login-wrap');
        connector.style.transition = 'none';
        loginWrap.style.transition = 'none';
        connector.classList.add('visible');
        loginWrap.classList.add('visible');

        // Shake form jika ada error
        @if($errors->any())
        setTimeout(() => shake(loginWrap), 200);
        @endif
    });
    @endif
</script>

</body>
</html>