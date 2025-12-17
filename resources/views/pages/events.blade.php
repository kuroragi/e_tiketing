@extends('layouts.app')

@section('title', 'Events - E-Ticketing System')

@section('content')
    <!-- Header Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">Jelajahi Event Menarik</h1>
                    <p class="lead text-muted">Temukan event yang sesuai dengan minat dan passion Anda</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter & Search Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <form method="GET" action="{{ route('events') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Cari event..."
                            value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category">
                        <option value="all" {{ $selectedCategory === 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}"
                                {{ strtolower($selectedCategory) === strtolower($category) ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('events') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- Results Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            Ditemukan {{ count($events) }} event
                            @if ($selectedCategory && $selectedCategory !== 'all')
                                dalam kategori <strong>{{ $selectedCategory }}</strong>
                            @endif
                            @if ($search)
                                untuk pencarian "<strong>{{ $search }}</strong>"
                            @endif
                        </h4>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="view-mode" id="grid-view" checked>
                            <label class="btn btn-outline-primary" for="grid-view">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </label>
                            <input type="radio" class="btn-check" name="view-mode" id="list-view">
                            <label class="btn btn-outline-primary" for="list-view">
                                <i class="bi bi-list"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            @if (count($events) > 0)
                <!-- Grid View -->
                <div id="grid-container" class="row">
                    @foreach ($events as $event)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card event-card h-100">
                                <img src="{{ $event['image'] }}" class="card-img-top event-image"
                                    alt="{{ $event['title'] }}">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge-custom">{{ $event['category'] }}</span>
                                        <small class="text-muted">{{ $event['available_tickets'] }} tiket tersisa</small>
                                    </div>

                                    <h5 class="card-title fw-bold">{{ $event['title'] }}</h5>
                                    <p class="card-text text-muted small mb-3">{{ Str::limit($event['description'], 80) }}
                                    </p>

                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-calendar3 text-primary me-2"></i>
                                            <span
                                                class="small">{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }}
                                                - {{ $event['time'] }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt text-primary me-2"></i>
                                            <span class="small">{{ $event['location'] }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="price-tag">Rp
                                                {{ number_format($event['price'], 0, ',', '.') }}</span>
                                            <div>
                                                <a href="{{ route('event.detail', $event['id']) }}"
                                                    class="btn btn-outline-primary btn-sm me-1">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('purchase.ticket', $event['id']) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-cart-plus me-1"></i>Beli
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- List View -->
                <div id="list-container" class="d-none">
                    @foreach ($events as $event)
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    <img src="{{ $event['image'] }}" class="img-fluid h-100 rounded-start"
                                        style="object-fit: cover;" alt="{{ $event['title'] }}">
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body h-100 d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge-custom">{{ $event['category'] }}</span>
                                            <small class="text-muted">{{ $event['available_tickets'] }} tiket
                                                tersisa</small>
                                        </div>

                                        <h5 class="card-title fw-bold">{{ $event['title'] }}</h5>
                                        <p class="card-text text-muted mb-3">{{ $event['description'] }}</p>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                                    <span
                                                        class="small">{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }}
                                                        - {{ $event['time'] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                                    <span class="small">{{ $event['location'] }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="price-tag">Rp
                                                    {{ number_format($event['price'], 0, ',', '.') }}</span>
                                                <div>
                                                    <a href="{{ route('event.detail', $event['id']) }}"
                                                        class="btn btn-outline-primary me-2">
                                                        <i class="bi bi-eye me-1"></i>Detail
                                                    </a>
                                                    <a href="{{ route('purchase.ticket', $event['id']) }}"
                                                        class="btn btn-primary">
                                                        <i class="bi bi-cart-plus me-1"></i>Beli Tiket
                                                    </a>
                                                </div>
                                            </div>
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
                    <i class="bi bi-calendar-x display-1 text-muted mb-3"></i>
                    <h3 class="text-muted mb-3">Tidak ada event ditemukan</h3>
                    <p class="text-muted mb-4">
                        @if ($search || ($selectedCategory && $selectedCategory !== 'all'))
                            Coba ubah kata kunci pencarian atau kategori yang dipilih.
                        @else
                            Saat ini belum ada event yang tersedia.
                        @endif
                    </p>
                    <a href="{{ route('events') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Pencarian
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-2">Jangan Lewatkan Event Terbaru!</h3>
                    <p class="mb-0">Daftar newsletter dan dapatkan notifikasi event menarik langsung di email Anda.</p>
                </div>
                <div class="col-md-4">
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Masukkan email Anda">
                        <button type="submit" class="btn btn-light">
                            <i class="bi bi-envelope"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // View mode toggle
            const gridView = document.getElementById('grid-view');
            const listView = document.getElementById('list-view');
            const gridContainer = document.getElementById('grid-container');
            const listContainer = document.getElementById('list-container');

            gridView.addEventListener('change', function() {
                if (this.checked) {
                    gridContainer.classList.remove('d-none');
                    listContainer.classList.add('d-none');
                }
            });

            listView.addEventListener('change', function() {
                if (this.checked) {
                    gridContainer.classList.add('d-none');
                    listContainer.classList.remove('d-none');
                }
            });

            // Auto-submit form on category change
            document.querySelector('select[name="category"]').addEventListener('change', function() {
                this.form.submit();
            });
        });
    </script>
@endpush
