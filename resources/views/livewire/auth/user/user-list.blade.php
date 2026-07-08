<div>


    {{-- Toolbar --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" wire:model.live="search" class="form-control border-start-0"
                            placeholder="Cari nama">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="role_search" class="form-select">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ Str::ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary" @click="$dispatch('create-user')">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>
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
                                        @click="$dispatch('edit-user', { id: {{ $user->id }} })">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @if ($user->id !== Auth::id())
                                        <button class="btn btn-outline-danger btn-sm" title="Hapus"
                                            data-bs-toggle="modal" data-bs-target="#modalHapusUser"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}">
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
</div>
