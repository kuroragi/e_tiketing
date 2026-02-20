<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KominfoController extends Controller
{
    //  Dashboard 

    public function dashboard()
    {
        $user = Auth::user();
        $query = Ticket::query();

        // SKPD hanya lihat tiket milik departemennya
        if ($user->isSkpd()) {
            $query->where('department_id', $user->department_id);
        }

        $stats = [
            'total_tiket'     => (clone $query)->count(),
            'tiket_baru'      => (clone $query)->where('status', 'baru')->count(),
            'tiket_diproses'  => (clone $query)->where('status', 'diproses')->count(),
            'tiket_selesai'   => (clone $query)->where('status', 'selesai')->count(),
        ];

        // Rata-rata waktu penyelesaian (dalam hari)
        $selesai = (clone $query)->where('status', 'selesai')->whereNotNull('closed_at')->get();
        $stats['rata_penyelesaian'] = $selesai->count()
            ? round($selesai->avg(fn($t) => $t->created_at->diffInDays($t->closed_at)), 1)
            : 0;

        // 10 tiket terbaru
        $recentTickets = (clone $query)
            ->with(['requester', 'department', 'category', 'priority', 'assignee'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Quick actions berbasis peran
        $quickActions = [];
        if ($user->isSkpd() || $user->isAdmin()) {
            $quickActions[] = ['icon' => 'plus-circle', 'title' => 'Tiket Baru', 'description' => 'Ajukan permintaan baru', 'url' => route('tiket.create'), 'color' => 'primary'];
        }
        if (! $user->isSkpd()) {
            $quickActions[] = ['icon' => 'list-task', 'title' => 'Kelola Tiket', 'description' => 'Lihat semua tiket', 'url' => route('tiket.index'), 'color' => 'info'];
        }
        if ($user->isAdmin() || $user->isPimpinan() || $user->isPetugas()) {
            $quickActions[] = ['icon' => 'bar-chart', 'title' => 'Laporan', 'description' => 'Lihat laporan', 'url' => route('laporan.index'), 'color' => 'success'];
        }

        return view('kominfo.dashboard', compact('stats', 'recentTickets', 'quickActions'));
    }

    //  Form Pengajuan 

    public function create()
    {
        $skpdList      = Department::aktif()->orderBy('name')->get();
        $jenisKerjaan  = Category::aktif()->orderBy('name')->get();
        $prioritasList = Priority::ordered()->get();

        return view('kominfo.tiket-pengajuan', compact('skpdList', 'jenisKerjaan', 'prioritasList'));
    }

    //  Simpan Tiket Baru 

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
            'priority_id' => 'required|exists:priorities,id',
            'contact_pic' => 'required|string|max:255',
            'target_date' => 'nullable|date|after:today',
            'lampiran'    => 'nullable|array|max:5',
            'lampiran.*'  => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'title.required'       => 'Judul tiket harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'description.min'      => 'Deskripsi minimal 20 karakter.',
            'category_id.required' => 'Jenis pekerjaan harus dipilih.',
            'priority_id.required' => 'Prioritas harus dipilih.',
            'contact_pic.required' => 'Kontak/PIC harus diisi.',
        ]);

        $user = Auth::user();

        $ticket = Ticket::create([
            'number'       => Ticket::generateNumber(),
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'requester_id' => $user->id,
            'department_id'=> $user->department_id,
            'category_id'  => $validated['category_id'],
            'priority_id'  => $validated['priority_id'],
            'contact_pic'  => $validated['contact_pic'],
            'target_date'  => $validated['target_date'] ?? null,
            'status'       => 'baru',
        ]);

        // Simpan lampiran
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $storedName = $file->hashName();
                $path       = $file->storeAs('lampiran', $storedName, 'public');

                TicketAttachment::create([
                    'ticket_id'     => $ticket->id,
                    'user_id'       => $user->id,
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name'   => $storedName,
                    'path'          => $path,
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        // Audit log
        AuditLog::create([
            'user_id'      => $user->id,
            'action'       => 'created',
            'entity_type'  => 'Ticket',
            'entity_id'    => $ticket->id,
            'entity_name'  => $ticket->number,
            'description'  => "Tiket baru dibuat: {$ticket->number} - {$ticket->title}",
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);

        return redirect()->route('tiket.show', $ticket->id)
            ->with('success', "Tiket berhasil diajukan dengan nomor: {$ticket->number}");
    }

    //  Daftar Tiket 

    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Ticket::with(['requester', 'department', 'category', 'priority', 'assignee']);

        // Filter akses per peran
        if ($user->isSkpd()) {
            $query->where('department_id', $user->department_id);
        }

        // Terapkan filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }
        if ($request->filled('department_id') && ! $user->isSkpd()) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->dari)->startOfDay(),
                Carbon::parse($request->sampai)->endOfDay(),
            ]);
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'prioritas' => $query->join('priorities', 'tickets.priority_id', '=', 'priorities.id')
                                 ->orderByDesc('priorities.weight')
                                 ->select('tickets.*'),
            'terlama'   => $query->orderBy('created_at'),
            default     => $query->orderByDesc('created_at'),
        };

        $tickets      = $query->paginate(20)->withQueryString();
        $skpdList     = Department::aktif()->orderBy('name')->get();
        $petugasList  = User::where('role', 'petugas')->where('status', 'aktif')->orderBy('name')->get();
        $categories   = Category::aktif()->orderBy('name')->get();
        $priorities   = Priority::ordered()->get();

        $stats = [
            'total'     => Ticket::when($user->isSkpd(), fn($q) => $q->where('department_id', $user->department_id))->count(),
            'baru'      => Ticket::when($user->isSkpd(), fn($q) => $q->where('department_id', $user->department_id))->where('status', 'baru')->count(),
            'diproses'  => Ticket::when($user->isSkpd(), fn($q) => $q->where('department_id', $user->department_id))->where('status', 'diproses')->count(),
            'selesai'   => Ticket::when($user->isSkpd(), fn($q) => $q->where('department_id', $user->department_id))->where('status', 'selesai')->count(),
        ];

        return view('kominfo.tiket-daftar', compact(
            'tickets', 'skpdList', 'petugasList', 'categories', 'priorities', 'stats'
        ));
    }

    //  Detail Tiket 

    public function show($id)
    {
        $ticket = Ticket::with([
            'requester', 'department', 'category', 'priority', 'assignee',
            'comments.user', 'attachments.uploader',
        ])->findOrFail($id);

        $user = Auth::user();

        // SKPD hanya boleh lihat tiket departemennya sendiri
        if ($user->isSkpd() && $ticket->department_id !== $user->department_id) {
            abort(403);
        }

        $petugasList = User::where('role', 'petugas')->where('status', 'aktif')->orderBy('name')->get();

        return view('kominfo.tiket-detail', compact('ticket', 'petugasList'));
    }

    //  Update Status 

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'status'  => 'required|in:baru,diproses,selesai,ditolak,dibatalkan',
            'catatan' => 'nullable|string|max:1000',
            'summary' => 'nullable|string',
        ]);

        $oldStatus = $ticket->status;
        $newStatus = $request->status;
        $user      = Auth::user();

        $updateData = ['status' => $newStatus];

        // Set timestamps berdasarkan status
        if ($newStatus === 'diproses' && ! $ticket->started_at) {
            $updateData['started_at'] = now();
        }
        if (in_array($newStatus, ['selesai', 'ditolak', 'dibatalkan'])) {
            $updateData['closed_at'] = now();
            if ($request->filled('summary')) {
                $updateData['summary'] = $request->summary;
            }
        }

        $ticket->update($updateData);

        // Tambah komentar status change
        $body = "Status diubah dari **{$oldStatus}** ke **{$newStatus}**";
        if ($request->filled('catatan')) {
            $body .= "\n\n" . $request->catatan;
        }

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'body'      => $body,
            'type'      => 'status_change',
        ]);

        // Audit log
        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'status_changed',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'old_value'   => ['status' => $oldStatus],
            'new_value'   => ['status' => $newStatus],
            'description' => "Status tiket {$ticket->number} diubah: {$oldStatus}  {$newStatus}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        $statusLabel = ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'dibatalkan' => 'Dibatalkan'];

        return response()->json([
            'success' => true,
            'message' => 'Status tiket berhasil diubah ke ' . ($statusLabel[$newStatus] ?? $newStatus),
        ]);
    }

    //  Assign Tiket 

    public function assign(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'assignee_id' => 'required|exists:users,id',
            'catatan'     => 'nullable|string|max:500',
        ]);

        $assignee = User::findOrFail($request->assignee_id);
        $user     = Auth::user();

        $ticket->update([
            'assignee_id' => $assignee->id,
            'assigned_at' => now(),
            'status'      => $ticket->status === 'baru' ? 'diproses' : $ticket->status,
            'started_at'  => $ticket->started_at ?? now(),
        ]);

        $body = "Tiket ditugaskan ke **{$assignee->name}**";
        if ($request->filled('catatan')) {
            $body .= "\n\n" . $request->catatan;
        }

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'body'      => $body,
            'type'      => 'assignment',
        ]);

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'assigned',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'new_value'   => ['assignee_id' => $assignee->id, 'assignee_name' => $assignee->name],
            'description' => "Tiket {$ticket->number} ditugaskan ke {$assignee->name}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => "Tiket berhasil ditugaskan ke {$assignee->name}",
            'assignee' => ['id' => $assignee->id, 'name' => $assignee->name],
        ]);
    }

    //  Komentar 

    public function addComment(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user   = Auth::user();

        // SKPD hanya bisa komentar pada tiketnya sendiri
        if ($user->isSkpd() && $ticket->department_id !== $user->department_id) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|min:3|max:5000',
        ]);

        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'body'      => $request->body,
            'type'      => 'comment',
        ]);

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'updated',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'description' => "Komentar ditambahkan pada tiket {$ticket->number}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id'         => $comment->id,
                'body'       => $comment->body,
                'user_name'  => $user->name,
                'user_role'  => $user->role,
                'type'       => $comment->type,
                'created_at' => $comment->created_at->format('d M Y H:i'),
            ],
        ]);
    }

    //  Upload Lampiran 

    public function uploadAttachment(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user   = Auth::user();

        if ($user->isSkpd() && $ticket->department_id !== $user->department_id) {
            abort(403);
        }

        $request->validate([
            'lampiran'   => 'required|array|max:5',
            'lampiran.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $uploaded = [];
        foreach ($request->file('lampiran') as $file) {
            $storedName = $file->hashName();
            $path       = $file->storeAs('lampiran', $storedName, 'public');

            $attachment = TicketAttachment::create([
                'ticket_id'     => $ticket->id,
                'user_id'       => $user->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_name'   => $storedName,
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
            ]);

            $uploaded[] = [
                'id'            => $attachment->id,
                'original_name' => $attachment->original_name,
                'size'          => $attachment->humanSize(),
            ];
        }

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'updated',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'description' => count($uploaded) . " lampiran ditambahkan pada tiket {$ticket->number}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json(['success' => true, 'attachments' => $uploaded]);
    }

    //  Download Lampiran 

    public function downloadAttachment($attachmentId)
    {
        $attachment = TicketAttachment::with('ticket')->findOrFail($attachmentId);
        $user       = Auth::user();

        if ($user->isSkpd() && $attachment->ticket->department_id !== $user->department_id) {
            abort(403);
        }

        if (! Storage::disk('public')->exists($attachment->path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($attachment->path, $attachment->original_name);
    }

    //  Laporan 

    public function laporan(Request $request)
    {
        $dari    = $request->filled('dari')    ? Carbon::parse($request->dari)->startOfDay()    : now()->startOfMonth();
        $sampai  = $request->filled('sampai')  ? Carbon::parse($request->sampai)->endOfDay()    : now()->endOfMonth();
        $deptId  = $request->get('department_id');
        $catId   = $request->get('category_id');

        $base = Ticket::whereBetween('created_at', [$dari, $sampai]);
        if ($deptId) $base->where('department_id', $deptId);
        if ($catId)  $base->where('category_id', $catId);

        $total   = (clone $base)->count();
        $selesai = (clone $base)->where('status', 'selesai')->count();
        $avg     = (clone $base)->where('status', 'selesai')->whereNotNull('closed_at')->get()
            ->avg(fn($t) => $t->created_at->diffInDays($t->closed_at));

        $summary = [
            'total_tiket'        => $total,
            'tiket_selesai'      => $selesai,
            'persentase_selesai' => $total ? round($selesai / $total * 100) : 0,
            'rata_waktu'         => round($avg ?? 0, 1),
            'backlog'            => (clone $base)->whereIn('status', ['baru', 'diproses'])->count(),
        ];

        $statusDistribution = [
            'baru'       => (clone $base)->where('status', 'baru')->count(),
            'diproses'   => (clone $base)->where('status', 'diproses')->count(),
            'selesai'    => $selesai,
            'ditolak'    => (clone $base)->where('status', 'ditolak')->count(),
            'dibatalkan' => (clone $base)->where('status', 'dibatalkan')->count(),
        ];

        // Rekap per SKPD
        $skpdReport = Department::withCount([
            'tickets as total'   => fn($q) => $q->whereBetween('created_at', [$dari, $sampai]),
            'tickets as selesai' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai])->where('status', 'selesai'),
        ])->having('total', '>', 0)->orderByDesc('total')->get()
            ->map(fn($d) => [
                'nama'       => $d->name,
                'total'      => $d->total,
                'selesai'    => $d->selesai,
                'persentase' => $d->total ? round($d->selesai / $d->total * 100) : 0,
            ])->toArray();

        // Rekap per kategori
        $jenisReport = Category::withCount([
            'tickets as jumlah' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai]),
        ])->having('jumlah', '>', 0)->orderByDesc('jumlah')->get()
            ->map(fn($c) => [
                'nama'       => $c->name,
                'jumlah'     => $c->jumlah,
                'persentase' => $total ? round($c->jumlah / $total * 100) : 0,
            ])->toArray();

        $skpdList   = Department::aktif()->orderBy('name')->get();
        $categories = Category::aktif()->orderBy('name')->get();

        return view('kominfo.laporan', compact(
            'summary', 'statusDistribution', 'skpdReport', 'jenisReport',
            'skpdList', 'categories', 'dari', 'sampai'
        ));
    }

    //  Export CSV 

    public function exportCsv(Request $request)
    {
        $dari   = $request->filled('dari')   ? Carbon::parse($request->dari)->startOfDay()  : now()->startOfMonth();
        $sampai = $request->filled('sampai') ? Carbon::parse($request->sampai)->endOfDay()  : now()->endOfMonth();

        $tickets = Ticket::with(['department', 'category', 'priority', 'requester', 'assignee'])
            ->whereBetween('created_at', [$dari, $sampai])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'laporan-tiket-' . now()->format('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($tickets) {
            $handle = fopen('php://output', 'w');
            // BOM untuk Excel UTF-8
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['No. Tiket', 'Judul', 'SKPD', 'Kategori', 'Prioritas', 'Status', 'Pemohon', 'Petugas', 'Tgl Dibuat', 'Tgl Selesai', 'Durasi (hari)']);

            foreach ($tickets as $t) {
                fputcsv($handle, [
                    $t->number,
                    $t->title,
                    $t->department->name ?? '-',
                    $t->category->name   ?? '-',
                    $t->priority->name   ?? '-',
                    $t->statusLabel(),
                    $t->requester->name  ?? '-',
                    $t->assignee->name   ?? '-',
                    $t->created_at->format('d/m/Y'),
                    $t->closed_at        ? $t->closed_at->format('d/m/Y') : '-',
                    $t->resolutionDays() ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
