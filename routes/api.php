<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileDashboardController;
use App\Http\Controllers\Api\MobileReportController;
use App\Http\Controllers\Api\MobileTicketController;
use App\Http\Controllers\Api\MobileUserController;
use App\Http\Controllers\Api\PublicTicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API publik untuk pengaduan masyarakat.
| Semua endpoint memerlukan header X-API-Key yang valid.
| Base URL: /api/v1/
|
| Mobile API (Sanctum token):
| Base URL: /api/mobile/
|
*/

// ── Mobile API ─────────────────────────────────────────────────────────────
Route::prefix('mobile')->middleware('throttle:api')->group(function () {

    // Auth (tidak perlu token)
    Route::post('login', [MobileAuthController::class, 'login'])->name('mobile.login');

    // Protected (perlu Sanctum token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout',                         [MobileAuthController::class, 'logout'])->name('mobile.logout');
        Route::get('user',                            [MobileAuthController::class, 'user'])->name('mobile.user');

        // Dashboard
        Route::get('dashboard',                       [MobileDashboardController::class, 'index'])->name('mobile.dashboard');

        // Tiket
        Route::get('tickets',                         [MobileTicketController::class, 'index'])->name('mobile.tickets.index');
        Route::post('tickets',                        [MobileTicketController::class, 'store'])->name('mobile.tickets.store');
        Route::get('tickets/{id}',                    [MobileTicketController::class, 'show'])->name('mobile.tickets.show');
        Route::put('tickets/{id}/status',             [MobileTicketController::class, 'updateStatus'])->name('mobile.tickets.status');
        Route::post('tickets/{id}/assign',            [MobileTicketController::class, 'assign'])->name('mobile.tickets.assign');
        Route::post('tickets/{id}/comments',          [MobileTicketController::class, 'addComment'])->name('mobile.tickets.comments');
        Route::post('tickets/{id}/attachments',       [MobileTicketController::class, 'uploadAttachment'])->name('mobile.tickets.attachments');

        // Referensi
        Route::get('categories',                      [MobileTicketController::class, 'categories'])->name('mobile.categories');
        Route::get('priorities',                      [MobileTicketController::class, 'priorities'])->name('mobile.priorities');
        Route::get('departments',                     [MobileTicketController::class, 'departments'])->name('mobile.departments');

        // Pengguna (admin)
        Route::get('users',                           [MobileUserController::class, 'index'])->name('mobile.users.index');
        Route::get('petugas',                         [MobileUserController::class, 'petugas'])->name('mobile.petugas');
        Route::patch('users/{id}/status',             [MobileUserController::class, 'toggleStatus'])->name('mobile.users.status');

        // Laporan
        Route::get('reports',                         [MobileReportController::class, 'index'])->name('mobile.reports');
    });
});

// ── Public API (API Key) ───────────────────────────────────────────────────
Route::prefix('v1')->middleware(['throttle:api', 'api.key'])->group(function () {

    // Referensi data
    Route::get('categories', [PublicTicketController::class, 'categories'])
        ->name('api.v1.categories');

    Route::get('priorities', [PublicTicketController::class, 'priorities'])
        ->name('api.v1.priorities');

    // Pengaduan
    Route::post('tickets', [PublicTicketController::class, 'store'])
        ->name('api.v1.tickets.store');

    Route::get('tickets/{trackingCode}', [PublicTicketController::class, 'show'])
        ->name('api.v1.tickets.show');
});
