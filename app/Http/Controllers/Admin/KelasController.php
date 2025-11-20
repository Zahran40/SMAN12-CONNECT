<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Halaman pendataan semua kelas (tidak terikat tahun ajaran)
     */
    public function all(Request $request)
    {
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        
        // Filter berdasarkan tahun ajaran yang dipilih
        $selectedTahunAjaran = $request->get('tahun_ajaran_id');
        
        if ($selectedTahunAjaran) {
            $kelasList = Kelas::with(['waliKelas', 'siswa', 'tahunAjaran'])
                ->where('tahun_ajaran_id', $selectedTahunAjaran)
                ->orderBy('tingkat')
                ->orderBy('nama_kelas')
                ->get();
        } else {
            $kelasList = Kelas::with(['waliKelas', 'siswa', 'tahunAjaran'])
                ->orderBy('tahun_ajaran_id', 'desc')
                ->orderBy('tingkat')
                ->orderBy('nama_kelas')
                ->get();
        }
        
        return view('Admin.pendataanKelas', compact('kelasList', 'tahunAjaranList', 'selectedTahunAjaran'));
    }

    /**
     * Halaman kelola kelas untuk tahun ajaran tertentu
     */
    public function index($tahunAjaranId)
    {
        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
            ->with(['waliKelas', 'siswa'])
            ->get();
        
        return view('Admin.kelolaKelas', compact('tahunAjaran', 'kelasList'));
    }

    /**
     * Form tambah kelas
     */
    public function create($tahunAjaranId)
    {
        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);
        $guruList = Guru::all();
        
        return view('Admin.buatKelas', compact('tahunAjaran', 'guruList'));
    }

    /**
     * Simpan kelas baru
     */
    public function store(Request $request, $tahunAjaranId)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|integer|min:10|max:12',
            'jurusan' => 'nullable|string|max:50',
            'wali_kelas_id' => 'nullable|exists:guru,id_guru',
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi',
            'tingkat.required' => 'Tingkat kelas wajib dipilih',
            'tingkat.min' => 'Tingkat minimal 10',
            'tingkat.max' => 'Tingkat maksimal 12',
        ]);

        try {
            Kelas::create([
                'tahun_ajaran_id' => $tahunAjaranId,
                'nama_kelas' => $validated['nama_kelas'],
                'tingkat' => $validated['tingkat'],
                'jurusan' => $validated['jurusan'],
                'wali_kelas_id' => $validated['wali_kelas_id'],
            ]);

            return redirect()->route('admin.kelas.index', $tahunAjaranId)
                ->with('success', 'Kelas berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan kelas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Detail kelas dengan daftar siswa dan mata pelajaran
     */
    public function show($tahunAjaranId, $kelasId)
    {
        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);
        $kelas = Kelas::with(['waliKelas', 'siswa'])->findOrFail($kelasId);
        
        // Ambil jadwal mata pelajaran untuk kelas ini
        $jadwalMapel = DB::table('jadwal_pelajaran')
            ->join('mata_pelajaran', 'jadwal_pelajaran.mapel_id', '=', 'mata_pelajaran.id_mapel')
            ->join('guru', 'jadwal_pelajaran.guru_id', '=', 'guru.id_guru')
            ->where('jadwal_pelajaran.kelas_id', $kelasId)
            ->select('jadwal_pelajaran.*', 'mata_pelajaran.nama_mapel', 'mata_pelajaran.kode_mapel', 'guru.nama_lengkap as nama_guru')
            ->orderBy('jadwal_pelajaran.hari')
            ->orderBy('jadwal_pelajaran.jam_mulai')
            ->get();
        
        // Siswa yang belum masuk kelas ini
        $siswaAvailable = Siswa::whereDoesntHave('kelas', function($query) use ($kelasId) {
            $query->where('id_kelas', $kelasId);
        })->orWhereNull('kelas_id')->get();
        
        $guruList = Guru::all();
        
        return view('Admin.detailKelas', compact('tahunAjaran', 'kelas', 'siswaAvailable', 'guruList', 'jadwalMapel'));
    }

    /**
     * Tambah siswa ke kelas
     */
    public function addSiswa(Request $request, $tahunAjaranId, $kelasId)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id_siswa',
        ]);

        try {
            $siswa = Siswa::findOrFail($validated['siswa_id']);
            $siswa->kelas_id = $kelasId;
            $siswa->save();

            return back()->with('success', 'Siswa berhasil ditambahkan ke kelas');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }
    }

    /**
     * Hapus siswa dari kelas (set kelas_id = null)
     */
    public function removeSiswa($tahunAjaranId, $kelasId, $siswaId)
    {
        try {
            $siswa = Siswa::findOrFail($siswaId);
            $siswa->kelas_id = null;
            $siswa->save();

            return back()->with('success', 'Siswa berhasil dihapus dari kelas');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }

    /**
     * Update wali kelas
     */
    public function updateWaliKelas(Request $request, $tahunAjaranId, $kelasId)
    {
        $validated = $request->validate([
            'wali_kelas_id' => 'nullable|exists:guru,id_guru',
        ]);

        try {
            $kelas = Kelas::findOrFail($kelasId);
            $kelas->wali_kelas_id = $validated['wali_kelas_id'];
            $kelas->save();

            return back()->with('success', 'Wali kelas berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui wali kelas: ' . $e->getMessage());
        }
    }

    /**
     * Hapus kelas
     */
    public function destroy($tahunAjaranId, $kelasId)
    {
        try {
            $kelas = Kelas::findOrFail($kelasId);
            
            // Cek apakah ada siswa di kelas
            if ($kelas->siswa()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus kelas yang memiliki siswa. Hapus siswa terlebih dahulu.');
            }

            $namaKelas = $kelas->nama_kelas;
            $kelas->delete();

            return redirect()->route('admin.kelas.index', $tahunAjaranId)
                ->with('success', 'Kelas "' . $namaKelas . '" berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }
}
