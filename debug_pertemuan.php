<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG PERTEMUAN ===\n\n";

$pertemuans = DB::table('pertemuan')
    ->join('jadwal_pelajaran', 'pertemuan.jadwal_id', '=', 'jadwal_pelajaran.jadwal_id')
    ->select('pertemuan.*', 'jadwal_pelajaran.hari')
    ->get();

echo "Total Pertemuan di Database: " . $pertemuans->count() . "\n\n";

if ($pertemuans->count() > 0) {
    foreach ($pertemuans as $p) {
        echo "ID: {$p->id_pertemuan} | Jadwal: {$p->jadwal_id} | Pertemuan ke-{$p->nomor_pertemuan} | Tanggal: {$p->tanggal_pertemuan}\n";
    }
    
    echo "\n⚠️ ADA DATA PERTEMUAN! Ini yang bikin error 'sudah terisi'\n";
    echo "Solusi: Hapus semua pertemuan atau pilih nomor yang belum terisi\n\n";
    
    echo "Untuk hapus semua pertemuan, jalankan:\n";
    echo "php artisan tinker\n";
    echo "DB::table('pertemuan')->truncate();\n";
} else {
    echo "✅ Database pertemuan KOSONG - siap untuk diisi\n";
}
