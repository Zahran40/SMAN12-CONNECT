<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Function untuk cek apakah guru berhak akses jadwal tertentu
     */
    public function up(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_guru_can_access_jadwal");
        
        DB::unprepared("CREATE DEFINER=`root`@`localhost` FUNCTION `fn_guru_can_access_jadwal`(
            `id_guru_param` BIGINT,
            `id_jadwal_param` BIGINT
        ) RETURNS BOOLEAN
        DETERMINISTIC
        BEGIN
            DECLARE can_access BOOLEAN;
            
            SELECT COUNT(*) > 0 INTO can_access
            FROM jadwal_pelajaran
            WHERE id_jadwal = id_jadwal_param
              AND guru_id = id_guru_param;
            
            RETURN can_access;
        END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_guru_can_access_jadwal");
    }
};
