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
            $table->bigInteger('tahun_ajaran_id');
            $table->bigInteger('kelas_id')->index('idx_kelas');
            $table->bigInteger('mapel_id')->index('idx_mapel');
            $table->bigInteger('guru_id')->index('idx_guru');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->unique(['tahun_ajaran_id', 'kelas_id', 'mapel_id', 'hari', 'jam_mulai'], 'uk_jadwal');
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
