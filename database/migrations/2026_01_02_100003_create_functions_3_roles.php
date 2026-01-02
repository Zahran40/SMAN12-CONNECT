<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Functions untuk kalkulasi dan helper
     */
    public function up(): void
    {
        // 1. FUNCTION HITUNG DISKON - Calculate potongan berdasarkan tipe
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_hitung_diskon;
        ");
        
        DB::unprepared("
            CREATE FUNCTION fn_hitung_diskon(
                p_jumlah_bayar DECIMAL(15,2),
                p_tipe_potongan VARCHAR(20),
                p_nilai_potongan DECIMAL(15,2)
            )
            RETURNS DECIMAL(15,2)
            DETERMINISTIC
            BEGIN
                DECLARE v_potongan DECIMAL(15,2);
                
                IF p_tipe_potongan = 'Persentase' THEN
                    SET v_potongan = p_jumlah_bayar * (p_nilai_potongan / 100);
                ELSE
                    SET v_potongan = p_nilai_potongan;
                END IF;
                
                -- Pastikan potongan tidak lebih dari jumlah bayar
                IF v_potongan > p_jumlah_bayar THEN
                    SET v_potongan = p_jumlah_bayar;
                END IF;
                
                RETURN v_potongan;
            END
        ");

        // 2. FUNCTION TOTAL TUNGGAKAN SISWA - Hitung total tunggakan per siswa
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_total_tunggakan_siswa;
        ");
        
        DB::unprepared("
            CREATE FUNCTION fn_total_tunggakan_siswa(
                p_siswa_id BIGINT,
                p_tahun_ajaran_id BIGINT
            )
            RETURNS DECIMAL(15,2)
            READS SQL DATA
            BEGIN
                DECLARE v_total DECIMAL(15,2);
                
                SELECT COALESCE(SUM(jumlah_bayar), 0)
                INTO v_total
                FROM pembayaran_spp
                WHERE siswa_id = p_siswa_id
                AND tahun_ajaran_id = p_tahun_ajaran_id
                AND status = 'Belum Lunas';
                
                RETURN v_total;
            END
        ");

        // 3. FUNCTION PERSENTASE KEHADIRAN SISWA - Hitung % kehadiran
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_persentase_kehadiran_siswa;
        ");
        
        DB::unprepared("
            CREATE FUNCTION fn_persentase_kehadiran_siswa(
                p_siswa_id BIGINT,
                p_tahun_ajaran_id BIGINT
            )
            RETURNS DECIMAL(5,2)
            READS SQL DATA
            BEGIN
                DECLARE v_total_pertemuan INT;
                DECLARE v_total_hadir INT;
                DECLARE v_persentase DECIMAL(5,2);
                
                -- Hitung total pertemuan
                SELECT COUNT(DISTINCT da.id_detail_absensi)
                INTO v_total_pertemuan
                FROM detail_absensi da
                JOIN pertemuan p ON da.pertemuan_id = p.id_pertemuan
                JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
                WHERE da.siswa_id = p_siswa_id
                AND jp.tahun_ajaran_id = p_tahun_ajaran_id;
                
                -- Hitung total hadir
                SELECT COUNT(DISTINCT da.id_detail_absensi)
                INTO v_total_hadir
                FROM detail_absensi da
                JOIN pertemuan p ON da.pertemuan_id = p.id_pertemuan
                JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
                WHERE da.siswa_id = p_siswa_id
                AND jp.tahun_ajaran_id = p_tahun_ajaran_id
                AND da.status_kehadiran = 'Hadir';
                
                -- Hitung persentase
                IF v_total_pertemuan > 0 THEN
                    SET v_persentase = (v_total_hadir * 100.0) / v_total_pertemuan;
                ELSE
                    SET v_persentase = 0;
                END IF;
                
                RETURN ROUND(v_persentase, 2);
            END
        ");

        // 4. FUNCTION RATA NILAI KELAS - Average nilai per kelas
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_rata_nilai_kelas;
        ");
        
        DB::unprepared("
            CREATE FUNCTION fn_rata_nilai_kelas(
                p_kelas_id BIGINT,
                p_mata_pelajaran_id BIGINT,
                p_tahun_ajaran_id BIGINT
            )
            RETURNS DECIMAL(5,2)
            READS SQL DATA
            BEGIN
                DECLARE v_rata DECIMAL(5,2);
                
                SELECT COALESCE(AVG(n.nilai_akhir), 0)
                INTO v_rata
                FROM nilai n
                JOIN siswa s ON n.siswa_id = s.id_siswa
                WHERE s.kelas_id = p_kelas_id
                AND n.mata_pelajaran_id = p_mata_pelajaran_id
                AND n.tahun_ajaran_id = p_tahun_ajaran_id;
                
                RETURN ROUND(v_rata, 2);
            END
        ");

        // 5. FUNCTION TOTAL POIN PERILAKU - Sum poin prestasi/pelanggaran
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_total_poin_perilaku;
        ");
        
        DB::unprepared("
            CREATE FUNCTION fn_total_poin_perilaku(
                p_siswa_id BIGINT,
                p_tahun_ajaran_id BIGINT,
                p_jenis VARCHAR(20)
            )
            RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_total INT;
                
                SELECT COALESCE(SUM(poin), 0)
                INTO v_total
                FROM laporan_perilaku
                WHERE siswa_id = p_siswa_id
                AND tahun_ajaran_id = p_tahun_ajaran_id
                AND (p_jenis IS NULL OR jenis = p_jenis);
                
                RETURN v_total;
            END
        ");

        // 6. FUNCTION KATEGORI NILAI - Konversi nilai ke grade
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_kategori_nilai;
        ");
        
        DB::unprepared("
            CREATE FUNCTION fn_kategori_nilai(
                p_nilai DECIMAL(5,2)
            )
            RETURNS VARCHAR(20)
            DETERMINISTIC
            BEGIN
                DECLARE v_kategori VARCHAR(20);
                
                IF p_nilai >= 90 THEN
                    SET v_kategori = 'Sangat Baik (A)';
                ELSEIF p_nilai >= 80 THEN
                    SET v_kategori = 'Baik (B)';
                ELSEIF p_nilai >= 70 THEN
                    SET v_kategori = 'Cukup (C)';
                ELSEIF p_nilai >= 60 THEN
                    SET v_kategori = 'Kurang (D)';
                ELSE
                    SET v_kategori = 'Sangat Kurang (E)';
                END IF;
                
                RETURN v_kategori;
            END
        ");

        // 7. FUNCTION STATUS PEMBAYARAN - Check status pembayaran siswa
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_status_pembayaran_siswa;
        ");
        
        DB::unprepared("
            CREATE FUNCTION fn_status_pembayaran_siswa(
                p_siswa_id BIGINT,
                p_tahun_ajaran_id BIGINT
            )
            RETURNS VARCHAR(50)
            READS SQL DATA
            BEGIN
                DECLARE v_total_tagihan INT;
                DECLARE v_total_lunas INT;
                DECLARE v_status VARCHAR(50);
                
                SELECT 
                    COUNT(*),
                    COUNT(CASE WHEN status = 'Lunas' THEN 1 END)
                INTO v_total_tagihan, v_total_lunas
                FROM pembayaran_spp
                WHERE siswa_id = p_siswa_id
                AND tahun_ajaran_id = p_tahun_ajaran_id;
                
                IF v_total_tagihan = 0 THEN
                    SET v_status = 'Belum Ada Tagihan';
                ELSEIF v_total_lunas = v_total_tagihan THEN
                    SET v_status = 'Lunas Semua';
                ELSEIF v_total_lunas = 0 THEN
                    SET v_status = 'Belum Bayar Sama Sekali';
                ELSE
                    SET v_status = CONCAT('Lunas ', v_total_lunas, ' dari ', v_total_tagihan);
                END IF;
                
                RETURN v_status;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_status_pembayaran_siswa");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_kategori_nilai");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_total_poin_perilaku");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_rata_nilai_kelas");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_persentase_kehadiran_siswa");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_total_tunggakan_siswa");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_hitung_diskon");
    }
};
