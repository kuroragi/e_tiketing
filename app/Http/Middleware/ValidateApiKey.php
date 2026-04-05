<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * Validasi API key dari header X-API-Key.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah API diaktifkan
        if (! Setting::get('api_enabled', true)) {
            return response()->json([
                'success' => false,
                'message' => 'API sedang tidak aktif.',
            ], 503);
        }

        $apiKey = $request->header('X-API-Key');
        $storedKey = Setting::get('api_key');

        if (! $apiKey || ! $storedKey || ! hash_equals($storedKey, $apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API key tidak valid atau tidak disertakan.',
            ], 401);
        }

        return $next($request);
    }
}
