<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * ALASAN DROP KOLOM 'hari':
     * - Kolom 'hari' redundan dengan 'tgl_publikasi'
     * - User bisa input hari yang berbeda dari tanggal (inconsistent data)
     * - Melanggar prinsip normalisasi database (data derivable)
     * - Bahaya: Senin 5 Desember 2025 (padahal 5 Des = Jumat)
     * 
     * SOLUSI:
     * - Drop kolom 'hari' dari tabel
     * - Generate hari auto dari 'tgl_publikasi' di query/view
     * - Pakai Carbon::parse($tgl)->translatedFormat('l') di PHP
     * - Atau DAYNAME(tgl_publikasi) di SQL
     */
    public function up(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn('hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->string('hari', 20)->nullable()->after('tgl_publikasi');
        });
    }
};
