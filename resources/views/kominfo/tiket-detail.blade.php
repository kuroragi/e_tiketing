@extends('layouts.e-ticket')

@section('title', 'Detail Tiket')

@php
    $ticket = $ticket ?? [
        'id' => request()->route('id') ?? 0,
        'judul' => 'Contoh Tiket',
        'deskripsi' => 'Ini adalah contoh deskripsi tiket untuk menampilkan informasi rinci.',
        'skpd' => 'Contoh SKPD',
        'jenis_pekerjaan' => 'Umum',
        'tanggal_pengajuan' => now()->format('Y-m-d'),
        'target_selesai' => '-',
        'status' => 'Baru',
        'prioritas' => 'Sedang',
    ];
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/tiket/daftar">Daftar Tiket</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('page-header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-0">{{ $ticket['judul'] ?? 'Judul Tiket' }}</h4>
            <small class="text-white-50">ID #{{ $ticket['id'] ?? '-' }} • {{ $ticket['skpd'] ?? 'SKPD' }}</small>
        </div>
        <div>
            <span
                class="status-badge status-{{ strtolower($ticket['status'] ?? 'baru') }}">{{ $ticket['status'] ?? 'Baru' }}</span>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Deskripsi</h5>
                    <p class="text-muted">{{ $ticket['deskripsi'] ?? 'Tidak ada deskripsi' }}</p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>SKPD</strong>
                            <div class="text-muted">{{ $ticket['skpd'] ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Jenis Pekerjaan</strong>
                            <div class="text-muted">{{ $ticket['jenis_pekerjaan'] ?? 'Umum' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Nama Pemohon</strong>
                            <div class="text-muted">{{ $ticket['pemohon'] ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Kontak Pemohon</strong>
                            <div class="text-muted">{{ $ticket['kontak'] ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Pengajuan</strong>
                            <div class="text-muted">{{ $ticket['tanggal_pengajuan'] ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Target Selesai</strong>
                            <div class="text-muted">{{ $ticket['target_selesai'] ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-info">
                <div class="card-body">
                    <h6 class="mb-3">Catatan/Komentar</h6>
                    <p class="text-muted mb-0">Belum ada catatan.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span
                            class="status-badge status-{{ strtolower($ticket['status'] ?? 'baru') }}">{{ $ticket['status'] ?? 'Baru' }}</span>
                    </div>
                    <div class="text-center mb-4">
                        <i class="bi bi-flag-fill priority-{{ strtolower($ticket['prioritas'] ?? 'rendah') }}"></i>
                        <div><small>Prioritas {{ ucfirst(strtolower($ticket['prioritas'] ?? 'rendah')) }}</small></div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="/tiket/daftar" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-2"></i>Kembali
                            ke Daftar</a>
                        <a href="/tiket/edit/{{ $ticket['id'] ?? '0' }}" class="btn btn-primary"><i
                                class="bi bi-pencil-square me-2"></i>Edit Tiket</a>
                        <div class="mt-3 text-center">
                            <small class="text-muted d-block">Petugas</small>
                            <div class="d-flex justify-content-center align-items-center mt-1">
                                @if (!empty($ticket['petugas']))
                                    <div class="user-avatar me-2">{{ substr($ticket['petugas'], 0, 1) }}</div>
                                    <small>{{ $ticket['petugas'] }}</small>
                                @else
                                    <small class="text-muted">Belum ditugaskan</small>
                                @endif
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
        // Tempat untuk script khusus halaman detail tiket
    </script>
@endpush
