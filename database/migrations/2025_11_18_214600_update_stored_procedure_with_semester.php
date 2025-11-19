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
        // Drop old procedure
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_average_tugas');
        
        // Create new procedure with semester filter
        DB::unprepared("
            CREATE PROCEDURE sp_calculate_average_tugas(
                IN p_siswa_id INT,
                IN p_mapel_id INT,
                IN p_semester VARCHAR(10),
                OUT p_average DECIMAL(5,2)
            )
            BEGIN
                -- Hitung rata-rata dari semua tugas yang sudah dinilai untuk semester tertentu
                SELECT 
                    COALESCE(AVG(dt.nilai), 0)
                INTO p_average
                FROM detail_tugas dt
                INNER JOIN tugas t ON dt.tugas_id = t.id_tugas
                INNER JOIN jadwal_pelajaran jp ON t.jadwal_id = jp.id_jadwal
                WHERE dt.siswa_id = p_siswa_id
                  AND jp.mapel_id = p_mapel_id
                  AND t.semester = p_semester
                  AND dt.nilai IS NOT NULL;  -- Hanya tugas yang sudah dinilai
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new procedure
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_average_tugas');
        
        // Restore old procedure (without semester filter)
        DB::unprepared("
            CREATE PROCEDURE sp_calculate_average_tugas(
                IN p_siswa_id INT,
                IN p_mapel_id INT,
                OUT p_average DECIMAL(5,2)
            )
            BEGIN
                SELECT 
                    COALESCE(AVG(dt.nilai), 0)
                INTO p_average
                FROM detail_tugas dt
                INNER JOIN tugas t ON dt.tugas_id = t.id_tugas
                INNER JOIN jadwal_pelajaran jp ON t.jadwal_id = jp.id_jadwal
                WHERE dt.siswa_id = p_siswa_id
                  AND jp.mapel_id = p_mapel_id
                  AND dt.nilai IS NOT NULL;
            END
        ");
    }
};
