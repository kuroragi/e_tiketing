@extends('layouts.e-ticket')

@section('title', 'Manajemen SKPD - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Manajemen SKPD</li>
@endsection

@section('page-header')
    <h2 class="mb-1"><i class="bi bi-building me-2"></i>Manajemen SKPD</h2>
    <p class="mb-0">Kelola data Satuan Kerja Perangkat Daerah</p>
@endsection

@section('content')

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <i class="bi bi-buildings text-primary fs-3 mb-1"></i>
                    <div class="fw-bold fs-4">{{ $department_count }}</div>
                    <div class="text-muted small">Total SKPD</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <i class="bi bi-check-circle text-success fs-3 mb-1"></i>
                    <div class="fw-bold fs-4">{{ $department_active_count }}</div>
                    <div class="text-muted small">SKPD Aktif</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <i class="bi bi-ticket text-info fs-3 mb-1"></i>
                    <div class="fw-bold fs-4">{{ $ticket_count }}</div>
                    <div class="text-muted small">Total Tiket</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <i class="bi bi-people text-warning fs-3 mb-1"></i>
                    <div class="fw-bold fs-4">{{ $operator_count }}</div>
                    <div class="text-muted small">Total Operator</div>
                </div>
            </div>
        </div>
    </div>

    @livewire('skpd.skpd-list')

    @livewire('skpd.skpd-form')



    {{--  MODAL HAPUS SKPD  --}}
    <div class="modal fade" id="modalHapusSkpd" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formHapusSkpd" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger"><i class="bi bi-trash me-2"></i>Hapus SKPD</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus SKPD <strong id="hapusNama"></strong>?</p>
                        <div id="hapusWarning" class="alert alert-warning d-none">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            SKPD ini memiliki <strong id="hapusTiketCount"></strong> tiket terkait. Pastikan tiket sudah
                            dipindahkan sebelum menghapus.
                        </div>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Hapus SKPD
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Hapus modal
        document.getElementById('modalHapusSkpd').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            const tiket = parseInt(btn.dataset.tiket) || 0;
            document.getElementById('hapusNama').textContent = btn.dataset.name;
            document.getElementById('formHapusSkpd').action = `/admin/skpd/${btn.dataset.id}`;
            const warning = document.getElementById('hapusWarning');
            if (tiket > 0) {
                document.getElementById('hapusTiketCount').textContent = tiket + ' tiket';
                warning.classList.remove('d-none');
            } else {
                warning.classList.add('d-none');
            }
        });
    </script>
@endpush
