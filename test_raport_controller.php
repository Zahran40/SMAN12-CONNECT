<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== SIMULASI RAPORT CONTROLLER ===\n\n";

// Simulate guru login (ID 1 - Siti Nurhaliza)
$guru = \App\Models\Guru::find(1);
echo "Guru: {$guru->nama_lengkap}\n\n";

// Ambil tahun ajaran aktif
$tahunAjaranAktif = \App\Models\TahunAjaran::where('status', 'Aktif')->first();
echo "TA Aktif: {$tahunAjaranAktif->tahun_mulai}/{$tahunAjaranAktif->tahun_selesai} {$tahunAjaranAktif->semester} (ID: {$tahunAjaranAktif->id_tahun_ajaran})\n\n";

// Tentukan tahun ajaran untuk query
$tahunAjaranForQuery = $tahunAjaranAktif->id_tahun_ajaran;

if ($tahunAjaranAktif->semester === 'Genap') {
    $semesterGanjil = \App\Models\TahunAjaran::where('tahun_mulai', $tahunAjaranAktif->tahun_mulai)
        ->where('tahun_selesai', $tahunAjaranAktif->tahun_selesai)
        ->where('semester', 'Ganjil')
        ->first();
    
    if ($semesterGanjil) {
        $tahunAjaranForQuery = $semesterGanjil->id_tahun_ajaran;
        echo "Semester Genap aktif, query dari Ganjil ID: {$tahunAjaranForQuery}\n\n";
    }
}

// Query jadwal
$jadwalList = \App\Models\JadwalPelajaran::where('guru_id', $guru->id_guru)
    ->where('tahun_ajaran_id', $tahunAjaranForQuery)
    ->with(['mataPelajaran', 'kelas'])
    ->get();

echo "Jadwal sebelum unique: {$jadwalList->count()}\n";
foreach ($jadwalList as $j) {
    echo "  - {$j->kelas->nama_kelas} - {$j->mataPelajaran->nama_mapel} (ID: {$j->id_jadwal}, mapel_id: {$j->mapel_id}, kelas_id: {$j->kelas_id})\n";
}

$jadwalList = $jadwalList->unique(function ($item) {
    return $item->id_mapel . '-' . $item->kelas_id;
});

echo "\nJadwal setelah unique: {$jadwalList->count()}\n";
foreach ($jadwalList as $j) {
    echo "  - {$j->kelas->nama_kelas} - {$j->mataPelajaran->nama_mapel}\n";
}
