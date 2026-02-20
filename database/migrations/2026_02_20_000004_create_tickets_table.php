<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('number', 50)->unique()->comment('Format: YYYY-MM-XXXX');
            $table->string('title', 255);
            $table->longText('description');
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('priority_id')->constrained('priorities')->restrictOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak', 'dibatalkan'])->default('baru');
            $table->string('contact_pic', 255)->nullable()->comment('Nama & kontak pemohon');
            $table->date('target_date')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->longText('summary')->nullable()->comment('Ringkasan hasil pekerjaan');
            $table->timestamps();

            // Indexes for common queries
            $table->index(['status', 'created_at']);
            $table->index(['department_id', 'status', 'created_at']);
            $table->index(['assignee_id', 'status']);
            $table->index('category_id');
            $table->index('priority_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
