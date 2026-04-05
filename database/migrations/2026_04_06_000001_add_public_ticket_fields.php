<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Public submitter info (nullable — only filled for public/API tickets)
            $table->string('public_name')->nullable()->after('contact_pic');
            $table->string('public_email')->nullable()->after('public_name');
            $table->string('public_phone')->nullable()->after('public_email');
            $table->string('public_nik', 20)->nullable()->after('public_phone');
            $table->text('public_address')->nullable()->after('public_nik');

            // Source tracking
            $table->enum('source', ['internal', 'public', 'api'])->default('internal')->after('public_address');

            // Tracking code for public users (UUID-based)
            $table->string('tracking_code', 36)->nullable()->unique()->after('source');

            // Make requester_id nullable for public tickets
            $table->unsignedBigInteger('requester_id')->nullable()->change();
        });

        // Make ticket_attachments.user_id nullable for public uploads
        Schema::table('ticket_attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'public_name', 'public_email', 'public_phone',
                'public_nik', 'public_address', 'source', 'tracking_code',
            ]);
        });
    }
};
