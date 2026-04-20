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
                                <input type="hidden" name="layanan" value="{{ $layanan ?? '' }}">

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
                                @if (($layanan ?? '') === 'cctv')
                                {{-- ══════════════════ FORM PERMINTAAN DATA CCTV ══════════════════ --}}
                                <h5 class="fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-camera-video text-primary me-2"></i>Detail Permintaan CCTV
                                </h5>

                                {{-- Kategori CCTV otomatis --}}
                                @if($cctvCategory ?? null)
                                    <input type="hidden" name="category_id" value="{{ $cctvCategory->id }}">
                                    <div class="alert alert-info d-flex align-items-center gap-2 mb-3 py-2">
                                        <i class="bi bi-camera-video-fill fs-5"></i>
                                        <div class="small">
                                            <strong>Kategori:</strong> {{ $cctvCategory->name }}
                                            <span class="text-muted ms-1">— dipilih otomatis</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_kejadian"
                                            class="form-control @error('tanggal_kejadian') is-invalid @enderror"
                                            value="{{ old('tanggal_kejadian') }}"
                                            max="{{ date('Y-m-d') }}" required>
                                        @error('tanggal_kejadian')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Daerah / Area Kejadian <span class="text-danger">*</span></label>
                                        <input type="text" name="daerah_kejadian"
                                            class="form-control @error('daerah_kejadian') is-invalid @enderror"
                                            value="{{ old('daerah_kejadian') }}" required
                                            placeholder="Contoh: Jl. Ahmad Yani, Pasar Atas">
                                        @error('daerah_kejadian')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Titik Lokasi CCTV
                                        <small class="text-muted fw-normal">(opsional — landmark atau titik terdekat)</small>
                                    </label>
                                    <input type="text" name="lokasi_cctv"
                                        class="form-control @error('lokasi_cctv') is-invalid @enderror"
                                        value="{{ old('lokasi_cctv') }}"
                                        placeholder="Contoh: Depan Kantor Pos, Persimpangan Lampu Merah ...">
                                    @error('lokasi_cctv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Perkiraan Waktu Awal <span class="text-danger">*</span></label>
                                        <input type="time" name="waktu_awal"
                                            class="form-control @error('waktu_awal') is-invalid @enderror"
                                            value="{{ old('waktu_awal') }}" required>
                                        @error('waktu_awal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Perkiraan Waktu Akhir
                                            <small class="text-muted fw-normal">(opsional)</small>
                                        </label>
                                        <input type="time" name="waktu_akhir"
                                            class="form-control @error('waktu_akhir') is-invalid @enderror"
                                            value="{{ old('waktu_akhir') }}">
                                        @error('waktu_akhir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keperluan Permintaan <span class="text-danger">*</span></label>
                                    <select name="keperluan"
                                        class="form-select @error('keperluan') is-invalid @enderror" required>
                                        <option value="">-- Pilih Keperluan --</option>
                                        <option value="Laporan Kepolisian" {{ old('keperluan') === 'Laporan Kepolisian' ? 'selected' : '' }}>Laporan Kepolisian</option>
                                        <option value="Penyelidikan / Investigasi" {{ old('keperluan') === 'Penyelidikan / Investigasi' ? 'selected' : '' }}>Penyelidikan / Investigasi</option>
                                        <option value="Keperluan Asuransi" {{ old('keperluan') === 'Keperluan Asuransi' ? 'selected' : '' }}>Keperluan Asuransi</option>
                                        <option value="Kepentingan Instansi Pemerintah" {{ old('keperluan') === 'Kepentingan Instansi Pemerintah' ? 'selected' : '' }}>Kepentingan Instansi Pemerintah</option>
                                        <option value="Keperluan Lainnya" {{ old('keperluan') === 'Keperluan Lainnya' ? 'selected' : '' }}>Keperluan Lainnya</option>
                                    </select>
                                    @error('keperluan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Instansi / Lembaga
                                            <small class="text-muted fw-normal">(opsional)</small>
                                        </label>
                                        <input type="text" name="nama_instansi"
                                            class="form-control @error('nama_instansi') is-invalid @enderror"
                                            value="{{ old('nama_instansi') }}"
                                            placeholder="Contoh: Polres Bukittinggi, PT. XYZ">
                                        @error('nama_instansi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            No. Surat / No. Laporan Polisi
                                            <small class="text-muted fw-normal">(opsional)</small>
                                        </label>
                                        <input type="text" name="nomor_laporan"
                                            class="form-control @error('nomor_laporan') is-invalid @enderror"
                                            value="{{ old('nomor_laporan') }}"
                                            placeholder="Contoh: LP/123/IV/2026/...">
                                        @error('nomor_laporan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keterangan Tambahan <small class="text-muted fw-normal">(opsional)</small></label>
                                    <textarea name="keterangan"
                                        class="form-control @error('keterangan') is-invalid @enderror" rows="4"
                                        placeholder="Jelaskan kronologi kejadian, ciri-ciri pelaku/kendaraan, atau informasi lain yang relevan...">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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

                                @else
                                {{-- ══════════════════ FORM PENGADUAN LAYANAN KOMINFO ══════════════════ --}}
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

                                @endif
                                {{-- ══════════════════ LAMPIRAN (drag-and-drop) ══════════════════ --}}
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-paperclip me-1"></i>Lampiran
                                        <small class="text-muted fw-normal">(opsional, maks 5 file)</small>
                                    </label>

                                    {{-- Hidden actual input --}}
                                    <input type="file" name="lampiran[]" id="pub-lampiran"
                                        accept=".pdf,.jpg,.jpeg,.png" multiple class="d-none">

                                    <div id="pub-drop-zone"
                                        class="rounded-3 p-4 text-center"
                                        style="border: 2px dashed #ced4da; background:#f8f9fa; cursor:pointer; transition: border-color .2s, background .2s;">
                                        <i class="bi bi-cloud-upload fs-2 text-secondary mb-2 d-block"></i>
                                        <p class="mb-2 text-muted small">Seret &amp; lepas file di sini, atau</p>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('pub-lampiran').click()">
                                            <i class="bi bi-folder2-open me-1"></i>Pilih File
                                        </button>
                                        <p class="small text-muted mt-2 mb-0">
                                            PDF, JPG, PNG &nbsp;·&nbsp; Maks 10 MB per file &nbsp;·&nbsp; Maks 5 file
                                        </p>
                                    </div>

                                    <div id="pub-file-list" class="mt-2"></div>

                                    @error('lampiran')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    @error('lampiran.*')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
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
                                        @if (($layanan ?? '') === 'cctv')
                                            <i class="bi bi-send me-2"></i>Kirim Permintaan CCTV
                                        @else
                                            <i class="bi bi-send me-2"></i>Kirim Pengaduan
                                        @endif
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropZone  = document.getElementById('pub-drop-zone');
    const fileInput = document.getElementById('pub-lampiran');
    const fileList  = document.getElementById('pub-file-list');
    if (!dropZone || !fileInput || !fileList) return;

    let files = [];
    const MAX_FILES = 5;
    const MAX_SIZE  = 10 * 1024 * 1024;
    const ALLOWED   = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];

    function renderFileList() {
        fileList.innerHTML = '';
        files.forEach(function (file, i) {
            const row = document.createElement('div');
            row.className = 'd-flex align-items-center gap-2 mt-1 p-2 border rounded bg-white small';
            const ext = file.name.split('.').pop().toUpperCase();
            row.innerHTML =
                '<span class="badge bg-secondary">' + ext + '</span>' +
                '<span class="flex-grow-1 text-truncate">' + file.name + '</span>' +
                '<span class="text-muted text-nowrap">' + (file.size / 1024 / 1024).toFixed(1) + ' MB</span>' +
                '<button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 lh-1" data-i="' + i + '">' +
                '<i class="bi bi-x"></i></button>';
            fileList.appendChild(row);
        });
        fileList.querySelectorAll('[data-i]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                files.splice(parseInt(this.dataset.i), 1);
                syncInput();
                renderFileList();
            });
        });
    }

    function syncInput() {
        const dt = new DataTransfer();
        files.forEach(function (f) { dt.items.add(f); });
        fileInput.files = dt.files;
    }

    function addFiles(incoming) {
        Array.from(incoming).forEach(function (f) {
            if (files.length >= MAX_FILES) {
                alert('Maksimal ' + MAX_FILES + ' file lampiran.');
                return;
            }
            if (f.size > MAX_SIZE) {
                alert('"' + f.name + '" terlalu besar. Maks 10 MB per file.');
                return;
            }
            if (!ALLOWED.includes(f.type)) {
                alert('"' + f.name + '" tidak didukung. Gunakan PDF, JPG, atau PNG.');
                return;
            }
            files.push(f);
        });
        syncInput();
        renderFileList();
    }

    // Click to browse
    dropZone.addEventListener('click', function () { fileInput.click(); });
    fileInput.addEventListener('change', function () {
        addFiles(fileInput.files);
        fileInput.value = '';
    });

    // Drag events
    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropZone.style.borderColor = '#4f46e5';
        dropZone.style.background  = '#eef0ff';
    });
    dropZone.addEventListener('dragleave', function () {
        dropZone.style.borderColor = '#ced4da';
        dropZone.style.background  = '#f8f9fa';
    });
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropZone.style.borderColor = '#ced4da';
        dropZone.style.background  = '#f8f9fa';
        addFiles(e.dataTransfer.files);
    });
});
</script>
@endpush
