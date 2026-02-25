@extends('layouts.e-ticket')

@section('title', 'Dashboard - Sistem Ticketing Kominfo')

@section('content')
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-2">Selamat Datang di Sistem Ticketing Layanan</h3>
                            <p class="text-muted mb-0">Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
                            <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="bi bi-speedometer2 display-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-warning">
                <div class="card-body stats-card">
                    <div class="stats-number text-warning">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stats-label">Total Tiket</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-primary">
                <div class="card-body stats-card">
                    <div class="stats-number text-primary">{{ $stats['baru'] ?? 0 }}</div>
                    <div class="stats-label">Tiket Baru</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-info">
                <div class="card-body stats-card">
                    <div class="stats-number text-info">{{ $stats['diproses'] ?? 0 }}</div>
                    <div class="stats-label">Sedang Diproses</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-success">
                <div class="card-body stats-card">
                    <div class="stats-number text-success">{{ $stats['selesai'] ?? 0 }}</div>
                    <div class="stats-label">Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Tickets -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        Tiket Terbaru
                    </h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if (isset($recentTickets) && count($recentTickets) > 0)
                        @foreach ($recentTickets as $ticket)
                            <a href="{{ route('tiket.show', $ticket->id) }}" class="text-decoration-none text-dark">
                            <div class="ticket-card p-3 border-bottom">
                                <div class="row align-items-center">
                                    <div class="col-md-1">
                                        <div class="user-avatar">{{ substr($ticket->department->name ?? 'T', 0, 1) }}</div>
                                    </div>
                                    <div class="col-md-7">
                                        <h6 class="mb-1">{{ $ticket->title }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-building me-1"></i>{{ $ticket->department->name ?? '-' }}
                                            <span class="ms-3"><i
                                                    class="bi bi-calendar me-1"></i>{{ $ticket->created_at->format('d/m/Y') }}</span>
                                        </small>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="status-badge status-{{ strtolower($ticket->status) }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <span class="priority-{{ strtolower($ticket->priority->name ?? 'rendah') }}">
                                            <i class="bi bi-flag-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                            <p class="text-muted">Belum ada tiket terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning text-warning me-2"></i>
                        Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    @foreach ($quickActions as $action)
                        <a href="{{ $action['url'] }}" class="btn btn-{{ $action['color'] }} w-100 mb-2">
                            <i class="bi bi-{{ $action['icon'] }} me-2"></i>{{ $action['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- SKPD Performance -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-building text-info me-2"></i>
                        Statistik SKPD
                    </h6>
                </div>
                <div class="card-body">
                    @if (isset($skpdStats) && count($skpdStats) > 0)
                        @foreach ($skpdStats as $skpd)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">{{ $skpd['nama'] }}</h6>
                                    <small class="text-muted">{{ $skpd['total'] }} tiket</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-light text-dark">{{ $skpd['bulan_ini'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-graph-up text-muted mb-2 d-block fs-3"></i>
                            <small class="text-muted">Belum ada data statistik</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section (if needed) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up text-success me-2"></i>
                        Grafik Perkembangan Tiket (7 Hari Terakhir)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $index => $day)
                            <div class="col">
                                <div class="mb-2">
                                    <div class="bg-primary"
                                        style="height: {{ rand(20, 80) }}px; width: 30px; margin: 0 auto; border-radius: 4px;">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $day }}</small>
                                <div><strong>{{ rand(1, 15) }}</strong></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Detail Modal removed - clicking ticket now links directly to detail page -->
@endsection

@push('scripts')
    <script>
        // Auto refresh dashboard every 5 minutes
        setInterval(() => {
            if (window.location.pathname === '/dashboard') {
                location.reload();
            }
        }, 300000);
    </script>
@endpush
