@extends('layouts.e-ticket')

@php
    $viewMode = $viewMode ?? 'semua';
    $authUser = auth()->user();
    $isSkpd = $authUser->isSkpd();
    $isPetugas = $authUser->isPetugas();
    $isAdmin = $authUser->isAdmin();
    $isPimpinan = $authUser->isPimpinan();
    $isKominfo = $isPetugas || $isAdmin || $isPimpinan;
@endphp

@section('title',
    $viewMode === 'saya'
    ? 'Tiket Saya - Sistem Ticketing Kominfo'
    : 'Daftar Tiket - Sistem Ticketing
    Kominfo')

@section('breadcrumb')
    @if ($viewMode === 'saya')
        <li class="breadcrumb-item active">Tiket Saya</li>
    @else
        <li class="breadcrumb-item active">Daftar Tiket</li>
    @endif
@endsection

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            @if ($viewMode === 'saya')
                <h2 class="mb-1"><i class="bi bi-ticket-perforated me-2"></i>Tiket Saya</h2>
                <p class="mb-0">Riwayat dan status pengajuan tiket dari departemen Anda</p>
            @elseif ($isPetugas)
                <h2 class="mb-1"><i class="bi bi-tools me-2"></i>Daftar Tiket Pekerjaan</h2>
                <p class="mb-0">Kelola dan selesaikan tiket yang menjadi tanggung jawab Anda</p>
            @elseif ($isPimpinan)
                <h2 class="mb-1"><i class="bi bi-bar-chart-steps me-2"></i>Pantau Tiket</h2>
                <p class="mb-0">Pantau seluruh tiket pekerjaan yang masuk dari semua SKPD</p>
            @else
                <h2 class="mb-1"><i class="bi bi-list-check me-2"></i>Daftar Tiket Pekerjaan</h2>
                <p class="mb-0">Kelola dan pantau semua tiket masuk dari SKPD</p>
            @endif
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-light text-dark fs-6">Total: {{ $tickets->total() }} tiket</span>
            @if ($viewMode === 'saya')
                <a href="{{ route('tiket.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Ajukan Tiket Baru
                </a>
            @elseif ($isAdmin)
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-bar-chart me-1"></i>Laporan
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <!-- Filter & Search -->
    <div class="card mb-4">
        <div class="card-body">
            @php
                $filterRoute = $viewMode === 'saya' ? route('tiket.saya') : route('tiket.index');
            @endphp
            <form method="GET" action="{{ $filterRoute }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" id="search"
                            placeholder="Cari no/judul..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="">Semua Status</option>
                        <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="menunggu_verifikasi"
                            {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        @if ($viewMode === 'saya')
                            <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan
                            </option>
                        @endif
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

                {{-- Filter SKPD: hanya untuk Kominfo (semua mode) --}}
                @if ($viewMode === 'semua' && !$isSkpd)
                    <div class="col-md-2">
                        <label for="department_id" class="form-label">SKPD</label>
                        <select class="form-select" name="department_id" id="department_id">
                            <option value="">Semua SKPD</option>
                            @foreach ($skpdList ?? [] as $skpd)
                                <option value="{{ $skpd->id }}"
                                    {{ request('department_id') == $skpd->id ? 'selected' : '' }}>
                                    {{ $skpd->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Filter Petugas: hanya Admin di semua mode --}}
                @if ($viewMode === 'semua' && $isAdmin && ($petugasList ?? collect())->count())
                    <div class="col-md-2">
                        <label for="assignee_id" class="form-label">Petugas</label>
                        <select class="form-select" name="assignee_id" id="assignee_id">
                            <option value="">Semua Petugas</option>
                            @foreach ($petugasList as $p)
                                <option value="{{ $p->id }}"
                                    {{ request('assignee_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Tombol Filter Cepat untuk Petugas dihapus: petugas otomatis melihat tiket mereka sendiri --}}

                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ $filterRoute }}" class="btn btn-outline-secondary">
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
                    <small class="text-muted">{{ $viewMode === 'saya' ? 'Menunggu Proses' : 'Tiket Baru' }}</small>
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
        @if ($viewMode === 'semua')
            <div class="col-md-3">
                <div class="card" style="border-top:3px solid #ea580c;">
                    <div class="card-body text-center">
                        <h4 style="color:#ea580c;">{{ $stats['menunggu_verifikasi'] ?? 0 }}</h4>
                        <small class="text-muted">Menunggu Verifikasi</small>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-body text-center">
                        <h4 class="text-primary">{{ $stats['total'] ?? 0 }}</h4>
                        <small class="text-muted">Total Tiket</small>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Tickets List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                @if ($viewMode === 'saya')
                    Tiket Saya
                @elseif ($isPetugas)
                    Tiket Pekerjaan Saya
                @else
                    Semua Tiket
                @endif
            </h5>
            <div class="d-flex gap-2">
                @if (!$isPetugas)
                    <a href="{{ route('tiket.create') }}">
                        <button class="btn btn-sm btn-primary">Tambah Tiket</button></a>
                @endif
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
                                    <th width="{{ $viewMode === 'saya' ? '35%' : '25%' }}">Judul Pekerjaan</th>
                                    @if ($viewMode === 'semua')
                                        <th width="15%">SKPD</th>
                                    @endif
                                    <th width="10%">Prioritas</th>
                                    <th width="10%">Status</th>
                                    <th width="12%">Tanggal</th>
                                    @if ($viewMode === 'semua' || $isKominfo)
                                        <th width="13%">Petugas</th>
                                    @endif
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

                                        {{-- Kolom SKPD hanya untuk tampilan semua (Kominfo) --}}
                                        @if ($viewMode === 'semua')
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar me-2">
                                                        {{ substr($ticket->department->name ?? 'T', 0, 1) }}</div>
                                                    <small>{{ $ticket->department->name ?? '-' }}</small>
                                                </div>
                                            </td>
                                        @endif

                                        <td>
                                            <span class="priority-{{ strtolower($ticket->priority->name ?? 'rendah') }}">
                                                <i class="bi bi-flag-fill"></i>
                                                {{ ucfirst($ticket->priority->name ?? 'Rendah') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ strtolower($ticket->status) }}">
                                                {{ $ticket->status === 'menunggu_verifikasi' ? 'Menunggu Verifikasi' : ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $ticket->created_at->format('d/m/Y') }}</div>
                                            @if($ticket->target_date)
                                                @php
                                                    $td = \Carbon\Carbon::parse($ticket->target_date);
                                                    $isOverdue = $td->isPast() && $ticket->isOpen();
                                                    $overdueDays = $isOverdue ? now()->diffInDays($td) : 0;
                                                    $remaining = !$isOverdue && $ticket->isOpen() ? now()->diffInDays($td, false) : null;
                                                @endphp
                                                @if($isOverdue)
                                                    <div class="badge-overdue mt-1">⚠ {{ $overdueDays }}h terlambat</div>
                                                @elseif($remaining !== null && $remaining <= 3)
                                                    <small style="color:var(--warning);font-weight:600;">⏳ {{ $remaining }}h lagi</small>
                                                @else
                                                    <small class="text-muted">Target: {{ $td->format('d/m/Y') }}</small>
                                                @endif
                                            @endif
                                        </td>

                                        {{-- Kolom Petugas --}}
                                        @if ($viewMode === 'semua' || $isKominfo)
                                            <td>
                                                @if ($ticket->assignees->count())
                                                    @foreach ($ticket->assignees as $a)
                                                        <div class="d-flex align-items-center mb-1">
                                                            <div class="user-avatar me-2">{{ substr($a->name, 0, 1) }}
                                                            </div>
                                                            <small>{{ $a->name }}</small>
                                                        </div>
                                                    @endforeach
                                                @elseif ($isAdmin)
                                                    <button class="btn btn-xs btn-outline-warning btn-sm" type="button"
                                                        data-bs-toggle="modal" data-bs-target="#assignModal"
                                                        data-ticket-id="{{ $ticket->id }}"
                                                        onclick="event.stopPropagation()">
                                                        <i class="bi bi-person-plus me-1"></i>Tugaskan
                                                    </button>
                                                @else
                                                    <small class="text-muted fst-italic">Belum ditugaskan</small>
                                                @endif
                                            </td>
                                        @endif

                                        <td>
                                            <div class="dropdown" onclick="event.stopPropagation()">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('tiket.show', $ticket->id) }}">
                                                            <i class="bi bi-eye me-2"></i>Lihat Detail
                                                        </a></li>

                                                    {{-- Aksi SKPD: Batalkan tiket baru milik sendiri --}}
                                                    @if ($viewMode === 'saya' && $ticket->status === 'baru' && $ticket->requester_id === $authUser->id)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form method="POST"
                                                                action="{{ route('tiket.batalkan', $ticket->id) }}"
                                                                onsubmit="return confirm('Yakin ingin membatalkan tiket ini?')">
                                                                @csrf @method('PUT')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="bi bi-x-octagon me-2"></i>Batalkan Tiket
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    {{-- Aksi Petugas / Admin --}}
                                                    @if (in_array($ticket->status, ['baru', 'diproses', 'menunggu_verifikasi']) && ($isAdmin || $isPetugas))
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @if ($isAdmin && in_array($ticket->status, ['baru', 'diproses']) && $ticket->assignees->isEmpty())
                                                            <li>
                                                                <button type="button" class="dropdown-item"
                                                                    data-bs-toggle="modal" data-bs-target="#assignModal"
                                                                    data-ticket-id="{{ $ticket->id }}">
                                                                    <i class="bi bi-person-plus me-2"></i>Tugaskan Petugas
                                                                </button>
                                                            </li>
                                                        @elseif ($isAdmin && in_array($ticket->status, ['baru', 'diproses']))
                                                            <li>
                                                                <button type="button" class="dropdown-item"
                                                                    data-bs-toggle="modal" data-bs-target="#assignModal"
                                                                    data-ticket-id="{{ $ticket->id }}">
                                                                    <i class="bi bi-person-gear me-2"></i>Ubah Penugasan
                                                                </button>
                                                            </li>
                                                        @endif
                                                        {{-- Mulai Kerjakan: hanya petugas yang ditugaskan --}}
                                                        @if ($ticket->status === 'baru' && $isPetugas && $ticket->assignees->contains('id', $authUser->id))
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('tiket.update-status', $ticket->id) }}">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status"
                                                                        value="diproses">
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="bi bi-play-circle me-2"></i>Mulai
                                                                        Kerjakan
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        {{-- Minta Verifikasi: petugas menandai pekerjaan selesai --}}
                                                        @if ($ticket->status === 'diproses' && $isPetugas && $ticket->assignees->contains('id', $authUser->id))
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('tiket.update-status', $ticket->id) }}">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status"
                                                                        value="menunggu_verifikasi">
                                                                    <button type="submit"
                                                                        class="dropdown-item text-warning">
                                                                        <i class="bi bi-hourglass-split me-2"></i>Minta
                                                                        Verifikasi
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        {{-- Verifikasi Selesai: hanya admin --}}
                                                        @if ($ticket->status === 'menunggu_verifikasi' && $isAdmin)
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('tiket.update-status', $ticket->id) }}">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status" value="selesai">
                                                                    <button type="submit"
                                                                        class="dropdown-item text-success">
                                                                        <i class="bi bi-patch-check me-2"></i>Verifikasi
                                                                        Selesai
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if ($isAdmin)
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('tiket.update-status', $ticket->id) }}"
                                                                    onsubmit="return confirm('Yakin tolak tiket ini?')">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status" value="ditolak">
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger">
                                                                        <i class="bi bi-x-circle me-2"></i>Tolak Tiket
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
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
                                                {{ $ticket->status === 'menunggu_verifikasi' ? 'Menunggu Verifikasi' : ucfirst($ticket->status) }}
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title text-dark">{{ Str::limit($ticket->title, 50) }}</h6>
                                            <p class="card-text small text-muted">
                                                {{ Str::limit($ticket->description, 70) }}</p>

                                            @if ($viewMode === 'semua')
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="user-avatar me-2">
                                                        {{ substr($ticket->department->name ?? 'T', 0, 1) }}</div>
                                                    <small
                                                        class="text-muted">{{ $ticket->department->name ?? '-' }}</small>
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="priority-{{ strtolower($ticket->priority->name ?? 'rendah') }}">
                                                    <i class="bi bi-flag-fill"></i>
                                                    {{ ucfirst($ticket->priority->name ?? 'Rendah') }}
                                                </span>
                                                <small class="text-muted">{{ $ticket->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            @if($ticket->target_date)
                                                @php
                                                    $tdCard = \Carbon\Carbon::parse($ticket->target_date);
                                                    $isOverdueCard = $tdCard->isPast() && $ticket->isOpen();
                                                @endphp
                                                @if($isOverdueCard)
                                                    <div class="badge-overdue mt-2">⚠ Terlambat {{ now()->diffInDays($tdCard) }} hari</div>
                                                @endif
                                            @endif

                                            @if ($viewMode === 'saya' && $ticket->status === 'baru' && $ticket->requester_id === $authUser->id)
                                                <div class="mt-2">
                                                    <form method="POST"
                                                        action="{{ route('tiket.batalkan', $ticket->id) }}"
                                                        onsubmit="return confirm('Yakin ingin membatalkan tiket ini?')">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                                                            onclick="event.stopPropagation(); event.preventDefault(); if(confirm('Yakin?')) this.form.submit();">
                                                            <i class="bi bi-x-octagon me-1"></i>Batalkan
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif

                                            @if ($viewMode === 'semua' && $ticket->assignees->count())
                                                @foreach ($ticket->assignees as $a)
                                                    <div class="mt-1 d-flex align-items-center">
                                                        <div class="user-avatar me-2"
                                                            style="width:24px;height:24px;font-size:10px">
                                                            {{ substr($a->name, 0, 1) }}</div>
                                                        <small class="text-muted">{{ $a->name }}</small>
                                                    </div>
                                                @endforeach
                                            @elseif ($viewMode === 'semua')
                                                <small class="text-muted fst-italic mt-1 d-block">Belum ditugaskan</small>
                                            @endif
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
                    @if ($viewMode === 'saya')
                        <h5 class="text-muted">Belum ada tiket yang diajukan</h5>
                        <p class="text-muted">Anda belum pernah mengajukan tiket atau tidak ada tiket yang sesuai filter.
                        </p>
                        <a href="{{ route('tiket.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Ajukan Tiket Baru
                        </a>
                    @else
                        <h5 class="text-muted">Tidak ada tiket ditemukan</h5>
                        <p class="text-muted">Coba ubah filter atau kriteria pencarian Anda</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if ($isAdmin)
        @include('kominfo.partials.assign-modal')
    @endif
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
                if (el) el.addEventListener('change', function() {
                    this.form.submit();
                });
            });
        });
    </script>
@endpush
