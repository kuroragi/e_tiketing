<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="/assets/logo-ticket.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="{{ Setting::get('landing_hero_subtitle', 'Layanan pengaduan dan permintaan data untuk masyarakat Kota Bukittinggi') }}">

    <title>@yield('title', Setting::get('app_name', 'Layanan Publik Kominfo Bukittinggi'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --landing-primary: {{ Setting::get('landing_primary_color', '#4f46e5') }};
            --landing-primary-dark: {{ Setting::get('landing_primary_dark', '#3730a3') }};
            --landing-secondary: #0ea5e9;
            --landing-accent: #f59e0b;
            --landing-success: #10b981;
            --landing-dark: #1e293b;
            --landing-light: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #334155;
            overflow-x: hidden;
        }

        /* ── Navbar ──────────────────────────────────────────── */
        .landing-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .landing-navbar.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .landing-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--landing-primary) !important;
        }

        .landing-navbar .navbar-brand i {
            font-size: 1.5rem;
        }

        .landing-navbar .nav-link {
            font-weight: 500;
            color: #475569 !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .landing-navbar .nav-link:hover,
        .landing-navbar .nav-link.active {
            color: var(--landing-primary) !important;
            background: rgba(79, 70, 229, 0.06);
        }

        .btn-nav-login {
            background: var(--landing-primary);
            color: #fff !important;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1.25rem !important;
            border-radius: 0.5rem;
        }

        .btn-nav-login:hover {
            background: var(--landing-primary-dark);
            color: #fff !important;
        }

        /* ── Hero Section ────────────────────────────────────── */
        .hero-section {
            background: linear-gradient(135deg, var(--landing-primary) 0%, var(--landing-primary-dark) 50%, #1e1b4b 100%);
            min-height: 600px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.9);
            padding: 0.375rem 1rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 1.25rem;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
            margin-bottom: 2rem;
            max-width: 540px;
        }

        .hero-actions .btn {
            padding: 0.75rem 1.75rem;
            font-weight: 600;
            border-radius: 0.625rem;
            font-size: 1rem;
        }

        .btn-hero-primary {
            background: #fff;
            color: var(--landing-primary);
            border: none;
        }

        .btn-hero-primary:hover {
            background: #f1f5f9;
            color: var(--landing-primary-dark);
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        /* Track ticket inline */
        .hero-track-form {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 0.75rem;
            padding: 1rem;
            max-width: 480px;
        }

        .hero-track-form .form-control {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
        }

        .hero-stats {
            position: relative;
            z-index: 2;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 0.75rem;
            padding: 1.25rem;
            text-align: center;
            backdrop-filter: blur(8px);
        }

        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        /* ── Services Section ────────────────────────────────── */
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--landing-dark);
        }

        .section-subtitle {
            color: #64748b;
            font-size: 1.05rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .service-card {
            background: #fff;
            border-radius: 1rem;
            padding: 2rem;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: var(--landing-primary);
        }

        .service-icon {
            width: 56px;
            height: 56px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .service-card h5 {
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--landing-dark);
        }

        .service-card p {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* ── How It Works ────────────────────────────────────── */
        .step-card {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--landing-primary), var(--landing-secondary));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 auto 1.25rem;
        }

        .step-card h5 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .step-card p {
            color: #64748b;
            font-size: 0.9rem;
        }

        /* ── CTA Section ─────────────────────────────────────── */
        .cta-section {
            background: linear-gradient(135deg, var(--landing-primary) 0%, var(--landing-primary-dark) 100%);
            border-radius: 1.5rem;
            padding: 4rem 2rem;
            color: #fff;
        }

        .cta-section h2 {
            font-weight: 800;
            font-size: 2rem;
        }

        .cta-section p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.05rem;
        }

        /* ── Footer ──────────────────────────────────────────── */
        .landing-footer {
            background: var(--landing-dark);
            color: #94a3b8;
        }

        .landing-footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .landing-footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .landing-footer a:hover {
            color: #fff;
        }

        .footer-brand {
            color: #fff;
            font-weight: 800;
            font-size: 1.25rem;
        }

        /* ── Responsive ──────────────────────────────────────── */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-section {
                min-height: auto;
                padding: 5rem 0 3rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.5rem;
            }
        }

        @stack('styles')
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg landing-navbar fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing') }}">
                <i class="bi bi-headset"></i>
                {{ Setting::get('app_name', 'Layanan Publik Kominfo') }}
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#landingNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="landingNav">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}"
                            href="{{ route('landing') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#layanan-publik">Layanan Publik</a>
                    </li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-nav-login">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Login Petugas
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="landing-footer py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand mb-3">
                        <i class="bi bi-headset me-2"></i>{{ Setting::get('app_name', 'Layanan Publik Kominfo') }}
                    </div>
                    <p class="small mb-3">
                        {{ Setting::get('app_description', 'Layanan pengaduan dan permintaan data publik Dinas Komunikasi dan Informatika Kota Bukittinggi.') }}
                    </p>
                    <div class="d-flex gap-2">
                        @if (Setting::get('contact_social_facebook'))
                            <a href="{{ Setting::get('contact_social_facebook') }}"
                                class="btn btn-sm btn-outline-light rounded-circle"
                                style="width:36px;height:36px;display:flex;align-items:center;justify-content:center"><i
                                    class="bi bi-facebook"></i></a>
                        @endif
                        @if (Setting::get('contact_social_instagram'))
                            <a href="{{ Setting::get('contact_social_instagram') }}"
                                class="btn btn-sm btn-outline-light rounded-circle"
                                style="width:36px;height:36px;display:flex;align-items:center;justify-content:center"><i
                                    class="bi bi-instagram"></i></a>
                        @endif
                        @if (Setting::get('contact_social_twitter'))
                            <a href="{{ Setting::get('contact_social_twitter') }}"
                                class="btn btn-sm btn-outline-light rounded-circle"
                                style="width:36px;height:36px;display:flex;align-items:center;justify-content:center"><i
                                    class="bi bi-twitter-x"></i></a>
                        @endif
                        @if (Setting::get('contact_social_youtube'))
                            <a href="{{ Setting::get('contact_social_youtube') }}"
                                class="btn btn-sm btn-outline-light rounded-circle"
                                style="width:36px;height:36px;display:flex;align-items:center;justify-content:center"><i
                                    class="bi bi-youtube"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6>Layanan Publik</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <a href="https://ppid.bukittinggikota.go.id/" target="_blank" rel="noopener noreferrer">
                                PPID Bukittinggi <i class="bi bi-box-arrow-up-right" style="font-size:0.65rem;"></i>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="https://www.lapor.go.id/" target="_blank" rel="noopener noreferrer">
                                LAPOR! <i class="bi bi-box-arrow-up-right" style="font-size:0.65rem;"></i>
                            </a>
                        </li>
                        <li class="mb-2"><a href="{{ route('login') }}">Login Petugas</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6>Informasi</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i
                                class="bi bi-telephone me-1"></i>{{ Setting::get('contact_phone', '(0752) 123-4567') }}
                        </li>
                        <li class="mb-2"><i
                                class="bi bi-envelope me-1"></i>{{ Setting::get('contact_email', 'kominfo@bukittinggi.go.id') }}
                        </li>
                        <li class="mb-2"><i
                                class="bi bi-clock me-1"></i>{{ Setting::get('contact_hours', 'Senin-Jumat, 08:00-17:00 WIB') }}
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6>Alamat</h6>
                    <p class="small">
                        <i
                            class="bi bi-geo-alt me-1"></i>{{ Setting::get('contact_address', 'Jl. Panglima Nyak Arief No. 45, Bukittinggi, Sumatera Barat') }}
                    </p>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="small mb-0">&copy; {{ date('Y') }}
                        {{ Setting::get('app_institution', 'Dinas Komunikasi dan Informatika Kota Bukittinggi') }}. All
                        rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small mb-0">Sistem E-Ticketing v1.0</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Navbar scroll effect -->
    <script>
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
