{{-- Assign modal; requires $petugasList in scope. Trigger with data-bs-toggle="modal" data-bs-target="#assignModal" data-ticket-id="..." --}}
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">
                    <i class="bi bi-people me-2"></i>Tugaskan Petugas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="text-muted small mb-3">Pilih satu atau lebih petugas yang akan menangani tiket ini.</p>
                    <div class="row g-2" id="assignPetugasList">
                        @foreach ($petugasList ?? [] as $petugas)
                            @php
                                $cnt = $petugas->aktif_count ?? 0;
                                $badgeClass = $cnt === 0 ? 'success' : ($cnt <= 3 ? 'info' : 'warning');
                            @endphp
                            <div class="col-md-6">
                                <div class="card border petugas-assign-card h-100" style="cursor:pointer"
                                    onclick="togglePetugasCard(this)">
                                    <div class="card-body py-2 px-3 d-flex align-items-center gap-3">
                                        <input class="form-check-input flex-shrink-0" type="checkbox"
                                            name="assignee_ids[]" value="{{ $petugas->id }}"
                                            id="assignModal_p_{{ $petugas->id }}" onclick="event.stopPropagation()">
                                        <label class="flex-grow-1 mb-0" for="assignModal_p_{{ $petugas->id }}"
                                            style="cursor:pointer">
                                            <div class="fw-semibold">{{ $petugas->name }}</div>
                                            <span class="badge bg-{{ $badgeClass }}">{{ $cnt }} tiket
                                                aktif</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <label for="assignNote" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" name="catatan" id="assignNote" rows="2" placeholder="Tambahkan catatan penugasan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Simpan Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('assignModal').addEventListener('show.bs.modal', function(e) {
            var ticketId = e.relatedTarget ? e.relatedTarget.getAttribute('data-ticket-id') : null;
            if (ticketId) {
                document.getElementById('assignForm').action = '/tiket/' + ticketId + '/assign';
            }
            // Uncheck all when opening (no pre-selection from list view)
            document.querySelectorAll('#assignPetugasList .form-check-input').forEach(function(cb) {
                cb.checked = false;
                cb.closest('.petugas-assign-card').classList.remove('border-primary',
                    'bg-primary-subtle');
            });
        });
    });

    function togglePetugasCard(card) {
        var cb = card.querySelector('input[type=checkbox]');
        cb.checked = !cb.checked;
        card.classList.toggle('border-primary', cb.checked);
        card.classList.toggle('bg-primary-subtle', cb.checked);
    }
</script>
