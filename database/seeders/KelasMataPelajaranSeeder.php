<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KelasMataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Ambil tahun ajaran aktif
        $tahunAjaran = DB::table('tahun_ajaran')
            ->where('status', 'Berlangsung')
            ->orWhere('status', 'Aktif')
            ->first();
        
        if (!$tahunAjaran) {
            // Jika belum ada, buat tahun ajaran
            $tahunAjaranId = DB::table('tahun_ajaran')->insertGetId([
                'tahun_mulai' => 2024,
                'tahun_selesai' => 2025,
                'semester' => 'Ganjil',
                'status' => 'Berlangsung',
            ]);
        } else {
            $tahunAjaranId = $tahunAjaran->id_tahun_ajaran;
        }

        // ============================================
        // 1. TAMBAH KELAS
        // ============================================
        
        $kelasData = [
            // KELAS X (Fase E) - Belum ada penjurusan
            ['nama_kelas' => 'X-1', 'tingkat' => '10', 'jurusan' => 'Umum', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'X-2', 'tingkat' => '10', 'jurusan' => 'Umum', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'X-3', 'tingkat' => '10', 'jurusan' => 'Umum', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'X-4', 'tingkat' => '10', 'jurusan' => 'Umum', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'X-5', 'tingkat' => '10', 'jurusan' => 'Umum', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'X-6', 'tingkat' => '10', 'jurusan' => 'Umum', 'tahun_ajaran_id' => $tahunAjaranId],
            
            // KELAS XI (Fase F) - MIPA
            ['nama_kelas' => 'XI-MIPA-1', 'tingkat' => '11', 'jurusan' => 'MIPA', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XI-MIPA-2', 'tingkat' => '11', 'jurusan' => 'MIPA', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XI-MIPA-3', 'tingkat' => '11', 'jurusan' => 'MIPA', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XI-MIPA-4', 'tingkat' => '11', 'jurusan' => 'MIPA', 'tahun_ajaran_id' => $tahunAjaranId],
            
            // KELAS XI (Fase F) - IPS
            ['nama_kelas' => 'XI-IPS-1', 'tingkat' => '11', 'jurusan' => 'IPS', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XI-IPS-2', 'tingkat' => '11', 'jurusan' => 'IPS', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XI-IPS-3', 'tingkat' => '11', 'jurusan' => 'IPS', 'tahun_ajaran_id' => $tahunAjaranId],
            
            // KELAS XII (Fase F) - MIPA
            ['nama_kelas' => 'XII-MIPA-1', 'tingkat' => '12', 'jurusan' => 'MIPA', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XII-MIPA-2', 'tingkat' => '12', 'jurusan' => 'MIPA', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XII-MIPA-3', 'tingkat' => '12', 'jurusan' => 'MIPA', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XII-MIPA-4', 'tingkat' => '12', 'jurusan' => 'MIPA', 'tahun_ajaran_id' => $tahunAjaranId],
            
            // KELAS XII (Fase F) - IPS
            ['nama_kelas' => 'XII-IPS-1', 'tingkat' => '12', 'jurusan' => 'IPS', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XII-IPS-2', 'tingkat' => '12', 'jurusan' => 'IPS', 'tahun_ajaran_id' => $tahunAjaranId],
            ['nama_kelas' => 'XII-IPS-3', 'tingkat' => '12', 'jurusan' => 'IPS', 'tahun_ajaran_id' => $tahunAjaranId],
        ];

        foreach ($kelasData as $kelas) {
            DB::table('kelas')->updateOrInsert(
                ['nama_kelas' => $kelas['nama_kelas'], 'tahun_ajaran_id' => $tahunAjaranId],
                $kelas
            );
        }

        $this->command->info('✓ Kelas berhasil ditambahkan (20 kelas)');

        // ============================================
        // 2. TAMBAH MATA PELAJARAN
        // ============================================
        
        $mapelData = [
            // A. MATA PELAJARAN UMUM (Wajib untuk Semua Kelas X, XI, XII)
            ['kode_mapel' => 'PAI', 'nama_mapel' => 'Pendidikan Agama Islam', 'kategori' => 'Umum', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kode_mapel' => 'PAK', 'nama_mapel' => 'Pendidikan Agama Kristen', 'kategori' => 'Umum', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kode_mapel' => 'PAKA', 'nama_mapel' => 'Pendidikan Agama Katolik', 'kategori' => 'Umum', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kode_mapel' => 'PAH', 'nama_mapel' => 'Pendidikan Agama Hindu', 'kategori' => 'Umum', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kode_mapel' => 'PAB', 'nama_mapel' => 'Pendidikan Agama Buddha', 'kategori' => 'Umum', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kode_mapel' => 'PP', 'nama_mapel' => 'Pendidikan Pancasila', 'kategori' => 'Umum', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00'],
            ['kode_mapel' => 'BIND', 'nama_mapel' => 'Bahasa Indonesia', 'kategori' => 'Umum', 'jam_mulai' => '10:00', 'jam_selesai' => '11:30'],
            ['kode_mapel' => 'MTK-W', 'nama_mapel' => 'Matematika Wajib', 'kategori' => 'Umum', 'jam_mulai' => '12:30', 'jam_selesai' => '14:00'],
            ['kode_mapel' => 'BING', 'nama_mapel' => 'Bahasa Inggris', 'kategori' => 'Umum', 'jam_mulai' => '14:00', 'jam_selesai' => '15:30'],
            ['kode_mapel' => 'SEJ', 'nama_mapel' => 'Sejarah Indonesia', 'kategori' => 'Umum', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kode_mapel' => 'PJOK', 'nama_mapel' => 'PJOK', 'kategori' => 'Umum', 'jam_mulai' => '07:00', 'jam_selesai' => '09:00'],
            ['kode_mapel' => 'SEN-MUS', 'nama_mapel' => 'Seni Musik', 'kategori' => 'Umum', 'jam_mulai' => '09:00', 'jam_selesai' => '10:30'],
            ['kode_mapel' => 'SEN-RUP', 'nama_mapel' => 'Seni Rupa', 'kategori' => 'Umum', 'jam_mulai' => '09:00', 'jam_selesai' => '10:30'],
            ['kode_mapel' => 'PRKY', 'nama_mapel' => 'Prakarya', 'kategori' => 'Umum', 'jam_mulai' => '09:00', 'jam_selesai' => '10:30'],
            ['kode_mapel' => 'MULOK', 'nama_mapel' => 'Bahasa Daerah (Budaya Melayu)', 'kategori' => 'Mulok', 'jam_mulai' => '13:00', 'jam_selesai' => '14:00'],
            
            // B. MATA PELAJARAN KELAS X (Fase E)
            ['kode_mapel' => 'IPA-TRP', 'nama_mapel' => 'IPA Terpadu', 'kategori' => 'Kelas X', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00'],
            ['kode_mapel' => 'IPS-TRP', 'nama_mapel' => 'IPS Terpadu', 'kategori' => 'Kelas X', 'jam_mulai' => '10:00', 'jam_selesai' => '12:00'],
            ['kode_mapel' => 'INF', 'nama_mapel' => 'Informatika', 'kategori' => 'Kelas X', 'jam_mulai' => '13:00', 'jam_selesai' => '14:30'],
            ['kode_mapel' => 'P5', 'nama_mapel' => 'Projek Penguatan Profil Pelajar Pancasila (P5)', 'kategori' => 'Kelas X', 'jam_mulai' => '14:30', 'jam_selesai' => '16:00'],
            
            // C. MATA PELAJARAN PILIHAN MIPA (Fase F - Kelas XI & XII)
            ['kode_mapel' => 'MTK-L', 'nama_mapel' => 'Matematika Tingkat Lanjut', 'kategori' => 'MIPA', 'jam_mulai' => '07:00', 'jam_selesai' => '09:00'],
            ['kode_mapel' => 'FIS', 'nama_mapel' => 'Fisika', 'kategori' => 'MIPA', 'jam_mulai' => '09:00', 'jam_selesai' => '11:00'],
            ['kode_mapel' => 'KIM', 'nama_mapel' => 'Kimia', 'kategori' => 'MIPA', 'jam_mulai' => '11:00', 'jam_selesai' => '13:00'],
            ['kode_mapel' => 'BIO', 'nama_mapel' => 'Biologi', 'kategori' => 'MIPA', 'jam_mulai' => '13:30', 'jam_selesai' => '15:30'],
            ['kode_mapel' => 'INF-L', 'nama_mapel' => 'Informatika Lanjut', 'kategori' => 'MIPA', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00'],
            
            // D. MATA PELAJARAN PILIHAN IPS (Fase F - Kelas XI & XII)
            ['kode_mapel' => 'SOS', 'nama_mapel' => 'Sosiologi', 'kategori' => 'IPS', 'jam_mulai' => '07:00', 'jam_selesai' => '09:00'],
            ['kode_mapel' => 'EKO', 'nama_mapel' => 'Ekonomi', 'kategori' => 'IPS', 'jam_mulai' => '09:00', 'jam_selesai' => '11:00'],
            ['kode_mapel' => 'GEO', 'nama_mapel' => 'Geografi', 'kategori' => 'IPS', 'jam_mulai' => '11:00', 'jam_selesai' => '13:00'],
            ['kode_mapel' => 'ANT', 'nama_mapel' => 'Antropologi', 'kategori' => 'IPS', 'jam_mulai' => '13:30', 'jam_selesai' => '15:00'],
            
            // E. MATA PELAJARAN BAHASA & BUDAYA
            ['kode_mapel' => 'BING-L', 'nama_mapel' => 'Bahasa Inggris Tingkat Lanjut', 'kategori' => 'Bahasa', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00'],
            ['kode_mapel' => 'BJPN', 'nama_mapel' => 'Bahasa Jepang', 'kategori' => 'Bahasa', 'jam_mulai' => '10:00', 'jam_selesai' => '11:30'],
            ['kode_mapel' => 'BJRM', 'nama_mapel' => 'Bahasa Jerman', 'kategori' => 'Bahasa', 'jam_mulai' => '10:00', 'jam_selesai' => '11:30'],
        ];

        foreach ($mapelData as $mapel) {
            DB::table('mata_pelajaran')->updateOrInsert(
                ['kode_mapel' => $mapel['kode_mapel']],
                $mapel
            );
        }

        $this->command->info('✓ Mata Pelajaran berhasil ditambahkan (31 mapel)');
        $this->command->info('');
        $this->command->info('=== RINGKASAN ===');
        $this->command->info('Total Kelas: 20 kelas (6 kelas X, 7 kelas XI, 7 kelas XII)');
        $this->command->info('Total Mata Pelajaran: 31 mapel');
        $this->command->info('  - Umum/Wajib: 15 mapel');
        $this->command->info('  - Kelas X (Fase E): 4 mapel');
        $this->command->info('  - MIPA (Fase F): 5 mapel');
        $this->command->info('  - IPS (Fase F): 4 mapel');
        $this->command->info('  - Bahasa & Budaya: 3 mapel');
    }
}
