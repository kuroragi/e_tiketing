{{-- Complete-ticket modal. Trigger: data-bs-toggle="modal" data-bs-target="#completeModal" data-ticket-id="{{ $ticket->id }}" --}}
<div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModalLabel">
                    <i class="bi bi-check-circle me-2 text-success"></i>Selesaikan Tiket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="completeForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="selesai">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="completeNote" class="form-label">Catatan Penyelesaian</label>
                        <textarea class="form-control" name="note" id="completeNote" rows="3"
                            placeholder="Jelaskan penyelesaian yang dilakukan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Tandai Selesai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('completeModal').addEventListener('show.bs.modal', function(e) {
            var ticketId = e.relatedTarget ? e.relatedTarget.getAttribute('data-ticket-id') : null;
            if (ticketId) {
                document.getElementById('completeForm').action = '/tiket/' + ticketId + '/status';
            }
        });
    });
</script>
