<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_hadir_persen");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` FUNCTION `fn_hadir_persen`(
            `siswa_id_param` BIGINT,
            `tahun_ajaran_id_param` BIGINT
        ) RETURNS DECIMAL(5,2)
        DETERMINISTIC
BEGIN
    DECLARE total_pertemuan INT;
    DECLARE total_hadir INT;
    DECLARE persen_hadir DECIMAL(5,2);
    -- Hitung total pertemuan dalam tahun ajaran
    SELECT COUNT(DISTINCT da.pertemuan_id)
    INTO total_pertemuan
    FROM detail_absensi da
    JOIN pertemuan p ON da.pertemuan_id = p.id_pertemuan
    JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
    WHERE da.siswa_id = siswa_id_param
      AND jp.tahun_ajaran_id = tahun_ajaran_id_param;
    -- Hitung total kehadiran (status 'Hadir')
    SELECT COUNT(*)
    INTO total_hadir
    FROM detail_absensi da
    JOIN pertemuan p ON da.pertemuan_id = p.id_pertemuan
    JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
    WHERE da.siswa_id = siswa_id_param
      AND jp.tahun_ajaran_id = tahun_ajaran_id_param
      AND da.status_kehadiran = 'Hadir';
    -- Hitung persentase
    IF total_pertemuan > 0 THEN
        SET persen_hadir = (total_hadir / total_pertemuan) * 100;
    ELSE
        SET persen_hadir = 0;
    END IF;
    RETURN persen_hadir;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_hadir_persen");
    }
};