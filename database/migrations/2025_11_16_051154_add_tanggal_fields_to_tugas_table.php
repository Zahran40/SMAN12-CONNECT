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
        Schema::table('tugas', function (Blueprint $table) {
            // Tambah kolom tanggal_dibuka dan tanggal_ditutup
            $table->date('tanggal_dibuka')->nullable()->after('deskripsi_tugas');
            $table->date('tanggal_ditutup')->nullable()->after('tanggal_dibuka');
            
            // Update kolom yang sudah ada
            $table->time('jam_buka')->nullable()->after('tanggal_ditutup')->change();
            $table->time('jam_tutup')->nullable()->after('jam_buka')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn(['tanggal_dibuka', 'tanggal_ditutup']);
        });
    }
};
