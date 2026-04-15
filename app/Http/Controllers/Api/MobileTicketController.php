<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketComment;
use App\Models\User;
use App\Rules\SafeFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MobileTicketController extends Controller
{
    /**
     * GET /api/mobile/tickets
     * List tiket (scoped by role).
     */
    public function index(Request $request): JsonResponse
    {
        $user  = $request->user();
        $query = Ticket::with(['requester', 'department', 'category', 'priority', 'assignees']);

        // Scope by role
        if ($user->isSkpd()) {
            $query->where('department_id', $user->department_id);
        } elseif ($user->isPetugas()) {
            $query->whereHas('assignees', fn($q) => $q->where('users.id', $user->id));
        }
        // Admin & pimpinan: lihat semua

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($b) => $b->where('title', 'like', "%$q%")
                                      ->orWhere('number', 'like', "%$q%"));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $tickets = $query->orderByDesc('created_at')
                         ->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => $tickets->map(fn($t) => $this->formatTicketList($t)),
            'meta'    => [
                'current_page' => $tickets->currentPage(),
                'last_page'    => $tickets->lastPage(),
                'total'        => $tickets->total(),
            ],
        ]);
    }

    /**
     * GET /api/mobile/tickets/{id}
     * Detail tiket.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user   = $request->user();
        $ticket = Ticket::with([
            'requester', 'department', 'category', 'priority',
            'assignees', 'comments.user', 'attachments.uploader',
        ])->findOrFail($id);

        // Authorize
        if ($user->isSkpd() && $ticket->department_id !== $user->department_id) {
            abort(403, 'Tidak diizinkan.');
        }
        if ($user->isPetugas() && ! $ticket->assignees->contains('id', $user->id)) {
            abort(403, 'Tidak diizinkan.');
        }

        return response()->json([
            'success' => true,
            'data'    => $this->formatTicketDetail($ticket),
        ]);
    }

    /**
     * POST /api/mobile/tickets
     * Buat tiket baru (SKPD / Admin).
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->isSkpd() && ! $user->isAdmin()) {
            abort(403, 'Tidak diizinkan.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
            'priority_id' => 'required|exists:priorities,id',
            'location'    => 'nullable|string|max:255',
        ]);

        $ticket = Ticket::create([
            'number'        => Ticket::generateNumber(),
            'title'         => $validated['title'],
            'description'   => $validated['description'],
            'category_id'   => $validated['category_id'],
            'priority_id'   => $validated['priority_id'],
            'requester_id'  => $user->id,
            'department_id' => $user->department_id,
            'contact_pic'   => $user->name,
            'source'        => 'mobile',
            'status'        => 'baru',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil dibuat.',
            'data'    => $this->formatTicketDetail($ticket->fresh([
                'requester', 'department', 'category', 'priority',
                'assignees', 'comments.user', 'attachments.uploader',
            ])),
        ], 201);
    }

    // placeholder closing brace to be removed by next replacement
        ], 201);
    }

    /**
     * PUT /api/mobile/tickets/{id}/status
     * Update status tiket (admin / petugas).
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if (! $user->isAdmin() && ! $user->isPetugas()) {
            abort(403, 'Tidak diizinkan.');
        }

        $request->validate([
            'status'  => 'required|in:baru,diproses,menunggu_verifikasi,selesai,ditolak',
            'summary' => 'nullable|string|max:500',
        ]);

        $ticket = Ticket::findOrFail($id);

        if ($user->isPetugas() && ! $ticket->assignees->contains('id', $user->id)) {
            abort(403, 'Tidak diizinkan.');
        }

        $ticket->update([
            'status'     => $request->status,
            'summary'    => $request->summary ?? $ticket->summary,
            'closed_at'  => in_array($request->status, ['selesai', 'ditolak']) ? now() : $ticket->closed_at,
            'started_at' => $request->status === 'diproses' && ! $ticket->started_at ? now() : $ticket->started_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status tiket berhasil diperbarui.',
            'status'  => $ticket->status,
        ]);
    }

    /**
     * POST /api/mobile/tickets/{id}/assign
     * Assign petugas ke tiket (admin only).
     */
    public function assign(Request $request, int $id): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            abort(403, 'Tidak diizinkan.');
        }

        $request->validate([
            'petugas_id' => 'required|exists:users,id',
        ]);

        $ticket  = Ticket::findOrFail($id);
        $petugas = User::findOrFail($request->petugas_id);

        $ticket->assignees()->syncWithoutDetaching([
            $petugas->id => [
                'assigned_by_id' => $request->user()->id,
                'assigned_at'    => now(),
            ],
        ]);

        $ticket->update([
            'assignee_id' => $petugas->id,
            'assigned_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Petugas berhasil ditugaskan.']);
    }

    /**
     * POST /api/mobile/tickets/{id}/comments
     * Tambah komentar.
     */
    public function addComment(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|min:3',
            'type' => 'nullable|in:comment,note',
        ]);

        $ticket = Ticket::findOrFail($id);

        $comment = $ticket->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $request->body,
            'type'    => $request->input('type', 'comment'),
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'data'    => $this->formatComment($comment),
        ], 201);
    }

    /**
     * POST /api/mobile/tickets/{id}/attachments
     * Upload lampiran.
     */
    public function uploadAttachment(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240', new SafeFile()],
        ]);

        $ticket = Ticket::findOrFail($id);
        $file   = $request->file('file');

        $storedName = $file->hashName();
        $path       = $file->storeAs('lampiran', $storedName, 'public');

        $attachment = TicketAttachment::create([
            'ticket_id'     => $ticket->id,
            'user_id'       => $request->user()->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => $storedName,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'path'          => $path,
        ]);

        $attachment->load('uploader');

        return response()->json([
            'success' => true,
            'data'    => $this->formatAttachment($attachment),
        ], 201);
    }

    /**
     * GET /api/mobile/categories
     */
    public function categories(): JsonResponse
    {
        $categories = Category::aktif()->orderBy('name')->get(['id', 'name']);
        return response()->json(['success' => true, 'data' => $categories]);
    }

    /**
     * GET /api/mobile/priorities
     */
    public function priorities(): JsonResponse
    {
        $priorities = Priority::ordered()->get(['id', 'name', 'weight', 'color']);
        return response()->json(['success' => true, 'data' => $priorities]);
    }

    /**
     * GET /api/mobile/departments
     */
    public function departments(): JsonResponse
    {
        $departments = Department::aktif()->orderBy('name')->get(['id', 'name', 'code']);
        return response()->json(['success' => true, 'data' => $departments]);
    }

    // ── Formatters ──────────────────────────────────────────────────────────

    private function formatTicketList(Ticket $t): array
    {
        return [
            'id'             => $t->id,
            'number'         => $t->number,
            'title'          => $t->title,
            'status'         => $t->status,
            'category'       => $t->category?->name,
            'priority'       => $t->priority?->name,
            'priority_color' => $t->priority?->color,
            'department'     => $t->department?->name,
            'requester'      => $t->requester?->name ?? $t->contact_pic,
            'assignees'      => $t->assignees->pluck('name'),
            'created_at'     => $t->created_at?->toISOString(),
        ];
    }

    private function formatTicketDetail(Ticket $t): array
    {
        return [
            ...$this->formatTicketList($t),
            'description'   => $t->description,
            'summary'       => $t->summary,
            'source'        => $t->source,
            'target_date'   => $t->target_date?->toDateString(),
            'assigned_at'   => $t->assigned_at?->toISOString(),
            'started_at'    => $t->started_at?->toISOString(),
            'closed_at'     => $t->closed_at?->toISOString(),
            'comments'      => $t->comments->map(fn($c) => $this->formatComment($c)),
            'attachments'   => $t->attachments->map(fn($a) => $this->formatAttachment($a)),
        ];
    }

    private function formatComment(TicketComment $c): array
    {
        return [
            'id'         => $c->id,
            'body'       => $c->body,
            'type'       => $c->type,
            'user'       => $c->user?->name ?? 'Sistem',
            'user_role'  => $c->user?->role ?? '-',
            'created_at' => $c->created_at?->toISOString(),
        ];
    }

    private function formatAttachment(TicketAttachment $a): array
    {
        return [
            'id'            => $a->id,
            'original_name' => $a->original_name,
            'mime_type'     => $a->mime_type,
            'size'          => $a->size,
            'url'           => Storage::disk('public')->url($a->path),
            'uploader'      => $a->uploader?->name ?? '-',
            'created_at'    => $a->created_at?->toISOString(),
        ];
    }
}
