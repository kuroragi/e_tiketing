<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketManagementController extends Controller
{
    //  Pending Tickets 

    public function index()
    {
        $pendingTickets = Ticket::with(['department', 'category', 'priority', 'requester'])
            ->where('status', 'baru')
            ->whereNull('assignee_id')
            ->orderByDesc('created_at')
            ->paginate(20);

        $petugasList = User::where('role', 'petugas')
            ->where('status', 'aktif')
            ->withCount(['assignedTickets as aktif_count' => fn($q) => $q->whereIn('status', ['baru', 'diproses'])])
            ->orderBy('name')
            ->get();

        return view('pages.admin.manajemen-tiket.index', compact('pendingTickets', 'petugasList'));
    }

    //  Auto Assignment Config 

    public function autoAssignment()
    {
        $petugasList = User::where('role', 'petugas')
            ->where('status', 'aktif')
            ->withCount(['assignedTickets as aktif_count' => fn($q) => $q->whereIn('status', ['baru', 'diproses'])])
            ->orderBy('name')
            ->get();

        // Beban kerja petugas
        $bebanKerja = $petugasList->map(fn($p) => [
            'id'          => $p->id,
            'nama'        => $p->name,
            'aktif_count' => $p->aktif_count,
            'load_pct'    => $p->aktif_count > 0 ? min(100, round($p->aktif_count / 10 * 100)) : 0,
            'status'      => match(true) {
                $p->aktif_count === 0      => 'Tersedia',
                $p->aktif_count <= 3       => 'Ringan',
                $p->aktif_count <= 6       => 'Sedang',
                default                    => 'Tinggi',
            },
        ])->toArray();

        return view('pages.admin.manajemen-tiket.auto-assignment', compact('petugasList', 'bebanKerja'));
    }

    public function saveAutoAssignment(Request $request)
    {
        // Konfigurasi auto-assignment disimpan di settings sebagai JSON
        $config = $request->input('config', []);
        \App\Models\Setting::set('auto_assignment_config', $config, 'json');

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'updated',
            'entity_type' => 'Setting',
            'entity_id'   => 0,
            'entity_name' => 'Auto Assignment Config',
            'description' => 'Konfigurasi auto-assignment diperbarui',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json(['success' => true, 'message' => 'Konfigurasi berhasil disimpan.']);
    }

    //  Manual Assignment 

    public function manualAssignment()
    {
        $pendingTickets = Ticket::with(['department', 'category', 'priority'])
            ->whereIn('status', ['baru'])
            ->whereNull('assignee_id')
            ->orderByDesc(
                \App\Models\Priority::select('weight')
                    ->whereColumn('priorities.id', 'tickets.priority_id')
            )
            ->limit(10)
            ->get();

        $petugasList = User::where('role', 'petugas')
            ->where('status', 'aktif')
            ->withCount(['assignedTickets as aktif_count' => fn($q) => $q->whereIn('status', ['baru', 'diproses'])])
            ->orderBy('aktif_count')
            ->get();

        return view('pages.admin.manajemen-tiket.manual-assignment', compact('pendingTickets', 'petugasList'));
    }

    //  History 

    public function history(Request $request)
    {
        $query = AuditLog::with('user')
            ->where('action', 'assigned')
            ->where('entity_type', 'Ticket')
            ->orderByDesc('created_at');

        if ($request->filled('dari'))   $query->where('created_at', '>=', Carbon::parse($request->dari)->startOfDay());
        if ($request->filled('sampai')) $query->where('created_at', '<=', Carbon::parse($request->sampai)->endOfDay());

        $history = $query->paginate(25)->withQueryString();

        $kpi = [
            'total_assignment' => AuditLog::where('action', 'assigned')->where('entity_type', 'Ticket')->count(),
            'bulan_ini'        => AuditLog::where('action', 'assigned')->where('entity_type', 'Ticket')->whereMonth('created_at', now()->month)->count(),
        ];

        return view('pages.admin.manajemen-tiket.history', compact('history', 'kpi'));
    }

    //  API: Auto Assign 

    public function autoAssign(Request $request, $id)
    {
        $ticket  = Ticket::with(['category', 'priority'])->findOrFail($id);
        $request->validate(['assignee_id' => 'nullable|exists:users,id']);

        if ($request->filled('assignee_id')) {
            // Gunakan petugas yang dipilih dari simulasi
            $assignee = User::findOrFail($request->assignee_id);
        } else {
            // Cari petugas dengan beban terkecil
            $assignee = User::where('role', 'petugas')
                ->where('status', 'aktif')
                ->withCount(['assignedTickets as aktif_count' => fn($q) => $q->whereIn('status', ['baru', 'diproses'])])
                ->orderBy('aktif_count')
                ->first();
        }

        if (! $assignee) {
            return response()->json(['success' => false, 'message' => 'Tidak ada petugas tersedia.'], 422);
        }

        $ticket->update([
            'assignee_id' => $assignee->id,
            'assigned_at' => now(),
            'status'      => 'diproses',
            'started_at'  => now(),
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'body'      => "Tiket di-assign secara otomatis ke **{$assignee->name}**",
            'type'      => 'assignment',
        ]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'assigned',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'new_value'   => ['assignee_id' => $assignee->id, 'assignee_name' => $assignee->name, 'method' => 'automatic'],
            'description' => "Tiket {$ticket->number} di-assign otomatis ke {$assignee->name}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => "Tiket berhasil di-assign ke {$assignee->name}",
            'assignee' => ['id' => $assignee->id, 'name' => $assignee->name],
        ]);
    }

    //  API: Manual Assign 

    public function assignManual(Request $request, $id)
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
            'status'      => 'diproses',
            'started_at'  => $ticket->started_at ?? now(),
        ]);

        $body = "Tiket di-assign secara manual ke **{$assignee->name}**";
        if ($request->filled('catatan')) {
            $body .= "\n\nCatatan: " . $request->catatan;
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
            'new_value'   => ['assignee_id' => $assignee->id, 'assignee_name' => $assignee->name, 'method' => 'manual', 'notes' => $request->catatan],
            'description' => "Tiket {$ticket->number} di-assign manual ke {$assignee->name}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => "Tiket berhasil di-assign ke {$assignee->name}",
            'assignee' => ['id' => $assignee->id, 'name' => $assignee->name],
        ]);
    }
}
