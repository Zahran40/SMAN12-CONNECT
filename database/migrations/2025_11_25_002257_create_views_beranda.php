<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_jadwal_mengajar AS
            SELECT 
                jp.id_jadwal,
                jp.guru_id,
                jp.kelas_id,
                jp.mapel_id,
                jp.hari,
                jp.jam_mulai,
                jp.jam_selesai,
                jp.tahun_ajaran_id,
                mp.nama_mapel,
                k.nama_kelas,
                g.nama_lengkap AS nama_guru,
                (SELECT COUNT(*) 
                 FROM siswa_kelas sk 
                 WHERE sk.kelas_id = jp.kelas_id 
                 AND sk.tahun_ajaran_id = jp.tahun_ajaran_id 
                 AND sk.status = 'Aktif') AS jumlah_siswa
            FROM jadwal_pelajaran jp
            INNER JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
            INNER JOIN kelas k ON jp.kelas_id = k.id_kelas
            INNER JOIN guru g ON jp.guru_id = g.id_guru
            INNER JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
            WHERE ta.is_archived = 0
            AND EXISTS (
                SELECT 1 FROM tahun_ajaran ta_aktif
                WHERE ta_aktif.status = 'Aktif'
                AND ta_aktif.is_archived = 0
                AND ta_aktif.tahun_mulai = ta.tahun_mulai
                AND ta_aktif.tahun_selesai = ta.tahun_selesai
            )
            ORDER BY 
                FIELD(jp.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'),
                jp.jam_mulai
        ");
        DB::statement("
            CREATE OR REPLACE VIEW view_jadwal_siswa AS
            SELECT 
                jp.id_jadwal,
                jp.kelas_id,
                jp.mapel_id,
                jp.hari,
                jp.jam_mulai,
                jp.jam_selesai,
                mp.nama_mapel,
                g.nama_lengkap AS nama_guru,
                g.id_guru,
                k.nama_kelas
            FROM jadwal_pelajaran jp
            INNER JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
            INNER JOIN guru g ON jp.guru_id = g.id_guru
            INNER JOIN kelas k ON jp.kelas_id = k.id_kelas
            ORDER BY 
                FIELD(jp.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'),
                jp.jam_mulai
        ");
        DB::statement("
            CREATE OR REPLACE VIEW view_presensi_aktif AS
            SELECT 
                p.id_pertemuan,
                p.jadwal_id,
                p.nomor_pertemuan,
                p.tanggal_pertemuan,
                p.waktu_mulai,
                p.waktu_selesai,
                p.waktu_absen_dibuka,
                p.waktu_absen_ditutup,
                p.jam_absen_buka,
                p.jam_absen_tutup,
                jp.kelas_id,
                jp.mapel_id,
                jp.guru_id,
                jp.hari,
                mp.nama_mapel,
                g.nama_lengkap AS nama_guru,
                k.nama_kelas,
                CASE 
                    WHEN p.waktu_absen_dibuka IS NOT NULL 
                         AND p.waktu_absen_ditutup IS NOT NULL
                         AND NOW() BETWEEN p.waktu_absen_dibuka AND p.waktu_absen_ditutup
                    THEN 1 
                    ELSE 0 
                END AS is_open
            FROM pertemuan p
            INNER JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
            INNER JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
            INNER JOIN guru g ON jp.guru_id = g.id_guru
            INNER JOIN kelas k ON jp.kelas_id = k.id_kelas
            WHERE DATE(p.tanggal_pertemuan) = CURDATE()
              AND p.waktu_absen_dibuka IS NOT NULL
              AND p.waktu_absen_ditutup IS NOT NULL
        ");
        DB::statement("
            CREATE OR REPLACE VIEW view_status_absensi_siswa AS
            SELECT 
                da.id_detail_absensi,
                da.pertemuan_id,
                da.siswa_id,
                da.status_kehadiran,
                da.keterangan,
                da.dicatat_pada,
                p.jadwal_id,
                p.nomor_pertemuan,
                p.tanggal_pertemuan,
                jp.kelas_id,
                jp.mapel_id,
                mp.nama_mapel,
                s.nama_lengkap AS nama_siswa,
                s.nisn
            FROM detail_absensi da
            INNER JOIN pertemuan p ON da.pertemuan_id = p.id_pertemuan
            INNER JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
            INNER JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
            INNER JOIN siswa s ON da.siswa_id = s.id_siswa
        ");
    }
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_status_absensi_siswa');
        DB::statement('DROP VIEW IF EXISTS view_presensi_aktif');
        DB::statement('DROP VIEW IF EXISTS view_jadwal_siswa');
        DB::statement('DROP VIEW IF EXISTS view_jadwal_mengajar');
    }
};