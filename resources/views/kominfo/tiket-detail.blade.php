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
        <span class="status-badge status-{{ strtolower($ticket->status) }}">{{ ucfirst($ticket->status) }}</span>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
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
                            @if($ticket->contact_pic)
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
            @if($ticket->attachments->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-paperclip text-info me-2"></i>Lampiran ({{ $ticket->attachments->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                    @foreach($ticket->attachments as $att)
                        <div class="col-md-6">
                            <div class="border rounded p-2 d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-arrow-down fs-4 text-primary"></i>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="small fw-bold text-truncate">{{ $att->original_name }}</div>
                                    <small class="text-muted">{{ number_format($att->size / 1024, 1) }} KB</small>
                                </div>
                                <a href="{{ route('tiket.attachment.download', $att->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Komentar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-chat-dots text-success me-2"></i>Komentar & Catatan</h5>
                </div>
                <div class="card-body">
                    @forelse($ticket->comments as $comment)
                        <div class="d-flex gap-3 mb-3">
                            <div class="user-avatar flex-shrink-0">{{ substr($comment->user->name ?? '?', 0, 1) }}</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $comment->user->name ?? 'Unknown' }}
                                    <small class="text-muted fw-normal ms-2">{{ $comment->created_at->format('d M Y H:i') }}</small>
                                </div>
                                <p class="mb-0 text-muted">{{ $comment->content }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada komentar.</p>
                    @endforelse

                    <!-- Form Komentar -->
                    <hr class="my-3">
                    <form action="{{ route('tiket.comment', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                name="content" rows="3" placeholder="Tulis komentar atau catatan pengerjaan..."
                                required>{{ old('content') }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-send me-1"></i>Kirim Komentar
                        </button>
                    </form>
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
                        <span class="status-badge status-{{ strtolower($ticket->status) }} fs-6">{{ ucfirst($ticket->status) }}</span>
                    </div>
                    <div class="text-center mb-3">
                        <i class="bi bi-flag-fill priority-{{ strtolower($ticket->priority->name ?? 'rendah') }} fs-4"></i>
                        <div><small class="text-muted">Prioritas {{ ucfirst($ticket->priority->name ?? 'Rendah') }}</small></div>
                    </div>

                    <hr>
                    <div class="mb-2">
                        <small class="text-muted d-block">Petugas yang Ditugaskan</small>
                        @if($ticket->assignee)
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <div class="user-avatar">{{ substr($ticket->assignee->name, 0, 1) }}</div>
                                <strong>{{ $ticket->assignee->name }}</strong>
                            </div>
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

                    @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                        @if($ticket->status === 'baru')
                            <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="diproses">
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="bi bi-play-circle me-2"></i>Mulai Kerjakan
                                </button>
                            </form>
                        @elseif($ticket->status === 'diproses')
                            <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="selesai">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle me-2"></i>Tandai Selesai
                                </button>
                            </form>
                        @endif

                        @if(in_array($ticket->status, ['baru', 'diproses']))
                            <form method="POST" action="{{ route('tiket.update-status', $ticket->id) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Yakin ingin menolak tiket ini?')">
                                    <i class="bi bi-x-circle me-2"></i>Tolak Tiket
                                </button>
                            </form>
                        @endif

                        <!-- Tugaskan Petugas -->
                        @if(!$ticket->assignee || $ticket->status !== 'selesai')
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignModal">
                            <i class="bi bi-person-plus me-2"></i>Tugaskan Petugas
                        </button>
                        @endif
                    @endif

                    <!-- Upload Lampiran -->
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#lampiranModal">
                        <i class="bi bi-paperclip me-2"></i>Tambah Lampiran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Assign Petugas -->
    @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
    <div class="modal fade" id="assignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tugaskan Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('tiket.assign', $ticket->id) }}">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <label class="form-label">Pilih Petugas</label>
                        <select class="form-select" name="assignee_id" required>
                            <option value="">-- Pilih Petugas --</option>
                            @foreach($petugasList as $petugas)
                                <option value="{{ $petugas->id }}" {{ $ticket->assignee_id == $petugas->id ? 'selected' : '' }}>
                                    {{ $petugas->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tugaskan</button>
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
                <form method="POST" action="{{ route('tiket.attachment', $ticket->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label">Upload File</label>
                        <input type="file" class="form-control" name="lampiran" accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="form-text">PDF, JPG, PNG  maks. 10MB</div>
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