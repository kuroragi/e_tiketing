@extends('layouts.e-ticket')

@section('title', 'Hubungi Kami - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Hubungi Kami</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-headset me-2"></i>
        Hubungi Kami
    </h2>
    <p class="mb-0">Tim support kami siap membantu Anda</p>
@endsection

@section('content')
    <div class="row">
        <!-- Contact Information -->
        <div class="col-lg-4 mb-4">
            <h5 class="mb-4">Informasi Kontak</h5>

            @foreach ($kontakInfo as $info)
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body d-flex">
                        <div class="flex-shrink-0">
                            <i class="bi {{ $info['icon'] }} display-5 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title">{{ $info['label'] }}</h6>
                            <p class="card-text text-muted mb-0">{{ $info['nilai'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Social Media -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Media Sosial</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departments -->
        <div class="col-lg-8">
            <h5 class="mb-4">Departemen dan Kontak Khusus</h5>

            <div class="row">
                @foreach ($departments as $dept)
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-diagram-2 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-1">{{ $dept['nama'] }}</h6>
                                        <a href="mailto:{{ $dept['email'] }}"
                                            class="small text-primary text-decoration-none">{{ $dept['email'] }}</a>
                                    </div>
                                </div>
                                <p class="text-muted small">{{ $dept['fungsi'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Contact Form -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-envelope me-2"></i>
                        Kirim Pesan Langsung
                    </h6>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" placeholder="Masukkan nama Anda"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Masukkan email Anda"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="skpd" class="form-label">SKPD/Instansi</label>
                            <input type="text" class="form-control" id="skpd" placeholder="Masukkan nama SKPD Anda"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="subjek" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subjek" placeholder="Masukkan subjek pesan"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea class="form-control" id="pesan" rows="5" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori Pertanyaan</label>
                            <select class="form-select" id="kategori" required>
                                <option selected disabled>Pilih kategori...</option>
                                <option value="teknis">Masalah Teknis</option>
                                <option value="panduan">Panduan Penggunaan</option>
                                <option value="akun">Masalah Akun</option>
                                <option value="saran">Saran dan Masukan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Response Time Info -->
            <div class="alert alert-info mt-4" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Waktu Respons:</strong> Kami biasanya merespons pertanyaan Anda dalam waktu 1-2 jam kerja. Untuk
                masalah urgent, silakan hubungi langsung melalui telepon.
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            showToast('Pesan Anda berhasil dikirim. Terima kasih telah menghubungi kami!', 'success');
            this.reset();
        });

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} alert-dismissible position-fixed top-0 end-0 m-3`;
            toast.style.zIndex = '9999';
            toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 4000);
        }
    </script>
@endpush
