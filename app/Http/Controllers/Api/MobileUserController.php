<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileUserController extends Controller
{
    /**
     * GET /api/mobile/users
     * Daftar pengguna (admin only).
     */
    public function index(Request $request): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            abort(403, 'Tidak diizinkan.');
        }

        $query = User::with('department')->orderBy('name');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($b) => $b->where('name', 'like', "%$q%")
                                      ->orWhere('email', 'like', "%$q%"));
        }

        $users = $query->paginate($request->integer('per_page', 50));

        return response()->json([
            'success' => true,
            'data'    => $users->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'role'       => $u->role,
                'status'     => $u->status,
                'department' => $u->department?->name,
            ]),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'total'        => $users->total(),
            ],
        ]);
    }

    /**
     * GET /api/mobile/petugas
     * Daftar petugas aktif (untuk assign - admin only).
     */
    public function petugas(Request $request): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            abort(403, 'Tidak diizinkan.');
        }

        $petugas = User::where('role', 'petugas')
            ->where('status', 'aktif')
            ->withCount(['assignedTickets as open_tickets' => fn($q) => $q->whereIn('status', ['baru', 'diproses'])])
            ->orderBy('open_tickets')
            ->get(['id', 'name', 'email']);

        return response()->json(['success' => true, 'data' => $petugas]);
    }

    /**
     * PATCH /api/mobile/users/{id}/status
     * Toggle status aktif/nonaktif pengguna (admin only).
     */
    public function toggleStatus(Request $request, int $id): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            abort(403, 'Tidak diizinkan.');
        }

        $user       = User::findOrFail($id);
        $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        return response()->json([
            'success' => true,
            'status'  => $user->status,
        ]);
    }
}
