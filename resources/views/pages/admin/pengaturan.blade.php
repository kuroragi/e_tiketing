@extends('layouts.e-ticket')

@section('title', 'Pengaturan Sistem - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Pengaturan Sistem</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-gear me-2"></i>
        Pengaturan Sistem
    </h2>
    <p class="mb-0">Konfigurasi dan pengaturan sistem E-Ticket</p>
@endsection

@section('content')
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="list-group sticky-top" style="top: 80px;">
                <a href="#informasi" class="list-group-item list-group-item-action" data-bs-smooth-scroll="true">
                    <i class="bi bi-info-circle me-2"></i>Informasi Sistem
                </a>
                <a href="#email" class="list-group-item list-group-item-action" data-bs-smooth-scroll="true">
                    <i class="bi bi-envelope me-2"></i>Email
                </a>
                <a href="#aplikasi" class="list-group-item list-group-item-action" data-bs-smooth-scroll="true">
                    <i class="bi bi-sliders me-2"></i>Pengaturan Aplikasi
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Informasi Sistem -->
            <div class="card mb-4" id="informasi">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Informasi Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Sistem</label>
                            <input type="text" class="form-control" value="{{ $settings['nama_sistem'] }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Versi</label>
                            <input type="text" class="form-control" value="{{ $settings['versi'] }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">URL Sistem</label>
                            <input type="text" class="form-control" value="{{ $settings['url_sistem'] }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Organisasi</label>
                            <input type="text" class="form-control" value="{{ $settings['nama_organisasi'] }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Admin</label>
                            <input type="email" class="form-control" value="{{ $settings['email_admin'] }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" value="{{ $settings['telepon'] }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" rows="2">{{ $settings['alamat'] }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Operasional</label>
                        <input type="text" class="form-control" value="{{ $settings['jam_operasional'] }}">
                    </div>

                    <button class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

            <!-- Email Settings -->
            <div class="card mb-4" id="email">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope text-primary me-2"></i>
                        Pengaturan Email
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Mail Driver</label>
                            <input type="text" class="form-control" value="{{ $emailSettings['mail_driver'] }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mail Host</label>
                            <input type="text" class="form-control" value="{{ $emailSettings['mail_host'] }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Mail Port</label>
                            <input type="text" class="form-control" value="{{ $emailSettings['mail_port'] }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Encryption</label>
                            <input type="text" class="form-control" value="{{ $emailSettings['mail_encryption'] }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">From Name</label>
                        <input type="text" class="form-control" value="{{ $emailSettings['mail_from_name'] }}">
                    </div>

                    <button class="btn btn-primary me-2">
                        <i class="bi bi-check-circle me-2"></i>
                        Simpan
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="bi bi-envelope-check me-2"></i>
                        Test Email
                    </button>
                </div>
            </div>

            <!-- Pengaturan Aplikasi -->
            <div class="card mb-4" id="aplikasi">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-sliders text-primary me-2"></i>
                        Pengaturan Aplikasi
                    </h5>
                </div>
                <div class="card-body">
                    @foreach ($systemSettings as $category)
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2">{{ $category['kategori'] }}</h6>

                            @foreach ($category['settings'] as $setting)
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label">{{ $setting['label'] }}</label>
                                        </div>
                                        <div class="col-md-4">
                                            @if ($setting['type'] == 'boolean')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        {{ $setting['value'] ? 'checked' : '' }}>
                                                </div>
                                            @else
                                                <input type="text" class="form-control"
                                                    value="{{ $setting['value'] }}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <button class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        Simpan Semua Pengaturan
                    </button>
                </div>
            </div>

            <!-- Backup & Reset -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check text-danger me-2"></i>
                        Maintenance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6>Backup Database</h6>
                            <p class="text-muted small">Buat backup database sistem untuk keamanan data</p>
                            <button class="btn btn-warning">
                                <i class="bi bi-download me-2"></i>
                                Backup Sekarang
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Clear Cache</h6>
                            <p class="text-muted small">Bersihkan cache untuk meningkatkan performa</p>
                            <button class="btn btn-info">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Clear Cache
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
