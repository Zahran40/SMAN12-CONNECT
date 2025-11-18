<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Raport;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RaportController extends Controller
{
    /**
     * Halaman Nilai Raport - Pilih Mata Pelajaran
     */
    public function index()
    {
        $guru = auth()->user()->guru;
        
        // Ambil semua jadwal mengajar guru (unique per mapel dan kelas)
        $jadwalList = JadwalPelajaran::where('guru_id', $guru->id_guru)
            ->with(['mataPelajaran', 'kelas'])
            ->get()
            ->unique(function ($item) {
                return $item->id_mapel . '-' . $item->kelas_id;
            });
        
        return view('Guru.raport', compact('jadwalList'));
    }

    /**
     * Halaman Input Nilai - Daftar Siswa per Kelas & Mapel
     */
    public function inputNilai($jadwalId)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwalId);
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        $tahunAjaranLabel = $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai;
        $semesterAktif = $tahunAjaranAktif->semester; // 'Ganjil' atau 'Genap'
        
        // Ambil semua siswa di kelas ini dengan nilai raport
        $siswaList = Siswa::where('kelas_id', $jadwal->kelas_id)
            ->with(['user', 'nilai' => function($query) use ($jadwal, $tahunAjaran, $semesterAktif) {
                $query->where('mapel_id', $jadwal->mapel_id)
                      ->where('tahun_ajaran_id', $tahunAjaran)
                      ->where('semester', $semesterAktif);
            }])
            ->orderBy('nama_lengkap')
            ->get();
        
        return view('Guru.detailRaportSiswa', compact('jadwal', 'siswaList', 'tahunAjaran', 'tahunAjaranLabel', 'semesterAktif'));
    }

    /**
     * Halaman Detail Input Nilai Siswa
     */
    public function detailNilai($jadwalId, $siswaId)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwalId);
        $siswa = Siswa::with('user')->findOrFail($siswaId);
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        $tahunAjaranLabel = $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai;
        
        // Ambil nilai untuk semester Ganjil
        $raport = Raport::where('siswa_id', $siswaId)
            ->where('mapel_id', $jadwal->mapel_id)
            ->where('tahun_ajaran_id', $tahunAjaran)
            ->where('semester', 'Ganjil')
            ->first();
        
        return view('Guru.chartRaportSiswaS1', compact('jadwal', 'siswa', 'raport', 'tahunAjaran', 'tahunAjaranLabel'));
    }

    /**
     * Halaman Detail Input Nilai Siswa - Semester 2
     */
    public function detailNilaiS2($jadwalId, $siswaId)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwalId);
        $siswa = Siswa::with('user')->findOrFail($siswaId);
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        $tahunAjaranLabel = $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai;
        
        // Ambil nilai untuk semester Genap
        $raport = Raport::where('siswa_id', $siswaId)
            ->where('mapel_id', $jadwal->mapel_id)
            ->where('tahun_ajaran_id', $tahunAjaran)
            ->where('semester', 'Genap')
            ->first();
        
        return view('Guru.chartRaportSiswaS2', compact('jadwal', 'siswa', 'raport', 'tahunAjaran', 'tahunAjaranLabel'));
    }

    /**
     * Simpan/Update Nilai Raport
     */
    public function simpanNilai(Request $request, $jadwalId, $siswaId)
    {
        $request->validate([
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
            'deskripsi' => 'nullable|string|max:250',
            'semester' => 'required|in:Ganjil,Genap'
        ]);
        
        $jadwal = JadwalPelajaran::findOrFail($jadwalId);
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        
        // Cari atau buat raport baru
        $raport = Raport::updateOrCreate(
            [
                'siswa_id' => $siswaId,
                'mapel_id' => $jadwal->mapel_id,
                'tahun_ajaran_id' => $tahunAjaran,
                'semester' => $request->semester
            ],
            [
                'nilai_tugas' => $request->nilai_tugas,
                'nilai_uts' => $request->nilai_uts,
                'nilai_uas' => $request->nilai_uas,
                'deskripsi' => $request->deskripsi
            ]
        );
        
        // Hitung nilai akhir
        $raport->hitungNilaiAkhir();
        $raport->save();
        
        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Lihat Chart Perkembangan Siswa
     */
    public function chartPerkembangan($jadwalId, $siswaId)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwalId);
        $siswa = Siswa::with('user')->findOrFail($siswaId);
        
        $tahunAjaran = '2024/2025';
        
        // Ambil nilai siswa untuk mata pelajaran ini
        $raport = Raport::where('siswa_id', $siswaId)
            ->where('mapel_id', $jadwal->id_mapel)
            ->where('tahun_ajaran_id', $tahunAjaran)
            ->first();
        
        return view('Guru.chartPerkembangan', compact('jadwal', 'siswa', 'raport'));
    }
}
