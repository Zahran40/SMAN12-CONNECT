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
            $table->string('jenis_aktivitas', 100)->nullable()->index('idx_jenis'); // 'raport', 'pembayaran_spp', 'absensi', dll
            $table->text('deskripsi')->nullable(); // Deskripsi detail aktivitas
            $table->unsignedBigInteger('user_id')->nullable(); // ID user yang melakukan aksi
            $table->string('role', 20)->nullable(); // 'guru', 'siswa', 'admin'
            $table->string('nama_tabel', 100)->nullable()->index('idx_tabel'); // Nama tabel yang dimodifikasi
            $table->bigInteger('id_referensi')->nullable(); // ID record yang dimodifikasi
            $table->string('aksi', 20)->nullable(); // 'insert', 'update', 'delete'
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('waktu')->nullable()->useCurrent()->index('idx_waktu');
            
            // Index untuk performa
            $table->index('user_id');
            $table->index('role');
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
