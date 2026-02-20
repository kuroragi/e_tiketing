@extends('layouts.e-ticket')

@section('title', 'Manajemen Role - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Manajemen Role</li>
@endsection

@section('page-header')
    <h2 class="mb-1"><i class="bi bi-shield-lock me-2"></i>Manajemen Role</h2>
    <p class="mb-0">Kelola role dan hak akses sistem</p>
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Role List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Daftar Role</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahRole">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Role
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Role</th>
                                    <th>Jumlah Pengguna</th>
                                    <th>Permissions</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>
                                            @php
                                                $badgeColor = match ($role->name) {
                                                    'admin' => 'danger',
                                                    'pimpinan' => 'warning',
                                                    'petugas' => 'primary',
                                                    'skpd' => 'success',
                                                    default => 'secondary',
                                                };
                                                $icon = match ($role->name) {
                                                    'admin' => 'bi-shield-fill',
                                                    'pimpinan' => 'bi-person-badge-fill',
                                                    'petugas' => 'bi-person-gear',
                                                    'skpd' => 'bi-building',
                                                    default => 'bi-person',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }} fs-6 px-3 py-2">
                                                <i class="bi {{ $icon }} me-1"></i>
                                                {{ Str::ucfirst($role->name) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $role->users_count }}
                                                pengguna</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($role->permissions->take(4) as $perm)
                                                    <span class="badge bg-info text-dark small">{{ $perm->name }}</span>
                                                @endforeach
                                                @if ($role->permissions->count() > 4)
                                                    <span
                                                        class="badge bg-secondary small">+{{ $role->permissions->count() - 4 }}
                                                        lagi</span>
                                                @endif
                                                @if ($role->permissions->count() === 0)
                                                    <span class="text-muted small fst-italic">Tidak ada permission</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal"
                                                data-bs-target="#modalEditRole" data-id="{{ $role->id }}"
                                                data-name="{{ $role->name }}"
                                                data-permissions="{{ $role->permissions->pluck('id')->join(',') }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if (!in_array($role->name, ['admin', 'petugas', 'skpd', 'pimpinan']))
                                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modalHapusRole" data-id="{{ $role->id }}"
                                                    data-name="{{ $role->name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-outline-secondary btn-sm" disabled
                                                    title="Role sistem tidak dapat dihapus">
                                                    <i class="bi bi-lock"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada role terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permission Groups -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0"><i class="bi bi-key me-2 text-warning"></i>Semua Permission</h5>
                </div>
                <div class="card-body">
                    @foreach ($allPermissions as $group => $perms)
                        <div class="mb-3">
                            <p class="text-muted small text-uppercase fw-bold mb-1">{{ $group }}</p>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($perms as $perm)
                                    <span class="badge bg-light text-dark border small">{{ $perm->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Modal Tambah Role ─────────────────────────────────────── --}}
    <div class="modal fade" id="modalTambahRole" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Role Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="contoh: supervisor"
                                required>
                            <div class="form-text">Gunakan huruf kecil, tanpa spasi (gunakan strip jika perlu).</div>
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Permissions</label>
                            <div class="row g-2 mt-1">
                                @foreach (\Spatie\Permission\Models\Permission::orderBy('name')->get() as $perm)
                                    <div class="col-md-4 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                value="{{ $perm->id }}" id="tperm_{{ $perm->id }}">
                                            <label class="form-check-label small" for="tperm_{{ $perm->id }}">
                                                {{ $perm->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ─── Modal Edit Role ───────────────────────────────────────── --}}
    <div class="modal fade" id="modalEditRole" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditRole" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editRoleName" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Permissions</label>
                            <div class="row g-2 mt-1">
                                @foreach (\Spatie\Permission\Models\Permission::orderBy('name')->get() as $perm)
                                    <div class="col-md-4 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input edit-perm-check" type="checkbox"
                                                name="permissions[]" value="{{ $perm->id }}"
                                                id="eperm_{{ $perm->id }}">
                                            <label class="form-check-label small" for="eperm_{{ $perm->id }}">
                                                {{ $perm->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ─── Modal Hapus Role ──────────────────────────────────────── --}}
    <div class="modal fade" id="modalHapusRole" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formHapusRole" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger"><i class="bi bi-trash me-2"></i>Hapus Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus role <strong id="hapusRoleName"></strong>?</p>
                        <p class="text-muted small">Semua pengguna dengan role ini akan kehilangan akses terkait.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Hapus Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Edit Role Modal
        document.getElementById('modalEditRole').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const perms = btn.dataset.permissions ? btn.dataset.permissions.split(',') : [];

            document.getElementById('editRoleName').value = name;
            document.getElementById('formEditRole').action = `/admin/roles/${id}`;

            // Reset all checkboxes
            document.querySelectorAll('.edit-perm-check').forEach(cb => {
                cb.checked = perms.includes(cb.value);
            });
        });

        // Hapus Role Modal
        document.getElementById('modalHapusRole').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('hapusRoleName').textContent = btn.dataset.name;
            document.getElementById('formHapusRole').action = `/admin/roles/${btn.dataset.id}`;
        });
    </script>
@endpush
