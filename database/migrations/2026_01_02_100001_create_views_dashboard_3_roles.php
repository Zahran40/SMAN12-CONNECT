<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Views untuk Dashboard 3 Role Baru
     */
    public function up(): void
    {
        // 1. VIEW DASHBOARD ORANG TUA - Ringkasan aktivitas anak
        DB::unprepared("
            CREATE OR REPLACE VIEW view_dashboard_orangtua AS
            SELECT 
                ro.id_relasi,
                ro.orang_tua_id,
                ro.siswa_id,
                ro.hubungan,
                s.nis,
                s.nisn,
                s.nama_lengkap AS nama_siswa,
                s.jenis_kelamin,
                k.nama_kelas,
                k.tingkat,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                
                -- Statistik Presensi
                COUNT(DISTINCT da.id_detail_absensi) AS total_pertemuan,
                COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) AS total_hadir,
                COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Izin' THEN da.id_detail_absensi END) AS total_izin,
                COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Sakit' THEN da.id_detail_absensi END) AS total_sakit,
                COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Alpa' THEN da.id_detail_absensi END) AS total_alpa,
                ROUND((COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) * 100.0 / 
                    NULLIF(COUNT(DISTINCT da.id_detail_absensi), 0)), 2) AS persentase_kehadiran,
                
                -- Statistik Akademik
                AVG(n.nilai_akhir) AS rata_rata_nilai,
                
                -- Statistik Keuangan
                COUNT(DISTINCT ps.id_pembayaran) AS total_tagihan,
                COUNT(DISTINCT CASE WHEN ps.status = 'Lunas' THEN ps.id_pembayaran END) AS tagihan_lunas,
                COUNT(DISTINCT CASE WHEN ps.status = 'Belum Lunas' THEN ps.id_pembayaran END) AS tagihan_belum_lunas,
                SUM(CASE WHEN ps.status = 'Belum Lunas' THEN ps.jumlah_bayar ELSE 0 END) AS total_tunggakan,
                
                -- Statistik Perilaku
                COUNT(DISTINCT CASE WHEN lp.jenis = 'Prestasi' THEN lp.id_laporan END) AS total_prestasi,
                COUNT(DISTINCT CASE WHEN lp.jenis = 'Pelanggaran' THEN lp.id_laporan END) AS total_pelanggaran,
                SUM(lp.poin) AS total_poin_perilaku
                
            FROM relasi_ortu_siswa ro
            JOIN siswa s ON ro.siswa_id = s.id_siswa
            LEFT JOIN kelas k ON s.kelas_id = k.id_kelas
            LEFT JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id_tahun_ajaran
            LEFT JOIN detail_absensi da ON s.id_siswa = da.siswa_id
            LEFT JOIN nilai n ON s.id_siswa = n.siswa_id AND ta.id_tahun_ajaran = n.tahun_ajaran_id
            LEFT JOIN pembayaran_spp ps ON s.id_siswa = ps.siswa_id AND ta.id_tahun_ajaran = ps.tahun_ajaran_id
            LEFT JOIN laporan_perilaku lp ON s.id_siswa = lp.siswa_id AND ta.id_tahun_ajaran = lp.tahun_ajaran_id
            WHERE ta.status = 'Aktif' OR ta.status IS NULL
            GROUP BY ro.id_relasi, ro.orang_tua_id, ro.siswa_id, ro.hubungan, 
                     s.nis, s.nisn, s.nama_lengkap, s.jenis_kelamin,
                     k.nama_kelas, k.tingkat, ta.tahun_mulai, ta.tahun_selesai, ta.semester
        ");

        // 2. VIEW DASHBOARD KEPALA SEKOLAH - KPI & Statistik Global
        DB::unprepared("
            CREATE OR REPLACE VIEW view_dashboard_kepsek AS
            SELECT 
                ta.id_tahun_ajaran,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                ta.status AS status_tahun_ajaran,
                
                -- Statistik Siswa
                COUNT(DISTINCT s.id_siswa) AS total_siswa_aktif,
                COUNT(DISTINCT k.id_kelas) AS total_kelas_aktif,
                ROUND(COUNT(DISTINCT s.id_siswa) * 1.0 / NULLIF(COUNT(DISTINCT k.id_kelas), 0), 2) AS rata_siswa_per_kelas,
                
                -- Statistik Guru
                COUNT(DISTINCT g.id_guru) AS total_guru_aktif,
                COUNT(DISTINCT jp.id_jadwal) AS total_jadwal,
                
                -- Statistik Presensi Siswa
                COUNT(DISTINCT da.id_detail_absensi) AS total_pertemuan_berlangsung,
                ROUND((COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) * 100.0 / 
                    NULLIF(COUNT(DISTINCT da.id_detail_absensi), 0)), 2) AS persentase_kehadiran_global,
                
                -- Statistik Akademik
                ROUND(AVG(n.nilai_akhir), 2) AS rata_nilai_sekolah,
                COUNT(DISTINCT CASE WHEN n.nilai_akhir >= 80 THEN n.id_nilai END) AS siswa_nilai_tinggi,
                COUNT(DISTINCT CASE WHEN n.nilai_akhir < 60 THEN n.id_nilai END) AS siswa_perlu_remedial,
                
                -- Statistik Keuangan
                COUNT(DISTINCT ps.id_pembayaran) AS total_tagihan_spp,
                COUNT(DISTINCT CASE WHEN ps.status = 'Lunas' THEN ps.id_pembayaran END) AS tagihan_lunas,
                ROUND((COUNT(DISTINCT CASE WHEN ps.status = 'Lunas' THEN ps.id_pembayaran END) * 100.0 / 
                    NULLIF(COUNT(DISTINCT ps.id_pembayaran), 0)), 2) AS persentase_pembayaran,
                SUM(CASE WHEN ps.status = 'Lunas' THEN ps.jumlah_bayar ELSE 0 END) AS total_pemasukan_spp,
                SUM(CASE WHEN ps.status = 'Belum Lunas' THEN ps.jumlah_bayar ELSE 0 END) AS total_tunggakan_spp,
                
                -- Statistik Perilaku
                COUNT(DISTINCT lp.id_laporan) AS total_laporan_perilaku,
                COUNT(DISTINCT CASE WHEN lp.jenis = 'Prestasi' THEN lp.id_laporan END) AS total_prestasi,
                COUNT(DISTINCT CASE WHEN lp.jenis = 'Pelanggaran' THEN lp.id_laporan END) AS total_pelanggaran
                
            FROM tahun_ajaran ta
            LEFT JOIN kelas k ON ta.id_tahun_ajaran = k.tahun_ajaran_id
            LEFT JOIN siswa s ON k.id_kelas = s.kelas_id
            LEFT JOIN guru g ON 1=1
            LEFT JOIN jadwal_pelajaran jp ON ta.id_tahun_ajaran = jp.tahun_ajaran_id
            LEFT JOIN pertemuan p ON jp.id_jadwal = p.jadwal_id
            LEFT JOIN detail_absensi da ON p.id_pertemuan = da.pertemuan_id
            LEFT JOIN nilai n ON s.id_siswa = n.siswa_id AND ta.id_tahun_ajaran = n.tahun_ajaran_id
            LEFT JOIN pembayaran_spp ps ON s.id_siswa = ps.siswa_id AND ta.id_tahun_ajaran = ps.tahun_ajaran_id
            LEFT JOIN laporan_perilaku lp ON s.id_siswa = lp.siswa_id AND ta.id_tahun_ajaran = lp.tahun_ajaran_id
            WHERE ta.status = 'Aktif'
            GROUP BY ta.id_tahun_ajaran, ta.tahun_mulai, ta.tahun_selesai, ta.semester, ta.status
        ");

        // 3. VIEW DASHBOARD BENDAHARA - Statistik Keuangan Detail
        DB::unprepared("
            CREATE OR REPLACE VIEW view_dashboard_bendahara AS
            SELECT 
                ta.id_tahun_ajaran,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                
                -- Statistik Tagihan
                COUNT(DISTINCT ps.id_pembayaran) AS total_tagihan,
                COUNT(DISTINCT CASE WHEN ps.status = 'Lunas' THEN ps.id_pembayaran END) AS total_lunas,
                COUNT(DISTINCT CASE WHEN ps.status = 'Belum Lunas' THEN ps.id_pembayaran END) AS total_belum_lunas,
                
                -- Statistik Pembayaran per Metode
                COUNT(DISTINCT CASE WHEN ps.metode_pembayaran = 'Tunai' THEN ps.id_pembayaran END) AS pembayaran_tunai,
                COUNT(DISTINCT CASE WHEN ps.metode_pembayaran = 'Transfer' THEN ps.id_pembayaran END) AS pembayaran_transfer,
                COUNT(DISTINCT CASE WHEN ps.metode_pembayaran = 'Kartu' THEN ps.id_pembayaran END) AS pembayaran_kartu,
                COUNT(DISTINCT CASE WHEN ps.metode_pembayaran = 'E-Wallet' THEN ps.id_pembayaran END) AS pembayaran_ewallet,
                
                -- Statistik Keuangan
                SUM(ps.jumlah_bayar) AS total_tagihan_nominal,
                SUM(CASE WHEN ps.status = 'Lunas' THEN ps.jumlah_bayar ELSE 0 END) AS total_pemasukan,
                SUM(CASE WHEN ps.status = 'Belum Lunas' THEN ps.jumlah_bayar ELSE 0 END) AS total_tunggakan,
                ROUND((SUM(CASE WHEN ps.status = 'Lunas' THEN ps.jumlah_bayar ELSE 0 END) * 100.0 / 
                    NULLIF(SUM(ps.jumlah_bayar), 0)), 2) AS persentase_terkumpul,
                
                -- Statistik Diskon/Beasiswa
                COUNT(DISTINCT sd.id) AS total_penerima_diskon,
                SUM(CASE 
                    WHEN db.tipe_potongan = 'Persentase' 
                    THEN ps.jumlah_bayar * (db.nilai_potongan / 100)
                    ELSE db.nilai_potongan 
                END) AS total_potongan_diberikan,
                
                -- Statistik Refund
                COUNT(DISTINCT rp.id_refund) AS total_pengajuan_refund,
                SUM(CASE WHEN rp.status = 'Selesai' THEN rp.jumlah_refund ELSE 0 END) AS total_refund_selesai,
                
                -- Statistik Reminder
                COUNT(DISTINCT pr.id_reminder) AS total_reminder_dikirim,
                COUNT(DISTINCT CASE WHEN pr.status = 'Terkirim' THEN pr.id_reminder END) AS reminder_terkirim
                
            FROM tahun_ajaran ta
            LEFT JOIN pembayaran_spp ps ON ta.id_tahun_ajaran = ps.tahun_ajaran_id
            LEFT JOIN siswa_diskon sd ON ps.siswa_id = sd.siswa_id AND ta.id_tahun_ajaran = sd.tahun_ajaran_id
            LEFT JOIN diskon_beasiswa db ON sd.diskon_id = db.id_diskon
            LEFT JOIN refund_pembayaran rp ON ps.id_pembayaran = rp.pembayaran_id
            LEFT JOIN payment_reminder pr ON ps.id_pembayaran = pr.pembayaran_id
            WHERE ta.status = 'Aktif'
            GROUP BY ta.id_tahun_ajaran, ta.tahun_mulai, ta.tahun_selesai, ta.semester
        ");

        // 4. VIEW TUNGGAKAN SPP - Detail siswa yang menunggak
        DB::unprepared("
            CREATE OR REPLACE VIEW view_tunggakan_spp AS
            SELECT 
                s.id_siswa,
                s.nis,
                s.nisn,
                s.nama_lengkap,
                k.nama_kelas,
                k.tingkat,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                ps.id_pembayaran,
                ps.bulan,
                ps.tahun,
                ps.jumlah_bayar,
                ps.tgl_bayar,
                ps.status,
                DATEDIFF(CURDATE(), DATE(CONCAT(ps.tahun, '-', LPAD(ps.bulan, 2, '0'), '-01'))) AS hari_terlambat,
                
                -- Info Orang Tua
                GROUP_CONCAT(DISTINCT CONCAT(ot.nama_lengkap, ' (', ro.hubungan, ')') SEPARATOR ', ') AS orang_tua,
                GROUP_CONCAT(DISTINCT ot.no_telepon SEPARATOR ', ') AS no_hp_ortu,
                
                -- Status Reminder
                COUNT(DISTINCT pr.id_reminder) AS total_reminder,
                MAX(pr.waktu_kirim) AS reminder_terakhir
                
            FROM pembayaran_spp ps
            JOIN siswa s ON ps.siswa_id = s.id_siswa
            JOIN kelas k ON s.kelas_id = k.id_kelas
            JOIN tahun_ajaran ta ON ps.tahun_ajaran_id = ta.id_tahun_ajaran
            LEFT JOIN relasi_ortu_siswa ro ON s.id_siswa = ro.siswa_id
            LEFT JOIN orang_tua ot ON ro.orang_tua_id = ot.id_orang_tua
            LEFT JOIN payment_reminder pr ON ps.id_pembayaran = pr.pembayaran_id
            WHERE ps.status = 'Belum Lunas'
            GROUP BY s.id_siswa, s.nis, s.nisn, s.nama_lengkap,
                     k.nama_kelas, k.tingkat, ta.tahun_mulai, ta.tahun_selesai, ta.semester,
                     ps.id_pembayaran, ps.bulan, ps.tahun, ps.jumlah_bayar, ps.tgl_bayar, ps.status
            ORDER BY hari_terlambat DESC
        ");

        // 5. VIEW STATISTIK PRESENSI - Per Kelas & Per Siswa
        DB::unprepared("
            CREATE OR REPLACE VIEW view_statistik_presensi AS
            SELECT 
                k.id_kelas,
                k.nama_kelas,
                k.tingkat,
                s.id_siswa,
                s.nis,
                s.nama_lengkap,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                
                COUNT(DISTINCT da.id_detail_absensi) AS total_pertemuan,
                COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) AS hadir,
                COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Izin' THEN da.id_detail_absensi END) AS izin,
                COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Sakit' THEN da.id_detail_absensi END) AS sakit,
                COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Alpa' THEN da.id_detail_absensi END) AS alpa,
                
                ROUND((COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) * 100.0 / 
                    NULLIF(COUNT(DISTINCT da.id_detail_absensi), 0)), 2) AS persentase_kehadiran,
                    
                -- Status Kehadiran (kategori berdasarkan persentase)
                CASE 
                    WHEN (COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) * 100.0 / 
                        NULLIF(COUNT(DISTINCT da.id_detail_absensi), 0)) >= 90 THEN 'Sangat Baik'
                    WHEN (COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) * 100.0 / 
                        NULLIF(COUNT(DISTINCT da.id_detail_absensi), 0)) >= 80 THEN 'Baik'
                    WHEN (COUNT(DISTINCT CASE WHEN da.status_kehadiran = 'Hadir' THEN da.id_detail_absensi END) * 100.0 / 
                        NULLIF(COUNT(DISTINCT da.id_detail_absensi), 0)) >= 70 THEN 'Cukup'
                    ELSE 'Kurang'
                END AS kategori_kehadiran
                
            FROM siswa s
            JOIN kelas k ON s.kelas_id = k.id_kelas
            JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id_tahun_ajaran
            LEFT JOIN detail_absensi da ON s.id_siswa = da.siswa_id
            WHERE ta.status = 'Aktif'
            GROUP BY k.id_kelas, k.nama_kelas, k.tingkat, s.id_siswa, s.nis, s.nama_lengkap,
                     ta.tahun_mulai, ta.tahun_selesai, ta.semester
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_statistik_presensi");
        DB::unprepared("DROP VIEW IF EXISTS view_tunggakan_spp");
        DB::unprepared("DROP VIEW IF EXISTS view_dashboard_bendahara");
        DB::unprepared("DROP VIEW IF EXISTS view_dashboard_kepsek");
        DB::unprepared("DROP VIEW IF EXISTS view_dashboard_orangtua");
    }
};
