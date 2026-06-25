<div>
    {{-- Toolbar --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" wire:model.live="departement_search" class="form-control border-start-0"
                            placeholder="Cari nama SKPD...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="status_search" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary" @click="$dispatch('create-skpd')">
                        <i class="bi bi-plus-circle me-2"></i>Tambah SKPD
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SKPD Grid --}}
    <div class="row g-4" id="skpdGrid">
        @forelse($departments as $dept)
            <div class="col-lg-6 skpd-card" data-name="{{ strtolower($dept->name) }}"
                data-code="{{ strtolower($dept->code) }}" data-status="{{ $dept->status }}">
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
                            @if ($dept->status === 'aktif')
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
                            @if ($dept->head)
                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <small class="text-muted d-block"><i
                                                class="bi bi-person me-1"></i>Kepala</small>
                                        <small class="fw-semibold">{{ $dept->head }}</small>
                                    </div>
                                </div>
                            @endif
                            @if ($dept->contact)
                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <small class="text-muted d-block"><i
                                                class="bi bi-telephone me-1"></i>Kontak</small>
                                        <small class="fw-semibold">{{ $dept->contact }}</small>
                                    </div>
                                </div>
                            @endif
                            @if ($dept->address)
                                <div class="col-12">
                                    <div class="bg-light rounded p-2">
                                        <small class="text-muted d-block"><i
                                                class="bi bi-geo-alt me-1"></i>Alamat</small>
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

                        {{-- PIC Petugas --}}
                        <div class="mb-3">
                            @if ($dept->pic)
                                <div class="alert alert-success py-2 mb-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-person-check-fill text-success"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size:.7rem">PIC Tiket</small>
                                        <span class="fw-semibold small">{{ $dept->pic->name }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-secondary py-2 mb-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-person-dash text-muted"></i>
                                    <small class="text-muted">PIC belum ditentukan</small>
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-fill"
                                @click="$dispatch('edit-skpd', { id: {{ $dept->id }} })">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalHapusSkpd" data-id="{{ $dept->id }}"
                                data-name="{{ $dept->name }}" data-tiket="{{ $dept->tickets_count }}">
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
                        <button class="btn btn-primary" @click="$dispatch('create-skpd')">
                            <i class="bi bi-plus-circle me-2"></i>Tambah SKPD Pertama
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    {{-- @if ($departments->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            <div class="small">{{ $departments->links() }}</div>
        </div>
    @endif --}}


</div>
