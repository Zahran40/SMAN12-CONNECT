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
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->enum('kategori', ['Umum', 'Kelas X', 'MIPA', 'IPS', 'Bahasa', 'Mulok'])->after('nama_mapel')->nullable();
            $table->time('jam_mulai')->after('kategori')->nullable();
            $table->time('jam_selesai')->after('jam_mulai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'jam_mulai', 'jam_selesai']);
        });
    }
};
