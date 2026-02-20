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
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Toolbar --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari nama atau kode SKPD...">
                </div>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSkpd">
                    <i class="bi bi-plus-circle me-2"></i>Tambah SKPD
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-buildings text-primary fs-3 mb-1"></i>
                <div class="fw-bold fs-4">{{ $departments->total() }}</div>
                <div class="text-muted small">Total SKPD</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-check-circle text-success fs-3 mb-1"></i>
                <div class="fw-bold fs-4">{{ $departments->where('status', 'aktif')->count() }}</div>
                <div class="text-muted small">SKPD Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-ticket text-info fs-3 mb-1"></i>
                <div class="fw-bold fs-4">{{ $departments->sum('tickets_count') }}</div>
                <div class="text-muted small">Total Tiket</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-people text-warning fs-3 mb-1"></i>
                <div class="fw-bold fs-4">{{ $departments->sum('users_count') }}</div>
                <div class="text-muted small">Total Operator</div>
            </div>
        </div>
    </div>
</div>

{{-- SKPD Grid --}}
<div class="row g-4" id="skpdGrid">
    @forelse($departments as $dept)
    <div class="col-lg-6 skpd-card"
         data-name="{{ strtolower($dept->name) }}"
         data-code="{{ strtolower($dept->code) }}"
         data-status="{{ $dept->status }}">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-2 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold"
                             style="width:48px;height:48px;font-size:1.1rem;flex-shrink:0;">
                            {{ strtoupper(substr($dept->code ?? $dept->name, 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $dept->name }}</h6>
                            <small class="text-muted">{{ $dept->code }}</small>
                        </div>
                    </div>
                    @if($dept->status === 'aktif')
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                            <i class="bi bi-circle-fill me-1" style="font-size:.45rem"></i>Aktif
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                            <i class="bi bi-circle-fill me-1" style="font-size:.45rem"></i>Nonaktif
                        </span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="row g-2 mb-3">
                    @if($dept->head)
                    <div class="col-6">
                        <div class="bg-light rounded p-2">
                            <small class="text-muted d-block"><i class="bi bi-person me-1"></i>Kepala</small>
                            <small class="fw-semibold">{{ $dept->head }}</small>
                        </div>
                    </div>
                    @endif
                    @if($dept->contact)
                    <div class="col-6">
                        <div class="bg-light rounded p-2">
                            <small class="text-muted d-block"><i class="bi bi-telephone me-1"></i>Kontak</small>
                            <small class="fw-semibold">{{ $dept->contact }}</small>
                        </div>
                    </div>
                    @endif
                    @if($dept->address)
                    <div class="col-12">
                        <div class="bg-light rounded p-2">
                            <small class="text-muted d-block"><i class="bi bi-geo-alt me-1"></i>Alamat</small>
                            <small>{{ $dept->address }}</small>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="row text-center g-2 mb-3">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="fw-bold text-primary fs-5">{{ $dept->tickets_count }}</div>
                            <small class="text-muted">Total Tiket</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="fw-bold text-info fs-5">{{ $dept->users_count }}</div>
                            <small class="text-muted">Operator</small>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm flex-fill"
                            data-bs-toggle="modal" data-bs-target="#modalEditSkpd"
                            data-id="{{ $dept->id }}"
                            data-name="{{ $dept->name }}"
                            data-code="{{ $dept->code }}"
                            data-contact="{{ $dept->contact }}"
                            data-head="{{ $dept->head }}"
                            data-address="{{ $dept->address }}"
                            data-status="{{ $dept->status }}">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </button>
                    <button class="btn btn-outline-danger btn-sm"
                            data-bs-toggle="modal" data-bs-target="#modalHapusSkpd"
                            data-id="{{ $dept->id }}"
                            data-name="{{ $dept->name }}"
                            data-tiket="{{ $dept->tickets_count }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-building fs-1 text-muted opacity-50 d-block mb-3"></i>
                <p class="text-muted mb-4">Belum ada SKPD terdaftar.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSkpd">
                    <i class="bi bi-plus-circle me-2"></i>Tambah SKPD Pertama
                </button>
            </div>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($departments->hasPages())
<div class="mt-4">{{ $departments->links() }}</div>
@endif

{{--  MODAL TAMBAH SKPD  --}}
<div class="modal fade" id="modalTambahSkpd" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.skpd.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-building-add me-2 text-primary"></i>Tambah SKPD Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama SKPD <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Dinas Komunikasi dan Informatika" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kode / Singkatan <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control text-uppercase" placeholder="KOMINFO" required
                                   oninput="this.value=this.value.toUpperCase()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Kepala</label>
                            <input type="text" name="head" class="form-control" placeholder="Nama kepala dinas">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontak</label>
                            <input type="text" name="contact" class="form-control" placeholder="0751-xxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Jl. ..."></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan SKPD
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--  MODAL EDIT SKPD  --}}
<div class="modal fade" id="modalEditSkpd" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditSkpd" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2 text-warning"></i>Edit SKPD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama SKPD <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kode / Singkatan <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="editCode" class="form-control text-uppercase" required
                                   oninput="this.value=this.value.toUpperCase()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Kepala</label>
                            <input type="text" name="head" id="editHead" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontak</label>
                            <input type="text" name="contact" id="editContact" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="address" id="editAddress" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="editStatus" class="form-select" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                        SKPD ini memiliki <strong id="hapusTiketCount"></strong> tiket terkait. Pastikan tiket sudah dipindahkan sebelum menghapus.
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
    // Edit modal  populate fields
    document.getElementById('modalEditSkpd').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('editName').value    = btn.dataset.name    || '';
        document.getElementById('editCode').value    = btn.dataset.code    || '';
        document.getElementById('editHead').value    = btn.dataset.head    || '';
        document.getElementById('editContact').value = btn.dataset.contact || '';
        document.getElementById('editAddress').value = btn.dataset.address || '';
        document.getElementById('editStatus').value  = btn.dataset.status  || 'aktif';
        document.getElementById('formEditSkpd').action = `/admin/skpd/${btn.dataset.id}`;
    });

    // Hapus modal
    document.getElementById('modalHapusSkpd').addEventListener('show.bs.modal', function (e) {
        const btn    = e.relatedTarget;
        const tiket  = parseInt(btn.dataset.tiket) || 0;
        document.getElementById('hapusNama').textContent = btn.dataset.name;
        document.getElementById('formHapusSkpd').action  = `/admin/skpd/${btn.dataset.id}`;
        const warning = document.getElementById('hapusWarning');
        if (tiket > 0) {
            document.getElementById('hapusTiketCount').textContent = tiket + ' tiket';
            warning.classList.remove('d-none');
        } else {
            warning.classList.add('d-none');
        }
    });

    // Live search + filter
    function filterCards() {
        const q      = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('filterStatus').value.toLowerCase();
        document.querySelectorAll('.skpd-card').forEach(function(card) {
            const matchQ      = !q      || card.dataset.name.includes(q) || card.dataset.code.includes(q);
            const matchStatus = !status || card.dataset.status === status;
            card.style.display = (matchQ && matchStatus) ? '' : 'none';
        });
    }

    document.getElementById('searchInput').addEventListener('input', filterCards);
    document.getElementById('filterStatus').addEventListener('change', filterCards);

    // Re-open modal on validation error
    @if($errors->any())
        @if(old('_method') === 'PUT')
            new bootstrap.Modal(document.getElementById('modalEditSkpd')).show();
        @else
            new bootstrap.Modal(document.getElementById('modalTambahSkpd')).show();
        @endif
    @endif
</script>
@endpush