@extends('layouts.e-ticket')

@section('title', 'Manajemen Pengguna - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Manajemen Pengguna</li>
@endsection

@section('page-header')
    <h2 class="mb-1"><i class="bi bi-people me-2"></i>Manajemen Pengguna</h2>
    <p class="mb-0">Kelola akun pengguna sistem</p>
@endsection

@section('content')

    {{-- Flash messages --}}
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
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

    {{-- Toolbar --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="searchInput" class="form-control border-start-0"
                            placeholder="Cari nama atau email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="filterRole" class="form-select">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ Str::ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        @php
            $roleBadges = [
                'admin' => ['color' => 'danger', 'icon' => 'bi-shield-fill'],
                'pimpinan' => ['color' => 'warning', 'icon' => 'bi-person-badge-fill'],
                'petugas' => ['color' => 'primary', 'icon' => 'bi-person-gear'],
                'skpd' => ['color' => 'success', 'icon' => 'bi-building'],
            ];
        @endphp
        @foreach ($roles as $role)
            @php $rb = $roleBadges[$role->name] ?? ['color' => 'secondary', 'icon' => 'bi-person']; @endphp
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body py-3">
                        <i class="bi {{ $rb['icon'] }} text-{{ $rb['color'] }} fs-3 mb-1"></i>
                        <div class="fw-bold fs-4">{{ $role->users_count }}</div>
                        <div class="text-muted small">{{ Str::ucfirst($role->name) }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Users Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>Daftar Pengguna</h5>
            <span class="badge bg-secondary">{{ $users->total() }} pengguna</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelPengguna">
                    <thead class="table-light">
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>SKPD / Departemen</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @php
                                $rb = $roleBadges[$user->role] ?? ['color' => 'secondary', 'icon' => 'bi-person'];
                            @endphp
                            <tr data-role="{{ $user->role }}" data-name="{{ strtolower($user->name) }}"
                                data-email="{{ strtolower($user->email) }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-{{ $rb['color'] }} bg-opacity-15 d-flex align-items-center justify-content-center text-{{ $rb['color'] }} fw-bold"
                                            style="width:38px;height:38px;font-size:1rem;flex-shrink:0;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $rb['color'] }}">
                                        <i class="bi {{ $rb['icon'] }} me-1"></i>
                                        {{ Str::ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->department)
                                        <span class="text-sm">{{ $user->department->name }}</span>
                                    @else
                                        <span class="text-muted small"></span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->status === 'aktif')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $user->created_at->format('d M Y') }}</small></td>
                                <td class="text-end">
                                    <button class="btn btn-outline-primary btn-sm me-1" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#modalEditUser"
                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                        data-dept="{{ $user->department_id }}" data-status="{{ $user->status }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @if ($user->id !== Auth::id())
                                        <button class="btn btn-outline-danger btn-sm" title="Hapus" data-bs-toggle="modal"
                                            data-bs-target="#modalHapusUser" data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled title="Akun sendiri">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-people fs-3 d-block mb-2 opacity-50"></i>
                                    Belum ada pengguna terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($users->hasPages())
            <div class="card-footer bg-white">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{--  MODAL TAMBAH PENGGUNA  --}}
    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="labelTambahUser">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.pengguna.store') }}" method="POST" id="formTambahUser">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="labelTambahUser">
                            <i class="bi bi-person-plus me-2 text-primary"></i>Tambah Pengguna Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Nama lengkap pengguna" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="email@domain.go.id" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kata Sandi <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="addPwd" class="form-control"
                                        placeholder="Minimal 8 karakter" required minlength="8">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePwd('addPwd','addPwdIcon')">
                                        <i class="bi bi-eye" id="addPwdIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Konfirmasi Kata Sandi <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="addPwdC"
                                        class="form-control" placeholder="Ulangi kata sandi" required minlength="8">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePwd('addPwdC','addPwdCIcon')">
                                        <i class="bi bi-eye" id="addPwdCIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select" required>
                                    <option value="">-- Pilih Role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ Str::ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Departemen / SKPD</label>
                                <select name="department_id" class="form-select">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
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
                            <i class="bi bi-person-plus me-1"></i>Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--  MODAL EDIT PENGGUNA  --}}
    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="labelEditUser">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditUser" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_edit_id" id="editUserId" value="{{ old('_edit_id') }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="labelEditUser">
                            <i class="bi bi-pencil me-2 text-warning"></i>Edit Pengguna
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="editName"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" id="editEmail"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kata Sandi Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="editPwd" class="form-control"
                                        placeholder="Kosongkan jika tidak diganti" minlength="8">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePwd('editPwd','editPwdIcon')">
                                        <i class="bi bi-eye" id="editPwdIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="editPwdC"
                                        class="form-control" placeholder="Ulangi kata sandi baru" minlength="8">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePwd('editPwdC','editPwdCIcon')">
                                        <i class="bi bi-eye" id="editPwdCIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                <select name="role" id="editRole"
                                    class="form-select @error('role') is-invalid @enderror" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ old('role') == $role->name ? 'selected' : '' }}>
                                            {{ Str::ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Departemen / SKPD</label>
                                <select name="department_id" id="editDept" class="form-select">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}"
                                            {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" id="editStatus"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

    {{--  MODAL HAPUS PENGGUNA  --}}
    <div class="modal fade" id="modalHapusUser" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formHapusUser" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            <i class="bi bi-trash me-2"></i>Hapus Pengguna
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus pengguna <strong id="hapusNama"></strong>?</p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Hapus Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Toggle password visibility
        function togglePwd(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        // Edit modal  populate fields
        document.getElementById('modalEditUser').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            // If triggered programmatically (e.g. after validation error), relatedTarget is null.
            // In that case, the form fields are already pre-filled via Blade old() values.
            if (!btn) return;
            document.getElementById('editName').value = btn.dataset.name;
            document.getElementById('editEmail').value = btn.dataset.email;
            document.getElementById('editRole').value = btn.dataset.role;
            document.getElementById('editDept').value = btn.dataset.dept || '';
            document.getElementById('editStatus').value = btn.dataset.status;
            document.getElementById('editPwd').value = '';
            document.getElementById('editPwdC').value = '';
            document.getElementById('editUserId').value = btn.dataset.id;
            document.getElementById('formEditUser').action = `/admin/pengguna/${btn.dataset.id}`;
        });

        // Hapus modal  populate name and action
        document.getElementById('modalHapusUser').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('hapusNama').textContent = btn.dataset.name;
            document.getElementById('formHapusUser').action = `/admin/pengguna/${btn.dataset.id}`;
        });

        // Live search + filter
        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const role = document.getElementById('filterRole').value.toLowerCase();
            document.querySelectorAll('#tabelPengguna tbody tr[data-name]').forEach(row => {
                const matchName = row.dataset.name.includes(search) || row.dataset.email.includes(search);
                const matchRole = !role || row.dataset.role === role;
                row.style.display = (matchName && matchRole) ? '' : 'none';
            });
        }

        document.getElementById('searchInput').addEventListener('input', filterTable);
        document.getElementById('filterRole').addEventListener('change', filterTable);

        // Re-open modal with errors if validation failed
        @if ($errors->any() && old('_method') !== 'PUT' && old('_method') !== 'DELETE')
            var modalTambah = new bootstrap.Modal(document.getElementById('modalTambahUser'));
            modalTambah.show();
        @elseif ($errors->any() && old('_method') === 'PUT')
            // Set the form action using the old route (stored in a hidden input)
            var editFormEl = document.getElementById('formEditUser');
            var oldId = '{{ old('_edit_id') }}';
            if (oldId) {
                editFormEl.action = '/admin/pengguna/' + oldId;
            }
            var modalEdit = new bootstrap.Modal(document.getElementById('modalEditUser'));
            modalEdit.show();
        @endif
    </script>
@endpush
