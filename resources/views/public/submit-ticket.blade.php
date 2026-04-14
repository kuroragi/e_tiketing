@extends('layouts.landing')

@section('title', 'Buat Pengaduan - ' . Setting::get('app_name', 'Layanan Publik Kominfo'))

@push('styles')
    <style>
        .form-section {
            padding-top: 100px;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        }

        .form-card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .form-card .card-header {
            background: linear-gradient(135deg, var(--landing-primary), var(--landing-primary-dark));
            color: #fff;
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1.5rem 2rem;
        }

        .form-card .card-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
        }

        .form-label .text-danger {
            font-size: 0.8rem;
        }

        .info-sidebar .info-item {
            display: flex;
            align-items: start;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .info-sidebar .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .category-option {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .category-option:hover {
            border-color: var(--landing-primary);
            background: rgba(79, 70, 229, 0.03);
        }

        .category-option.selected {
            border-color: var(--landing-primary);
            background: rgba(79, 70, 229, 0.06);
        }

        .category-option input[type="radio"] {
            display: none;
        }
    </style>
@endpush

@section('content')
    <section class="form-section">
        <div class="container py-5">
            <div class="row justify-content-center g-4">
                <!-- Main Form -->
                <div class="col-lg-8">
                    <div class="form-card">
                        <div class="card-header"
                            @if ($selectedService) style="background: linear-gradient(135deg, {{ $selectedService['color'] }}, {{ $selectedService['color'] }}cc);" @endif>
                            <h4 class="mb-1">
                                <i
                                    class="bi {{ $selectedService ? $selectedService['icon'] : 'bi-pencil-square' }} me-2"></i>
                                {{ $selectedService ? 'Formulir: ' . $selectedService['title'] : 'Formulir Pengaduan Publik' }}
                            </h4>
                            <p class="mb-0 small opacity-75">Silakan lengkapi formulir di bawah ini. Semua data akan dijaga
                                kerahasiaannya.</p>
                        </div>
                        <div class="card-body">
                            @if ($selectedService)
                                <div class="alert alert-light border-start border-4 d-flex align-items-start gap-3 mb-4"
                                    style="border-color: {{ $selectedService['color'] }} !important; background: {{ $selectedService['color'] }}0d;">
                                    <span style="font-size:1.4rem; color:{{ $selectedService['color'] }};">
                                        <i class="bi {{ $selectedService['icon'] }}"></i>
                                    </span>
                                    <div>
                                        <strong
                                            style="color:{{ $selectedService['color'] }};">{{ $selectedService['title'] }}</strong>
                                        <p class="mb-0 small text-muted mt-1">{{ $selectedService['description'] }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Terjadi kesalahan:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('public.ticket.store') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Data Pelapor -->
                                <h5 class="fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-person text-primary me-2"></i>Data Pelapor
                                </h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="public_name"
                                            class="form-control @error('public_name') is-invalid @enderror"
                                            value="{{ old('public_name') }}" required placeholder="Nama lengkap Anda">
                                        @error('public_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">NIK <small class="text-muted">(opsional)</small></label>
                                        <input type="text" name="public_nik"
                                            class="form-control @error('public_nik') is-invalid @enderror"
                                            value="{{ old('public_nik') }}" placeholder="16 digit NIK" maxlength="16">
                                        @error('public_nik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="public_email"
                                            class="form-control @error('public_email') is-invalid @enderror"
                                            value="{{ old('public_email') }}" required placeholder="email@contoh.com">
                                        @error('public_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. HP / WhatsApp <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="public_phone"
                                            class="form-control @error('public_phone') is-invalid @enderror"
                                            value="{{ old('public_phone') }}" required placeholder="08xxxxxxxxxx">
                                        @error('public_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Alamat <small class="text-muted">(opsional)</small></label>
                                    <textarea name="public_address" class="form-control @error('public_address') is-invalid @enderror" rows="2"
                                        placeholder="Alamat lengkap Anda">{{ old('public_address') }}</textarea>
                                    @error('public_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Detail Pengaduan -->
                                <h5 class="fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-file-earmark-text text-primary me-2"></i>Detail Pengaduan
                                </h5>

                                <div class="mb-3">
                                    <label class="form-label">Kategori Layanan <span class="text-danger">*</span></label>
                                    <select name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('category_id', request('kategori')) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Judul Pengaduan <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" required
                                        placeholder="{{ $selectedService['title_placeholder'] ?? 'Contoh: Permintaan Data CCTV Terkait Kejadian di Jl. Ahmad Yani' }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Lengkap <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required
                                        placeholder="{{ $selectedService['desc_placeholder'] ?? 'Jelaskan secara detail pengaduan atau permintaan Anda...\n\nContoh: Saya ingin meminta rekaman CCTV pada tanggal 5 April 2026 pukul 14:00-16:00 WIB di persimpangan Jl. Ahmad Yani untuk keperluan pelaporan ke pihak kepolisian terkait kejadian pencurian.' }}">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimal 20 karakter. Semakin detail, semakin cepat kami dapat
                                        memproses.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                                    <select name="priority_id"
                                        class="form-select @error('priority_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Prioritas --</option>
                                        @foreach ($priorities as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('priority_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }} — {{ $p->description ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Lampiran <small class="text-muted">(opsional, maks 5
                                            file)</small></label>
                                    <input type="file" name="lampiran[]"
                                        class="form-control @error('lampiran.*') is-invalid @enderror" multiple
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    @error('lampiran.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Format: PDF, JPG, PNG. Maks 10 MB per file.</div>
                                </div>

                                <!-- Captcha sederhana -->
                                <div class="mb-4">
                                    <label class="form-label">Verifikasi <span class="text-danger">*</span></label>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge bg-dark px-3 py-2 fs-6"
                                            style="letter-spacing:3px;font-family:monospace;">
                                            {{ $captchaNum1 }} + {{ $captchaNum2 }} = ?
                                        </span>
                                        <input type="number" name="captcha_answer"
                                            class="form-control @error('captcha_answer') is-invalid @enderror"
                                            style="max-width:100px;" required placeholder="?">
                                        <input type="hidden" name="captcha_hash" value="{{ $captchaHash }}">
                                    </div>
                                    @error('captcha_answer')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <a href="{{ route('landing') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="bi bi-send me-2"></i>Kirim Pengaduan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4" style="border-radius:1rem;">
                        <div class="card-body p-4 info-sidebar">
                            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Penting
                            </h5>

                            <div class="info-item">
                                <div class="info-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <div>
                                    <strong class="d-block small">Kerahasiaan Data</strong>
                                    <small class="text-muted">Data pribadi Anda akan dijaga kerahasiaannya sesuai ketentuan
                                        yang berlaku.</small>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div>
                                    <strong class="d-block small">Waktu Respon</strong>
                                    <small class="text-muted">Pengaduan akan direspon dalam 1x24 jam hari kerja.</small>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-ticket-perforated"></i>
                                </div>
                                <div>
                                    <strong class="d-block small">Kode Tracking</strong>
                                    <small class="text-muted">Anda akan mendapatkan kode tracking setelah pengaduan
                                        terkirim untuk memantau status.</small>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-file-earmark-check"></i>
                                </div>
                                <div>
                                    <strong class="d-block small">Dokumen Pendukung</strong>
                                    <small class="text-muted">Siapkan foto/dokumen pendukung untuk mempercepat proses
                                        verifikasi.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Card -->
                    <div class="card border-0 shadow-sm" style="border-radius:1rem;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-headset text-primary me-2"></i>Butuh Bantuan?</h5>
                            <p class="text-muted small mb-3">Hubungi kami jika butuh bantuan dalam mengisi formulir
                                pengaduan.</p>
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="bi bi-telephone text-primary me-2"></i>
                                    {{ Setting::get('contact_phone', '(0752) 123-4567') }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-envelope text-primary me-2"></i>
                                    {{ Setting::get('contact_email', 'kominfo@bukittinggi.go.id') }}
                                </li>
                                <li>
                                    <i class="bi bi-clock text-primary me-2"></i>
                                    {{ Setting::get('contact_hours', 'Senin-Jumat 08:00-17:00 WIB') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
