<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE ticket_comments MODIFY COLUMN type ENUM('comment','note','status_change','assignment','progress') NOT NULL DEFAULT 'comment'");
        }
        // SQLite: enum is stored as text, no modification needed
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        DB::statement("UPDATE ticket_comments SET type = 'comment' WHERE type = 'progress'");

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE ticket_comments MODIFY COLUMN type ENUM('comment','note','status_change','assignment') NOT NULL DEFAULT 'comment'");
        }
    }
};
