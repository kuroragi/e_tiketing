<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KominfoController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\TicketManagementController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Redirect root
Route::redirect('/', '/dashboard');

// Protected Routes (all roles)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [KominfoController::class, 'dashboard'])->name('dashboard');

    // Tiket Routes
    Route::prefix('tiket')->group(function () {
        Route::get('daftar', [KominfoController::class, 'index'])
            ->name('tiket.index');

        Route::get('pengajuan', [KominfoController::class, 'create'])
            ->name('tiket.create');
        Route::post('pengajuan', [KominfoController::class, 'store'])
            ->name('tiket.store');

        Route::get('saya', [KominfoController::class, 'myTickets'])->name('tiket.saya');

        Route::get('{id}', [KominfoController::class, 'show'])->name('tiket.show');

        Route::put('{id}/status', [KominfoController::class, 'updateStatus'])
            ->name('tiket.update-status');

        Route::put('{id}/batalkan', [KominfoController::class, 'cancelTicket'])
            ->name('tiket.batalkan');

        Route::put('{id}/assign', [KominfoController::class, 'assign'])
            ->name('tiket.assign');

        Route::post('{id}/komentar', [KominfoController::class, 'addComment'])->name('tiket.comment');
        Route::post('{id}/lampiran', [KominfoController::class, 'uploadAttachment'])->name('tiket.attachment');
        Route::get('lampiran/{attachmentId}/download', [KominfoController::class, 'downloadAttachment'])
            ->name('tiket.attachment.download');
    });

    // Laporan
    Route::get('/laporan', [KominfoController::class, 'laporan'])
        ->name('laporan.index');

    Route::get('/laporan/export/csv', [KominfoController::class, 'exportCsv'])
        ->name('laporan.export.csv');

    // Static Pages
    Route::get('/panduan', [PageController::class, 'panduan'])->name('panduan');
    Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
    Route::get('/hubungi', [PageController::class, 'hubungi'])->name('hubungi');
    Route::get('/kebijakan', [PageController::class, 'kebijakan'])->name('kebijakan');
    Route::get('/syarat-ketentuan', [PageController::class, 'syaratKetentuan'])->name('syarat-ketentuan');

    // Admin Routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('dashboard', [AdminPageController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('pengguna', [AdminPageController::class, 'pengguna'])->name('admin.pengguna');
        Route::post('pengguna', [AdminPageController::class, 'storeUser'])->name('admin.pengguna.store');
        Route::put('pengguna/{id}', [AdminPageController::class, 'updateUser'])->name('admin.pengguna.update');
        Route::delete('pengguna/{id}', [AdminPageController::class, 'destroyUser'])->name('admin.pengguna.destroy');

        Route::get('skpd', [AdminPageController::class, 'skpd'])->name('admin.skpd');
        Route::post('skpd', [AdminPageController::class, 'storeDepartment'])->name('admin.skpd.store');
        Route::put('skpd/{id}', [AdminPageController::class, 'updateDepartment'])->name('admin.skpd.update');
        Route::delete('skpd/{id}', [AdminPageController::class, 'destroyDepartment'])->name('admin.skpd.destroy');

        Route::get('jenis-pekerjaan', [AdminPageController::class, 'jenisPekerjaan'])->name('admin.jenis-pekerjaan');
        Route::post('jenis-pekerjaan', [AdminPageController::class, 'storeCategory'])->name('admin.jenis-pekerjaan.store');
        Route::put('jenis-pekerjaan/{id}', [AdminPageController::class, 'updateCategory'])->name('admin.jenis-pekerjaan.update');
        Route::delete('jenis-pekerjaan/{id}', [AdminPageController::class, 'destroyCategory'])->name('admin.jenis-pekerjaan.destroy');

        Route::get('pengaturan', [AdminPageController::class, 'pengaturan'])->name('admin.pengaturan');
        Route::post('pengaturan', [AdminPageController::class, 'savePengaturan'])->name('admin.pengaturan.save');

        Route::get('log-aktivitas', [AdminPageController::class, 'logAktivitas'])->name('admin.log-aktivitas');
        Route::get('laporan', [AdminPageController::class, 'laporan'])->name('admin.laporan');

        // Role Management
        Route::prefix('roles')->name('admin.roles.')->group(function () {
            Route::get('/',         [RoleController::class, 'index'])->name('index');
            Route::post('/',        [RoleController::class, 'store'])->name('store');
            Route::put('{id}',      [RoleController::class, 'update'])->name('update');
            Route::delete('{id}',   [RoleController::class, 'destroy'])->name('destroy');
        });

        // Permission Management
        Route::prefix('permissions')->name('admin.permissions.')->group(function () {
            Route::get('/',         [PermissionController::class, 'index'])->name('index');
            Route::post('/',        [PermissionController::class, 'store'])->name('store');
            Route::put('{id}',      [PermissionController::class, 'update'])->name('update');
            Route::delete('{id}',   [PermissionController::class, 'destroy'])->name('destroy');
        });
    });

    // Ticket Management (Admin/Petugas) — authorization checked in controller
    Route::prefix('admin/ticket-management')->group(function () {
        Route::get('/', [TicketManagementController::class, 'index'])->name('ticket.management.index');
        Route::get('auto-assignment', [TicketManagementController::class, 'autoAssignment'])->name('ticket.management.auto');
        Route::post('save-auto-config', [TicketManagementController::class, 'saveAutoAssignment'])->name('ticket.management.save-auto');
        Route::get('manual-assignment', [TicketManagementController::class, 'manualAssignment'])->name('ticket.management.manual');
        Route::get('history', [TicketManagementController::class, 'history'])->name('ticket.management.history');
    });

    // API AJAX routes — authorization checked in controller
    Route::prefix('api/ticket')->group(function () {
        Route::post('auto-assign/{id}', [TicketManagementController::class, 'autoAssign'])->name('api.ticket.auto-assign');
        Route::post('manual-assign/{id}', [TicketManagementController::class, 'assignManual'])->name('api.ticket.manual-assign');
    });
});
