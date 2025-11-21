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
            $table->id('id_log');
            $table->string('jenis_aktivitas', 100); // 'raport', 'pembayaran_spp', 'absensi', dll
            $table->text('deskripsi'); // Deskripsi detail aktivitas
            $table->unsignedBigInteger('user_id'); // ID user yang melakukan aksi (guru/siswa)
            $table->string('role', 20); // 'guru', 'siswa', 'admin'
            $table->string('tabel_terkait', 50)->nullable(); // Nama tabel yang dimodifikasi
            $table->unsignedBigInteger('id_terkait')->nullable(); // ID record yang dimodifikasi
            $table->string('aksi', 20); // 'insert', 'update', 'delete'
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Index untuk performa query
            $table->index('jenis_aktivitas');
            $table->index('user_id');
            $table->index('role');
            $table->index('created_at');
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
