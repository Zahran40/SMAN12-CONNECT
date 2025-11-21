<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            // Tambah kolom baru
            $table->string('jenis_aktivitas', 100)->nullable()->after('id_log')->index('idx_jenis');
            $table->text('deskripsi')->nullable()->after('jenis_aktivitas');
            $table->unsignedBigInteger('user_id')->nullable()->after('deskripsi')->index();
            $table->string('role', 20)->nullable()->after('user_id')->index();
            $table->text('user_agent')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->dropColumn(['jenis_aktivitas', 'deskripsi', 'user_id', 'role', 'user_agent']);
        });
    }
};
