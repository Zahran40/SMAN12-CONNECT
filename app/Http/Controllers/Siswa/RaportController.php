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
            // Ambil kelas siswa untuk tahun ajaran ini dari tabel siswa_kelas
            // Ini akan mengambil kelas history sesuai tahun ajaran yang pernah diikuti
            $kelas = DB::table('siswa_kelas')
                ->join('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id_kelas')
                ->where('siswa_kelas.siswa_id', $siswa->id_siswa)
                ->where('siswa_kelas.tahun_ajaran_id', $ta->id_tahun_ajaran)
                ->select('kelas.*')
                ->first();
            
            // Jika tidak ada di siswa_kelas, coba ambil dari kelas langsung
            if (!$kelas) {
                $kelas = DB::table('kelas')
                    ->where('tahun_ajaran_id', $ta->id_tahun_ajaran)
                    ->whereExists(function($query) use ($siswa, $ta) {
                        $query->select(DB::raw(1))
                              ->from('siswa_kelas')
                              ->whereColumn('siswa_kelas.kelas_id', 'kelas.id_kelas')
                              ->where('siswa_kelas.siswa_id', $siswa->id_siswa);
                    })
                    ->first();
            }
            
            $ta->kelas = $kelas;
            return $ta;
        });
        
        // Untuk dropdown filter
        $allTahunAjaran = TahunAjaran::whereIn('id_tahun_ajaran', $tahunAjaranIds)
            ->orderBy('tahun_mulai', 'desc')
            ->orderBy('semester', 'asc')
            ->get();
        
        return view('Siswa.nilai', compact('tahunAjaranWithKelas', 'allTahunAjaran', 'selectedTahunAjaranId'));
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
        
        return view('Siswa.detailRaport', compact('mataPelajaran', 'raport', 'tahunAjaranLabel'));
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
        
        // Ambil semua nilai siswa untuk tahun ajaran dan semester ini
        $raports = DB::table('nilai')
            ->join('mata_pelajaran', 'nilai.mapel_id', '=', 'mata_pelajaran.id_mapel')
            ->join('tahun_ajaran', 'nilai.tahun_ajaran_id', '=', 'tahun_ajaran.id_tahun_ajaran')
            ->where('nilai.siswa_id', $siswa->id_siswa)
            ->where('nilai.tahun_ajaran_id', $tahunAjaranId)
            ->where('nilai.semester', $semester)
            ->select(
                'nilai.*',
                'mata_pelajaran.nama_mapel',
                'mata_pelajaran.id_mapel',
                'tahun_ajaran.tahun_mulai',
                'tahun_ajaran.tahun_selesai'
            )
            ->get()
            ->map(function($item) {
                return (object)[
                    'id_nilai' => $item->id_nilai,
                    'nilai_akhir' => $item->nilai_akhir,
                    'nilai_huruf' => $item->nilai_huruf,
                    'grade' => $item->nilai_huruf,
                    'mataPelajaran' => (object)['nama_mapel' => $item->nama_mapel, 'id_mapel' => $item->id_mapel]
                ];
            });
        
        // Hitung rata-rata
        $rataRata = $raports->avg('nilai_akhir') ?? 0;
        
        return view('Siswa.detailRaport', compact('raports', 'rataRata', 'tahunAjaranLabel', 'semester', 'tahunAjaranList', 'tahunAjaranId'));
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
