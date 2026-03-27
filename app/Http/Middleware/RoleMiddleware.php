<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware role check yang kompatibel dengan sistem dual-role:
 * - Kolom `role` di tabel users (legacy)
 * - Spatie model_has_roles (current)
 *
 * User dianggap memiliki role jika salah satunya terpenuhi.
 *
 * Catatan: Middleware ini tidak digunakan secara aktif di routes
 * karena alias 'role' sudah mengarah ke Spatie RoleMiddleware.
 * File ini dipertahankan sebagai fallback/standalone check.
 */
class RoleMiddleware
{
    /**
     * Usage: middleware('check_role:admin,petugas')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Cek kolom role legacy ATAU Spatie roles
        $hasRole = in_array($user->role, $roles) || $user->hasAnyRole($roles);

        if (! $hasRole) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}
