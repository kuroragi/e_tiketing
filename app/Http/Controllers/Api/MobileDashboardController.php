<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileDashboardController extends Controller
{
    /**
     * GET /api/mobile/dashboard
     * Statistik dashboard sesuai role.
     */
    public function index(Request $request): JsonResponse
    {
        $user  = $request->user();
        $query = Ticket::query();

        // Scope by role
        if ($user->isSkpd()) {
            $query->where('department_id', $user->department_id);
        } elseif ($user->isPetugas()) {
            $query->whereHas('assignees', fn($q) => $q->where('users.id', $user->id));
        }

        $stats = [
            'total'    => (clone $query)->count(),
            'baru'     => (clone $query)->where('status', 'baru')->count(),
            'diproses' => (clone $query)->where('status', 'diproses')->count(),
            'selesai'  => (clone $query)->where('status', 'selesai')->count(),
            'ditolak'  => (clone $query)->where('status', 'ditolak')->count(),
        ];

        // Rata penyelesaian
        $selesaiList = (clone $query)->where('status', 'selesai')
            ->whereNotNull('closed_at')->get(['created_at', 'closed_at']);
        $stats['rata_penyelesaian'] = $selesaiList->count()
            ? round($selesaiList->avg(fn($t) => $t->created_at->diffInDays($t->closed_at)), 1)
            : 0;

        // Tiket terbaru
        $recent = (clone $query)
            ->with(['category', 'priority', 'assignees', 'department'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn($t) => [
                'id'         => $t->id,
                'number'     => $t->number,
                'title'      => $t->title,
                'status'     => $t->status,
                'category'   => $t->category?->name,
                'priority'   => $t->priority?->name,
                'department' => $t->department?->name,
                'assignees'  => $t->assignees->pluck('name'),
                'created_at' => $t->created_at?->toISOString(),
            ]);

        // Extra per role
        $extra = [];

        if ($user->isAdmin()) {
            $extra['total_users']      = User::count();
            $extra['total_departments'] = Department::aktif()->count();
            $extra['unassigned']       = Ticket::whereDoesntHave('assignees')
                ->where('status', 'baru')->count();
        }

        if ($user->isPimpinan()) {
            $extra['per_category'] = Ticket::selectRaw('categories.name, count(*) as total')
                ->join('categories', 'categories.id', '=', 'tickets.category_id')
                ->groupBy('categories.name')
                ->get();
            $extra['per_department'] = Ticket::selectRaw('departments.name, count(*) as total, sum(status = "selesai") as selesai')
                ->join('departments', 'departments.id', '=', 'tickets.department_id')
                ->groupBy('departments.name')
                ->get();
        }

        return response()->json([
            'success' => true,
            'stats'   => $stats,
            'recent'  => $recent,
            'extra'   => $extra,
        ]);
    }
}
