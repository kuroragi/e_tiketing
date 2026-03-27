<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Timeout idle: 60 menit (dalam detik).
     * Sesuai SESSION_LIFETIME di .env.
     */
    private const IDLE_TIMEOUT = 3600; // 60 menit

    /**
     * Handle request: cek apakah sesi sudah expired karena idle.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya berlaku untuk user yang sudah login
        if (! Auth::check()) {
            return $next($request);
        }

        // Lewati pengecekan untuk request logout (agar tidak loop)
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        $lastActivity = $request->session()->get('_last_activity');

        if ($lastActivity !== null) {
            $idleSeconds = time() - $lastActivity;

            if ($idleSeconds > self::IDLE_TIMEOUT) {
                // Sesi expired — logout paksa
                $userName = Auth::user()->name;
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect sesuai tipe request
                if ($request->expectsJson()) {
                    return response()->json([
                        'message'  => 'Sesi Anda telah habis karena tidak aktif.',
                        'redirect' => route('login'),
                    ], 401);
                }

                return redirect()->route('login')
                    ->with('warning', "Sesi Anda telah berakhir karena tidak aktif selama 60 menit. Silakan login kembali.");
            }
        }

        // Perbarui timestamp aktivitas terakhir
        $request->session()->put('_last_activity', time());

        return $next($request);
    }
}
