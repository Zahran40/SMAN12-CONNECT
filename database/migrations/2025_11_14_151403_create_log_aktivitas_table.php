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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->bigInteger('id_log', true);
            $table->string('nama_tabel', 100)->nullable()->index('idx_tabel');
            $table->string('aksi', 20)->nullable();
            $table->bigInteger('id_referensi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('user_aksi', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('waktu')->nullable()->useCurrent()->index('idx_waktu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
