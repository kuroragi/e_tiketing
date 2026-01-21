<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Ticketing Layanan Kominfo Kota Bukittinggi')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0b5ed7;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
            --dark-color: #212529;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-gray);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.1rem;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            border-bottom: 3px solid var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.2s ease-in-out;
            border-left: 4px solid var(--border-color);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card.card-primary {
            border-left-color: var(--primary-color);
        }

        .card.card-success {
            border-left-color: var(--success-color);
        }

        .card.card-warning {
            border-left-color: var(--warning-color);
        }

        .card.card-danger {
            border-left-color: var(--danger-color);
        }

        .sidebar {
            background-color: white;
            min-height: calc(100vh - 76px);
            border-right: 1px solid var(--border-color);
        }

        .sidebar .nav-link {
            color: #6c757d;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem;
            margin: 0.125rem 0;
            transition: all 0.2s ease-in-out;
        }

        .sidebar .nav-link:hover {
            background-color: var(--light-gray);
            color: var(--primary-color);
        }

        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .main-content {
            background-color: var(--light-gray);
            min-height: calc(100vh - 76px);
            padding: 1.5rem;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
        }

        .status-baru {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-diproses {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-selesai {
            background-color: #d1fae5;
            color: #059669;
        }

        .status-ditolak {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .priority-tinggi {
            color: #dc2626;
        }

        .priority-sedang {
            color: #f59e0b;
        }

        .priority-rendah {
            color: #10b981;
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 1rem 0;
            margin-top: auto;
        }

        .breadcrumb {
            background-color: transparent;
            margin-bottom: 0;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
        }

        .stats-card {
            text-align: center;
            padding: 1.5rem;
        }

        .stats-card .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-card .stats-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0;
        }

        .ticket-card {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .ticket-card:hover {
            background-color: #f8f9fa;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>

    @stack('styles')
</head>

<body class="d-flex flex-column">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="bi bi-ticket-perforated me-2 fs-4 text-primary"></i>
                <div>
                    <div>Sistem Ticketing</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Kominfo Kota Bukittinggi</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</div>
                            <span>{{ auth()->user()->name ?? 'User' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">{{ auth()->user()->skpd ?? 'SKPD' }}</h6>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a>
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#"><i
                                        class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3">
                        <h6 class="text-muted mb-3">MENU UTAMA</h6>
                        <nav class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="/dashboard">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                <small class="text-muted d-block">Beranda dan ringkasan sistem</small>
                            </a>

                            <a class="nav-link {{ request()->routeIs('tiket.pengajuan') ? 'active' : '' }}"
                                href="/tiket/pengajuan">
                                <i class="bi bi-plus-circle me-2"></i>Ajukan Tiket
                                <small class="text-muted d-block">Form pengajuan tiket baru</small>
                            </a>

                            <a class="nav-link {{ request()->routeIs('tiket.saya') ? 'active' : '' }}"
                                href="/tiket/saya">
                                <i class="bi bi-ticket me-2"></i>Tiket Saya
                                <small class="text-muted d-block">Daftar tiket yang Anda ajukan</small>
                            </a>

                            <a class="nav-link {{ request()->routeIs('tiket.daftar') ? 'active' : '' }}"
                                href="/tiket/daftar">
                                <i class="bi bi-list-check me-2"></i>Daftar Tiket
                                <small class="text-muted d-block">Daftar semua tiket</small>
                            </a>

                            <a class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}"
                                href="/laporan">
                                <i class="bi bi-bar-chart me-2"></i>Laporan
                                <small class="text-muted d-block">Grafik dan laporan ringkas</small>
                            </a>

                            <h6 class="text-muted mb-3 mt-4">ADMINISTRASI</h6>
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
                                <small class="text-muted d-block">Overview sistem</small>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.pengguna') ? 'active' : '' }}"
                                href="{{ route('admin.pengguna') }}">
                                <i class="bi bi-people me-2"></i>Pengguna
                                <small class="text-muted d-block">Kelola data pengguna</small>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.skpd') ? 'active' : '' }}"
                                href="{{ route('admin.skpd') }}">
                                <i class="bi bi-building me-2"></i>Data SKPD
                                <small class="text-muted d-block">Kelola data SKPD</small>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.jenis-pekerjaan') ? 'active' : '' }}"
                                href="{{ route('admin.jenis-pekerjaan') }}">
                                <i class="bi bi-tags me-2"></i>Jenis Pekerjaan
                                <small class="text-muted d-block">Kelola kategori pekerjaan</small>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}"
                                href="{{ route('admin.pengaturan') }}">
                                <i class="bi bi-gear me-2"></i>Pengaturan
                                <small class="text-muted d-block">Konfigurasi sistem</small>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.log-aktivitas') ? 'active' : '' }}"
                                href="{{ route('admin.log-aktivitas') }}">
                                <i class="bi bi-clock-history me-2"></i>Log Aktivitas
                                <small class="text-muted d-block">Monitor aktivitas sistem</small>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}"
                                href="{{ route('admin.laporan') }}">
                                <i class="bi bi-bar-chart me-2"></i>Laporan Admin
                                <small class="text-muted d-block">Laporan komprehensif</small>
                            </a>
                            <a class="nav-link {{ request()->routeIs('ticket.management.*') ? 'active' : '' }}"
                                href="{{ route('ticket.management.index') }}">
                                <i class="bi bi-shuffle me-2"></i>Manajemen Tiket
                                <small class="text-muted d-block">Auto & manual assignment</small>
                            </a>

                            <h6 class="text-muted mb-3 mt-4">INFORMASI</h6>
                            <a class="nav-link {{ request()->routeIs('panduan') ? 'active' : '' }}"
                                href="{{ route('panduan') }}">
                                <i class="bi bi-book me-2"></i>Panduan
                                <small class="text-muted d-block">Cara menggunakan sistem</small>
                            </a>
                            <a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}"
                                href="{{ route('tentang') }}">
                                <i class="bi bi-info-circle me-2"></i>Tentang Sistem
                                <small class="text-muted d-block">Informasi lengkap sistem</small>
                            </a>
                            <a class="nav-link {{ request()->routeIs('hubungi') ? 'active' : '' }}"
                                href="{{ route('hubungi') }}">
                                <i class="bi bi-headset me-2"></i>Hubungi Kami
                                <small class="text-muted d-block">Kontak dan dukungan</small>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Breadcrumb -->
                    @if (!request()->routeIs('dashboard'))
                        <nav aria-label="breadcrumb" class="mb-3">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    @endif

                    <!-- Page Header -->
                    @hasSection('page-header')
                        <div class="page-header">
                            <div class="container-fluid">
                                @yield('page-header')
                            </div>
                        </div>
                    @endif

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Main Content Area -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <h6 class="text-white mb-2">Navigasi Cepat</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('dashboard') }}" class="text-muted text-decoration-none"><i
                                    class="bi bi-house me-1"></i>Dashboard</a></li>
                        <li><a href="{{ route('panduan') }}" class="text-muted text-decoration-none"><i
                                    class="bi bi-book me-1"></i>Panduan</a></li>
                        <li><a href="{{ route('tentang') }}" class="text-muted text-decoration-none"><i
                                    class="bi bi-info-circle me-1"></i>Tentang</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-white mb-2">Dukungan</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('hubungi') }}" class="text-muted text-decoration-none"><i
                                    class="bi bi-headset me-1"></i>Hubungi Kami</a></li>
                        <li><a href="{{ route('kebijakan') }}" class="text-muted text-decoration-none"><i
                                    class="bi bi-shield-lock me-1"></i>Kebijakan Privasi</a></li>
                        <li><a href="{{ route('syarat-ketentuan') }}" class="text-muted text-decoration-none"><i
                                    class="bi bi-file-text me-1"></i>Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-white mb-2">Informasi</h6>
                    <p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>(0752) 123-4567</p>
                    <p class="text-muted small mb-1"><i class="bi bi-envelope me-1"></i>kominfo@bukittinggi.go.id</p>
                    <p class="text-muted small"><i class="bi bi-clock me-1"></i>Senin - Jumat, 08:00 - 17:00</p>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">Sistem Ticketing Layanan v1.0</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Common Scripts -->
    <script>
        // Auto dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        // Tooltip initialization
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>

    @stack('scripts')
</body>

</html>
