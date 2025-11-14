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
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->foreign(['guru_id'], 'fk_jadwal_guru')->references(['id_guru'])->on('guru')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['kelas_id'], 'fk_jadwal_kelas')->references(['id_kelas'])->on('kelas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['mapel_id'], 'fk_jadwal_mapel')->references(['id_mapel'])->on('mata_pelajaran')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['tahun_ajaran_id'], 'fk_jadwal_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->dropForeign('fk_jadwal_guru');
            $table->dropForeign('fk_jadwal_kelas');
            $table->dropForeign('fk_jadwal_mapel');
            $table->dropForeign('fk_jadwal_tahun');
        });
    }
};
