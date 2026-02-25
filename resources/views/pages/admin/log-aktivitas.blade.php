@extends('layouts.e-ticket')

@section('title', 'Log Aktivitas - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Log Aktivitas</li>
@endsection

@section('page-header')
    <h2 class="mb-1"><i class="bi bi-clock-history me-2"></i>Log Aktivitas Sistem</h2>
    <p class="mb-0">Pantau seluruh aktivitas pengguna dalam sistem</p>
@endsection

@section('content')
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.log-aktivitas') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="dari" value="{{ request('dari') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="sampai" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Pengguna</label>
                    <select class="form-select" name="user_id">
                        <option value="">Semua Pengguna</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipe Entitas</label>
                    <select class="form-select" name="entity_type">
                        <option value="">Semua Tipe</option>
                        @foreach($entityTypes as $et)
                            <option value="{{ $et }}" {{ request('entity_type') === $et ? 'selected' : '' }}>
                                {{ $et }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Aksi</label>
                    <select class="form-select" name="action">
                        <option value="">Semua Aksi</option>
                        <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Dibuat</option>
                        <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Diperbarui</option>
                        <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Dihapus</option>
                        <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.log-aktivitas') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Aktivitas</h5>
            <span class="badge bg-secondary">{{ $logs->total() }} entri</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Tipe</th>
                            <th>Nama Entitas</th>
                            <th>IP Address</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>
                                    <small>{{ $log->created_at->format('d M Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                            {{ substr($log->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <small>{{ $log->user->name ?? 'System' }}</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $colorMap = ['created' => 'success', 'updated' => 'warning', 'deleted' => 'danger', 'login' => 'info', 'logout' => 'secondary'];
                                        $badgeColor = $colorMap[$log->action] ?? 'primary';
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}">{{ ucfirst($log->action) }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $log->entity_type }}</small>
                                </td>
                                <td>
                                    <small><strong>{{ $log->entity_name }}</strong></small>
                                </td>
                                <td>
                                    <small class="font-monospace">{{ $log->ip_address ?? '-' }}</small>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#logDetail{{ $log->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="logDetail{{ $log->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-clock-history me-2"></i>Detail Aktivitas
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-4">Waktu</dt>
                                                <dd class="col-sm-8">{{ $log->created_at->format('d M Y H:i:s') }}</dd>

                                                <dt class="col-sm-4">Pengguna</dt>
                                                <dd class="col-sm-8">{{ $log->user->name ?? 'System' }}</dd>

                                                <dt class="col-sm-4">Aksi</dt>
                                                <dd class="col-sm-8">
                                                    <span class="badge bg-{{ $badgeColor }}">{{ ucfirst($log->action) }}</span>
                                                </dd>

                                                <dt class="col-sm-4">Tipe Entitas</dt>
                                                <dd class="col-sm-8">{{ $log->entity_type }}</dd>

                                                <dt class="col-sm-4">Nama Entitas</dt>
                                                <dd class="col-sm-8">{{ $log->entity_name }}</dd>

                                                <dt class="col-sm-4">Deskripsi</dt>
                                                <dd class="col-sm-8">{{ $log->description }}</dd>

                                                <dt class="col-sm-4">IP Address</dt>
                                                <dd class="col-sm-8 font-monospace">{{ $log->ip_address ?? '-' }}</dd>
                                            </dl>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                    Tidak ada log aktivitas ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
@endsection