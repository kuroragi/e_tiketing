<div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModalLabel">Selesaikan Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="completeForm">
                    <div class="mb-3">
                        <label for="completeNote" class="form-label">Catatan Penyelesaian</label>
                        <textarea class="form-control" id="completeNote" rows="3" placeholder="Jelaskan penyelesaian yang dilakukan"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="notifyRequester">
                        <label class="form-check-label" for="notifyRequester">
                            Beri tahu pemohon melalui email
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tandai Selesai</button>
            </div>
        </div>
    </div>
</div>
