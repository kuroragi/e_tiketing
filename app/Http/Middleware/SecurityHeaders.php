<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Tambahkan security headers ke setiap response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Cegah website di-embed dalam iframe (clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Cegah browser menebak MIME type (MIME sniffing attack)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Aktifkan XSS filter browser (legacy, tapi masih berguna)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Kontrol informasi yang dikirim di header Referer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Batasi fitur browser yang dapat diakses halaman
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), payment=()'
        );

        // Hapus informasi server agar attacker tidak tahu stack
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
