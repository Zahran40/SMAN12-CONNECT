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
        Schema::create('tugas', function (Blueprint $table) {
            $table->bigInteger('id_tugas', true);
            $table->bigInteger('jadwal_id')->index('fk_tugas_jadwal');
            $table->bigInteger('pertemuan_id')->nullable()->index('idx_tugas_pertemuan');
            $table->string('judul_tugas', 250);
            $table->text('instruksi')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('tgl_dibuat')->nullable()->useCurrent();
            $table->dateTime('waktu_dibuka')->nullable();
            $table->dateTime('waktu_ditutup')->nullable();
            $table->dateTime('tgl_deadline')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
