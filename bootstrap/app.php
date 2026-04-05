<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Spatie permission middleware
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'active'             => \App\Http\Middleware\CheckUserActive::class,
            'api.key'            => \App\Http\Middleware\ValidateApiKey::class,
        ]);

        // Security headers pada semua response web
        $middleware->appendToGroup('web', \App\Http\Middleware\SecurityHeaders::class);

        // Paksa aktif: cek status user
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckUserActive::class);

        // Session timeout 60 menit idle (setelah auth)
        $middleware->appendToGroup('web', \App\Http\Middleware\SessionTimeout::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Redirect semua HTTP 403 (termasuk Spatie UnauthorizedException dan abort(403)) ke dashboard
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() !== 403) {
                return null; // biarkan exception lain ditangani secara default
            }

            if ($request->expectsJson()) {
                return null; // biarkan default JSON response
            }

            $message = $e->getMessage() ?: 'Anda tidak memiliki hak akses untuk halaman tersebut.';

            if (auth()->check()) {
                return redirect()->route('dashboard')
                    ->with('forbidden', $message);
            }

            return redirect()->route('login')
                ->with('forbidden', $message);
        });
    })->create();
