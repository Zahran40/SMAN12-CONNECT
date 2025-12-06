<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop unused stored procedures (yang sudah diganti dengan view atau tidak digunakan)
        DB::statement('DROP PROCEDURE IF EXISTS sp_get_jadwal_guru');
        DB::statement('DROP PROCEDURE IF EXISTS sp_get_jadwal_siswa');
        DB::statement('DROP PROCEDURE IF EXISTS sp_get_materi_by_pertemuan');
        DB::statement('DROP PROCEDURE IF EXISTS sp_get_pembayaran_siswa');
        DB::statement('DROP PROCEDURE IF EXISTS sp_get_presensi_pertemuan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika ingin rollback, SP/function bisa dibuat ulang dari migration aslinya
        // Namun karena migration file ada yang sudah dihapus,
        // maka tidak perlu dibuat ulang
    }
};
