<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * View untuk melihat semua tugas per siswa
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW view_tugas_siswa AS
            SELECT 
                t.id_tugas,
                t.judul_tugas,
                t.deskripsi_tugas,
                t.file_path AS file_tugas,
                t.tanggal_deadline,
                t.jam_buka,
                t.jam_tutup,
                t.created_at AS tgl_upload,
                p.id_pertemuan,
                p.nomor_pertemuan,
                p.topik_bahasan,
                jp.id_jadwal,
                mp.id_mapel,
                mp.nama_mapel,
                k.id_kelas,
                k.nama_kelas,
                g.id_guru,
                g.nama_lengkap AS nama_guru,
                dt.id_detail_tugas,
                dt.siswa_id,
                dt.file_path AS file_jawaban,
                dt.teks_jawaban,
                dt.tgl_kumpul,
                dt.nilai,
                dt.komentar_guru,
                s.nama_lengkap AS nama_siswa,
                s.nis,
                s.nisn,
                CASE 
                    WHEN dt.tgl_kumpul IS NULL THEN 'Belum Dikumpulkan'
                    WHEN dt.tgl_kumpul <= CONCAT(t.tanggal_deadline, ' ', t.jam_tutup) THEN 'Tepat Waktu'
                    ELSE 'Terlambat'
                END AS status_pengumpulan
            FROM tugas t
            JOIN pertemuan p ON t.pertemuan_id = p.id_pertemuan
            JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
            JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
            JOIN kelas k ON jp.kelas_id = k.id_kelas
            JOIN guru g ON jp.guru_id = g.id_guru
            LEFT JOIN detail_tugas dt ON t.id_tugas = dt.tugas_id
            LEFT JOIN siswa s ON dt.siswa_id = s.id_siswa
            ORDER BY t.tanggal_deadline DESC, s.nama_lengkap ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_tugas_siswa");
    }
};
