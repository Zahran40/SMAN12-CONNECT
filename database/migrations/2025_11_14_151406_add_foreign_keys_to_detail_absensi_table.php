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
        Schema::table('detail_absensi', function (Blueprint $table) {
            $table->foreign(['pertemuan_id'], 'fk_absensi_pertemuan')->references(['id_pertemuan'])->on('pertemuan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['siswa_id'], 'fk_absensi_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_absensi', function (Blueprint $table) {
            $table->dropForeign('fk_absensi_pertemuan');
            $table->dropForeign('fk_absensi_siswa');
        });
    }
};
