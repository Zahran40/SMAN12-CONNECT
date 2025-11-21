<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Halaman pendataan semua kelas (tidak terikat tahun ajaran)
     * Tampilkan semua tahun ajaran dengan info kelas
     */
    public function all(Request $request)
    {
        // Ambil semua tahun ajaran
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')
            ->with(['kelas.waliKelas', 'kelas.siswa'])
            ->get();
        
        return view('Admin.pendataanKelas', compact('tahunAjaranList'));
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
        $kelas = Kelas::with(['tahunAjaran', 'waliKelas', 'siswaAktif'])->findOrFail($kelasId);
        
        // Ambil jadwal mata pelajaran untuk kelas ini
        $jadwalMapel = DB::table('jadwal_pelajaran')
            ->join('mata_pelajaran', 'jadwal_pelajaran.mapel_id', '=', 'mata_pelajaran.id_mapel')
            ->join('guru', 'jadwal_pelajaran.guru_id', '=', 'guru.id_guru')
            ->where('jadwal_pelajaran.kelas_id', $kelasId)
            ->select('jadwal_pelajaran.*', 'mata_pelajaran.nama_mapel', 'mata_pelajaran.kode_mapel', 'guru.nama_lengkap as nama_guru')
            ->orderBy('jadwal_pelajaran.hari')
            ->orderBy('jadwal_pelajaran.jam_mulai')
            ->get();
        
        // Siswa yang belum masuk kelas APAPUN di tahun ajaran yang sama
        // Logika: Siswa yang tidak ada di siswa_kelas untuk tahun ajaran ini dengan status Aktif
        $siswaAvailable = Siswa::whereDoesntHave('siswaKelas', function($query) use ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId)
                  ->where('status', 'Aktif');
        })
        ->orderBy('nama_lengkap')
        ->get();
        
        // Ambil mata pelajaran yang sudah ada di kelas ini
        $mapelDiKelas = $jadwalMapel->pluck('mapel_id')->toArray();
        
        // Ambil jadwal yang mata pelajarannya belum ada di kelas ini
        $jadwalAvailable = JadwalPelajaran::with(['mataPelajaran', 'guru'])
            ->whereNotIn('mapel_id', $mapelDiKelas)  // Mapel yang belum ada di kelas ini
            ->get();
        
        // Ambil semua guru untuk dropdown wali kelas
        $guruList = Guru::orderBy('nama_lengkap')->get();
        
        return view('Admin.detailKelas', compact('tahunAjaran', 'kelas', 'siswaAvailable', 'jadwalMapel', 'jadwalAvailable', 'guruList'));
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
            DB::beginTransaction();

            $siswa = Siswa::findOrFail($validated['siswa_id']);
            $kelas = Kelas::findOrFail($kelasId);
            
            // Update kolom kelas_id di tabel siswa (untuk backward compatibility)
            $siswa->kelas_id = $kelasId;
            $siswa->save();

            // Hapus record lama di tahun ajaran yang sama (jika ada)
            SiswaKelas::where('siswa_id', $siswa->id_siswa)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'Aktif')
                ->delete();

            // Insert record baru ke siswa_kelas
            SiswaKelas::create([
                'siswa_id' => $siswa->id_siswa,
                'kelas_id' => $kelasId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'status' => 'Aktif',
                'tanggal_masuk' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Siswa berhasil ditambahkan ke kelas');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }
    }

    /**
     * Hapus siswa dari kelas (set kelas_id = null dan update siswa_kelas)
     */
    public function removeSiswa($tahunAjaranId, $kelasId, $siswaId)
    {
        try {
            DB::beginTransaction();

            $siswa = Siswa::findOrFail($siswaId);
            
            // Set kelas_id null di tabel siswa
            $siswa->kelas_id = null;
            $siswa->save();

            // HAPUS record dari siswa_kelas (bukan update status)
            // Karena ada unique constraint (siswa_id, tahun_ajaran_id, status)
            SiswaKelas::where('siswa_id', $siswaId)
                ->where('kelas_id', $kelasId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'Aktif')
                ->delete();

            DB::commit();
            return back()->with('success', 'Siswa berhasil dihapus dari kelas');
        } catch (\Exception $e) {
            DB::rollBack();
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
     * Assign jadwal pelajaran yang sudah ada ke kelas
     */
    public function addMapel(Request $request, $tahunAjaranId, $kelasId)
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id_jadwal',
        ], [
            'jadwal_id.required' => 'Jadwal pelajaran wajib dipilih',
        ]);

        try {
            DB::beginTransaction();

            $jadwal = JadwalPelajaran::findOrFail($validated['jadwal_id']);
            
            // Cek apakah mata pelajaran sudah ada di kelas ini
            $exists = JadwalPelajaran::where('kelas_id', $kelasId)
                ->where('mapel_id', $jadwal->mapel_id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Mata pelajaran sudah ada di kelas ini');
            }

            // Copy jadwal ke kelas ini (buat record baru)
            JadwalPelajaran::create([
                'kelas_id' => $kelasId,
                'mapel_id' => $jadwal->mapel_id,
                'guru_id' => $jadwal->guru_id,
                'tahun_ajaran_id' => $tahunAjaranId,
                'hari' => $jadwal->hari,
                'jam_mulai' => $jadwal->jam_mulai,
                'jam_selesai' => $jadwal->jam_selesai,
            ]);

            DB::commit();
            return back()->with('success', 'Jadwal pelajaran berhasil ditambahkan ke kelas');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan mata pelajaran: ' . $e->getMessage());
        }
    }

    /**
     * Hapus mata pelajaran dari kelas (delete jadwal)
     */
    public function removeMapel($tahunAjaranId, $kelasId, $jadwalId)
    {
        try {
            $jadwal = JadwalPelajaran::findOrFail($jadwalId);
            
            // Cek apakah sudah ada pertemuan/materi
            if ($jadwal->pertemuan()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus jadwal yang sudah memiliki pertemuan/materi');
            }

            $jadwal->delete();
            
            return back()->with('success', 'Mata pelajaran berhasil dihapus dari kelas');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus mata pelajaran: ' . $e->getMessage());
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
