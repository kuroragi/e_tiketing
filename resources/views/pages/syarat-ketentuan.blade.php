@extends('layouts.e-ticket')

@section('title', 'Syarat dan Ketentuan - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Syarat dan Ketentuan</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-file-text me-2"></i>
        Syarat dan Ketentuan
    </h2>
    <p class="mb-0">Perjanjian penggunaan Sistem E-Ticket Kominfo Bukittinggi</p>
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
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Perhatian:</strong> Dengan mengklik tombol "Setuju" atau menggunakan sistem ini, Anda menyatakan
                bahwa telah membaca, memahami, dan menerima semua syarat dan ketentuan di bawah ini.
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

            <!-- Acceptance Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Penerimaan Syarat dan Ketentuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="acceptTerms">
                        <label class="form-check-label" for="acceptTerms">
                            Saya telah membaca dan memahami semua syarat dan ketentuan di atas
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="acceptPrivacy">
                        <label class="form-check-label" for="acceptPrivacy">
                            Saya setuju dengan <a href="{{ route('kebijakan') }}" target="_blank">Kebijakan Privasi</a>
                            sistem
                        </label>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-lg btn-primary" onclick="acceptTerms()">
                            <i class="bi bi-check-lg me-2"></i>
                            Setuju dan Lanjutkan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-lg btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>
                            Batal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Version Info -->
            <div class="alert alert-secondary" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Versi Dokumen:</strong> 1.0<br>
                <strong>Tanggal Berlaku:</strong> {{ now()->format('d F Y') }}<br>
                <strong>Terakhir Diperbarui:</strong> {{ now()->format('d F Y') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function acceptTerms() {
            const terms = document.getElementById('acceptTerms').checked;
            const privacy = document.getElementById('acceptPrivacy').checked;

            if (!terms || !privacy) {
                showToast('Harap setujui semua persyaratan sebelum melanjutkan', 'warning');
                return;
            }

            showToast('Terima kasih! Anda telah menerima syarat dan ketentuan kami.', 'success');

            // Simulate saving acceptance
            setTimeout(() => {
                window.location.href = "{{ route('dashboard') }}";
            }, 1500);
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
