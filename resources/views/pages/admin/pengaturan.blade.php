@extends('layouts.e-ticket')

@section('title', 'Pengaturan Sistem - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Pengaturan Sistem</li>
@endsection

@section('page-header')
    <h2 class="mb-1"><i class="bi bi-gear me-2"></i>Pengaturan Sistem</h2>
    <p class="mb-0">Konfigurasi dan pengaturan sistem E-Ticket</p>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.pengaturan.save') }}">
        @csrf
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="list-group sticky-top" style="top: 80px;">
                <a href="#informasi" class="list-group-item list-group-item-action active">
                    <i class="bi bi-info-circle me-2"></i>Informasi Sistem
                </a>
                <a href="#email" class="list-group-item list-group-item-action">
                    <i class="bi bi-envelope me-2"></i>Email / SMTP
                </a>
                <a href="#upload" class="list-group-item list-group-item-action">
                    <i class="bi bi-cloud-upload me-2"></i>Upload File
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Informasi Sistem -->
            <div class="card mb-4" id="informasi">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Sistem</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Aplikasi</label>
                            <input type="text" class="form-control" name="app_name"
                                value="{{ $settings['app_name']->value ?? 'Sistem Ticketing Kominfo' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Deskripsi Aplikasi</label>
                            <input type="text" class="form-control" name="app_description"
                                value="{{ $settings['app_description']->value ?? '' }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Instansi / Organisasi</label>
                        <input type="text" class="form-control" name="app_institution"
                            value="{{ $settings['app_institution']->value ?? '' }}"
                            placeholder="Dinas Komunikasi dan Informatika Kota Bukittinggi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Versi Sistem</label>
                        <input type="text" class="form-control" value="1.0.0" readonly>
                        <div class="form-text">Versi dikontrol oleh sistem, tidak dapat diubah manual.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Simpan Informasi
                    </button>
                </div>
            </div>

            <!-- Email / SMTP Settings -->
            <div class="card mb-4" id="email">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-envelope text-primary me-2"></i>Pengaturan Email / SMTP</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">SMTP Host</label>
                            <input type="text" class="form-control" name="smtp_host"
                                value="{{ $settings['smtp_host']->value ?? 'smtp.mailtrap.io' }}"
                                placeholder="smtp.gmail.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SMTP Port</label>
                            <input type="number" class="form-control" name="smtp_port"
                                value="{{ $settings['smtp_port']->value ?? '587' }}"
                                placeholder="587">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pengirim</label>
                            <input type="text" class="form-control" name="mail_from_name"
                                value="{{ $settings['mail_from_name']->value ?? '' }}"
                                placeholder="Kominfo Bukittinggi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Email Pengirim</label>
                            <input type="email" class="form-control" name="mail_from_address"
                                value="{{ $settings['mail_from_address']->value ?? '' }}"
                                placeholder="noreply@domain.go.id">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-check-circle me-2"></i>Simpan
                    </button>
                </div>
            </div>

            <!-- Upload Settings -->
            <div class="card mb-4" id="upload">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-cloud-upload text-primary me-2"></i>Pengaturan Upload File</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Ukuran Maksimal Upload (KB)</label>
                        <input type="number" class="form-control" name="max_upload_size"
                            value="{{ $settings['max_upload_size']->value ?? 10240 }}"
                            min="1024" max="51200">
                        <div class="form-text">Default: 10240 KB (10 MB)</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">MIME Type yang Diizinkan</label>
                        <input type="text" class="form-control" name="allowed_mimetypes"
                            value="{{ $settings['allowed_mimetypes']->value ?? 'application/pdf,image/jpeg,image/png' }}"
                            placeholder="application/pdf,image/jpeg,image/png">
                        <div class="form-text">Pisahkan dengan koma</div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Simpan
                    </button>
                </div>
            </div>

            <!-- Maintenance -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-shield-check text-danger me-2"></i>Maintenance</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6>Clear Cache</h6>
                            <p class="text-muted small">Bersihkan cache aplikasi untuk meningkatkan performa.</p>
                            <a href="{{ url('admin/clear-cache') }}" class="btn btn-info"
                                onclick="return confirm('Bersihkan cache sekarang?')">
                                <i class="bi bi-arrow-clockwise me-2"></i>Clear Cache
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Informasi Server</h6>
                            <ul class="list-unstyled small text-muted">
                                <li><i class="bi bi-server me-1"></i>PHP {{ phpversion() }}</li>
                                <li><i class="bi bi-box me-1"></i>Laravel {{ app()->version() }}</li>
                                <li><i class="bi bi-clock me-1"></i>{{ now()->format('d M Y H:i') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for sidebar links
    document.querySelectorAll('.list-group-item').forEach(function(link) {
        link.addEventListener('click', function(e) {
            document.querySelectorAll('.list-group-item').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endpush