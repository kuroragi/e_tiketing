<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Rules\SafeFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicTicketController extends Controller
{
    /**
     * GET /api/v1/categories
     * Daftar kategori layanan aktif.
     */
    public function categories(): JsonResponse
    {
        $categories = Category::aktif()
            ->orderBy('name')
            ->get(['id', 'name', 'description']);

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    /**
     * GET /api/v1/priorities
     * Daftar prioritas.
     */
    public function priorities(): JsonResponse
    {
        $priorities = Priority::ordered()
            ->get(['id', 'name', 'weight', 'color', 'description']);

        return response()->json([
            'success' => true,
            'data'    => $priorities,
        ]);
    }

    /**
     * POST /api/v1/tickets
     * Buat pengaduan baru via API.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string|max:20',
            'nik'         => 'nullable|string|size:16',
            'address'     => 'nullable|string|max:500',
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
            'priority_id' => 'required|exists:priorities,id',
            'lampiran'    => 'nullable|array|max:5',
            'lampiran.*'  => ['file', 'max:10240', new SafeFile()],
        ]);

        $trackingCode = (string) Str::uuid();

        $ticket = Ticket::create([
            'number'         => Ticket::generateNumber(),
            'title'          => $validated['title'],
            'description'    => $validated['description'],
            'requester_id'   => null,
            'department_id'  => null,
            'category_id'    => $validated['category_id'],
            'priority_id'    => $validated['priority_id'],
            'contact_pic'    => $validated['name'],
            'public_name'    => $validated['name'],
            'public_email'   => $validated['email'],
            'public_phone'   => $validated['phone'],
            'public_nik'     => $validated['nik'] ?? null,
            'public_address' => $validated['address'] ?? null,
            'source'         => 'api',
            'tracking_code'  => $trackingCode,
            'status'         => 'baru',
        ]);

        // Lampiran
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $storedName = $file->hashName();
                $path = $file->storeAs('lampiran', $storedName, 'public');

                TicketAttachment::create([
                    'ticket_id'     => $ticket->id,
                    'user_id'       => null,
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name'   => $storedName,
                    'path'          => $path,
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        AuditLog::create([
            'user_id'     => null,
            'action'      => 'created',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'description' => "Pengaduan via API: {$ticket->number} - {$ticket->title} (oleh {$ticket->public_name})",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dibuat.',
            'data'    => [
                'ticket_number' => $ticket->number,
                'tracking_code' => $ticket->tracking_code,
                'title'         => $ticket->title,
                'status'        => $ticket->status,
                'created_at'    => $ticket->created_at->toIso8601String(),
            ],
        ], 201);
    }

    /**
     * GET /api/v1/tickets/{trackingCode}
     * Lacak status tiket.
     */
    public function show(string $trackingCode): JsonResponse
    {
        $ticket = Ticket::with(['category:id,name', 'priority:id,name,color,weight'])
            ->where('tracking_code', $trackingCode)
            ->orWhere('number', $trackingCode)
            ->first();

        if (! $ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        // Timeline (hanya komentar publik)
        $timeline = $ticket->comments()
            ->with('user:id,name,role')
            ->whereIn('type', ['comment', 'status_change', 'progress'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($c) => [
                'type'       => $c->type,
                'body'       => $c->body,
                'author'     => $c->user->name ?? 'Sistem',
                'created_at' => $c->created_at->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'ticket_number'  => $ticket->number,
                'tracking_code'  => $ticket->tracking_code,
                'title'          => $ticket->title,
                'description'    => $ticket->description,
                'status'         => $ticket->status,
                'status_label'   => $ticket->statusLabel(),
                'category'       => $ticket->category->name ?? null,
                'priority'       => $ticket->priority ? [
                    'name'   => $ticket->priority->name,
                    'color'  => $ticket->priority->color,
                    'weight' => $ticket->priority->weight,
                ] : null,
                'summary'        => $ticket->summary,
                'source'         => $ticket->source,
                'created_at'     => $ticket->created_at->toIso8601String(),
                'closed_at'      => $ticket->closed_at?->toIso8601String(),
                'timeline'       => $timeline,
            ],
        ]);
    }
}
