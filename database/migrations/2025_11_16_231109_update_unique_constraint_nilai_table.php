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
        Schema::table('nilai', function (Blueprint $table) {
            // Drop unique constraint lama
            $table->dropUnique('uk_nilai');
            
            // Tambah unique constraint baru yang include semester
            $table->unique(['tahun_ajaran_id', 'siswa_id', 'mapel_id', 'semester'], 'uk_nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Kembalikan ke constraint lama
            $table->dropUnique('uk_nilai');
            $table->unique(['tahun_ajaran_id', 'siswa_id', 'mapel_id'], 'uk_nilai');
        });
    }
};
