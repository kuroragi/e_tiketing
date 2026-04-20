@extends('layouts.e-ticket')

@section('title', 'Pengajuan Tiket - Sistem Ticketing Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pengajuan Tiket</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-plus-circle me-2"></i>
        Pengajuan Tiket Pekerjaan
    </h2>
    <p class="mb-0">Ajukan permintaan bantuan IT kepada Dinas Kominfo Kota Bukittinggi</p>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square text-primary me-2"></i>
                        Form Pengajuan Tiket Baru
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Info SKPD (read-only, tidak perlu diisi ulang) --}}
                    <div class="alert alert-light border d-flex align-items-center gap-3 mb-4" style="border-radius:.6rem;">
                        <i class="bi bi-building text-primary fs-5 flex-shrink-0"></i>
                        <div class="small">
                            <span class="text-muted">Pengaju:</span>
                            <strong class="ms-1">{{ auth()->user()->name }}</strong>
                            <span class="text-muted ms-2">—</span>
                            <span class="ms-2">{{ auth()->user()->department->name ?? 'Tidak terdaftar' }}</span>
                        </div>
                    </div>

                    <form action="{{ route('tiket.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Jenis Pekerjaan & Lokasi -->
                        <div class="row mb-4">
                            <div class="col-md-7">
                                <label for="category_id" class="form-label">Jenis Pekerjaan <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                    name="category_id" id="category_id" required>
                                    <option value="">— Pilih Jenis Pekerjaan —</option>
                                    @foreach ($jenisKerjaan ?? [] as $jenis)
                                        <option value="{{ $jenis->id }}"
                                            {{ old('category_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label for="location" class="form-label">
                                    Lokasi Pekerjaan
                                    <small class="text-muted fw-normal">(opsional)</small>
                                </label>
                                <input type="text" class="form-control" name="location" id="location"
                                    value="{{ old('location') }}"
                                    placeholder="Contoh: Ruang IT Lantai 2">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                Detail Pengaduan / Permintaan
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                name="description" id="description" rows="6" required
                                placeholder="Jelaskan secara detail: kondisi yang terjadi, perangkat yang bermasalah, langkah yang sudah dilakukan, dan hasil yang diharapkan...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-between mt-1">
                                <div class="form-text">Jelaskan sedetail mungkin agar tim Kominfo dapat memahami kebutuhan Anda (min. 20 karakter).</div>
                                <div class="form-text text-end" id="char-count"></div>
                            </div>
                        </div>

                        <!-- Lampiran Drag & Drop -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-paperclip me-1"></i>Lampiran Pendukung
                                <small class="text-muted fw-normal">(opsional, maks 5 file)</small>
                            </label>

                            {{-- Hidden actual input --}}
                            <input type="file" name="lampiran[]" id="lampiran"
                                accept=".pdf,.jpg,.jpeg,.png" multiple class="d-none">

                            <div id="drop-zone"
                                class="rounded-3 p-4 text-center"
                                style="border: 2px dashed #ced4da; background:#f8f9fa; cursor:pointer; transition: border-color .2s, background .2s;">
                                <i class="bi bi-cloud-upload fs-2 text-secondary mb-2 d-block"></i>
                                <p class="mb-2 text-muted small">Seret & lepas file di sini, atau</p>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="document.getElementById('lampiran').click()">
                                    <i class="bi bi-folder2-open me-1"></i>Pilih File
                                </button>
                                <p class="small text-muted mt-2 mb-0">
                                    PDF, JPG, PNG &nbsp;·&nbsp; Maks 10 MB per file &nbsp;·&nbsp; Maks 5 file
                                </p>
                            </div>

                            <div id="file-list" class="mt-2"></div>

                            @error('lampiran')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('lampiran.*')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Terms & Submit -->
                        <div class="border rounded p-3 bg-light mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreement" required>
                                <label class="form-check-label small" for="agreement">
                                    Saya menyatakan bahwa informasi yang diberikan adalah benar dan saya memahami
                                    bahwa pekerjaan akan dilaksanakan sesuai dengan prioritas dan ketersediaan
                                    sumber daya Dinas Kominfo Kota Bukittinggi.
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-warning me-2">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i>Ajukan Tiket
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Help -->
        <div class="col-lg-4">
            <div class="card card-info">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Panduan Pengajuan
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Pilih jenis pekerjaan</strong><br>
                            <small class="text-muted">Pilih kategori yang paling sesuai dengan permasalahan Anda</small>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Deskripsikan secara detail</strong><br>
                            <small class="text-muted">Jelaskan kondisi yang terjadi dan hasil yang diharapkan</small>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Sertakan lampiran jika ada</strong><br>
                            <small class="text-muted">Screenshot error, foto perangkat, atau dokumen pendukung lainnya</small>
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Pantau status tiket</strong><br>
                            <small class="text-muted">Cek menu "Tiket Saya" untuk melihat progress penanganan</small>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card card-warning mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock me-2"></i>
                        Estimasi Waktu Penanganan
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <span class="badge" style="background:#dc3545;">Urgent</span>
                            Segera — gangguan kritis operasional
                        </li>
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fd7e14;">Tinggi</span>
                            1 hari kerja
                        </li>
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <span class="badge" style="background:#0dcaf0; color:#000;">Sedang</span>
                            3 hari kerja
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#198754;">Rendah</span>
                            7 hari kerja
                        </li>
                    </ul>
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Prioritas ditentukan oleh tim Kominfo sesuai dampak dan urgensi.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Character counter ─────────────────────────────────────────
    const deskripsi  = document.getElementById('description');
    const charCount  = document.getElementById('char-count');

    function updateCount() {
        const n = deskripsi.value.length;
        charCount.textContent = n + ' karakter';
        charCount.className   = 'form-text text-end ' + (n > 900 ? 'text-danger' : n > 700 ? 'text-warning' : 'text-muted');
    }
    deskripsi.addEventListener('input', updateCount);
    updateCount();

    // ── Drag & Drop uploader ──────────────────────────────────────
    const dropZone  = document.getElementById('drop-zone');
    const fileInput = document.getElementById('lampiran');
    const fileList  = document.getElementById('file-list');
    let   files     = [];

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
        dropZone.style.borderColor  = '#4f46e5';
        dropZone.style.background   = '#eef0ff';
    });
    dropZone.addEventListener('dragleave', function () {
        dropZone.style.borderColor  = '#ced4da';
        dropZone.style.background   = '#f8f9fa';
    });
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropZone.style.borderColor  = '#ced4da';
        dropZone.style.background   = '#f8f9fa';
        addFiles(e.dataTransfer.files);
    });
});
</script>
@endpush
