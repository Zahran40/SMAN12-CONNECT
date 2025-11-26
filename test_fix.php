<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== TEST SETELAH FIX ===\n\n";

$guru = \App\Models\Guru::find(1);
$jadwalList = \App\Models\JadwalPelajaran::where('guru_id', $guru->id_guru)
    ->where('tahun_ajaran_id', 1)
    ->with(['mataPelajaran', 'kelas'])
    ->get()
    ->unique(function ($item) {
        return $item->mapel_id . '-' . $item->kelas_id;
    });

echo "Total jadwal setelah unique: {$jadwalList->count()}\n\n";
foreach ($jadwalList as $j) {
    echo "- {$j->kelas->nama_kelas} - {$j->mataPelajaran->nama_mapel}\n";
}
