@extends('layouts.e-ticket')

@section('title', 'Auto Assignment - E-Ticket Kominfo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ticket.management.index') }}">Manajemen Tiket</a></li>
    <li class="breadcrumb-item active">Auto Assignment</li>
@endsection

@section('page-header')
    <h2 class="mb-1">
        <i class="bi bi-lightning-fill me-2"></i>
        Konfigurasi Auto Assignment
    </h2>
    <p class="mb-0">Atur aturan otomatis pemilihan petugas berdasarkan jenis pekerjaan dan prioritas</p>
@endsection

@section('content')
<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab">
            <i class="bi bi-sliders me-2"></i>Konfigurasi
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="workload-tab" data-bs-toggle="tab" data-bs-target="#workload" type="button" role="tab">
            <i class="bi bi-percent me-2"></i>Beban Kerja Petugas
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="simulation-tab" data-bs-toggle="tab" data-bs-target="#simulation" type="button" role="tab">
            <i class="bi bi-play-circle me-2"></i>Simulasi
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
            <i class="bi bi-clock-history me-2"></i>History
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- Konfigurasi Tab -->
    <div class="tab-pane fade show active" id="config" role="tabpanel"
<!-- Info Alert -->
<div class="alert alert-info border-0 mb-4" role="alert">
    <div class="d-flex">
        <i class="bi bi-info-circle me-3 display-6"></i>
        <div>
            <h5 class="alert-heading">Cara Kerja Auto Assignment</h5>
            <p class="mb-2">Sistem akan secara otomatis menentukan petugas yang akan menangani tiket berdasarkan:</p>
            <ul class="mb-0">
                <li><strong>Jenis Pekerjaan:</strong> Sistem memilih petugas yang sesuai dengan keahlian</li>
                <li><strong>Prioritas Tiket:</strong> Tiket urgent/tinggi akan diprioritaskan</li>
                <li><strong>Beban Kerja Petugas:</strong> Distribusi merata berdasarkan jumlah tiket yang sedang dikerjakan</li>
                <li><strong>Load Balancing:</strong> Algoritma round-robin untuk keseimbangan beban</li>
            </ul>
        </div>
    </div>
</div>

<!-- Petugas Overview -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3">Daftar Petugas dan Keahlian</h5>
    </div>
    @foreach($petugasList as $petugas)
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">{{ $petugas['nama'] }}</h6>
                    <span class="badge bg-primary">{{ $petugas['kode'] }}</span>
                </div>
                <small class="text-muted d-block mb-3">Keahlian:</small>
                <div>
                    @foreach($petugas['keahlian'] as $skill)
                    <span class="badge bg-light text-dark mb-1">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Configuration Form -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-sliders me-2"></i>
            Konfigurasi Aturan Assignment
        </h5>
    </div>
    <div class="card-body">
        <form id="autoAssignmentForm">
            @foreach($assignmentRules as $index => $rule)
            <div class="mb-4">
                <div class="border-bottom pb-3 mb-3">
                    <h6 class="mb-3">
                        <i class="bi bi-diagram-2 me-2"></i>
                        {{ $rule['jenis_pekerjaan'] }}
                    </h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="enable{{ $index }}" checked>
                        <label class="form-check-label" for="enable{{ $index }}">
                            Aktifkan auto assignment untuk {{ $rule['jenis_pekerjaan'] }}
                        </label>
                    </div>
                </div>

                @foreach($rule['rules'] as $ruleIdx => $ruleItem)
                <div class="row align-items-end mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Kondisi</label>
                        <input type="text" class="form-control" value="{{ $ruleItem['kondisi'] }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Petugas</label>
                        <select class="form-select">
                            <option selected>{{ $ruleItem['petugas'] }}</option>
                            @foreach($petugasList as $petugas)
                            <option>{{ $petugas['nama'] }} ({{ $petugas['kode'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Persentase</label>
                        <div class="input-group">
                            <input type="number" class="form-control" value="{{ $ruleItem['persentase'] }}" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                    </div>
                </div>
                @endforeach

                <button type="button" class="btn btn-sm btn-outline-primary mb-3">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Aturan
                </button>
            </div>
            @endforeach

            <hr>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-lg btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        Simpan Konfigurasi
                    </button>
                    <button type="reset" class="btn btn-lg btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Reset
                    </button>
                    <button type="button" class="btn btn-lg btn-outline-secondary" onclick="exportConfig()">
                        <i class="bi bi-download me-2"></i>
                        Export
                    </button>
                    <button type="button" class="btn btn-lg btn-outline-secondary" onclick="importConfig()">
                        <i class="bi bi-upload me-2"></i>
                        Import
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Algorithm Explanation -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-cpu me-2"></i>
            Algoritma Pemilihan Petugas
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-4">
                <h6>Langkah 1: Identifikasi Keahlian</h6>
                <p class="text-muted">Sistem mengidentifikasi petugas yang memiliki keahlian sesuai jenis pekerjaan tiket.</p>
                <div class="bg-light p-3 rounded">
                    <code>
                        petugas_cocok = <br>
                        &nbsp;&nbsp;keahlian petugas ∩ jenis pekerjaan tiket
                    </code>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <h6>Langkah 2: Filter Berdasarkan Prioritas</h6>
                <p class="text-muted">Petugas yang sesuai diprioritaskan berdasarkan level prioritas tiket.</p>
                <div class="bg-light p-3 rounded">
                    <code>
                        if prioritas == Urgent → prioritas utama<br>
                        else if prioritas == Tinggi → prioritas kedua<br>
                        else → normal
                    </code>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <h6>Langkah 3: Pertimbangkan Beban Kerja</h6>
                <p class="text-muted">Pilih petugas dengan beban kerja terendah untuk distribusi merata.</p>
                <div class="bg-light p-3 rounded">
                    <code>
                        beban_kerja_min = <br>
                        &nbsp;&nbsp;min(load semua petugas cocok)
                    </code>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <h6>Langkah 4: Round-Robin Load Balancing</h6>
                <p class="text-muted">Distribusi tiket secara merata menggunakan persentase yang dikonfigurasi.</p>
                <div class="bg-light p-3 rounded">
                    <code>
                        random_selection menggunakan<br>
                        &nbsp;&nbsp;weighted probability
                    </code>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>

    <!-- Beban Kerja Tab -->
    <div class="tab-pane fade" id="workload" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-percent me-2"></i>
                    Beban Kerja Petugas Saat Ini
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <h6>Distribusi Beban Kerja (Grafik)</h6>
                        <div style="position: relative; height: 300px;">
                            <canvas id="workloadChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Statistik Beban Kerja</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Petugas</th>
                                        <th>Tiket Aktif</th>
                                        <th>% Load</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Ahmad Fauzi (A)</strong></td>
                                        <td>2</td>
                                        <td>40%</td>
                                        <td><span class="badge bg-info">Sedang</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Siti Aminah (B)</strong></td>
                                        <td>3</td>
                                        <td>60%</td>
                                        <td><span class="badge bg-warning">Tinggi</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Rizki Pratama (C)</strong></td>
                                        <td>1</td>
                                        <td>20%</td>
                                        <td><span class="badge bg-success">Ringan</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Desi Marlina (D)</strong></td>
                                        <td>2</td>
                                        <td>40%</td>
                                        <td><span class="badge bg-info">Sedang</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Budi Santoso (E)</strong></td>
                                        <td>0</td>
                                        <td>0%</td>
                                        <td><span class="badge bg-success">Tersedia</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <strong>Catatan:</strong> Beban kerja dihitung berdasarkan jumlah tiket yang sedang dalam status "Berlangsung" dan belum selesai. Sistem auto assignment akan memprioritaskan petugas dengan beban kerja terendah.
                </div>
            </div>
        </div>
    </div>

    <!-- Simulasi Tab -->
    <div class="tab-pane fade" id="simulation" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-play-circle me-2"></i>
                    Simulasi Auto Assignment
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Simulasikan proses auto assignment untuk menguji konfigurasi Anda sebelum diterapkan ke sistem produksi.</p>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pilih Jenis Pekerjaan</label>
                        <select class="form-select" id="simJenisPekerjaan">
                            <option selected disabled>-- Pilih --</option>
                            <option>PIC Presensi</option>
                            <option>Perbaikan Portal</option>
                            <option>Troubleshooting</option>
                            <option>Maintenance Server</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pilih Prioritas</label>
                        <select class="form-select" id="simPrioritas">
                            <option selected disabled>-- Pilih --</option>
                            <option>Urgent</option>
                            <option>Tinggi</option>
                            <option>Sedang</option>
                            <option>Rendah</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary" onclick="runSimulation()">
                    <i class="bi bi-play-fill me-2"></i>
                    Jalankan Simulasi
                </button>

                <div id="simulationResult" class="mt-4" style="display: none;">
                    <h6>Hasil Simulasi:</h6>
                    <div class="alert alert-success">
                        <h6 class="alert-heading">Petugas Terpilih</h6>
                        <p id="resultPetugas" class="mb-0"><strong>Ahmad Fauzi (A)</strong></p>
                        <small id="resultReason" class="text-muted d-block mt-2"></small>
                    </div>
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Penjelasan</h6>
                        <ul id="resultExplanation" class="mb-0">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Tab -->
    <div class="tab-pane fade" id="history" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    History Perubahan Konfigurasi
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Admin</th>
                                <th>Perubahan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>22 Jan 2026, 10:30</td>
                                <td>Super Admin</td>
                                <td>Update petugas PIC Presensi: Ahmad → Siti (70%)</td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="restoreConfig('config_001')">Restore</button>
                                </td>
                            </tr>
                            <tr>
                                <td>20 Jan 2026, 14:15</td>
                                <td>Admin Tiket</td>
                                <td>Tambah rule: Maintenance Server 100% ke Budi</td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="restoreConfig('config_002')">Restore</button>
                                </td>
                            </tr>
                            <tr>
                                <td>18 Jan 2026, 09:45</td>
                                <td>Super Admin</td>
                                <td>Reset ke konfigurasi default</td>
                                <td><span class="badge bg-secondary">Tidak Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="restoreConfig('config_003')">Restore</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Initialize Workload Chart
let workloadChart = null;
document.addEventListener('DOMContentLoaded', function() {
    initWorkloadChart();
});

function initWorkloadChart() {
    const ctx = document.getElementById('workloadChart').getContext('2d');
    workloadChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ahmad Fauzi', 'Siti Aminah', 'Rizki Pratama', 'Desi Marlina', 'Budi Santoso'],
            datasets: [{
                label: 'Tiket Aktif',
                data: [2, 3, 1, 2, 0],
                backgroundColor: ['#0d6efd', '#ffc107', '#28a745', '#0dcaf0', '#198754'],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5
                }
            }
        }
    });
}

// Form Submission
document.getElementById('autoAssignmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = e.target.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    setTimeout(() => {
        showToast('Konfigurasi auto assignment berhasil disimpan!', 'success');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 1000);
});

// Simulasi Assignment
function runSimulation() {
    const jenis = document.getElementById('simJenisPekerjaan').value;
    const prioritas = document.getElementById('simPrioritas').value;

    if (!jenis || !prioritas) {
        showToast('Pilih jenis pekerjaan dan prioritas terlebih dahulu', 'warning');
        return;
    }

    // Simulasi logic
    const simulationLogic = {
        'PIC Presensi': {
            'Urgent': { petugas: 'Ahmad Fauzi (A)', reason: '70% probabilitas untuk urgent' },
            'Tinggi': { petugas: 'Siti Aminah (B)', reason: '60% probabilitas untuk tinggi' },
            'Sedang': { petugas: 'Ahmad Fauzi (A)', reason: 'Load terendah' },
            'Rendah': { petugas: 'Budi Santoso (E)', reason: 'Load terendah & tersedia' }
        },
        'Perbaikan Portal': {
            'Urgent': { petugas: 'Siti Aminah (B)', reason: '60% probabilitas untuk urgent' },
            'Tinggi': { petugas: 'Rizki Pratama (C)', reason: '70% probabilitas untuk tinggi' },
            'Sedang': { petugas: 'Rizki Pratama (C)', reason: 'Load terendah' },
            'Rendah': { petugas: 'Rizki Pratama (C)', reason: 'Sesuai keahlian' }
        },
        'Troubleshooting': {
            'Urgent': { petugas: 'Rizki Pratama (C)', reason: '50% probabilitas untuk urgent' },
            'Tinggi': { petugas: 'Desi Marlina (D)', reason: 'Load seimbang' },
            'Sedang': { petugas: 'Ahmad Fauzi (A)', reason: 'Load terendah' },
            'Rendah': { petugas: 'Budi Santoso (E)', reason: 'Tersedia' }
        },
        'Maintenance Server': {
            'Urgent': { petugas: 'Budi Santoso (E)', reason: '100% untuk maintenance' },
            'Tinggi': { petugas: 'Budi Santoso (E)', reason: '100% untuk maintenance' },
            'Sedang': { petugas: 'Budi Santoso (E)', reason: 'Expert di bidang ini' },
            'Rendah': { petugas: 'Budi Santoso (E)', reason: 'Satu-satunya yang terlatih' }
        }
    };

    const result = simulationLogic[jenis][prioritas];
    
    document.getElementById('resultPetugas').textContent = result.petugas;
    document.getElementById('resultReason').innerHTML = `<strong>Alasan:</strong> ${result.reason}`;
    
    const explanations = [
        '✓ Petugas memiliki keahlian untuk: ' + jenis,
        '✓ Prioritas tiket: ' + prioritas,
        '✓ Beban kerja petugas: sedang',
        '✓ Algoritma: rule-based matching'
    ];
    
    const explanationHtml = explanations.map(exp => `<li>${exp}</li>`).join('');
    document.getElementById('resultExplanation').innerHTML = explanationHtml;
    
    document.getElementById('simulationResult').style.display = 'block';
    document.getElementById('simulationResult').scrollIntoView({ behavior: 'smooth' });
    
    showToast('Simulasi berhasil dijalankan', 'success');
}

// Export Configuration
function exportConfig() {
    const config = {
        timestamp: new Date().toISOString(),
        data: 'Konfigurasi auto assignment saat ini'
    };
    
    const dataStr = JSON.stringify(config, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `auto-assignment-config-${Date.now()}.json`;
    link.click();
    
    showToast('Konfigurasi berhasil diexport', 'success');
}

// Import Configuration
function importConfig() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'application/json';
    input.onchange = function(e) {
        const file = e.target.files[0];
        const reader = new FileReader();
        reader.onload = function(event) {
            try {
                const config = JSON.parse(event.target.result);
                showToast('Konfigurasi berhasil diimport. Silakan review dan simpan.', 'success');
            } catch (err) {
                showToast('Format file tidak valid', 'danger');
            }
        };
        reader.readAsText(file);
    };
    input.click();
}

// Restore Configuration
function restoreConfig(configId) {
    if (confirm('Apakah Anda yakin ingin mengembalikan konfigurasi ini? Konfigurasi saat ini akan ditimpa.')) {
        showToast('Konfigurasi berhasil di-restore', 'success');
        setTimeout(() => location.reload(), 1500);
    }
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
