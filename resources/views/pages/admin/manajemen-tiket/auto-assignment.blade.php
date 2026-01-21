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
@endsection

@push('scripts')
<script>
document.getElementById('autoAssignmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    showToast('Konfigurasi auto assignment berhasil disimpan!', 'success');
});

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
