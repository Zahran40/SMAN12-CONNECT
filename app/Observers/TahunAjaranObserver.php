<?php

namespace App\Observers;

use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Log;

class TahunAjaranObserver
{
    /**
     * Handle the TahunAjaran "created" event.
     * Auto-create 27 kelas (9 kelas x 3 tingkat) HANYA untuk semester Ganjil
     * Semester Genap menggunakan kelas yang sama
     */
    public function created(TahunAjaran $tahunAjaran): void
    {
        Log::info("🎓 Observer: Tahun Ajaran baru dibuat - {$tahunAjaran->tahun_mulai}/{$tahunAjaran->tahun_selesai} {$tahunAjaran->semester}");
        
        // HANYA create kelas untuk semester Ganjil
        // Semester Genap akan menggunakan kelas yang sama
        if ($tahunAjaran->semester !== 'Ganjil') {
            Log::info("ℹ️ Observer: Skip create kelas untuk semester {$tahunAjaran->semester} (kelas sudah dibuat di semester Ganjil)");
            return;
        }
        
        // Template kelas untuk SMA Negeri 12 Medan
        $kelasTemplate = $this->getKelasTemplate();
        
        $createdCount = 0;
        
        foreach ($kelasTemplate as $template) {
            try {
                Kelas::create([
                    'nama_kelas' => $template['nama_kelas'],
                    'tingkat' => $template['tingkat'],
                    'jurusan' => $template['jurusan'],
                    'tahun_ajaran_id' => $tahunAjaran->id_tahun_ajaran,
                    'wali_kelas_id' => null, // Akan diisi kemudian oleh admin
                ]);
                $createdCount++;
            } catch (\Exception $e) {
                Log::error("❌ Gagal create kelas {$template['nama_kelas']}: {$e->getMessage()}");
            }
        }
        
        Log::info("✅ Observer: Berhasil create {$createdCount}/27 kelas untuk tahun ajaran {$tahunAjaran->id_tahun_ajaran}");
    }

    /**
     * Handle the TahunAjaran "updating" event.
     * Jika status diubah menjadi "Tidak Aktif", tidak perlu hapus data.
     * Data tetap ada untuk keperluan historis/audit.
     */
    public function updating(TahunAjaran $tahunAjaran): void
    {
        // Cek jika status berubah dari Aktif → Tidak Aktif
        if ($tahunAjaran->isDirty('status')) {
            $statusLama = $tahunAjaran->getOriginal('status');
            $statusBaru = $tahunAjaran->status;
            
            if ($statusLama === 'Aktif' && $statusBaru === 'Tidak Aktif') {
                Log::info("⚠️ Observer: Tahun ajaran {$tahunAjaran->id_tahun_ajaran} diubah menjadi Tidak Aktif");
                Log::info("ℹ️ Data kelas dan relasi tetap tersimpan untuk keperluan historis");
                
                // TIDAK MENGHAPUS DATA
                // Data kelas, siswa_kelas, jadwal, nilai, dll tetap ada
                // Hanya filter di query: WHERE tahun_ajaran_id = X AND status = 'Aktif'
            }
        }
    }

    /**
     * Template 27 kelas standar untuk SMA Negeri 12 Medan
     * 
     * Format:
     * - Tingkat 10: X-E1 s/d X-E9 (Total: 9 kelas)
     * - Tingkat 11: XI-F1 s/d XI-F9 (Total: 9 kelas)
     * - Tingkat 12: XII-F1 s/d XII-F9 (Total: 9 kelas)
     * 
     * Total: 27 kelas per tahun ajaran
     */
    private function getKelasTemplate(): array
    {
        $template = [];
        
        // Tingkat 10 (Kelas X): X-E1 sampai X-E9
        for ($i = 1; $i <= 9; $i++) {
            $template[] = [
                'nama_kelas' => "X-E{$i}",
                'tingkat' => '10',
                'jurusan' => 'Umum',
            ];
        }
        
        // Tingkat 11 (Kelas XI): XI-F1 sampai XI-F9
        for ($i = 1; $i <= 9; $i++) {
            $template[] = [
                'nama_kelas' => "XI-F{$i}",
                'tingkat' => '11',
                'jurusan' => 'IPA',
            ];
        }
        
        // Tingkat 12 (Kelas XII): XII-F1 sampai XII-F9
        for ($i = 1; $i <= 9; $i++) {
            $template[] = [
                'nama_kelas' => "XII-F{$i}",
                'tingkat' => '12',
                'jurusan' => 'IPA',
            ];
        }
        
        return $template; // Total: 27 kelas
    }
}
