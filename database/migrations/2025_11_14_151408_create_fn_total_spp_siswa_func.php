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
        DB::unprepared("DROP FUNCTION IF EXISTS fn_total_spp_siswa");
        
        DB::unprepared("CREATE DEFINER=`root`@`localhost` FUNCTION `fn_total_spp_siswa`(
            `siswa_id_param` BIGINT,
            `tahun_param` YEAR
        ) RETURNS DECIMAL(15,2)
        DETERMINISTIC
BEGIN
    DECLARE total_spp DECIMAL(15,2);
    
    -- Hitung total pembayaran SPP yang sudah lunas untuk siswa di tahun tertentu
    SELECT COALESCE(SUM(jumlah_bayar), 0)
    INTO total_spp
    FROM pembayaran_spp
    WHERE siswa_id = siswa_id_param
      AND tahun = tahun_param
      AND status = 'Lunas';
    
    RETURN total_spp;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_total_spp_siswa");
    }
};
