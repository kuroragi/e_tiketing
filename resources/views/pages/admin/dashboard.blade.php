@extends('layouts.e-ticket')

@section('title', 'Dashboard Admin - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard Admin</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-speedometer2 me-2"></i>
        Dashboard Administrasi
    </h2>
    <p class="mb-0">Overview dan monitoring sistem E-Ticket Kominfo</p>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        @foreach ($stats as $stat)
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card card-{{ $stat['color'] }}">
                    <div class="card-body stats-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stats-number text-{{ $stat['color'] }}">{{ $stat['nilai'] }}</div>
                                <div class="stats-label">{{ $stat['label'] }}</div>
                                <small class="text-muted">{{ $stat['change'] }}</small>
                            </div>
                            <div>
                                <i class="bi {{ $stat['icon'] }} display-5 text-{{ $stat['color'] }} opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2 text-info"></i>
                        Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach ($recentActivities as $activity)
                            <div class="timeline-item mb-3 pb-3 border-bottom">
                                <div class="d-flex">
                                    <div class="timeline-marker me-3">
                                        <div class="bg-{{ $activity['color'] }} rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi {{ $activity['icon'] }} text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $activity['action'] }}</h6>
                                        <p class="text-muted small mb-1">
                                            <strong>{{ $activity['user'] }}</strong>
                                            {{ str_replace('_', ' ', strtolower($activity['action'])) }}
                                        </p>
                                        <small class="text-muted">
                                            <i class="bi bi-ticket me-1"></i>{{ $activity['target'] }} -
                                            {{ $activity['waktu'] }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2 text-warning"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pengguna') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah Pengguna
                        </a>
                        <a href="{{ route('admin.skpd') }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-building me-2"></i>
                            Kelola SKPD
                        </a>
                        <a href="{{ route('admin.jenis-pekerjaan') }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-tags me-2"></i>
                            Kelola Jenis Pekerjaan
                        </a>
                        <a href="{{ route('admin.pengaturan') }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-gear me-2"></i>
                            Pengaturan Sistem
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-pulse me-2 text-success"></i>
                        Status Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Database</small>
                            <small class="text-success"><i class="bi bi-check-circle me-1"></i>Online</small>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Web Server</small>
                            <small class="text-success"><i class="bi bi-check-circle me-1"></i>Online</small>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Email Service</small>
                            <small class="text-success"><i class="bi bi-check-circle me-1"></i>Online</small>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="alert alert-success py-2" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        <small>Semua sistem berjalan normal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
