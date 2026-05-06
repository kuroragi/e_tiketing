<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Petugas yang otomatis ditugaskan ketika tiket dibuat dengan kategori ini.
            // NULL  = tiket masuk antrian admin seperti biasa.
            // Diisi = tiket langsung di-assign tanpa melalui admin.
            $table->foreignId('auto_assignee_id')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['auto_assignee_id']);
            $table->dropColumn('auto_assignee_id');
        });
    }
};
