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
        Schema::table('detail_tugas', function (Blueprint $table) {
            $table->foreign(['siswa_id'], 'fk_detail_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['tugas_id'], 'fk_detail_tugas')->references(['id_tugas'])->on('tugas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_tugas', function (Blueprint $table) {
            $table->dropForeign('fk_detail_siswa');
            $table->dropForeign('fk_detail_tugas');
        });
    }
};
