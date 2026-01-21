@extends('layouts.e-ticket')

@section('title', 'Kebijakan Privasi - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Kebijakan Privasi</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-shield-lock me-2"></i>
        Kebijakan Privasi
    </h2>
    <p class="mb-0">Memahami bagaimana kami melindungi data Anda</p>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="sticky-top" style="top: 80px;">
                <div class="list-group">
                    @foreach ($sections as $index => $section)
                        <a href="#section{{ $index }}" class="list-group-item list-group-item-action"
                            data-bs-smooth-scroll="true">
                            {{ explode('. ', $section['title'])[0] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="alert alert-warning" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Penting:</strong> Harap baca kebijakan privasi ini dengan seksama. Dengan menggunakan sistem, Anda
                menyetujui pengumpulan dan penggunaan informasi sesuai kebijakan ini.
            </div>

            @foreach ($sections as $index => $section)
                <div class="card mb-4 border-0 shadow-sm" id="section{{ $index }}">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul text-primary me-2"></i>
                            {{ $section['title'] }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $section['content'] }}</p>
                    </div>
                </div>
            @endforeach

            <!-- Last Updated -->
            <div class="alert alert-info" role="alert">
                <i class="bi bi-calendar-event me-2"></i>
                <strong>Pembaruan Terakhir:</strong> Kebijakan ini terakhir diperbarui pada {{ now()->format('d F Y') }}.
            </div>

            <!-- Contact for Privacy -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-4">
                    <h5 class="mb-3">Pertanyaan tentang Privasi Anda?</h5>
                    <p class="text-muted mb-3">Jika Anda memiliki pertanyaan atau kekhawatiran tentang kebijakan privasi
                        kami, silakan hubungi kami.</p>
                    <a href="{{ route('hubungi') }}" class="btn btn-primary">
                        <i class="bi bi-envelope me-2"></i>
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
