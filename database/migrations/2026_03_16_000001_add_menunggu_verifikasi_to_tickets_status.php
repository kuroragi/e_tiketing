<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('baru','diproses','menunggu_verifikasi','selesai','ditolak','dibatalkan') NOT NULL DEFAULT 'baru'");
        }
        // SQLite: enum is stored as text, no modification needed
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        DB::statement("UPDATE tickets SET status = 'diproses' WHERE status = 'menunggu_verifikasi'");

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('baru','diproses','selesai','ditolak','dibatalkan') NOT NULL DEFAULT 'baru'");
        }
    }
};
