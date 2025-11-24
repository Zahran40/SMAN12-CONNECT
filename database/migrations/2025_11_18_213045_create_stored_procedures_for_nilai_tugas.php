<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_average_tugas');
        DB::unprepared("
            CREATE PROCEDURE sp_calculate_average_tugas(
                IN p_siswa_id INT,
                IN p_mapel_id INT,
                OUT p_average DECIMAL(5,2)
            )
            BEGIN
                -- Hitung rata-rata dari semua tugas yang sudah dinilai
                SELECT 
                    COALESCE(AVG(dt.nilai), 0)
                INTO p_average
                FROM detail_tugas dt
                INNER JOIN tugas t ON dt.tugas_id = t.id_tugas
                INNER JOIN jadwal_pelajaran jp ON t.jadwal_id = jp.id_jadwal
                WHERE dt.siswa_id = p_siswa_id
                  AND jp.mapel_id = p_mapel_id
                  AND dt.nilai IS NOT NULL;  -- Hanya tugas yang sudah dinilai
            END
        ");
        DB::unprepared('DROP FUNCTION IF EXISTS fn_convert_grade_letter');
        DB::unprepared("
            CREATE FUNCTION fn_convert_grade_letter(p_nilai DECIMAL(5,2))
            RETURNS VARCHAR(1)
            DETERMINISTIC
            BEGIN
                DECLARE v_grade VARCHAR(1);
                IF p_nilai >= 90 THEN
                    SET v_grade = 'A';
                ELSEIF p_nilai >= 80 THEN
                    SET v_grade = 'B';
                ELSEIF p_nilai >= 70 THEN
                    SET v_grade = 'C';
                ELSEIF p_nilai >= 60 THEN
                    SET v_grade = 'D';
                ELSE
                    SET v_grade = 'E';
                END IF;
                RETURN v_grade;
            END
        ");
    }
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_average_tugas');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_convert_grade_letter');
    }
};