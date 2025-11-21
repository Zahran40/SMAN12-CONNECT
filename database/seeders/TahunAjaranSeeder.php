<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TahunAjaran;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Seed tahun ajaran untuk testing
     * Observer akan otomatis create 30 kelas untuk setiap tahun ajaran
     * (10 kelas per tingkat: 5 MIPA + 5 IPS)
     */
    public function run(): void
    {
        // 1. Tahun Ajaran 2024/2025 Ganjil (AKTIF)
        $ta1Exists = TahunAjaran::where('tahun_mulai', '2024')
            ->where('tahun_selesai', '2025')
            ->where('semester', 'Ganjil')
            ->exists();
            
        if (!$ta1Exists) {
            // Gunakan create() bukan firstOrCreate() agar observer trigger
            $ta1 = TahunAjaran::create([
                'tahun_mulai' => '2024',
                'tahun_selesai' => '2025',
                'semester' => 'Ganjil',
                'status' => 'Aktif',
            ]);
            $this->command->info("✅ Tahun Ajaran 2024/2025 Ganjil dibuat → Observer akan create 30 kelas");
        } else {
            $this->command->info("ℹ️ Tahun Ajaran 2024/2025 Ganjil sudah ada");
        }

        // 2. Tahun Ajaran 2024/2025 Genap (TIDAK AKTIF - untuk historis)
        $ta2Exists = TahunAjaran::where('tahun_mulai', '2024')
            ->where('tahun_selesai', '2025')
            ->where('semester', 'Genap')
            ->exists();
            
        if (!$ta2Exists) {
            $ta2 = TahunAjaran::create([
                'tahun_mulai' => '2024',
                'tahun_selesai' => '2025',
                'semester' => 'Genap',
                'status' => 'Tidak Aktif',
            ]);
            $this->command->info("✅ Tahun Ajaran 2024/2025 Genap dibuat → Observer akan create 30 kelas");
        } else {
            $this->command->info("ℹ️ Tahun Ajaran 2024/2025 Genap sudah ada");
        }

        // 3. Tahun Ajaran 2023/2024 Genap (TIDAK AKTIF - historis)
        $ta3Exists = TahunAjaran::where('tahun_mulai', '2023')
            ->where('tahun_selesai', '2024')
            ->where('semester', 'Genap')
            ->exists();
            
        if (!$ta3Exists) {
            $ta3 = TahunAjaran::create([
                'tahun_mulai' => '2023',
                'tahun_selesai' => '2024',
                'semester' => 'Genap',
                'status' => 'Tidak Aktif',
            ]);
            $this->command->info("✅ Tahun Ajaran 2023/2024 Genap dibuat → Observer akan create 30 kelas");
        } else {
            $this->command->info("ℹ️ Tahun Ajaran 2023/2024 Genap sudah ada");
        }

        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("📊 Total Tahun Ajaran: " . TahunAjaran::count());
        $this->command->info("📚 Total Kelas: " . DB::table('kelas')->count());
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }
}
