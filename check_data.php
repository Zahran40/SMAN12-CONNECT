<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TAHUN AJARAN RECORDS ===\n";
$tahunAjarans = \App\Models\TahunAjaran::select('id_tahun_ajaran', 'tahun_mulai', 'tahun_selesai', 'semester', 'status', 'is_archived')
    ->orderBy('tahun_mulai', 'desc')
    ->orderBy('semester', 'asc')
    ->get();

foreach ($tahunAjarans as $ta) {
    echo sprintf(
        "ID: %d | %d/%d - %s | Status: %s | Archived: %s\n",
        $ta->id_tahun_ajaran,
        $ta->tahun_mulai,
        $ta->tahun_selesai,
        $ta->semester,
        $ta->status,
        $ta->is_archived ? 'Yes' : 'No'
    );
}

echo "\n=== KELAS COUNT PER TAHUN AJARAN ===\n";
foreach ($tahunAjarans as $ta) {
    $count = \App\Models\Kelas::where('tahun_ajaran_id', $ta->id_tahun_ajaran)->count();
    echo sprintf(
        "%d/%d %s: %d kelas\n",
        $ta->tahun_mulai,
        $ta->tahun_selesai,
        $ta->semester,
        $count
    );
}
