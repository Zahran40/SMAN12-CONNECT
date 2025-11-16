<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PertemuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua data pertemuan dan detail_absensi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_absensi')->truncate();
        DB::table('pertemuan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get semua jadwal pelajaran
        $jadwalList = DB::table('jadwal_pelajaran')->get();

        foreach ($jadwalList as $jadwal) {
            // Buat 16 slot pertemuan kosong untuk setiap jadwal
            for ($i = 1; $i <= 16; $i++) {
                DB::table('pertemuan')->insert([
                    'jadwal_id' => $jadwal->id_jadwal,
                    'nomor_pertemuan' => $i,
                    'tanggal_pertemuan' => null,
                    'tanggal_absen_dibuka' => null,
                    'tanggal_absen_ditutup' => null,
                    'jam_absen_buka' => null,
                    'jam_absen_tutup' => null,
                    'topik_bahasan' => null,
                    'is_submitted' => false,
                    'submitted_at' => null,
                    'submitted_by' => null,
                ]);
            }
        }

        $this->command->info('Berhasil membuat ' . (count($jadwalList) * 16) . ' slot pertemuan untuk ' . count($jadwalList) . ' jadwal.');
    }
}
