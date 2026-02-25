@extends('layouts.e-ticket')

@section('title', 'Daftar Tiket - Sistem Ticketing Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Daftar Tiket</li>
@endsection

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-list-check me-2"></i>
                Daftar Tiket Pekerjaan
            </h2>
            <p class="mb-0">Kelola dan pantau semua tiket masuk dari SKPD</p>
        </div>
        <div>
            <span class="badge bg-light text-dark fs-6">
                Total: {{ $tickets->total() }} tiket
            </span>
        </div>
    </div>
@endsection

@section('content')
    <!-- Filter & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('tiket.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" id="search" placeholder="Cari no/judul..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="">Semua Status</option>
                        <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="priority_id" class="form-label">Prioritas</label>
                    <select class="form-select" name="priority_id" id="priority_id">
                        <option value="">Semua Prioritas</option>
                        @foreach ($priorities ?? [] as $p)
                            <option value="{{ $p->id }}" {{ request('priority_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="department_id" class="form-label">SKPD</label>
                    <select class="form-select" name="department_id" id="department_id">
                        <option value="">Semua SKPD</option>
                        @foreach ($skpdList ?? [] as $skpd)
                            <option value="{{ $skpd->id }}" {{ request('department_id') == $skpd->id ? 'selected' : '' }}>
                                {{ $skpd->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('tiket.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-warning">
                <div class="card-body text-center">
                    <h4 class="text-warning">{{ $stats['baru'] ?? 0 }}</h4>
                    <small class="text-muted">Tiket Baru</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-info">
                <div class="card-body text-center">
                    <h4 class="text-info">{{ $stats['diproses'] ?? 0 }}</h4>
                    <small class="text-muted">Sedang Diproses</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-success">
                <div class="card-body text-center">
                    <h4 class="text-success">{{ $stats['selesai'] ?? 0 }}</h4>
                    <small class="text-muted">Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-primary">
                <div class="card-body text-center">
                    <h4 class="text-primary">{{ $stats['total'] ?? 0 }}</h4>
                    <small class="text-muted">Total Tiket</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tiket</h5>
            <div class="d-flex gap-2">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="view-mode" id="list-view" checked>
                    <label class="btn btn-outline-primary btn-sm" for="list-view">
                        <i class="bi bi-list"></i>
                    </label>
                    <input type="radio" class="btn-check" name="view-mode" id="card-view">
                    <label class="btn btn-outline-primary btn-sm" for="card-view">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if ($tickets->count() > 0)
                <!-- List View -->
                <div id="list-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">No. Tiket</th>
                                    <th width="25%">Judul Pekerjaan</th>
                                    <th width="15%">SKPD</th>
                                    <th width="10%">Prioritas</th>
                                    <th width="10%">Status</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="13%">Petugas</th>
                                    <th width="7%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr class="ticket-row" style="cursor:pointer"
                                        onclick="window.location='{{ route('tiket.show', $ticket->id) }}'">
                                        <td>
                                            <strong class="text-primary">{{ $ticket->number }}</strong>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $ticket->title }}</div>
                                            <small class="text-muted">{{ Str::limit($ticket->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">{{ substr($ticket->department->name ?? 'T', 0, 1) }}</div>
                                                <small>{{ $ticket->department->name ?? '-' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="priority-{{ strtolower($ticket->priority->name ?? 'rendah') }}">
                                                <i class="bi bi-flag-fill"></i>
                                                {{ ucfirst($ticket->priority->name ?? 'Rendah') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ strtolower($ticket->status) }}">
                                                {{ ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $ticket->created_at->format('d/m/Y') }}</div>
                                            @if ($ticket->target_date)
                                                <small class="text-muted">Target: {{ \Carbon\Carbon::parse($ticket->target_date)->format('d/m/Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($ticket->assignee)
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar me-2">{{ substr($ticket->assignee->name, 0, 1) }}</div>
                                                    <small>{{ $ticket->assignee->name }}</small>
                                                </div>
                                            @else
                                                <small class="text-muted">Belum ditugaskan</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown" onclick="event.stopPropagation()">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('tiket.show', $ticket->id) }}">
                                                        <i class="bi bi-eye me-2"></i>Lihat Detail
                                                    </a></li>
                                                    @if (in_array($ticket->status, ['baru', 'diproses']) && (auth()->user()->isAdmin() || auth()->user()->isPetugas()))
                                                        <li><hr class="dropdown-divider"></li>
                                                        @if ($ticket->status === 'baru')
                                                            <li>
                                                                <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status" value="diproses">
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="bi bi-play-circle me-2"></i>Mulai Kerjakan
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if ($ticket->status === 'diproses')
                                                            <li>
                                                                <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status" value="selesai">
                                                                    <button type="submit" class="dropdown-item text-success">
                                                                        <i class="bi bi-check-circle me-2"></i>Selesaikan
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                                                @csrf @method('PUT')
                                                                <input type="hidden" name="status" value="ditolak">
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Yakin tolak tiket ini?')">
                                                                    <i class="bi bi-x-circle me-2"></i>Tolak
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card View -->
                <div id="card-container" class="d-none">
                    <div class="row p-3">
                        @foreach ($tickets as $ticket)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <a href="{{ route('tiket.show', $ticket->id) }}" class="text-decoration-none">
                                <div class="card ticket-card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                                        <small class="fw-bold text-primary">{{ $ticket->number }}</small>
                                        <span class="status-badge status-{{ strtolower($ticket->status) }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title text-dark">{{ Str::limit($ticket->title, 50) }}</h6>
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($ticket->description, 70) }}</p>

                                        <div class="d-flex align-items-center mb-2">
                                            <div class="user-avatar me-2">{{ substr($ticket->department->name ?? 'T', 0, 1) }}</div>
                                            <small class="text-muted">{{ $ticket->department->name ?? '-' }}</small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="priority-{{ strtolower($ticket->priority->name ?? 'rendah') }}">
                                                <i class="bi bi-flag-fill"></i> {{ ucfirst($ticket->priority->name ?? 'Rendah') }}
                                            </span>
                                            <small class="text-muted">{{ $ticket->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="p-3">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada tiket ditemukan</h5>
                    <p class="text-muted">Coba ubah filter atau kriteria pencarian Anda</p>
                </div>
            @endif
        </div>
    </div>

    @include('kominfo.partials.assign-modal')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // View mode toggle
            const listView = document.getElementById('list-view');
            const cardView = document.getElementById('card-view');
            const listContainer = document.getElementById('list-container');
            const cardContainer = document.getElementById('card-container');

            listView.addEventListener('change', function() {
                if (this.checked) {
                    listContainer.classList.remove('d-none');
                    cardContainer.classList.add('d-none');
                }
            });

            cardView.addEventListener('change', function() {
                if (this.checked) {
                    listContainer.classList.add('d-none');
                    cardContainer.classList.remove('d-none');
                }
            });

            // Auto-submit form on filter change
            ['status', 'priority_id', 'department_id'].forEach(function(id) {
                const el = document.getElementById(id);
                if (el) el.addEventListener('change', function() { this.form.submit(); });
            });
        });
    </script>
@endpush