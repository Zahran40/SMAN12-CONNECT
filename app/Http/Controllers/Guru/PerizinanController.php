<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Perizinan;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerizinanController extends Controller
{
    /**
     * Tampilkan daftar perizinan online siswa untuk Guru / Wali Kelas
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();

        // Tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaranId = $tahunAjaranAktif ? $tahunAjaranAktif->id_tahun_ajaran : 1;

        // Kelas di mana guru ini menjadi Wali Kelas
        $kelasWali = Kelas::where('wali_kelas_id', $guru->id_guru)->get();
        $kelasWaliIds = $kelasWali->pluck('id_kelas')->toArray();
        $isWaliKelas = !empty($kelasWaliIds);

        // Kelas yang diajar oleh guru ini
        $kelasMengajarIds = JadwalPelajaran::where('guru_id', $guru->id_guru)->pluck('kelas_id')->unique()->toArray();

        // Gabungan semua kelas yang relevan dengan guru ini
        $allKelasIds = array_unique(array_merge($kelasWaliIds, $kelasMengajarIds));
        
        // Jika tidak ada kelas sama sekali (misal guru baru), ambil semua kelas sebagai fallback
        if (empty($allKelasIds)) {
            $allKelasIds = Kelas::pluck('id_kelas')->toArray();
        }

        $daftarKelas = Kelas::whereIn('id_kelas', $allKelasIds)->orderBy('nama_kelas')->get();

        // Filter request
        $statusFilter = $request->get('status', 'Semua');
        $kelasFilter = $request->get('kelas_id');
        $search = $request->get('search');

        // Tentukan kelas yang difilter
        $selectedKelasIds = $kelasFilter ? [$kelasFilter] : $allKelasIds;

        // Ambil siswa ID dari siswa_kelas aktif atau siswa langsung
        $siswaIdsDariKelas = SiswaKelas::whereIn('kelas_id', $selectedKelasIds)
            ->where('status', 'Aktif')
            ->pluck('siswa_id')
            ->toArray();
        
        $siswaIdsDirect = Siswa::whereIn('kelas_id', $selectedKelasIds)->pluck('id_siswa')->toArray();
        $siswaIds = array_unique(array_merge($siswaIdsDariKelas, $siswaIdsDirect));

        // Query Perizinan
        $query = Perizinan::whereIn('siswa_id', $siswaIds)
            ->with(['siswa.kelas', 'siswa.kelasAktif', 'orangTua', 'approver']);

        // Filter status
        if ($statusFilter && $statusFilter !== 'Semua') {
            $query->where('status', $statusFilter);
        }

        // Filter search
        if ($search) {
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Statistik
        $allSiswaIdsScope = array_unique(array_merge(
            SiswaKelas::whereIn('kelas_id', $allKelasIds)->where('status', 'Aktif')->pluck('siswa_id')->toArray(),
            Siswa::whereIn('kelas_id', $allKelasIds)->pluck('id_siswa')->toArray()
        ));

        $countMenunggu = Perizinan::whereIn('siswa_id', $allSiswaIdsScope)->where('status', 'Menunggu')->count();
        $countDisetujui = Perizinan::whereIn('siswa_id', $allSiswaIdsScope)->where('status', 'Disetujui')->count();
        $countDitolak = Perizinan::whereIn('siswa_id', $allSiswaIdsScope)->where('status', 'Ditolak')->count();
        $countSemua = Perizinan::whereIn('siswa_id', $allSiswaIdsScope)->count();

        // Urutkan: Menunggu paling atas, lalu tanggal terbaru
        $perizinanList = $query->orderByRaw("CASE WHEN status = 'Menunggu' THEN 1 WHEN status = 'Disetujui' THEN 2 ELSE 3 END")
            ->orderBy('id_izin', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('Guru.perizinan', compact(
            'guru',
            'isWaliKelas',
            'kelasWali',
            'daftarKelas',
            'perizinanList',
            'statusFilter',
            'kelasFilter',
            'search',
            'countMenunggu',
            'countDisetujui',
            'countDitolak',
            'countSemua'
        ));
    }

    /**
     * Update status persetujuan perizinan
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak,Menunggu',
        ]);

        $perizinan = Perizinan::with('siswa')->findOrFail($id);
        $perizinan->status = $request->status;
        $perizinan->approval_by = Auth::id();
        $perizinan->approval_date = now();
        $perizinan->save();

        $namaSiswa = $perizinan->siswa ? $perizinan->siswa->nama_lengkap : 'Siswa';
        $pesan = "Pengajuan izin untuk {$namaSiswa} telah diubah menjadi: {$request->status}";

        return redirect()->back()->with('success', $pesan);
    }
}
