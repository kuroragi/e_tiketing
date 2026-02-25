{{-- Reject-ticket modal. Trigger: data-bs-toggle="modal" data-bs-target="#rejectModal" data-ticket-id="{{ $ticket->id }}" --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="bi bi-x-circle me-2 text-danger"></i>Tolak Tiket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="ditolak">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Alasan Penolakan <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" name="note" id="rejectReason" rows="3" placeholder="Tuliskan alasan penolakan"
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-2"></i>Tolak Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('rejectModal').addEventListener('show.bs.modal', function(e) {
            var ticketId = e.relatedTarget ? e.relatedTarget.getAttribute('data-ticket-id') : null;
            if (ticketId) {
                document.getElementById('rejectForm').action = '/tiket/' + ticketId + '/status';
            }
        });
    });
</script>
