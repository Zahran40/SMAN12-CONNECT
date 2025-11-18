<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Raport;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RaportController extends Controller
{
    /**
     * Halaman Nilai Raport - Pilih Mata Pelajaran
     */
    public function index()
    {
        $guru = Auth::user()->guru;
        
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
        
        // Auto-calculate nilai tugas dari stored procedure untuk semester Ganjil
        DB::statement('CALL sp_calculate_average_tugas(?, ?, ?, @avg)', [$siswaId, $jadwal->mapel_id, 'Ganjil']);
        $averageTugas = DB::select('SELECT @avg as average')[0]->average;
        
        return view('Guru.chartRaportSiswaS1', compact('jadwal', 'siswa', 'raport', 'tahunAjaran', 'tahunAjaranLabel', 'averageTugas'));
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
        
        // Auto-calculate nilai tugas dari stored procedure untuk semester Genap
        DB::statement('CALL sp_calculate_average_tugas(?, ?, ?, @avg)', [$siswaId, $jadwal->mapel_id, 'Genap']);
        $averageTugas = DB::select('SELECT @avg as average')[0]->average;
        
        return view('Guru.chartRaportSiswaS2', compact('jadwal', 'siswa', 'raport', 'tahunAjaran', 'tahunAjaranLabel', 'averageTugas'));
    }

    /**
     * Simpan/Update Nilai Raport
     */
    public function simpanNilai(Request $request, $jadwalId, $siswaId)
    {
        $request->validate([
            'nilai_uts' => 'nullable|integer|min:1|max:100',
            'nilai_uas' => 'nullable|integer|min:1|max:100',
            'deskripsi' => 'nullable|string|max:250',
            'semester' => 'required|in:Ganjil,Genap'
        ], [
            'nilai_uts.min' => 'Nilai UTS minimal adalah 1',
            'nilai_uts.max' => 'Nilai UTS maksimal adalah 100',
            'nilai_uas.min' => 'Nilai UAS minimal adalah 1',
            'nilai_uas.max' => 'Nilai UAS maksimal adalah 100',
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
                'nilai_uts' => $request->nilai_uts,
                'nilai_uas' => $request->nilai_uas,
                'deskripsi' => $request->deskripsi
            ]
        );
        
        // Auto-calculate nilai tugas dari stored procedure
        $raport->calculateNilaiTugas();
        
        // Hitung nilai akhir (akan auto-calculate nilai_huruf juga)
        $raport->hitungNilaiAkhir();
        $raport->save();
        
        return redirect()->back()->with('success', 'Nilai berhasil disimpan! Nilai tugas dihitung otomatis dari rata-rata semua tugas siswa.');
    }
}
