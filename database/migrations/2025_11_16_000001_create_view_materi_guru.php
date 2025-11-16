<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * View untuk melihat semua materi yang diupload oleh guru
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW view_materi_guru AS
            SELECT 
                m.id_materi,
                m.judul_materi,
                m.deskripsi,
                m.file_path,
                m.tgl_upload,
                p.id_pertemuan,
                p.nomor_pertemuan,
                p.topik_bahasan,
                p.tanggal_pertemuan,
                jp.id_jadwal,
                mp.id_mapel,
                mp.nama_mapel,
                k.id_kelas,
                k.nama_kelas,
                g.id_guru,
                g.nama_lengkap AS nama_guru,
                g.nip,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester
            FROM materi m
            JOIN pertemuan p ON m.pertemuan_id = p.id_pertemuan
            JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
            JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
            JOIN kelas k ON jp.kelas_id = k.id_kelas
            JOIN guru g ON jp.guru_id = g.id_guru
            JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
            WHERE ta.status = 'Aktif'
            ORDER BY m.tgl_upload DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_materi_guru");
    }
};
