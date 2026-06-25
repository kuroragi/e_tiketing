<div>


    {{--  MODAL TAMBAH/EDIT SKPD  --}}
    <div class="modal fade" id="modalSkpdForm" wire:ignore.self tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if($department_id)
                                <i class="bi bi-pencil me-2 text-warning"></i>Edit SKPD
                            @else
                                <i class="bi bi-building-add me-2 text-primary"></i>Tambah SKPD Baru
                            @endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama SKPD <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control"
                                placeholder="Dinas Komunikasi dan Informatika" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kode / Singkatan <span
                                    class="text-danger">*</span></label>
                            <input type="text" wire:model="code" class="form-control text-uppercase"
                                placeholder="KOMINFO" required oninput="this.value=this.value.toUpperCase()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Kepala</label>
                            <input type="text" wire:model="head" class="form-control"
                                placeholder="Nama kepala dinas">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontak</label>
                            <input type="text" wire:model="contact" class="form-control" placeholder="0751-xxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea wire:model="address" class="form-control" rows="2" placeholder="Jl. ..."></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select wire:model="status" class="form-select" required>
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-check me-1 text-success"></i>Petugas PIC
                            </label>
                            <select wire:model="pic_id" class="form-select">
                                <option value="">— Belum ada PIC —</option>
                                @foreach ($petugasList as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                PIC menerima tiket kategori <strong>PIC</strong> dari SKPD ini secara otomatis.
                            </div>
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
            let modal = new bootstrap.Modal(document.getElementById('modalSkpdForm'));
            
            Livewire.on('show-skpd-form', (event) => {
                modal.show();
            });
            
            Livewire.on('hide-skpd-form', (event) => {
                modal.hide();
            });
        });
    </script>
    @endpush
</div>
