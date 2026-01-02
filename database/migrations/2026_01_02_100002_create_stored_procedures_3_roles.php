<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stored Procedures untuk 3 Role Baru
     */
    public function up(): void
    {
        // 1. SP GENERATE TAGIHAN SPP MASSAL - Untuk bendahara buat tagihan batch
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_generate_tagihan_spp_massal;
        ");
        
        DB::unprepared("
            CREATE PROCEDURE sp_generate_tagihan_spp_massal(
                IN p_tahun_ajaran_id BIGINT,
                IN p_bulan INT,
                IN p_tahun INT,
                IN p_jumlah_bayar DECIMAL(15,2),
                IN p_tingkat VARCHAR(10)
            )
            BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE v_siswa_id BIGINT;
                DECLARE cur CURSOR FOR 
                    SELECT s.id_siswa 
                    FROM siswa s
                    JOIN kelas k ON s.kelas_id = k.id_kelas
                    WHERE k.tahun_ajaran_id = p_tahun_ajaran_id
                    AND (p_tingkat IS NULL OR k.tingkat = p_tingkat)
                    AND NOT EXISTS (
                        SELECT 1 FROM pembayaran_spp ps 
                        WHERE ps.siswa_id = s.id_siswa 
                        AND ps.tahun_ajaran_id = p_tahun_ajaran_id
                        AND ps.bulan = p_bulan 
                        AND ps.tahun = p_tahun
                    );
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                OPEN cur;
                
                read_loop: LOOP
                    FETCH cur INTO v_siswa_id;
                    IF done THEN
                        LEAVE read_loop;
                    END IF;
                    
                    INSERT INTO pembayaran_spp (
                        siswa_id, 
                        tahun_ajaran_id, 
                        bulan, 
                        tahun, 
                        jumlah_bayar, 
                        status,
                        created_at
                    ) VALUES (
                        v_siswa_id,
                        p_tahun_ajaran_id,
                        p_bulan,
                        p_tahun,
                        p_jumlah_bayar,
                        'Belum Lunas',
                        NOW()
                    );
                END LOOP;
                
                CLOSE cur;
                
                SELECT CONCAT('Berhasil generate tagihan untuk ', ROW_COUNT(), ' siswa') AS result;
            END
        ");

        // 2. SP REKAP KEUANGAN PERIODE - Laporan keuangan per periode
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_rekap_keuangan_periode;
        ");
        
        DB::unprepared("
            CREATE PROCEDURE sp_rekap_keuangan_periode(
                IN p_tanggal_mulai DATE,
                IN p_tanggal_selesai DATE,
                IN p_tahun_ajaran_id BIGINT
            )
            BEGIN
                SELECT 
                    DATE_FORMAT(ps.tgl_bayar, '%Y-%m') AS periode,
                    COUNT(*) AS total_transaksi,
                    SUM(ps.jumlah_bayar) AS total_pemasukan,
                    ps.metode_pembayaran,
                    COUNT(CASE WHEN ps.metode_pembayaran = 'Tunai' THEN 1 END) AS tunai_count,
                    SUM(CASE WHEN ps.metode_pembayaran = 'Tunai' THEN ps.jumlah_bayar ELSE 0 END) AS tunai_total,
                    COUNT(CASE WHEN ps.metode_pembayaran = 'Transfer' THEN 1 END) AS transfer_count,
                    SUM(CASE WHEN ps.metode_pembayaran = 'Transfer' THEN ps.jumlah_bayar ELSE 0 END) AS transfer_total,
                    COUNT(CASE WHEN ps.metode_pembayaran = 'E-Wallet' THEN 1 END) AS ewallet_count,
                    SUM(CASE WHEN ps.metode_pembayaran = 'E-Wallet' THEN ps.jumlah_bayar ELSE 0 END) AS ewallet_total
                FROM pembayaran_spp ps
                WHERE ps.status = 'Lunas'
                AND ps.tgl_bayar BETWEEN p_tanggal_mulai AND p_tanggal_selesai
                AND (p_tahun_ajaran_id IS NULL OR ps.tahun_ajaran_id = p_tahun_ajaran_id)
                GROUP BY DATE_FORMAT(ps.tgl_bayar, '%Y-%m'), ps.metode_pembayaran
                ORDER BY periode DESC;
            END
        ");

        // 3. SP STATISTIK KEHADIRAN SISWA - Untuk monitoring orang tua & kepsek
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_statistik_kehadiran_siswa;
        ");
        
        DB::unprepared("
            CREATE PROCEDURE sp_statistik_kehadiran_siswa(
                IN p_siswa_id BIGINT,
                IN p_tahun_ajaran_id BIGINT,
                IN p_bulan INT,
                IN p_tahun INT
            )
            BEGIN
                SELECT 
                    s.id_siswa,
                    s.nama_lengkap,
                    k.nama_kelas,
                    COUNT(DISTINCT da.id_detail_absensi) AS total_pertemuan,
                    COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) AS hadir,
                    COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Izin' THEN da.id_detail_absensi END) AS izin,
                    COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Sakit' THEN da.id_detail_absensi END) AS sakit,
                    COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Alpa' THEN da.id_detail_absensi END) AS alpa,
                    ROUND((COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) * 100.0 / 
                        NULLIF(COUNT(DISTINCT da.id_detail_absensi), 0)), 2) AS persentase_kehadiran
                FROM siswa s
                JOIN kelas k ON s.kelas_id = k.id_kelas
                LEFT JOIN detail_absensi da ON s.id_siswa = da.siswa_id
                LEFT JOIN pertemuan p ON da.pertemuan_id = p.id_pertemuan
                WHERE s.id_siswa = p_siswa_id
                AND k.tahun_ajaran_id = p_tahun_ajaran_id
                AND (p_bulan IS NULL OR MONTH(p.tgl_pertemuan) = p_bulan)
                AND (p_tahun IS NULL OR YEAR(p.tgl_pertemuan) = p_tahun)
                GROUP BY s.id_siswa, s.nama_lengkap, k.nama_kelas;
            END
        ");

        // 4. SP EVALUASI KINERJA GURU - Auto calculate dari data sistem
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_evaluasi_kinerja_guru;
        ");
        
        DB::unprepared("
            CREATE PROCEDURE sp_evaluasi_kinerja_guru(
                IN p_guru_id BIGINT,
                IN p_tahun_ajaran_id BIGINT
            )
            BEGIN
                DECLARE v_ketepatan_waktu INT;
                DECLARE v_kelengkapan_materi INT;
                DECLARE v_kualitas_pembelajaran INT;
                DECLARE v_administrasi_kelas INT;
                
                -- Hitung ketepatan waktu (berdasarkan pertemuan yang ada)
                SELECT 
                    ROUND(
                        (COUNT(CASE WHEN p.status = 'Selesai' THEN 1 END) * 100.0 / 
                        NULLIF(COUNT(*), 0))
                    )
                INTO v_ketepatan_waktu
                FROM pertemuan p
                JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
                WHERE jp.guru_id = p_guru_id
                AND jp.tahun_ajaran_id = p_tahun_ajaran_id;
                
                -- Hitung kelengkapan materi (upload materi)
                SELECT 
                    ROUND(
                        (COUNT(CASE WHEN m.id_materi IS NOT NULL THEN 1 END) * 100.0 / 
                        NULLIF(COUNT(*), 0))
                    )
                INTO v_kelengkapan_materi
                FROM pertemuan p
                LEFT JOIN materi m ON p.id_pertemuan = m.pertemuan_id
                JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
                WHERE jp.guru_id = p_guru_id
                AND jp.tahun_ajaran_id = p_tahun_ajaran_id;
                
                -- Hitung kualitas pembelajaran (rata-rata nilai siswa)
                SELECT 
                    COALESCE(AVG(n.nilai_akhir), 0)
                INTO v_kualitas_pembelajaran
                FROM nilai n
                JOIN jadwal_pelajaran jp ON n.mata_pelajaran_id = jp.mata_pelajaran_id
                WHERE jp.guru_id = p_guru_id
                AND n.tahun_ajaran_id = p_tahun_ajaran_id;
                
                -- Hitung administrasi kelas (input nilai)
                SELECT 
                    ROUND(
                        (COUNT(CASE WHEN n.nilai_akhir IS NOT NULL THEN 1 END) * 100.0 / 
                        NULLIF(COUNT(DISTINCT s.id_siswa), 0))
                    )
                INTO v_administrasi_kelas
                FROM siswa s
                JOIN kelas k ON s.kelas_id = k.id_kelas
                JOIN jadwal_pelajaran jp ON k.id_kelas = jp.kelas_id
                LEFT JOIN nilai n ON s.id_siswa = n.siswa_id AND jp.mata_pelajaran_id = n.mata_pelajaran_id
                WHERE jp.guru_id = p_guru_id
                AND jp.tahun_ajaran_id = p_tahun_ajaran_id;
                
                -- Return hasil evaluasi
                SELECT 
                    p_guru_id AS guru_id,
                    COALESCE(v_ketepatan_waktu, 0) AS ketepatan_waktu_mengajar,
                    COALESCE(v_kelengkapan_materi, 0) AS kelengkapan_materi,
                    COALESCE(v_kualitas_pembelajaran, 0) AS kualitas_pembelajaran,
                    COALESCE(v_administrasi_kelas, 0) AS administrasi_kelas,
                    ROUND((
                        COALESCE(v_ketepatan_waktu, 0) + 
                        COALESCE(v_kelengkapan_materi, 0) + 
                        COALESCE(v_kualitas_pembelajaran, 0) + 
                        COALESCE(v_administrasi_kelas, 0)
                    ) / 4.0, 2) AS skor_total;
            END
        ");

        // 5. SP SEND REMINDER TUNGGAKAN - Auto reminder untuk tagihan lewat jatuh tempo
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_send_reminder_tunggakan;
        ");
        
        DB::unprepared("
            CREATE PROCEDURE sp_send_reminder_tunggakan(
                IN p_hari_terlambat INT
            )
            BEGIN
                INSERT INTO payment_reminder (
                    pembayaran_id,
                    siswa_id,
                    metode,
                    tujuan,
                    pesan,
                    status,
                    is_auto,
                    created_at
                )
                SELECT 
                    ps.id_pembayaran,
                    ps.siswa_id,
                    'WhatsApp' AS metode,
                    COALESCE(ot.no_telepon, '0000000000') AS tujuan,
                    CONCAT(
                        'Reminder Pembayaran SPP\\n',
                        'Siswa: ', s.nama_lengkap, '\\n',
                        'Bulan: ', ps.bulan, '/', ps.tahun, '\\n',
                        'Jumlah: Rp ', FORMAT(ps.jumlah_bayar, 0), '\\n',
                        'Status: BELUM LUNAS\\n',
                        'Segera lakukan pembayaran.'
                    ) AS pesan,
                    'Pending' AS status,
                    TRUE AS is_auto,
                    NOW()
                FROM pembayaran_spp ps
                JOIN siswa s ON ps.siswa_id = s.id_siswa
                LEFT JOIN relasi_ortu_siswa ro ON s.id_siswa = ro.siswa_id AND ro.hubungan = 'Ayah'
                LEFT JOIN orang_tua ot ON ro.orang_tua_id = ot.id_orang_tua
                WHERE ps.status = 'Belum Lunas'
                AND DATEDIFF(CURDATE(), DATE(CONCAT(ps.tahun, '-', LPAD(ps.bulan, 2, '0'), '-01'))) >= p_hari_terlambat
                AND NOT EXISTS (
                    SELECT 1 FROM payment_reminder pr 
                    WHERE pr.pembayaran_id = ps.id_pembayaran 
                    AND DATE(pr.created_at) = CURDATE()
                );
                
                SELECT CONCAT('Berhasil create ', ROW_COUNT(), ' reminder') AS result;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_send_reminder_tunggakan");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_evaluasi_kinerja_guru");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_statistik_kehadiran_siswa");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_keuangan_periode");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_generate_tagihan_spp_massal");
    }
};
