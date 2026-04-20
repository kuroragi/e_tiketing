@extends('layouts.landing')

@section('title', Setting::get('app_name', 'Sistem Tiket Internal — Kominfo Bukittinggi'))

@section('content')
    <!-- ── Hero Section ────────────────────────────────────────── -->
    <section class="hero-section" style="padding-top: 100px;">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 hero-content">
                    <div class="hero-badge">
                        <i class="bi bi-shield-check"></i>
                        {{ Setting::get('app_institution', 'Dinas Komunikasi dan Informatika Kota Bukittinggi') }}
                    </div>
                    <h1 class="hero-title">
                        Layanan Publik &amp; Tiket<br>Kominfo Bukittinggi
                    </h1>
                    <p class="hero-subtitle">
                        Ajukan permintaan data CCTV, laporkan gangguan Wi-Fi publik, kerusakan
                        infrastruktur jaringan, atau layanan IT Kominfo lainnya secara online.
                    </p>
                    <div class="hero-actions d-flex flex-wrap gap-3 mb-4">
                        <a href="#layanan-publik" class="btn btn-hero-primary btn-lg">
                            <i class="bi bi-arrow-down-circle me-2"></i>Layanan untuk Publik
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-hero-secondary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sistem
                        </a>
                    </div>
                </div>

                <div class="col-lg-5 hero-stats">
                    @if ($showStats)
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number">{{ number_format($stats['total']) }}</div>
                                    <div class="stat-label">Total Tiket</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number">{{ number_format($stats['selesai']) }}</div>
                                    <div class="stat-label">Selesai Ditangani</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number">{{ number_format($stats['diproses']) }}</div>
                                    <div class="stat-label">Sedang Diproses</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number">{{ $stats['rata_hari'] }} <small
                                            style="font-size:0.5em">hari</small></div>
                                    <div class="stat-label">Rata-rata Selesai</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- ── Layanan Publik Kominfo ──────────────────────────────────── -->
    <section class="py-5" id="layanan-publik" style="background: var(--landing-light);">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3" style="font-size:0.85rem;">
                    <i class="bi bi-people me-1"></i>Untuk Masyarakat Umum
                </span>
                <h2 class="section-title">Layanan Publik Kominfo Bukittinggi</h2>
                <p class="section-subtitle">
                    Sampaikan permintaan atau pengaduan terkait layanan Dinas Komunikasi dan Informatika
                    Kota Bukittinggi secara online. Cepat, mudah, dan dapat dilacak.
                </p>
            </div>

            <div class="row g-4 justify-content-center">

                <!-- Permintaan Data CCTV -->
                <div class="col-lg-5 col-md-10">
                    <div class="service-card h-100 d-flex flex-column" style="border-top: 4px solid #6366f1;">
                        <div class="service-icon" style="background: #6366f115; color: #6366f1;">
                            <i class="bi bi-camera-video"></i>
                        </div>
                        <div class="mb-2">
                            <span class="badge" style="background:#6366f120; color:#6366f1; font-size:0.75rem;">
                                Permintaan Data
                            </span>
                        </div>
                        <h4 class="fw-bold mb-2">Permintaan Data CCTV</h4>
                        <p class="text-muted mb-1" style="font-size:0.9rem;">
                            Ajukan permintaan rekaman CCTV milik Pemkot Bukittinggi untuk keperluan
                            pelaporan, penyelidikan, atau kebutuhan lainnya yang sah.
                        </p>
                        <ul class="list-unstyled small text-muted mb-4 mt-2">
                            <li class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Rekaman CCTV untuk laporan kepolisian</li>
                            <li class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Permintaan data keamanan kawasan publik</li>
                            <li class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Keperluan investigasi resmi</li>
                        </ul>
                        <div class="mt-auto">
                            <a href="{{ route('public.ticket.create') }}?layanan=cctv"
                                class="btn btn-lg w-100 fw-semibold"
                                style="background:#6366f1; color:#fff; border:none;">
                                <i class="bi bi-send me-2"></i>Ajukan Permintaan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pengaduan Layanan Kominfo -->
                <div class="col-lg-5 col-md-10">
                    <div class="service-card h-100 d-flex flex-column" style="border-top: 4px solid #10b981;">
                        <div class="service-icon" style="background: #10b98115; color: #10b981;">
                            <i class="bi bi-wifi"></i>
                        </div>
                        <div class="mb-2">
                            <span class="badge" style="background:#10b98120; color:#10b981; font-size:0.75rem;">
                                Pengaduan Layanan
                            </span>
                        </div>
                        <h4 class="fw-bold mb-2">Pengaduan Layanan Kominfo</h4>
                        <p class="text-muted mb-1" style="font-size:0.9rem;">
                            Laporkan gangguan atau kerusakan pada layanan dan infrastruktur yang dikelola
                            Dinas Komunikasi dan Informatika Kota Bukittinggi.
                        </p>
                        <ul class="list-unstyled small text-muted mb-4 mt-2">
                            <li class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Wi-Fi publik lemot atau tidak dapat terhubung</li>
                            <li class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Kabel jaringan/infrastruktur yang rusak</li>
                            <li class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Gangguan CCTV, videotron, atau perangkat publik lainnya</li>
                        </ul>
                        <div class="mt-auto">
                            <a href="{{ route('public.ticket.create') }}?layanan=pengaduan"
                                class="btn btn-lg w-100 fw-semibold"
                                style="background:#10b981; color:#fff; border:none;">
                                <i class="bi bi-megaphone me-2"></i>Buat Pengaduan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lacak Pengaduan -->
            <div class="row justify-content-center mt-4">
                <div class="col-lg-10">
                    <div class="alert alert-light border d-flex gap-3 align-items-center" style="border-radius:0.75rem;">
                        <i class="bi bi-search text-primary flex-shrink-0" style="font-size:1.3rem;"></i>
                        <div class="flex-grow-1 small">
                            <strong class="text-dark">Sudah punya kode pengaduan?</strong>
                            <span class="text-muted ms-1">Lacak status pengaduan Anda secara real-time.</span>
                        </div>
                        <a href="{{ route('public.ticket.track') }}" class="btn btn-outline-primary btn-sm text-nowrap">
                            <i class="bi bi-search me-1"></i>Lacak Pengaduan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Tentang Sistem Internal ──────────────────────────────── -->
    <section class="py-5">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 mb-3" style="font-size:0.85rem;">
                        <i class="bi bi-building me-1"></i>Untuk Staf Internal
                    </span>
                    <h2 class="section-title mb-3">Sistem Tiket Internal Kominfo</h2>
                    <p class="text-muted mb-4">
                        Aplikasi ini merupakan platform manajemen tiket <strong>khusus untuk staf dan perangkat
                            daerah</strong> di lingkungan Pemerintah Kota Bukittinggi. Digunakan untuk
                        pengelolaan, pelacakan, dan pelaporan permintaan layanan TI secara internal.
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0 rounded-2 d-flex align-items-center justify-content-center"
                                    style="width:40px;height:40px;background:#4f46e515;">
                                    <i class="bi bi-ticket-perforated text-primary"></i>
                                </div>
                                <div>
                                    <strong class="d-block small">Manajemen Tiket</strong>
                                    <span class="text-muted" style="font-size:0.82rem;">Buat, kelola, dan pantau tiket
                                        layanan secara terpusat</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0 rounded-2 d-flex align-items-center justify-content-center"
                                    style="width:40px;height:40px;background:#0ea5e915;">
                                    <i class="bi bi-diagram-3" style="color:#0ea5e9;"></i>
                                </div>
                                <div>
                                    <strong class="d-block small">Multi Departemen</strong>
                                    <span class="text-muted" style="font-size:0.82rem;">Tiket dapat diarahkan ke bidang
                                        yang tepat secara otomatis</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0 rounded-2 d-flex align-items-center justify-content-center"
                                    style="width:40px;height:40px;background:#10b98115;">
                                    <i class="bi bi-bar-chart-line" style="color:#10b981;"></i>
                                </div>
                                <div>
                                    <strong class="d-block small">Laporan & Statistik</strong>
                                    <span class="text-muted" style="font-size:0.82rem;">Dashboard analitik untuk memantau
                                        kinerja penanganan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0 rounded-2 d-flex align-items-center justify-content-center"
                                    style="width:40px;height:40px;background:#f59e0b15;">
                                    <i class="bi bi-bell" style="color:#f59e0b;"></i>
                                </div>
                                <div>
                                    <strong class="d-block small">Notifikasi Otomatis</strong>
                                    <span class="text-muted" style="font-size:0.82rem;">Update status tiket dikirim
                                        otomatis melalui Telegram</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk sebagai Petugas
                    </a>
                </div>
                <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
                    <div class="text-center p-5"
                        style="background:linear-gradient(135deg,#4f46e508,#0ea5e908);border-radius:1.5rem;border:1px solid #e2e8f0;">
                        <i class="bi bi-headset" style="font-size:6rem;color:var(--landing-primary);opacity:0.7;"></i>
                        <p class="text-muted mt-3 mb-0 small">Sistem Tiket Internal<br>Kominfo Bukittinggi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── CTA Staff Login ───────────────────────────────────────── -->
    <section class="py-5" style="background: var(--landing-light);">
        <div class="container py-4">
            <div class="cta-section text-center">
                <h2 class="mb-3">Staf Pemerintahan Kota Bukittinggi?</h2>
                <p class="mb-4 mx-auto" style="max-width:520px;">
                    Gunakan akun yang telah diberikan oleh administrator untuk mengakses
                    sistem tiket internal Kominfo.
                </p>
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
                </a>
            </div>
        </div>
    </section>
@endsection
