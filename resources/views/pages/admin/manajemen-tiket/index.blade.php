@extends('layouts.e-ticket')

@section('title', 'Manajemen Tiket - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Manajemen Tiket</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-ticket-detailed me-2"></i>
        Manajemen dan Assignment Tiket
    </h2>
    <p class="mb-0">Kelola assignment tiket dengan dua metode: Otomatis dan Manual</p>
@endsection

@section('content')
    <!-- Navigation Tabs -->
    <div class="nav-tabs-container mb-4">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending"
                    type="button" role="tab">
                    <i class="bi bi-hourglass-bottom me-2"></i>
                    Tiket Pending
                    <span class="badge bg-warning ms-2">{{ count($pendingTickets) }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="auto-tab" data-bs-toggle="tab" data-bs-target="#auto" type="button"
                    role="tab">
                    <i class="bi bi-lightning-fill me-2"></i>
                    Auto Assignment
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button"
                    role="tab">
                    <i class="bi bi-hand-index me-2"></i>
                    Manual Assignment
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button"
                    role="tab">
                    <i class="bi bi-clock-history me-2"></i>
                    History Assignment
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Tiket Pending -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>
                        Daftar Tiket Belum Diassign
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Tiket</th>
                                    <th>Judul</th>
                                    <th>SKPD</th>
                                    <th>Jenis Pekerjaan</th>
                                    <th>Prioritas</th>
                                    <th>Waktu Masuk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingTickets as $ticket)
                                    <tr>
                                        <td>
                                            <strong>{{ $ticket['id'] }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $ticket['judul'] }}</strong><br>
                                            <small class="text-muted">{{ substr($ticket['deskripsi'], 0, 40) }}...</small>
                                        </td>
                                        <td>{{ $ticket['skpd'] }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $ticket['jenis_pekerjaan'] }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $ticket['prioritas'] === 'Urgent' ? 'danger' : ($ticket['prioritas'] === 'Tinggi' ? 'warning' : 'success') }}">
                                                {{ $ticket['prioritas'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $ticket['tanggal_masuk']->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary"
                                                    onclick="showAutoAssignModal('{{ $ticket['id'] }}', '{{ $ticket['jenis_pekerjaan'] }}', '{{ $ticket['prioritas'] }}')">
                                                    <i class="bi bi-lightning-fill me-1"></i>Auto
                                                </button>
                                                <button type="button" class="btn btn-outline-info"
                                                    onclick="showManualAssignModal('{{ $ticket['id'] }}')">
                                                    <i class="bi bi-hand-index me-1"></i>Manual
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto Assignment -->
        <div class="tab-pane fade" id="auto" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>
                        Konfigurasi Auto Assignment
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Cara Kerja:</strong> Sistem akan otomatis menentukan petugas berdasarkan jenis pekerjaan,
                        prioritas, dan keahlian petugas. Konfigurasi di bawah menunjukkan aturan assignment untuk setiap
                        jenis pekerjaan.
                    </div>

                    <div class="accordion" id="autoAssignAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse1">
                                    <i class="bi bi-clipboard-data me-2"></i>
                                    <strong>PIC Presensi</strong>
                                    <span class="ms-auto"><span class="badge bg-primary">Aktif</span></span>
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show"
                                data-bs-parent="#autoAssignAccordion">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kondisi</th>
                                                    <th>Petugas Utama</th>
                                                    <th>Petugas Backup</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><span class="badge bg-danger">Urgent</span></td>
                                                    <td><strong>Ahmad Fauzi (A)</strong> - 70%</td>
                                                    <td><strong>Siti Aminah (B)</strong> - 30%</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge bg-warning">Tinggi</span></td>
                                                    <td><strong>Siti Aminah (B)</strong> - 60%</td>
                                                    <td><strong>Ahmad Fauzi (A)</strong> - 40%</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse2">
                                    <i class="bi bi-globe me-2"></i>
                                    <strong>Perbaikan Portal</strong>
                                    <span class="ms-auto"><span class="badge bg-primary">Aktif</span></span>
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse"
                                data-bs-parent="#autoAssignAccordion">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kondisi</th>
                                                    <th>Petugas Utama</th>
                                                    <th>Petugas Backup</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><span class="badge bg-danger">Urgent</span></td>
                                                    <td><strong>Siti Aminah (B)</strong> - 60%</td>
                                                    <td><strong>Rizki Pratama (C)</strong> - 40%</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge bg-warning">Tinggi</span></td>
                                                    <td><strong>Rizki Pratama (C)</strong> - 70%</td>
                                                    <td><strong>Siti Aminah (B)</strong> - 30%</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse3">
                                    <i class="bi bi-tools me-2"></i>
                                    <strong>Troubleshooting</strong>
                                    <span class="ms-auto"><span class="badge bg-primary">Aktif</span></span>
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse"
                                data-bs-parent="#autoAssignAccordion">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kondisi</th>
                                                    <th>Petugas Utama</th>
                                                    <th>Petugas Backup</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><span class="badge bg-danger">Urgent</span></td>
                                                    <td><strong>Rizki Pratama (C)</strong> - 50%</td>
                                                    <td><strong>Desi Marlina (D)</strong> - 50%</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Simpan Konfigurasi
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Reset ke Default
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manual Assignment -->
        <div class="tab-pane fade" id="manual" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-hand-index me-2"></i>
                        Manual Assignment
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Admin dan pihak berwenang dapat memilih petugas secara manual sesuai
                        keputusan.</p>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6>Petugas Tersedia</h6>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <h6 class="mb-2">Ahmad Fauzi (A)</h6>
                                    <small class="text-muted">Keahlian: PIC Presensi, Troubleshooting</small><br>
                                    <span class="badge bg-info mt-2">Load: 2 tiket</span>
                                </div>
                                <div class="list-group-item">
                                    <h6 class="mb-2">Siti Aminah (B)</h6>
                                    <small class="text-muted">Keahlian: PIC Presensi, Perbaikan Portal</small><br>
                                    <span class="badge bg-warning mt-2">Load: 3 tiket</span>
                                </div>
                                <div class="list-group-item">
                                    <h6 class="mb-2">Rizki Pratama (C)</h6>
                                    <small class="text-muted">Keahlian: Perbaikan Portal, Troubleshooting</small><br>
                                    <span class="badge bg-success mt-2">Load: 1 tiket</span>
                                </div>
                                <div class="list-group-item">
                                    <h6 class="mb-2">Desi Marlina (D)</h6>
                                    <small class="text-muted">Keahlian: Troubleshooting, Maintenance Server</small><br>
                                    <span class="badge bg-info mt-2">Load: 2 tiket</span>
                                </div>
                                <div class="list-group-item">
                                    <h6 class="mb-2">Budi Santoso (E)</h6>
                                    <small class="text-muted">Keahlian: Maintenance Server, PIC Presensi</small><br>
                                    <span class="badge bg-success mt-2">Load: 0 tiket (Tersedia)</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6>Catatan</h6>
                            <div class="alert alert-info" role="alert">
                                <p><i class="bi bi-info-circle me-2"></i><strong>Load Petugas:</strong> Menunjukkan jumlah
                                    tiket yang sedang dikerjakan oleh petugas.</p>
                                <p class="mb-0"><i class="bi bi-info-circle me-2"></i><strong>Rekomendasi:</strong>
                                    Assign tiket ke petugas dengan load terendah untuk distribusi beban kerja yang lebih
                                    merata.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Assignment -->
        <div class="tab-pane fade" id="history" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        History Assignment
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Lihat riwayat assignment tiket baik yang otomatis maupun manual.</p>
                    <a href="{{ route('ticket.management.history') }}" class="btn btn-primary">
                        <i class="bi bi-eye me-2"></i>
                        Lihat Detail History
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Unified Auto Assign Modal -->
    <div class="modal fade" id="autoAssignModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Auto Assignment - <span id="autoTicketId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        Sistem akan secara otomatis memilih petugas berdasarkan konfigurasi assignment dan beban kerja saat
                        ini.
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Jenis Pekerjaan</small>
                            <p><strong id="autoJenisPekerjaan"></strong></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Prioritas</small>
                            <p><strong id="autoPrioritas"></strong></p>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <h6><i class="bi bi-person-check me-2"></i>Petugas yang akan dipilih:</h6>
                        <p class="mb-0"><strong>Sistem sedang menghitung...</strong></p>
                        <small class="text-muted">Algoritma: Jenis Pekerjaan + Prioritas + Load Petugas</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="autoAssignBtn"
                        onclick="confirmAutoAssign(currentTicketId)">
                        <i class="bi bi-check-circle me-2"></i>Assign Otomatis
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Unified Manual Assign Modal -->
    <div class="modal fade" id="manualAssignModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manual Assignment - <span id="manualTicketId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Petugas</label>
                        <select class="form-select" id="petugasSelect">
                            <option selected disabled>-- Pilih Petugas --</option>
                            <option>Ahmad Fauzi - Load: 2 tiket</option>
                            <option>Siti Aminah - Load: 3 tiket</option>
                            <option>Rizki Pratama - Load: 1 tiket</option>
                            <option>Desi Marlina - Load: 2 tiket</option>
                            <option>Budi Santoso - Load: 0 tiket</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan untuk Petugas (Opsional)</label>
                        <textarea class="form-control" id="catatanText" rows="3" placeholder="Tambahkan catatan khusus jika ada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="manualAssignBtn"
                        onclick="confirmManualAssign(currentTicketId)">
                        <i class="bi bi-check-circle me-2"></i>Assign Manual
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentTicketId = null;
        let autoAssignModal = null;
        let manualAssignModal = null;

        // Initialize modals only once on page load
        document.addEventListener('DOMContentLoaded', function() {
            autoAssignModal = new bootstrap.Modal(document.getElementById('autoAssignModal'), {
                backdrop: 'static',
                keyboard: false
            });

            manualAssignModal = new bootstrap.Modal(document.getElementById('manualAssignModal'), {
                backdrop: 'static',
                keyboard: false
            });
        });

        // Show Auto Assign Modal
        function showAutoAssignModal(ticketId, jenisPekerjaan, prioritas) {
            currentTicketId = ticketId;
            document.getElementById('autoTicketId').textContent = ticketId;
            document.getElementById('autoJenisPekerjaan').textContent = jenisPekerjaan;
            document.getElementById('autoPrioritas').textContent = prioritas;

            autoAssignModal.show();
        }

        // Show Manual Assign Modal
        function showManualAssignModal(ticketId) {
            currentTicketId = ticketId;
            document.getElementById('manualTicketId').textContent = ticketId;
            document.getElementById('petugasSelect').value = '';
            document.getElementById('catatanText').value = '';

            manualAssignModal.show();
        }

        function confirmAutoAssign(ticketId) {
            if (!ticketId) {
                showToast('ID Tiket tidak valid', 'danger');
                return;
            }

            const btn = document.getElementById('autoAssignBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

            fetch(`/api/ticket/auto-assign/${ticketId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    autoAssignModal.hide();
                    showToast('Tiket berhasil diassign secara otomatis!', 'success');
                    setTimeout(() => location.reload(), 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Gagal mengassign tiket. Silakan coba lagi.', 'danger');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        }

        function confirmManualAssign(ticketId) {
            if (!ticketId) {
                showToast('ID Tiket tidak valid', 'danger');
                return;
            }

            const petugasSelect = document.getElementById('petugasSelect');
            const petugas = petugasSelect.value;

            if (!petugas) {
                showToast('Pilih petugas terlebih dahulu', 'warning');
                return;
            }

            const btn = document.getElementById('manualAssignBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

            fetch(`/api/ticket/manual-assign/${ticketId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        petugas: petugas
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    manualAssignModal.hide();
                    showToast('Tiket berhasil diassign secara manual!', 'success');
                    setTimeout(() => location.reload(), 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Gagal mengassign tiket. Silakan coba lagi.', 'danger');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            toast.setAttribute('role', 'alert');
            toast.style.zIndex = '9999';
            toast.style.maxWidth = '500px';
            toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
            document.body.appendChild(toast);

            const bsAlert = new bootstrap.Alert(toast);
            setTimeout(() => bsAlert.close(), 4000);
        }
    </script>
@endpush
