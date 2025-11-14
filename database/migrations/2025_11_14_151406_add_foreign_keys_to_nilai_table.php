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
        Schema::table('nilai', function (Blueprint $table) {
            $table->foreign(['mapel_id'], 'fk_nilai_mapel')->references(['id_mapel'])->on('mata_pelajaran')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['siswa_id'], 'fk_nilai_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['tahun_ajaran_id'], 'fk_nilai_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropForeign('fk_nilai_mapel');
            $table->dropForeign('fk_nilai_siswa');
            $table->dropForeign('fk_nilai_tahun');
        });
    }
};
