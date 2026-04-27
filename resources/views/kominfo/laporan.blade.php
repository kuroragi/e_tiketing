@extends('layouts.e-ticket')

@section('title', 'Laporan Komprehensif - Sistem Ticketing Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1 fw-bold"><i class="bi bi-bar-chart-line me-2"></i>Laporan Komprehensif</h2>
            <p class="mb-0 opacity-75">Monitoring dan evaluasi beban kerja Dinas Kominfo Kota Bukittinggi</p>
        </div>
        @if (auth()->user()->isAdmin() || auth()->user()->isPimpinan())
            @php
                $csvParams = http_build_query(
                    array_filter([
                        'dari' => request('dari', now()->startOfMonth()->format('Y-m-d')),
                        'sampai' => request('sampai', now()->endOfMonth()->format('Y-m-d')),
                        'department_id' => request('department_id'),
                        'category_id' => request('category_id'),
                        'status' => request('status'),
                        'priority_id' => request('priority_id'),
                        'assignee_id' => request('assignee_id'),
                    ]),
                );
            @endphp
            <div class="d-flex gap-2">
                <a href="{{ route('laporan.export.csv') }}?{{ $csvParams }}"
                    class="btn btn-success d-flex align-items-center gap-2"
                    style="background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.4);color:#fff;">
                    <i class="bi bi-filetype-csv"></i>Export CSV
                </a>
                <button type="button" class="btn d-flex align-items-center gap-2"
                    style="background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.4);color:#fff;"
                    data-bs-toggle="modal" data-bs-target="#pdfModal">
                    <i class="bi bi-file-earmark-pdf"></i>Export PDF
                </button>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .filter-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .preset-btn {
            font-size: .72rem;
            padding: .2rem .55rem;
            border-radius: 20px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all .15s;
        }

        .preset-btn:hover,
        .preset-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .report-stat {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: .85rem;
            padding: 1.1rem 1.25rem;
            box-shadow: var(--card-shadow);
            transition: transform .2s;
        }

        .report-stat:hover {
            transform: translateY(-2px);
        }

        .report-stat .r-val {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .report-stat .r-lbl {
            font-size: .78rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-top: .3rem;
        }

        .report-stat .r-sub {
            font-size: .7rem;
            color: var(--text-muted);
        }

        .chart-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .chart-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chart-body {
            padding: 1.25rem;
        }

        /* ── Tabs ─────────────────────────────────────────── */
        .report-tabs {
            display: flex;
            gap: .5rem;
            border-bottom: 2px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .report-tab-btn {
            background: none;
            border: none;
            padding: .65rem 1.2rem;
            font-size: .85rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: color .15s, border-color .15s;
        }

        .report-tab-btn:hover {
            color: var(--primary);
        }

        .report-tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .report-tab-pane {
            display: none;
        }

        .report-tab-pane.active {
            display: block;
        }

        /* ── SKPD Cards ───────────────────────────────────── */
        .skpd-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .skpd-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: .85rem;
            overflow: hidden;
            transition: box-shadow .2s;
        }

        .skpd-card:hover {
            box-shadow: 0 4px 18px rgba(0, 0, 0, .12);
        }

        .skpd-card-header {
            padding: .85rem 1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            cursor: pointer;
            border-bottom: 1px solid transparent;
        }

        .skpd-card-header.expanded {
            border-bottom-color: var(--border);
        }

        .skpd-badge {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
            font-size: .75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .skpd-detail {
            display: none;
            padding: .75rem 1rem;
        }

        .skpd-detail.show {
            display: block;
        }

        .perf-bar {
            height: 6px;
            border-radius: 50px;
            background: var(--border);
            overflow: hidden;
            margin-top: 3px;
        }

        .perf-ok {
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .perf-warn {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .perf-danger {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }

        /* ── Category Table ───────────────────────────────── */
        .cat-table tbody tr.cat-row {
            cursor: pointer;
        }

        .cat-table tbody tr.cat-row:hover td {
            background: var(--bg-hover);
        }

        .cat-detail-row td {
            padding: 0 !important;
        }

        .cat-detail-inner {
            display: none;
            padding: .75rem 1.25rem;
            background: var(--bg-body);
        }

        .cat-detail-inner.show {
            display: block;
        }

        /* ── Petugas Table ────────────────────────────────── */
        .perf-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1rem;
            border-bottom: 1px solid var(--border);
        }

        .perf-row:last-child {
            border-bottom: none;
        }

        .jenis-bar {
            height: 5px;
            border-radius: 50px;
            background: var(--border);
            flex: 1;
            overflow: hidden;
        }

        .jenis-fill {
            height: 100%;
            border-radius: 50px;
            background: linear-gradient(90deg, var(--primary), #7c3aed);
        }
    </style>
@endpush

@section('content')

    {{-- ══════════════════════════════════════════════════════════
         Filter Panel
    ══════════════════════════════════════════════════════════ --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('laporan.index') }}" id="filterForm">

            {{-- Date row with presets --}}
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <span class="fw-semibold me-1" style="font-size:.8rem;color:var(--text-secondary);">Periode:</span>
                <button type="button" class="preset-btn" data-preset="this_month">Bulan Ini</button>
                <button type="button" class="preset-btn" data-preset="last_month">Bln Lalu</button>
                <button type="button" class="preset-btn" data-preset="last_3_months">3 Bulan</button>
                <button type="button" class="preset-btn" data-preset="this_year">Tahun Ini</button>
                <div class="d-flex align-items-center gap-2 ms-2">
                    <input type="date" class="form-control form-control-sm" name="dari" id="dari"
                        value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}" style="width:auto;">
                    <span style="color:var(--text-muted)">—</span>
                    <input type="date" class="form-control form-control-sm" name="sampai" id="sampai"
                        value="{{ request('sampai', now()->endOfMonth()->format('Y-m-d')) }}" style="width:auto;">
                </div>
            </div>

            {{-- Filter dropdowns --}}
            <div class="row g-2 align-items-end">
                <div class="col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-semibold" style="font-size:.75rem;">SKPD</label>
                    <select class="form-select form-select-sm" name="department_id">
                        <option value="">Semua SKPD</option>
                        @foreach ($skpdList ?? [] as $skpd)
                            <option value="{{ $skpd->id }}"
                                {{ request('department_id') == $skpd->id ? 'selected' : '' }}>
                                {{ $skpd->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-semibold" style="font-size:.75rem;">Kategori</label>
                    <select class="form-select form-select-sm" name="category_id">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-semibold" style="font-size:.75rem;">Status</label>
                    <select class="form-select form-select-sm" name="status">
                        <option value="">Semua Status</option>
                        @foreach ([
            'baru' => 'Baru',
            'diproses' => 'Diproses',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
        ] as $val => $label)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-semibold" style="font-size:.75rem;">Prioritas</label>
                    <select class="form-select form-select-sm" name="priority_id">
                        <option value="">Semua Prioritas</option>
                        @foreach ($priorities ?? [] as $p)
                            <option value="{{ $p->id }}" {{ request('priority_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if (auth()->user()->isAdmin() || auth()->user()->isPimpinan())
                    <div class="col-sm-6 col-md-4 col-lg-2">
                        <label class="form-label fw-semibold" style="font-size:.75rem;">Petugas</label>
                        <select class="form-select form-select-sm" name="assignee_id">
                            <option value="">Semua Petugas</option>
                            @foreach ($petugasList ?? [] as $pt)
                                <option value="{{ $pt->id }}"
                                    {{ request('assignee_id') == $pt->id ? 'selected' : '' }}>
                                    {{ $pt->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-sm-6 col-md-4 col-lg d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         Summary Stat Cards
    ══════════════════════════════════════════════════════════ --}}
    @php
        $statCards = [
            [
                'val' => $summary['total_tiket'] ?? 0,
                'lbl' => 'Total Tiket',
                'sub' => 'periode ini',
                'color' => '#3b82f6',
                'icon' => 'bi-ticket-perforated',
            ],
            [
                'val' => $summary['tiket_selesai'] ?? 0,
                'lbl' => 'Tiket Selesai',
                'sub' => ($summary['persentase_selesai'] ?? 0) . '% dari total',
                'color' => '#22c55e',
                'icon' => 'bi-check-circle',
            ],
            [
                'val' => ($summary['rata_waktu'] ?? 0) . ' hari',
                'lbl' => 'Rerata Selesai',
                'sub' => 'waktu penyelesaian',
                'color' => '#8b5cf6',
                'icon' => 'bi-stopwatch',
            ],
            [
                'val' => $summary['backlog'] ?? 0,
                'lbl' => 'Backlog',
                'sub' => 'baru + diproses',
                'color' => '#f59e0b',
                'icon' => 'bi-hourglass-split',
            ],
            [
                'val' => $summary['tiket_ditolak'] ?? 0,
                'lbl' => 'Ditolak',
                'sub' => 'tiket tidak diproses',
                'color' => '#ef4444',
                'icon' => 'bi-x-circle',
            ],
            [
                'val' => count($skpdDetail ?? []),
                'lbl' => 'SKPD Aktif',
                'sub' => 'memiliki tiket periode ini',
                'color' => '#06b6d4',
                'icon' => 'bi-building',
            ],
        ];
    @endphp
    <div class="row g-3 mb-4">
        @foreach ($statCards as $c)
            <div class="col-6 col-lg-2">
                <div class="report-stat">
                    <div class="mb-2" style="font-size:1.3rem;color:{{ $c['color'] }};"><i
                            class="bi {{ $c['icon'] }}"></i></div>
                    <div class="r-val" style="color:{{ $c['color'] }}">{{ $c['val'] }}</div>
                    <div class="r-lbl">{{ $c['lbl'] }}</div>
                    <div class="r-sub">{{ $c['sub'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════════
         Tab Navigation
    ══════════════════════════════════════════════════════════ --}}
    <div class="report-tabs">
        <button class="report-tab-btn active" data-tab="ringkasan">
            <i class="bi bi-grid-1x2 me-1"></i>Ringkasan
        </button>
        <button class="report-tab-btn" data-tab="per-skpd">
            <i class="bi bi-building me-1"></i>Per SKPD
            <span class="badge bg-secondary rounded-pill ms-1"
                style="font-size:.65rem;">{{ count($skpdDetail ?? []) }}</span>
        </button>
        <button class="report-tab-btn" data-tab="per-kategori">
            <i class="bi bi-tags me-1"></i>Per Kategori
            <span class="badge bg-secondary rounded-pill ms-1"
                style="font-size:.65rem;">{{ count($categoryDetail ?? []) }}</span>
        </button>
        @if (!empty($petugasStats))
            <button class="report-tab-btn" data-tab="petugas">
                <i class="bi bi-person-badge me-1"></i>Kinerja Petugas
            </button>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════
         TAB: Ringkasan
    ══════════════════════════════════════════════════════════ --}}
    <div class="report-tab-pane active" id="tab-ringkasan">
        <div class="row g-4 mb-4">
            {{-- Trend chart --}}
            <div class="col-lg-8">
                <div class="chart-card h-100">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-graph-up text-primary me-2"></i>Tren Pekerjaan 6
                            Bulan Terakhir</h6>
                        <small style="color:var(--text-muted);">{{ $dari->translatedFormat('d M Y') }} –
                            {{ $sampai->translatedFormat('d M Y') }}</small>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartTrend" height="200"></canvas>
                    </div>
                </div>
            </div>
            {{-- Status donut --}}
            <div class="col-lg-4">
                <div class="chart-card h-100">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-pie-chart text-warning me-2"></i>Distribusi Status
                        </h6>
                    </div>
                    <div class="chart-body d-flex flex-column align-items-center"
                        style="min-height:260px;justify-content:center;">
                        <canvas id="chartStatus" style="max-width:170px;max-height:170px;"></canvas>
                        <div class="mt-3 w-100" id="statusLegend"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- SKPD quick bars --}}
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-building text-info me-2"></i>Kinerja per SKPD</h6>
                        <span class="badge bg-secondary rounded-pill"
                            style="font-size:.7rem;">{{ count($skpdReport ?? []) }} SKPD</span>
                    </div>
                    <div class="p-0">
                        @forelse($skpdReport ?? [] as $r)
                            @php $fc = $r['persentase']>=80 ? 'perf-ok' : ($r['persentase']>=60 ? 'perf-warn' : 'perf-danger'); @endphp
                            <div class="perf-row">
                                <div
                                    style="width:28px;height:28px;border-radius:6px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(mb_substr($r['nama'], 0, 1)) }}
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-semibold text-truncate"
                                            style="color:var(--text-primary);max-width:55%">{{ $r['nama'] }}</small>
                                        <div class="d-flex gap-1 flex-shrink-0">
                                            <span class="badge bg-secondary rounded-pill"
                                                style="font-size:.65rem;">{{ $r['total'] }}</span>
                                            <span
                                                class="badge {{ $r['persentase'] >= 80 ? 'bg-success' : ($r['persentase'] >= 60 ? 'bg-warning text-dark' : 'bg-danger') }} rounded-pill"
                                                style="font-size:.65rem;">{{ $r['persentase'] }}%</span>
                                        </div>
                                    </div>
                                    <div class="perf-bar">
                                        <div class="{{ $fc }}"
                                            style="width:{{ $r['persentase'] }}%;height:100%;border-radius:50px;"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5" style="color:var(--text-muted)">
                                <i class="bi bi-building mb-2 d-block fs-2"></i>Tidak ada data SKPD
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            {{-- Category horizontal bar --}}
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-header">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-tags text-warning me-2"></i>Jenis Pekerjaan Terbanyak
                        </h6>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartJenis" height="240"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         TAB: Per SKPD
    ══════════════════════════════════════════════════════════ --}}
    <div class="report-tab-pane" id="tab-per-skpd">
        {{-- Sort toolbar --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <small style="color:var(--text-muted);">Klik header SKPD untuk melihat detail kategori &amp; petugas</small>
            <div class="d-flex gap-2 align-items-center">
                <small class="fw-semibold" style="font-size:.75rem;color:var(--text-secondary);">Urutkan:</small>
                <select class="form-select form-select-sm" id="skpdSort" style="width:auto;">
                    <option value="total">Total Tiket</option>
                    <option value="selesai">Tiket Selesai</option>
                    <option value="backlog">Backlog</option>
                    <option value="persentase">% Selesai</option>
                </select>
            </div>
        </div>

        <div class="skpd-grid" id="skpdGrid">
            @forelse($skpdDetail ?? [] as $dept)
                @php
                    $pct = $dept['persentase'];
                    $barCls = $pct >= 80 ? 'perf-ok' : ($pct >= 60 ? 'perf-warn' : 'perf-danger');
                    $badgeCls = $pct >= 80 ? 'bg-success' : ($pct >= 60 ? 'bg-warning text-dark' : 'bg-danger');
                @endphp
                <div class="skpd-card" data-total="{{ $dept['total'] }}" data-selesai="{{ $dept['selesai'] }}"
                    data-backlog="{{ $dept['backlog'] }}" data-persentase="{{ $pct }}">
                    <div class="skpd-card-header" onclick="toggleSkpd(this)">
                        <div class="skpd-badge">{{ $dept['code'] ?? strtoupper(mb_substr($dept['nama'], 0, 3)) }}</div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-truncate" style="font-size:.85rem;color:var(--text-primary);">
                                {{ $dept['nama'] }}</div>
                            <div class="d-flex gap-1 mt-1 flex-wrap">
                                <span class="badge bg-secondary rounded-pill"
                                    style="font-size:.65rem;">{{ $dept['total'] }} tiket</span>
                                <span class="badge {{ $badgeCls }} rounded-pill"
                                    style="font-size:.65rem;">{{ $pct }}% selesai</span>
                                @if ($dept['backlog'] > 0)
                                    <span class="badge bg-warning text-dark rounded-pill"
                                        style="font-size:.65rem;">{{ $dept['backlog'] }} backlog</span>
                                @endif
                            </div>
                            <div class="perf-bar mt-1">
                                <div class="{{ $barCls }}"
                                    style="width:{{ $pct }}%;height:100%;border-radius:50px;"></div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down"
                            style="font-size:.7rem;color:var(--text-muted);transition:transform .2s;flex-shrink:0;"></i>
                    </div>
                    <div class="skpd-detail">
                        {{-- Stats row --}}
                        <div class="d-flex gap-3 flex-wrap mb-3" style="font-size:.78rem;">
                            <div><span style="color:var(--text-muted);">Selesai:</span> <strong
                                    style="color:#22c55e;">{{ $dept['selesai'] }}</strong></div>
                            <div><span style="color:var(--text-muted);">Ditolak:</span> <strong
                                    style="color:#ef4444;">{{ $dept['ditolak'] }}</strong></div>
                            <div><span style="color:var(--text-muted);">Dibatalkan:</span> <strong
                                    style="color:#94a3b8;">{{ $dept['dibatalkan'] }}</strong></div>
                            <div><span style="color:var(--text-muted);">Rerata:</span> <strong>{{ $dept['rata_hari'] }}
                                    hari</strong></div>
                        </div>

                        {{-- Top categories --}}
                        @if (!empty($dept['kategori']))
                            <div class="mb-3">
                                <div class="fw-semibold mb-2"
                                    style="font-size:.75rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;">
                                    Top Kategori</div>
                                @foreach ($dept['kategori'] as $kat)
                                    <div class="d-flex align-items-center gap-2 mb-1" style="font-size:.8rem;">
                                        <div class="flex-grow-1 text-truncate" style="color:var(--text-primary);">
                                            {{ $kat['nama'] }}</div>
                                        <span class="badge bg-secondary rounded-pill"
                                            style="font-size:.65rem;">{{ $kat['jumlah'] }}</span>
                                        <span
                                            class="badge {{ $kat['selesai'] == $kat['jumlah'] ? 'bg-success' : 'bg-secondary' }} rounded-pill"
                                            style="font-size:.65rem;">{{ $kat['persentase'] }}%</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Assigned petugas --}}
                        @if (!empty($dept['petugas']))
                            <div>
                                <div class="fw-semibold mb-2"
                                    style="font-size:.75rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;">
                                    Petugas Penanganan</div>
                                @foreach ($dept['petugas'] as $pt)
                                    <div class="d-flex align-items-center gap-2 mb-1" style="font-size:.8rem;">
                                        <div
                                            style="width:24px;height:24px;border-radius:6px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0;">
                                            {{ strtoupper(mb_substr($pt['nama'], 0, 1)) }}</div>
                                        <div class="flex-grow-1 text-truncate" style="color:var(--text-primary);">
                                            {{ $pt['nama'] }}</div>
                                        <span class="badge bg-secondary rounded-pill"
                                            style="font-size:.65rem;">{{ $pt['jumlah'] }}</span>
                                        <span class="badge bg-success rounded-pill"
                                            style="font-size:.65rem;">{{ $pt['selesai'] }} ✓</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="font-size:.78rem;color:var(--text-muted);">Belum ada petugas ditugaskan</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5" style="color:var(--text-muted);">
                    <i class="bi bi-building mb-2 d-block fs-1"></i>Tidak ada data SKPD pada periode ini
                </div>
            @endforelse
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         TAB: Per Kategori
    ══════════════════════════════════════════════════════════ --}}
    <div class="report-tab-pane" id="tab-per-kategori">
        <div class="chart-card">
            <div class="chart-header">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-tags text-warning me-2"></i>Detail per Kategori</h6>
                <small style="color:var(--text-muted);">Klik baris untuk detail distribusi status &amp; SKPD</small>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 cat-table" style="font-size:.83rem;">
                    <thead style="background:var(--bg-sidebar);color:var(--text-secondary);">
                        <tr>
                            <th class="px-3 py-2">Kategori</th>
                            <th class="text-center py-2">Total</th>
                            <th class="text-center py-2">Selesai</th>
                            <th class="text-center py-2">Backlog</th>
                            <th class="text-center py-2">% Selesai</th>
                            <th class="py-2">Rerata</th>
                            <th class="py-2" style="width:30px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categoryDetail ?? [] as $i => $cat)
                            @php
                                $bgCls =
                                    $cat['persentase'] >= 80
                                        ? 'bg-success'
                                        : ($cat['persentase'] >= 60
                                            ? 'bg-warning text-dark'
                                            : 'bg-danger');
                            @endphp
                            <tr class="cat-row" onclick="toggleCat(this, 'cat-detail-{{ $i }}')">
                                <td class="px-3 py-2 fw-semibold" style="color:var(--text-primary);">{{ $cat['nama'] }}
                                </td>
                                <td class="text-center py-2"><span
                                        class="badge bg-secondary rounded-pill">{{ $cat['total'] }}</span></td>
                                <td class="text-center py-2"><span
                                        class="badge bg-success rounded-pill">{{ $cat['selesai'] }}</span></td>
                                <td class="text-center py-2">
                                    @if ($cat['backlog'] > 0)
                                        <span class="badge bg-warning text-dark rounded-pill">{{ $cat['backlog'] }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center py-2"><span
                                        class="badge {{ $bgCls }} rounded-pill">{{ $cat['persentase'] }}%</span>
                                </td>
                                <td class="py-2" style="color:var(--text-secondary);">{{ $cat['rata_hari'] }} hr</td>
                                <td class="py-2 text-center"><i class="bi bi-chevron-down cat-chevron"
                                        style="font-size:.7rem;color:var(--text-muted);transition:transform .2s;"></i></td>
                            </tr>
                            <tr class="cat-detail-row">
                                <td colspan="7" class="p-0">
                                    <div class="cat-detail-inner" id="cat-detail-{{ $i }}">
                                        <div class="row g-3 py-2">
                                            {{-- Status distribution --}}
                                            <div class="col-md-5">
                                                <div class="fw-semibold mb-2"
                                                    style="font-size:.72rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;">
                                                    Distribusi Status</div>
                                                @php
                                                    $statusMap = [
                                                        'baru' => ['label' => 'Baru', 'color' => '#eab308'],
                                                        'diproses' => ['label' => 'Diproses', 'color' => '#3b82f6'],
                                                        'menunggu_verifikasi' => [
                                                            'label' => 'Menunggu Verifikasi',
                                                            'color' => '#8b5cf6',
                                                        ],
                                                        'selesai' => ['label' => 'Selesai', 'color' => '#22c55e'],
                                                        'ditolak' => ['label' => 'Ditolak', 'color' => '#ef4444'],
                                                        'dibatalkan' => ['label' => 'Dibatalkan', 'color' => '#94a3b8'],
                                                    ];
                                                @endphp
                                                @foreach ($statusMap as $sk => $si)
                                                    @php $sv = $cat['status_dist'][$sk] ?? 0; @endphp
                                                    @if ($sv > 0)
                                                        <div class="d-flex align-items-center gap-2 mb-1"
                                                            style="font-size:.78rem;">
                                                            <span
                                                                style="width:8px;height:8px;border-radius:2px;background:{{ $si['color'] }};display:inline-block;flex-shrink:0;"></span>
                                                            <span class="flex-grow-1"
                                                                style="color:var(--text-secondary);">{{ $si['label'] }}</span>
                                                            <strong
                                                                style="color:var(--text-primary);">{{ $sv }}</strong>
                                                            <span
                                                                style="color:var(--text-muted);">{{ $cat['total'] ? round(($sv / $cat['total']) * 100) : 0 }}%</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            {{-- Top SKPDs --}}
                                            <div class="col-md-7">
                                                <div class="fw-semibold mb-2"
                                                    style="font-size:.72rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;">
                                                    Top SKPD Pengaju</div>
                                                @foreach ($cat['skpd_top'] as $sk)
                                                    <div class="d-flex align-items-center gap-2 mb-1"
                                                        style="font-size:.78rem;">
                                                        <div class="flex-grow-1 text-truncate"
                                                            style="color:var(--text-primary);">{{ $sk['nama'] }}</div>
                                                        <span class="badge bg-secondary rounded-pill"
                                                            style="font-size:.65rem;">{{ $sk['jumlah'] }}</span>
                                                        <span
                                                            style="color:var(--text-muted);">{{ $sk['persentase'] }}%</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5" style="color:var(--text-muted);">
                                    <i class="bi bi-tags mb-2 d-block fs-1"></i>Tidak ada data kategori pada periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         TAB: Kinerja Petugas
    ══════════════════════════════════════════════════════════ --}}
    @if (!empty($petugasStats) && $petugasStats->isNotEmpty())
        <div class="report-tab-pane" id="tab-petugas">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-person-badge text-primary me-2"></i>Kinerja Petugas</h6>
                    <span class="badge bg-secondary rounded-pill" style="font-size:.7rem;">{{ $petugasStats->count() }}
                        Petugas</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0" style="font-size:.85rem;">
                        <thead style="background:var(--bg-sidebar);color:var(--text-secondary);">
                            <tr>
                                <th class="px-3 py-2">Petugas</th>
                                <th class="text-center py-2">Ditugaskan</th>
                                <th class="text-center py-2">Selesai</th>
                                <th class="text-center py-2">Belum Selesai</th>
                                <th class="py-2">Tingkat Penyelesaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($petugasStats as $p)
                                @php
                                    $pct = $p->total_assigned
                                        ? round(($p->total_selesai / $p->total_assigned) * 100)
                                        : 0;
                                    $barCls = $pct >= 80 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <tr>
                                    <td class="px-3 py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                style="width:28px;height:28px;border-radius:6px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">
                                                {{ strtoupper(mb_substr($p->name, 0, 1)) }}
                                            </div>
                                            <span class="fw-semibold"
                                                style="color:var(--text-primary)">{{ $p->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center py-2"><span
                                            class="badge bg-secondary rounded-pill">{{ $p->total_assigned }}</span></td>
                                    <td class="text-center py-2"><span
                                            class="badge bg-success rounded-pill">{{ $p->total_selesai }}</span></td>
                                    <td class="text-center py-2">
                                        <span
                                            class="badge {{ $p->total_assigned - $p->total_selesai > 0 ? 'bg-warning text-dark' : 'bg-secondary' }} rounded-pill">
                                            {{ $p->total_assigned - $p->total_selesai }}
                                        </span>
                                    </td>
                                    <td class="py-2" style="min-width:140px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="flex-grow-1"
                                                style="height:6px;border-radius:50px;background:var(--border);overflow:hidden;">
                                                <div class="{{ $barCls }}"
                                                    style="width:{{ $pct }}%;height:100%;border-radius:50px;">
                                                </div>
                                            </div>
                                            <small class="fw-semibold"
                                                style="color:var(--text-secondary);min-width:32px;">{{ $pct }}%</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════
         MODAL: Konfigurasi Laporan PDF
    ══════════════════════════════════════════════════════════ --}}
    @if (auth()->user()->isAdmin() || auth()->user()->isPimpinan())
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background:var(--primary);color:#fff;">
                    <h5 class="modal-title" id="pdfModalLabel">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Cetak Laporan PDF
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('laporan.export.pdf') }}" method="GET" target="_blank">
                    <div class="modal-body">

                        <div class="alert alert-info d-flex align-items-start gap-2 py-2" style="font-size:.85rem;">
                            <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                            <div>
                                Laporan akan dicetak dengan kop:<br>
                                <strong>Laporan Tiket Pekerjaan · Dinas Komunikasi dan Informatika · Kota Bukittinggi</strong><br>
                                Isi laporan mencakup daftar tiket sesuai rentang waktu dan filter petugas yang dipilih.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rentang Waktu Laporan</label>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <label class="form-label" style="font-size:.8rem;">Dari Tanggal</label>
                                    <input type="date" class="form-control" name="dari" required
                                        value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" style="font-size:.8rem;">Sampai Tanggal</label>
                                    <input type="date" class="form-control" name="sampai" required
                                        value="{{ request('sampai', now()->endOfMonth()->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                @php
                                    $pdfPresets = [
                                        ['label' => 'Bulan Ini',  'dari' => now()->startOfMonth()->format('Y-m-d'),                         'sampai' => now()->endOfMonth()->format('Y-m-d')],
                                        ['label' => 'Bulan Lalu', 'dari' => now()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d'),    'sampai' => now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d')],
                                        ['label' => '3 Bulan',    'dari' => now()->subMonths(2)->startOfMonth()->format('Y-m-d'),            'sampai' => now()->endOfMonth()->format('Y-m-d')],
                                        ['label' => 'Tahun Ini',  'dari' => now()->startOfYear()->format('Y-m-d'),                          'sampai' => now()->endOfYear()->format('Y-m-d')],
                                    ];
                                @endphp
                                @foreach ($pdfPresets as $pr)
                                    <button type="button" class="btn btn-sm btn-outline-secondary pdf-preset"
                                        data-dari="{{ $pr['dari'] }}" data-sampai="{{ $pr['sampai'] }}"
                                        style="font-size:.75rem;padding:.2rem .6rem;border-radius:20px;">
                                        {{ $pr['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Filter Petugas</label>
                            <div class="mb-2 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllPetugas"
                                    style="font-size:.78rem;">Pilih Semua</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clearPetugas"
                                    style="font-size:.78rem;">Kosongkan</button>
                                <span class="text-muted" style="font-size:.78rem;align-self:center;">
                                    (kosongkan = semua petugas)
                                </span>
                            </div>
                            <div class="border rounded p-3" style="max-height:200px;overflow-y:auto;background:var(--bg-body,#f8fafc);">
                                @forelse ($petugasList ?? [] as $pt)
                                    <div class="form-check">
                                        <input class="form-check-input petugas-check" type="checkbox"
                                            name="assignee_ids[]" value="{{ $pt->id }}"
                                            id="pt_{{ $pt->id }}">
                                        <label class="form-check-label" for="pt_{{ $pt->id }}" style="font-size:.88rem;">
                                            {{ $pt->name }}
                                        </label>
                                    </div>
                                @empty
                                    <div class="text-muted" style="font-size:.85rem;">Tidak ada data petugas.</div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = () => document.documentElement.getAttribute('data-theme') === 'dark';
            const gridColor = () => isDark() ? 'rgba(255,255,255,.08)' : 'rgba(0,0,0,.06)';
            const lblColor = () => isDark() ? '#94a3b8' : '#64748b';
            Chart.defaults.font.family = "'Inter', sans-serif";
            const charts = [];

            /* ── Tab navigation ───────────────────────────────── */
            const tabBtns = document.querySelectorAll('.report-tab-btn');
            const tabPanes = document.querySelectorAll('.report-tab-pane');

            function activateTab(tabId) {
                tabBtns.forEach(b => b.classList.toggle('active', b.dataset.tab === tabId));
                tabPanes.forEach(p => p.classList.toggle('active', p.id === 'tab-' + tabId));
                sessionStorage.setItem('laporanTab', tabId);
                charts.forEach(c => c.resize());
            }

            tabBtns.forEach(btn => btn.addEventListener('click', () => activateTab(btn.dataset.tab)));

            const savedTab = sessionStorage.getItem('laporanTab');
            if (savedTab && document.getElementById('tab-' + savedTab)) activateTab(savedTab);

            /* ── Date preset buttons ──────────────────────────── */
            const dariFld = document.getElementById('dari');
            const sampaiFld = document.getElementById('sampai');

            function fmt(d) {
                return d.toISOString().split('T')[0];
            }

            const presets = {
                this_month: () => {
                    const n = new Date();
                    dariFld.value = fmt(new Date(n.getFullYear(), n.getMonth(), 1));
                    sampaiFld.value = fmt(new Date(n.getFullYear(), n.getMonth() + 1, 0));
                },
                last_month: () => {
                    const n = new Date();
                    dariFld.value = fmt(new Date(n.getFullYear(), n.getMonth() - 1, 1));
                    sampaiFld.value = fmt(new Date(n.getFullYear(), n.getMonth(), 0));
                },
                last_3_months: () => {
                    const n = new Date();
                    dariFld.value = fmt(new Date(n.getFullYear(), n.getMonth() - 2, 1));
                    sampaiFld.value = fmt(new Date(n.getFullYear(), n.getMonth() + 1, 0));
                },
                this_year: () => {
                    const n = new Date();
                    dariFld.value = fmt(new Date(n.getFullYear(), 0, 1));
                    sampaiFld.value = fmt(new Date(n.getFullYear(), 11, 31));
                },
            };

            document.querySelectorAll('.preset-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove(
                        'active'));
                    btn.classList.add('active');
                    presets[btn.dataset.preset]?.();
                    document.getElementById('filterForm').submit();
                });
            });

            /* ── SKPD card expand/collapse ────────────────────── */
            window.toggleSkpd = function(header) {
                const detail = header.nextElementSibling;
                const chevron = header.querySelector('.bi-chevron-down, .bi-chevron-up');
                const isOpen = detail.classList.contains('show');
                detail.classList.toggle('show', !isOpen);
                header.classList.toggle('expanded', !isOpen);
                if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
            };

            /* ── Category row expand/collapse ────────────────── */
            window.toggleCat = function(row, detailId) {
                const inner = document.getElementById(detailId);
                const chevron = row.querySelector('.cat-chevron');
                const isOpen = inner?.classList.contains('show');
                inner?.classList.toggle('show', !isOpen);
                if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
            };

            /* ── SKPD grid sort ───────────────────────────────── */
            const skpdGrid = document.getElementById('skpdGrid');
            document.getElementById('skpdSort')?.addEventListener('change', function() {
                const key = this.value;
                const cards = [...skpdGrid.querySelectorAll('.skpd-card')];
                cards.sort((a, b) => Number(b.dataset[key]) - Number(a.dataset[key]));
                cards.forEach(c => skpdGrid.appendChild(c));
            });

            /* ── Trend chart ──────────────────────────────────── */
            (function() {
                const td = @json($trendData ?? []);
                if (!td.length) return;
                const c = new Chart(document.getElementById('chartTrend'), {
                    type: 'bar',
                    data: {
                        labels: td.map(m => m.label),
                        datasets: [{
                                label: 'Masuk',
                                data: td.map(m => m.masuk),
                                backgroundColor: 'rgba(59,130,246,.75)',
                                borderRadius: 5
                            },
                            {
                                label: 'Selesai',
                                data: td.map(m => m.selesai),
                                backgroundColor: 'rgba(34,197,94,.75)',
                                borderRadius: 5
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: lblColor(),
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: gridColor()
                                },
                                ticks: {
                                    color: lblColor()
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: lblColor()
                                },
                                grid: {
                                    color: gridColor()
                                }
                            },
                        },
                    },
                });
                charts.push(c);
            })();

            /* ── Status donut ─────────────────────────────────── */
            (function() {
                const sd = {
                    labels: ['Baru', 'Diproses', 'Mng. Verifikasi', 'Selesai', 'Ditolak', 'Dibatalkan'],
                    data: [
                        {{ $statusDistribution['baru'] ?? 0 }},
                        {{ $statusDistribution['diproses'] ?? 0 }},
                        {{ $statusDistribution['menunggu_verifikasi'] ?? 0 }},
                        {{ $statusDistribution['selesai'] ?? 0 }},
                        {{ $statusDistribution['ditolak'] ?? 0 }},
                        {{ $statusDistribution['dibatalkan'] ?? 0 }},
                    ],
                    colors: ['#eab308', '#3b82f6', '#8b5cf6', '#22c55e', '#ef4444', '#94a3b8'],
                };
                const c = new Chart(document.getElementById('chartStatus'), {
                    type: 'doughnut',
                    data: {
                        labels: sd.labels,
                        datasets: [{
                            data: sd.data,
                            backgroundColor: sd.colors,
                            borderWidth: 2,
                            borderColor: isDark() ? '#1e293b' : '#fff'
                        }],
                    },
                    options: {
                        responsive: true,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    },
                });
                charts.push(c);

                const leg = document.getElementById('statusLegend');
                if (leg) {
                    leg.innerHTML = sd.labels.map((l, i) =>
                        `<span class="d-inline-flex align-items-center me-2 mb-1 small" style="color:var(--text-secondary)">
                            <span style="width:10px;height:10px;border-radius:3px;background:${sd.colors[i]};display:inline-block;margin-right:5px"></span>
                            ${l} <strong class="ms-1" style="color:var(--text-primary)">${sd.data[i]}</strong>
                        </span>`
                    ).join('');
                }
            })();

            /* ── Category horizontal bar ──────────────────────── */
            @php
                $jenisLabels = array_column($jenisReport ?? [], 'nama');
                $jenisValues = array_column($jenisReport ?? [], 'jumlah');
            @endphp
            @if (count($jenisReport ?? []) > 0)
                (function() {
                    const labels = @json($jenisLabels);
                    const data = @json($jenisValues);
                    const c = new Chart(document.getElementById('chartJenis'), {
                        type: 'bar',
                        data: {
                            labels: labels.map(l => l.length > 26 ? l.substring(0, 24) + '…' : l),
                            datasets: [{
                                label: 'Jumlah Tiket',
                                data,
                                backgroundColor: 'rgba(139,92,246,.75)',
                                borderRadius: 5
                            }],
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        color: lblColor()
                                    },
                                    grid: {
                                        color: gridColor()
                                    }
                                },
                                y: {
                                    ticks: {
                                        color: lblColor(),
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        color: gridColor()
                                    }
                                },
                            },
                        },
                    });
                    charts.push(c);
                })();
            @endif

            /* ── Re-render on theme toggle ────────────────────── */
            document.getElementById('themeToggle')?.addEventListener('click', () => {
                setTimeout(() => charts.forEach(c => c.update()), 300);
            });

            /* ── PDF Modal: preset tanggal & pilih petugas ─────── */
            document.querySelectorAll('.pdf-preset').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const form = btn.closest('form');
                    form.querySelector('[name="dari"]').value   = btn.dataset.dari;
                    form.querySelector('[name="sampai"]').value = btn.dataset.sampai;
                });
            });
            document.getElementById('selectAllPetugas')?.addEventListener('click', function () {
                document.querySelectorAll('.petugas-check').forEach(cb => cb.checked = true);
            });
            document.getElementById('clearPetugas')?.addEventListener('click', function () {
                document.querySelectorAll('.petugas-check').forEach(cb => cb.checked = false);
            });
        });
    </script>
@endpush
