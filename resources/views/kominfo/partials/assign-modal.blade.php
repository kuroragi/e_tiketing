{{-- Assign modal; requires $petugasList in scope. Trigger with data-bs-toggle="modal" data-bs-target="#assignModal" data-ticket-id="{{ $ticket->id }}" --}}
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Tugaskan Petugas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="petugasSelect" class="form-label">Pilih Petugas <span class="text-danger">*</span></label>
                        <select class="form-select" name="assignee_id" id="petugasSelect" required>
                            <option value="">-- Pilih Petugas --</option>
                            @foreach ($petugasList ?? [] as $petugas)
                                <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignNote" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" name="note" id="assignNote" rows="3" placeholder="Tambahkan catatan penugasan"></textarea>
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
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('assignModal').addEventListener('show.bs.modal', function (e) {
        var ticketId = e.relatedTarget ? e.relatedTarget.getAttribute('data-ticket-id') : null;
        if (ticketId) {
            document.getElementById('assignForm').action = '/tiket/' + ticketId + '/assign';
        }
    });
});
</script>
