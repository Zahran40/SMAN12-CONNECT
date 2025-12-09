<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Raport;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  

class RaportController extends Controller
{
    /**
     * Halaman Raport Siswa
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        
        // Ambil semua tahun ajaran yang pernah diikuti siswa (dari tabel nilai)
        $tahunAjaranIds = DB::table('nilai')
            ->where('siswa_id', $siswa->id_siswa)
            ->select('tahun_ajaran_id')
            ->distinct()
            ->pluck('tahun_ajaran_id');
        
        // Jika tidak ada nilai, tampilkan tahun ajaran aktif saja
        if ($tahunAjaranIds->isEmpty()) {
            $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
            if ($tahunAjaranAktif) {
                $tahunAjaranIds = collect([$tahunAjaranAktif->id_tahun_ajaran]);
            }
        }
        
        $tahunAjaranSiswa = TahunAjaran::whereIn('id_tahun_ajaran', $tahunAjaranIds)
            ->orderBy('tahun_mulai', 'desc')
            ->orderBy('semester', 'asc')
            ->get();
        
        // Pilihan tahun ajaran dari request (untuk filter)
        $selectedTahunAjaranId = $request->query('tahun_ajaran_id');
        
        // Jika ada filter, tampilkan hanya tahun ajaran yang dipilih
        if ($selectedTahunAjaranId) {
            $tahunAjaranSiswa = $tahunAjaranSiswa->where('id_tahun_ajaran', $selectedTahunAjaranId);
        }
        
        // Ambil data kelas untuk setiap tahun ajaran dari siswa_kelas
        $tahunAjaranWithKelas = $tahunAjaranSiswa->map(function($ta) use ($siswa) {
            // Gunakan semester logic: Genap semester query pakai data Ganjil semester
            $tahunAjaranIdForQuery = $ta->id_tahun_ajaran;
            if ($ta->semester === 'Genap') {
                // Cari tahun ajaran Ganjil dengan tahun_mulai yang sama
                $tahunAjaranGanjil = TahunAjaran::where('tahun_mulai', $ta->tahun_mulai)
                    ->where('semester', 'Ganjil')
                    ->first();
                
                if ($tahunAjaranGanjil) {
                    $tahunAjaranIdForQuery = $tahunAjaranGanjil->id_tahun_ajaran;
                }
            }
            
            // Ambil kelas siswa untuk tahun ajaran ini dari tabel siswa_kelas
            $kelas = DB::table('siswa_kelas')
                ->join('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id_kelas')
                ->where('siswa_kelas.siswa_id', $siswa->id_siswa)
                ->where('siswa_kelas.tahun_ajaran_id', $tahunAjaranIdForQuery)
                ->where('siswa_kelas.status', 'Aktif')
                ->select('kelas.*')
                ->first();
            
            $ta->kelas = $kelas;
            return $ta;
        });
        
        // Untuk dropdown filter
        $allTahunAjaran = TahunAjaran::whereIn('id_tahun_ajaran', $tahunAjaranIds)
            ->orderBy('tahun_mulai', 'desc')
            ->orderBy('semester', 'asc')
            ->get();
        
        return view('siswa.nilai', compact('tahunAjaranWithKelas', 'allTahunAjaran', 'selectedTahunAjaranId'));
    }
    
    /**
     * Detail Nilai per Mata Pelajaran
     */
    public function detail($mapelId)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        $tahunAjaranLabel = $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai;
        
        $mataPelajaran = MataPelajaran::findOrFail($mapelId);
        
        $raport = Raport::where('siswa_id', $siswa->id_siswa)
            ->where('mapel_id', $mapelId)
            ->where('tahun_ajaran_id', $tahunAjaran)
            ->first();
        
        return view('siswa.detailRaport', compact('mataPelajaran', 'raport', 'tahunAjaranLabel'));
    }

    /**
     * Detail Semua Nilai Raport berdasarkan Semester
     */
    public function detailAll(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        $semester = $request->query('semester', 'Ganjil'); // Default Ganjil
        
        // Ambil tahun_ajaran_id dari request atau default ke yang aktif
        $tahunAjaranId = $request->query('tahun_ajaran_id');
        
        if (!$tahunAjaranId) {
            // Default ke tahun ajaran aktif
            $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
            $tahunAjaranId = $tahunAjaranAktif ? $tahunAjaranAktif->id_tahun_ajaran : null;
        }
        
        $selectedTahunAjaran = TahunAjaran::find($tahunAjaranId);
        $tahunAjaranLabel = $selectedTahunAjaran ? ($selectedTahunAjaran->tahun_mulai . '/' . $selectedTahunAjaran->tahun_selesai) : '-';
        
        // Ambil semua tahun ajaran yang pernah diikuti siswa
        $tahunAjaranIds = DB::table('nilai')
            ->where('siswa_id', $siswa->id_siswa)
            ->select('tahun_ajaran_id')
            ->distinct()
            ->pluck('tahun_ajaran_id');
        
        $tahunAjaranList = TahunAjaran::whereIn('id_tahun_ajaran', $tahunAjaranIds)
            ->orderBy('tahun_mulai', 'desc')
            ->orderBy('semester', 'asc')
            ->get();
        
        // Ambil nilai menggunakan Stored Procedure sp_rekap_nilai_siswa (alternatif dari view)
        try {
            $rekapNilai = DB::select('CALL sp_rekap_nilai_siswa(?, ?, ?)', [
                $siswa->id_siswa,
                $tahunAjaranId,
                $semester
            ]);
            
            // Convert hasil SP ke format yang sama dengan view
            $raports = collect($rekapNilai)->map(function($item) use ($siswa) {
                // Get grade dari function fn_convert_grade_letter
                $gradeResult = DB::select('SELECT fn_convert_grade_letter(?) as grade', [$item->nilai_akhir]);
                $grade = $gradeResult[0]->grade ?? '-';
                
                return (object)[
                    'nilai_akhir' => $item->nilai_akhir,
                    'nilai_huruf' => $grade,
                    'grade' => $grade,
                    'nilai_tugas' => $item->nilai_tugas,
                    'nilai_uts' => $item->nilai_uts,
                    'nilai_uas' => $item->nilai_uas,
                    'semester' => $item->semester,
                    'mataPelajaran' => (object)['nama_mapel' => $item->nama_mapel]
                ];
            });
        } catch (\Exception $e) {
            // Fallback ke view jika SP gagal
            $raports = DB::table('view_nilai_siswa')
                ->where('id_siswa', $siswa->id_siswa)
                ->where('id_tahun_ajaran', $tahunAjaranId)
                ->where('semester', $semester)
                ->select(
                    'id_nilai',
                    'nilai_tugas',
                    'nilai_uts',
                    'nilai_uas',
                    'nilai_akhir',
                    'grade',
                    'nama_mapel',
                    'id_mapel',
                    'semester'
                )
                ->get()
                ->map(function($item) {
                    return (object)[
                        'id_nilai' => $item->id_nilai,
                        'nilai_akhir' => $item->nilai_akhir,
                        'nilai_huruf' => $item->grade,
                        'grade' => $item->grade,
                        'nilai_tugas' => $item->nilai_tugas,
                        'nilai_uts' => $item->nilai_uts,
                        'nilai_uas' => $item->nilai_uas,
                        'semester' => $item->semester,
                        'mataPelajaran' => (object)['nama_mapel' => $item->nama_mapel, 'id_mapel' => $item->id_mapel]
                    ];
                });
        }
        
        // Hitung rata-rata semester menggunakan fn_rata_nilai (lebih efisien dari PHP avg)
        try {
            $result = DB::select('SELECT fn_rata_nilai(?, ?, ?) as rata', [
                $siswa->id_siswa,
                $tahunAjaranId,
                $semester
            ]);
            $rataRata = $result[0]->rata ?? 0;
        } catch (\Exception $e) {
            // Fallback ke PHP avg jika function gagal
            $rataRata = $raports->avg('nilai_akhir') ?? 0;
        }
        
        // Ambil data kelas siswa untuk tahun ajaran ini
        // Gunakan semester logic: Genap semester query pakai data Ganjil semester
        $kelasAjaranIdForQuery = $tahunAjaranId;
        if ($semester === 'Genap' && $selectedTahunAjaran) {
            // Cari tahun ajaran Ganjil dengan tahun_mulai yang sama
            $tahunAjaranGanjil = TahunAjaran::where('tahun_mulai', $selectedTahunAjaran->tahun_mulai)
                ->where('semester', 'Ganjil')
                ->first();
            
            if ($tahunAjaranGanjil) {
                $kelasAjaranIdForQuery = $tahunAjaranGanjil->id_tahun_ajaran;
            }
        }
        
        $kelasData = DB::table('siswa_kelas')
            ->join('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id_kelas')
            ->where('siswa_kelas.siswa_id', $siswa->id_siswa)
            ->where('siswa_kelas.tahun_ajaran_id', $kelasAjaranIdForQuery)
            ->where('siswa_kelas.status', 'Aktif')
            ->select('kelas.nama_kelas')
            ->first();
        
        $namaKelas = $kelasData->nama_kelas ?? null;
        
        return view('siswa.detailRaport', compact('raports', 'rataRata', 'tahunAjaranLabel', 'semester', 'tahunAjaranList', 'tahunAjaranId', 'namaKelas'));
    }
    
    /**
     * Cetak Raport (Print)
     */
    public function cetakRaport(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->firstOrFail();
        $semester = $request->query('semester', 'Ganjil'); // Default Ganjil
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        
        // Ambil semua nilai siswa untuk tahun ajaran dan semester ini
        $nilai = DB::table('nilai')
            ->join('mata_pelajaran', 'nilai.mapel_id', '=', 'mata_pelajaran.id_mapel')
            ->where('nilai.siswa_id', $siswa->id_siswa)
            ->where('nilai.tahun_ajaran_id', $tahunAjaran)
            ->where('nilai.semester', $semester)
            ->select(
                'mata_pelajaran.nama_mapel',
                'nilai.nilai_tugas',
                'nilai.nilai_uts',
                'nilai.nilai_uas',
                'nilai.nilai_akhir',
                'nilai.nilai_huruf',
                'nilai.deskripsi'
            )
            ->get();
        
        // Hitung rata-rata
        $rataRata = $nilai->avg('nilai_akhir') ?? 0;
        
        return view('siswa.cetak_raport', compact('siswa', 'nilai', 'rataRata', 'tahunAjaranAktif', 'semester'));
    }
}
