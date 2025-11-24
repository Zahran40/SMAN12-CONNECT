<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW view_absensi_siswa AS
            SELECT 
                da.id_detail_absensi,
                da.status_kehadiran,
                da.keterangan,
                da.dicatat_pada,
                s.id_siswa,
                s.nis,
                s.nama_lengkap AS nama_siswa,
                k.nama_kelas,
                p.id_pertemuan,
                p.tanggal_pertemuan,
                p.topik_bahasan,
                p.status_sesi,
                mp.nama_mapel,
                g.nama_lengkap AS nama_guru,
                jp.hari,
                jp.jam_mulai,
                jp.jam_selesai,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester
            FROM detail_absensi da
            JOIN siswa s ON da.siswa_id = s.id_siswa
            JOIN kelas k ON s.kelas_id = k.id_kelas
            LEFT JOIN pertemuan p ON da.pertemuan_id = p.id_pertemuan
            LEFT JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
            LEFT JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
            LEFT JOIN guru g ON jp.guru_id = g.id_guru
            LEFT JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
        ");
    }
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_absensi_siswa");
    }
};