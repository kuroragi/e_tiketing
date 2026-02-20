<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Pastikan user yang login berstatus aktif.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->status !== 'aktif') {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi admin.']);
        }

        return $next($request);
    }
}
