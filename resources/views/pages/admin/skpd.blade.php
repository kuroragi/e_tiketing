@extends('layouts.e-ticket')

@section('title', 'Manajemen SKPD - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Manajemen SKPD</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-building me-2"></i>
        Manajemen SKPD
    </h2>
    <p class="mb-0">Kelola data Satuan Kerja Perangkat Daerah</p>
@endsection

@section('content')
    <!-- Toolbar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Cari nama SKPD...">
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
                        Tambah SKPD
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SKPD List -->
    <div class="row">
        @foreach ($skpdList as $skpd)
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title">{{ $skpd['nama'] }}</h5>
                                <p class="text-muted small">{{ $skpd['singkatan'] }}</p>
                            </div>
                            <span class="badge bg-success">{{ ucfirst($skpd['status']) }}</span>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Pimpinan</small>
                                <strong>{{ $skpd['pimpinan'] }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Total Tiket</small>
                                <strong class="text-primary">{{ $skpd['total_tiket'] }}</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Alamat</small>
                            <p class="small mb-2">{{ $skpd['alamat'] }}</p>
                        </div>

                        <div class="row text-center border-top pt-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Kontak</small>
                                <small>{{ $skpd['kontak'] }}</small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Email</small>
                                <small>{{ $skpd['email'] }}</small>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i>
                                Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash me-1"></i>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
