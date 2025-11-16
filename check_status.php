<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$t = DB::table('tugas')->where('id_tugas', 2)->first();
$now = now();
$buka = \Carbon\Carbon::parse($t->waktu_dibuka);
$tutup = \Carbon\Carbon::parse($t->waktu_ditutup);

echo "=== STATUS TUGAS ===\n";
echo "Tugas: {$t->judul_tugas}\n\n";
echo "Waktu Sekarang (WIB): " . $now->format('Y-m-d H:i:s') . "\n";
echo "Waktu Dibuka:         " . $buka->format('Y-m-d H:i:s') . "\n";
echo "Waktu Ditutup:        " . $tutup->format('Y-m-d H:i:s') . "\n\n";

$isOpen = $now->gte($buka) && $now->lte($tutup);
echo "Now >= Buka? " . ($now->gte($buka) ? 'YES ✅' : 'NO ❌') . "\n";
echo "Now <= Tutup? " . ($now->lte($tutup) ? 'YES ✅' : 'NO ❌') . "\n";
echo "\nSTATUS: " . ($isOpen ? '🟢 TERBUKA - Siswa bisa upload!' : '🔴 TIDAK TERBUKA') . "\n";
