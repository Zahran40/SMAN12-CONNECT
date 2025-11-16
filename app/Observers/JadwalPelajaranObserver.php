<?php

namespace App\Observers;

use App\Models\JadwalPelajaran;
use App\Models\Pertemuan;

class JadwalPelajaranObserver
{
    /**
     * Handle the JadwalPelajaran "created" event.
     * Auto-generate 16 pertemuan kosong saat jadwal dibuat
     */
    public function created(JadwalPelajaran $jadwal): void
    {
        // Create 16 empty pertemuan slots
        for ($i = 1; $i <= 16; $i++) {
            Pertemuan::create([
                'jadwal_id' => $jadwal->id_jadwal, // Use primary key id_jadwal
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
    }

    /**
     * Handle the JadwalPelajaran "deleted" event.
     * Hapus semua pertemuan jika jadwal dihapus
     */
    public function deleted(JadwalPelajaran $jadwal): void
    {
        $jadwal->pertemuan()->delete();
    }
}
