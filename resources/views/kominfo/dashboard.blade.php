@extends('layouts.e-ticket')

@section('title', 'Dashboard - Sistem Ticketing Kominfo')

@php
    $authUser = auth()->user();
    $isAdmin = $authUser->isAdmin();
    $isPimpinan = $authUser->isPimpinan();
    $isPetugas = $authUser->isPetugas();
    $isSkpd = $authUser->isSkpd();
    $roleBadge = match (true) {
        $isAdmin => ['label' => 'Administrator', 'class' => 'bg-danger'],
        $isPetugas => ['label' => 'Petugas Lapangan', 'class' => 'bg-warning text-dark'],
        $isPimpinan => ['label' => 'Pimpinan', 'class' => 'bg-dark'],
        $isSkpd => ['label' => 'SKPD', 'class' => 'bg-info text-dark'],
        default => ['label' => 'Pengguna', 'class' => 'bg-secondary'],
    };
@endphp

@section('content')
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="mb-0">Selamat Datang, {{ $authUser->name }}</h3>
                                <span class="badge {{ $roleBadge['class'] }}">{{ $roleBadge['label'] }}</span>
                            </div>
                            <p class="text-muted mb-0">Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
                            <small class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</small>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="bi bi-speedometer2 display-4 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Admin/Pimpinan: system-wide stat cards --}}
    @if (($isAdmin || $isPimpinan) && $adminStats)
        <div class="row mb-3">
            @foreach ($adminStats as $s)
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card card-{{ $s['color'] }} h-100">
                        <div class="card-body stats-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stats-number text-{{ $s['color'] }}">{{ $s['nilai'] }}</div>
                                    <div class="stats-label">{{ $s['label'] }}</div>
                                    <small class="text-muted">{{ $s['sub'] }}</small>
                                </div>
                                <i class="bi {{ $s['icon'] }} display-5 text-{{ $s['color'] }} opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Ticket stats (scoped per role) -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-warning">
                <div class="card-body stats-card">
                    <div class="stats-number text-warning">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stats-label">Total Tiket{{ $isSkpd ? ' Saya' : '' }}</div>
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
                    @if (!empty($stats['rata_penyelesaian']))
                        <small class="text-muted">avg. {{ $stats['rata_penyelesaian'] }} hari</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">

        <!-- LEFT COL: audit activities (admin) + recent tickets -->
        <div class="{{ $isAdmin || $isPimpinan || $isPetugas ? 'col-lg-8' : 'col-lg-9' }} mb-4">

            {{-- Admin/Pimpinan: Audit activities --}}
            @if (($isAdmin || $isPimpinan) && $recentActivities->count())
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock-history text-info me-2"></i>Aktivitas Sistem Terbaru</h5>
                        <a href="{{ route('admin.log-aktivitas') }}" class="btn btn-sm btn-outline-secondary">Lihat
                            Semua</a>
                    </div>
                    <div class="card-body p-0">
                        @foreach ($recentActivities as $activity)
                            <div class="d-flex align-items-start gap-3 p-3 border-bottom">
                                <div class="bg-{{ $activity['color'] }} rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px">
                                    <i class="bi {{ $activity['icon'] }} text-white small"></i>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold small">{{ $activity['action'] }}</div>
                                    <div class="text-muted small">
                                        <strong>{{ $activity['user'] }}</strong> &mdash; {{ $activity['target'] }}
                                    </div>
                                </div>
                                <small class="text-muted text-nowrap">{{ $activity['waktu'] }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Tickets -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-ticket-perforated text-primary me-2"></i>
                        @if ($isSkpd)
                            Tiket Terbaru Saya
                        @elseif($isPetugas)
                            Tiket Ditugaskan ke Saya
                        @else
                            Tiket Terbaru
                        @endif
                    </h5>
                    <a href="{{ $isSkpd ? route('tiket.saya') : route('tiket.index') }}"
                        class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($recentTickets as $ticket)
                        <a href="{{ route('tiket.show', $ticket->id) }}"
                            class="d-flex align-items-center gap-3 p-3 border-bottom text-decoration-none text-dark ticket-row-hover">
                            <div class="user-avatar flex-shrink-0">{{ substr($ticket->department->name ?? 'T', 0, 1) }}
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold text-truncate">{{ $ticket->title }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-building me-1"></i>{{ $ticket->department->name ?? '-' }}
                                    &nbsp;&bull;&nbsp;
                                    <i class="bi bi-calendar me-1"></i>{{ $ticket->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                                <span
                                    class="status-badge status-{{ $ticket->status }}">{{ ucfirst($ticket->status) }}</span>
                                @if ($ticket->priority)
                                    <small class="priority-{{ strtolower($ticket->priority->name) }}">
                                        <i class="bi bi-flag-fill"></i> {{ $ticket->priority->name }}
                                    </small>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">Belum ada tiket</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RIGHT COL: Quick actions + role-specific panels -->
        <div class="{{ $isAdmin || $isPimpinan || $isPetugas ? 'col-lg-4' : 'col-lg-3' }}">

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-lightning text-warning me-2"></i>Aksi Cepat</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    @foreach ($quickActions as $action)
                        <a href="{{ $action['url'] }}" class="btn btn-{{ $action['color'] }}">
                            <i class="bi bi-{{ $action['icon'] }} me-2"></i>{{ $action['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Admin/Pimpinan: SKPD breakdown --}}
            @if (($isAdmin || $isPimpinan) && $skpdStats->count())
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-building text-info me-2"></i>Tiket per SKPD</h6>
                        <a href="{{ route('admin.skpd') }}" class="btn btn-sm btn-outline-secondary">Kelola</a>
                    </div>
                    <div class="card-body p-0">
                        @foreach ($skpdStats as $dept)
                            @php $pct = $skpdStats->max('total_tiket') > 0 ? round($dept->total_tiket / $skpdStats->max('total_tiket') * 100) : 0; @endphp
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <div class="min-w-0 flex-grow-1 me-2">
                                    <div class="small fw-semibold text-truncate">{{ $dept->name }}</div>
                                    <div class="progress mt-1" style="height:4px">
                                        <div class="progress-bar bg-info" style="width:{{ $pct }}%"></div>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <span class="badge bg-secondary">{{ $dept->total_tiket }}</span>
                                    @if ($dept->tiket_baru)
                                        <span class="badge bg-primary">{{ $dept->tiket_baru }} baru</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Admin & Petugas: workload panel --}}
            @if (($isAdmin || $isPetugas) && $petugasWorkload->count())
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-people text-success me-2"></i>Beban Kerja Petugas</h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach ($petugasWorkload as $p)
                            @php
                                $cnt = $p->aktif_count;
                                $bc =
                                    $cnt === 0 ? 'success' : ($cnt <= 3 ? 'info' : ($cnt <= 6 ? 'warning' : 'danger'));
                            @endphp
                            <div class="d-flex align-items-center gap-2 p-2 border-bottom">
                                <div class="user-avatar flex-shrink-0" style="width:32px;height:32px;font-size:12px">
                                    {{ substr($p->name, 0, 1) }}</div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="small fw-semibold text-truncate">{{ $p->name }}</div>
                                    <div class="progress" style="height:3px">
                                        <div class="progress-bar bg-{{ $bc }}"
                                            style="width:{{ min(100, $cnt * 10) }}%"></div>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $bc }} flex-shrink-0">{{ $cnt }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Admin only: System status --}}
            @if ($isAdmin)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-heart-pulse text-success me-2"></i>Status Sistem</h6>
                    </div>
                    <div class="card-body">
                        @foreach ([['label' => 'Database'], ['label' => 'Web Server'], ['label' => 'File Storage']] as $svc)
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small>{{ $svc['label'] }}</small>
                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>Online</small>
                            </div>
                            <div class="progress mb-3" style="height:4px">
                                <div class="progress-bar bg-success" style="width:100%"></div>
                            </div>
                        @endforeach
                        <div class="alert alert-success py-2 mb-0 small">
                            <i class="bi bi-check-circle me-1"></i> Semua sistem berjalan normal
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Ticket Detail Modal removed - clicking ticket now links directly to detail page -->
@endsection

@push('scripts')
    <script>
        // Auto refresh dashboard every 5 minutes
        setInterval(() => {
            if (window.location.pathname === '/dashboard') location.reload();
        }, 300000);
    </script>
    <style>
        .ticket-row-hover:hover {
            background-color: var(--bs-light);
        }
    </style>
@endpush
