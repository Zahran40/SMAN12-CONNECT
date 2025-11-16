<?php

/**
 * Script untuk set waktu absensi ke pertemuan yang sudah ada
 * Gunakan: php set_waktu_absensi.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "=== SET WAKTU ABSENSI PERTEMUAN ===\n\n";

// Ambil pertemuan hari ini
$today = Carbon::now()->toDateString();
$hari = Carbon::now()->locale('id')->dayName;

// Karena hari ini Minggu, gunakan Senin untuk testing
if ($hari === 'Minggu') {
    $hari = 'Senin';
    echo "Hari ini Minggu (tidak ada jadwal), menggunakan hari Senin untuk testing\n";
}

echo "Hari: $hari, Tanggal: $today\n\n";

// Cari jadwal hari ini
$jadwalList = DB::table('jadwal_pelajaran')
    ->where('hari', $hari)
    ->get();

if ($jadwalList->isEmpty()) {
    die("Tidak ada jadwal untuk hari $hari\n");
}

echo "Ditemukan " . $jadwalList->count() . " jadwal untuk hari $hari\n\n";

foreach ($jadwalList as $jadwal) {
    // Cari atau buat pertemuan untuk hari ini
    $pertemuan = DB::table('pertemuan')
        ->where('jadwal_id', $jadwal->id_jadwal)
        ->whereDate('tanggal_pertemuan', $today)
        ->first();
    
    if (!$pertemuan) {
        // Hitung nomor pertemuan berikutnya
        $nomorPertemuan = DB::table('pertemuan')
            ->where('jadwal_id', $jadwal->id_jadwal)
            ->max('nomor_pertemuan') + 1;
        
        // Buat pertemuan baru
        $pertemuanId = DB::table('pertemuan')->insertGetId([
            'jadwal_id' => $jadwal->id_jadwal,
            'nomor_pertemuan' => $nomorPertemuan,
            'tanggal_pertemuan' => $today,
            'waktu_mulai' => $jadwal->jam_mulai,
            'waktu_selesai' => $jadwal->jam_selesai,
            'topik_bahasan' => 'Pertemuan ' . $nomorPertemuan,
            'status_sesi' => 'Buka',
            'created_at' => Carbon::now(),
        ]);
        
        $pertemuan = DB::table('pertemuan')->where('id_pertemuan', $pertemuanId)->first();
        echo "✓ Pertemuan baru dibuat: ID $pertemuanId\n";
    } else {
        echo "✓ Pertemuan sudah ada: ID {$pertemuan->id_pertemuan}\n";
    }
    
    // Set waktu absensi (misal: 30 menit sebelum kelas sampai 15 menit setelah kelas mulai)
    $waktuMulai = Carbon::parse($today . ' ' . $jadwal->jam_mulai);
    $waktuSelesai = Carbon::parse($today . ' ' . $jadwal->jam_selesai);
    
    $tanggalAbsenDibuka = $today;
    $tanggalAbsenDitutup = $today;
    $jamAbsenBuka = $waktuMulai->copy()->subMinutes(30)->format('H:i:s');
    $jamAbsenTutup = $waktuMulai->copy()->addMinutes(15)->format('H:i:s');
    
    $waktuAbsenDibuka = $tanggalAbsenDibuka . ' ' . $jamAbsenBuka;
    $waktuAbsenDitutup = $tanggalAbsenDitutup . ' ' . $jamAbsenTutup;
    
    // Update pertemuan
    DB::table('pertemuan')
        ->where('id_pertemuan', $pertemuan->id_pertemuan)
        ->update([
            'tanggal_absen_dibuka' => $tanggalAbsenDibuka,
            'tanggal_absen_ditutup' => $tanggalAbsenDitutup,
            'jam_absen_buka' => $jamAbsenBuka,
            'jam_absen_tutup' => $jamAbsenTutup,
            'waktu_absen_dibuka' => $waktuAbsenDibuka,
            'waktu_absen_ditutup' => $waktuAbsenDitutup,
        ]);
    
    // Ambil info jadwal
    $mapel = DB::table('mata_pelajaran')->where('id_mapel', $jadwal->mapel_id)->first();
    $kelas = DB::table('kelas')->where('id_kelas', $jadwal->kelas_id)->first();
    
    echo "  Mapel: {$mapel->nama_mapel} - {$kelas->nama_kelas}\n";
    echo "  Waktu Absensi: $jamAbsenBuka - $jamAbsenTutup\n";
    echo "  Status: ";
    
    $now = Carbon::now();
    $buka = Carbon::parse($waktuAbsenDibuka);
    $tutup = Carbon::parse($waktuAbsenDitutup);
    
    if ($now->lessThan($buka)) {
        echo "BELUM DIBUKA (buka pada " . $buka->format('H:i') . ")\n";
    } elseif ($now->greaterThan($tutup)) {
        echo "SUDAH DITUTUP (tutup pada " . $tutup->format('H:i') . ")\n";
    } else {
        echo "SEDANG TERBUKA ✓\n";
    }
    
    echo "\n";
}

echo "\n=== SELESAI ===\n";
echo "Waktu sekarang: " . Carbon::now()->format('H:i:s') . "\n";
echo "Silakan cek di browser!\n";
