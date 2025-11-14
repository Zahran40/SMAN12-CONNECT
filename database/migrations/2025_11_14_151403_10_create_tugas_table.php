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
            $table->bigInteger('pertemuan_id')->nullable()->index('idx_materi_pertemuan');
            $table->string('judul_tugas', 250);
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();
            $table->dateTime('waktu_dibuka')->nullable();
            $table->dateTime('waktu_ditutup')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            
            // Foreign Keys
            $table->foreign(['jadwal_id'], 'fk_tugas_jadwal')->references(['id_jadwal'])->on('jadwal_pelajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['pertemuan_id'], 'fk_tugas_pertemuan')->references(['id_pertemuan'])->on('pertemuan')->onUpdate('cascade')->onDelete('set null');
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
