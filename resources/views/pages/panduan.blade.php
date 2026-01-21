@extends('layouts.e-ticket')

@section('title', 'Panduan Penggunaan - Sistem E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Panduan Penggunaan</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-book me-2"></i>
        Panduan Penggunaan Sistem
    </h2>
    <p class="mb-0">Pelajari cara menggunakan sistem E-Ticket Kominfo Bukittinggi</p>
@endsection

@section('content')
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="sticky-top" style="top: 80px;">
                <div class="list-group">
                    @foreach ($sections as $section)
                        <a href="#{{ $section['id'] }}" class="list-group-item list-group-item-action"
                            data-bs-smooth-scroll="true">
                            <i class="bi {{ $section['icon'] }} me-2"></i>
                            {{ $section['title'] }}
                        </a>
                    @endforeach
                    <a href="#faq" class="list-group-item list-group-item-action" data-bs-smooth-scroll="true">
                        <i class="bi bi-question-circle me-2"></i>
                        Pertanyaan Umum (FAQ)
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Sections -->
            @foreach ($sections as $section)
                <div class="card mb-4" id="{{ $section['id'] }}">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi {{ $section['icon'] }} text-primary me-2"></i>
                            {{ $section['title'] }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $section['content'] }}</p>
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Tips:</strong> Baca instruksi dengan teliti dan hubungi support jika ada yang kurang
                            jelas.
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- FAQ Section -->
            <div class="card mb-4" id="faq">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-question-circle text-primary me-2"></i>
                        Pertanyaan Umum (FAQ)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        @foreach ($faqItems as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}">
                                        <i class="bi bi-question-lg text-primary me-2"></i>
                                        {{ $faq['pertanyaan'] }}
                                    </button>
                                </h2>
                                <div id="faq{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq['jawaban'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="alert alert-primary" role="alert">
                <h5>Masih Ada Pertanyaan?</h5>
                <p class="mb-0">Jika Anda masih memiliki pertanyaan atau mengalami kesulitan, silakan hubungi tim support
                    kami melalui <a href="{{ route('hubungi') }}">halaman Hubungi Kami</a> atau klik tombol di bawah.</p>
                <a href="{{ route('hubungi') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-headset me-2"></i>
                    Hubungi Support
                </a>
            </div>
        </div>
    </div>
@endsection
