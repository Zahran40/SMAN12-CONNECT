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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['reference_id'], 'fk_users_guru')->references(['id_guru'])->on('guru')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['reference_id'], 'fk_users_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_guru');
            $table->dropForeign('fk_users_siswa');
        });
    }
};
