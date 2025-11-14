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
        DB::unprepared("DROP FUNCTION IF EXISTS fn_rata_nilai");
        
        DB::unprepared("CREATE DEFINER=`root`@`localhost` FUNCTION `fn_rata_nilai`(
            `siswa_id_param` BIGINT,
            `tahun_ajaran_id_param` BIGINT
        ) RETURNS DECIMAL(5,2)
        DETERMINISTIC
BEGIN
    DECLARE rata_rata DECIMAL(5,2);
    
    -- Hitung rata-rata nilai akhir dari semua mata pelajaran
    SELECT AVG(nilai_akhir)
    INTO rata_rata
    FROM nilai
    WHERE siswa_id = siswa_id_param
      AND tahun_ajaran_id = tahun_ajaran_id_param
      AND nilai_akhir IS NOT NULL;
    
    -- Return 0 jika tidak ada nilai
    IF rata_rata IS NULL THEN
        SET rata_rata = 0;
    END IF;
    
    RETURN rata_rata;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_rata_nilai");
    }
};
