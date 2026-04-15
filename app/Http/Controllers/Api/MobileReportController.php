<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileReportController extends Controller
{
    /**
     * GET /api/mobile/reports
     * Laporan ringkasan (pimpinan / admin).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user->isAdmin() && ! $user->isPimpinan()) {
            abort(403, 'Tidak diizinkan.');
        }

        $period = $request->input('period', 'month'); // week, month, quarter, year
        $from   = match ($period) {
            'week'    => now()->subWeek(),
            'quarter' => now()->subMonths(3),
            'year'    => now()->subYear(),
            default   => now()->subMonth(),
        };

        $base = Ticket::where('created_at', '>=', $from);

        // Ringkasan
        $summary = [
            'total'    => (clone $base)->count(),
            'baru'     => (clone $base)->where('status', 'baru')->count(),
            'diproses' => (clone $base)->where('status', 'diproses')->count(),
            'selesai'  => (clone $base)->where('status', 'selesai')->count(),
            'ditolak'  => (clone $base)->where('status', 'ditolak')->count(),
        ];

        // Per kategori
        $perCategory = (clone $base)
            ->selectRaw('categories.name, count(*) as total, sum(tickets.status = "selesai") as selesai')
            ->join('categories', 'categories.id', '=', 'tickets.category_id')
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        // Per SKPD
        $perSkpd = (clone $base)
            ->selectRaw('departments.name, count(*) as total, sum(tickets.status = "selesai") as selesai')
            ->join('departments', 'departments.id', '=', 'tickets.department_id')
            ->groupBy('departments.name')
            ->orderByDesc('total')
            ->get();

        // Per petugas
        $perPetugas = User::where('role', 'petugas')
            ->withCount([
                'assignedTickets as total' => fn($q) => $q->where('tickets.created_at', '>=', $from),
                'assignedTickets as selesai' => fn($q) => $q->where('tickets.created_at', '>=', $from)
                    ->where('tickets.status', 'selesai'),
            ])
            ->get(['id', 'name'])
            ->map(fn($p) => [
                'name'    => $p->name,
                'total'   => $p->total ?? 0,
                'selesai' => $p->selesai ?? 0,
            ])
            ->sortByDesc('total')
            ->values();

        return response()->json([
            'success'      => true,
            'period'       => $period,
            'summary'      => $summary,
            'per_category' => $perCategory,
            'per_skpd'     => $perSkpd,
            'per_petugas'  => $perPetugas,
        ]);
    }
}
