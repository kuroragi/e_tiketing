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

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0b5ed7;
            --text-primary: #212529;
            --bg-light: #f8f9fa;
            --border-color: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0a58ca 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: white;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0a58ca 100%);
            padding: 2rem 1.5rem;
            text-align: center;
            color: white;
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            margin: 0;
        }

        .login-header .logo-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
        }

        .login-body {
            padding: 2.5rem 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .form-label .required {
            color: #dc3545;
        }

        .form-control {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .input-group-text {
            background-color: var(--bg-light);
            border: 1px solid var(--border-color);
            color: #6b7280;
        }

        .form-check {
            margin-bottom: 1rem;
        }

        .form-check-input {
            width: 1.1rem;
            height: 1.1rem;
            margin-top: 0.2rem;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .form-check-label {
            margin-bottom: 0;
            cursor: pointer;
            user-select: none;
            font-size: 0.95rem;
            color: var(--text-primary);
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(13, 110, 253, 0.2);
        }

        .btn-login:disabled {
            background-color: #b3bcc9;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }

        .login-divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background-color: var(--border-color);
        }

        .login-divider::before {
            left: 0;
        }

        .login-divider::after {
            right: 0;
        }

        .login-divider-text {
            font-size: 0.85rem;
            color: #9ca3af;
            background: white;
            padding: 0 0.5rem;
            position: relative;
            z-index: 1;
        }

        .login-footer {
            background-color: #2d3748;
            color: #e2e8f0;
            padding: 2.5rem 1.5rem 1.5rem;
            margin-top: 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-section h3 i {
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #cbd5e0;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 0.5rem;
        }

        .footer-links a i {
            font-size: 0.85rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .footer-links a:hover i {
            opacity: 1;
        }

        .footer-divider {
            border-top: 1px solid #4a5568;
            margin: 2rem 0 1.5rem 0;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #4a5568;
        }

        .footer-bottom p {
            margin: 0;
            font-size: 0.85rem;
            color: #a0aec0;
        }

        .footer-security {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .footer-security i {
            color: #10b981;
            font-size: 1rem;
        }

        .footer-security span {
            color: #10b981;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .login-footer {
                padding: 2rem 1rem 1.5rem;
            }
        }

        /* Alert Styles */
        .alert {
            border-radius: 0.375rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: #fee;
            color: #842029;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-danger i {
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-body {
                padding: 2rem 1rem;
            }

            .login-header {
                padding: 1.5rem 1rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .login-header .logo-icon {
                font-size: 2rem;
            }
        }

        /* Loading Spinner Animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner-border {
            animation: spin 0.75s linear infinite;
        }

        /* Accessibility */
        .form-control:focus,
        .btn-login:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Print Styles */
        @media print {
            body {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="logo-icon">
                    <i class="bi bi-ticket-detailed"></i>
                </div>
                <h1>Ticketing Kominfo</h1>
                <p>Sistem Manajemen Tiket Layanan Kominfo Kota Bukittinggi</p>
            </div>

            <!-- Body -->
            <div class="login-body">
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

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                    @csrf

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i>
                            Alamat Email
                            <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="nama@kominfo.bukittinggi.go.id"
                            required autocomplete="email" autofocus>
                        @error('email')
                            <small class="d-block text-danger mt-2">
                                <i class="bi bi-info-circle"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i>
                            Kata Sandi
                            <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Masukkan kata sandi" required
                                autocomplete="current-password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <small class="d-block text-danger mt-2">
                                <i class="bi bi-info-circle"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Ingat akun saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login" id="submitBtn">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Masuk</span>
                    </button>
                </form>

                <!-- Info Message -->
                <div class="login-divider">
                    <span class="login-divider-text">Informasi</span>
                </div>

                <div class="alert alert-info mb-2"
                    style="background-color: #e7f5ff; border-left: 4px solid var(--primary-color); color: #1971c2; border: none; padding: 0.75rem 1rem;">
                    <i class="bi bi-info-circle"></i>
                    <strong>Pengguna baru?</strong> Hubungi admin Kominfo untuk membuat akun.
                </div>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <div class="footer-content">
                    <!-- Navigasi Cepat -->
                    <div class="footer-section">
                        <h3>
                            <i class="bi bi-compass"></i>
                            Navigasi Cepat
                        </h3>
                        <ul class="footer-links">
                            <li>
                                <a href="{{ route('panduan') }}">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Panduan Penggunaan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tentang') }}">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Tentang Sistem</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('hubungi') }}">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Hubungi Kami</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Dukungan -->
                    <div class="footer-section">
                        <h3>
                            <i class="bi bi-question-circle"></i>
                            Dukungan
                        </h3>
                        <ul class="footer-links">
                            <li>
                                <a href="#">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>FAQ</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('hubungi') }}">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Hubungi Support</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Status Sistem</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Informasi -->
                    <div class="footer-section">
                        <h3>
                            <i class="bi bi-info-circle"></i>
                            Informasi
                        </h3>
                        <ul class="footer-links">
                            <li>
                                <a href="{{ route('kebijakan') }}">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Kebijakan Privasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('syarat-ketentuan') }}">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Syarat & Ketentuan</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Versi v1.0</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="footer-divider"></div>

                <!-- Footer Bottom -->
                <div class="footer-bottom">
                    <div class="footer-security">
                        <i class="bi bi-shield-check"></i>
                        <span>Halaman ini aman dan terenkripsi (HTTPS)</span>
                    </div>
                    <p>
                        &copy; 2026 Dinas Komunikasi dan Informatika Kota Bukittinggi. Semua hak dilindungi.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.2 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        // Form Submission Loading State
        document.getElementById('loginForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('loading');
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>Memproses...</span>';
            submitBtn.disabled = true;
        });

        // Keyboard Navigation
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && event.ctrlKey) {
                document.getElementById('loginForm').submit();
            }
        });

        // Focus Management
        document.getElementById('email').focus();
    </script>
</body>

</html>
