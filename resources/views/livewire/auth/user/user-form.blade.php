<div>
    {{--  MODAL TAMBAH/EDIT PENGGUNA  --}}
    <div class="modal fade" id="modalUserForm" wire:ignore.self tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if($user_id)
                                <i class="bi bi-pencil me-2 text-warning"></i>Edit Pengguna
                            @else
                                <i class="bi bi-person-plus me-2 text-primary"></i>Tambah Pengguna Baru
                            @endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control"
                                    placeholder="Nama lengkap pengguna" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" wire:model="email" class="form-control"
                                    placeholder="email@domain.go.id" required>
                                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    @if($user_id) Kata Sandi Baru @else Kata Sandi <span class="text-danger">*</span> @endif
                                </label>
                                <div class="input-group" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" wire:model="password" class="form-control"
                                        placeholder="{{ $user_id ? 'Kosongkan jika tidak diganti' : 'Minimal 8 karakter' }}" 
                                        {{ $user_id ? '' : 'required' }} minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" @click="show = !show">
                                        <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                                    </button>
                                </div>
                                @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    @if($user_id) Konfirmasi Kata Sandi Baru @else Konfirmasi Kata Sandi <span class="text-danger">*</span> @endif
                                </label>
                                <div class="input-group" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" wire:model="password_confirmation"
                                        class="form-control" placeholder="Ulangi kata sandi" {{ $user_id ? '' : 'required' }} minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" @click="show = !show">
                                        <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                <select wire:model.live="role" class="form-select" required>
                                    <option value="">-- Pilih Role --</option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->name }}">{{ Str::ucfirst($r->name) }}</option>
                                    @endforeach
                                </select>
                                @error('role') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4 {{ in_array($role, ['petugas', 'pimpinan']) ? 'd-none' : '' }}">
                                <label class="form-label fw-semibold">Departemen / SKPD</label>
                                <select wire:model="department_id" class="form-select">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4 {{ in_array($role, ['petugas', 'pimpinan']) ? '' : 'd-none' }}">
                                <label class="form-label fw-semibold">Departemen / SKPD</label>
                                <div class="form-control bg-light text-muted d-flex align-items-center gap-1"
                                    style="cursor:default">
                                    <i class="bi bi-building-fill text-primary"></i>
                                    <span>Dinas Kominfo <em>(otomatis)</em></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select wire:model="status" class="form-select" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                                @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn {{ $button_color }}">
                            <div wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1" role="status"></div>
                            <i class="bi {{ $button_icon }} me-1" wire:loading.remove wire:target="save"></i>{{ $button_word }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            let modal = new bootstrap.Modal(document.getElementById('modalUserForm'));
            
            Livewire.on('show-user-form', (event) => {
                modal.show();
            });
            
            Livewire.on('hide-user-form', (event) => {
                modal.hide();
            });
        });
    </script>
    @endpush
</div>
