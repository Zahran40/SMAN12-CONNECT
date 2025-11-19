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
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        
        // Hitung statistik untuk setiap tahun ajaran
        foreach ($tahunAjaran as $ta) {
            $ta->jumlah_kelas = Kelas::where('tahun_ajaran_id', $ta->id_tahun_ajaran)->count();
            
            // Hitung siswa melalui kelas
            $kelasIds = Kelas::where('tahun_ajaran_id', $ta->id_tahun_ajaran)->pluck('id_kelas');
            $ta->jumlah_siswa = Siswa::whereIn('kelas_id', $kelasIds)->count();
            
            $ta->jumlah_guru = Guru::count(); // Guru tidak terikat tahun ajaran
            $ta->jumlah_mapel = MataPelajaran::count();
        }
        
        return view('Admin.tahunAjaran', compact('tahunAjaran'));
    }

    /**
     * Form tambah tahun ajaran
     */
    public function create()
    {
        return view('Admin.buatTahunAjaran');
    }

    /**
     * Simpan tahun ajaran baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|regex:/^\d{4}\/\d{4}$/',
            'semester' => 'required|in:Ganjil,Genap',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib dipilih',
            'tahun_ajaran.regex' => 'Format tahun ajaran tidak valid',
            'semester.required' => 'Semester wajib dipilih',
            'status.required' => 'Status wajib dipilih',
        ]);

        try {
            DB::beginTransaction();

            // Parse tahun ajaran
            list($tahunMulai, $tahunSelesai) = explode('/', $request->tahun_ajaran);

            // Cek duplikasi
            $exists = TahunAjaran::where('tahun_mulai', $tahunMulai)
                ->where('tahun_selesai', $tahunSelesai)
                ->where('semester', $request->semester)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Tahun ajaran dan semester ini sudah ada')
                    ->withInput();
            }

            // Jika status Aktif, nonaktifkan tahun ajaran lain
            if ($request->status === 'Aktif') {
                TahunAjaran::where('status', 'Aktif')->update(['status' => 'Tidak Aktif']);
            }

            TahunAjaran::create([
                'tahun_mulai' => $tahunMulai,
                'tahun_selesai' => $tahunSelesai,
                'semester' => $request->semester,
                'status' => $request->status,
            ]);

            DB::commit();
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('success', 'Tahun ajaran berhasil ditambahkan');
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
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        try {
            DB::beginTransaction();

            $tahunAjaran = TahunAjaran::findOrFail($id);

            // Jika diaktifkan, nonaktifkan yang lain
            if ($request->status === 'Aktif') {
                TahunAjaran::where('id_tahun_ajaran', '!=', $id)
                    ->where('status', 'Aktif')
                    ->update(['status' => 'Tidak Aktif']);
            }

            $tahunAjaran->update(['status' => $request->status]);

            DB::commit();
            return back()->with('success', 'Status tahun ajaran berhasil diperbarui');
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
}
