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
        Schema::create('detail_absensi', function (Blueprint $table) {
            $table->bigInteger('id_detail_absensi', true);
            $table->bigInteger('pertemuan_id')->index('fk_absensi_pertemuan');
            $table->bigInteger('siswa_id')->index('fk_absensi_siswa');
            $table->enum('status_kehadiran', ['Hadir', 'Izin', 'Sakit', 'Alfa']);
            $table->string('keterangan', 200)->nullable();
            $table->timestamp('dicatat_pada')->nullable()->useCurrent();

            $table->unique(['pertemuan_id', 'siswa_id'], 'uk_absensi_unik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_absensi');
    }
};
