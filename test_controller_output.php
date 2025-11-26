<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Simulate the exact controller logic
$statusFilter = 'all';

$tahunAjaranRaw = \App\Models\TahunAjaran::where('is_archived', false)
    ->orderBy('tahun_mulai', 'desc')
    ->orderBy('semester', 'asc')
    ->get();

$tahunAjaranGrouped = $tahunAjaranRaw->groupBy(function($ta) {
    return $ta->tahun_mulai . '/' . $ta->tahun_selesai;
});

$tahunAjaran = [];
foreach ($tahunAjaranGrouped as $key => $group) {
    [$tahunMulai, $tahunSelesai] = explode('/', $key);
    
    $ganjil = $group->firstWhere('semester', 'Ganjil');
    $genap = $group->firstWhere('semester', 'Genap');
    
    $ganjilId = $ganjil ? $ganjil->id_tahun_ajaran : null;
    $jumlahKelas = $ganjilId ? \App\Models\Kelas::where('tahun_ajaran_id', $ganjilId)->count() : 0;
    
    $allSemesterIds = $group->pluck('id_tahun_ajaran');
    $jumlahSiswa = \App\Models\SiswaKelas::whereIn('tahun_ajaran_id', $allSemesterIds)
        ->where('status', 'Aktif')
        ->distinct('siswa_id')
        ->count('siswa_id');
    
    if ($statusFilter !== 'all') {
        if ($ganjil && $ganjil->status !== $statusFilter && $genap && $genap->status !== $statusFilter) {
            continue;
        }
    }
    
    $isAnyActive = ($ganjil && $ganjil->status === 'Aktif') || ($genap && $genap->status === 'Aktif');
    $canDelete = !$isAnyActive;

    $tahunAjaran[] = (object)[
        'tahun_mulai' => $tahunMulai,
        'tahun_selesai' => $tahunSelesai,
        'ganjil' => $ganjil,
        'genap' => $genap,
        'jumlah_kelas' => $jumlahKelas,
        'jumlah_siswa' => $jumlahSiswa,
        'jumlah_guru' => \App\Models\Guru::count(),
        'jumlah_mapel' => \App\Models\MataPelajaran::count(),
        'can_delete' => $canDelete,
    ];
}

echo "=== OUTPUT YANG AKAN DIKIRIM KE VIEW ===\n\n";
foreach ($tahunAjaran as $ta) {
    echo "Tahun Ajaran: {$ta->tahun_mulai}/{$ta->tahun_selesai}\n";
    echo "  - Ganjil: " . ($ta->ganjil ? "ID {$ta->ganjil->id_tahun_ajaran} ({$ta->ganjil->status})" : "NULL") . "\n";
    echo "  - Genap: " . ($ta->genap ? "ID {$ta->genap->id_tahun_ajaran} ({$ta->genap->status})" : "NULL") . "\n";
    echo "  - Jumlah Kelas: {$ta->jumlah_kelas}\n";
    echo "  - Jumlah Siswa: {$ta->jumlah_siswa}\n";
    echo "  - Can Delete: " . ($ta->can_delete ? 'Yes' : 'No') . "\n";
    echo "\n";
}
