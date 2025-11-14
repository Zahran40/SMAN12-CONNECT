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
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->foreign(['siswa_id'], 'fk_spp_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['tahun_ajaran_id'], 'fk_spp_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropForeign('fk_spp_siswa');
            $table->dropForeign('fk_spp_tahun');
        });
    }
};
