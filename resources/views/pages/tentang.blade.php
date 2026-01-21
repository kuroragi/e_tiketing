@extends('layouts.e-ticket')

@section('title', 'Tentang Sistem - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tentang Sistem</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-info-circle me-2"></i>
        Tentang Sistem E-Ticket Kominfo
    </h2>
    <p class="mb-0">Memahami lebih lanjut tentang sistem dan visi misi kami</p>
@endsection

@section('content')
    <!-- Introduction Section -->
    <div class="card mb-4 border-0 bg-light">
        <div class="card-body">
            <h5 class="card-title">{{ $aboutData['title'] }}</h5>
            <p class="card-text text-muted">{{ $aboutData['description'] }}</p>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-4">
                <i class="bi bi-star text-warning me-2"></i>
                Fitur Unggulan Sistem
            </h4>
        </div>
        @foreach ($aboutData['features'] as $feature)
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi {{ $feature['icon'] }} text-white display-6"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title">{{ $feature['title'] }}</h5>
                                <p class="card-text text-muted small">{{ $feature['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Visi Misi Section -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-eye me-2"></i>
                        Visi
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ $aboutData['visi'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-target me-2"></i>
                        Misi
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach ($aboutData['misi'] as $item)
                            <li class="d-flex mb-2">
                                <i class="bi bi-check-circle text-success me-2 flex-shrink-0"></i>
                                <span class="text-muted">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Teams Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-4">
                <i class="bi bi-people text-info me-2"></i>
                Pihak-Pihak yang Terlibat
            </h4>
        </div>
        @foreach ($teams as $team)
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-building display-3 text-primary opacity-50"></i>
                        </div>
                        <h5 class="card-title">{{ $team['nama'] }}</h5>
                        <p class="badge bg-primary mb-2">{{ $team['peran'] }}</p>
                        <p class="card-text text-muted small">{{ $team['deskripsi'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Stats Section -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        Statistik Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="display-4 text-primary fw-bold">10+</div>
                            <p class="text-muted mb-0">SKPD Terdaftar</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="display-4 text-success fw-bold">500+</div>
                            <p class="text-muted mb-0">Tiket Terproses</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="display-4 text-info fw-bold">95%</div>
                            <p class="text-muted mb-0">Tingkat Kepuasan</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="display-4 text-warning fw-bold">24/7</div>
                            <p class="text-muted mb-0">Ketersediaan Sistem</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="alert alert-primary border-0" role="alert">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="mb-1">Siap Menggunakan Sistem?</h5>
                <p class="mb-0">Baca panduan penggunaan lengkap atau hubungi tim kami untuk bantuan lebih lanjut.</p>
            </div>
            <div class="flex-shrink-0 ms-3">
                <a href="{{ route('panduan') }}" class="btn btn-primary me-2">
                    <i class="bi bi-book me-1"></i>
                    Panduan Penggunaan
                </a>
                <a href="{{ route('hubungi') }}" class="btn btn-outline-primary">
                    <i class="bi bi-headset me-1"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
@endsection
