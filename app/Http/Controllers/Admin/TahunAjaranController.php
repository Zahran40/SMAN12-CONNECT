<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    /**
     * Halaman utama Tahun Ajaran dengan statistik
     */
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'all'); // all, Aktif, Tidak Aktif
        
        // Ambil semua tahun ajaran yang tidak di-archive dan group by tahun_mulai + tahun_selesai
        $tahunAjaranRaw = TahunAjaran::where('is_archived', false)
            ->orderBy('tahun_mulai', 'desc')
            ->orderBy('semester', 'asc')
            ->get();
        
        // Group by tahun ajaran (2024/2025)
        $tahunAjaranGrouped = $tahunAjaranRaw->groupBy(function($ta) {
            return $ta->tahun_mulai . '/' . $ta->tahun_selesai;
        });
        
        // Format data untuk view
        $tahunAjaran = [];
        foreach ($tahunAjaranGrouped as $key => $group) {
            [$tahunMulai, $tahunSelesai] = explode('/', $key);
            
            $ganjil = $group->firstWhere('semester', 'Ganjil');
            $genap = $group->firstWhere('semester', 'Genap');
            
            // Hitung statistik untuk tahun ajaran ini
            // Kelas: hitung dari kedua semester jika ada
            $allSemesterIds = $group->pluck('id_tahun_ajaran');
            $jumlahKelas = Kelas::whereIn('tahun_ajaran_id', $allSemesterIds)->count();

            // Hitung siswa unik dari kedua semester
            $jumlahSiswa = \App\Models\SiswaKelas::whereIn('tahun_ajaran_id', $allSemesterIds)
                ->where('status', 'Aktif')
                ->distinct('siswa_id')
                ->count('siswa_id');
            
            // Filter berdasarkan status jika diperlukan
            if ($statusFilter !== 'all') {
                if ($ganjil && $ganjil->status !== $statusFilter && $genap && $genap->status !== $statusFilter) {
                    continue;
                }
            }
            
            // Tentukan apakah tahun ajaran ini aman untuk dihapus
            // Tahun ajaran bisa dihapus jika SEMUA semester tidak aktif
            // Data siswa/kelas/jadwal tidak perlu dihapus (untuk history)
            $isAnyActive = ($ganjil && $ganjil->status === 'Aktif') || ($genap && $genap->status === 'Aktif');
            $canDelete = !$isAnyActive;

            $tahunAjaran[] = (object)[
                'tahun_mulai' => $tahunMulai,
                'tahun_selesai' => $tahunSelesai,
                'ganjil' => $ganjil,
                'genap' => $genap,
                'jumlah_kelas' => $jumlahKelas,
                'jumlah_siswa' => $jumlahSiswa,
                'jumlah_guru' => Guru::count(),
                'jumlah_mapel' => MataPelajaran::count(),
                'can_delete' => $canDelete,
            ];
        }
        
        return view('Admin.tahunAjaran', compact('tahunAjaran', 'statusFilter'));
    }

    /**
     * Form tambah tahun ajaran
     */
    public function create()
    {
        // Ambil tahun ajaran yang sudah ada (untuk cek duplikasi)
        $existingYears = TahunAjaran::select('tahun_mulai', 'tahun_selesai')
            ->distinct()
            ->get()
            ->map(function($ta) {
                return $ta->tahun_mulai . '/' . $ta->tahun_selesai;
            })
            ->toArray();
        
        return view('Admin.buatTahunAjaran', compact('existingYears'));
    }

    /**
     * Simpan tahun ajaran baru (Auto-create 2 semester sekaligus)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_mulai' => 'required|integer|min:2020|max:2050',
            'tahun_selesai' => 'required|integer|min:2020|max:2050|gt:tahun_mulai',
        ], [
            'tahun_mulai.required' => 'Tahun mulai wajib diisi',
            'tahun_selesai.required' => 'Tahun selesai wajib diisi',
            'tahun_selesai.gt' => 'Tahun selesai harus lebih besar dari tahun mulai',
        ]);

        try {
            DB::beginTransaction();

            $tahunMulai = $request->tahun_mulai;
            $tahunSelesai = $request->tahun_selesai;

            // Cek duplikasi tahun ajaran
            $exists = TahunAjaran::where('tahun_mulai', $tahunMulai)
                ->where('tahun_selesai', $tahunSelesai)
                ->exists();

            if ($exists) {
                return back()->with('error', "Tahun ajaran {$tahunMulai}/{$tahunSelesai} sudah ada")
                    ->withInput();
            }

            // Auto-deactivate semua tahun ajaran aktif
            TahunAjaran::where('status', 'Aktif')->update(['status' => 'Tidak Aktif']);

            // Create Semester Ganjil (Aktif) - Observer akan create 30 kelas
            $semesterGanjil = TahunAjaran::create([
                'tahun_mulai' => (int)$tahunMulai,
                'tahun_selesai' => (int)$tahunSelesai,
                'semester' => 'Ganjil',
                'status' => 'Aktif',  // Semester Ganjil default aktif
            ]);

            // Create Semester Genap (Tidak Aktif) - TIDAK create kelas (pakai kelas semester Ganjil)
            $semesterGenap = TahunAjaran::create([
                'tahun_mulai' => (int)$tahunMulai,
                'tahun_selesai' => (int)$tahunSelesai,
                'semester' => 'Genap',
                'status' => 'Tidak Aktif',  // Semester Genap belum aktif
            ]);

            DB::commit();
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('success', "Tahun ajaran {$tahunMulai}/{$tahunSelesai} berhasil dibuat dengan 30 kelas (digunakan untuk kedua semester)");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan tahun ajaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Detail tahun ajaran dengan statistik lengkap
     */
    public function show($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        
        // Statistik detail
        $kelasIds = Kelas::where('tahun_ajaran_id', $id)->pluck('id_kelas');
        
        $data = [
            'tahunAjaran' => $tahunAjaran,
            'jumlah_kelas' => Kelas::where('tahun_ajaran_id', $id)->count(),
            'jumlah_siswa' => Siswa::whereIn('kelas_id', $kelasIds)->count(),
            'jumlah_guru' => Guru::count(),
            'jumlah_mapel' => MataPelajaran::count(),
            'kelas_list' => Kelas::where('tahun_ajaran_id', $id)->with('siswa')->get(),
        ];
        
        return view('Admin.tahunAjaran', $data);
    }

    /**
     * Update status tahun ajaran
     * PENTING: Hanya 1 semester yang boleh aktif dalam 1 tahun ajaran
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        try {
            DB::beginTransaction();

            $tahunAjaran = TahunAjaran::findOrFail($id);
            $newStatus = $request->status;

            if ($newStatus === 'Aktif') {
                // RULE: Hanya 1 semester boleh aktif dalam tahun ajaran yang sama
                // Contoh: Jika mengaktifkan 2024/2025 Genap, maka 2024/2025 Ganjil otomatis nonaktif
                TahunAjaran::where('tahun_mulai', $tahunAjaran->tahun_mulai)
                    ->where('tahun_selesai', $tahunAjaran->tahun_selesai)
                    ->where('id_tahun_ajaran', '!=', $id)
                    ->update(['status' => 'Tidak Aktif']);
                
                // RULE: Hanya 1 tahun ajaran boleh aktif di seluruh sistem
                // Nonaktifkan semua tahun ajaran lain (tahun berbeda)
                TahunAjaran::where(function($q) use ($tahunAjaran) {
                    $q->where('tahun_mulai', '!=', $tahunAjaran->tahun_mulai)
                      ->orWhere('tahun_selesai', '!=', $tahunAjaran->tahun_selesai);
                })
                ->where('status', 'Aktif')
                ->update(['status' => 'Tidak Aktif']);
            }

            $tahunAjaran->update(['status' => $newStatus]);

            DB::commit();
            
            $semester = $tahunAjaran->semester;
            $tahun = $tahunAjaran->tahun_mulai . '/' . $tahunAjaran->tahun_selesai;
            
            if ($newStatus === 'Aktif') {
                return back()->with('success', "Semester {$semester} tahun ajaran {$tahun} berhasil diaktifkan. Semester lain otomatis dinonaktifkan.");
            } else {
                return back()->with('success', "Semester {$semester} tahun ajaran {$tahun} berhasil dinonaktifkan.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Hapus tahun ajaran
     */
    public function destroy($id)
    {
        try {
            $tahunAjaran = TahunAjaran::findOrFail($id);

            // Cek apakah tahun ajaran sedang aktif
            if ($tahunAjaran->status === 'Aktif') {
                return back()->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif');
            }

            // Cek apakah ada data terkait
            $jumlahKelas = Kelas::where('tahun_ajaran_id', $id)->count();
            $kelasIds = Kelas::where('tahun_ajaran_id', $id)->pluck('id_kelas');
            $jumlahSiswa = Siswa::whereIn('kelas_id', $kelasIds)->count();

            if ($jumlahKelas > 0 || $jumlahSiswa > 0) {
                return back()->with('error', 'Tidak dapat menghapus tahun ajaran yang memiliki data kelas atau siswa');
            }

            $tahunAjaran->delete();
            return back()->with('success', 'Tahun ajaran berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tahun ajaran: ' . $e->getMessage());
        }
    }

    /**
     * Hapus semua tahun ajaran tidak aktif (bulk delete)
     */
    public function destroyInactive()
    {
        try {
            DB::beginTransaction();

            $tahunAjaranTidakAktif = TahunAjaran::where('status', 'Tidak Aktif')->get();
            
            if ($tahunAjaranTidakAktif->isEmpty()) {
                return back()->with('info', 'Tidak ada tahun ajaran tidak aktif untuk dihapus');
            }

            $deleted = 0;
            $skipped = 0;
            $errors = [];

            foreach ($tahunAjaranTidakAktif as $ta) {
                // Cek apakah ada kelas
                $jumlahKelas = Kelas::where('tahun_ajaran_id', $ta->id_tahun_ajaran)->count();
                
                if ($jumlahKelas > 0) {
                    // Hapus relasi di siswa_kelas dulu
                    $kelasIds = Kelas::where('tahun_ajaran_id', $ta->id_tahun_ajaran)->pluck('id_kelas');
                    \App\Models\SiswaKelas::whereIn('kelas_id', $kelasIds)->delete();
                    
                    // Hapus jadwal pelajaran
                    \App\Models\JadwalPelajaran::where('tahun_ajaran_id', $ta->id_tahun_ajaran)->delete();
                    
                    // Hapus kelas
                    Kelas::where('tahun_ajaran_id', $ta->id_tahun_ajaran)->delete();
                }
                
                // Hapus tahun ajaran
                $ta->delete();
                $deleted++;
            }

            DB::commit();
            
            return back()->with('success', "Berhasil menghapus {$deleted} tahun ajaran tidak aktif beserta semua data terkait (kelas, siswa_kelas, jadwal)");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus tahun ajaran: ' . $e->getMessage());
        }
    }

    /**
     * Hapus (arsipkan) seluruh tahun ajaran (kedua semester) berdasarkan tahun mulai dan tahun selesai
     * PENTING: Tidak benar-benar menghapus, hanya mengarsipkan (is_archived = true)
     * Data kelas/siswa_kelas/jadwal tetap utuh untuk history raport
     */
    public function destroyYear($tahunMulai, $tahunSelesai)
    {
        try {
            DB::beginTransaction();

            $tas = TahunAjaran::where('tahun_mulai', $tahunMulai)
                ->where('tahun_selesai', $tahunSelesai)
                ->get();

            if ($tas->isEmpty()) {
                return back()->with('error', 'Tahun ajaran tidak ditemukan');
            }

            // Jika salah satu semester aktif, tolak
            foreach ($tas as $ta) {
                if ($ta->status === 'Aktif') {
                    return back()->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif. Nonaktifkan terlebih dahulu.');
                }
            }

            // Set is_archived = true untuk menyembunyikan dari daftar
            // Data tetap ada di database untuk history raport siswa
            foreach ($tas as $ta) {
                $ta->update(['is_archived' => true]);
            }

            DB::commit();
            return back()->with('success', "Tahun ajaran {$tahunMulai}/{$tahunSelesai} berhasil diarsipkan. Data kelas, siswa, dan jadwal tetap tersimpan untuk history raport.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengarsipkan tahun ajaran: ' . $e->getMessage());
        }
    }
}
