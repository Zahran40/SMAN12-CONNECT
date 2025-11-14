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
        Schema::create('nilai', function (Blueprint $table) {
            $table->bigInteger('id_nilai', true);
            $table->bigInteger('tahun_ajaran_id');
            $table->bigInteger('siswa_id')->index('idx_siswa');
            $table->bigInteger('mapel_id')->index('idx_mapel');
            $table->decimal('nilai_tugas', 5);
            $table->decimal('nilai_uts', 5);
            $table->decimal('nilai_uas', 5);
            $table->decimal('nilai_akhir', 5);
            $table->string('deskripsi', 250)->nullable();

            $table->unique(['tahun_ajaran_id', 'siswa_id', 'mapel_id'], 'uk_nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
