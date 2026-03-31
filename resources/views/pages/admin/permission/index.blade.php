@extends('layouts.e-ticket')

@section('title', 'Manajemen Permission - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Manajemen Permission</li>
@endsection

@section('page-header')
    <h2 class="mb-1"><i class="bi bi-key me-2"></i>Manajemen Permission</h2>
    <p class="mb-0">Kelola daftar permission yang tersedia dalam sistem</p>
@endsection

@section('content')

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

    <div class="row g-4">
        <!-- Permission Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2 text-warning"></i>
                        Daftar Permission
                        <span class="badge bg-secondary ms-2">{{ $permissions->count() }}</span>
                    </h5>
                    <button class="btn btn-warning btn-sm text-dark" data-bs-toggle="modal"
                        data-bs-target="#modalTambahPerm">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Permission
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tblPermissions">
                            <thead class="table-light">
                                <tr>
                                    <th width="40">#</th>
                                    <th>Nama Permission</th>
                                    <th>Digunakan di Role</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $i => $perm)
                                    <tr>
                                        <td class="text-muted small">{{ $i + 1 }}</td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded text-dark">{{ $perm->name }}</code>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $perm->roles_count > 0 ? 'success' : 'light text-muted border' }}">
                                                {{ $perm->roles_count }} role
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal"
                                                data-bs-target="#modalEditPerm" data-id="{{ $perm->id }}"
                                                data-name="{{ $perm->name }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modalHapusPerm" data-id="{{ $perm->id }}"
                                                data-name="{{ $perm->name }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada permission
                                            terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Permission Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0"><i class="bi bi-shield-check me-2 text-success"></i>Permission per Role</h5>
                </div>
                <div class="card-body">
                    @foreach ($roles as $role)
                        @php
                            $color = match ($role->name) {
                                'admin' => 'danger',
                                'pimpinan' => 'warning',
                                'petugas' => 'primary',
                                'skpd' => 'success',
                                default => 'secondary',
                            };
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-{{ $color }} me-2">{{ Str::ucfirst($role->name) }}</span>
                                <small class="text-muted">{{ $role->permissions->count() }} permission</small>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($role->permissions as $p)
                                    <span class="badge bg-light text-dark border"
                                        style="font-size:.7rem">{{ $p->name }}</span>
                                @endforeach
                                @if ($role->permissions->count() === 0)
                                    <small class="text-muted fst-italic">Tidak ada permission</small>
                                @endif
                            </div>
                        </div>
                        @if (!$loop->last)
                            <hr class="my-2">
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Modal Tambah Permission ───────────────────────────────── --}}
    <div class="modal fade" id="modalTambahPerm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Permission <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="contoh: kelola-laporan"
                                required>
                            <div class="form-text">Gunakan format: <code>kata-kerja-objek</code> (contoh:
                                <code>lihat-tiket</code>)</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-dark">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ─── Modal Edit Permission ─────────────────────────────────── --}}
    <div class="modal fade" id="modalEditPerm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditPerm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Permission <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="editPermName" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ─── Modal Hapus Permission ────────────────────────────────── --}}
    <div class="modal fade" id="modalHapusPerm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formHapusPerm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger"><i class="bi bi-trash me-2"></i>Hapus Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Hapus permission <code id="hapusPermName"></code>?</p>
                        <p class="text-muted small">Permission ini akan dihapus dari semua role yang menggunakannya.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const permBaseUrl = "{{ url('admin/permissions') }}";

        document.getElementById('modalEditPerm').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('editPermName').value = btn.dataset.name;
            document.getElementById('formEditPerm').action = `${permBaseUrl}/${btn.dataset.id}`;
        });

        document.getElementById('modalHapusPerm').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('hapusPermName').textContent = btn.dataset.name;
            document.getElementById('formHapusPerm').action = `${permBaseUrl}/${btn.dataset.id}`;
        });
    </script>
@endpush
