@extends('layouts.e-ticket')

@section('title', 'Laporan Admin - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Laporan Admin</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-bar-chart me-2"></i>
        Laporan Admin
    </h2>
    <p class="mb-0">Laporan komprehensif kinerja sistem</p>
@endsection

@section('content')
    <!-- Period Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Periode</label>
                    <select class="form-select">
                        <option selected>Bulan Ini (Januari 2026)</option>
                        <option>Bulan Lalu</option>
                        <option>3 Bulan Terakhir</option>
                        <option>Tahun Ini</option>
                        <option>Custom</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Refresh
                    </button>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-success w-100">
                        <i class="bi bi-download me-2"></i>
                        Export PDF
                    </button>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-outline-secondary w-100">
                        <i class="bi bi-printer me-2"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-primary">
                <div class="card-body stats-card">
                    <div class="stats-number text-primary">{{ $reportData['total_tiket'] }}</div>
                    <div class="stats-label">Total Tiket</div>
                    <small class="text-muted">{{ $reportData['periode'] }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-success">
                <div class="card-body stats-card">
                    <div class="stats-number text-success">{{ $reportData['tiket_selesai'] }}</div>
                    <div class="stats-label">Tiket Selesai</div>
                    <small class="text-muted">{{ $reportData['persentase_selesai'] }}%</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-info">
                <div class="card-body stats-card">
                    <div class="stats-number text-info">{{ $reportData['rata_waktu_penyelesaian'] }}</div>
                    <div class="stats-label">Rata-rata Waktu</div>
                    <small class="text-muted">hari penyelesaian</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-warning">
                <div class="card-body stats-card">
                    <div class="stats-number text-warning">{{ $reportData['kepuasan_pengguna'] }}/5</div>
                    <div class="stats-label">Kepuasan Pengguna</div>
                    <small class="text-muted">rating</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="row mb-4">
        <!-- Top SKPD -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-building text-primary me-2"></i>
                        SKPD dengan Tiket Terbanyak
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>SKPD</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Selesai</th>
                                    <th class="text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topSkpd as $item)
                                    <tr>
                                        <td><strong>{{ $item['skpd'] }}</strong></td>
                                        <td class="text-center">{{ $item['tiket'] }}</td>
                                        <td class="text-center">{{ $item['selesai'] }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-success">{{ round(($item['selesai'] / $item['tiket']) * 100) }}%</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Jenis Pekerjaan -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-tags text-success me-2"></i>
                        Jenis Pekerjaan Terbanyak
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Pekerjaan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalJenis = array_sum(array_column($topJenis, 'tiket'));
                                @endphp
                                @foreach ($topJenis as $item)
                                    <tr>
                                        <td><strong>{{ $item['jenis'] }}</strong></td>
                                        <td class="text-center">{{ $item['tiket'] }}</td>
                                        <td class="text-end">
                                            <div class="progress" style="height: 15px;">
                                                <div class="progress-bar"
                                                    style="width: {{ ($item['tiket'] / $totalJenis) * 100 }}%">
                                                    {{ round(($item['tiket'] / $totalJenis) * 100) }}%
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

    <!-- Status Distribution -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart text-info me-2"></i>
                        Distribusi Status Tiket
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-5 text-success mb-2">{{ $reportData['tiket_selesai'] }}</div>
                                <p class="text-muted">Selesai</p>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-success"
                                        style="width: {{ ($reportData['tiket_selesai'] / $reportData['total_tiket']) * 100 }}%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-5 text-info mb-2">{{ $reportData['tiket_diproses'] }}</div>
                                <p class="text-muted">Diproses</p>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-info"
                                        style="width: {{ ($reportData['tiket_diproses'] / $reportData['total_tiket']) * 100 }}%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-5 text-warning mb-2">{{ $reportData['tiket_baru'] }}</div>
                                <p class="text-muted">Baru</p>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-warning"
                                        style="width: {{ ($reportData['tiket_baru'] / $reportData['total_tiket']) * 100 }}%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="display-5 text-danger mb-2">0</div>
                                <p class="text-muted">Ditolak</p>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-danger" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="alert alert-info" role="alert">
        <h5 class="alert-heading">
            <i class="bi bi-lightbulb me-2"></i>
            Rekomendasi
        </h5>
        <ul class="mb-0">
            <li>Tingkatkan response time untuk tiket urgent - target &lt; 2 jam</li>
            <li>Tingkatkan jumlah petugas untuk menangani beban kerja yang meningkat</li>
            <li>Buat SOP khusus untuk jenis pekerjaan dengan tingkat penolakan tinggi</li>
            <li>Lakukan evaluasi kepuasan pengguna secara berkala</li>
        </ul>
    </div>
@endsection
