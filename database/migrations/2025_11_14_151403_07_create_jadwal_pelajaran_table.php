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
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
            $table->bigInteger('id_jadwal', true);
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun_ajaran');
            $table->bigInteger('kelas_id')->index('idx_kelas');
            $table->bigInteger('mapel_id')->index('idx_mapel');
            $table->bigInteger('guru_id')->index('idx_guru');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->unique(['tahun_ajaran_id', 'kelas_id', 'mapel_id', 'hari', 'jam_mulai'], 'uk_jadwal');
            
            // Foreign Keys
            $table->foreign(['tahun_ajaran_id'], 'fk_jadwal_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['kelas_id'], 'fk_jadwal_kelas')->references(['id_kelas'])->on('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['mapel_id'], 'fk_jadwal_mapel')->references(['id_mapel'])->on('mata_pelajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['guru_id'], 'fk_jadwal_guru')->references(['id_guru'])->on('guru')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajaran');
    }
};
