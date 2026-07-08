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

    @livewire('auth.user.user-list', ['_roleBadges' => $roleBadges])\

    @livewire('auth.user.user-form')



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
        // Toggle SKPD field: hide for petugas/pimpinan (mereka otomatis Kominfo)
        const internalRoles = ['petugas', 'pimpinan'];

        function toggleDeptByRole(roleValue, deptGroupId, kominfoInfoId) {
            const isInternal = internalRoles.includes(roleValue);
            document.getElementById(deptGroupId).classList.toggle('d-none', isInternal);
            document.getElementById(kominfoInfoId).classList.toggle('d-none', !isInternal);
        }

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


    </script>
@endpush
