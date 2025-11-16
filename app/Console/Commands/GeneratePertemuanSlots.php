<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalPelajaran;
use App\Models\Pertemuan;

class GeneratePertemuanSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pertemuan:generate {--force : Force regenerate even if slots exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate 16 empty pertemuan slots for all jadwal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        $jadwals = JadwalPelajaran::all();
        $this->info("Found {$jadwals->count()} jadwal pelajaran");
        
        $created = 0;
        $skipped = 0;
        
        foreach ($jadwals as $jadwal) {
            // Check existing pertemuan - use id_jadwal (primary key)
            $existingCount = Pertemuan::where('jadwal_id', $jadwal->id_jadwal)->count();
            
            if ($existingCount > 0 && !$force) {
                $this->warn("Jadwal {$jadwal->id_jadwal} already has {$existingCount} pertemuan - skipped");
                $skipped++;
                continue;
            }
            
            if ($force && $existingCount > 0) {
                Pertemuan::where('jadwal_id', $jadwal->id_jadwal)->delete();
                $this->info("Deleted {$existingCount} existing pertemuan for jadwal {$jadwal->id_jadwal}");
            }
            
            // Create 16 slots
            for ($i = 1; $i <= 16; $i++) {
                Pertemuan::create([
                    'jadwal_id' => $jadwal->id_jadwal, // Use id_jadwal from model
                    'nomor_pertemuan' => $i,
                    'tanggal_pertemuan' => null,
                    'tanggal_absen_dibuka' => null,
                    'tanggal_absen_ditutup' => null,
                    'jam_absen_buka' => null,
                    'jam_absen_tutup' => null,
                    'waktu_absen_dibuka' => null,
                    'waktu_absen_ditutup' => null,
                    'topik_bahasan' => null,
                    'is_submitted' => false,
                ]);
            }
            
            $this->info("✓ Created 16 pertemuan slots for jadwal {$jadwal->id_jadwal}");
            $created++;
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("- Created: {$created} jadwal");
        $this->info("- Skipped: {$skipped} jadwal");
        $this->info("- Total pertemuan created: " . ($created * 16));
        
        return Command::SUCCESS;
    }
}
