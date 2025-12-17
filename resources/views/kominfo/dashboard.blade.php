@extends('layouts.e-ticket')

@section('title', 'Dashboard - Sistem Ticketing Kominfo')

@section('content')
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-2">Selamat Datang di Sistem Ticketing Layanan</h3>
                            <p class="text-muted mb-0">Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
                            <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="bi bi-speedometer2 display-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-warning">
                <div class="card-body stats-card">
                    <div class="stats-number text-warning">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stats-label">Total Tiket</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-primary">
                <div class="card-body stats-card">
                    <div class="stats-number text-primary">{{ $stats['baru'] ?? 0 }}</div>
                    <div class="stats-label">Tiket Baru</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-info">
                <div class="card-body stats-card">
                    <div class="stats-number text-info">{{ $stats['diproses'] ?? 0 }}</div>
                    <div class="stats-label">Sedang Diproses</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-success">
                <div class="card-body stats-card">
                    <div class="stats-number text-success">{{ $stats['selesai'] ?? 0 }}</div>
                    <div class="stats-label">Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Tickets -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        Tiket Terbaru
                    </h5>
                    @if (auth()->user()->role === 'kominfo' || auth()->user()->role === 'admin')
                        <a href="{{ route('tiket.daftar') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if (isset($recentTickets) && count($recentTickets) > 0)
                        @foreach ($recentTickets as $ticket)
                            <div class="ticket-card p-3 border-bottom" onclick="showTicketDetail({{ $ticket['id'] }})">
                                <div class="row align-items-center">
                                    <div class="col-md-1">
                                        <div class="user-avatar">{{ substr($ticket['skpd'], 0, 1) }}</div>
                                    </div>
                                    <div class="col-md-7">
                                        <h6 class="mb-1">{{ $ticket['judul'] }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-building me-1"></i>{{ $ticket['skpd'] }}
                                            <span class="ms-3"><i
                                                    class="bi bi-calendar me-1"></i>{{ $ticket['tanggal_pengajuan'] }}</span>
                                        </small>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="status-badge status-{{ strtolower($ticket['status']) }}">
                                            {{ $ticket['status'] }}
                                        </span>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <span class="priority-{{ strtolower($ticket['prioritas']) }}">
                                            <i class="bi bi-flag-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                            <p class="text-muted">Belum ada tiket terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning text-warning me-2"></i>
                        Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    @if (auth()->user()->role === 'skpd')
                        <a href="{{ route('tiket.pengajuan') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-plus-circle me-2"></i>Ajukan Tiket Baru
                        </a>
                        <a href="{{ route('tiket.saya') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-ticket me-2"></i>Lihat Tiket Saya
                        </a>
                    @elseif(auth()->user()->role === 'kominfo')
                        <a href="{{ route('tiket.daftar', ['status' => 'baru']) }}" class="btn btn-warning w-100 mb-2">
                            <i class="bi bi-exclamation-circle me-2"></i>Tiket Menunggu
                        </a>
                        <a href="{{ route('tiket.daftar', ['status' => 'diproses']) }}" class="btn btn-info w-100">
                            <i class="bi bi-arrow-repeat me-2"></i>Tiket Dikerjakan
                        </a>
                    @elseif(auth()->user()->role === 'pimpinan')
                        <a href="{{ route('laporan.index') }}" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-bar-chart me-2"></i>Lihat Laporan
                        </a>
                        <a href="{{ route('tiket.daftar') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-eye me-2"></i>Monitor Tiket
                        </a>
                    @endif
                </div>
            </div>

            <!-- SKPD Performance -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-building text-info me-2"></i>
                        Statistik SKPD
                    </h6>
                </div>
                <div class="card-body">
                    @if (isset($skpdStats) && count($skpdStats) > 0)
                        @foreach ($skpdStats as $skpd)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">{{ $skpd['nama'] }}</h6>
                                    <small class="text-muted">{{ $skpd['total'] }} tiket</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-light text-dark">{{ $skpd['bulan_ini'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-graph-up text-muted mb-2 d-block fs-3"></i>
                            <small class="text-muted">Belum ada data statistik</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section (if needed) -->
    @if (auth()->user()->role === 'pimpinan' || auth()->user()->role === 'admin')
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up text-success me-2"></i>
                            Grafik Perkembangan Tiket (7 Hari Terakhir)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $index => $day)
                                <div class="col">
                                    <div class="mb-2">
                                        <div class="bg-primary"
                                            style="height: {{ rand(20, 80) }}px; width: 30px; margin: 0 auto; border-radius: 4px;">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $day }}</small>
                                    <div><strong>{{ rand(1, 15) }}</strong></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Ticket Detail Modal -->
    <div class="modal fade" id="ticketDetailModal" tabindex="-1" aria-labelledby="ticketDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticketDetailModalLabel">Detail Tiket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ticketDetailContent">
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showTicketDetail(ticketId) {
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('ticketDetailModal'));
            modal.show();

            // Simulate loading (in real app, this would be an AJAX call)
            setTimeout(() => {
                const tickets = @json($recentTickets ?? []);
                const ticket = tickets.find(t => t.id == ticketId);

                if (ticket) {
                    document.getElementById('ticketDetailContent').innerHTML = `
                <div class="row">
                    <div class="col-md-8">
                        <h5>${ticket.judul}</h5>
                        <p class="text-muted mb-3">${ticket.deskripsi || 'Tidak ada deskripsi'}</p>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>SKPD:</strong><br>
                                <span class="text-muted">${ticket.skpd}</span>
                            </div>
                            <div class="col-6">
                                <strong>Jenis Pekerjaan:</strong><br>
                                <span class="text-muted">${ticket.jenis_pekerjaan || 'Umum'}</span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Tanggal Pengajuan:</strong><br>
                                <span class="text-muted">${ticket.tanggal_pengajuan}</span>
                            </div>
                            <div class="col-6">
                                <strong>Target Selesai:</strong><br>
                                <span class="text-muted">${ticket.target_selesai || '-'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <span class="status-badge status-${ticket.status.toLowerCase()}">${ticket.status}</span>
                                <div class="mt-3">
                                    <i class="bi bi-flag-fill priority-${ticket.prioritas.toLowerCase()}"></i>
                                    <div><small>Prioritas ${ticket.prioritas}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                }
            }, 500);
        }

        // Auto refresh dashboard every 5 minutes
        setInterval(() => {
            if (window.location.pathname === '/dashboard') {
                location.reload();
            }
        }, 300000);
    </script>
@endpush
