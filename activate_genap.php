<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "Mengubah status semester...\n\n";

$ganjil = \App\Models\TahunAjaran::find(1);
$ganjil->status = 'Tidak Aktif';
$ganjil->save();
echo "Semester Ganjil (ID 1): Tidak Aktif\n";

$genap = \App\Models\TahunAjaran::find(2);
$genap->status = 'Aktif';
$genap->save();
echo "Semester Genap (ID 2): Aktif\n";

echo "\nSekarang coba akses halaman raport dan materi lagi.\n";
