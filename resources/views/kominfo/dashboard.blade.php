@extends('layouts.e-ticket')

@section('title', 'Dashboard - Sistem Ticketing Kominfo')

@php
    $authUser   = auth()->user();
    $isAdmin    = $authUser->isAdmin();
    $isPimpinan = $authUser->isPimpinan();
    $isPetugas  = $authUser->isPetugas();
    $isSkpd     = $authUser->isSkpd();
    $roleBadge  = match(true) {
        $isAdmin    => ['label' => 'Administrator',     'class' => 'bg-danger'],
        $isPetugas  => ['label' => 'Petugas Lapangan',  'class' => 'bg-warning text-dark'],
        $isPimpinan => ['label' => 'Pimpinan',          'class' => 'bg-dark'],
        $isSkpd     => ['label' => 'SKPD',              'class' => 'bg-info text-dark'],
        default     => ['label' => 'Pengguna',          'class' => 'bg-secondary'],
    };
@endphp

@push('styles')
<style>
    .stat-card-modern {
        border-radius: 1rem; padding: 1.4rem 1.25rem;
        border: 1px solid var(--border);
        background: var(--bg-card);
        box-shadow: var(--card-shadow);
        transition: transform .2s, box-shadow .2s;
        position: relative; overflow: hidden;
    }
    .stat-card-modern:hover { transform: translateY(-3px); box-shadow: var(--card-shadow-lg); }
    .stat-card-modern .stat-icon {
        width: 52px; height: 52px; border-radius: .85rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; flex-shrink: 0;
    }
    .stat-card-modern .stat-value {
        font-size: 2.1rem; font-weight: 800; line-height: 1; color: var(--text-primary);
    }
    .stat-card-modern .stat-label {
        font-size: .78rem; color: var(--text-secondary); font-weight: 500; margin-top: .25rem;
    }
    .stat-card-modern .stat-sub {
        font-size: .72rem; color: var(--text-muted); margin-top: .3rem;
    }
    .stat-card-modern .glow {
        position: absolute; right: -10px; top: -10px;
        width: 80px; height: 80px; border-radius: 50%; opacity: .07;
    }

    /* Welcome banner gradient per role */
    .welcome-banner {
        border-radius: 1rem; padding: 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
        color: #fff; position: relative; overflow: hidden;
        box-shadow: 0 4px 20px rgba(37,99,235,.3);
    }
    .welcome-banner::after {
        content: ''; position: absolute; right: -30px; top: -30px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,.06);
    }
    .welcome-banner::before {
        content: ''; position: absolute; right: 60px; bottom: -50px;
        width: 120px; height: 120px; border-radius: 50%;
        background: rgba(255,255,255,.04);
    }

    /* Chart card */
    .chart-card {
        border: 1px solid var(--border); border-radius: 1rem;
        background: var(--bg-card); box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    .chart-card .chart-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .chart-card .chart-body { padding: 1.25rem; }

    /* Petugas workload bar */
    .workload-bar { height: 5px; border-radius: 50px; background: var(--border); overflow: hidden; margin-top: 4px; }
    .workload-bar .fill { height: 100%; border-radius: 50px; transition: width .6s ease; }

    /* Activity item */
    .activity-item {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: .75rem 1rem; border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .activity-item:hover { background: var(--bg-card-hover); }
    .activity-item:last-child { border-bottom: none; }

    /* SKPD mini table */
    .skpd-row {
        display: flex; align-items: center; gap: .75rem;
        padding: .65rem 1rem; border-bottom: 1px solid var(--border);
    }
    .skpd-row:last-child { border-bottom: none; }
    .skpd-rank {
        width: 24px; height: 24px; border-radius: 6px;
        font-size: .7rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        background: var(--primary-light); color: var(--primary); flex-shrink: 0;
    }
</style>
@endpush

@section('content')

    {{-- ═══ WELCOME BANNER ═══ --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-banner">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h3 class="mb-0 fw-bold">Selamat Datang, {{ $authUser->name }}</h3>
                            <span class="badge {{ $roleBadge['class'] }} ms-1">{{ $roleBadge['label'] }}</span>
                        </div>
                        <p class="mb-0 opacity-75">Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
                        <small class="opacity-50">{{ now()->translatedFormat('l, d F Y') }}</small>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <i class="bi bi-speedometer2 opacity-20" style="font-size: 5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ STAT CARDS ═══ --}}
    @if($isAdmin && $adminStats)
        <div class="row g-3 mb-4">
            @foreach($adminStats as $s)
            <div class="col-6 col-lg-3">
                <div class="stat-card-modern">
                    <div class="glow" style="background:var(--bs-{{ $s['color'] }});"></div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="stat-icon" style="background:rgba(var(--bs-{{ $s['color'] }}-rgb),.12); color:var(--bs-{{ $s['color'] }});">
                            <i class="bi {{ $s['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="stat-value" data-count="{{ $s['nilai'] }}">{{ $s['nilai'] }}</div>
                    <div class="stat-label">{{ $s['label'] }}</div>
                    <div class="stat-sub">{{ $s['sub'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @if($isPimpinan && $pimpinanStats)
        <div class="row g-3 mb-4">
            @foreach($pimpinanStats as $s)
            <div class="col-6 col-lg-3">
                <div class="stat-card-modern">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="stat-icon" style="background:rgba(var(--bs-{{ $s['color'] }}-rgb),.12); color:var(--bs-{{ $s['color'] }});">
                            <i class="bi {{ $s['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="stat-value" data-count="{{ is_numeric($s['nilai']) ? $s['nilai'] : '' }}">{{ $s['nilai'] }}</div>
                    <div class="stat-label">{{ $s['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @if(!$isPimpinan)
        <div class="row g-3 mb-4">
            @php
                $statItems = [
                    ['value' => $stats['total']    ?? 0, 'label' => 'Total Tiket'.($isSkpd ? ' Saya':''), 'color' => '#6366f1', 'icon' => 'bi-ticket-perforated'],
                    ['value' => $stats['baru']     ?? 0, 'label' => 'Tiket Baru',     'color' => '#f59e0b', 'icon' => 'bi-plus-circle'],
                    ['value' => $stats['diproses'] ?? 0, 'label' => 'Sedang Diproses','color' => '#3b82f6', 'icon' => 'bi-arrow-repeat'],
                    ['value' => $stats['selesai']  ?? 0, 'label' => 'Selesai',         'color' => '#22c55e', 'icon' => 'bi-check-circle'],
                ];
            @endphp
            @foreach($statItems as $st)
            <div class="col-6 col-lg-3">
                <div class="stat-card-modern">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="stat-icon" style="background:{{ $st['color'] }}18; color:{{ $st['color'] }};">
                            <i class="bi {{ $st['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="stat-value" data-count="{{ $st['value'] }}">{{ $st['value'] }}</div>
                    <div class="stat-label">{{ $st['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- ═══ MAIN ROW ═══ --}}
    <div class="row g-4">

        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">

            {{-- Pimpinan: Charts --}}
            @if($isPimpinan && $chartData)
                <div class="row g-3 mb-4">
                    <div class="col-lg-7">
                        <div class="chart-card h-100">
                            <div class="chart-header">
                                <h6 class="mb-0 fw-semibold"><i class="bi bi-bar-chart-line text-primary me-2"></i>Tren Pekerjaan 6 Bulan</h6>
                            </div>
                            <div class="chart-body">
                                <canvas id="chartTren" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="chart-card h-100">
                            <div class="chart-header">
                                <h6 class="mb-0 fw-semibold"><i class="bi bi-pie-chart text-warning me-2"></i>Distribusi Status</h6>
                            </div>
                            <div class="chart-body d-flex flex-column align-items-center">
                                <canvas id="chartStatus" style="max-width:180px;max-height:180px;"></canvas>
                                <div class="mt-3 w-100" id="chartStatusLegend"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Admin: Bar chart per SKPD --}}
            @if($isAdmin && $skpdStats->count())
                <div class="chart-card mb-4">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-building text-info me-2"></i>Tiket per SKPD</h6>
                        <a href="{{ route('admin.skpd') }}" class="btn btn-sm btn-outline-secondary">Kelola</a>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartSkpd" height="160"></canvas>
                    </div>
                </div>
            @endif

            {{-- Admin: Audit activities --}}
            @if($isAdmin && $recentActivities->count())
                <div class="chart-card mb-4">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history text-info me-2"></i>Aktivitas Sistem Terbaru</h6>
                        <a href="{{ route('admin.log-aktivitas') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </div>
                    @foreach($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="bg-{{ $activity['color'] }} rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:36px;height:36px;">
                                <i class="bi {{ $activity['icon'] }} text-white small"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold small" style="color:var(--text-primary)">{{ $activity['action'] }}</div>
                                <div class="small" style="color:var(--text-secondary)">
                                    <strong>{{ $activity['user'] }}</strong> &mdash; {{ $activity['target'] }}
                                </div>
                            </div>
                            <small style="color:var(--text-muted);" class="text-nowrap">{{ $activity['waktu'] }}</small>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Recent Tickets --}}
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-ticket-perforated text-primary me-2"></i>
                        @if($isSkpd) Tiket Terbaru Saya
                        @elseif($isPetugas) Tiket Ditugaskan ke Saya
                        @else Tiket Terbaru
                        @endif
                    </h6>
                    <a href="{{ $isSkpd ? route('tiket.saya') : route('tiket.index') }}"
                        class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
                @forelse($recentTickets as $ticket)
                    @php
                        $isOverdue = $ticket->target_date
                            && $ticket->target_date->isPast()
                            && $ticket->isOpen();
                        $overdueDays = $isOverdue ? now()->diffInDays($ticket->target_date) : 0;
                    @endphp
                    <a href="{{ route('tiket.show', $ticket->id) }}"
                        class="activity-item text-decoration-none">
                        <div class="user-avatar flex-shrink-0">{{ substr($ticket->department->name ?? 'T', 0, 1) }}</div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-truncate" style="color:var(--text-primary);">{{ $ticket->title }}</div>
                            <div class="small" style="color:var(--text-secondary);">
                                <i class="bi bi-building me-1"></i>{{ $ticket->department->name ?? '-' }}
                                &nbsp;&bull;&nbsp;
                                <i class="bi bi-calendar me-1"></i>{{ $ticket->created_at->format('d/m/Y') }}
                                @if($isOverdue)
                                    &nbsp;<span class="badge-overdue">⚠ {{ $overdueDays }} hari terlambat</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                            <span class="status-badge status-{{ $ticket->status }}">{{ $ticket->statusLabel() }}</span>
                            @if($ticket->priority)
                                <small class="priority-{{ strtolower($ticket->priority->name) }}">
                                    <i class="bi bi-flag-fill"></i> {{ $ticket->priority->name }}
                                </small>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 mb-3 d-block" style="color:var(--text-muted)"></i>
                        <p style="color:var(--text-muted)" class="mb-0">Belum ada tiket</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-4">

            {{-- Quick Actions --}}
            <div class="chart-card mb-4">
                <div class="chart-header">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-lightning-charge text-warning me-2"></i>Aksi Cepat</h6>
                </div>
                <div class="chart-body d-grid gap-2">
                    @foreach($quickActions as $action)
                        <a href="{{ $action['url'] }}" class="btn btn-{{ $action['color'] }} d-flex align-items-center gap-2">
                            <i class="bi bi-{{ $action['icon'] }}"></i>{{ $action['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Admin/Petugas: Petugas workload chart --}}
            @if(($isAdmin || $isPetugas) && $petugasWorkload->count())
                <div class="chart-card mb-4">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-people text-success me-2"></i>Beban Kerja Petugas</h6>
                    </div>
                    <div class="chart-body p-0">
                        @foreach($petugasWorkload as $p)
                            @php
                                $cnt = $p->aktif_count;
                                $pct = min(100, $cnt * 10);
                                $color = $cnt === 0 ? '#22c55e' : ($cnt <= 3 ? '#3b82f6' : ($cnt <= 6 ? '#f59e0b' : '#ef4444'));
                                $label = $cnt === 0 ? 'Tersedia' : ($cnt <= 3 ? 'Ringan' : ($cnt <= 6 ? 'Sedang' : 'Tinggi'));
                            @endphp
                            <div class="skpd-row">
                                <div class="user-avatar flex-shrink-0" style="width:32px;height:32px;font-size:.7rem;">{{ substr($p->name, 0, 1) }}</div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="small fw-semibold text-truncate" style="color:var(--text-primary)">{{ $p->name }}</div>
                                    <div class="workload-bar">
                                        <div class="fill" style="width:{{ $pct }}%;background:{{ $color }};"></div>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0 ms-2">
                                    <span class="badge rounded-pill" style="background:{{ $color }}20;color:{{ $color }};font-size:.7rem;">{{ $cnt }} &bull; {{ $label }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Pimpinan: SKPD per-dept stat --}}
            @if($isPimpinan && $skpdStats->count())
                <div class="chart-card mb-4">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-building text-info me-2"></i>Tiket per SKPD</h6>
                    </div>
                    <div class="p-0">
                        @foreach($skpdStats as $i => $dept)
                            @php $pct = $skpdStats->max('total_tiket') > 0 ? round($dept->total_tiket / $skpdStats->max('total_tiket') * 100) : 0; @endphp
                            <div class="skpd-row">
                                <div class="skpd-rank">{{ $i+1 }}</div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="small fw-semibold text-truncate" style="color:var(--text-primary)">{{ $dept->name }}</div>
                                    <div class="workload-bar">
                                        <div class="fill" style="width:{{ $pct }}%;background:#3b82f6;"></div>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0 ms-2">
                                    <span class="badge bg-secondary rounded-pill" style="font-size:.7rem;">{{ $dept->total_tiket }}</span>
                                    @if($dept->tiket_baru)
                                        <span class="badge bg-warning text-dark rounded-pill" style="font-size:.7rem;">{{ $dept->tiket_baru }} baru</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Admin: System Status --}}
            @if($isAdmin)
                <div class="chart-card">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-heart-pulse text-success me-2"></i>Status Sistem</h6>
                    </div>
                    <div class="chart-body">
                        @foreach([['label' => 'Database', 'status' => 'Online'], ['label' => 'Web Server', 'status' => 'Online'], ['label' => 'File Storage', 'status' => 'Online']] as $svc)
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small style="color:var(--text-secondary)">{{ $svc['label'] }}</small>
                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>{{ $svc['status'] }}</small>
                            </div>
                            <div class="workload-bar mb-3">
                                <div class="fill" style="width:100%;background:#22c55e;"></div>
                            </div>
                        @endforeach
                        <div class="alert alert-success py-2 mb-0 small">
                            <i class="bi bi-check-circle me-1"></i>Semua sistem berjalan normal
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = () => document.documentElement.getAttribute('data-theme') === 'dark';
    const gridColor  = () => isDark() ? 'rgba(255,255,255,.08)' : 'rgba(0,0,0,.06)';
    const labelColor = () => isDark() ? '#94a3b8' : '#64748b';

    Chart.defaults.font.family = "'Inter', sans-serif";

    /* ─── Pimpinan: Tren Bar Chart ─────────────────── */
    @if($isPimpinan && $chartData)
    (function () {
        const monthlyData = @json($chartData['chartMonthly']);
        const statusData  = @json($chartData['chartStatus']);

        const tren = new Chart(document.getElementById('chartTren'), {
            type: 'bar',
            data: {
                labels: monthlyData.map(m => m.label),
                datasets: [
                    { label: 'Masuk',   data: monthlyData.map(m => m.masuk),   backgroundColor: 'rgba(59,130,246,.75)', borderRadius: 5 },
                    { label: 'Selesai', data: monthlyData.map(m => m.selesai), backgroundColor: 'rgba(34,197,94,.75)',  borderRadius: 5 },
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                plugins: { legend: { position: 'top', labels: { color: labelColor(), font: { size: 11 } } } },
                scales: {
                    x: { grid: { color: gridColor() }, ticks: { color: labelColor() } },
                    y: { beginAtZero: true, ticks: { precision: 0, color: labelColor() }, grid: { color: gridColor() } },
                }
            }
        });

        const donut = new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: statusData.labels,
                datasets: [{ data: statusData.data, backgroundColor: statusData.colors, borderWidth: 2, borderColor: isDark() ? '#1e293b' : '#fff' }]
            },
            options: { responsive: true, cutout: '68%', plugins: { legend: { display: false } } }
        });

        // Legend
        const legendEl = document.getElementById('chartStatusLegend');
        if (legendEl) {
            legendEl.innerHTML = statusData.labels.map((lbl, i) =>
                `<span class="d-inline-flex align-items-center me-2 mb-1 small" style="color:var(--text-secondary)">
                    <span style="width:10px;height:10px;border-radius:3px;background:${statusData.colors[i]};display:inline-block;margin-right:5px"></span>
                    ${lbl} <strong class="ms-1" style="color:var(--text-primary)">${statusData.data[i]}</strong>
                </span>`
            ).join('');
        }

        // Re-render on theme change
        document.getElementById('themeToggle')?.addEventListener('click', () => {
            setTimeout(() => {
                [tren, donut].forEach(c => c.update());
            }, 300);
        });
    })();
    @endif

    /* ─── Admin: SKPD Bar Chart ────────────────────── */
    @if($isAdmin && $skpdStats->count())
    (function () {
        const labels = @json($skpdStats->pluck('name'));
        const data   = @json($skpdStats->pluck('total_tiket'));
        const baru   = @json($skpdStats->pluck('tiket_baru'));

        const skpdChart = new Chart(document.getElementById('chartSkpd'), {
            type: 'bar',
            data: {
                labels: labels.map(l => l.length > 20 ? l.substring(0,18)+'…' : l),
                datasets: [
                    { label: 'Total',  data: data, backgroundColor: 'rgba(99,102,241,.75)', borderRadius: 5 },
                    { label: 'Baru',   data: baru, backgroundColor: 'rgba(245,158,11,.75)', borderRadius: 5 },
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { color: labelColor(), font: { size: 11 } } } },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0, color: labelColor() }, grid: { color: gridColor() } },
                    y: { ticks: { color: labelColor(), font: { size: 11 } }, grid: { color: gridColor() } },
                }
            }
        });

        document.getElementById('themeToggle')?.addEventListener('click', () => {
            setTimeout(() => skpdChart.update(), 300);
        });
    })();
    @endif
});
</script>
@endpush
