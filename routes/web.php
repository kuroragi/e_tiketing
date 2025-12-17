<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KominfoController;

// Redirect root to dashboard
Route::redirect('/', '/dashboard');

// E-Ticket System Routes - Sistem Ticketing Kominfo Bukittinggi
Route::group(['prefix' => '/'], function () {
    
    // Dashboard - Halaman utama dengan ringkasan
    Route::get('dashboard', [KominfoController::class, 'dashboard'])->name('dashboard');
    
    // Tiket Routes
    Route::group(['prefix' => 'tiket'], function () {
        // Pengajuan tiket baru (untuk SKPD)
        Route::get('pengajuan', [KominfoController::class, 'create'])->name('tiket.create');
        Route::post('pengajuan', [KominfoController::class, 'store'])->name('tiket.store');
        
        // Daftar tiket (untuk staff Kominfo)
        Route::get('daftar', [KominfoController::class, 'index'])->name('tiket.index');
        
        // Detail tiket (AJAX)
        Route::get('{id}', [KominfoController::class, 'show'])->name('tiket.show');
        
        // Update status tiket (AJAX)
        Route::put('{id}/status', [KominfoController::class, 'updateStatus'])->name('tiket.update-status');
    });
    
    // Laporan untuk pimpinan
    Route::get('laporan', [KominfoController::class, 'laporan'])->name('laporan.index');
});
