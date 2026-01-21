@extends('layouts.e-ticket')

@section('title', 'Manajemen Jenis Pekerjaan - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Manajemen Jenis Pekerjaan</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-tags me-2"></i>
        Manajemen Jenis Pekerjaan
    </h2>
    <p class="mb-0">Kelola kategori dan jenis pekerjaan yang ditangani Kominfo</p>
@endsection

@section('content')
    <!-- Toolbar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Cari jenis pekerjaan...">
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option selected>Semua Status</option>
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Jenis
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Jenis Pekerjaan Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Daftar Jenis Pekerjaan
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Jenis</th>
                            <th>Kode</th>
                            <th>Deskripsi</th>
                            <th>Estimasi</th>
                            <th>Tiket</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jenisList as $jenis)
                            <tr>
                                <td>
                                    <strong>{{ $jenis['nama'] }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $jenis['kode'] }}</span>
                                </td>
                                <td>
                                    <small>{{ substr($jenis['deskripsi'], 0, 40) }}...</small>
                                </td>
                                <td>
                                    <small>{{ $jenis['estimasi_hari'] }} hari</small>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $jenis['total_tiket'] }}</strong>
                                </td>
                                <td>
                                    <span class="text-success">{{ $jenis['total_selesai'] }}</span>
                                    <small
                                        class="text-muted">({{ round(($jenis['total_selesai'] / $jenis['total_tiket']) * 100) }}%)</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ ucfirst($jenis['status']) }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-primary">{{ count($jenisList) }}</h4>
                    <p class="text-muted small">Total Jenis Pekerjaan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-success">{{ array_sum(array_column($jenisList, 'total_tiket')) }}</h4>
                    <p class="text-muted small">Total Tiket</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-info">
                        {{ round((array_sum(array_column($jenisList, 'total_selesai')) / array_sum(array_column($jenisList, 'total_tiket'))) * 100) }}%
                    </h4>
                    <p class="text-muted small">Tingkat Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-warning">5.2</h4>
                    <p class="text-muted small">Hari Rata-rata</p>
                </div>
            </div>
        </div>
    </div>
@endsection
