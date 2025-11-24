<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_pembayaran_siswa");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_pembayaran_siswa`(IN `id_siswa_param` BIGINT, IN `status_param` VARCHAR(20))
BEGIN
  SELECT ps.id_pembayaran,
         ps.nama_tagihan,
         ps.bulan,
         ps.tahun,
         ps.jumlah_bayar,
         ps.tgl_bayar,
         ps.metode_pembayaran,
         ps.nomor_va,
         ps.status,
         ta.tahun_mulai,
         ta.tahun_selesai,
         ta.semester
  FROM pembayaran_spp ps
  JOIN tahun_ajaran ta ON ps.tahun_ajaran_id = ta.id_tahun_ajaran
  WHERE ps.siswa_id = id_siswa_param
    AND (status_param IS NULL OR ps.status = status_param)
  ORDER BY ps.tahun DESC, ps.bulan DESC;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_pembayaran_siswa");
    }
};