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
        // Hanya untuk siswa, guru sudah ada di create_guru_table
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'foto_profil')) {
                $table->string('foto_profil', 255)->nullable()->after('golongan_darah');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};
