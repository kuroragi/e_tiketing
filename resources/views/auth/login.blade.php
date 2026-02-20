<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Sistem Ticketing Layanan Kominfo</title>

    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons 1.11.1 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0a58ca;
            --primary-deeper: #084298;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* ─── Wrapper ─────────────────────────────────────────── */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ─── Main Two-Column Section ─────────────────────────── */
        .login-main {
            flex: 1;
            display: flex;
        }

        /* ─── Left Panel (branding) ───────────────────────────── */
        .login-panel {
            flex: 0 0 45%;
            background: linear-gradient(150deg, var(--primary) 0%, var(--primary-deeper) 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 3.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-panel::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
        }

        .login-panel::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -60px;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .panel-logo {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .panel-title {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .panel-subtitle {
            font-size: 1rem;
            opacity: .85;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .panel-features {
            list-style: none;
            padding: 0;
            position: relative;
            z-index: 1;
        }

        .panel-features li {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: .9rem;
            font-size: .95rem;
            opacity: .9;
        }

        .panel-features li i {
            font-size: 1.15rem;
            background: rgba(255, 255, 255, .2);
            border-radius: 50%;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .panel-badge {
            margin-top: 2.5rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 2rem;
            padding: .45rem 1rem;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .5px;
            position: relative;
            z-index: 1;
        }

        /* ─── Right Panel (form) ──────────────────────────────── */
        .login-form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8f9fb;
        }

        .login-form-inner {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 4rem;
            max-width: 520px;
            width: 100%;
            margin: 0 auto;
        }

        .form-heading {
            margin-bottom: 2rem;
        }

        .form-heading h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: .4rem;
        }

        .form-heading p {
            font-size: .95rem;
            color: #64748b;
        }

        .form-label {
            font-weight: 600;
            font-size: .875rem;
            color: #374151;
            margin-bottom: .45rem;
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .form-label .req {
            color: #ef4444;
        }

        .form-control {
            padding: .65rem .9rem;
            border: 1.5px solid #e2e8f0;
            border-radius: .5rem;
            font-size: .95rem;
            background: white;
            transition: border-color .2s, box-shadow .2s;
            color: #1e293b;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, .12);
            outline: none;
            background: white;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .input-group .form-control {
            border-right: none;
            border-radius: .5rem 0 0 .5rem;
        }

        .input-group-text {
            background: white;
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-radius: 0 .5rem .5rem 0;
            color: #6b7280;
            cursor: pointer;
            transition: color .2s;
        }

        .input-group-text:hover {
            color: var(--primary);
        }

        .form-control.is-invalid {
            border-color: #ef4444 !important;
        }

        .form-check-input {
            width: 1.1rem;
            height: 1.1rem;
            border: 1.5px solid #d1d5db;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            font-size: .9rem;
            color: #4b5563;
            cursor: pointer;
        }

        /* ─── Submit Button ──────────────────────────────────── */
        .btn-submit {
            width: 100%;
            padding: .8rem 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: .5rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s, opacity .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            letter-spacing: .2px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, .35);
        }

        .btn-submit:active {
            transform: none;
        }

        .btn-submit:disabled {
            opacity: .65;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* ─── Alert ──────────────────────────────────────────── */
        .alert {
            border-radius: .5rem;
            border: none;
            padding: .8rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            font-size: .9rem;
            margin-bottom: 1.5rem;
        }

        .alert i {
            margin-top: .05rem;
            flex-shrink: 0;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 3px solid #22c55e;
        }

        .alert-info {
            background: #eff6ff;
            color: #1e40af;
            border-left: 3px solid var(--primary);
        }

        /* ─── Divider ─────────────────────────────────────────── */
        .form-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #9ca3af;
            font-size: .8rem;
            margin: 1.5rem 0;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ─── Footer Bar ─────────────────────────────────────── */
        .login-footer {
            background: #1e293b;
            color: #94a3b8;
        }

        .footer-links-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            padding: 1rem 2rem;
            border-bottom: 1px solid #334155;
        }

        .footer-links-bar a {
            color: #94a3b8;
            text-decoration: none;
            font-size: .82rem;
            transition: color .2s;
        }

        .footer-links-bar a:hover {
            color: white;
        }

        .footer-links-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .footer-bottom-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            padding: .85rem 2rem;
        }

        .footer-bottom-bar p {
            font-size: .8rem;
            margin: 0;
        }

        .footer-secure {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .8rem;
            color: #10b981;
        }

        /* ─── Responsive ─────────────────────────────────────── */
        @media (max-width: 991px) {
            .login-panel {
                flex: 0 0 40%;
                padding: 3rem 2.5rem;
            }

            .login-form-inner {
                padding: 2.5rem 2.5rem;
            }

            .panel-title {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 767px) {
            .login-main {
                flex-direction: column;
            }

            .login-panel {
                flex: none;
                padding: 2.5rem 1.5rem;
                text-align: center;
            }

            .panel-logo {
                font-size: 2.75rem;
                margin-bottom: 1rem;
            }

            .panel-title {
                font-size: 1.5rem;
            }

            .panel-subtitle {
                font-size: .9rem;
                margin-bottom: 1.25rem;
            }

            .panel-features {
                display: none;
            }

            .panel-badge {
                margin: 1rem auto 0;
            }

            .login-form-section {
                background: white;
            }

            .login-form-inner {
                padding: 2rem 1.25rem;
                max-width: 100%;
            }

            .footer-links-bar,
            .footer-bottom-bar {
                padding: .85rem 1rem;
            }

            .footer-links-group {
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .login-panel {
                padding: 2rem 1rem;
            }

            .login-form-inner {
                padding: 1.75rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">

        <!-- ─── Main ─────────────────────────────────────────────── -->
        <div class="login-main">

            <!-- Left branding panel -->
            <div class="login-panel">
                <div class="panel-logo">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>
                <h1 class="panel-title">Sistem Ticketing<br>Layanan Kominfo</h1>
                <p class="panel-subtitle">
                    Platform pengelolaan tiket layanan Dinas Komunikasi dan Informatika Kota Bukittinggi — cepat,
                    terstruktur, dan terpantau.
                </p>
                <ul class="panel-features">
                    <li>
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span>Pengajuan tiket layanan secara online</span>
                    </li>
                    <li>
                        <i class="bi bi-people-fill"></i>
                        <span>Penugasan petugas otomatis & manual</span>
                    </li>
                    <li>
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <span>Monitoring & laporan real-time</span>
                    </li>
                    <li>
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Akses berbasis peran yang aman</span>
                    </li>
                </ul>
                <div class="panel-badge">
                    <i class="bi bi-buildings"></i>
                    Pemkot Bukittinggi &mdash; 2026
                </div>
            </div>

            <!-- Right form section -->
            <div class="login-form-section">
                <div class="login-form-inner">

                    <div class="form-heading">
                        <h2>Selamat Datang</h2>
                        <p>Masuk untuk mengakses sistem ticketing layanan.</p>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <div>
                                @if ($errors->has('email'))
                                    <strong>{{ $errors->first('email') }}</strong>
                                @elseif ($errors->has('password'))
                                    <strong>{{ $errors->first('password') }}</strong>
                                @else
                                    <strong>Informasi login tidak sesuai. Silakan coba lagi.</strong>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>{{ session('status') }}</div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i>
                                Alamat Email
                                <span class="req">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}"
                                placeholder="nama@kominfo.bukittinggi.go.id" required autocomplete="email" autofocus>
                            @error('email')
                                <small class="d-block text-danger mt-1">
                                    <i class="bi bi-info-circle"></i> {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i>
                                Kata Sandi
                                <span class="req">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Masukkan kata sandi" required
                                    autocomplete="current-password">
                                <span class="input-group-text" id="togglePassword" title="Tampilkan/sembunyikan">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </span>
                            </div>
                            @error('password')
                                <small class="d-block text-danger mt-1">
                                    <i class="bi bi-info-circle"></i> {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Ingat akun saya</label>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Masuk ke Sistem</span>
                        </button>
                    </form>

                    <div class="form-divider">Informasi</div>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle-fill"></i>
                        <div><strong>Pengguna baru?</strong> Hubungi admin Kominfo untuk membuat akun.</div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ─── Footer ───────────────────────────────────────────── -->
        <footer class="login-footer">
            <div class="footer-links-bar">
                <div class="footer-links-group">
                    <a href="{{ route('panduan') }}">Panduan Penggunaan</a>
                    <a href="{{ route('tentang') }}">Tentang Sistem</a>
                    <a href="{{ route('hubungi') }}">Hubungi Kami</a>
                    <a href="{{ route('kebijakan') }}">Kebijakan Privasi</a>
                    <a href="{{ route('syarat-ketentuan') }}">Syarat & Ketentuan</a>
                </div>
                <div class="footer-secure">
                    <i class="bi bi-shield-check-fill"></i>
                    <span>Terenkripsi &amp; Aman</span>
                </div>
            </div>
            <div class="footer-bottom-bar">
                <p>&copy; 2026 Dinas Komunikasi dan Informatika Kota Bukittinggi. Semua hak dilindungi.</p>
                <p>Versi v1.0</p>
            </div>
        </footer>

    </div>

    <!-- Bootstrap 5.3.2 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const pw = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                pw.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status"></span><span>Memproses...</span>';
        });

        // Auto-focus
        document.getElementById('email').focus();
    </script>
</body>

</html>
