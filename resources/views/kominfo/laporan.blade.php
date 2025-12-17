@extends('layouts.e-ticket')

@section('title', 'Laporan - Sistem Ticketing Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-bar-chart me-2"></i>
        Laporan Pekerjaan Kominfo
    </h2>
    <p class="mb-0">Monitoring dan evaluasi beban kerja Dinas Kominfo Kota Bukittinggi</p>
@endsection

@section('content')
    <!-- Filter Periode -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label for="periode" class="form-label">Periode</label>
                    <select class="form-select" name="periode" id="periode">
                        <option value="bulan_ini" {{ request('periode', 'bulan_ini') === 'bulan_ini' ? 'selected' : '' }}>
                            Bulan Ini</option>
                        <option value="3_bulan" {{ request('periode') === '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir
                        </option>
                        <option value="tahun_ini" {{ request('periode') === 'tahun_ini' ? 'selected' : '' }}>Tahun Ini
                        </option>
                        <option value="custom" {{ request('periode') === 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <div class="col-md-2" id="start-date"
                    style="display: {{ request('periode') === 'custom' ? 'block' : 'none' }}">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2" id="end-date"
                    style="display: {{ request('periode') === 'custom' ? 'block' : 'none' }}">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="skpd_filter" class="form-label">SKPD</label>
                    <select class="form-select" name="skpd_filter">
                        <option value="">Semua SKPD</option>
                        @foreach ($skpdList ?? [] as $skpd)
                            <option value="{{ $skpd['id'] }}"
                                {{ request('skpd_filter') == $skpd['id'] ? 'selected' : '' }}>
                                {{ $skpd['nama'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="button" class="btn btn-success" onclick="exportReport()">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card card-primary">
                <div class="card-body stats-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stats-number text-primary">{{ $summary['total_tiket'] ?? 0 }}</div>
                            <div class="stats-label">Total Tiket</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-ticket display-4 text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card card-success">
                <div class="card-body stats-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stats-number text-success">{{ $summary['tiket_selesai'] ?? 0 }}</div>
                            <div class="stats-label">Tiket Selesai</div>
                            <small class="text-muted">{{ $summary['persentase_selesai'] ?? 0 }}%</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle display-4 text-success opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card card-info">
                <div class="card-body stats-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stats-number text-info">{{ $summary['rata_waktu'] ?? 0 }}</div>
                            <div class="stats-label">Rata-rata Waktu</div>
                            <small class="text-muted">hari penyelesaian</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock display-4 text-info opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card card-warning">
                <div class="card-body stats-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stats-number text-warning">{{ $summary['beban_kerja'] ?? 0 }}</div>
                            <div class="stats-label">Beban Kerja</div>
                            <small class="text-muted">tiket/bulan</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up display-4 text-warning opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Trend Chart -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        Trend Pekerjaan Bulanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <!-- Simulate Chart -->
                        <div class="d-flex align-items-end justify-content-between h-100 p-3">
                            @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'] as $month)
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-primary"
                                        style="width: 30px; height: {{ rand(50, 200) }}px; margin-bottom: 10px; border-radius: 4px;">
                                    </div>
                                    <small class="text-muted">{{ $month }}</small>
                                    <strong>{{ rand(10, 50) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart text-success me-2"></i>
                        Distribusi Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <span class="text-white fw-bold">{{ $statusDistribution['selesai'] ?? 0 }}</span>
                                </div>
                                <div class="mt-2"><small>Selesai</small></div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <span class="text-white fw-bold">{{ $statusDistribution['diproses'] ?? 0 }}</span>
                                </div>
                                <div class="mt-2"><small>Diproses</small></div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <span class="text-white fw-bold">{{ $statusDistribution['baru'] ?? 0 }}</span>
                                </div>
                                <div class="mt-2"><small>Baru</small></div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <span class="text-white fw-bold">{{ $statusDistribution['ditolak'] ?? 0 }}</span>
                                </div>
                                <div class="mt-2"><small>Ditolak</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="row">
        <!-- SKPD Performance -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-building text-info me-2"></i>
                        Laporan Per SKPD
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>SKPD</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Selesai</th>
                                    <th class="text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($skpdReport ?? [] as $report)
                                    <tr>
                                        <td>{{ $report['nama'] }}</td>
                                        <td class="text-center">{{ $report['total'] }}</td>
                                        <td class="text-center">{{ $report['selesai'] }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-{{ $report['persentase'] >= 80 ? 'success' : ($report['persentase'] >= 60 ? 'warning' : 'danger') }}">
                                                {{ $report['persentase'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jenis Pekerjaan -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-tools text-warning me-2"></i>
                        Jenis Pekerjaan Terbanyak
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Jenis Pekerjaan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jenisReport ?? [] as $report)
                                    <tr>
                                        <td>{{ $report['nama'] }}</td>
                                        <td class="text-center">{{ $report['jumlah'] }}</td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 15px;">
                                                <div class="progress-bar" style="width: {{ $report['persentase'] }}%">
                                                    {{ $report['persentase'] }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-speedometer2 text-primary me-2"></i>
                        Indikator Kinerja Kominfo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="text-center">
                                <div class="display-1 mb-3">
                                    <div class="progress mx-auto"
                                        style="width: 100px; height: 100px; border-radius: 50%;">
                                        <div class="progress-bar progress-bar-striped"
                                            style="width: {{ $kpi['response_time'] ?? 75 }}%"></div>
                                    </div>
                                </div>
                                <h6>Response Time</h6>
                                <p class="text-muted mb-0">Rata-rata {{ $kpi['avg_response'] ?? 2 }} jam</p>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="text-center">
                                <div class="display-4 text-success mb-3">{{ $kpi['completion_rate'] ?? 85 }}%</div>
                                <h6>Completion Rate</h6>
                                <p class="text-muted mb-0">Tingkat penyelesaian</p>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="text-center">
                                <div class="display-4 text-info mb-3">{{ $kpi['satisfaction'] ?? 4.2 }}/5</div>
                                <h6>Satisfaction Score</h6>
                                <p class="text-muted mb-0">Kepuasan SKPD</p>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="text-center">
                                <div class="display-4 text-warning mb-3">{{ $kpi['workload'] ?? 15 }}</div>
                                <h6>Workload</h6>
                                <p class="text-muted mb-0">Tiket per petugas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('periode').addEventListener('change', function() {
            const customFields = ['start-date', 'end-date'];
            const isCustom = this.value === 'custom';

            customFields.forEach(fieldId => {
                document.getElementById(fieldId).style.display = isCustom ? 'block' : 'none';
            });

            if (!isCustom) {
                this.form.submit();
            }
        });

        function exportReport() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'true');

            // Simulate export
            showToast('Laporan sedang diexport...', 'info');

            setTimeout(() => {
                showToast('Laporan berhasil diexport!', 'success');
            }, 2000);
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} alert-dismissible position-fixed top-0 end-0 m-3`;
            toast.style.zIndex = '9999';
            toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endpush
