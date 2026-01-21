<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">Tugaskan Petugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignForm">
                    <div class="mb-3">
                        <label for="petugasSelect" class="form-label">Pilih Petugas</label>
                        <select class="form-select" id="petugasSelect">
                            <option value="">-- Pilih --</option>
                            <option value="1">Ahmad Fauzi</option>
                            <option value="2">Siti Aminah</option>
                            <option value="3">Rizki Pratama</option>
                            <option value="4">Desi Marlina</option>
                            <option value="5">Budi Santoso</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignNote" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" id="assignNote" rows="3" placeholder="Tambahkan catatan"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Simpan Penugasan</button>
            </div>
        </div>
    </div>
</div>
