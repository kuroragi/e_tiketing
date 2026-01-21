<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KominfoController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminPageController;
use App\Services\TelegramService;

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

// Static Pages Routes - Halaman Statis untuk User
Route::group(['prefix' => '/'], function () {
    // Panduan Penggunaan
    Route::get('panduan', [PageController::class, 'panduan'])->name('panduan');
    
    // Tentang Sistem
    Route::get('tentang', [PageController::class, 'tentang'])->name('tentang');
    
    // Hubungi Kami
    Route::get('hubungi', [PageController::class, 'hubungi'])->name('hubungi');
    
    // Kebijakan Privasi
    Route::get('kebijakan', [PageController::class, 'kebijakan'])->name('kebijakan');
    
    // Syarat dan Ketentuan
    Route::get('syarat-ketentuan', [PageController::class, 'syaratKetentuan'])->name('syarat-ketentuan');
});

// Admin Pages Routes - Halaman Administrasi
Route::group(['prefix' => 'admin'], function () {
    // Dashboard Admin
    Route::get('dashboard', [AdminPageController::class, 'dashboard'])->name('admin.dashboard');
    
    // Manajemen Pengguna
    Route::get('pengguna', [AdminPageController::class, 'pengguna'])->name('admin.pengguna');
    
    // Manajemen SKPD
    Route::get('skpd', [AdminPageController::class, 'skpd'])->name('admin.skpd');
    
    // Manajemen Jenis Pekerjaan
    Route::get('jenis-pekerjaan', [AdminPageController::class, 'jenisPekerjaan'])->name('admin.jenis-pekerjaan');
    
    // Pengaturan Sistem
    Route::get('pengaturan', [AdminPageController::class, 'pengaturan'])->name('admin.pengaturan');
    
    // Log Aktivitas
    Route::get('log-aktivitas', [AdminPageController::class, 'logAktivitas'])->name('admin.log-aktivitas');
    
    // Laporan Admin
    Route::get('laporan', [AdminPageController::class, 'laporan'])->name('admin.laporan');
});


// Note: Testing send messaege to Telegram
Route::get('/test-telegram', function () {
    $message = "Ini adalah pesan percobaan dari sistem E-Ticketing Kominfo Bukittinggi.";
    $sent = TelegramService::send($message);
    return $sent ? 'Pesan berhasil dikirim ke Telegram.' : 'Gagal mengirim pesan ke Telegram.\nerror: '.$sent;
});