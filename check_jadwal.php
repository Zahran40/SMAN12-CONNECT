<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== CEK JADWAL DAN TAHUN AJARAN ===\n\n";

// Cek tahun ajaran aktif
$tahunAjaranAktif = \App\Models\TahunAjaran::where('status', 'Aktif')->first();

if ($tahunAjaranAktif) {
    echo "Tahun Ajaran Aktif:\n";
    echo "  ID: {$tahunAjaranAktif->id_tahun_ajaran}\n";
    echo "  Tahun: {$tahunAjaranAktif->tahun_mulai}/{$tahunAjaranAktif->tahun_selesai}\n";
    echo "  Semester: {$tahunAjaranAktif->semester}\n";
    echo "  Status: {$tahunAjaranAktif->status}\n\n";
    
    // Cek jadwal pelajaran untuk tahun ajaran ini
    $jadwalCount = \App\Models\JadwalPelajaran::where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)->count();
    echo "Jumlah Jadwal untuk TA Aktif (ID {$tahunAjaranAktif->id_tahun_ajaran}): {$jadwalCount}\n\n";
    
    // Jika semester Genap, cek apakah ada semester Ganjil
    if ($tahunAjaranAktif->semester === 'Genap') {
        $semesterGanjil = \App\Models\TahunAjaran::where('tahun_mulai', $tahunAjaranAktif->tahun_mulai)
            ->where('tahun_selesai', $tahunAjaranAktif->tahun_selesai)
            ->where('semester', 'Ganjil')
            ->first();
            
        if ($semesterGanjil) {
            echo "Semester Ganjil ditemukan:\n";
            echo "  ID: {$semesterGanjil->id_tahun_ajaran}\n";
            echo "  Status: {$semesterGanjil->status}\n";
            
            $jadwalGanjilCount = \App\Models\JadwalPelajaran::where('tahun_ajaran_id', $semesterGanjil->id_tahun_ajaran)->count();
            echo "  Jumlah Jadwal: {$jadwalGanjilCount}\n\n";
            
            echo "MASALAH: Semester Genap aktif tapi jadwal ada di semester Ganjil!\n";
            echo "SOLUSI: Jadwal harus di-query dari semester Ganjil juga.\n";
        } else {
            echo "WARNING: Tidak ada semester Ganjil untuk tahun ajaran ini!\n";
        }
    }
} else {
    echo "Tidak ada tahun ajaran yang aktif!\n";
}

echo "\n=== SEMUA JADWAL PELAJARAN ===\n";
$semuaJadwal = \App\Models\JadwalPelajaran::with('tahunAjaran')->get();
foreach ($semuaJadwal->groupBy('tahun_ajaran_id') as $taId => $jadwals) {
    $ta = $jadwals->first()->tahunAjaran;
    echo "TA ID {$taId} ({$ta->tahun_mulai}/{$ta->tahun_selesai} {$ta->semester}): " . $jadwals->count() . " jadwal\n";
}
