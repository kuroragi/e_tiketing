@extends('layouts.e-ticket')

@section('title', 'Manajemen Pengguna - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Manajemen Pengguna</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-people me-2"></i>
        Manajemen Pengguna
    </h2>
    <p class="mb-0">Kelola akun pengguna sistem</p>
@endsection

@section('content')
    <!-- Toolbar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Cari nama atau email pengguna...">
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option selected>Semua Role</option>
                        <option>Administrator</option>
                        <option>Pimpinan</option>
                        <option>Petugas Kominfo</option>
                        <option>Pengguna SKPD</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Overview -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <h5 class="mb-3">
                <i class="bi bi-shield-check me-2"></i>
                Daftar Role Pengguna
            </h5>
            <div class="row">
                @foreach ($roles as $role)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="card-title">{{ $role['nama'] }}</h6>
                                <p class="text-muted small">{{ $role['deskripsi'] }}</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil me-1"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Daftar Pengguna Sistem
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>SKPD</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2">{{ substr($user['nama'], 0, 1) }}</div>
                                        <div>
                                            <strong>{{ $user['nama'] }}</strong><br>
                                            <small class="text-muted">@{{ $user['username'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><small>{{ $user['email'] }}</small></td>
                                <td>
                                    <span class="badge bg-info">{{ $user['role'] }}</span>
                                </td>
                                <td><small>{{ $user['skpd'] }}</small></td>
                                <td>
                                    <span class="badge bg-success">{{ ucfirst($user['status']) }}</span>
                                </td>
                                <td><small>{{ $user['terdaftar'] }}</small></td>
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
@endsection
