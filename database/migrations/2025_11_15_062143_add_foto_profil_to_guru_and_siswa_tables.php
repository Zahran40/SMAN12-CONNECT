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
        Schema::table('guru', function (Blueprint $table) {
            $table->string('foto_profil', 255)->nullable()->after('golongan_darah');
        });
        
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('foto_profil', 255)->nullable()->after('golongan_darah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
        
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};
