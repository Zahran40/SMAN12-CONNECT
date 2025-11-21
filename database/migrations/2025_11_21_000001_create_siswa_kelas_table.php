<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel junction untuk relasi Many-to-Many antara Siswa dan Kelas
     * Sesuai dengan prinsip MSBD: Siswa bisa pindah kelas per tahun ajaran
     */
    public function up(): void
    {
        Schema::create('siswa_kelas', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('siswa_id')->index('idx_siswa');
            $table->bigInteger('kelas_id')->index('idx_kelas');
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun_ajaran');
            $table->date('tanggal_masuk')->nullable(); // Kapan siswa masuk ke kelas ini
            $table->date('tanggal_keluar')->nullable(); // Jika pindah kelas
            $table->enum('status', ['Aktif', 'Pindah', 'Lulus', 'Keluar'])->default('Aktif');
            
            // Unique constraint: 1 siswa tidak bisa di 2 kelas aktif di tahun ajaran yang sama
            $table->unique(['siswa_id', 'tahun_ajaran_id', 'status'], 'uk_siswa_tahun_status');
            
            // Foreign Keys
            $table->foreign(['siswa_id'], 'fk_sk_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['kelas_id'], 'fk_sk_kelas')->references(['id_kelas'])->on('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['tahun_ajaran_id'], 'fk_sk_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_kelas');
    }
};
