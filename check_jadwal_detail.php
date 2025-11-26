<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== SEMUA JADWAL PELAJARAN ===\n\n";

$jadwalList = \App\Models\JadwalPelajaran::with(['mataPelajaran', 'kelas', 'guru', 'tahunAjaran'])
    ->orderBy('kelas_id')
    ->orderBy('mapel_id')
    ->get();

echo "Total jadwal: " . $jadwalList->count() . "\n\n";

foreach ($jadwalList as $jadwal) {
    echo sprintf(
        "ID: %d | %s - %s | Guru: %s | TA: %d (%s) | Hari: %s | Jam: %s\n",
        $jadwal->id_jadwal,
        $jadwal->kelas->nama_kelas,
        $jadwal->mataPelajaran->nama_mapel,
        $jadwal->guru->nama_lengkap,
        $jadwal->tahun_ajaran_id,
        $jadwal->tahunAjaran->semester,
        $jadwal->hari,
        $jadwal->jam_mulai
    );
}

echo "\n=== TEST UNIQUE ===\n";
$unique = $jadwalList->unique(function ($item) {
    return $item->mapel_id . '-' . $item->kelas_id;
});

echo "Setelah unique: " . $unique->count() . " jadwal\n\n";
foreach ($unique as $jadwal) {
    echo sprintf(
        "%s - %s | Key: %s-%s\n",
        $jadwal->kelas->nama_kelas,
        $jadwal->mataPelajaran->nama_mapel,
        $jadwal->mapel_id,
        $jadwal->kelas_id
    );
}
