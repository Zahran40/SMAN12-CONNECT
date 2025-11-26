<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== CEK DATA SISWA_KELAS ===\n\n";

// Cek siswa_kelas per tahun ajaran
$siswaKelasGrouped = DB::table('siswa_kelas')
    ->select('tahun_ajaran_id', DB::raw('count(*) as total'))
    ->where('status', 'Aktif')
    ->groupBy('tahun_ajaran_id')
    ->get();

foreach ($siswaKelasGrouped as $group) {
    $ta = \App\Models\TahunAjaran::find($group->tahun_ajaran_id);
    echo "Tahun Ajaran ID {$group->tahun_ajaran_id}";
    if ($ta) {
        echo " ({$ta->tahun_mulai}/{$ta->tahun_selesai} {$ta->semester})";
    }
    echo ": {$group->total} siswa\n";
}

echo "\n=== CEK KELAS YANG ADA SISWA ===\n";
$kelasWithSiswa = DB::table('siswa_kelas')
    ->join('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id_kelas')
    ->select('kelas.id_kelas', 'kelas.nama_kelas', 'siswa_kelas.tahun_ajaran_id', DB::raw('count(*) as total_siswa'))
    ->where('siswa_kelas.status', 'Aktif')
    ->groupBy('kelas.id_kelas', 'kelas.nama_kelas', 'siswa_kelas.tahun_ajaran_id')
    ->get();

foreach ($kelasWithSiswa as $kelas) {
    $ta = \App\Models\TahunAjaran::find($kelas->tahun_ajaran_id);
    echo "Kelas {$kelas->nama_kelas} (TA ID {$kelas->tahun_ajaran_id}";
    if ($ta) {
        echo " - {$ta->semester}";
    }
    echo "): {$kelas->total_siswa} siswa\n";
}
