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
        Schema::create('materi', function (Blueprint $table) {
            $table->bigInteger('id_materi', true);
            $table->bigInteger('jadwal_id')->index('fk_materi_jadwal');
            $table->bigInteger('pertemuan_id')->nullable()->index('idx_materi_pertemuan');
            $table->string('judul_materi', 250);
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('tgl_upload')->nullable()->useCurrent();
            
            // Foreign Keys
            $table->foreign(['jadwal_id'], 'fk_materi_jadwal')->references(['id_jadwal'])->on('jadwal_pelajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['pertemuan_id'], 'fk_materi_pertemuan')->references(['id_pertemuan'])->on('pertemuan')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
