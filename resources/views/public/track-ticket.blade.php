@extends('layouts.landing')

@section('title', 'Lacak Tiket - ' . Setting::get('app_name', 'Layanan Publik Kominfo'))

@push('styles')
<style>
    .track-section {
        padding-top: 100px;
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
    }
    .track-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    }
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.65rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--landing-primary);
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px var(--landing-primary);
    }
    .timeline-item.status-change::before {
        background: #f59e0b;
        box-shadow: 0 0 0 2px #f59e0b;
    }
    .timeline-item.completed::before {
        background: #10b981;
        box-shadow: 0 0 0 2px #10b981;
    }
    .status-progress {
        display: flex;
        gap: 0;
        margin-bottom: 2rem;
    }
    .status-step {
        flex: 1;
        text-align: center;
        position: relative;
    }
    .status-step .step-dot {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin: 0 auto 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        background: #e2e8f0;
        color: #94a3b8;
        position: relative;
        z-index: 2;
    }
    .status-step.active .step-dot {
        background: var(--landing-primary);
        color: #fff;
    }
    .status-step.done .step-dot {
        background: #10b981;
        color: #fff;
    }
    .status-step::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 50%;
        width: 100%;
        height: 3px;
        background: #e2e8f0;
        z-index: 1;
    }
    .status-step:last-child::after {
        display: none;
    }
    .status-step.done::after {
        background: #10b981;
    }
    .status-step .step-label {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
    }
    .status-step.active .step-label,
    .status-step.done .step-label {
        color: #334155;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<section class="track-section">
    <div class="container py-5">
        <!-- Search Form -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7">
                <div class="text-center mb-4">
                    <h2 class="fw-bold"><i class="bi bi-search text-primary me-2"></i>Lacak Pengaduan</h2>
                    <p class="text-muted">Masukkan kode tracking untuk melihat status pengaduan Anda</p>
                </div>
                <div class="track-card p-4">
                    <form action="{{ route('public.ticket.track') }}" method="GET">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-ticket-perforated text-primary"></i></span>
                            <input type="text" name="code" class="form-control border-start-0"
                                placeholder="Masukkan kode tracking..." value="{{ request('code') }}" required>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-1"></i>Lacak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(isset($ticket))
            <!-- Ticket Result -->
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <!-- Status Progress -->
                    @php
                        $statuses = ['baru', 'diproses', 'menunggu_verifikasi', 'selesai'];
                        $statusLabels = [
                            'baru' => 'Baru',
                            'diproses' => 'Diproses',
                            'menunggu_verifikasi' => 'Verifikasi',
                            'selesai' => 'Selesai'
                        ];
                        $statusIcons = [
                            'baru' => 'bi-clock',
                            'diproses' => 'bi-gear',
                            'menunggu_verifikasi' => 'bi-hourglass-split',
                            'selesai' => 'bi-check-lg'
                        ];
                        $currentIndex = array_search($ticket->status, $statuses);
                        if ($currentIndex === false) $currentIndex = -1;
                        $isRejectedOrCancelled = in_array($ticket->status, ['ditolak', 'dibatalkan']);
                    @endphp

                    @if(!$isRejectedOrCancelled)
                    <div class="status-progress mb-4">
                        @foreach($statuses as $i => $status)
                            <div class="status-step {{ $i < $currentIndex ? 'done' : ($i == $currentIndex ? 'active' : '') }}">
                                <div class="step-dot">
                                    <i class="bi {{ $i < $currentIndex ? 'bi-check-lg' : $statusIcons[$status] }}"></i>
                                </div>
                                <div class="step-label">{{ $statusLabels[$status] }}</div>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-{{ $ticket->status === 'ditolak' ? 'danger' : 'warning' }} d-flex align-items-center gap-3 mb-4" style="border-radius:0.75rem;">
                        <i class="bi bi-{{ $ticket->status === 'ditolak' ? 'x-circle' : 'exclamation-triangle' }} fs-3"></i>
                        <div>
                            <strong>Pengaduan {{ $ticket->status === 'ditolak' ? 'Ditolak' : 'Dibatalkan' }}</strong>
                            <p class="mb-0 small">{{ $ticket->summary ?? 'Tidak ada keterangan tambahan.' }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Ticket Details -->
                    <div class="track-card mb-4">
                        <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold mb-1">{{ $ticket->title }}</h4>
                                <small class="text-muted">
                                    <i class="bi bi-hash"></i>{{ $ticket->number }} &bull;
                                    Tracking: <code>{{ $ticket->tracking_code }}</code>
                                </small>
                            </div>
                            <span class="badge bg-{{ $ticket->statusBadgeClass() }} fs-6 px-3 py-2">
                                {{ $ticket->statusLabel() }}
                            </span>
                        </div>
                        <div class="p-4">
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-2"><i class="bi bi-file-text me-1 text-primary"></i>Deskripsi</h6>
                                    <p class="text-muted">{{ $ticket->description }}</p>

                                    @if($ticket->summary)
                                        <h6 class="fw-bold mb-2 mt-4"><i class="bi bi-journal-check me-1 text-success"></i>Ringkasan Penyelesaian</h6>
                                        <p class="text-muted">{{ $ticket->summary }}</p>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-light rounded-3 p-3">
                                        <table class="table table-borderless table-sm small mb-0">
                                            <tr>
                                                <td class="text-muted">Kategori</td>
                                                <td class="fw-semibold">{{ $ticket->category->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Prioritas</td>
                                                <td>
                                                    <span class="badge" style="background:{{ $ticket->priority->color ?? '#6b7280' }}">
                                                        {{ $ticket->priority->name ?? '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Dibuat</td>
                                                <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                            </tr>
                                            @if($ticket->closed_at)
                                            <tr>
                                                <td class="text-muted">Selesai</td>
                                                <td>{{ $ticket->closed_at->format('d M Y') }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td class="text-muted">Pelapor</td>
                                                <td>{{ $ticket->public_name ?? ($ticket->requester->name ?? '-') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    @if($comments->count())
                    <div class="track-card p-4">
                        <h5 class="fw-bold mb-4"><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Aktivitas</h5>
                        <div class="timeline">
                            @foreach($comments as $comment)
                                <div class="timeline-item {{ $comment->type === 'status_change' ? 'status-change' : '' }} {{ $comment->type === 'progress' ? 'completed' : '' }}">
                                    <div class="d-flex justify-content-between mb-1">
                                        <strong class="small">
                                            {{ $comment->user->name ?? 'Sistem' }}
                                            @if($comment->user && ($comment->user->isPetugas() || $comment->user->isAdmin()))
                                                <span class="badge bg-primary bg-opacity-10 text-primary ms-1" style="font-size:0.65rem;">Petugas</span>
                                            @endif
                                        </strong>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="text-muted small mb-0">{!! nl2br(e($comment->body)) !!}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @elseif(request('code'))
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="track-card p-5">
                        <i class="bi bi-search" style="font-size:3rem;color:#cbd5e1;"></i>
                        <h4 class="fw-bold mt-3">Tiket Tidak Ditemukan</h4>
                        <p class="text-muted">Kode tracking <code>{{ request('code') }}</code> tidak ditemukan dalam sistem kami.
                            Pastikan kode yang Anda masukkan benar.</p>
                        <a href="{{ route('public.ticket.track') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
