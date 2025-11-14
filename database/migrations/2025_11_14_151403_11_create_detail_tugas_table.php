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
        Schema::create('detail_tugas', function (Blueprint $table) {
            $table->bigInteger('id_detail_tugas', true);
            $table->bigInteger('tugas_id')->index('fk_detail_tugas_idx');
            $table->bigInteger('siswa_id')->index('fk_detail_siswa_idx');
            $table->timestamp('tgl_kumpul')->nullable()->useCurrent();
            $table->string('file_path')->nullable();
            $table->text('teks_jawaban')->nullable();
            $table->decimal('nilai', 5)->nullable();
            $table->text('komentar_guru')->nullable();

            $table->unique(['tugas_id', 'siswa_id'], 'uk_tugas_siswa');
            
            // Foreign Keys
            $table->foreign(['tugas_id'], 'fk_detail_tugas')->references(['id_tugas'])->on('tugas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['siswa_id'], 'fk_detail_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_tugas');
    }
};
