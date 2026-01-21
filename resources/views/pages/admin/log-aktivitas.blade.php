@extends('layouts.e-ticket')

@section('title', 'Log Aktivitas - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Log Aktivitas</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-clock-history me-2"></i>
        Log Aktivitas Sistem
    </h2>
    <p class="mb-0">Pantau seluruh aktivitas pengguna dalam sistem</p>
@endsection

@section('content')
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipe Aksi</label>
                    <select class="form-select">
                        <option selected>Semua Aksi</option>
                        @foreach ($filters['actions'] as $action)
                            <option value="{{ $action }}">{{ ucwords(str_replace('_', ' ', strtolower($action))) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option selected>Semua Status</option>
                        <option value="success">Berhasil</option>
                        <option value="danger">Gagal</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary">
                        <i class="bi bi-funnel me-2"></i>
                        Terapkan Filter
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Daftar Aktivitas
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th>IP Address</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td>
                                    <small>{{ $log['waktu']->format('d M Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 30px; height: 30px;">
                                            {{ substr($log['user'], 0, 1) }}</div>
                                        <small>{{ $log['user'] }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $log['action_label'] }}</span>
                                </td>
                                <td>
                                    <small>
                                        <strong>{{ $log['target'] }}</strong><br>
                                        <span class="text-muted">{{ $log['target_label'] }}</span>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $log['status'] === 'success' ? 'success' : 'danger' }}">
                                        <i
                                            class="bi {{ $log['status'] === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle' }} me-1"></i>
                                        {{ ucfirst($log['status']) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="font-monospace">{{ $log['ip_address'] }}</small>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#logDetail{{ $log['id'] }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="logDetail{{ $log['id'] }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Aktivitas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <dl class="row">
                                                <dt class="col-sm-4">Waktu</dt>
                                                <dd class="col-sm-8">{{ $log['waktu']->format('d M Y H:i:s') }}</dd>

                                                <dt class="col-sm-4">Pengguna</dt>
                                                <dd class="col-sm-8">{{ $log['user'] }}</dd>

                                                <dt class="col-sm-4">Aksi</dt>
                                                <dd class="col-sm-8">{{ $log['action_label'] }}</dd>

                                                <dt class="col-sm-4">Target</dt>
                                                <dd class="col-sm-8">{{ $log['target_label'] }}</dd>

                                                <dt class="col-sm-4">Status</dt>
                                                <dd class="col-sm-8">
                                                    <span
                                                        class="badge bg-{{ $log['status'] === 'success' ? 'success' : 'danger' }}">{{ ucfirst($log['status']) }}</span>
                                                </dd>

                                                <dt class="col-sm-4">IP Address</dt>
                                                <dd class="col-sm-8">{{ $log['ip_address'] }}</dd>
                                            </dl>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Sebelumnya</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Selanjutnya</a></li>
                </ul>
            </nav>
        </div>
    </div>
@endsection
