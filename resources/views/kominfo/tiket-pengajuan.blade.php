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
                                <label for="skpd" class="form-label">SKPD/Unit Kerja *</label>
                                <select class="form-select @error('skpd') is-invalid @enderror" name="skpd"
                                    id="skpd" required>
                                    <option value="">Pilih SKPD...</option>
                                    @foreach ($skpdList ?? [] as $skpd)
                                        <option value="{{ $skpd['id'] }}"
                                            {{ old('skpd') == $skpd['id'] ? 'selected' : '' }}>
                                            {{ $skpd['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('skpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nama_pemohon" class="form-label">Nama Pemohon *</label>
                                <input type="text" class="form-control @error('nama_pemohon') is-invalid @enderror"
                                    name="nama_pemohon" id="nama_pemohon" value="{{ old('nama_pemohon') }}" required>
                                @error('nama_pemohon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror"
                                    name="jabatan" id="jabatan" value="{{ old('jabatan') }}">
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="kontak" class="form-label">No. Telepon/WA *</label>
                                <input type="tel" class="form-control @error('kontak') is-invalid @enderror"
                                    name="kontak" id="kontak" value="{{ old('kontak') }}" required>
                                @error('kontak')
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
                                <label for="judul" class="form-label">Judul/Ringkasan Pekerjaan *</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    name="judul" id="judul" value="{{ old('judul') }}"
                                    placeholder="Contoh: Perbaikan Jaringan Internet Kantor SKPD" required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="jenis_pekerjaan" class="form-label">Jenis Pekerjaan *</label>
                                <select class="form-select @error('jenis_pekerjaan') is-invalid @enderror"
                                    name="jenis_pekerjaan" id="jenis_pekerjaan" required>
                                    <option value="">Pilih Jenis...</option>
                                    @foreach ($jenisPekerjaan ?? [] as $jenis)
                                        <option value="{{ $jenis['id'] }}"
                                            {{ old('jenis_pekerjaan') == $jenis['id'] ? 'selected' : '' }}>
                                            {{ $jenis['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_pekerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="deskripsi" class="form-label">Deskripsi Lengkap Pekerjaan *</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi" rows="5"
                                    required
                                    placeholder="Jelaskan secara detail pekerjaan yang diminta, kondisi saat ini, dan hasil yang diharapkan...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Jelaskan sedetail mungkin agar tim Kominfo dapat memahami kebutuhan
                                    Anda</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="prioritas" class="form-label">Tingkat Prioritas *</label>
                                <select class="form-select @error('prioritas') is-invalid @enderror" name="prioritas"
                                    id="prioritas" required>
                                    <option value="">Pilih Prioritas...</option>
                                    <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>
                                        Rendah (Tidak Mendesak)
                                    </option>
                                    <option value="sedang" {{ old('prioritas') == 'sedang' ? 'selected' : '' }}>
                                        Sedang (Normal)
                                    </option>
                                    <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>
                                        Tinggi (Mendesak)
                                    </option>
                                </select>
                                @error('prioritas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="target_selesai" class="form-label">Target Waktu Penyelesaian</label>
                                <input type="date" class="form-control @error('target_selesai') is-invalid @enderror"
                                    name="target_selesai" id="target_selesai" value="{{ old('target_selesai') }}"
                                    min="{{ date('Y-m-d') }}">
                                @error('target_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Opsional - jika ada deadline khusus</div>
                            </div>
                            <div class="col-md-4">
                                <label for="lokasi" class="form-label">Lokasi Pekerjaan</label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                    name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                                    placeholder="Contoh: Ruang IT Lantai 2">
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            <div class="col-md-6">
                                <label for="lampiran" class="form-label">Upload File</label>
                                <input type="file" class="form-control @error('lampiran') is-invalid @enderror"
                                    name="lampiran" id="lampiran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                @error('lampiran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">File: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</div>
                            </div>
                            <div class="col-md-6">
                                <label for="catatan_tambahan" class="form-label">Catatan Tambahan</label>
                                <textarea class="form-control @error('catatan_tambahan') is-invalid @enderror" name="catatan_tambahan"
                                    id="catatan_tambahan" rows="3" placeholder="Informasi tambahan yang perlu diketahui...">{{ old('catatan_tambahan') }}</textarea>
                                @error('catatan_tambahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
            const deskripsi = document.getElementById('deskripsi');
            const charCounter = document.createElement('div');
            charCounter.className = 'form-text text-end';
            deskripsi.parentNode.appendChild(charCounter);

            deskripsi.addEventListener('input', function() {
                const length = this.value.length;
                charCounter.textContent = `${length}/1000 karakter`;

                if (length > 800) {
                    charCounter.className = 'form-text text-end text-warning';
                } else if (length > 900) {
                    charCounter.className = 'form-text text-end text-danger';
                } else {
                    charCounter.className = 'form-text text-end text-muted';
                }
            });

            // Initial count
            deskripsi.dispatchEvent(new Event('input'));

            // Priority helper
            const prioritas = document.getElementById('prioritas');
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
                const file = this.files[0];
                if (file) {
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    const allowedTypes = ['application/pdf', 'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg', 'image/jpg', 'image/png'
                    ];

                    if (file.size > maxSize) {
                        alert('Ukuran file terlalu besar. Maksimal 5MB.');
                        this.value = '';
                        return;
                    }

                    if (!allowedTypes.includes(file.type)) {
                        alert('Tipe file tidak didukung. Gunakan PDF, DOC, DOCX, JPG, atau PNG.');
                        this.value = '';
                        return;
                    }
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
