<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_spp_tahun");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_rekap_spp_tahun`(IN `id_tahun_ajaran_param` BIGINT)
BEGIN
  SELECT s.id_siswa,
         s.nisn,
         s.nama_lengkap AS nama_siswa,
         k.nama_kelas,
         SUM(CASE WHEN p.status = 'Lunas' THEN p.jumlah_bayar ELSE 0 END) AS total_bayar,
         SUM(CASE WHEN p.status = 'Lunas' THEN 1 ELSE 0 END) AS bulan_lunas,
         SUM(CASE WHEN p.status = 'Belum Lunas' THEN 1 ELSE 0 END) AS bulan_belum_lunas,
         COUNT(p.id_pembayaran) AS total_tagihan
  FROM pembayaran_spp p
  JOIN siswa s ON p.siswa_id = s.id_siswa
  LEFT JOIN siswa_kelas sk ON s.id_siswa = sk.siswa_id AND sk.status = 'Aktif'
  LEFT JOIN kelas k ON sk.kelas_id = k.id_kelas
  WHERE p.tahun_ajaran_id = id_tahun_ajaran_param
  GROUP BY s.id_siswa, s.nisn, s.nama_lengkap, k.nama_kelas
  ORDER BY k.nama_kelas, s.nama_lengkap;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_spp_tahun");
    }
};