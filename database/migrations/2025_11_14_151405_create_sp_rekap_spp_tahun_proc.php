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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_rekap_spp_tahun`(IN `id_tahun_ajaran_param` BIGINT)
BEGIN
  SELECT s.nama_lengkap,
         SUM(p.jumlah_bayar) AS total_bayar,
         SUM(CASE WHEN p.status = 'Belum Lunas' THEN 1 ELSE 0 END) AS bulan_belum_lunas
  FROM pembayaran_spp p
  JOIN siswa s ON p.siswa_id = s.id_siswa
  WHERE p.tahun_ajaran_id = id_tahun_ajaran_param
  GROUP BY s.id_siswa;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_spp_tahun");
    }
};
