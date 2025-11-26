<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING CONTROLLER LOGIC ===\n\n";

// Simulate controller logic
$tahunAjaranRaw = \App\Models\TahunAjaran::where('is_archived', false)
    ->orderBy('tahun_mulai', 'desc')
    ->orderBy('semester', 'asc')
    ->get();

echo "Raw tahun ajaran (not archived):\n";
foreach ($tahunAjaranRaw as $ta) {
    echo sprintf("  - %d/%d %s (ID: %d)\n", $ta->tahun_mulai, $ta->tahun_selesai, $ta->semester, $ta->id_tahun_ajaran);
}

// Group by tahun ajaran
$tahunAjaranGrouped = $tahunAjaranRaw->groupBy(function($ta) {
    return $ta->tahun_mulai . '/' . $ta->tahun_selesai;
});

echo "\n=== GROUPED DATA ===\n";
foreach ($tahunAjaranGrouped as $key => $group) {
    echo "\nTahun Ajaran: $key\n";
    
    [$tahunMulai, $tahunSelesai] = explode('/', $key);
    
    $ganjil = $group->firstWhere('semester', 'Ganjil');
    $genap = $group->firstWhere('semester', 'Genap');
    
    echo "  Ganjil: " . ($ganjil ? "ID {$ganjil->id_tahun_ajaran}" : "NULL") . "\n";
    echo "  Genap: " . ($genap ? "ID {$genap->id_tahun_ajaran}" : "NULL") . "\n";
    
    // Count kelas
    $ganjilId = $ganjil ? $ganjil->id_tahun_ajaran : null;
    $jumlahKelas = $ganjilId ? \App\Models\Kelas::where('tahun_ajaran_id', $ganjilId)->count() : 0;
    
    echo "  Ganjil ID used for counting: " . ($ganjilId ?? 'NULL') . "\n";
    echo "  Jumlah Kelas: $jumlahKelas\n";
    
    if ($ganjilId) {
        $kelasQuery = \App\Models\Kelas::where('tahun_ajaran_id', $ganjilId);
        echo "  SQL Query: " . $kelasQuery->toSql() . "\n";
        echo "  Bindings: " . json_encode($kelasQuery->getBindings()) . "\n";
    }
}
