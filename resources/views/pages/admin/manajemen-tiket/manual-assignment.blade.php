@extends('layouts.e-ticket')

@section('title', 'Manual Assignment - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ticket.management.index') }}">Manajemen Tiket</a></li>
    <li class="breadcrumb-item active">Manual Assignment</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-hand-index me-2"></i>
        Manual Assignment Tiket
    </h2>
    <p class="mb-0">Pilih petugas secara manual sesuai keputusan pihak berwenang</p>
@endsection

@section('content')
    <!-- Info Alert -->
    <div class="alert alert-primary border-0 mb-4" role="alert">
        <div class="d-flex">
            <i class="bi bi-person-check me-3 display-6"></i>
            <div>
                <h5 class="alert-heading">Cara Kerja Manual Assignment</h5>
                <p class="mb-2">Admin dan pihak berwenang dapat secara fleksibel memilih petugas yang akan menangani tiket
                    berdasarkan:</p>
                <ul class="mb-0">
                    <li><strong>Keputusan Pimpinan:</strong> Prioritas sesuai keputusan manajemen</li>
                    <li><strong>Keahlian Khusus:</strong> Pilih petugas dengan keahlian spesifik untuk pekerjaan kompleks
                    </li>
                    <li><strong>Ketersediaan:</strong> Pertimbangkan beban kerja saat ini</li>
                    <li><strong>Preferensi:</strong> Assign berdasarkan preferensi atau pembelajaran sebelumnya</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Tickets -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-hourglass-bottom me-2"></i>
                        Tiket Pending ({{ count($pendingTickets) }})
                    </h5>
                </div>
                <div class="card-body">
                    @foreach ($pendingTickets as $ticket)
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="mb-1">
                                            <strong>{{ $ticket['id'] }}</strong> - {{ $ticket['judul'] }}
                                        </h6>
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-building me-1"></i>{{ $ticket['skpd'] }} |
                                            <i class="bi bi-person me-1"></i>{{ $ticket['pemohon'] }}
                                        </p>
                                        <div class="mb-2">
                                            <span class="badge bg-info">{{ $ticket['jenis_pekerjaan'] }}</span>
                                            <span
                                                class="badge bg-{{ $ticket['prioritas'] === 'Urgent' ? 'danger' : 'warning' }}">{{ $ticket['prioritas'] }}</span>
                                            <span
                                                class="badge bg-secondary">{{ $ticket['tanggal_masuk']->diffForHumans() }}</span>
                                        </div>
                                        <small class="text-muted">{{ $ticket['deskripsi'] }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                                            data-bs-target="#assignModal{{ $ticket['id'] }}">
                                            <i class="bi bi-person-check me-2"></i>
                                            Assign Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Modal -->
                        <div class="modal fade" id="assignModal{{ $ticket['id'] }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Assign Tiket - {{ $ticket['id'] }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Ticket Info -->
                                        <div class="alert alert-light border mb-4" role="alert">
                                            <h6 class="mb-2">{{ $ticket['judul'] }}</h6>
                                            <small class="text-muted">
                                                <strong>SKPD:</strong> {{ $ticket['skpd'] }} |
                                                <strong>Jenis:</strong> {{ $ticket['jenis_pekerjaan'] }} |
                                                <strong>Prioritas:</strong> {{ $ticket['prioritas'] }}
                                            </small>
                                        </div>

                                        <!-- Petugas Selection -->
                                        <div class="mb-4">
                                            <label class="form-label"><strong>Pilih Petugas</strong></label>
                                            <div class="row">
                                                @foreach ($petugasList as $petugas)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card cursor-pointer petugas-card"
                                                            data-petugas="{{ $petugas['id'] }}"
                                                            onclick="selectPetugas(this, {{ $petugas['id'] }}, '{{ $ticket['id'] }}')">
                                                            <div class="card-body">
                                                                <div class="form-check">
                                                                    <input class="form-check-input petugas-radio"
                                                                        type="radio" name="petugas_{{ $ticket['id'] }}"
                                                                        value="{{ $petugas['id'] }}"
                                                                        id="petugas_{{ $ticket['id'] }}_{{ $petugas['id'] }}">
                                                                    <label class="form-check-label w-100"
                                                                        for="petugas_{{ $ticket['id'] }}_{{ $petugas['id'] }}">
                                                                        <h6 class="mb-1">{{ $petugas['nama'] }}</h6>
                                                                        <small
                                                                            class="text-muted d-block mb-2">{{ $petugas['skill'] }}</small>
                                                                        <span class="badge bg-warning">Load:
                                                                            {{ $petugas['load'] }} tiket</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Catatan -->
                                        <div class="mb-4">
                                            <label class="form-label">Catatan untuk Petugas (Opsional)</label>
                                            <textarea class="form-control" rows="3" placeholder="Tambahkan instruksi atau catatan khusus jika diperlukan..."></textarea>
                                        </div>

                                        <!-- Persetujuan -->
                                        <div class="alert alert-info" role="alert">
                                            <h6 class="alert-heading mb-2">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Informasi Penting
                                            </h6>
                                            <small>
                                                Pastikan petugas yang dipilih memiliki keahlian yang sesuai dengan jenis
                                                pekerjaan.
                                                Pertimbangkan juga beban kerja saat ini untuk distribusi yang lebih merata.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="confirmAssignment('{{ $ticket['id'] }}')">
                                            <i class="bi bi-check-circle me-2"></i>
                                            Assign Tiket
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Petugas Info -->
        <div class="col-lg-4 mb-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>
                        Status Petugas
                    </h5>
                </div>
                <div class="card-body">
                    @foreach ($petugasList as $petugas)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">{{ $petugas['nama'] }}</h6>
                                <span
                                    class="badge bg-{{ $petugas['load'] === 0 ? 'success' : ($petugas['load'] <= 2 ? 'info' : 'warning') }}">
                                    {{ $petugas['load'] }} tiket
                                </span>
                            </div>
                            <small class="text-muted d-block mb-2">{{ $petugas['skill'] }}</small>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar" style="width: {{ ($petugas['load'] / 5) * 100 }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-book me-2"></i>
                        Rekomendasi
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Budi Santoso</strong>
                            <small class="text-success d-block">Tersedia (0 tiket) - Cocok untuk Maintenance</small>
                        </li>
                        <li class="mb-2">
                            <strong>Rizki Pratama</strong>
                            <small class="text-success d-block">Ringan (1 tiket) - Cocok untuk Portal</small>
                        </li>
                        <li>
                            <strong>Ahmad Fauzi</strong>
                            <small class="text-info d-block">Sedang (2 tiket) - Tersedia untuk Presensi</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function selectPetugas(element, petugasId, ticketId) {
            const radios = document.querySelectorAll(`input[name="petugas_${ticketId}"]`);
            radios.forEach(radio => {
                radio.parentElement.parentElement.parentElement.classList.remove('border-primary',
                    'bg-primary-light');
            });

            element.classList.add('border', 'border-primary');
            document.getElementById(`petugas_${ticketId}_${petugasId}`).checked = true;
        }

        function confirmAssignment(ticketId) {
            const selectedPetugas = document.querySelector(`input[name="petugas_${ticketId}"]:checked`);

            if (!selectedPetugas) {
                showToast('Pilih petugas terlebih dahulu', 'warning');
                return;
            }

            fetch(`/api/ticket/manual-assign/${ticketId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        petugas_id: selectedPetugas.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    showToast('Tiket berhasil diassign!', 'success');
                    setTimeout(() => location.reload(), 1500);
                });
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} alert-dismissible position-fixed top-0 end-0 m-3`;
            toast.style.zIndex = '9999';
            toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
            document.body.appendChild(toast);

            setTimeout(() => toast.remove(), 3000);
        }
    </script>
@endpush
