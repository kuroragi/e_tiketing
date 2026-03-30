@extends('layouts.e-ticket')

@section('title', 'Dashboard - Sistem Ticketing Kominfo')

@php
    $authUser   = auth()->user();
    $isAdmin    = $authUser->isAdmin();
    $isPimpinan = $authUser->isPimpinan();
    $isPetugas  = $authUser->isPetugas();
    $isSkpd     = $authUser->isSkpd();
    $canAnalytics = $isAdmin || $isPimpinan || $isPetugas;

    $roleBadge = match(true) {
        $isAdmin    => ['label' => 'Administrator',    'class' => 'bg-danger'],
        $isPetugas  => ['label' => 'Petugas Lapangan', 'class' => 'bg-warning text-dark'],
        $isPimpinan => ['label' => 'Pimpinan',         'class' => 'bg-dark'],
        $isSkpd     => ['label' => 'SKPD',             'class' => 'bg-info text-dark'],
        default     => ['label' => 'Pengguna',         'class' => 'bg-secondary'],
    };

    // Deteksi tab aktif — jika ada filter analitik dikirim, aktifkan tab analitik
    $activeTab = (request()->hasAny(['dari','sampai','analytics_dept','analytics_cat'])
                  || request()->is('*#analitik'))
                 ? 'analitik' : 'ringkasan';
@endphp

@push('styles')
<style>
    /* ── STAT CARDS ───────────────────────────────────── */
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
    .stat-card-modern .stat-sub { font-size: .72rem; color: var(--text-muted); margin-top: .3rem; }

    /* ── WELCOME BANNER ───────────────────────────────── */
    .welcome-banner {
        border-radius: 1rem; padding: 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
        color: #fff; position: relative; overflow: hidden;
        box-shadow: 0 4px 20px rgba(37,99,235,.3);
    }
    .welcome-banner::after  { content:''; position:absolute; right:-30px; top:-30px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.06); }
    .welcome-banner::before { content:''; position:absolute; right:60px;  bottom:-50px; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,.04); }

    /* ── DASHBOARD TABS ───────────────────────────────── */
    .dashboard-tabs {
        border-bottom: 2px solid var(--border);
        margin-bottom: 1.5rem;
        gap: .25rem;
        position: relative;
    }
    .dashboard-tabs .tab-btn {
        background: none; border: none;
        padding: .65rem 1.25rem;
        font-size: .875rem; font-weight: 600;
        color: var(--text-secondary);
        border-radius: .5rem .5rem 0 0;
        cursor: pointer;
        position: relative;
        transition: color .25s ease, background .25s ease;
        display: flex; align-items: center; gap: .5rem;
        white-space: nowrap;
        user-select: none;
    }
    .dashboard-tabs .tab-btn:hover {
        color: var(--primary);
        background: var(--primary-light);
    }
    .dashboard-tabs .tab-btn.active {
        color: var(--primary);
        background: var(--primary-light);
    }
    /* Sliding underline indicator */
    .tab-indicator {
        position: absolute;
        bottom: -2px;
        height: 2px;
        background: var(--primary);
        border-radius: 2px 2px 0 0;
        transition: left .3s cubic-bezier(.4,0,.2,1), width .3s cubic-bezier(.4,0,.2,1);
        pointer-events: none;
    }
    /* Tab pane fade + slide animation */
    .tab-pane {
        display: none;
        opacity: 0;
        transform: translateY(6px);
    }
    .tab-pane.active {
        display: block;
        animation: tabFadeIn .3s ease forwards;
    }
    @keyframes tabFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── CHART CARD ───────────────────────────────────── */
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

    /* ── WORKLOAD ─────────────────────────────────────── */
    .workload-bar { height: 5px; border-radius: 50px; background: var(--border); overflow: hidden; margin-top: 4px; }
    .workload-bar .fill { height: 100%; border-radius: 50px; transition: width .6s ease; }

    /* ── ITEM ROWS ────────────────────────────────────── */
    .activity-item {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: .75rem 1rem; border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .activity-item:hover { background: var(--bg-card-hover); }
    .activity-item:last-child { border-bottom: none; }
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

    /* ── ANALYTICS TAB ────────────────────────────────── */
    .analytics-filter {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 1rem; padding: 1.25rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
    }
    .analytics-stat {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: .85rem; padding: 1.1rem 1rem;
        text-align: center; box-shadow: var(--card-shadow);
        transition: transform .2s;
    }
    .analytics-stat:hover { transform: translateY(-2px); }
    .analytics-stat .a-val { font-size: 1.75rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
    .analytics-stat .a-lbl { font-size: .75rem; color: var(--text-secondary); margin-top: .3rem; font-weight: 500; }
    .analytics-stat .a-sub { font-size: .68rem; color: var(--text-muted); }

    /* SKPD performance table */
    .perf-bar { height: 8px; border-radius: 50px; background: var(--border); overflow: hidden; margin-top: 4px; }
    .perf-bar .fill-ok      { background: linear-gradient(90deg, #22c55e, #16a34a); }
    .perf-bar .fill-warn    { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .perf-bar .fill-danger  { background: linear-gradient(90deg, #ef4444, #dc2626); }

    /* Jenis pekerjaan bar */
    .jenis-bar { height: 6px; border-radius: 50px; background: var(--border); overflow: hidden; }
    .jenis-bar .fill { background: linear-gradient(90deg, var(--primary), #7c3aed); }
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
                    <i class="bi bi-speedometer2 opacity-20" style="font-size:5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ TAB NAV ═══ --}}
<div class="d-flex dashboard-tabs" id="dashboardTabs">
    <button class="tab-btn {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-tab="ringkasan">
        <i class="bi bi-speedometer2"></i> Ringkasan
    </button>
    @if($canAnalytics)
    <button class="tab-btn {{ $activeTab === 'analitik' ? 'active' : '' }}" data-tab="analitik" id="tab-analitik-btn">
        <i class="bi bi-bar-chart-line"></i> Analitik &amp; Laporan
    </button>
    @endif
</div>

{{-- ══════════════════════════════════════════════════
     TAB 1 — RINGKASAN
══════════════════════════════════════════════════ --}}
<div class="tab-pane {{ $activeTab === 'ringkasan' ? 'active' : '' }}" id="pane-ringkasan">

    {{-- Stat Cards --}}
    @if($isAdmin && $adminStats)
        <div class="row g-3 mb-4">
            @foreach($adminStats as $s)
            <div class="col-6 col-lg-3">
                <div class="stat-card-modern">
                    <div class="stat-icon mb-2" style="background:{{ match($s['color']) { 'primary'=>'rgba(37,99,235,.12)', 'info'=>'rgba(8,145,178,.12)', 'danger'=>'rgba(220,38,38,.12)', 'success'=>'rgba(22,163,74,.12)', default=>'rgba(100,116,139,.12)' } }};color:var(--{{ $s['color'] }}{{ $s['color']==='primary' ? '' : '' }});">
                        <i class="{{ $s['icon'] }}"></i>
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
                    <div class="stat-icon mb-2" style="background:rgba(59,130,246,.1);color:#3b82f6;">
                        <i class="bi {{ $s['icon'] }}"></i>
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
                    ['value'=>$stats['total']??0,    'label'=>'Total Tiket'.($isSkpd?' Saya':''), 'color'=>'#6366f1','icon'=>'bi-ticket-perforated'],
                    ['value'=>$stats['baru']??0,     'label'=>'Tiket Baru',     'color'=>'#f59e0b','icon'=>'bi-plus-circle'],
                    ['value'=>$stats['diproses']??0, 'label'=>'Sedang Diproses','color'=>'#3b82f6','icon'=>'bi-arrow-repeat'],
                    ['value'=>$stats['selesai']??0,  'label'=>'Selesai',        'color'=>'#22c55e','icon'=>'bi-check-circle'],
                ];
            @endphp
            @foreach($statItems as $st)
            <div class="col-6 col-lg-3">
                <div class="stat-card-modern">
                    <div class="stat-icon mb-2" style="background:{{ $st['color'] }}18;color:{{ $st['color'] }};"><i class="bi {{ $st['icon'] }}"></i></div>
                    <div class="stat-value" data-count="{{ $st['value'] }}">{{ $st['value'] }}</div>
                    <div class="stat-label">{{ $st['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- Main Row --}}
    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Pimpinan/Admin: Chart tren --}}
            @if(($isPimpinan || $isAdmin) && $chartData)
                <div class="row g-3 mb-4">
                    <div class="col-lg-7">
                        <div class="chart-card h-100">
                            <div class="chart-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-bar-chart-line text-primary me-2"></i>Tren 6 Bulan Terakhir</h6></div>
                            <div class="chart-body"><canvas id="chartTren" height="200"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="chart-card h-100">
                            <div class="chart-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-pie-chart text-warning me-2"></i>Distribusi Status</h6></div>
                            <div class="chart-body d-flex flex-column align-items-center">
                                <canvas id="chartStatus" style="max-width:180px;max-height:180px;"></canvas>
                                <div class="mt-3 w-100" id="chartStatusLegend"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Admin: Chart SKPD --}}
            @if($isAdmin && $skpdStats->count())
                <div class="chart-card mb-4">
                    <div class="chart-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-building text-info me-2"></i>Tiket per SKPD</h6></div>
                    <div class="chart-body"><canvas id="chartSkpd" height="160"></canvas></div>
                </div>
            @endif

            {{-- Admin: Audit log --}}
            @if($isAdmin && $recentActivities->count())
                <div class="chart-card mb-4">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history text-info me-2"></i>Aktivitas Sistem Terbaru</h6>
                        <a href="{{ route('admin.log-aktivitas') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </div>
                    @foreach($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="bg-{{ $activity['color'] }} rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;">
                                <i class="bi {{ $activity['icon'] }} text-white small"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold small" style="color:var(--text-primary)">{{ $activity['action'] }}</div>
                                <div class="small" style="color:var(--text-secondary)"><strong>{{ $activity['user'] }}</strong> &mdash; {{ $activity['target'] }}</div>
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
                        @else Tiket Terbaru @endif
                    </h6>
                    <a href="{{ $isSkpd ? route('tiket.saya') : route('tiket.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
                @forelse($recentTickets as $ticket)
                    @php
                        $isOverdue = $ticket->target_date && \Carbon\Carbon::parse($ticket->target_date)->isPast() && $ticket->isOpen();
                        $overdueDays = $isOverdue ? now()->diffInDays(\Carbon\Carbon::parse($ticket->target_date)) : 0;
                    @endphp
                    <a href="{{ route('tiket.show', $ticket->id) }}" class="activity-item text-decoration-none">
                        <div class="user-avatar flex-shrink-0">{{ substr($ticket->department->name ?? 'T', 0, 1) }}</div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-truncate" style="color:var(--text-primary);">{{ $ticket->title }}</div>
                            <div class="small" style="color:var(--text-secondary);">
                                <i class="bi bi-building me-1"></i>{{ $ticket->department->name ?? '-' }}
                                &nbsp;&bull;&nbsp;<i class="bi bi-calendar me-1"></i>{{ $ticket->created_at->format('d/m/Y') }}
                                @if($isOverdue) &nbsp;<span class="badge-overdue">⚠ {{ $overdueDays }}h terlambat</span>@endif
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                            <span class="status-badge status-{{ $ticket->status }}">{{ $ticket->statusLabel() }}</span>
                            @if($ticket->priority)
                                <small class="priority-{{ strtolower($ticket->priority->name) }}"><i class="bi bi-flag-fill"></i> {{ $ticket->priority->name }}</small>
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

        {{-- Right Column --}}
        <div class="col-lg-4">
            <div class="chart-card mb-4">
                <div class="chart-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-lightning-charge text-warning me-2"></i>Aksi Cepat</h6></div>
                <div class="chart-body d-grid gap-2">
                    @foreach($quickActions as $action)
                        <a href="{{ $action['url'] }}" class="btn btn-{{ $action['color'] }} d-flex align-items-center gap-2">
                            <i class="bi bi-{{ $action['icon'] }}"></i>{{ $action['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if(($isAdmin || $isPetugas) && $petugasWorkload->count())
                <div class="chart-card mb-4">
                    <div class="chart-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-people text-success me-2"></i>Beban Kerja Petugas</h6></div>
                    <div class="p-0">
                        @foreach($petugasWorkload as $p)
                            @php $cnt=$p->aktif_count; $pct=min(100,$cnt*10); $col=$cnt===0?'#22c55e':($cnt<=3?'#3b82f6':($cnt<=6?'#f59e0b':'#ef4444')); $lbl=$cnt===0?'Tersedia':($cnt<=3?'Ringan':($cnt<=6?'Sedang':'Tinggi')); @endphp
                            <div class="skpd-row">
                                <div class="user-avatar flex-shrink-0" style="width:32px;height:32px;font-size:.7rem;">{{ substr($p->name,0,1) }}</div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="small fw-semibold text-truncate" style="color:var(--text-primary)">{{ $p->name }}</div>
                                    <div class="workload-bar"><div class="fill" style="width:{{ $pct }}%;background:{{ $col }};"></div></div>
                                </div>
                                <span class="badge rounded-pill ms-2" style="background:{{ $col }}20;color:{{ $col }};font-size:.7rem;">{{ $cnt }} &bull; {{ $lbl }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($isPimpinan && $skpdStats->count())
                <div class="chart-card mb-4">
                    <div class="chart-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-building text-info me-2"></i>Tiket per SKPD</h6></div>
                    <div class="p-0">
                        @foreach($skpdStats as $i => $dept)
                            @php $pct=$skpdStats->max('total_tiket')>0?round($dept->total_tiket/$skpdStats->max('total_tiket')*100):0; @endphp
                            <div class="skpd-row">
                                <div class="skpd-rank">{{ $i+1 }}</div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="small fw-semibold text-truncate" style="color:var(--text-primary)">{{ $dept->name }}</div>
                                    <div class="workload-bar"><div class="fill" style="width:{{ $pct }}%;background:#3b82f6;"></div></div>
                                </div>
                                <div class="text-end flex-shrink-0 ms-2">
                                    <span class="badge bg-secondary rounded-pill" style="font-size:.7rem;">{{ $dept->total_tiket }}</span>
                                    @if($dept->tiket_baru)<span class="badge bg-warning text-dark rounded-pill ms-1" style="font-size:.7rem;">{{ $dept->tiket_baru }} baru</span>@endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($isAdmin)
                <div class="chart-card">
                    <div class="chart-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-heart-pulse text-success me-2"></i>Status Sistem</h6></div>
                    <div class="chart-body">
                        @foreach([['label'=>'Database'],['label'=>'Web Server'],['label'=>'File Storage']] as $svc)
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small style="color:var(--text-secondary)">{{ $svc['label'] }}</small>
                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>Online</small>
                            </div>
                            <div class="workload-bar mb-3"><div class="fill" style="width:100%;background:#22c55e;"></div></div>
                        @endforeach
                        <div class="alert alert-success py-2 mb-0 small"><i class="bi bi-check-circle me-1"></i>Semua sistem berjalan normal</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     TAB 2 — ANALITIK & LAPORAN
══════════════════════════════════════════════════ --}}
@if($canAnalytics)
<div class="tab-pane {{ $activeTab === 'analitik' ? 'active' : '' }}" id="pane-analitik">

    @if($analyticsData)
    @php
        $s = $analyticsData['summary'];
        $sd = $analyticsData['statusDist'];
        $dari_fmt   = $analyticsData['dari']->format('Y-m-d');
        $sampai_fmt = $analyticsData['sampai']->format('Y-m-d');
    @endphp

    {{-- Filter Periode --}}
    <div class="analytics-filter">
        <form method="GET" action="{{ route('dashboard') }}" id="analyticsForm" class="row g-3 align-items-end">
            <input type="hidden" name="_tab" value="analitik">
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.8rem;">Dari Tanggal</label>
                <input type="date" class="form-control" name="dari" value="{{ $dari_fmt }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.8rem;">Sampai Tanggal</label>
                <input type="date" class="form-control" name="sampai" value="{{ $sampai_fmt }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.8rem;">SKPD</label>
                <select class="form-select" name="analytics_dept">
                    <option value="">Semua SKPD</option>
                    @foreach($skpdList as $d)
                        <option value="{{ $d->id }}" {{ $analyticsData['deptId'] == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.8rem;">Kategori</label>
                <select class="form-select" name="analytics_cat">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $analyticsData['catId'] == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
            </div>
            <div class="col-md-2 d-grid">
                @if($isAdmin || $isPimpinan)
                    <a href="{{ route('laporan.export.csv') }}?dari={{ $dari_fmt }}&sampai={{ $sampai_fmt }}" class="btn btn-success">
                        <i class="bi bi-download me-1"></i>Export CSV
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Summary Stat Cards --}}
    <div class="row g-3 mb-4">
        @php
            $aStats = [
                ['val'=>$s['total_tiket'],        'lbl'=>'Total Tiket',        'sub'=>'periode ini',   'color'=>'#3b82f6', 'icon'=>'bi-ticket'],
                ['val'=>$s['tiket_selesai'],       'lbl'=>'Tiket Selesai',     'sub'=>$s['persentase_selesai'].'% dari total', 'color'=>'#22c55e', 'icon'=>'bi-check-circle'],
                ['val'=>$s['rata_waktu'].' hari',  'lbl'=>'Rata-rata Selesai', 'sub'=>'waktu penyelesaian',    'color'=>'#8b5cf6', 'icon'=>'bi-stopwatch'],
                ['val'=>$s['backlog'],             'lbl'=>'Backlog',           'sub'=>'baru + diproses',       'color'=>'#f59e0b', 'icon'=>'bi-hourglass-split'],
            ];
        @endphp
        @foreach($aStats as $ast)
        <div class="col-6 col-lg-3">
            <div class="analytics-stat">
                <div class="mb-2" style="font-size:1.5rem;color:{{ $ast['color'] }};"><i class="bi {{ $ast['icon'] }}"></i></div>
                <div class="a-val" style="color:{{ $ast['color'] }}">{{ $ast['val'] }}</div>
                <div class="a-lbl">{{ $ast['lbl'] }}</div>
                <div class="a-sub">{{ $ast['sub'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Charts Row --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-card h-100">
                <div class="chart-header">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-graph-up text-primary me-2"></i>Tren Pekerjaan Bulanan</h6>
                    <small style="color:var(--text-muted)">{{ $analyticsData['dari']->translatedFormat('d M Y') }} – {{ $analyticsData['sampai']->translatedFormat('d M Y') }}</small>
                </div>
                <div class="chart-body"><canvas id="chartTrend" height="200"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-card h-100">
                <div class="chart-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-pie-chart text-warning me-2"></i>Distribusi Status</h6></div>
                <div class="chart-body d-flex flex-column align-items-center justify-content-center" style="min-height:260px;">
                    <canvas id="chartStatusAnalytic" style="max-width:180px;max-height:180px;"></canvas>
                    <div class="mt-3 w-100" id="chartStatusAnalyticLegend"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Tables --}}
    <div class="row g-4">
        {{-- SKPD Performance --}}
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-building text-info me-2"></i>Kinerja per SKPD</h6></div>
                <div class="chart-body p-0">
                    @forelse($analyticsData['skpdReport'] as $r)
                        @php $fc = $r['persentase']>=80 ? 'fill-ok' : ($r['persentase']>=60 ? 'fill-warn' : 'fill-danger'); @endphp
                        <div class="skpd-row">
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-semibold text-truncate" style="color:var(--text-primary);max-width:60%">{{ $r['nama'] }}</small>
                                    <div class="d-flex gap-1">
                                        <span class="badge bg-secondary rounded-pill" style="font-size:.65rem;">{{ $r['total'] }} tiket</span>
                                        <span class="badge rounded-pill {{ $r['persentase']>=80?'bg-success':($r['persentase']>=60?'bg-warning text-dark':'bg-danger') }}" style="font-size:.65rem;">{{ $r['persentase'] }}%</span>
                                    </div>
                                </div>
                                <div class="perf-bar"><div class="{{ $fc }}" style="width:{{ $r['persentase'] }}%;height:100%;border-radius:50px;"></div></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4" style="color:var(--text-muted)"><i class="bi bi-inbox mb-2 d-block fs-3"></i>Tidak ada data</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Jenis Pekerjaan --}}
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-tags text-warning me-2"></i>Jenis Pekerjaan Terbanyak</h6>
                </div>
                <div class="chart-body">
                    <canvas id="chartJenis" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    @else
        <div class="text-center py-5">
            <i class="bi bi-bar-chart-line display-1 mb-3 d-block" style="color:var(--text-muted)"></i>
            <h5 style="color:var(--text-muted)">Tidak ada data analitik</h5>
        </div>
    @endif
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* ─── CHART HELPERS & REGISTRY ──────────────────── */
    const isDark    = () => document.documentElement.getAttribute('data-theme') === 'dark';
    const gridColor = () => isDark() ? 'rgba(255,255,255,.08)' : 'rgba(0,0,0,.06)';
    const lblColor  = () => isDark() ? '#94a3b8' : '#64748b';
    Chart.defaults.font.family = "'Inter', sans-serif";
    const charts = [];   // semua chart instance dikumpulkan di sini

    /* ─── TAB SYSTEM ────────────────────────────────── */
    const tabs   = document.querySelectorAll('.tab-btn');
    const panes  = document.querySelectorAll('.tab-pane');
    const tabsEl = document.getElementById('dashboardTabs');

    // Sliding underline indicator
    const indicator = document.createElement('div');
    indicator.className = 'tab-indicator';
    tabsEl.appendChild(indicator);

    function moveIndicator(activeBtn) {
        if (!activeBtn) return;
        const tabsRect = tabsEl.getBoundingClientRect();
        const btnRect  = activeBtn.getBoundingClientRect();
        indicator.style.left  = (btnRect.left - tabsRect.left) + 'px';
        indicator.style.width = btnRect.width + 'px';
    }

    function activateTab(tabId) {
        let activeBtn = null;
        tabs.forEach(t => {
            const isActive = t.dataset.tab === tabId;
            t.classList.toggle('active', isActive);
            if (isActive) activeBtn = t;
        });

        panes.forEach(p => {
            const shouldBeActive = p.id === 'pane-' + tabId;
            if (shouldBeActive) {
                p.style.display = 'block';
                void p.offsetWidth;  // force reflow agar animasi restart
                p.classList.add('active');
                // Resize semua chart setelah animasi selesai (agar tidak gepeng)
                setTimeout(() => charts.forEach(c => { try { c.resize(); } catch(e) {} }), 320);
            } else {
                p.classList.remove('active');
                p.style.display = 'none';
            }
        });

        requestAnimationFrame(() => moveIndicator(activeBtn));

        const hash = tabId !== 'ringkasan' ? '#' + tabId : '';
        history.replaceState(null, '', window.location.pathname + window.location.search + hash);
    }

    tabs.forEach(btn => btn.addEventListener('click', () => activateTab(btn.dataset.tab)));

    // Posisikan indicator ke tab aktif saat page load
    requestAnimationFrame(() => moveIndicator(document.querySelector('.tab-btn.active')));

    // Perbaiki posisi indicator saat resize window
    window.addEventListener('resize', () => moveIndicator(document.querySelector('.tab-btn.active')));

    /* ─── TAB 1: Tren Bar Chart (Admin/Pimpinan) ────── */
    @if(($isAdmin || $isPimpinan) && $chartData)
    (function () {
        const md  = @json($chartData['chartMonthly']);
        const sd  = @json($chartData['chartStatus']);

        const tren = new Chart(document.getElementById('chartTren'), {
            type: 'bar',
            data: {
                labels: md.map(m => m.label),
                datasets: [
                    { label:'Masuk',   data:md.map(m=>m.masuk),   backgroundColor:'rgba(59,130,246,.75)',  borderRadius:5 },
                    { label:'Selesai', data:md.map(m=>m.selesai), backgroundColor:'rgba(34,197,94,.75)',   borderRadius:5 },
                ]
            },
            options: {
                responsive:true, maintainAspectRatio:true,
                plugins:{ legend:{ position:'top', labels:{ color:lblColor(), font:{size:11} } } },
                scales:{
                    x:{ grid:{color:gridColor()}, ticks:{color:lblColor()} },
                    y:{ beginAtZero:true, ticks:{precision:0,color:lblColor()}, grid:{color:gridColor()} },
                }
            }
        });
        charts.push(tren);

        const donut = new Chart(document.getElementById('chartStatus'), {
            type:'doughnut',
            data:{ labels:sd.labels, datasets:[{ data:sd.data, backgroundColor:sd.colors, borderWidth:2, borderColor:isDark()?'#1e293b':'#fff' }] },
            options:{ responsive:true, cutout:'68%', plugins:{ legend:{display:false} } }
        });
        charts.push(donut);

        const leg = document.getElementById('chartStatusLegend');
        if (leg) {
            leg.innerHTML = sd.labels.map((l,i)=>
                `<span class="d-inline-flex align-items-center me-2 mb-1 small" style="color:var(--text-secondary)">
                    <span style="width:10px;height:10px;border-radius:3px;background:${sd.colors[i]};display:inline-block;margin-right:5px"></span>
                    ${l} <strong class="ms-1" style="color:var(--text-primary)">${sd.data[i]}</strong>
                </span>`
            ).join('');
        }
    })();
    @endif

    /* ─── TAB 1: SKPD Bar (Admin) ───────────────────── */
    @if($isAdmin && $skpdStats->count())
    (function () {
        const labels = @json($skpdStats->pluck('name'));
        const data   = @json($skpdStats->pluck('total_tiket'));
        const baru   = @json($skpdStats->pluck('tiket_baru'));

        const c = new Chart(document.getElementById('chartSkpd'), {
            type:'bar',
            data:{
                labels: labels.map(l=>l.length>22?l.substring(0,20)+'…':l),
                datasets:[
                    { label:'Total', data, backgroundColor:'rgba(99,102,241,.75)', borderRadius:5 },
                    { label:'Baru',  data:baru, backgroundColor:'rgba(245,158,11,.75)', borderRadius:5 },
                ]
            },
            options:{
                indexAxis:'y', responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{ position:'top', labels:{ color:lblColor(), font:{size:11} } } },
                scales:{
                    x:{ beginAtZero:true, ticks:{precision:0,color:lblColor()}, grid:{color:gridColor()} },
                    y:{ ticks:{color:lblColor(),font:{size:11}}, grid:{color:gridColor()} },
                }
            }
        });
        charts.push(c);
    })();
    @endif

    /* ─── TAB 2: Charts Analitik ────────────────────── */
    @if($canAnalytics && $analyticsData)
    (function () {
        const trend = @json($analyticsData['trendMonthly']);
        const sdA   = {
            labels: ['Baru','Diproses','Selesai','Ditolak','Dibatalkan'],
            data:   [
                {{ $analyticsData['statusDist']['baru'] ?? 0 }},
                {{ $analyticsData['statusDist']['diproses'] ?? 0 }},
                {{ $analyticsData['statusDist']['selesai'] ?? 0 }},
                {{ $analyticsData['statusDist']['ditolak'] ?? 0 }},
                {{ $analyticsData['statusDist']['dibatalkan'] ?? 0 }},
            ],
            colors: ['#eab308','#3b82f6','#22c55e','#ef4444','#94a3b8'],
        };

        // Tren chart
        const trendChart = new Chart(document.getElementById('chartTrend'), {
            type:'bar',
            data:{
                labels: trend.map(m=>m.label),
                datasets:[
                    { label:'Masuk',   data:trend.map(m=>m.masuk),   backgroundColor:'rgba(59,130,246,.75)',  borderRadius:5 },
                    { label:'Selesai', data:trend.map(m=>m.selesai), backgroundColor:'rgba(34,197,94,.75)',   borderRadius:5 },
                ]
            },
            options:{
                responsive:true, maintainAspectRatio:true,
                plugins:{ legend:{ position:'top', labels:{ color:lblColor(), font:{size:11} } } },
                scales:{
                    x:{ grid:{color:gridColor()}, ticks:{color:lblColor()} },
                    y:{ beginAtZero:true, ticks:{precision:0,color:lblColor()}, grid:{color:gridColor()} },
                }
            }
        });
        charts.push(trendChart);

        // Status donut
        const donutA = new Chart(document.getElementById('chartStatusAnalytic'), {
            type:'doughnut',
            data:{ labels:sdA.labels, datasets:[{ data:sdA.data, backgroundColor:sdA.colors, borderWidth:2, borderColor:isDark()?'#1e293b':'#fff' }] },
            options:{ responsive:true, cutout:'68%', plugins:{ legend:{display:false} } }
        });
        charts.push(donutA);

        const legA = document.getElementById('chartStatusAnalyticLegend');
        if (legA) {
            legA.innerHTML = sdA.labels.map((l,i)=>
                `<span class="d-inline-flex align-items-center me-2 mb-1 small" style="color:var(--text-secondary)">
                    <span style="width:10px;height:10px;border-radius:3px;background:${sdA.colors[i]};display:inline-block;margin-right:5px"></span>
                    ${l} <strong class="ms-1" style="color:var(--text-primary)">${sdA.data[i]}</strong>
                </span>`
            ).join('');
        }

        // Jenis pekerjaan horizontal bar
        const jenisLabels = @json(array_column($analyticsData['jenisReport'], 'nama'));
        const jenisData   = @json(array_column($analyticsData['jenisReport'], 'jumlah'));

        if (jenisLabels.length > 0) {
            const jenisChart = new Chart(document.getElementById('chartJenis'), {
                type:'bar',
                data:{
                    labels: jenisLabels.map(l=>l.length>22?l.substring(0,20)+'…':l),
                    datasets:[{ label:'Jumlah Tiket', data:jenisData, backgroundColor:'rgba(139,92,246,.75)', borderRadius:5 }]
                },
                options:{
                    indexAxis:'y', responsive:true, maintainAspectRatio:false,
                    plugins:{ legend:{display:false} },
                    scales:{
                        x:{ beginAtZero:true, ticks:{precision:0,color:lblColor()}, grid:{color:gridColor()} },
                        y:{ ticks:{color:lblColor(),font:{size:11}}, grid:{color:gridColor()} },
                    }
                }
            });
            charts.push(jenisChart);
        }
    })();
    @endif

    /* ─── Theme change → re-render charts ──────────── */
    document.getElementById('themeToggle')?.addEventListener('click', () => {
        setTimeout(() => charts.forEach(c => c.update()), 300);
    });

    /* ─── Animated counters ─────────────────────────── */
    document.querySelectorAll('.stat-value[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10);
        if (!isNaN(target) && target > 0) {
            const step = Math.ceil(target / 40);
            let cur = 0;
            const t = setInterval(() => {
                cur = Math.min(cur + step, target);
                el.textContent = cur.toLocaleString('id-ID');
                if (cur >= target) clearInterval(t);
            }, 20);
        }
    });

    // Auto refresh every 5 min
    setInterval(() => { if (window.location.pathname==='/dashboard') location.reload(); }, 300000);

    /* ─── Aktifkan tab dari URL hash / query params ─── */
    const urlParams = new URLSearchParams(window.location.search);
    if (window.location.hash === '#analitik' ||
        urlParams.has('dari') || urlParams.has('sampai') ||
        urlParams.has('analytics_dept') || urlParams.has('_tab')) {
        activateTab('analitik');
    }
});
</script>
@endpush
