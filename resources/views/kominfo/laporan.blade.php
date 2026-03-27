@extends('layouts.e-ticket')

@section('title', 'Laporan - Sistem Ticketing Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1 fw-bold"><i class="bi bi-bar-chart-line me-2"></i>Laporan Pekerjaan</h2>
            <p class="mb-0 opacity-75">Monitoring dan evaluasi beban kerja Dinas Kominfo Kota Bukittinggi</p>
        </div>
        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
            <a href="{{ route('laporan.export.csv') }}?dari={{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}&sampai={{ request('sampai', now()->endOfMonth()->format('Y-m-d')) }}"
               class="btn btn-success d-flex align-items-center gap-2" style="background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.4);color:#fff;">
                <i class="bi bi-download"></i>Export CSV
            </a>
        @endif
    </div>
@endsection

@push('styles')
<style>
    .filter-card { background:var(--bg-card); border:1px solid var(--border); border-radius:1rem; padding:1.25rem; box-shadow:var(--card-shadow); margin-bottom:1.5rem; }
    .report-stat { background:var(--bg-card); border:1px solid var(--border); border-radius:.85rem; padding:1.1rem 1.25rem; box-shadow:var(--card-shadow); transition:transform .2s; }
    .report-stat:hover { transform:translateY(-2px); }
    .report-stat .r-val { font-size:2rem; font-weight:800; line-height:1; }
    .report-stat .r-lbl { font-size:.78rem; color:var(--text-secondary); font-weight:500; margin-top:.3rem; }
    .report-stat .r-sub { font-size:.7rem; color:var(--text-muted); }
    .chart-card { background:var(--bg-card); border:1px solid var(--border); border-radius:1rem; box-shadow:var(--card-shadow); overflow:hidden; }
    .chart-header { padding:1rem 1.25rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
    .chart-body { padding:1.25rem; }
    .perf-row { display:flex; align-items:center; gap:.75rem; padding:.65rem 1rem; border-bottom:1px solid var(--border); }
    .perf-row:last-child { border-bottom:none; }
    .perf-bar { height:6px; border-radius:50px; background:var(--border); overflow:hidden; margin-top:3px; }
    .perf-ok     { background:linear-gradient(90deg,#22c55e,#16a34a); }
    .perf-warn   { background:linear-gradient(90deg,#f59e0b,#d97706); }
    .perf-danger { background:linear-gradient(90deg,#ef4444,#dc2626); }
    .jenis-row { display:flex; align-items:center; gap:.75rem; padding:.55rem 1rem; border-bottom:1px solid var(--border); }
    .jenis-row:last-child { border-bottom:none; }
    .jenis-bar { height:5px; border-radius:50px; background:var(--border); flex:1; overflow:hidden; }
    .jenis-fill { height:100%; border-radius:50px; background:linear-gradient(90deg,var(--primary),#7c3aed); }
</style>
@endpush

@section('content')

    {{-- Filter Periode --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.8rem;">Dari Tanggal</label>
                <input type="date" class="form-control" name="dari" id="dari"
                    value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.8rem;">Sampai Tanggal</label>
                <input type="date" class="form-control" name="sampai" id="sampai"
                    value="{{ request('sampai', now()->endOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.8rem;">SKPD</label>
                <select class="form-select" name="department_id" id="department_id">
                    <option value="">Semua SKPD</option>
                    @foreach($skpdList ?? [] as $skpd)
                        <option value="{{ $skpd->id }}" {{ request('department_id') == $skpd->id ? 'selected' : '' }}>
                            {{ $skpd->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:.8rem;">Kategori</label>
                <select class="form-select" name="category_id" id="category_id">
                    <option value="">Semua Kategori</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
            </div>
            <div class="col-md-2 d-grid">
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
            </div>
        </form>
    </div>

    {{-- Summary Stat Cards --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['val' => $summary['total_tiket']??0,        'lbl' => 'Total Tiket',        'sub' => 'periode ini',                        'color' => '#3b82f6', 'icon' => 'bi-ticket-perforated'],
                ['val' => $summary['tiket_selesai']??0,      'lbl' => 'Tiket Selesai',      'sub' => ($summary['persentase_selesai']??0).'% dari total', 'color' => '#22c55e', 'icon' => 'bi-check-circle'],
                ['val' => ($summary['rata_waktu']??0).' hari','lbl' => 'Rata-rata Selesai', 'sub' => 'waktu penyelesaian',                 'color' => '#8b5cf6', 'icon' => 'bi-stopwatch'],
                ['val' => $summary['backlog']??0,             'lbl' => 'Backlog',            'sub' => 'baru + sedang diproses',             'color' => '#f59e0b', 'icon' => 'bi-hourglass-split'],
            ];
        @endphp
        @foreach($cards as $c)
        <div class="col-6 col-lg-3">
            <div class="report-stat">
                <div class="mb-2" style="font-size:1.4rem;color:{{ $c['color'] }};"><i class="bi {{ $c['icon'] }}"></i></div>
                <div class="r-val" style="color:{{ $c['color'] }}">{{ $c['val'] }}</div>
                <div class="r-lbl">{{ $c['lbl'] }}</div>
                <div class="r-sub">{{ $c['sub'] }}</div>
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
                    <small style="color:var(--text-muted);">{{ $dari->translatedFormat('d M Y') }} – {{ $sampai->translatedFormat('d M Y') }}</small>
                </div>
                <div class="chart-body">
                    <canvas id="chartTrend" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-card h-100">
                <div class="chart-header">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-pie-chart text-warning me-2"></i>Distribusi Status</h6>
                </div>
                <div class="chart-body d-flex flex-column align-items-center" style="min-height:280px;justify-content:center;">
                    <canvas id="chartStatus" style="max-width:180px;max-height:180px;"></canvas>
                    <div class="mt-3 w-100" id="statusLegend"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Tables --}}
    <div class="row g-4">
        {{-- Kinerja per SKPD --}}
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-building text-info me-2"></i>Kinerja per SKPD</h6>
                    <span class="badge bg-secondary rounded-pill" style="font-size:.7rem;">{{ count($skpdReport??[]) }} SKPD</span>
                </div>
                <div class="p-0">
                    @forelse($skpdReport ?? [] as $r)
                        @php $fc = $r['persentase']>=80 ? 'perf-ok' : ($r['persentase']>=60 ? 'perf-warn' : 'perf-danger'); @endphp
                        <div class="perf-row">
                            <div style="width:28px;height:28px;border-radius:6px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($r['nama'],0,1)) }}
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-semibold text-truncate" style="color:var(--text-primary);max-width:55%">{{ $r['nama'] }}</small>
                                    <div class="d-flex gap-1 flex-shrink-0">
                                        <span class="badge bg-secondary rounded-pill" style="font-size:.65rem;">{{ $r['total'] }}</span>
                                        <span class="badge {{ $r['persentase']>=80?'bg-success':($r['persentase']>=60?'bg-warning text-dark':'bg-danger') }} rounded-pill" style="font-size:.65rem;">{{ $r['persentase'] }}%</span>
                                    </div>
                                </div>
                                <div class="perf-bar"><div class="{{ $fc }}" style="width:{{ $r['persentase'] }}%;height:100%;border-radius:50px;"></div></div>
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

        {{-- Jenis Pekerjaan --}}
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-tags text-warning me-2"></i>Jenis Pekerjaan Terbanyak</h6>
                    <span class="badge bg-secondary rounded-pill" style="font-size:.7rem;">Top {{ count($jenisReport??[]) }}</span>
                </div>
                <div class="chart-body">
                    <canvas id="chartJenis" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark    = () => document.documentElement.getAttribute('data-theme') === 'dark';
    const gridColor = () => isDark() ? 'rgba(255,255,255,.08)' : 'rgba(0,0,0,.06)';
    const lblColor  = () => isDark() ? '#94a3b8' : '#64748b';

    Chart.defaults.font.family = "'Inter', sans-serif";

    const charts = [];

    /* ── Tren Bulanan ────────────────────────────────── */
    @php
        // Hitung tren 6 bulan terakhir menggunakan $dari dan $sampai dari controller
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $trendData[] = [
                'label'   => $m->translatedFormat('M Y'),
                'masuk'   => \App\Models\Ticket::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count(),
                'selesai' => \App\Models\Ticket::where('status','selesai')->whereYear('closed_at', $m->year)->whereMonth('closed_at', $m->month)->count(),
            ];
        }
    @endphp
    (function () {
        const td = @json($trendData);
        const c = new Chart(document.getElementById('chartTrend'), {
            type: 'bar',
            data: {
                labels: td.map(m => m.label),
                datasets: [
                    { label:'Masuk',   data:td.map(m=>m.masuk),   backgroundColor:'rgba(59,130,246,.75)',  borderRadius:5 },
                    { label:'Selesai', data:td.map(m=>m.selesai), backgroundColor:'rgba(34,197,94,.75)',   borderRadius:5 },
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
        charts.push(c);
    })();

    /* ── Status Donut ────────────────────────────────── */
    (function () {
        const sd = {
            labels: ['Baru','Diproses','Selesai','Ditolak','Dibatalkan'],
            data:   [
                {{ $statusDistribution['baru']       ?? 0 }},
                {{ $statusDistribution['diproses']   ?? 0 }},
                {{ $statusDistribution['selesai']    ?? 0 }},
                {{ $statusDistribution['ditolak']    ?? 0 }},
                {{ $statusDistribution['dibatalkan'] ?? 0 }},
            ],
            colors: ['#eab308','#3b82f6','#22c55e','#ef4444','#94a3b8'],
        };

        const c = new Chart(document.getElementById('chartStatus'), {
            type:'doughnut',
            data:{ labels:sd.labels, datasets:[{ data:sd.data, backgroundColor:sd.colors, borderWidth:2, borderColor:isDark()?'#1e293b':'#fff' }] },
            options:{ responsive:true, cutout:'68%', plugins:{ legend:{display:false} } }
        });
        charts.push(c);

        const leg = document.getElementById('statusLegend');
        if (leg) {
            leg.innerHTML = sd.labels.map((l,i)=>
                `<span class="d-inline-flex align-items-center me-2 mb-1 small" style="color:var(--text-secondary)">
                    <span style="width:10px;height:10px;border-radius:3px;background:${sd.colors[i]};display:inline-block;margin-right:5px"></span>
                    ${l} <strong class="ms-1" style="color:var(--text-primary)">${sd.data[i]}</strong>
                </span>`
            ).join('');
        }
    })();

    /* ── Jenis Pekerjaan Horizontal Bar ─────────────── */
    @php
        $jenisLabels = array_column($jenisReport ?? [], 'nama');
        $jenisValues = array_column($jenisReport ?? [], 'jumlah');
    @endphp
    @if(count($jenisReport ?? []) > 0)
    (function () {
        const labels = @json($jenisLabels);
        const data   = @json($jenisValues);

        const c = new Chart(document.getElementById('chartJenis'), {
            type:'bar',
            data:{
                labels: labels.map(l=>l.length>24?l.substring(0,22)+'…':l),
                datasets:[{ label:'Jumlah Tiket', data, backgroundColor:'rgba(139,92,246,.75)', borderRadius:5 }]
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
        charts.push(c);
    })();
    @endif

    /* ── Re-render on theme toggle ───────────────────── */
    document.getElementById('themeToggle')?.addEventListener('click', () => {
        setTimeout(() => charts.forEach(c => c.update()), 300);
    });
});
</script>
@endpush
