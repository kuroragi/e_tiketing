<?php

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
*/

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
