<?php

namespace App\Observers;

use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Log;

class TahunAjaranObserver
{
    /**
     * Handle the TahunAjaran "created" event.
     * Auto-create 36 kelas (12 kelas x 3 tingkat) saat tahun ajaran baru dibuat
     */
    public function created(TahunAjaran $tahunAjaran): void
    {
        Log::info("🎓 Observer: Tahun Ajaran baru dibuat - {$tahunAjaran->tahun_mulai}/{$tahunAjaran->tahun_selesai} {$tahunAjaran->semester}");
        
        // Template kelas untuk SMA Negeri 12 (standar)
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
        
        Log::info("✅ Observer: Berhasil create {$createdCount}/36 kelas untuk tahun ajaran {$tahunAjaran->id_tahun_ajaran}");
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
     * Template 36 kelas standar untuk SMA Negeri 12
     * 
     * Format:
     * - Tingkat 10: X-1 s/d X-12 (IPA: 8 kelas, IPS: 4 kelas)
     * - Tingkat 11: XI-1 s/d XI-12 (IPA: 8 kelas, IPS: 4 kelas)
     * - Tingkat 12: XII-1 s/d XII-12 (IPA: 8 kelas, IPS: 4 kelas)
     */
    private function getKelasTemplate(): array
    {
        $template = [];
        
        // Tingkat 10 (Kelas X)
        for ($i = 1; $i <= 12; $i++) {
            $template[] = [
                'nama_kelas' => "X-{$i}",
                'tingkat' => '10',
                'jurusan' => ($i <= 8) ? 'IPA' : 'IPS', // 1-8: IPA, 9-12: IPS
            ];
        }
        
        // Tingkat 11 (Kelas XI)
        for ($i = 1; $i <= 12; $i++) {
            $template[] = [
                'nama_kelas' => "XI-{$i}",
                'tingkat' => '11',
                'jurusan' => ($i <= 8) ? 'IPA' : 'IPS',
            ];
        }
        
        // Tingkat 12 (Kelas XII)
        for ($i = 1; $i <= 12; $i++) {
            $template[] = [
                'nama_kelas' => "XII-{$i}",
                'tingkat' => '12',
                'jurusan' => ($i <= 8) ? 'IPA' : 'IPS',
            ];
        }
        
        return $template; // Total: 36 kelas
    }
}
