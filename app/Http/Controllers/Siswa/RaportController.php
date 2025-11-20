<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Raport;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  

class RaportController extends Controller
{
    /**
     * Halaman Raport Siswa
     */
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        $tahunAjaranLabel = $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai;
        
        // Ambil data kelas siswa
        $kelas = $siswa->kelas;
        
        return view('Siswa.nilai', compact('tahunAjaranLabel', 'kelas'));
    }
    
    /**
     * Detail Nilai per Mata Pelajaran
     */
    public function detail($mapelId)
    {
        $siswa = auth()->user()->siswa;
        
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
        $siswa = auth()->user()->siswa;
        $semester = $request->query('semester', 'Ganjil'); // Default Ganjil
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        $tahunAjaranLabel = $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai;
        
        // Ambil semua raport siswa untuk tahun ajaran dan semester ini
        $raports = Raport::where('siswa_id', $siswa->id_siswa)
            ->where('tahun_ajaran_id', $tahunAjaran)
            ->where('semester', $semester)
            ->with(['mataPelajaran', 'tahunAjaran'])
            ->get();
        
        // Hitung rata-rata
        $rataRata = $raports->avg('nilai_akhir') ?? 0;
        
        return view('Siswa.detailRaport', compact('raports', 'rataRata', 'tahunAjaranLabel', 'semester'));
    }
}
