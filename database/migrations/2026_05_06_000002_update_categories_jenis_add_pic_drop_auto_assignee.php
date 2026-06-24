<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah nilai 'pic' ke enum jenis (driver-aware)
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE categories MODIFY jenis ENUM('cctv','publik','skpd','pic') DEFAULT 'skpd'");
        } else {
            // SQLite tidak mendukung ENUM — string sudah cukup
            Schema::table('categories', function (Blueprint $table) {
                $table->string('jenis')->default('skpd')->change();
            });
        }

        // 2. Hapus kolom auto_assignee_id (warisan dari pendekatan sebelumnya)
        if (Schema::hasColumn('categories', 'auto_assignee_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign(['auto_assignee_id']);
                $table->dropColumn('auto_assignee_id');
            });
        }
    }

    public function down(): void
    {
        // Kembalikan auto_assignee_id
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('auto_assignee_id')->nullable()->constrained('users')->nullOnDelete();
        });

        // Kembalikan enum tanpa 'pic'
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("UPDATE categories SET jenis = 'skpd' WHERE jenis = 'pic'");
            DB::statement("ALTER TABLE categories MODIFY jenis ENUM('cctv','publik','skpd') DEFAULT 'skpd'");
        }
    }
};
