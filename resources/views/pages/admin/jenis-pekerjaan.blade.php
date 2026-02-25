@extends('layouts.e-ticket')

@section('title', 'Manajemen Jenis Pekerjaan - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Manajemen Jenis Pekerjaan</li>
@endsection

@section('page-header')
    <h2 class="mb-1"><i class="bi bi-tags me-2"></i>Manajemen Jenis Pekerjaan</h2>
    <p class="mb-0">Kelola kategori dan jenis pekerjaan yang ditangani Kominfo</p>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-primary">{{ $categories->total() }}</h4>
                    <p class="text-muted small mb-0">Total Jenis Pekerjaan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-success">{{ $categories->where('status', 'aktif')->count() }}</h4>
                    <p class="text-muted small mb-0">Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-warning">{{ $categories->where('status', 'nonaktif')->count() }}</h4>
                    <p class="text-muted small mb-0">Nonaktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-info">{{ $categories->sum('tickets_count') }}</h4>
                    <p class="text-muted small mb-0">Total Tiket</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control"
                        placeholder="Cari jenis pekerjaan...">
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#tambahModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Jenis
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Jenis Pekerjaan</h5>
        </div>
        <div class="card-body">
            @if($errors->any() && !session('success'))
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover" id="kategoriTable">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Jenis Pekerjaan</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Tiket</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $cat)
                            <tr data-name="{{ strtolower($cat->name) }}" data-status="{{ $cat->status }}">
                                <td>
                                    <strong>{{ $cat->name }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $cat->description ? Str::limit($cat->description, 60) : '-' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $cat->tickets_count }}</span>
                                </td>
                                <td class="text-center">
                                    @if($cat->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary"
                                            title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id="{{ $cat->id }}"
                                            data-name="{{ $cat->name }}"
                                            data-description="{{ $cat->description }}"
                                            data-status="{{ $cat->status }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger"
                                            title="Hapus"
                                            data-bs-toggle="modal" data-bs-target="#hapusModal"
                                            data-id="{{ $cat->id }}"
                                            data-name="{{ $cat->name }}"
                                            data-tiket="{{ $cat->tickets_count }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                    Belum ada jenis pekerjaan. Klik "Tambah Jenis" untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $categories->links() }}</div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal" tabindex="-1"
        @if($errors->any() && old('_action') === 'tambah') data-bs-backdrop="static" @endif>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Jenis Pekerjaan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.jenis-pekerjaan.store') }}">
                    @csrf
                    <input type="hidden" name="_action" value="tambah">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Jenis Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required
                                placeholder="Contoh: Instalasi Jaringan">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                name="description" rows="3"
                                placeholder="Deskripsi singkat jenis pekerjaan ini...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="aktif" {{ old('status') !== 'nonaktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Jenis Pekerjaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Jenis Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="hapusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-trash me-2"></i>Hapus Jenis Pekerjaan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="hapusForm">
                    @csrf @method('DELETE')
                    <div class="modal-body">
                        <p>Yakin ingin menghapus jenis pekerjaan <strong id="hapusNama"></strong>?</p>
                        <div id="hapusWarning" class="alert alert-warning d-none">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Jenis pekerjaan ini memiliki tiket terkait. Hapus hanya jika tidak diperlukan lagi.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live search & filter
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#kategoriTable tbody tr[data-name]');

    function filterTable() {
        const q = searchInput.value.toLowerCase();
        const s = statusFilter.value;
        rows.forEach(row => {
            const matchName = row.dataset.name.includes(q);
            const matchStatus = !s || row.dataset.status === s;
            row.style.display = matchName && matchStatus ? '' : 'none';
        });
    }
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Edit modal
    document.getElementById('editModal').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        const id = btn.dataset.id;
        document.getElementById('editForm').action = '/admin/jenis-pekerjaan/' + id;
        document.getElementById('editName').value = btn.dataset.name;
        document.getElementById('editDescription').value = btn.dataset.description || '';
        document.getElementById('editStatus').value = btn.dataset.status;
    });

    // Hapus modal
    document.getElementById('hapusModal').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('hapusForm').action = '/admin/jenis-pekerjaan/' + btn.dataset.id;
        document.getElementById('hapusNama').textContent = btn.dataset.name;
        const warning = document.getElementById('hapusWarning');
        warning.classList.toggle('d-none', parseInt(btn.dataset.tiket) === 0);
    });

    @if($errors->any() && old('_action') === 'tambah')
        new bootstrap.Modal(document.getElementById('tambahModal')).show();
    @endif
});
</script>
@endpush