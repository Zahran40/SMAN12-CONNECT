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
        // Drop unused/redundant views
        DB::statement('DROP VIEW IF EXISTS view_user_permissions');
        DB::statement('DROP VIEW IF EXISTS view_data_siswa');
        DB::statement('DROP VIEW IF EXISTS view_jadwal_kelas');
        DB::statement('DROP VIEW IF EXISTS view_nilai_mapel');
        DB::statement('DROP VIEW IF EXISTS view_absensi_siswa');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika ingin rollback, view bisa dibuat ulang dari migration aslinya
        // Namun karena view-view ini sudah dihapus migration filenya,
        // maka tidak perlu dibuat ulang
    }
};
