@extends('layouts.e-ticket')

@section('title', 'History Assignment - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ticket.management.index') }}">Manajemen Tiket</a></li>
    <li class="breadcrumb-item active">History Assignment</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-clock-history me-2"></i>
        History Assignment Tiket
    </h2>
    <p class="mb-0">Riwayat penempatan tiket ke petugas dengan statistik kinerja</p>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small"><i class="bi bi-stack me-2"></i>Total Assignment</p>
                        <h3 class="mb-0">{{ $statistics['total_assignment'] }}</h3>
                    </div>
                    <i class="bi bi-stack text-primary" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small"><i class="bi bi-robot me-2"></i>Otomatis</p>
                        <h3 class="mb-0">{{ $statistics['auto_assignment'] }}%</h3>
                    </div>
                    <i class="bi bi-robot text-success" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small"><i class="bi bi-hand-index me-2"></i>Manual</p>
                        <h3 class="mb-0">{{ $statistics['manual_assignment'] }}%</h3>
                    </div>
                    <i class="bi bi-hand-index text-warning" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small"><i class="bi bi-hourglass-end me-2"></i>Rata-rata Waktu</p>
                        <h3 class="mb-0">{{ $statistics['avg_time'] }}h</h3>
                    </div>
                    <i class="bi bi-hourglass-end text-info" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribution Chart Section -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>
                    Perbandingan Metode Assignment
                </h5>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px;">
                    <canvas id="assignmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Assignment per Petugas
                </h5>
            </div>
            <div class="card-body">
                @foreach($assignmentByPetugas as $petugas)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">{{ $petugas['nama'] }}</h6>
                        <span class="badge bg-primary">{{ $petugas['total'] }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ ($petugas['otomatis'] / $petugas['total']) * 100 }}%;" title="Otomatis: {{ $petugas['otomatis'] }}"></div>
                        <div class="progress-bar bg-warning" style="width: {{ ($petugas['manual'] / $petugas['total']) * 100 }}%;" title="Manual: {{ $petugas['manual'] }}"></div>
                    </div>
                    <small class="text-muted">
                        Otomatis: {{ $petugas['otomatis'] }} | Manual: {{ $petugas['manual'] }}
                    </small>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4 border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label small"><strong>Filter Metode</strong></label>
                <select class="form-select form-select-sm" id="methodFilter">
                    <option value="">Semua Metode</option>
                    <option value="otomatis">Otomatis</option>
                    <option value="manual">Manual</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small"><strong>Filter Petugas</strong></label>
                <select class="form-select form-select-sm" id="petugasFilter">
                    <option value="">Semua Petugas</option>
                    @foreach($petugasList as $petugas)
                    <option value="{{ $petugas['id'] }}">{{ $petugas['nama'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small"><strong>Filter Tanggal</strong></label>
                <input type="month" class="form-control form-control-sm" id="dateFilter">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i>
                    Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- History Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-list-check me-2"></i>
            Riwayat Assignment ({{ count($assignmentHistory) }} records)
        </h5>
        <button class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-download me-1"></i>
            Export CSV
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="15%">Tiket ID</th>
                    <th width="20%">Judul</th>
                    <th width="15%">Petugas</th>
                    <th width="12%">Metode</th>
                    <th width="12%">Tanggal</th>
                    <th width="13%">Waktu (Jam)</th>
                    <th width="13%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignmentHistory as $record)
                <tr>
                    <td>
                        <strong class="text-primary">{{ $record['id'] }}</strong>
                    </td>
                    <td>
                        <a href="javascript:void(0);" class="text-decoration-none">
                            {{ Str::limit($record['judul'], 30) }}
                        </a>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $record['petugas'] }}</span>
                    </td>
                    <td>
                        @if($record['metode'] === 'Otomatis')
                            <span class="badge bg-success">
                                <i class="bi bi-robot me-1"></i>{{ $record['metode'] }}
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="bi bi-hand-index me-1"></i>{{ $record['metode'] }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <small>{{ $record['tanggal']->format('d M Y, H:i') }}</small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $record['waktu'] <= 2 ? 'success' : ($record['waktu'] <= 4 ? 'info' : 'warning') }}">
                            {{ $record['waktu'] }}h
                        </span>
                    </td>
                    <td>
                        @if($record['status'] === 'Selesai')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>{{ $record['status'] }}
                            </span>
                        @elseif($record['status'] === 'Berlangsung')
                            <span class="badge bg-info">
                                <i class="bi bi-hourglass-bottom me-1"></i>{{ $record['status'] }}
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle me-1"></i>{{ $record['status'] }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                        <p class="text-muted mt-2">Belum ada riwayat assignment</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ count($assignmentHistory) }} dari {{ $statistics['total_assignment'] }} records</small>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Informasi Tiket</h6>
                        <p class="mb-1"><strong>ID:</strong> TKT-2024-0001</p>
                        <p class="mb-1"><strong>Judul:</strong> Perbaikan Portal Kominfo</p>
                        <p class="mb-1"><strong>Jenis:</strong> Perbaikan Portal</p>
                        <p class="mb-1"><strong>SKPD:</strong> Dinas Komunikasi</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Assignment</h6>
                        <p class="mb-1"><strong>Petugas:</strong> Budi Santoso</p>
                        <p class="mb-1"><strong>Metode:</strong> Otomatis</p>
                        <p class="mb-1"><strong>Tanggal:</strong> 15 Jan 2024, 10:30</p>
                        <p class="mb-1"><strong>Waktu Penyelesaian:</strong> 2 jam 15 menit</p>
                    </div>
                </div>
                <div>
                    <h6>Deskripsi Pekerjaan</h6>
                    <p class="small">Perbaikan pada bagian login portal kominfo yang mengalami masalah autentikasi. Sudah dilakukan pengecekan dan perbaikan pada database dan konfigurasi server.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Chart.js - Assignment Distribution
const ctx = document.getElementById('assignmentChart').getContext('2d');
const assignmentChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Otomatis', 'Manual'],
        datasets: [{
            data: [{{ $statistics['auto_assignment'] }}, {{ $statistics['manual_assignment'] }}],
            backgroundColor: ['#28a745', '#ffc107'],
            borderColor: ['#fff', '#fff'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Filter buttons
document.querySelectorAll('.table tbody tr').forEach(row => {
    row.addEventListener('click', function() {
        // Show detail modal
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    });
});
</script>
@endpush
