<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('weight')->default(1)->comment('4=Urgent, 3=Tinggi, 2=Sedang, 1=Rendah');
            $table->string('color', 7)->default('#6c757d')->comment('Hex color code');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('weight');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('priorities');
    }
};
