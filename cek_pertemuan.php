<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK DATA PERTEMUAN ===\n\n";

// Ambil semua jadwal
$jadwals = DB::table('jadwal_pelajaran')
    ->join('mata_pelajaran', 'jadwal_pelajaran.mata_pelajaran_id', '=', 'mata_pelajaran.id_mata_pelajaran')
    ->join('kelas', 'jadwal_pelajaran.kelas_id', '=', 'kelas.id_kelas')
    ->select('jadwal_pelajaran.*', 'mata_pelajaran.nama_mata_pelajaran', 'kelas.nama_kelas')
    ->get();

echo "Total Jadwal: " . $jadwals->count() . "\n\n";

foreach ($jadwals as $jadwal) {
    echo "┌─────────────────────────────────────────\n";
    echo "│ Jadwal ID: {$jadwal->jadwal_id}\n";
    echo "│ Mapel: {$jadwal->nama_mata_pelajaran}\n";
    echo "│ Kelas: {$jadwal->nama_kelas}\n";
    echo "│ Hari: {$jadwal->hari}\n";
    echo "└─────────────────────────────────────────\n";
    
    // Cek pertemuan untuk jadwal ini
    $pertemuans = DB::table('pertemuan')
        ->where('jadwal_id', $jadwal->jadwal_id)
        ->orderBy('nomor_pertemuan')
        ->get();
    
    if ($pertemuans->count() > 0) {
        echo "  📚 Pertemuan Terisi:\n";
        foreach ($pertemuans as $p) {
            echo "     • Pertemuan {$p->nomor_pertemuan}: {$p->tanggal_pertemuan} | {$p->jam_absen_buka}-{$p->jam_absen_tutup}\n";
            if ($p->topik_bahasan) {
                echo "       Materi: {$p->topik_bahasan}\n";
            }
        }
    } else {
        echo "  ✅ Belum ada pertemuan (16 slot kosong)\n";
    }
    echo "\n";
}

echo "\n=== SLOT TERSEDIA ===\n";
echo "Setiap jadwal memiliki 16 slot pertemuan (1-16)\n";
echo "Guru dapat memilih slot mana yang ingin diisi\n\n";

// Cek jadwal pertama sebagai contoh
if ($jadwals->count() > 0) {
    $firstJadwal = $jadwals->first();
    $terisi = DB::table('pertemuan')
        ->where('jadwal_id', $firstJadwal->jadwal_id)
        ->pluck('nomor_pertemuan')
        ->toArray();
    
    echo "Contoh untuk Jadwal ID {$firstJadwal->jadwal_id}:\n";
    echo "Slot Terisi: " . (count($terisi) > 0 ? implode(', ', $terisi) : 'Tidak ada') . "\n";
    echo "Slot Kosong: ";
    
    $kosong = [];
    for ($i = 1; $i <= 16; $i++) {
        if (!in_array($i, $terisi)) {
            $kosong[] = $i;
        }
    }
    echo count($kosong) > 0 ? implode(', ', $kosong) : 'Semua terisi';
    echo "\n";
}

echo "\n✅ SELESAI\n";
