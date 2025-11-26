<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== SKENARIO: BUAT TAHUN AJARAN BARU DAN AKTIFKAN ===\n\n";

// Step 1: Nonaktifkan tahun ajaran yang sekarang aktif
echo "Step 1: Nonaktifkan tahun ajaran lama...\n";
$oldActive = \App\Models\TahunAjaran::where('status', 'Aktif')->get();
foreach ($oldActive as $ta) {
    $ta->status = 'Tidak Aktif';
    $ta->save();
    echo "  - {$ta->tahun_mulai}/{$ta->tahun_selesai} {$ta->semester}: Tidak Aktif\n";
}

// Step 2: Buat tahun ajaran baru 2025/2026
echo "\nStep 2: Buat tahun ajaran baru 2025/2026...\n";

// Cek apakah sudah ada
$existingGanjil = \App\Models\TahunAjaran::where('tahun_mulai', 2025)
    ->where('tahun_selesai', 2026)
    ->where('semester', 'Ganjil')
    ->first();

if (!$existingGanjil) {
    $taGanjil = \App\Models\TahunAjaran::create([
        'tahun_mulai' => 2025,
        'tahun_selesai' => 2026,
        'semester' => 'Ganjil',
        'status' => 'Aktif',
        'is_archived' => false
    ]);
    echo "  - Semester Ganjil dibuat (ID: {$taGanjil->id_tahun_ajaran})\n";
    echo "  - Observer akan auto-create 30 kelas\n";
} else {
    $existingGanjil->status = 'Aktif';
    $existingGanjil->save();
    echo "  - Semester Ganjil sudah ada (ID: {$existingGanjil->id_tahun_ajaran}), diaktifkan\n";
    $taGanjil = $existingGanjil;
}

$existingGenap = \App\Models\TahunAjaran::where('tahun_mulai', 2025)
    ->where('tahun_selesai', 2026)
    ->where('semester', 'Genap')
    ->first();

if (!$existingGenap) {
    $taGenap = \App\Models\TahunAjaran::create([
        'tahun_mulai' => 2025,
        'tahun_selesai' => 2026,
        'semester' => 'Genap',
        'status' => 'Tidak Aktif',
        'is_archived' => false
    ]);
    echo "  - Semester Genap dibuat (ID: {$taGenap->id_tahun_ajaran})\n";
} else {
    echo "  - Semester Genap sudah ada (ID: {$existingGenap->id_tahun_ajaran})\n";
    $taGenap = $existingGenap;
}

// Step 3: Cek apakah kelas sudah dibuat oleh Observer
echo "\nStep 3: Cek kelas yang dibuat...\n";
$kelasCount = \App\Models\Kelas::where('tahun_ajaran_id', $taGanjil->id_tahun_ajaran)->count();
echo "  - Jumlah kelas di TA Ganjil: {$kelasCount}\n";

if ($kelasCount == 0) {
    echo "  - PERHATIAN: Kelas belum ada, Observer mungkin tidak berjalan!\n";
    echo "  - Gunakan route generate atau buat manual\n";
}

// Step 4: Simulasi copy siswa ke tahun ajaran baru
echo "\nStep 4: Copy siswa ke tahun ajaran baru...\n";
$siswaLama = \App\Models\SiswaKelas::where('tahun_ajaran_id', 1)
    ->where('status', 'Aktif')
    ->get();

echo "  - Siswa di TA lama: {$siswaLama->count()}\n";

foreach ($siswaLama as $sk) {
    // Cek apakah sudah ada di TA baru
    $exists = \App\Models\SiswaKelas::where('siswa_id', $sk->siswa_id)
        ->where('kelas_id', $sk->kelas_id)
        ->where('tahun_ajaran_id', $taGanjil->id_tahun_ajaran)
        ->exists();
    
    if (!$exists) {
        \App\Models\SiswaKelas::create([
            'siswa_id' => $sk->siswa_id,
            'kelas_id' => $sk->kelas_id,
            'tahun_ajaran_id' => $taGanjil->id_tahun_ajaran,
            'status' => 'Aktif'
        ]);
        $siswa = \App\Models\Siswa::find($sk->siswa_id);
        $kelas = \App\Models\Kelas::find($sk->kelas_id);
        echo "  - Copy: {$siswa->nama_lengkap} ke {$kelas->nama_kelas}\n";
    } else {
        echo "  - Sudah ada, skip\n";
    }
}

// Step 5: Copy jadwal ke tahun ajaran baru
echo "\nStep 5: Copy jadwal ke tahun ajaran baru...\n";
$jadwalLama = \App\Models\JadwalPelajaran::where('tahun_ajaran_id', 1)->get();
echo "  - Jadwal di TA lama: {$jadwalLama->count()}\n";

foreach ($jadwalLama as $jl) {
    $exists = \App\Models\JadwalPelajaran::where('guru_id', $jl->guru_id)
        ->where('kelas_id', $jl->kelas_id)
        ->where('mapel_id', $jl->mapel_id)
        ->where('tahun_ajaran_id', $taGanjil->id_tahun_ajaran)
        ->where('hari', $jl->hari)
        ->where('jam_mulai', $jl->jam_mulai)
        ->exists();
    
    if (!$exists) {
        \App\Models\JadwalPelajaran::create([
            'guru_id' => $jl->guru_id,
            'kelas_id' => $jl->kelas_id,
            'mapel_id' => $jl->mapel_id,
            'tahun_ajaran_id' => $taGanjil->id_tahun_ajaran,
            'hari' => $jl->hari,
            'jam_mulai' => $jl->jam_mulai,
            'jam_selesai' => $jl->jam_selesai
        ]);
        echo "  - Copy: {$jl->mataPelajaran->nama_mapel} - {$jl->kelas->nama_kelas}\n";
    } else {
        echo "  - Sudah ada, skip\n";
    }
}

echo "\n=== HASIL AKHIR ===\n";
$taAktif = \App\Models\TahunAjaran::where('status', 'Aktif')->first();
echo "TA Aktif: {$taAktif->tahun_mulai}/{$taAktif->tahun_selesai} {$taAktif->semester} (ID: {$taAktif->id_tahun_ajaran})\n";

$kelasCount = \App\Models\Kelas::where('tahun_ajaran_id', $taAktif->id_tahun_ajaran)->count();
echo "Jumlah Kelas: {$kelasCount}\n";

$siswaCount = \App\Models\SiswaKelas::where('tahun_ajaran_id', $taAktif->id_tahun_ajaran)
    ->where('status', 'Aktif')
    ->count();
echo "Jumlah Siswa: {$siswaCount}\n";

$jadwalCount = \App\Models\JadwalPelajaran::where('tahun_ajaran_id', $taAktif->id_tahun_ajaran)->count();
echo "Jumlah Jadwal: {$jadwalCount}\n";

echo "\nSekarang coba akses:\n";
echo "- Halaman Admin > Tahun Ajaran\n";
echo "- Halaman Guru > Materi/Raport/Presensi\n";
echo "Semua data harus muncul!\n";
