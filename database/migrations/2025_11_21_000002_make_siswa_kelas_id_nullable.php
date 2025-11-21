<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat kelas_id di tabel siswa menjadi nullable
     * Karena relasi siswa-kelas sekarang di tabel siswa_kelas
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Drop foreign key constraint dulu (nama foreign key: fk_siswa_kelas)
            $table->dropForeign('fk_siswa_kelas');
            
            // Ubah kolom jadi nullable
            $table->bigInteger('kelas_id')->nullable()->change();
            
            // Re-add foreign key dengan nullable
            $table->foreign(['kelas_id'], 'fk_siswa_kelas')->references(['id_kelas'])->on('kelas')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->bigInteger('kelas_id')->nullable(false)->change();
            $table->foreign(['kelas_id'], 'fk_siswa_kelas')->references(['id_kelas'])->on('kelas')->onUpdate('cascade')->onDelete('restrict');
        });
    }
};
