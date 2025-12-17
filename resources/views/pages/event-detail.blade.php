@extends('layouts.app')

@section('title', $event['title'] . ' - E-Ticketing System')

@section('content')
    <!-- Breadcrumb -->
    <section class="py-3 bg-light">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('events') }}">Events</a></li>
                    <li class="breadcrumb-item active">{{ $event['title'] }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Event Detail Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Event Image & Basic Info -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <img src="{{ $event['image'] }}" class="card-img-top" alt="{{ $event['title'] }}"
                            style="height: 400px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge-custom fs-6">{{ $event['category'] }}</span>
                                <div class="text-end">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-people me-1"></i>
                                        <span class="small">{{ $event['available_tickets'] }}/{{ $event['total_tickets'] }}
                                            tiket tersisa</span>
                                    </div>
                                    <div class="progress mt-1" style="height: 6px; width: 120px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ ($event['available_tickets'] / $event['total_tickets']) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h1 class="display-6 fw-bold mb-3">{{ $event['title'] }}</h1>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-calendar3 text-primary fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Tanggal & Waktu</h6>
                                            <p class="text-muted mb-0">
                                                {{ \Carbon\Carbon::parse($event['date'])->format('l, d F Y') }}</p>
                                            <p class="text-muted mb-0">{{ $event['time'] }} WIB</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-geo-alt text-success fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Lokasi</h6>
                                            <p class="text-muted mb-0">{{ $event['location'] }}</p>
                                            <p class="text-muted mb-0 small">{{ $event['address'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-person-check text-warning fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Penyelenggara</h6>
                                            <p class="text-muted mb-0">{{ $event['organizer'] }}</p>
                                            <p class="text-muted mb-0 small">{{ $event['contact'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-cash text-info fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Harga Mulai Dari</h6>
                                            <p class="price-tag mb-0">Rp {{ number_format($event['price'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event Description -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="fw-bold mb-3">Deskripsi Event</h3>
                            <p class="text-muted lh-lg">{{ $event['description'] }}</p>

                            <h4 class="fw-bold mb-3 mt-4">Fasilitas yang Tersedia</h4>
                            <div class="row">
                                @foreach ($event['features'] as $feature)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <span>{{ $feature }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Types -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="fw-bold mb-4">Jenis Tiket</h3>

                            @foreach ($event['ticket_types'] as $ticket)
                                <div class="card mb-3 border">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h5 class="fw-bold mb-2">{{ $ticket['type'] }}</h5>
                                                <p class="price-tag mb-2">Rp
                                                    {{ number_format($ticket['price'], 0, ',', '.') }}</p>
                                                <div class="mb-0">
                                                    <strong class="text-muted">Termasuk:</strong>
                                                    <ul class="list-unstyled ms-3 mb-0">
                                                        @foreach ($ticket['benefits'] as $benefit)
                                                            <li class="d-flex align-items-center mb-1">
                                                                <i class="bi bi-check text-success me-2"></i>
                                                                <span class="small">{{ $benefit }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <a href="{{ route('purchase.ticket', $event['id']) }}?type={{ urlencode($ticket['type']) }}"
                                                    class="btn btn-primary">
                                                    <i class="bi bi-cart-plus me-1"></i>Pilih Tiket
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Action Card -->
                    <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 1rem;">
                        <div class="card-body text-center">
                            <img src="{{ $event['image'] }}" class="card-img-top mb-3" alt="{{ $event['title'] }}"
                                style="height: 150px; object-fit: cover; border-radius: 8px;">

                            <h5 class="fw-bold mb-2">{{ $event['title'] }}</h5>
                            <p class="text-muted mb-3">{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }} -
                                {{ $event['time'] }}</p>

                            <div class="mb-3">
                                <span class="price-tag fs-4">Rp {{ number_format($event['price'], 0, ',', '.') }}</span>
                                <p class="text-muted small mb-0">Mulai dari</p>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <a href="{{ route('purchase.ticket', $event['id']) }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-cart-plus me-2"></i>Beli Tiket Sekarang
                                </a>
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#shareModal">
                                    <i class="bi bi-share me-2"></i>Bagikan Event
                                </button>
                            </div>

                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="fw-bold text-success mb-0">{{ $event['available_tickets'] }}</h6>
                                        <small class="text-muted">Tiket Tersisa</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-bold text-primary mb-0">{{ $event['category'] }}</h6>
                                    <small class="text-muted">Kategori</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Need Help Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-headset text-primary me-2"></i>
                                Butuh Bantuan?
                            </h5>
                            <p class="text-muted mb-3">Tim customer service kami siap membantu Anda 24/7.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-envelope me-2"></i>Hubungi Kami
                                </a>
                                <a href="https://wa.me/6281234567890" class="btn btn-success" target="_blank">
                                    <i class="bi bi-whatsapp me-2"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Bagikan Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Bagikan event ini kepada teman-teman Anda:</p>

                    <div class="row g-3">
                        <div class="col-6">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                target="_blank" class="btn btn-primary w-100">
                                <i class="bi bi-facebook me-2"></i>Facebook
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($event['title']) }}"
                                target="_blank" class="btn btn-info w-100">
                                <i class="bi bi-twitter me-2"></i>Twitter
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="https://wa.me/?text={{ urlencode($event['title'] . ' - ' . request()->fullUrl()) }}"
                                target="_blank" class="btn btn-success w-100">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-secondary w-100"
                                onclick="copyToClipboard('{{ request()->fullUrl() }}')">
                                <i class="bi bi-link-45deg me-2"></i>Copy Link
                            </button>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="shareUrl" class="form-label">URL Event:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="shareUrl"
                                value="{{ request()->fullUrl() }}" readonly>
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="copyToClipboard('{{ request()->fullUrl() }}')">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const Toast = {
                    show: function(message, type = 'success') {
                        const toast = document.createElement('div');
                        toast.className =
                            `alert alert-${type} alert-dismissible position-fixed top-0 end-0 m-3`;
                        toast.style.zIndex = '9999';
                        toast.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                        document.body.appendChild(toast);

                        setTimeout(() => {
                            toast.remove();
                        }, 3000);
                    }
                };

                Toast.show('<i class="bi bi-check-circle me-2"></i>Link berhasil disalin!');
            });
        }
    </script>
@endpush
