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
        Schema::table('siswa', function (Blueprint $table) {
            $table->foreign(['kelas_id'], 'fk_siswa_kelas')->references(['id_kelas'])->on('kelas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['user_id'], 'fk_siswa_user')->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign('fk_siswa_kelas');
            $table->dropForeign('fk_siswa_user');
        });
    }
};
