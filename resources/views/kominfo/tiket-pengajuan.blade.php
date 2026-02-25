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
    <p class="mb-0">Ajukan permintaan bantuan pekerjaan kepada Dinas Kominfo</p>
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
                    <form action="{{ route('tiket.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Informasi Pemohon -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person-badge me-2"></i>
                                    Informasi Pemohon
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKPD/Unit Kerja</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->department->name ?? 'Tidak terdaftar' }}" readonly>
                                <div class="form-text">SKPD otomatis sesuai akun Anda</div>
                            </div>
                            <div class="col-md-6">
                                <label for="contact_pic" class="form-label">Nama / No. Telepon PIC *</label>
                                <input type="text" class="form-control @error('contact_pic') is-invalid @enderror"
                                    name="contact_pic" id="contact_pic" value="{{ old('contact_pic', auth()->user()->name) }}" required
                                    placeholder="Nama dan nomor WA yang bisa dihubungi">
                                @error('contact_pic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Detail Pekerjaan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-tools me-2"></i>
                                    Detail Pekerjaan yang Diminta
                                </h6>
                            </div>
                            <div class="col-md-8">
                                <label for="title" class="form-label">Judul/Ringkasan Pekerjaan *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    name="title" id="title" value="{{ old('title') }}"
                                    placeholder="Contoh: Perbaikan Jaringan Internet Kantor SKPD" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="category_id" class="form-label">Jenis Pekerjaan *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                    name="category_id" id="category_id" required>
                                    <option value="">Pilih Jenis...</option>
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
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="description" class="form-label">Deskripsi Lengkap Pekerjaan *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="5"
                                    required
                                    placeholder="Jelaskan secara detail pekerjaan yang diminta, kondisi saat ini, dan hasil yang diharapkan...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Jelaskan sedetail mungkin agar tim Kominfo dapat memahami kebutuhan Anda (min. 20 karakter)</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="priority_id" class="form-label">Tingkat Prioritas *</label>
                                <select class="form-select @error('priority_id') is-invalid @enderror" name="priority_id"
                                    id="priority_id" required>
                                    <option value="">Pilih Prioritas...</option>
                                    @foreach ($prioritasList ?? [] as $prioritas)
                                        <option value="{{ $prioritas->id }}"
                                            {{ old('priority_id') == $prioritas->id ? 'selected' : '' }}>
                                            {{ $prioritas->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priority_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="target_date" class="form-label">Target Waktu Penyelesaian</label>
                                <input type="date" class="form-control @error('target_date') is-invalid @enderror"
                                    name="target_date" id="target_date" value="{{ old('target_date') }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                @error('target_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Opsional - jika ada deadline khusus</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Lokasi Pekerjaan</label>
                                <input type="text" class="form-control"
                                    name="location" value="{{ old('location') }}"
                                    placeholder="Contoh: Ruang IT Lantai 2">
                            </div>
                        </div>

                        <!-- Lampiran -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-paperclip me-2"></i>
                                    Lampiran Pendukung
                                </h6>
                            </div>
                            <div class="col-md-12">
                                <label for="lampiran" class="form-label">Upload File (maks. 5 file)</label>
                                <input type="file" class="form-control @error('lampiran') @error('lampiran.*') is-invalid @enderror @enderror"
                                    name="lampiran[]" id="lampiran" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                @error('lampiran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('lampiran.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">File: PDF, JPG, PNG (Max: 10MB per file, maks. 5 file)</div>
                            </div>
                        </div>

                        <!-- Terms & Submit -->
                        <div class="row">
                            <div class="col-12">
                                <div class="border rounded p-3 bg-light mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agreement" required>
                                        <label class="form-check-label" for="agreement">
                                            Saya menyatakan bahwa informasi yang diberikan adalah benar dan saya memahami
                                            bahwa pekerjaan akan dilaksanakan sesuai dengan prioritas dan ketersediaan
                                            sumber daya Dinas Kominfo Kota Bukittinggi. *
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Kembali
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-outline-warning me-2">
                                            <i class="bi bi-arrow-clockwise me-2"></i>Reset Form
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send me-2"></i>Ajukan Tiket
                                        </button>
                                    </div>
                                </div>
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
                            <strong>Isi form dengan lengkap</strong><br>
                            <small class="text-muted">Berikan informasi detail agar tim dapat memahami kebutuhan</small>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Pilih prioritas sesuai kebutuhan</strong><br>
                            <small class="text-muted">Prioritas tinggi untuk pekerjaan yang mendesak</small>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Sertakan lampiran jika diperlukan</strong><br>
                            <small class="text-muted">Screenshot error, dokumen spesifikasi, dll.</small>
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Pantau status tiket</strong><br>
                            <small class="text-muted">Cek menu "Tiket Saya" untuk melihat progress</small>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card card-warning mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock me-2"></i>
                        Estimasi Waktu
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-success">
                                <i class="bi bi-flag display-6"></i>
                                <div><strong>1-2</strong></div>
                                <small>Hari Kerja<br>Prioritas Tinggi</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-warning">
                                <i class="bi bi-flag display-6"></i>
                                <div><strong>3-5</strong></div>
                                <small>Hari Kerja<br>Prioritas Sedang</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-info">
                                <i class="bi bi-flag display-6"></i>
                                <div><strong>1-2</strong></div>
                                <small>Minggu<br>Prioritas Rendah</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Estimasi dapat berubah sesuai kompleksitas pekerjaan
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Character counter for description
            const deskripsi = document.getElementById('description');
            const charCounter = document.createElement('div');
            charCounter.className = 'form-text text-end';
            deskripsi.parentNode.appendChild(charCounter);

            deskripsi.addEventListener('input', function() {
                const length = this.value.length;
                charCounter.textContent = `${length}/1000 karakter`;

                if (length > 900) {
                    charCounter.className = 'form-text text-end text-danger';
                } else if (length > 800) {
                    charCounter.className = 'form-text text-end text-warning';
                } else {
                    charCounter.className = 'form-text text-end text-muted';
                }
            });

            // Initial count
            deskripsi.dispatchEvent(new Event('input'));

            // Priority helper
            const prioritas = document.getElementById('priority_id');
            prioritas.addEventListener('change', function() {
                const helpTexts = {
                    'rendah': 'Pekerjaan tidak mendesak, dapat dikerjakan sesuai jadwal normal',
                    'sedang': 'Pekerjaan dengan prioritas normal dalam antrian',
                    'tinggi': 'Pekerjaan mendesak yang memerlukan penanganan segera'
                };

                let helpEl = document.getElementById('priority-help');
                if (!helpEl) {
                    helpEl = document.createElement('div');
                    helpEl.id = 'priority-help';
                    helpEl.className = 'form-text';
                    prioritas.parentNode.appendChild(helpEl);
                }

                helpEl.textContent = helpTexts[this.value] || '';
            });

            // File upload validation
            const lampiran = document.getElementById('lampiran');
            lampiran.addEventListener('change', function() {
                const files = Array.from(this.files);
                const maxSize = 10 * 1024 * 1024; // 10MB
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];

                for (const file of files) {
                    if (file.size > maxSize) {
                        alert(`File "${file.name}" terlalu besar. Maksimal 10MB per file.`);
                        this.value = '';
                        return;
                    }
                    if (!allowedTypes.includes(file.type)) {
                        alert(`Tipe file "${file.name}" tidak didukung. Gunakan PDF, JPG, atau PNG.`);
                        this.value = '';
                        return;
                    }
                }
                if (files.length > 5) {
                    alert('Maksimal 5 file lampiran.');
                    this.value = '';
                }
            });

            // Form validation enhancement
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi (*)');
                }
            });
        });
    </script>
@endpush
