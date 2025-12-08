<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SiswaKelas;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class NaikkanKelasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'siswa:naik-kelas 
                            {tahun_ajaran_lama_id : ID Tahun Ajaran yang akan dinaikkan (tahun lama)}
                            {tahun_ajaran_baru_id : ID Tahun Ajaran tujuan (tahun baru)}
                            {--force : Skip konfirmasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Naikkan kelas siswa secara otomatis dari tahun ajaran lama ke baru (X→XI, XI→XII, XII→Lulus)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tahunLamaId = $this->argument('tahun_ajaran_lama_id');
        $tahunBaruId = $this->argument('tahun_ajaran_baru_id');

        // Validasi tahun ajaran
        $tahunLama = TahunAjaran::find($tahunLamaId);
        $tahunBaru = TahunAjaran::find($tahunBaruId);

        if (!$tahunLama) {
            $this->error("Tahun ajaran lama (ID: {$tahunLamaId}) tidak ditemukan!");
            return 1;
        }

        if (!$tahunBaru) {
            $this->error("Tahun ajaran baru (ID: {$tahunBaruId}) tidak ditemukan!");
            return 1;
        }

        // Pastikan tahun ajaran baru adalah semester Ganjil
        if ($tahunBaru->semester !== 'Ganjil') {
            $this->error("Tahun ajaran baru harus semester Ganjil! (ID: {$tahunBaruId} adalah {$tahunBaru->semester})");
            return 1;
        }

        $this->info("===========================================");
        $this->info("PROSES KENAIKAN KELAS OTOMATIS");
        $this->info("===========================================");
        $this->info("Dari: {$tahunLama->tahun_mulai}/{$tahunLama->tahun_selesai} ({$tahunLama->semester})");
        $this->info("Ke  : {$tahunBaru->tahun_mulai}/{$tahunBaru->tahun_selesai} ({$tahunBaru->semester})");
        $this->newLine();

        // Konfirmasi
        if (!$this->option('force')) {
            if (!$this->confirm('Lanjutkan proses kenaikan kelas?')) {
                $this->info('Proses dibatalkan.');
                return 0;
            }
        }

        DB::beginTransaction();

        try {
            $stats = [
                'naik_X_ke_XI' => 0,
                'naik_XI_ke_XII' => 0,
                'lulus_XII' => 0,
                'tidak_ada_kelas_baru' => 0,
                'sudah_ada_di_kelas_baru' => 0,
            ];

            // Ambil semua siswa aktif di tahun ajaran lama
            $siswaKelasLama = SiswaKelas::where('tahun_ajaran_id', $tahunLamaId)
                ->where('status', 'Aktif')
                ->with(['siswa', 'kelas'])
                ->get();

            $this->info("Total siswa aktif di tahun ajaran lama: " . $siswaKelasLama->count());
            $this->newLine();

            $progressBar = $this->output->createProgressBar($siswaKelasLama->count());
            $progressBar->start();

            foreach ($siswaKelasLama as $siswaKelas) {
                $siswa = $siswaKelas->siswa;
                $kelasLama = $siswaKelas->kelas;

                // Parse tingkat dari nama kelas (10/X, 11/XI, 12/XII)
                $tingkatLama = $kelasLama->tingkat;
                
                // Tentukan tingkat baru (support angka dan romawi)
                $tingkatBaru = null;
                if ($tingkatLama == '10' || $tingkatLama == 'X') {
                    $tingkatBaru = '11';
                    $stats['naik_X_ke_XI']++;
                } elseif ($tingkatLama == '11' || $tingkatLama == 'XI') {
                    $tingkatBaru = '12';
                    $stats['naik_XI_ke_XII']++;
                } elseif ($tingkatLama == '12' || $tingkatLama == 'XII') {
                    // Siswa kelas XII → Lulus
                    $siswaKelas->update([
                        'status' => 'Lulus',
                        'tanggal_keluar' => now(),
                    ]);
                    $stats['lulus_XII']++;
                    $progressBar->advance();
                    continue;
                }

                // Mapping jurusan lama ke baru (IPA → MIPA, IPS tetap IPS)
                $jurusanLama = $kelasLama->jurusan;
                $jurusanBaru = $jurusanLama;
                
                // Normalisasi nama jurusan
                if (strtoupper($jurusanLama) == 'IPA') {
                    $jurusanBaru = 'MIPA';
                } elseif (strtoupper($jurusanLama) == 'IPS') {
                    $jurusanBaru = 'IPS';
                }

                // Cari kelas baru dengan tingkat dan jurusan yang sesuai
                $kelasBaru = Kelas::where('tahun_ajaran_id', $tahunBaruId)
                    ->where('tingkat', $tingkatBaru)
                    ->where('jurusan', $jurusanBaru)
                    ->first();

                if (!$kelasBaru) {
                    // Tidak ada kelas baru yang sesuai
                    $this->warn("\nKelas baru tidak ditemukan untuk: {$siswa->nama_lengkap} ({$kelasLama->nama_kelas} → {$tingkatBaru}-{$kelasLama->jurusan})");
                    $stats['tidak_ada_kelas_baru']++;
                    $progressBar->advance();
                    continue;
                }

                // Cek apakah siswa sudah ada di tahun ajaran baru
                $sudahAda = SiswaKelas::where('siswa_id', $siswa->id_siswa)
                    ->where('tahun_ajaran_id', $tahunBaruId)
                    ->exists();

                if ($sudahAda) {
                    $stats['sudah_ada_di_kelas_baru']++;
                    $progressBar->advance();
                    continue;
                }

                // Nonaktifkan siswa di kelas lama (status Pindah karena naik kelas)
                $siswaKelas->update([
                    'status' => 'Pindah',
                    'tanggal_keluar' => now(),
                ]);

                // Tambahkan siswa ke kelas baru
                SiswaKelas::create([
                    'siswa_id' => $siswa->id_siswa,
                    'kelas_id' => $kelasBaru->id_kelas,
                    'tahun_ajaran_id' => $tahunBaruId,
                    'tanggal_masuk' => now(),
                    'status' => 'Aktif',
                ]);

                // Update kolom kelas_id di tabel siswa (untuk kompatibilitas)
                $siswa->update(['kelas_id' => $kelasBaru->id_kelas]);

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);

            DB::commit();

            // Tampilkan statistik
            $this->info("===========================================");
            $this->info("HASIL KENAIKAN KELAS");
            $this->info("===========================================");
            $this->info("✅ X → XI       : {$stats['naik_X_ke_XI']} siswa");
            $this->info("✅ XI → XII     : {$stats['naik_XI_ke_XII']} siswa");
            $this->info("🎓 XII → Lulus  : {$stats['lulus_XII']} siswa");
            
            if ($stats['tidak_ada_kelas_baru'] > 0) {
                $this->warn("⚠️  Tidak ada kelas baru: {$stats['tidak_ada_kelas_baru']} siswa");
            }
            
            if ($stats['sudah_ada_di_kelas_baru'] > 0) {
                $this->warn("⚠️  Sudah ada di kelas baru: {$stats['sudah_ada_di_kelas_baru']} siswa (dilewati)");
            }

            $this->newLine();
            $this->info("Proses kenaikan kelas selesai!");

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
