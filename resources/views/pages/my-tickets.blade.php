@extends('layouts.app')

@section('title', 'Tiket Saya - E-Ticketing System')

@section('content')
    <!-- Header Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-ticket-perforated text-primary me-3"></i>
                        Tiket Saya
                    </h1>
                    <p class="lead text-muted">Kelola dan lihat semua tiket yang Anda miliki</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-3 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <label class="form-label me-3 mb-0">Filter Status:</label>
                        <select class="form-select" id="statusFilter" style="width: auto;">
                            <option value="all">Semua Status</option>
                            <option value="Active">Aktif</option>
                            <option value="Used">Sudah Digunakan</option>
                            <option value="Expired">Kadaluarsa</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <span class="text-muted">Total: <strong id="ticketCount">{{ count($tickets) }}</strong> tiket</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Tickets Section -->
    <section class="py-5">
        <div class="container">
            @if (count($tickets) > 0)
                <div id="ticketsContainer">
                    @foreach ($tickets as $ticket)
                        <div class="card mb-4 ticket-card" data-status="{{ $ticket['status'] }}">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Ticket Info -->
                                    <div class="col-lg-8">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h4 class="fw-bold mb-1">{{ $ticket['event_title'] }}</h4>
                                                <span class="badge bg-primary">{{ $ticket['ticket_type'] }}</span>
                                                <span
                                                    class="badge ms-2 
                                            {{ $ticket['status'] === 'Active' ? 'bg-success' : '' }}
                                            {{ $ticket['status'] === 'Used' ? 'bg-secondary' : '' }}
                                            {{ $ticket['status'] === 'Expired' ? 'bg-danger' : '' }}">
                                                    {{ $ticket['status'] }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="price-tag mb-0">Rp
                                                    {{ number_format($ticket['total_price'], 0, ',', '.') }}</h5>
                                                <small class="text-muted">{{ $ticket['quantity'] }} tiket</small>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                                    <span>{{ \Carbon\Carbon::parse($ticket['date'])->format('d M Y') }} -
                                                        {{ $ticket['time'] }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                                    <span>{{ $ticket['location'] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bi bi-receipt text-success me-2"></i>
                                                    <span>ID: {{ $ticket['id'] }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-calendar-plus text-success me-2"></i>
                                                    <span>Dibeli:
                                                        {{ \Carbon\Carbon::parse($ticket['purchase_date'])->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="d-flex gap-2 flex-wrap">
                                            @if ($ticket['status'] === 'Active')
                                                <button class="btn btn-primary btn-sm"
                                                    onclick="showTicketDetail('{{ $ticket['id'] }}')">
                                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                                </button>
                                                <button class="btn btn-success btn-sm"
                                                    onclick="downloadTicket('{{ $ticket['id'] }}')">
                                                    <i class="bi bi-download me-1"></i>Download PDF
                                                </button>
                                                <button class="btn btn-info btn-sm"
                                                    onclick="shareTicket('{{ $ticket['id'] }}')">
                                                    <i class="bi bi-share me-1"></i>Bagikan
                                                </button>
                                            @elseif($ticket['status'] === 'Used')
                                                <button class="btn btn-outline-secondary btn-sm"
                                                    onclick="showTicketDetail('{{ $ticket['id'] }}')">
                                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                                </button>
                                                <button class="btn btn-outline-primary btn-sm"
                                                    onclick="downloadReceipt('{{ $ticket['id'] }}')">
                                                    <i class="bi bi-receipt me-1"></i>Download Kwitansi
                                                </button>
                                            @else
                                                <button class="btn btn-outline-secondary btn-sm"
                                                    onclick="showTicketDetail('{{ $ticket['id'] }}')">
                                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- QR Code -->
                                    <div class="col-lg-4 text-center">
                                        <div class="border rounded p-3">
                                            @if ($ticket['status'] === 'Active')
                                                <img src="{{ $ticket['qr_code'] }}" alt="QR Code" class="img-fluid mb-2"
                                                    style="max-width: 150px;">
                                                <p class="small text-muted mb-0">Tunjukkan QR code ini saat masuk event</p>
                                            @else
                                                <div class="d-flex align-items-center justify-content-center"
                                                    style="height: 150px;">
                                                    <div class="text-center">
                                                        <i class="bi bi-qr-code display-4 text-muted mb-2"></i>
                                                        <p class="small text-muted mb-0">
                                                            {{ $ticket['status'] === 'Used' ? 'Tiket sudah digunakan' : 'Tiket kadaluarsa' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="bi bi-ticket-perforated display-1 text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">Belum Ada Tiket</h3>
                    <p class="text-muted mb-4">Anda belum memiliki tiket apapun. Yuk, beli tiket event favorit Anda!</p>
                    <a href="{{ route('events') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-search me-2"></i>Jelajahi Event
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Statistics Section -->
    @if (count($tickets) > 0)
        <section class="py-5 bg-light">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <i class="bi bi-ticket display-4 text-primary mb-3"></i>
                                <h3 class="fw-bold text-primary">{{ count($tickets) }}</h3>
                                <p class="text-muted mb-0">Total Tiket</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <i class="bi bi-check-circle display-4 text-success mb-3"></i>
                                <h3 class="fw-bold text-success">
                                    {{ collect($tickets)->where('status', 'Active')->count() }}</h3>
                                <p class="text-muted mb-0">Tiket Aktif</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <i class="bi bi-calendar-check display-4 text-warning mb-3"></i>
                                <h3 class="fw-bold text-warning">{{ collect($tickets)->where('status', 'Used')->count() }}
                                </h3>
                                <p class="text-muted mb-0">Sudah Digunakan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <i class="bi bi-cash display-4 text-info mb-3"></i>
                                <h3 class="fw-bold text-info">Rp
                                    {{ number_format(collect($tickets)->sum('total_price'), 0, ',', '.') }}</h3>
                                <p class="text-muted mb-0">Total Pembelian</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Ticket Detail Modal -->
    <div class="modal fade" id="ticketDetailModal" tabindex="-1" aria-labelledby="ticketDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticketDetailModalLabel">Detail Tiket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ticketDetailContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Bagikan Tiket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Bagikan informasi tiket ini:</p>
                    <div class="row g-3">
                        <div class="col-6">
                            <button class="btn btn-primary w-100" onclick="shareToFacebook()">
                                <i class="bi bi-facebook me-2"></i>Facebook
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-info w-100" onclick="shareToTwitter()">
                                <i class="bi bi-twitter me-2"></i>Twitter
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-success w-100" onclick="shareToWhatsApp()">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-secondary w-100" onclick="copyTicketLink()">
                                <i class="bi bi-link-45deg me-2"></i>Copy Link
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
        document.addEventListener('DOMContentLoaded', function() {
            // Status filter
            const statusFilter = document.getElementById('statusFilter');
            const ticketsContainer = document.getElementById('ticketsContainer');
            const ticketCount = document.getElementById('ticketCount');

            if (statusFilter && ticketsContainer) {
                statusFilter.addEventListener('change', function() {
                    const selectedStatus = this.value;
                    const ticketCards = ticketsContainer.querySelectorAll('.ticket-card');
                    let visibleCount = 0;

                    ticketCards.forEach(card => {
                        const ticketStatus = card.dataset.status;
                        if (selectedStatus === 'all' || ticketStatus === selectedStatus) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (ticketCount) {
                        ticketCount.textContent = visibleCount;
                    }
                });
            }
        });

        // Global variables for sharing
        let currentTicketId = '';
        let currentTicketTitle = '';

        function showTicketDetail(ticketId) {
            // Find ticket data (in real app, this would be an API call)
            const tickets = @json($tickets);
            const ticket = tickets.find(t => t.id === ticketId);

            if (ticket) {
                const content = `
            <div class="row">
                <div class="col-md-8">
                    <h4 class="fw-bold mb-3">${ticket.event_title}</h4>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Tanggal:</strong><br>
                            <span class="text-muted">${new Date(ticket.date).toLocaleDateString('id-ID', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            })}</span>
                        </div>
                        <div class="col-6">
                            <strong>Waktu:</strong><br>
                            <span class="text-muted">${ticket.time} WIB</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Lokasi:</strong><br>
                        <span class="text-muted">${ticket.location}</span>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Jenis Tiket:</strong><br>
                            <span class="badge bg-primary">${ticket.ticket_type}</span>
                        </div>
                        <div class="col-6">
                            <strong>Jumlah:</strong><br>
                            <span class="text-muted">${ticket.quantity} tiket</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Total Harga:</strong><br>
                            <span class="price-tag">Rp ${ticket.total_price.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="col-6">
                            <strong>Status:</strong><br>
                            <span class="badge ${ticket.status === 'Active' ? 'bg-success' : ticket.status === 'Used' ? 'bg-secondary' : 'bg-danger'}">${ticket.status}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="border rounded p-3">
                        ${ticket.status === 'Active' ? 
                            `<img src="${ticket.qr_code}" alt="QR Code" class="img-fluid mb-2" style="max-width: 150px;">
                                 <p class="small text-muted mb-0">Scan QR code ini saat masuk event</p>` :
                            `<div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <div class="text-center">
                                        <i class="bi bi-qr-code display-4 text-muted mb-2"></i>
                                        <p class="small text-muted mb-0">${ticket.status === 'Used' ? 'Tiket sudah digunakan' : 'Tiket kadaluarsa'}</p>
                                    </div>
                                 </div>`
                        }
                    </div>
                </div>
            </div>
        `;

                document.getElementById('ticketDetailContent').innerHTML = content;
                new bootstrap.Modal(document.getElementById('ticketDetailModal')).show();
            }
        }

        function downloadTicket(ticketId) {
            // Simulate download
            showToast('Tiket PDF sedang diunduh...', 'info');
            setTimeout(() => {
                showToast('Tiket berhasil diunduh!', 'success');
            }, 2000);
        }

        function downloadReceipt(ticketId) {
            // Simulate download
            showToast('Kwitansi PDF sedang diunduh...', 'info');
            setTimeout(() => {
                showToast('Kwitansi berhasil diunduh!', 'success');
            }, 2000);
        }

        function shareTicket(ticketId) {
            // Find ticket data for sharing
            const tickets = @json($tickets);
            const ticket = tickets.find(t => t.id === ticketId);

            if (ticket) {
                currentTicketId = ticketId;
                currentTicketTitle = ticket.event_title;
                new bootstrap.Modal(document.getElementById('shareModal')).show();
            }
        }

        function shareToFacebook() {
            const url =
                `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.origin)}&quote=${encodeURIComponent(`Saya akan menghadiri ${currentTicketTitle}!`)}`;
            window.open(url, '_blank', 'width=600,height=400');
        }

        function shareToTwitter() {
            const text = `Saya akan menghadiri ${currentTicketTitle}! #ETicketing`;
            const url =
                `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(window.location.origin)}`;
            window.open(url, '_blank', 'width=600,height=400');
        }

        function shareToWhatsApp() {
            const text = `Saya akan menghadiri ${currentTicketTitle}! Lihat detail event di ${window.location.origin}`;
            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }

        function copyTicketLink() {
            const text = `Saya akan menghadiri ${currentTicketTitle}! Lihat detail event di ${window.location.origin}`;
            navigator.clipboard.writeText(text).then(function() {
                showToast('Link berhasil disalin!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('shareModal')).hide();
            });
        }

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
            }, 3000);
        }
    </script>
@endpush
