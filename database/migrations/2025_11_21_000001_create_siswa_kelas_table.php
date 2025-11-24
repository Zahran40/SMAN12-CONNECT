<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa_kelas', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('siswa_id')->index('idx_siswa');
            $table->bigInteger('kelas_id')->index('idx_kelas');
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun_ajaran');
            $table->date('tanggal_masuk')->nullable(); 
            $table->date('tanggal_keluar')->nullable(); 
            $table->enum('status', ['Aktif', 'Pindah', 'Lulus', 'Keluar'])->default('Aktif');
            $table->unique(['siswa_id', 'tahun_ajaran_id', 'status'], 'uk_siswa_tahun_status');
            $table->foreign(['siswa_id'], 'fk_sk_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['kelas_id'], 'fk_sk_kelas')->references(['id_kelas'])->on('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['tahun_ajaran_id'], 'fk_sk_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('cascade')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('siswa_kelas');
    }
};