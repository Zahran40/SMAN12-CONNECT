<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat function MySQL untuk menghitung nilai akhir siswa
     * berdasarkan formula: 30% Tugas + 30% UTS + 40% UAS
     */
    public function up(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS fn_calculate_nilai_akhir');
        
        DB::unprepared('
            CREATE FUNCTION fn_calculate_nilai_akhir(
                p_nilai_tugas DECIMAL(5,2),
                p_nilai_uts DECIMAL(5,2),
                p_nilai_uas DECIMAL(5,2)
            )
            RETURNS DECIMAL(5,2)
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_nilai_akhir DECIMAL(5,2);
                
                -- Formula: 30% Tugas + 30% UTS + 40% UAS
                -- Jika ada nilai NULL, dianggap 0
                SET v_nilai_akhir = (
                    (COALESCE(p_nilai_tugas, 0) * 0.30) +
                    (COALESCE(p_nilai_uts, 0) * 0.30) +
                    (COALESCE(p_nilai_uas, 0) * 0.40)
                );
                
                RETURN ROUND(v_nilai_akhir, 2);
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS fn_calculate_nilai_akhir');
    }
};
