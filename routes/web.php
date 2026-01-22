<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KominfoController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\TicketManagementController;
use App\Services\TelegramService;

Route::redirect('/', '/dashboard');
// Authentication Routes - Rute Autentikasi
// Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
// });

// Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Redirect root to dashboard

// E-Ticket System Routes - Sistem Ticketing Kominfo Bukittinggi (Protected by Auth Middleware)
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

// Ticket Management Routes - Manajemen Assignment Tiket
Route::group(['prefix' => 'admin/ticket-management'], function () {
    // Main Index - Lihat pending tickets
    Route::get('/', [TicketManagementController::class, 'index'])->name('ticket.management.index');
    
    // Auto Assignment - Konfigurasi assignment otomatis
    Route::get('auto-assignment', [TicketManagementController::class, 'autoAssignment'])->name('ticket.management.auto');
    Route::post('save-auto-config', [TicketManagementController::class, 'saveAutoAssignment'])->name('ticket.management.save-auto');
    
    // Manual Assignment - Assign manual oleh admin
    Route::get('manual-assignment', [TicketManagementController::class, 'manualAssignment'])->name('ticket.management.manual');
    
    // History - Riwayat assignment
    Route::get('history', [TicketManagementController::class, 'history'])->name('ticket.management.history');
});

// Ticket Management API Routes
Route::group(['prefix' => 'api/ticket'], function () {
    // Auto assign tiket
    Route::post('auto-assign/{id}', [TicketManagementController::class, 'saveAutoAssignment'])->name('api.ticket.auto-assign');
    
    // Manual assign tiket
    Route::post('manual-assign/{id}', [TicketManagementController::class, 'assignManual'])->name('api.ticket.manual-assign');
});


// Note: Testing send messaege to Telegram
Route::get('/test-telegram', function () {
    $message = "Ini adalah pesan percobaan dari sistem E-Ticketing Kominfo Bukittinggi.";
    $sent = TelegramService::send($message);
    return $sent ? 'Pesan berhasil dikirim ke Telegram.' : 'Gagal mengirim pesan ke Telegram.\nerror: '.$sent;
});