<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== KEMBALI KE TAHUN AJARAN 2024/2025 GENAP ===\n\n";

// Nonaktifkan semua
$all = \App\Models\TahunAjaran::all();
foreach ($all as $ta) {
    $ta->status = 'Tidak Aktif';
    $ta->save();
}

// Aktifkan 2024/2025 Genap
$genap = \App\Models\TahunAjaran::where('tahun_mulai', 2024)
    ->where('tahun_selesai', 2025)
    ->where('semester', 'Genap')
    ->first();

$genap->status = 'Aktif';
$genap->save();

echo "Tahun Ajaran Aktif: {$genap->tahun_mulai}/{$genap->tahun_selesai} {$genap->semester}\n";
echo "\nSekarang coba test semua halaman:\n";
echo "1. Admin > Tahun Ajaran (harus tampil 30 kelas)\n";
echo "2. Admin > Pendataan Kelas (harus tampil kelas untuk Ganjil dan Genap)\n";
echo "3. Guru > Materi (harus tampil 4 mata pelajaran)\n";
echo "4. Guru > Raport (harus tampil 4 mata pelajaran dengan 1 siswa)\n";
echo "5. Guru > Presensi (harus tampil 4 jadwal)\n";
