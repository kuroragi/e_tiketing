@extends('layouts.landing')

@section('title', 'Pengaduan Berhasil - ' . Setting::get('app_name', 'Layanan Publik Kominfo'))

@section('content')
<section style="padding-top: 100px; min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <!-- Success Card -->
                <div class="card border-0 shadow-sm text-center" style="border-radius: 1rem; overflow: hidden;">
                    <div class="py-5" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="bi bi-check-circle" style="font-size: 4rem; color: #fff;"></i>
                        <h2 class="text-white mt-3 mb-1 fw-bold">Pengaduan Berhasil Dikirim!</h2>
                        <p class="text-white opacity-75">Terima kasih atas laporan Anda</p>
                    </div>

                    <div class="card-body p-4 p-lg-5">
                        <!-- Tracking Code -->
                        <div class="bg-light rounded-3 p-4 mb-4">
                            <p class="text-muted mb-2 small fw-semibold">KODE TRACKING ANDA</p>
                            <h2 class="mb-2 fw-bold text-primary" style="letter-spacing: 2px; font-family: monospace;">
                                {{ $ticket->tracking_code }}
                            </h2>
                            <p class="text-muted small mb-3">
                                Simpan kode ini untuk melacak status pengaduan Anda
                            </p>
                            <button onclick="copyTrackingCode('{{ $ticket->tracking_code }}')" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-clipboard me-1"></i>Salin Kode
                            </button>
                        </div>

                        <!-- Ticket Summary -->
                        <div class="text-start mb-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-file-text text-primary me-2"></i>Ringkasan</h5>
                            <table class="table table-borderless small">
                                <tr>
                                    <td class="text-muted" style="width:40%">Nomor Tiket</td>
                                    <td class="fw-semibold">{{ $ticket->number }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Judul</td>
                                    <td class="fw-semibold">{{ $ticket->title }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kategori</td>
                                    <td>{{ $ticket->category->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Prioritas</td>
                                    <td>
                                        <span class="badge" style="background:{{ $ticket->priority->color ?? '#6b7280' }}">
                                            {{ $ticket->priority->name ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status</td>
                                    <td><span class="badge bg-secondary">Baru</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pelapor</td>
                                    <td>{{ $ticket->public_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td>{{ $ticket->public_email }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal</td>
                                    <td>{{ $ticket->created_at->format('d M Y, H:i') }} WIB</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ route('public.ticket.track', ['code' => $ticket->tracking_code]) }}"
                                class="btn btn-primary px-4">
                                <i class="bi bi-search me-2"></i>Lacak Status
                            </a>
                            <a href="{{ route('public.ticket.create') }}" class="btn btn-outline-primary px-4">
                                <i class="bi bi-plus-circle me-2"></i>Buat Pengaduan Lain
                            </a>
                            <a href="{{ route('landing') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-house me-2"></i>Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Alert -->
                <div class="alert alert-info mt-4 d-flex align-items-start gap-3" style="border-radius:0.75rem;">
                    <i class="bi bi-lightbulb fs-4 text-info flex-shrink-0"></i>
                    <div>
                        <strong>Tips:</strong> Anda dapat melacak status pengaduan kapan saja melalui halaman
                        <a href="{{ route('public.ticket.track') }}" class="fw-semibold">Lacak Tiket</a>
                        dengan memasukkan kode tracking di atas.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function copyTrackingCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        const btn = event.target.closest('button');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Tersalin!';
        btn.classList.replace('btn-outline-primary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.classList.replace('btn-success', 'btn-outline-primary');
        }, 2000);
    });
}
</script>
@endpush
