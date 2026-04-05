@extends('layouts.e-ticket')

@section('title', 'Pengaturan Landing Page - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pengaturan') }}">Pengaturan</a></li>
    <li class="breadcrumb-item active">Landing Page</li>
@endsection

@section('page-header')
    <h2 class="mb-1"><i class="bi bi-palette me-2"></i>Pengaturan Landing Page</h2>
    <p class="mb-0">Kustomisasi tampilan halaman depan publik</p>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.landing.save') }}">
        @csrf
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                <div class="list-group sticky-top" style="top: 80px;">
                    <a href="#hero" class="list-group-item list-group-item-action active">
                        <i class="bi bi-image me-2"></i>Hero Section
                    </a>
                    <a href="#warna" class="list-group-item list-group-item-action">
                        <i class="bi bi-palette me-2"></i>Warna & Branding
                    </a>
                    <a href="#layanan" class="list-group-item list-group-item-action">
                        <i class="bi bi-grid me-2"></i>Layanan Unggulan
                    </a>
                    <a href="#fitur" class="list-group-item list-group-item-action">
                        <i class="bi bi-toggles me-2"></i>Fitur Publik
                    </a>
                    <a href="#api" class="list-group-item list-group-item-action">
                        <i class="bi bi-plug me-2"></i>Pengaturan API
                    </a>
                </div>

                <div class="mt-3">
                    <a href="{{ route('landing') }}" target="_blank" class="btn btn-outline-primary w-100">
                        <i class="bi bi-eye me-2"></i>Lihat Landing Page
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">

                <!-- Hero Section Settings -->
                <div class="card mb-4" id="hero">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-image text-primary me-2"></i>Hero Section</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Hero</label>
                            <textarea class="form-control" name="landing_hero_title" rows="2"
                                placeholder="Layanan Pengaduan & Permintaan Data Publik">{{ $settings['landing_hero_title']->value ?? 'Layanan Pengaduan &
Permintaan Data Publik' }}</textarea>
                            <div class="form-text">Gunakan baris baru untuk memisahkan baris teks.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub-Judul Hero</label>
                            <textarea class="form-control" name="landing_hero_subtitle" rows="2"
                                placeholder="Sampaikan pengaduan, permintaan data CCTV, atau layanan lainnya secara online.">{{ $settings['landing_hero_subtitle']->value ?? 'Sampaikan pengaduan, permintaan data CCTV, atau layanan lainnya secara online. Cepat, transparan, dan dapat dilacak.' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Judul Section Layanan</label>
                            <input type="text" class="form-control" name="landing_services_title"
                                value="{{ $settings['landing_services_title']->value ?? 'Jenis Layanan yang Tersedia' }}"
                                placeholder="Jenis Layanan yang Tersedia">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub-Judul Section Layanan</label>
                            <input type="text" class="form-control" name="landing_services_subtitle"
                                value="{{ $settings['landing_services_subtitle']->value ?? 'Pilih jenis layanan sesuai kebutuhan Anda.' }}"
                                placeholder="Deskripsi singkat">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan
                        </button>
                    </div>
                </div>

                <!-- Warna & Branding -->
                <div class="card mb-4" id="warna">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-palette text-primary me-2"></i>Warna & Branding</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Warna Utama (Primary)</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" name="landing_primary_color"
                                        value="{{ $settings['landing_primary_color']->value ?? '#4f46e5' }}">
                                    <input type="text" class="form-control" id="primaryColorText"
                                        value="{{ $settings['landing_primary_color']->value ?? '#4f46e5' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Warna Utama Gelap</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" name="landing_primary_dark"
                                        value="{{ $settings['landing_primary_dark']->value ?? '#3730a3' }}">
                                    <input type="text" class="form-control" id="primaryDarkText"
                                        value="{{ $settings['landing_primary_dark']->value ?? '#3730a3' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i>
                            Perubahan warna akan langsung terlihat di halaman landing publik setelah disimpan.
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan
                        </button>
                    </div>
                </div>

                <!-- Layanan Unggulan -->
                <div class="card mb-4" id="layanan">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-grid text-primary me-2"></i>Layanan Unggulan</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Kelola daftar layanan yang ditampilkan di halaman landing. Kosongkan untuk menggunakan layanan default.
                        </p>

                        <input type="hidden" name="landing_services" id="landingServicesInput">

                        <div id="servicesContainer">
                            @php
                                $services = isset($settings['landing_services'])
                                    ? json_decode($settings['landing_services']->value, true)
                                    : null;
                                if (!$services) {
                                    $services = [
                                        ['icon' => 'bi-camera-video', 'title' => 'Permintaan Data CCTV', 'description' => 'Permintaan rekaman CCTV untuk keperluan investigasi, keamanan, atau bukti kejadian.', 'color' => '#6366f1', 'category_id' => ''],
                                        ['icon' => 'bi-hdd-network', 'title' => 'Gangguan Jaringan & Internet', 'description' => 'Laporkan gangguan jaringan internet atau infrastruktur telekomunikasi.', 'color' => '#0ea5e9', 'category_id' => ''],
                                        ['icon' => 'bi-globe2', 'title' => 'Informasi Website Resmi', 'description' => 'Permintaan update informasi atau pelaporan konten website pemerintah.', 'color' => '#10b981', 'category_id' => ''],
                                        ['icon' => 'bi-database', 'title' => 'Permintaan Data Publik', 'description' => 'Permintaan data statistik, data terbuka, atau informasi publik.', 'color' => '#f59e0b', 'category_id' => ''],
                                        ['icon' => 'bi-megaphone', 'title' => 'Pengaduan Layanan Publik', 'description' => 'Keluhan terkait layanan publik berbasis teknologi informasi.', 'color' => '#ef4444', 'category_id' => ''],
                                        ['icon' => 'bi-question-circle', 'title' => 'Pertanyaan & Konsultasi', 'description' => 'Ajukan pertanyaan atau konsultasi terkait layanan Kominfo.', 'color' => '#8b5cf6', 'category_id' => ''],
                                    ];
                                }
                            @endphp

                            @foreach($services as $idx => $svc)
                            <div class="card border mb-3 service-item">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong class="small text-muted">Layanan {{ $idx + 1 }}</strong>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-service">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-2">
                                            <label class="form-label small">Icon</label>
                                            <input type="text" class="form-control form-control-sm svc-icon"
                                                value="{{ $svc['icon'] }}" placeholder="bi-camera-video">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Judul</label>
                                            <input type="text" class="form-control form-control-sm svc-title"
                                                value="{{ $svc['title'] }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Deskripsi</label>
                                            <input type="text" class="form-control form-control-sm svc-desc"
                                                value="{{ $svc['description'] }}">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Warna</label>
                                            <input type="color" class="form-control form-control-sm form-control-color svc-color"
                                                value="{{ $svc['color'] }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Kategori</label>
                                            <select class="form-select form-select-sm svc-cat">
                                                <option value="">-- Pilih --</option>
                                                @foreach(\App\Models\Category::aktif()->get() as $cat)
                                                    <option value="{{ $cat->id }}" {{ ($svc['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="btnAddService">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Layanan
                        </button>

                        <div class="form-text mb-3">
                            <strong>Icon:</strong> Gunakan nama icon dari
                            <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>
                            (contoh: bi-camera-video, bi-database, bi-megaphone)
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan
                        </button>
                    </div>
                </div>

                <!-- Fitur Publik -->
                <div class="card mb-4" id="fitur">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-toggles text-primary me-2"></i>Fitur Publik</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="landing_enable_public_ticket"
                                    value="1" id="enablePublicTicket"
                                    {{ ($settings['landing_enable_public_ticket']->value ?? '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="enablePublicTicket">
                                    Aktifkan Pengaduan Publik
                                </label>
                            </div>
                            <div class="form-text">Mengizinkan masyarakat umum mengajukan pengaduan tanpa login.</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="landing_show_stats"
                                    value="1" id="showStats"
                                    {{ ($settings['landing_show_stats']->value ?? '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="showStats">
                                    Tampilkan Statistik di Landing Page
                                </label>
                            </div>
                            <div class="form-text">Menampilkan statistik pengaduan di bagian hero section.</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="landing_show_recent"
                                    value="1" id="showRecent"
                                    {{ ($settings['landing_show_recent']->value ?? '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="showRecent">
                                    Tampilkan Pengaduan Terbaru
                                </label>
                            </div>
                            <div class="form-text">Menampilkan daftar pengaduan terbaru dari publik di halaman landing.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan
                        </button>
                    </div>
                </div>

                <!-- API Settings -->
                <div class="card mb-4" id="api">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-plug text-primary me-2"></i>Pengaturan API</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="api_enabled"
                                    value="1" id="apiEnabled"
                                    {{ ($settings['api_enabled']->value ?? '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="apiEnabled">
                                    Aktifkan REST API
                                </label>
                            </div>
                            <div class="form-text">Mengizinkan aplikasi eksternal menggunakan API untuk membuat pengaduan.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">API Rate Limit <small class="text-muted">(request per menit)</small></label>
                            <input type="number" class="form-control" name="api_rate_limit" min="1" max="1000"
                                value="{{ $settings['api_rate_limit']->value ?? '30' }}" style="max-width:200px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">API Key</label>
                            <div class="input-group" style="max-width:500px;">
                                <input type="text" class="form-control" id="apiKeyDisplay"
                                    value="{{ $settings['api_key']->value ?? 'Belum dibuat' }}" readonly>
                                <button type="button" class="btn btn-outline-primary" onclick="generateApiKey()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Generate Baru
                                </button>
                            </div>
                            <input type="hidden" name="api_key" id="apiKeyInput"
                                value="{{ $settings['api_key']->value ?? '' }}">
                            <div class="form-text">API Key dibutuhkan oleh aplikasi eksternal untuk mengakses API.</div>
                        </div>

                        <div class="alert alert-light border small">
                            <strong><i class="bi bi-book me-1"></i>Dokumentasi API:</strong>
                            <ul class="mb-0 mt-1">
                                <li><code>GET /api/v1/categories</code> — Daftar kategori layanan</li>
                                <li><code>GET /api/v1/priorities</code> — Daftar prioritas</li>
                                <li><code>POST /api/v1/tickets</code> — Buat pengaduan baru</li>
                                <li><code>GET /api/v1/tickets/{tracking_code}</code> — Lacak status tiket</li>
                            </ul>
                            <p class="mt-2 mb-0">Semua request memerlukan header: <code>X-API-Key: {api_key}</code></p>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar smooth scroll
    document.querySelectorAll('.list-group-item').forEach(function(link) {
        link.addEventListener('click', function() {
            document.querySelectorAll('.list-group-item').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Color picker sync
    document.querySelectorAll('input[type="color"]').forEach(picker => {
        const textInput = picker.nextElementSibling;
        if (textInput && textInput.type === 'text') {
            picker.addEventListener('input', () => textInput.value = picker.value);
        }
    });

    // Add service
    document.getElementById('btnAddService').addEventListener('click', function() {
        const container = document.getElementById('servicesContainer');
        const count = container.querySelectorAll('.service-item').length + 1;
        const categories = @json(\App\Models\Category::aktif()->get(['id','name']));
        let opts = '<option value="">-- Pilih --</option>';
        categories.forEach(c => { opts += `<option value="${c.id}">${c.name}</option>`; });

        const card = document.createElement('div');
        card.className = 'card border mb-3 service-item';
        card.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <strong class="small text-muted">Layanan ${count}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-service"><i class="bi bi-trash"></i></button>
                </div>
                <div class="row g-2">
                    <div class="col-md-2">
                        <label class="form-label small">Icon</label>
                        <input type="text" class="form-control form-control-sm svc-icon" placeholder="bi-star">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Judul</label>
                        <input type="text" class="form-control form-control-sm svc-title" placeholder="Nama Layanan">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Deskripsi</label>
                        <input type="text" class="form-control form-control-sm svc-desc" placeholder="Deskripsi singkat">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small">Warna</label>
                        <input type="color" class="form-control form-control-sm form-control-color svc-color" value="#6366f1">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Kategori</label>
                        <select class="form-select form-select-sm svc-cat">${opts}</select>
                    </div>
                </div>
            </div>`;
        container.appendChild(card);
    });

    // Remove service
    document.getElementById('servicesContainer').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-remove-service');
        if (btn) btn.closest('.service-item').remove();
    });

    // Serialize services before submit
    document.querySelector('form').addEventListener('submit', function() {
        const items = document.querySelectorAll('.service-item');
        const data = Array.from(items).map(item => ({
            icon:        item.querySelector('.svc-icon').value.trim(),
            title:       item.querySelector('.svc-title').value.trim(),
            description: item.querySelector('.svc-desc').value.trim(),
            color:       item.querySelector('.svc-color').value,
            category_id: item.querySelector('.svc-cat').value,
        })).filter(s => s.title);
        document.getElementById('landingServicesInput').value = JSON.stringify(data);
    });
});

function generateApiKey() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let key = 'etk_';
    for (let i = 0; i < 40; i++) {
        key += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('apiKeyDisplay').value = key;
    document.getElementById('apiKeyInput').value = key;
}
</script>
@endpush
