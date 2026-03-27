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
                <a href="#hubungi" class="list-group-item list-group-item-action">
                    <i class="bi bi-headset me-2"></i>Hubungi Kami
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

            <!-- Hubungi Kami -->
            <div class="card mb-4" id="hubungi">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-headset text-primary me-2"></i>Manajemen Halaman Hubungi Kami</h5>
                </div>
                <div class="card-body">

                    {{-- Informasi Kontak Utama --}}
                    <h6 class="fw-semibold mb-3 border-bottom pb-2"><i class="bi bi-telephone me-1"></i>Informasi Kontak</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" name="contact_phone"
                                value="{{ $settings['contact_phone']->value ?? '(0752) 123-4567' }}"
                                placeholder="(0752) 123-4567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" name="contact_email"
                                value="{{ $settings['contact_email']->value ?? 'kominfo@bukittinggi.go.id' }}"
                                placeholder="kominfo@domain.go.id">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Kantor</label>
                        <input type="text" class="form-control" name="contact_address"
                            value="{{ $settings['contact_address']->value ?? 'Jl. Panglima Nyak Arief No. 45, Bukittinggi' }}"
                            placeholder="Alamat lengkap kantor">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Jam Operasional</label>
                        <input type="text" class="form-control" name="contact_hours"
                            value="{{ $settings['contact_hours']->value ?? 'Senin - Jumat, 08:00 - 17:00 WIB' }}"
                            placeholder="Senin - Jumat, 08:00 - 17:00 WIB">
                    </div>

                    {{-- Media Sosial --}}
                    <h6 class="fw-semibold mb-3 border-bottom pb-2"><i class="bi bi-share me-1"></i>Media Sosial</h6>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-facebook text-primary me-1"></i>Facebook URL</label>
                            <input type="url" class="form-control" name="contact_social_facebook"
                                value="{{ $settings['contact_social_facebook']->value ?? '' }}"
                                placeholder="https://facebook.com/...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-twitter text-info me-1"></i>Twitter / X URL</label>
                            <input type="url" class="form-control" name="contact_social_twitter"
                                value="{{ $settings['contact_social_twitter']->value ?? '' }}"
                                placeholder="https://twitter.com/...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-instagram text-danger me-1"></i>Instagram URL</label>
                            <input type="url" class="form-control" name="contact_social_instagram"
                                value="{{ $settings['contact_social_instagram']->value ?? '' }}"
                                placeholder="https://instagram.com/...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-youtube text-danger me-1"></i>YouTube URL</label>
                            <input type="url" class="form-control" name="contact_social_youtube"
                                value="{{ $settings['contact_social_youtube']->value ?? '' }}"
                                placeholder="https://youtube.com/...">
                        </div>
                    </div>

                    {{-- Departemen / Bagian --}}
                    <h6 class="fw-semibold mb-3 border-bottom pb-2"><i class="bi bi-diagram-2 me-1"></i>Departemen &amp; Kontak Khusus</h6>
                    <p class="text-muted small mb-3">Kelola daftar departemen atau bagian yang tampil di halaman Hubungi Kami.</p>

                    <input type="hidden" name="contact_departments" id="contactDepartmentsInput">

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle" id="deptTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:30%">Nama Bagian</th>
                                    <th style="width:30%">Email</th>
                                    <th>Fungsi / Deskripsi</th>
                                    <th style="width:60px"></th>
                                </tr>
                            </thead>
                            <tbody id="deptTableBody">
                                @php
                                    $depts = isset($settings['contact_departments'])
                                        ? json_decode($settings['contact_departments']->value, true)
                                        : null;
                                    if (!$depts) {
                                        $depts = [
                                            ['nama' => 'Bagian Website dan Portal',          'email' => 'website@kominfo.bukittinggi.go.id',       'fungsi' => 'Menangani perbaikan dan update website resmi SKPD'],
                                            ['nama' => 'Bagian Infrastructure dan Server',   'email' => 'infrastructure@kominfo.bukittinggi.go.id', 'fungsi' => 'Menangani maintenance server, database, dan jaringan'],
                                            ['nama' => 'Bagian Software dan Aplikasi',       'email' => 'software@kominfo.bukittinggi.go.id',       'fungsi' => 'Menangani pengembangan aplikasi dan software baru'],
                                            ['nama' => 'Bagian Support dan Troubleshooting', 'email' => 'support@kominfo.bukittinggi.go.id',        'fungsi' => 'Menangani keluhan teknis dan support end-user'],
                                        ];
                                    }
                                @endphp
                                @foreach ($depts as $d)
                                <tr>
                                    <td><input type="text" class="form-control form-control-sm dept-nama" value="{{ $d['nama'] }}"></td>
                                    <td><input type="email" class="form-control form-control-sm dept-email" value="{{ $d['email'] }}"></td>
                                    <td><input type="text" class="form-control form-control-sm dept-fungsi" value="{{ $d['fungsi'] }}"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-dept" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm mb-4" id="btnAddDept">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Bagian
                    </button>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Pengaturan Hubungi Kami
                        </button>
                    </div>

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

    // Tambah baris departemen baru
    document.getElementById('btnAddDept').addEventListener('click', function() {
        const tbody = document.getElementById('deptTableBody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" class="form-control form-control-sm dept-nama" placeholder="Nama Bagian"></td>
            <td><input type="email" class="form-control form-control-sm dept-email" placeholder="email@domain.go.id"></td>
            <td><input type="text" class="form-control form-control-sm dept-fungsi" placeholder="Deskripsi fungsi bagian"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-dept" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>
            </td>`;
        tbody.appendChild(row);
    });

    // Hapus baris (delegasi event)
    document.getElementById('deptTableBody').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-remove-dept');
        if (btn) btn.closest('tr').remove();
    });

    // Sebelum submit: serialisasi tabel departemen ke JSON
    document.querySelector('form').addEventListener('submit', function() {
        const rows = document.querySelectorAll('#deptTableBody tr');
        const data = Array.from(rows).map(row => ({
            nama:   row.querySelector('.dept-nama').value.trim(),
            email:  row.querySelector('.dept-email').value.trim(),
            fungsi: row.querySelector('.dept-fungsi').value.trim(),
        })).filter(d => d.nama || d.email);
        document.getElementById('contactDepartmentsInput').value = JSON.stringify(data);
    });
});
</script>
@endpush