@extends('layouts.app')

@section('title', 'Beranda - E-Ticketing System')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Beli Tiket Event Favoritmu dengan Mudah
                    </h1>
                    <p class="lead mb-4">
                        Platform terpercaya untuk membeli tiket berbagai event menarik di Indonesia.
                        Dari konser musik hingga workshop edukasi, semuanya ada di sini!
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('events') }}" class="btn btn-light btn-lg px-4">
                            <i class="bi bi-calendar-event me-2"></i>Jelajahi Event
                        </a>
                        <a href="#featured-events" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-arrow-down me-2"></i>Lihat Event Populer
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://via.placeholder.com/500x400/ffffff/6366f1?text=Event+Ticketing" alt="Event Ticketing"
                        class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-ticket-perforated fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-1">10,000+</h3>
                        <p class="text-muted">Tiket Terjual</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-calendar-check fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-1">500+</h3>
                        <p class="text-muted">Event Selesai</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-people fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-1">5,000+</h3>
                        <p class="text-muted">Pengguna Aktif</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-star-fill fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-1">4.8/5</h3>
                        <p class="text-muted">Rating Pengguna</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events Section -->
    <section id="featured-events" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3">Event Populer</h2>
                    <p class="lead text-muted">Jangan sampai terlewat event-event menarik ini!</p>
                </div>
            </div>

            <div class="row">
                @foreach ($featuredEvents as $event)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card event-card h-100">
                            <img src="{{ $event['image'] }}" class="card-img-top event-image" alt="{{ $event['title'] }}">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge-custom">{{ $event['category'] }}</span>
                                    <small class="text-muted">{{ $event['available_tickets'] }} tiket tersisa</small>
                                </div>

                                <h5 class="card-title fw-bold">{{ $event['title'] }}</h5>

                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar3 text-primary me-2"></i>
                                        <span class="small">{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }} -
                                            {{ $event['time'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt text-primary me-2"></i>
                                        <span class="small">{{ $event['location'] }}</span>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="price-tag">Rp {{ number_format($event['price'], 0, ',', '.') }}</span>
                                        <div>
                                            <a href="{{ route('event.detail', $event['id']) }}"
                                                class="btn btn-outline-primary btn-sm me-1">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('purchase.ticket', $event['id']) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-cart-plus me-1"></i>Beli
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('events') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-arrow-right me-2"></i>Lihat Semua Event
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3">Kategori Event</h2>
                    <p class="lead text-muted">Temukan event sesuai minat Anda</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <a href="{{ route('events', ['category' => 'Musik']) }}" class="text-decoration-none">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-music-note-beamed display-4 text-primary mb-3"></i>
                                <h6 class="card-title">Musik</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <a href="{{ route('events', ['category' => 'Edukasi']) }}" class="text-decoration-none">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-book display-4 text-success mb-3"></i>
                                <h6 class="card-title">Edukasi</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <a href="{{ route('events', ['category' => 'Hiburan']) }}" class="text-decoration-none">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-emoji-laughing display-4 text-warning mb-3"></i>
                                <h6 class="card-title">Hiburan</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <a href="{{ route('events', ['category' => 'Teknologi']) }}" class="text-decoration-none">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-cpu display-4 text-info mb-3"></i>
                                <h6 class="card-title">Teknologi</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <a href="{{ route('events', ['category' => 'Kuliner']) }}" class="text-decoration-none">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-cup-hot display-4 text-danger mb-3"></i>
                                <h6 class="card-title">Kuliner</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <a href="{{ route('events', ['category' => 'Seni']) }}" class="text-decoration-none">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-palette display-4 text-secondary mb-3"></i>
                                <h6 class="card-title">Seni</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3">Mengapa Memilih Kami?</h2>
                    <p class="lead text-muted">Alasan mengapa ribuan orang mempercayai platform kami</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="bi bi-shield-check display-4 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Aman & Terpercaya</h4>
                        <p class="text-muted">Transaksi Anda dilindungi dengan sistem keamanan berlapis dan enkripsi
                            tingkat tinggi.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="bi bi-lightning-charge display-4 text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Proses Cepat</h4>
                        <p class="text-muted">Beli tiket hanya dalam hitungan menit dengan proses pembayaran yang mudah dan
                            cepat.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="bi bi-headset display-4 text-warning"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Layanan 24/7</h4>
                        <p class="text-muted">Tim customer service kami siap membantu Anda kapan saja, 24 jam sehari 7 hari
                            seminggu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endpush
