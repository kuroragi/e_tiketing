@extends('layouts.e-ticket')

@section('title', 'Detail Tiket #' . $ticket->number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tiket.index') }}">Daftar Tiket</a></li>
    <li class="breadcrumb-item active">Detail Tiket</li>
@endsection

@section('page-header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-0">{{ $ticket->title }}</h4>
            <small class="text-white-50">{{ $ticket->number }} &bull; {{ $ticket->department->name ?? '-' }}</small>
        </div>
        <span
            class="status-badge status-{{ strtolower($ticket->status) }}">{{ $ticket->status === 'menunggu_verifikasi' ? 'Menunggu Verifikasi' : ucfirst($ticket->status) }}</span>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Deskripsi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-text text-primary me-2"></i>Detail Pekerjaan</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ $ticket->description }}</p>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <small class="text-muted d-block">SKPD/Unit Kerja</small>
                            <strong>{{ $ticket->department->name ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Jenis Pekerjaan</small>
                            <strong>{{ $ticket->category->name ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Nama Pemohon / Kontak</small>
                            <strong>{{ $ticket->requester->name ?? '-' }}</strong>
                            @if ($ticket->contact_pic)
                                <div class="text-muted small">{{ $ticket->contact_pic }}</div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tanggal Pengajuan</small>
                            <strong>{{ $ticket->created_at->format('d M Y H:i') }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Target Penyelesaian</small>
                            <strong>{{ $ticket->target_date ? \Carbon\Carbon::parse($ticket->target_date)->format('d M Y') : '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Waktu Selesai</small>
                            <strong>{{ $ticket->closed_at ? $ticket->closed_at->format('d M Y H:i') : '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lampiran -->
            @if ($ticket->attachments->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-paperclip text-info me-2"></i>Lampiran
                            ({{ $ticket->attachments->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach ($ticket->attachments as $att)
                                <div class="col-md-6">
                                    <div class="border rounded p-2 d-flex align-items-center gap-2">
                                        <i class="bi bi-file-earmark-arrow-down fs-4 text-primary"></i>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="small fw-bold text-truncate">{{ $att->original_name }}</div>
                                            <small class="text-muted">{{ number_format($att->size / 1024, 1) }} KB</small>
                                        </div>
                                        <a href="{{ route('tiket.attachment.download', $att->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Progress Pekerjaan -->
            @php
                $authUser = auth()->user();
                $isAssignedPetugas = $authUser->isPetugas() && $ticket->assignees->contains('id', $authUser->id);
            @endphp
            @if ($authUser->isPetugas() || $authUser->isAdmin() || $authUser->isPimpinan())
                <div class="card mb-4" id="progressCard">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard2-pulse text-warning me-2"></i>Progress Pekerjaan
                            <span class="badge bg-warning text-dark ms-1"
                                id="progressCount">{{ $progressList->count() }}</span>
                        </h5>
                        @if ($isAssignedPetugas && in_array($ticket->status, ['diproses', 'baru']))
                            <button class="btn btn-sm btn-warning" type="button" onclick="toggleProgressForm()">
                                <i class="bi bi-plus-circle me-1"></i>Tambah Progress
                            </button>
                        @endif
                    </div>
                    <div class="card-body">

                        {{-- Form Tambah Progress (petugas yang ditugaskan, tiket aktif) --}}
                        @if ($isAssignedPetugas && in_array($ticket->status, ['diproses', 'baru']))
                            <div id="progressForm" class="mb-4" style="display:none">
                                <form action="{{ route('tiket.progress', $ticket->id) }}" method="POST" id="formProgress">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Rincian Progress</label>
                                        <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="progressBody" rows="3"
                                            placeholder="Contoh: Sudah berhasil menginstall driver, saat ini sedang konfigurasi jaringan..." required>{{ old('body') }}</textarea>
                                        @error('body')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimal 5 karakter. Bisa ditambahkan berkali-kali.</div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="bi bi-save me-1"></i>Simpan Progress
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="toggleProgressForm()">Batal</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Timeline Progress --}}
                        <div id="progressTimeline">
                            @forelse ($progressList as $i => $prog)
                                <div class="progress-entry d-flex gap-3 mb-3" data-id="{{ $prog->id }}">
                                    <div class="flex-shrink-0 d-flex flex-column align-items-center">
                                        <div class="user-avatar bg-warning text-dark">
                                            {{ strtoupper(substr($prog->user->name ?? '?', 0, 1)) }}</div>
                                        @if (!$loop->last)
                                            <div
                                                style="width:2px;flex:1;background:#fde68a;min-height:16px;margin-top:2px;">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 pb-2">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                            <div>
                                                <span class="fw-semibold">{{ $prog->user->name ?? '?' }}</span>
                                                <span class="badge bg-warning text-dark ms-1"
                                                    style="font-size:.65rem">Petugas</span>
                                            </div>
                                            <small class="text-muted">
                                                <i
                                                    class="bi bi-clock me-1"></i>{{ $prog->created_at->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                        <div class="bg-light rounded p-2 text-dark"
                                            style="white-space:pre-line;font-size:.9rem;">{{ $prog->body }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted" id="emptyProgress">
                                    <i class="bi bi-clipboard2 fs-3 d-block mb-2 opacity-50"></i>
                                    Belum ada catatan progress pekerjaan.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- Komentar & Diskusi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-chat-dots text-success me-2"></i>Komentar & Diskusi</h5>
                </div>
                <div class="card-body">
                    @forelse($commentList as $comment)
                        <div class="d-flex gap-3 mb-3">
                            <div class="user-avatar flex-shrink-0">{{ substr($comment->user->name ?? '?', 0, 1) }}</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $comment->user->name ?? 'Unknown' }}
                                    @if ($comment->type !== 'comment')
                                        <span class="badge bg-secondary ms-1"
                                            style="font-size:.65rem">{{ $comment->typeLabel() }}</span>
                                    @endif
                                    <small
                                        class="text-muted fw-normal ms-2">{{ $comment->created_at->format('d M Y H:i') }}</small>
                                </div>
                                <p class="mb-0 text-muted">{{ $comment->body }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada komentar.</p>
                    @endforelse

                    <!-- Form Komentar -->
                    <hr class="my-3">
                    @if (in_array($ticket->status, ['selesai', 'ditolak']))
                        <div class="alert alert-secondary d-flex align-items-start gap-3 mb-0 border-0 rounded-3"
                            style="background:#f1f5f9;">
                            <i class="bi bi-lock-fill text-secondary mt-1 fs-5 flex-shrink-0"></i>
                            <div>
                                <div class="fw-semibold text-secondary mb-1">Tiket ini telah ditutup</div>
                                <p class="mb-0 small text-muted">
                                    Komentar tidak dapat ditambahkan karena tiket sudah berstatus
                                    <strong>{{ ucfirst($ticket->status) }}</strong>.
                                    Jika masih ada yang perlu ditindaklanjuti, silakan
                                    <a href="{{ route('tiket.create') }}" class="text-primary fw-semibold">buka tiket
                                        baru</a>
                                    atau hubungi kami melalui menu
                                    <a href="{{ route('hubungi') }}" class="text-primary fw-semibold">Hubungi Kami</a>.
                                </p>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('tiket.comment', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <textarea class="form-control @error('body') is-invalid @enderror" name="body" rows="3"
                                    placeholder="Tulis komentar atau catatan pengerjaan..." required>{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-send me-1"></i>Kirim Komentar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status & Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Tiket</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span
                            class="status-badge status-{{ strtolower($ticket->status) }} fs-6">{{ $ticket->status === 'menunggu_verifikasi' ? 'Menunggu Verifikasi' : ucfirst($ticket->status) }}</span>
                    </div>
                    <div class="text-center mb-3">
                        <i
                            class="bi bi-flag-fill priority-{{ strtolower($ticket->priority->name ?? 'rendah') }} fs-4"></i>
                        <div><small class="text-muted">Prioritas
                                {{ ucfirst($ticket->priority->name ?? 'Rendah') }}</small>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-2">
                        <small class="text-muted d-block">Petugas yang Ditugaskan</small>
                        @if ($ticket->assignees->count())
                            @foreach ($ticket->assignees as $assigneeUser)
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <div class="user-avatar">{{ substr($assigneeUser->name, 0, 1) }}</div>
                                    <strong>{{ $assigneeUser->name }}</strong>
                                </div>
                            @endforeach
                        @else
                            <span class="text-muted">Belum ditugaskan</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Aksi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Aksi</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('tiket.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>

                    @php $authUser = auth()->user(); @endphp

                    {{-- ── Aksi PETUGAS: mulai & selesaikan pekerjaan ── --}}
                    @if ($authUser->isPetugas())
                        @if ($ticket->status === 'baru')
                            <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="diproses">
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="bi bi-play-circle me-2"></i>Mulai Kerjakan
                                </button>
                            </form>
                        @elseif ($ticket->status === 'diproses')
                            <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="menunggu_verifikasi">
                                <div class="mb-2">
                                    <textarea class="form-control form-control-sm" name="summary" rows="2"
                                        placeholder="Ringkasan hasil pekerjaan (opsional)"></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-hourglass-split me-2"></i>Selesai – Minta Verifikasi
                                </button>
                            </form>
                        @elseif ($ticket->status === 'menunggu_verifikasi')
                            <div class="alert alert-warning py-2 mb-0 text-center small">
                                <i class="bi bi-hourglass-split me-1"></i>
                                Menunggu verifikasi dari Admin.
                            </div>
                        @endif

                        @if (in_array($ticket->status, ['baru', 'diproses']))
                            <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Yakin ingin menolak tiket ini?')">
                                    <i class="bi bi-x-circle me-2"></i>Tolak Tiket
                                </button>
                            </form>
                        @endif
                    @endif

                    {{-- ── Aksi ADMIN: verifikasi & pengelolaan ── --}}
                    @if ($authUser->isAdmin())
                        @if ($ticket->status === 'menunggu_verifikasi')
                            <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="selesai">
                                <div class="mb-2">
                                    <label class="form-label form-label-sm fw-semibold">
                                        <i class="bi bi-flag-fill me-1"></i>Tetapkan Prioritas <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select name="priority_id" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Prioritas --</option>
                                        @foreach ($priorities as $p)
                                            <option value="{{ $p->id }}"
                                                {{ $ticket->priority_id == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }}{{ $p->description ? ' — ' . $p->description : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <textarea class="form-control form-control-sm" name="catatan" rows="2"
                                        placeholder="Catatan verifikasi (opsional)"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-patch-check me-2"></i>Verifikasi Selesai
                                </button>
                            </form>
                        @endif

                        @if (in_array($ticket->status, ['baru', 'diproses', 'menunggu_verifikasi']))
                            <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Yakin ingin menolak tiket ini?')">
                                    <i class="bi bi-x-circle me-2"></i>Tolak Tiket
                                </button>
                            </form>
                        @endif

                        {{-- Tugaskan Petugas: hanya Admin --}}
                        @if (!in_array($ticket->status, ['selesai', 'ditolak', 'dibatalkan']))
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#assignModal" data-ticket-id="{{ $ticket->id }}">
                                <i
                                    class="bi bi-person-plus me-2"></i>{{ $ticket->assignees->count() ? 'Ubah Penugasan' : 'Tugaskan Petugas' }}
                            </button>
                        @endif
                    @endif

                    <!-- Upload Lampiran -->
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#lampiranModal">
                        <i class="bi bi-paperclip me-2"></i>Tambah Lampiran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Assign Petugas -->
    @if (auth()->user()->isAdmin())
        @php $currentAssigneeIds = $ticket->assignees->pluck('id')->toArray(); @endphp
        <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignModalLabel">
                            <i class="bi bi-people me-2"></i>Tugaskan Petugas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('tiket.assign', $ticket->id) }}">
                        @csrf @method('PUT')
                        <div class="modal-body">
                            <p class="text-muted small mb-3">Pilih satu atau lebih petugas yang akan menangani tiket ini.
                            </p>
                            <div class="row g-2">
                                @foreach ($petugasList as $petugas)
                                    @php
                                        $cnt = $petugas->aktif_count ?? 0;
                                        $badgeClass = $cnt === 0 ? 'success' : ($cnt <= 3 ? 'info' : 'warning');
                                        $isChecked = in_array($petugas->id, $currentAssigneeIds);
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="card border petugas-assign-card h-100 {{ $isChecked ? 'border-primary bg-primary-subtle' : '' }}"
                                            style="cursor:pointer" onclick="togglePetugasCard(this)">
                                            <div class="card-body py-2 px-3 d-flex align-items-center gap-3">
                                                <input class="form-check-input flex-shrink-0" type="checkbox"
                                                    name="assignee_ids[]" value="{{ $petugas->id }}"
                                                    id="detail_p_{{ $petugas->id }}" {{ $isChecked ? 'checked' : '' }}
                                                    onclick="event.stopPropagation()">
                                                <label class="flex-grow-1 mb-0" for="detail_p_{{ $petugas->id }}"
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
                                <label for="detailAssignNote" class="form-label">Catatan (opsional)</label>
                                <textarea class="form-control" name="catatan" id="detailAssignNote" rows="2"
                                    placeholder="Tambahkan catatan penugasan"></textarea>
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
    @endif

    <!-- Modal Lampiran -->
    <div class="modal fade" id="lampiranModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lampiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('tiket.attachment', $ticket->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label">Upload File</label>
                        <input type="file" class="form-control" name="lampiran" accept=".pdf,.jpg,.jpeg,.png"
                            required>
                        <div class="form-text">PDF, JPG, PNG maks. 10MB</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePetugasCard(card) {
            var cb = card.querySelector('input[type=checkbox]');
            cb.checked = !cb.checked;
            card.classList.toggle('border-primary', cb.checked);
            card.classList.toggle('bg-primary-subtle', cb.checked);
        }

        // ── Progress Form Toggle ──────────────────────────────────────────────
        function toggleProgressForm() {
            var frm = document.getElementById('progressForm');
            if (!frm) return;
            frm.style.display = frm.style.display === 'none' ? 'block' : 'none';
            if (frm.style.display === 'block') {
                document.getElementById('progressBody').focus();
            }
        }

        // ── Progress AJAX Submit ──────────────────────────────────────────────
        var formProgress = document.getElementById('formProgress');
        if (formProgress) {
            formProgress.addEventListener('submit', function(e) {
                e.preventDefault();
                var btn = formProgress.querySelector('button[type=submit]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

                fetch(formProgress.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ||
                                '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: new FormData(formProgress),
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            var p = data.progress;
                            var empty = document.getElementById('emptyProgress');
                            if (empty) empty.remove();

                            // Remove connector line from last existing entry
                            var entries = document.querySelectorAll('#progressTimeline .progress-entry');
                            // (connectors are only between items, new entry becomes last)

                            // Add connector to previous last entry
                            if (entries.length > 0) {
                                var lastEntry = entries[entries.length - 1];
                                var col = lastEntry.querySelector('.flex-column');
                                if (col && !col.querySelector('.connector-line')) {
                                    var line = document.createElement('div');
                                    line.className = 'connector-line';
                                    line.style.cssText =
                                        'width:2px;flex:1;background:#fde68a;min-height:16px;margin-top:2px;';
                                    col.appendChild(line);
                                }
                            }

                            var html = `
                        <div class="progress-entry d-flex gap-3 mb-3" data-id="${p.id}">
                            <div class="flex-shrink-0 d-flex flex-column align-items-center">
                                <div class="user-avatar bg-warning text-dark">${p.user_init}</div>
                            </div>
                            <div class="flex-grow-1 pb-2">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                    <div>
                                        <span class="fw-semibold">${p.user_name}</span>
                                        <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">Petugas</span>
                                    </div>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>${p.created_at}</small>
                                </div>
                                <div class="bg-light rounded p-2 text-dark" style="white-space:pre-line;font-size:.9rem;">${p.body}</div>
                            </div>
                        </div>`;
                            document.getElementById('progressTimeline').insertAdjacentHTML('beforeend', html);

                            // Update counter badge
                            var cnt = document.getElementById('progressCount');
                            if (cnt) cnt.textContent = parseInt(cnt.textContent || '0') + 1;

                            // Reset form
                            document.getElementById('progressBody').value = '';
                            document.getElementById('progressForm').style.display = 'none';
                        } else {
                            alert(data.message || 'Gagal menyimpan progress.');
                        }
                    })
                    .catch(() => alert('Terjadi kesalahan jaringan. Silakan coba lagi.'))
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-save me-1"></i>Simpan Progress';
                    });
            });
        }
    </script>
@endpush
