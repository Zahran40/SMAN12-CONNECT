<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n🎓 Seeding Nilai Raport (History)...\n";
        $siswa = DB::table('siswa')->first();
        if (!$siswa) {
            echo "⚠️ Tidak ada data siswa. Jalankan DatabaseSeeder terlebih dahulu.\n";
            return;
        }
        $mataPelajaran = DB::table('mata_pelajaran')->limit(5)->get();
        if ($mataPelajaran->isEmpty()) {
            echo "⚠️ Tidak ada data mata pelajaran. Jalankan DatabaseSeeder terlebih dahulu.\n";
            return;
        }
        $tahunAjaranList = DB::table('tahun_ajaran')->orderBy('tahun_mulai', 'desc')->get();
        if ($tahunAjaranList->count() < 2) {
            echo "⚠️ Minimal perlu 2 tahun ajaran untuk testing history.\n";
            return;
        }
        $nilaiCount = 0;
        foreach ($tahunAjaranList->take(3) as $index => $tahunAjaran) {
            foreach (['Ganjil', 'Genap'] as $semester) {
                foreach ($mataPelajaran as $mapel) {
                    $nilaiTugas = rand(70, 95);
                    $nilaiUts = rand(70, 95);
                    $nilaiUas = rand(75, 98);
                    $nilaiAkhir = ($nilaiTugas * 0.3) + ($nilaiUts * 0.3) + ($nilaiUas * 0.4);
                    if ($nilaiAkhir >= 90) {
                        $nilaiHuruf = 'A';
                        $deskripsi = 'Sangat Baik';
                    } elseif ($nilaiAkhir >= 80) {
                        $nilaiHuruf = 'B';
                        $deskripsi = 'Baik';
                    } elseif ($nilaiAkhir >= 70) {
                        $nilaiHuruf = 'C';
                        $deskripsi = 'Cukup';
                    } elseif ($nilaiAkhir >= 60) {
                        $nilaiHuruf = 'D';
                        $deskripsi = 'Kurang';
                    } else {
                        $nilaiHuruf = 'E';
                        $deskripsi = 'Sangat Kurang';
                    }
                    $exists = DB::table('nilai')
                        ->where('tahun_ajaran_id', $tahunAjaran->id_tahun_ajaran)
                        ->where('semester', $semester)
                        ->where('siswa_id', $siswa->id_siswa)
                        ->where('mapel_id', $mapel->id_mapel)
                        ->exists();
                    if (!$exists) {
                        DB::table('nilai')->insert([
                            'tahun_ajaran_id' => $tahunAjaran->id_tahun_ajaran,
                            'semester' => $semester,
                            'siswa_id' => $siswa->id_siswa,
                            'mapel_id' => $mapel->id_mapel,
                            'nilai_tugas' => $nilaiTugas,
                            'nilai_uts' => $nilaiUts,
                            'nilai_uas' => $nilaiUas,
                            'nilai_akhir' => round($nilaiAkhir, 2),
                            'nilai_huruf' => $nilaiHuruf,
                            'deskripsi' => $deskripsi,
                        ]);
                        $nilaiCount++;
                    }
                }
            }
        }
        echo "✅ Berhasil membuat {$nilaiCount} data nilai untuk siswa: {$siswa->nama_lengkap}\n";
        $summary = DB::table('nilai')
            ->join('tahun_ajaran', 'nilai.tahun_ajaran_id', '=', 'tahun_ajaran.id_tahun_ajaran')
            ->where('nilai.siswa_id', $siswa->id_siswa)
            ->select(
                'tahun_ajaran.tahun_mulai',
                'tahun_ajaran.tahun_selesai',
                'tahun_ajaran.semester',
                'tahun_ajaran.status',
                DB::raw('COUNT(*) as jumlah_mapel'),
                DB::raw('ROUND(AVG(nilai.nilai_akhir), 2) as rata_rata')
            )
            ->groupBy('tahun_ajaran.tahun_mulai', 'tahun_ajaran.tahun_selesai', 'tahun_ajaran.semester', 'tahun_ajaran.status')
            ->orderBy('tahun_ajaran.tahun_mulai', 'desc')
            ->get();
        echo "\n📊 Summary Nilai Raport:\n";
        echo "┌────────────────────────────────────────────────────────────────┐\n";
        echo "│ Tahun Ajaran      │ Status        │ Mapel │ Rata-rata      │\n";
        echo "├────────────────────────────────────────────────────────────────┤\n";
        foreach ($summary as $item) {
            $tahun = "{$item->tahun_mulai}/{$item->tahun_selesai} {$item->semester}";
            $status = $item->status == 'Aktif' ? '🟢 Aktif    ' : '⚪ History   ';
            printf("│ %-17s │ %-13s │ %5d │ %14.2f │\n", 
                $tahun, 
                $status, 
                $item->jumlah_mapel, 
                $item->rata_rata
            );
        }
        echo "└────────────────────────────────────────────────────────────────┘\n";
        echo "\n✨ Data nilai history berhasil dibuat!\n";
        echo "💡 Siswa dapat melihat nilai dari semua tahun ajaran (aktif & tidak aktif)\n\n";
    }
}