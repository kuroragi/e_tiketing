@extends('layouts.landing')

@section('title', Setting::get('app_name', 'Layanan Publik Kominfo Bukittinggi'))

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
                        {!! nl2br(e(Setting::get('landing_hero_title', "Layanan Pengaduan &\nPermintaan Data Publik"))) !!}
                    </h1>
                    <p class="hero-subtitle">
                        {{ Setting::get('landing_hero_subtitle', 'Sampaikan pengaduan, permintaan data CCTV, atau layanan lainnya secara online. Cepat, transparan, dan dapat dilacak.') }}
                    </p>
                    <div class="hero-actions d-flex flex-wrap gap-3 mb-4">
                        <a href="{{ route('public.ticket.create') }}" class="btn btn-hero-primary btn-lg">
                            <i class="bi bi-pencil-square me-2"></i>Buat Pengaduan
                        </a>
                        <a href="#layanan" class="btn btn-hero-secondary btn-lg">
                            <i class="bi bi-arrow-down-circle me-2"></i>Lihat Layanan
                        </a>
                    </div>

                    <!-- Track Ticket Mini Form -->
                    <div class="hero-track-form">
                        <form action="{{ route('public.ticket.track') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="code" class="form-control"
                                placeholder="Masukkan kode tracking tiket..." required>
                            <button type="submit" class="btn btn-hero-primary px-3 flex-shrink-0">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                        <small class="text-white-50 d-block mt-2">
                            <i class="bi bi-info-circle me-1"></i>Lacak status pengaduan Anda kapan saja
                        </small>
                    </div>
                </div>

                <div class="col-lg-5 hero-stats">
                    @if ($showStats)
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number">{{ number_format($stats['total']) }}</div>
                                    <div class="stat-label">Total Pengaduan</div>
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

    <!-- ── Layanan Section ──────────────────────────────────────── -->
    <section class="py-5" id="layanan" style="background: var(--landing-light);">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3" style="font-size:0.85rem;">
                    <i class="bi bi-grid me-1"></i>Layanan Kami
                </span>
                <h2 class="section-title">
                    {{ Setting::get('landing_services_title', 'Jenis Layanan yang Tersedia') }}
                </h2>
                <p class="section-subtitle">
                    {{ Setting::get('landing_services_subtitle', 'Pilih jenis layanan sesuai kebutuhan Anda. Semua layanan dapat diakses tanpa perlu datang ke kantor.') }}
                </p>
            </div>

            <div class="row g-4">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon"
                                style="background: {{ $service['color'] }}15; color: {{ $service['color'] }};">
                                <i class="bi {{ $service['icon'] }}"></i>
                            </div>
                            <h5>{{ $service['title'] }}</h5>
                            <p>{{ $service['description'] }}</p>
                            <a href="{{ route('public.ticket.create', ['kategori' => $service['category_id'] ?? '']) }}"
                                class="btn btn-sm btn-outline-primary mt-2">
                                Ajukan Permintaan <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── How It Works ─────────────────────────────────────────── -->
    <section class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 mb-3" style="font-size:0.85rem;">
                    <i class="bi bi-signpost-2 me-1"></i>Cara Kerja
                </span>
                <h2 class="section-title">Bagaimana Proses Pengaduan?</h2>
                <p class="section-subtitle">Proses yang mudah dan transparan dalam 4 langkah sederhana</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h5>Isi Formulir</h5>
                        <p>Lengkapi data diri dan deskripsi pengaduan atau permintaan Anda</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h5>Dapatkan Kode</h5>
                        <p>Anda akan mendapatkan kode tracking untuk memantau status</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h5>Tim Memproses</h5>
                        <p>Tim Kominfo akan segera menindaklanjuti pengaduan Anda</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h5>Selesai</h5>
                        <p>Anda akan mendapatkan notifikasi saat pengaduan telah ditangani</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Recent Public Tickets ─────────────────────────────────── -->
    @if ($showRecent && $recentTickets->count())
        <section class="py-5" style="background: var(--landing-light);">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 mb-3" style="font-size:0.85rem;">
                        <i class="bi bi-clock-history me-1"></i>Terbaru
                    </span>
                    <h2 class="section-title">Pengaduan Terakhir</h2>
                    <p class="section-subtitle">Transparansi penanganan pengaduan masyarakat</p>
                </div>

                <div class="row g-3">
                    @foreach ($recentTickets as $ticket)
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="badge bg-{{ $ticket->statusBadgeClass() }} bg-opacity-10 text-{{ $ticket->statusBadgeClass() }} p-2"
                                            style="font-size:1rem;">
                                            <i class="bi bi-ticket-perforated"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0 fw-bold">{{ Str::limit($ticket->title, 50) }}</h6>
                                            <span
                                                class="badge bg-{{ $ticket->statusBadgeClass() }} ms-2">{{ $ticket->statusLabel() }}</span>
                                        </div>
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-tag me-1"></i>{{ $ticket->category->name ?? '-' }}
                                            &bull;
                                            <i class="bi bi-calendar me-1"></i>{{ $ticket->created_at->diffForHumans() }}
                                        </small>
                                        <small class="text-muted">{{ Str::limit($ticket->description, 80) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ── CTA Section ───────────────────────────────────────────── -->
    <section class="py-5">
        <div class="container py-4">
            <div class="cta-section text-center">
                <h2 class="mb-3">Punya Pengaduan atau Permintaan?</h2>
                <p class="mb-4 mx-auto" style="max-width:500px;">
                    Jangan ragu untuk menyampaikan pengaduan atau permintaan data. Tim kami siap membantu Anda.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('public.ticket.create') }}" class="btn btn-light btn-lg px-4 fw-semibold">
                        <i class="bi bi-pencil-square me-2"></i>Buat Pengaduan Sekarang
                    </a>
                    <a href="{{ route('public.ticket.track') }}" class="btn btn-outline-light btn-lg px-4 fw-semibold">
                        <i class="bi bi-search me-2"></i>Lacak Tiket
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
