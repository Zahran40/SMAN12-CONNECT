<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Raport;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
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
        
        // Ambil semua raport siswa untuk tahun ajaran ini
        $raports = Raport::where('siswa_id', $siswa->id_siswa)
            ->where('tahun_ajaran_id', $tahunAjaran)
            ->with(['mataPelajaran', 'tahunAjaran'])
            ->get();
        
        // Hitung rata-rata
        $rataRata = $raports->avg('nilai_akhir') ?? 0;
        
        return view('Siswa.raport', compact('raports', 'rataRata', 'tahunAjaranLabel'));
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
}
