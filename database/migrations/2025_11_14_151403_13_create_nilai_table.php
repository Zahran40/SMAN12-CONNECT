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
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun_ajaran');
            $table->enum('semester', ['Ganjil', 'Genap'])->default('Ganjil');
            $table->bigInteger('siswa_id')->index('idx_siswa');
            $table->bigInteger('mapel_id')->index('idx_mapel');
            $table->decimal('nilai_tugas', 5)->nullable();
            $table->decimal('nilai_uts', 5)->nullable();
            $table->decimal('nilai_uas', 5)->nullable();
            $table->decimal('nilai_akhir', 5)->nullable();
            $table->char('nilai_huruf', 1)->nullable()->comment('Nilai huruf A-E, auto-calculated dari nilai_akhir');
            $table->string('deskripsi', 250)->nullable();

            $table->unique(['tahun_ajaran_id', 'siswa_id', 'mapel_id', 'semester'], 'uk_nilai');
            
            // Foreign Keys
            $table->foreign(['tahun_ajaran_id'], 'fk_nilai_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['siswa_id'], 'fk_nilai_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['mapel_id'], 'fk_nilai_mapel')->references(['id_mapel'])->on('mata_pelajaran')->onUpdate('cascade')->onDelete('cascade');
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
