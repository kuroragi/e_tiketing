<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('baru','diproses','menunggu_verifikasi','selesai','ditolak','dibatalkan') NOT NULL DEFAULT 'baru'");
    }

    public function down(): void
    {
        // Move any pending-verification tickets back to diproses before removing the enum value
        DB::statement("UPDATE tickets SET status = 'diproses' WHERE status = 'menunggu_verifikasi'");
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('baru','diproses','selesai','ditolak','dibatalkan') NOT NULL DEFAULT 'baru'");
    }
};
