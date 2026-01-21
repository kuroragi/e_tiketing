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
                Total: {{ count($tickets ?? []) }} tiket
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
                        <input type="text" class="form-control" name="search" id="search" placeholder="Cari tiket..."
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
                    <label for="prioritas" class="form-label">Prioritas</label>
                    <select class="form-select" name="prioritas" id="prioritas">
                        <option value="">Semua Prioritas</option>
                        <option value="tinggi" {{ request('prioritas') === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="sedang" {{ request('prioritas') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="rendah" {{ request('prioritas') === 'rendah' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="skpd" class="form-label">SKPD</label>
                    <select class="form-select" name="skpd" id="skpd">
                        <option value="">Semua SKPD</option>
                        @foreach ($skpdList ?? [] as $skpd)
                            <option value="{{ $skpd['id'] }}" {{ request('skpd') == $skpd['id'] ? 'selected' : '' }}>
                                {{ $skpd['nama'] }}
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
            <div class="card card-danger">
                <div class="card-body text-center">
                    <h4 class="text-danger">{{ $stats['overdue'] ?? 0 }}</h4>
                    <small class="text-muted">Terlambat</small>
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
            @if (isset($tickets) && count($tickets) > 0)
                <!-- List View -->
                <div id="list-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Judul Pekerjaan</th>
                                    <th width="15%">SKPD</th>
                                    <th width="10%">Prioritas</th>
                                    <th width="10%">Status</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="13%">Petugas</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $index => $ticket)
                                    <tr class="ticket-row" onclick="showTicketDetail({{ $ticket['id'] }})">
                                        <td>
                                            <strong>{{ str_pad($ticket['id'], 4, '0', STR_PAD_LEFT) }}</strong>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $ticket['judul'] }}</div>
                                            <small
                                                class="text-muted">{{ Str::limit($ticket['deskripsi'] ?? '', 50) }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">{{ substr($ticket['skpd'], 0, 1) }}</div>
                                                <small>{{ $ticket['skpd'] }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="priority-{{ strtolower($ticket['prioritas']) }}">
                                                <i class="bi bi-flag-fill"></i>
                                                {{ ucfirst($ticket['prioritas']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ strtolower($ticket['status']) }}">
                                                {{ $ticket['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $ticket['tanggal'] }}</div>
                                            @if ($ticket['target'])
                                                <small class="text-muted">Target: {{ $ticket['target'] }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($ticket['petugas'])
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar me-2">{{ substr($ticket['petugas'], 0, 1) }}
                                                    </div>
                                                    <small>{{ $ticket['petugas'] }}</small>
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
                                                    <li><a class="dropdown-item" href="{{ $ticket['id'] }}"
                                                            onclick="showTicketDetail({{ $ticket['id'] }})">
                                                            <i class="bi bi-eye me-2"></i>Lihat Detail
                                                        </a></li>
                                                    @if ($ticket['status'] === 'baru')
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="updateStatus({{ $ticket['id'] }}, 'diproses')">
                                                                <i class="bi bi-play-circle me-2"></i>Mulai Kerjakan
                                                            </a></li>
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="assignTicket({{ $ticket['id'] }})">
                                                                <i class="bi bi-person-plus me-2"></i>Tugaskan
                                                            </a></li>
                                                    @endif
                                                    @if ($ticket['status'] === 'diproses')
                                                        <li><a class="dropdown-item text-success" href="#"
                                                                onclick="completeTicket({{ $ticket['id'] }})">
                                                                <i class="bi bi-check-circle me-2"></i>Selesaikan
                                                            </a></li>
                                                    @endif
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"
                                                            onclick="rejectTicket({{ $ticket['id'] }})">
                                                            <i class="bi bi-x-circle me-2"></i>Tolak
                                                        </a></li>
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
                                <div class="card ticket-card h-100" onclick="showTicketDetail({{ $ticket['id'] }})">
                                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                                        <small class="fw-bold">#{{ str_pad($ticket['id'], 4, '0', STR_PAD_LEFT) }}</small>
                                        <span class="status-badge status-{{ strtolower($ticket['status']) }}">
                                            {{ $ticket['status'] }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($ticket['judul'], 40) }}</h6>
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($ticket['deskripsi'] ?? '', 60) }}</p>

                                        <div class="d-flex align-items-center mb-2">
                                            <div class="user-avatar me-2">{{ substr($ticket['skpd'], 0, 1) }}</div>
                                            <small class="text-muted">{{ $ticket['skpd'] }}</small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="priority-{{ strtolower($ticket['prioritas']) }}">
                                                <i class="bi bi-flag-fill"></i>
                                            </span>
                                            <small class="text-muted">{{ $ticket['tanggal'] }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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

    <!-- Modals -->
    @include('kominfo.partials.ticket-detail-modal')
    @include('kominfo.partials.assign-modal')
    @include('kominfo.partials.complete-modal')
    @include('kominfo.partials.reject-modal')

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
            const filters = ['status', 'prioritas', 'skpd'];
            filters.forEach(filterId => {
                const element = document.getElementById(filterId);
                if (element) {
                    element.addEventListener('change', function() {
                        this.form.submit();
                    });
                }
            });
        });

        // Ticket actions
        function showTicketDetail(ticketId) {
            // Show modal with ticket details
            const modal = new bootstrap.Modal(document.getElementById('ticketDetailModal'));
            modal.show();

            // Load ticket data (simulate API call)
            loadTicketDetail(ticketId);
        }

        function loadTicketDetail(ticketId) {
            // In real app, this would be an AJAX call
            const tickets = @json($tickets ?? []);
            const ticket = tickets.find(t => t.id == ticketId);

            if (ticket) {
                document.getElementById('ticketDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-8">
                    <h5>${ticket.judul}</h5>
                    <p class="text-muted">${ticket.deskripsi || 'Tidak ada deskripsi'}</p>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>SKPD:</strong><br>
                            <span class="text-muted">${ticket.skpd}</span>
                        </div>
                        <div class="col-6">
                            <strong>Pemohon:</strong><br>
                            <span class="text-muted">${ticket.nama_pemohon || '-'}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Tanggal Pengajuan:</strong><br>
                            <span class="text-muted">${ticket.tanggal}</span>
                        </div>
                        <div class="col-6">
                            <strong>Target Selesai:</strong><br>
                            <span class="text-muted">${ticket.target || '-'}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Jenis Pekerjaan:</strong><br>
                            <span class="text-muted">${ticket.jenis_pekerjaan || '-'}</span>
                        </div>
                        <div class="col-6">
                            <strong>Lokasi:</strong><br>
                            <span class="text-muted">${ticket.lokasi || '-'}</span>
                        </div>
                    </div>
                    
                    ${ticket.catatan_tambahan ? `
                                                        <div class="mb-3">
                                                            <strong>Catatan Tambahan:</strong><br>
                                                            <span class="text-muted">${ticket.catatan_tambahan}</span>
                                                        </div>
                                                        ` : ''}
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <span class="status-badge status-${ticket.status.toLowerCase()}">${ticket.status}</span>
                            
                            <div class="mt-3">
                                <i class="bi bi-flag-fill priority-${ticket.prioritas.toLowerCase()}"></i>
                                <div><small>Prioritas ${ticket.prioritas}</small></div>
                            </div>
                            
                            ${ticket.petugas ? `
                                                                <div class="mt-3">
                                                                    <div class="user-avatar mx-auto mb-2">${ticket.petugas.charAt(0)}</div>
                                                                    <div><small>Petugas: ${ticket.petugas}</small></div>
                                                                </div>
                                                                ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
            }
        }

        function updateStatus(ticketId, newStatus) {
            if (confirm(`Ubah status tiket menjadi "${newStatus}"?`)) {
                // In real app, this would be an AJAX call
                showToast(`Status tiket berhasil diubah menjadi "${newStatus}"`, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        }

        function assignTicket(ticketId) {
            const modal = new bootstrap.Modal(document.getElementById('assignModal'));
            modal.show();
        }

        function completeTicket(ticketId) {
            const modal = new bootstrap.Modal(document.getElementById('completeModal'));
            modal.show();
        }

        function rejectTicket(ticketId) {
            const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            modal.show();
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
