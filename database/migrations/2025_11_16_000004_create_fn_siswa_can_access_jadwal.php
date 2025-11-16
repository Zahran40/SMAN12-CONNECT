<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Function untuk cek apakah siswa berhak akses jadwal tertentu
     */
    public function up(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_siswa_can_access_jadwal");
        
        DB::unprepared("CREATE DEFINER=`root`@`localhost` FUNCTION `fn_siswa_can_access_jadwal`(
            `id_siswa_param` BIGINT,
            `id_jadwal_param` BIGINT
        ) RETURNS BOOLEAN
        DETERMINISTIC
        BEGIN
            DECLARE can_access BOOLEAN;
            DECLARE kelas_siswa BIGINT;
            
            -- Get kelas_id siswa
            SELECT kelas_id INTO kelas_siswa
            FROM siswa
            WHERE id_siswa = id_siswa_param;
            
            -- Check apakah jadwal untuk kelas tersebut
            SELECT COUNT(*) > 0 INTO can_access
            FROM jadwal_pelajaran
            WHERE id_jadwal = id_jadwal_param
              AND kelas_id = kelas_siswa;
            
            RETURN can_access;
        END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_siswa_can_access_jadwal");
    }
};
