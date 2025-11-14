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
        DB::unprepared("
            CREATE OR REPLACE VIEW view_jadwal_guru AS
            SELECT 
                jp.id_jadwal,
                jp.hari,
                jp.jam_mulai,
                jp.jam_selesai,
                g.id_guru,
                g.nip,
                g.nama_lengkap AS nama_guru,
                mp.id_mapel,
                mp.nama_mapel,
                mp.kode_mapel,
                k.id_kelas,
                k.nama_kelas,
                k.tingkat,
                ta.id_tahun_ajaran,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                ta.status AS status_tahun_ajaran
            FROM jadwal_pelajaran jp
            JOIN guru g ON jp.guru_id = g.id_guru
            JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
            JOIN kelas k ON jp.kelas_id = k.id_kelas
            JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
            ORDER BY 
                FIELD(jp.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'),
                jp.jam_mulai
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_jadwal_guru");
    }
};
