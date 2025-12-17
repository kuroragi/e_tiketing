@extends('layouts.app')

@section('title', 'Hubungi Kami - E-Ticketing System')

@section('content')
    <!-- Header Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-envelope text-primary me-3"></i>
                        Hubungi Kami
                    </h1>
                    <p class="lead text-muted">Kami siap membantu Anda 24/7. Jangan ragu untuk menghubungi kami!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-telephone display-5 text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Telepon</h5>
                            <p class="text-muted mb-3">Hubungi kami langsung untuk bantuan cepat</p>
                            <a href="tel:+6281234567890" class="btn btn-outline-primary">
                                <i class="bi bi-telephone me-2"></i>+62 812-3456-7890
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-envelope display-5 text-success"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Email</h5>
                            <p class="text-muted mb-3">Kirimkan email untuk pertanyaan detail</p>
                            <a href="mailto:info@eticketing.com" class="btn btn-outline-success">
                                <i class="bi bi-envelope me-2"></i>info@eticketing.com
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-whatsapp display-5 text-info"></i>
                            </div>
                            <h5 class="fw-bold mb-3">WhatsApp</h5>
                            <p class="text-muted mb-3">Chat dengan customer service kami</p>
                            <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-info">
                                <i class="bi bi-whatsapp me-2"></i>Chat WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Map Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-6 mb-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-4">Kirim Pesan</h3>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('contact.submit') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nama Lengkap *</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                            value="{{ old('email') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="subject" class="form-label">Subjek *</label>
                                        <select class="form-select" name="subject" id="subject" required>
                                            <option value="">Pilih subjek...</option>
                                            <option value="Pertanyaan Umum"
                                                {{ old('subject') === 'Pertanyaan Umum' ? 'selected' : '' }}>Pertanyaan Umum
                                            </option>
                                            <option value="Bantuan Pembelian Tiket"
                                                {{ old('subject') === 'Bantuan Pembelian Tiket' ? 'selected' : '' }}>Bantuan
                                                Pembelian Tiket</option>
                                            <option value="Masalah Pembayaran"
                                                {{ old('subject') === 'Masalah Pembayaran' ? 'selected' : '' }}>Masalah
                                                Pembayaran</option>
                                            <option value="Refund/Pembatalan"
                                                {{ old('subject') === 'Refund/Pembatalan' ? 'selected' : '' }}>
                                                Refund/Pembatalan</option>
                                            <option value="Masalah Teknis"
                                                {{ old('subject') === 'Masalah Teknis' ? 'selected' : '' }}>Masalah Teknis
                                            </option>
                                            <option value="Kerjasama Event Organizer"
                                                {{ old('subject') === 'Kerjasama Event Organizer' ? 'selected' : '' }}>
                                                Kerjasama Event Organizer</option>
                                            <option value="Lainnya" {{ old('subject') === 'Lainnya' ? 'selected' : '' }}>
                                                Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label">Pesan *</label>
                                        <textarea class="form-control" name="message" id="message" rows="5"
                                            placeholder="Tuliskan pesan Anda di sini..." required>{{ old('message') }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="privacyCheck" required>
                                            <label class="form-check-label" for="privacyCheck">
                                                Saya setuju dengan <a href="#" class="text-primary">kebijakan
                                                    privasi</a> *
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="bi bi-send me-2"></i>Kirim Pesan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Map & Office Info -->
                <div class="col-lg-6">
                    <!-- Map -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-0">
                            <div class="ratio ratio-16x9">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613507864!3d-6.194741395493371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b759%3A0x6b45e67356080477!2sMonas%2C%20Gambir%2C%20Kecamatan%20Gambir%2C%20Kota%20Jakarta%20Pusat%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sen!2sid!4v1639123456789!5m2!1sen!2sid"
                                    style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Office Info -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h4 class="fw-bold mb-4">
                                <i class="bi bi-building text-primary me-2"></i>
                                Kantor Kami
                            </h4>

                            <div class="mb-4">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                    Alamat
                                </h6>
                                <p class="text-muted">
                                    Jl. Gatot Subroto No. 123<br>
                                    Jakarta Selatan 12950<br>
                                    Indonesia
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-clock text-primary me-2"></i>
                                    Jam Operasional
                                </h6>
                                <ul class="list-unstyled text-muted mb-0">
                                    <li>Senin - Jumat: 09:00 - 18:00</li>
                                    <li>Sabtu: 09:00 - 15:00</li>
                                    <li>Minggu: Tutup</li>
                                    <li><strong class="text-dark">Customer Service Online: 24/7</strong></li>
                                </ul>
                            </div>

                            <div class="mb-0">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-people text-primary me-2"></i>
                                    Media Sosial
                                </h6>
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
                                    <a href="#" class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3">Pertanyaan yang Sering Diajukan</h2>
                    <p class="lead text-muted">Temukan jawaban atas pertanyaan umum di sini</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse1">
                                    Bagaimana cara membeli tiket?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Untuk membeli tiket, Anda bisa browse event yang tersedia, pilih event yang diinginkan,
                                    pilih jenis tiket, isi data pembeli, dan lakukan pembayaran. Tiket akan dikirim ke email
                                    Anda
                                    setelah pembayaran berhasil dikonfirmasi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse2">
                                    Metode pembayaran apa saja yang diterima?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Kami menerima berbagai metode pembayaran termasuk transfer bank (BCA, Mandiri, BNI,
                                    BRI),
                                    e-wallet (OVO, GoPay, DANA, ShopeePay), dan kartu kredit (Visa, Mastercard).
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse3">
                                    Bisakah saya refund/membatalkan tiket?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Kebijakan refund bergantung pada event organizer masing-masing. Umumnya, pembatalan bisa
                                    dilakukan maksimal 7 hari sebelum event dengan potong biaya administrasi. Silakan
                                    hubungi
                                    customer service untuk informasi lebih detail.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse4">
                                    Bagaimana cara menggunakan tiket saat event?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Anda bisa menunjukkan QR code yang ada di tiket elektronik melalui smartphone Anda,
                                    atau mencetak tiket PDF. Pastikan QR code dalam kondisi jelas dan mudah dibaca saat
                                    di-scan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse5">
                                    Bagaimana jika saya tidak menerima tiket via email?
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Silakan cek folder spam/junk mail terlebih dahulu. Jika masih belum menerima,
                                    hubungi customer service dengan menyertakan bukti pembayaran dan nomor order ID Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse6">
                                    Apakah bisa transfer tiket ke orang lain?
                                </button>
                            </h2>
                            <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya, beberapa jenis tiket bisa ditransfer ke orang lain. Namun hal ini bergantung pada
                                    kebijakan event organizer. Silakan hubungi customer service untuk bantuan proses
                                    transfer tiket.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-fill form for demo (remove in production)
            setTimeout(() => {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('demo') === 'true') {
                    document.getElementById('name').value = 'John Doe';
                    document.getElementById('email').value = 'john.doe@example.com';
                    document.getElementById('subject').value = 'Pertanyaan Umum';
                    document.getElementById('message').value =
                        'Halo, saya ingin bertanya tentang sistem ticketing Anda. Apakah ada diskon khusus untuk pembelian dalam jumlah banyak?';
                }
            }, 1000);

            // Form validation enhancement
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', function() {
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Mengirim...';
                submitBtn.disabled = true;
            });

            // Character counter for message
            const messageTextarea = document.getElementById('message');
            const charCounter = document.createElement('div');
            charCounter.className = 'text-muted small text-end mt-1';
            messageTextarea.parentNode.appendChild(charCounter);

            messageTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCounter.textContent = `${length}/500 karakter`;

                if (length > 500) {
                    charCounter.className = 'text-danger small text-end mt-1';
                } else if (length > 400) {
                    charCounter.className = 'text-warning small text-end mt-1';
                } else {
                    charCounter.className = 'text-muted small text-end mt-1';
                }
            });

            // Initial character count
            messageTextarea.dispatchEvent(new Event('input'));
        });
    </script>
@endpush
