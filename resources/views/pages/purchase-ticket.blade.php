@extends('layouts.app')

@section('title', 'Beli Tiket - ' . $event['title'])

@section('content')
    <!-- Breadcrumb -->
    <section class="py-3 bg-light">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('events') }}">Events</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('event.detail', $event['id']) }}">{{ $event['title'] }}</a>
                    </li>
                    <li class="breadcrumb-item active">Beli Tiket</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Purchase Form Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Purchase Form -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="fw-bold mb-4">
                                <i class="bi bi-cart-plus text-primary me-2"></i>
                                Pembelian Tiket
                            </h2>

                            <form action="{{ route('process.purchase', $event['id']) }}" method="POST" id="purchaseForm">
                                @csrf

                                <!-- Event Info -->
                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <img src="{{ $event['image'] }}" class="img-fluid rounded"
                                                    alt="{{ $event['title'] }}">
                                            </div>
                                            <div class="col-md-9">
                                                <h5 class="fw-bold mb-2">{{ $event['title'] }}</h5>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="bi bi-calendar3 text-primary me-2"></i>
                                                            <span>{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="bi bi-clock text-primary me-2"></i>
                                                            <span>{{ $event['time'] }} WIB</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-geo-alt text-primary me-2"></i>
                                                            <span>{{ $event['location'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ticket Selection -->
                                <div class="mb-4">
                                    <h4 class="fw-bold mb-3">1. Pilih Jenis Tiket</h4>
                                    <div class="row g-3">
                                        @foreach ($event['ticket_types'] as $index => $ticket)
                                            <div class="col-12">
                                                <div class="card ticket-option h-100">
                                                    <div class="card-body">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                <input class="form-check-input ticket-radio" type="radio"
                                                                    name="ticket_type" value="{{ $ticket['type'] }}"
                                                                    id="ticket{{ $index }}"
                                                                    data-price="{{ $ticket['price'] }}"
                                                                    {{ $index === 0 ? 'checked' : '' }}>
                                                            </div>
                                                            <div class="col">
                                                                <label class="form-check-label w-100"
                                                                    for="ticket{{ $index }}">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-start">
                                                                        <div>
                                                                            <h5 class="fw-bold mb-1">{{ $ticket['type'] }}
                                                                            </h5>
                                                                            <div class="text-muted small mb-2">
                                                                                @foreach ($ticket['benefits'] as $benefit)
                                                                                    <div><i
                                                                                            class="bi bi-check text-success me-1"></i>{{ $benefit }}
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-end">
                                                                            <h4 class="price-tag mb-0">Rp
                                                                                {{ number_format($ticket['price'], 0, ',', '.') }}
                                                                            </h4>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Quantity Selection -->
                                <div class="mb-4">
                                    <h4 class="fw-bold mb-3">2. Jumlah Tiket</h4>
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="quantity" class="form-label">Jumlah:</label>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group" style="width: 150px;">
                                                <button class="btn btn-outline-secondary" type="button" id="decreaseBtn">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" class="form-control text-center" name="quantity"
                                                    id="quantity" value="1" min="1" max="10" required>
                                                <button class="btn btn-outline-secondary" type="button" id="increaseBtn">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <small class="text-muted">Maksimal 10 tiket per transaksi</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Information -->
                                <div class="mb-4">
                                    <h4 class="fw-bold mb-3">3. Informasi Pembeli</h4>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Nama Lengkap *</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email *</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">No. Telepon *</label>
                                            <input type="tel" class="form-control" name="phone" id="phone"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="emergency_contact" class="form-label">Kontak Darurat</label>
                                            <input type="tel" class="form-control" name="emergency_contact"
                                                id="emergency_contact">
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                            <label class="form-check-label" for="termsCheck">
                                                Saya setuju dengan <a href="#" class="text-primary">syarat dan
                                                    ketentuan</a> yang berlaku *
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newsletterCheck">
                                            <label class="form-check-label" for="newsletterCheck">
                                                Saya ingin menerima informasi event terbaru via email
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="mb-4">
                                    <h4 class="fw-bold mb-3">4. Metode Pembayaran</h4>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="card payment-option">
                                                <div class="card-body text-center">
                                                    <input class="form-check-input d-none" type="radio"
                                                        name="payment_method" value="bank_transfer" id="bank_transfer"
                                                        checked>
                                                    <label for="bank_transfer" class="w-100">
                                                        <i class="bi bi-bank display-6 text-primary mb-2"></i>
                                                        <h6 class="fw-bold">Transfer Bank</h6>
                                                        <small class="text-muted">BCA, Mandiri, BNI, BRI</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card payment-option">
                                                <div class="card-body text-center">
                                                    <input class="form-check-input d-none" type="radio"
                                                        name="payment_method" value="e_wallet" id="e_wallet">
                                                    <label for="e_wallet" class="w-100">
                                                        <i class="bi bi-wallet display-6 text-success mb-2"></i>
                                                        <h6 class="fw-bold">E-Wallet</h6>
                                                        <small class="text-muted">OVO, GoPay, DANA, ShopeePay</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card payment-option">
                                                <div class="card-body text-center">
                                                    <input class="form-check-input d-none" type="radio"
                                                        name="payment_method" value="credit_card" id="credit_card">
                                                    <label for="credit_card" class="w-100">
                                                        <i class="bi bi-credit-card display-6 text-warning mb-2"></i>
                                                        <h6 class="fw-bold">Kartu Kredit</h6>
                                                        <small class="text-muted">Visa, Mastercard</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-cart-check me-2"></i>
                                        Lanjutkan Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-receipt me-2"></i>
                                Ringkasan Pesanan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="orderSummary">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Jenis Tiket:</span>
                                    <span id="selectedTicketType">{{ $event['ticket_types'][0]['type'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Harga Satuan:</span>
                                    <span id="ticketPrice">Rp
                                        {{ number_format($event['ticket_types'][0]['price'], 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Jumlah:</span>
                                    <span id="ticketQuantity">1</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span id="subtotal">Rp
                                        {{ number_format($event['ticket_types'][0]['price'], 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Biaya Admin:</span>
                                    <span id="adminFee">Rp 5.000</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total:</span>
                                    <span id="totalPrice" class="text-primary">Rp
                                        {{ number_format($event['ticket_types'][0]['price'] + 5000, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-shield-check text-success me-2"></i>
                                    <small>Transaksi 100% aman</small>
                                </div>
                                <div class="d-flex align-items-center text-muted mt-1">
                                    <i class="bi bi-clock text-info me-2"></i>
                                    <small>Tiket dikirim langsung via email</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body text-center">
                            <i class="bi bi-headset display-6 text-primary mb-3"></i>
                            <h6 class="fw-bold">Butuh Bantuan?</h6>
                            <p class="text-muted small mb-3">Tim support kami siap membantu Anda</p>
                            <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-envelope me-1"></i>Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .ticket-option {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: 2px solid transparent;
        }

        .ticket-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .ticket-option.selected {
            border-color: var(--primary-color);
            background-color: var(--primary-color);
            color: white;
        }

        .payment-option {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: 2px solid transparent;
        }

        .payment-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .payment-option.selected {
            border-color: var(--primary-color);
            background-color: rgba(99, 102, 241, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const decreaseBtn = document.getElementById('decreaseBtn');
            const increaseBtn = document.getElementById('increaseBtn');
            const ticketRadios = document.querySelectorAll('.ticket-radio');
            const paymentRadios = document.querySelectorAll('input[name="payment_method"]');

            // Quantity controls
            decreaseBtn.addEventListener('click', function() {
                const current = parseInt(quantityInput.value);
                if (current > 1) {
                    quantityInput.value = current - 1;
                    updateOrderSummary();
                }
            });

            increaseBtn.addEventListener('click', function() {
                const current = parseInt(quantityInput.value);
                if (current < 10) {
                    quantityInput.value = current + 1;
                    updateOrderSummary();
                }
            });

            quantityInput.addEventListener('input', updateOrderSummary);

            // Ticket selection
            ticketRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateTicketSelection();
                    updateOrderSummary();
                });
            });

            // Payment method selection
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', updatePaymentSelection);
            });

            // Initialize
            updateTicketSelection();
            updatePaymentSelection();
            updateOrderSummary();

            function updateTicketSelection() {
                document.querySelectorAll('.ticket-option').forEach(card => {
                    card.classList.remove('selected');
                });

                const selectedRadio = document.querySelector('.ticket-radio:checked');
                if (selectedRadio) {
                    selectedRadio.closest('.ticket-option').classList.add('selected');
                }
            }

            function updatePaymentSelection() {
                document.querySelectorAll('.payment-option').forEach(card => {
                    card.classList.remove('selected');
                });

                const selectedRadio = document.querySelector('input[name="payment_method"]:checked');
                if (selectedRadio) {
                    selectedRadio.closest('.payment-option').classList.add('selected');
                }
            }

            function updateOrderSummary() {
                const selectedTicket = document.querySelector('.ticket-radio:checked');
                const quantity = parseInt(quantityInput.value);
                const adminFee = 5000;

                if (selectedTicket) {
                    const ticketType = selectedTicket.value;
                    const ticketPrice = parseInt(selectedTicket.dataset.price);
                    const subtotal = ticketPrice * quantity;
                    const total = subtotal + adminFee;

                    document.getElementById('selectedTicketType').textContent = ticketType;
                    document.getElementById('ticketPrice').textContent = formatCurrency(ticketPrice);
                    document.getElementById('ticketQuantity').textContent = quantity;
                    document.getElementById('subtotal').textContent = formatCurrency(subtotal);
                    document.getElementById('totalPrice').textContent = formatCurrency(total);
                }
            }

            function formatCurrency(amount) {
                return 'Rp ' + amount.toLocaleString('id-ID');
            }

            // Form validation
            document.getElementById('purchaseForm').addEventListener('submit', function(e) {
                const termsCheck = document.getElementById('termsCheck');
                if (!termsCheck.checked) {
                    e.preventDefault();
                    alert('Anda harus menyetujui syarat dan ketentuan untuk melanjutkan.');
                    termsCheck.focus();
                }
            });

            // Auto-fill form for demo
            setTimeout(() => {
                document.getElementById('name').value = 'John Doe';
                document.getElementById('email').value = 'john.doe@example.com';
                document.getElementById('phone').value = '081234567890';
            }, 1000);
        });
    </script>
@endpush
