<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_materi_by_pertemuan");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_materi_by_pertemuan`(IN `pertemuan_id_param` BIGINT)
BEGIN
  SELECT p.id_pertemuan,
         p.nomor_pertemuan,
         p.tanggal_pertemuan,
         p.topik_bahasan,
         p.status_sesi,
         m.id_materi,
         m.judul_materi,
         m.deskripsi AS deskripsi_materi,
         m.file_path AS file_materi,
         m.created_at AS tgl_upload_materi,
         t.id_tugas,
         t.judul_tugas,
         t.deskripsi AS deskripsi_tugas,
         t.file_tugas,
         t.tgl_upload AS tgl_upload_tugas,
         t.tanggal_deadline,
         t.jam_buka,
         t.jam_tutup
  FROM pertemuan p
  LEFT JOIN materi m ON p.id_pertemuan = m.pertemuan_id
  LEFT JOIN tugas t ON p.id_pertemuan = t.pertemuan_id
  WHERE p.id_pertemuan = pertemuan_id_param
  ORDER BY m.created_at DESC, t.tgl_upload DESC;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_materi_by_pertemuan");
    }
};