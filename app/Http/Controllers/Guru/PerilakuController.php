<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\LaporanPerilaku;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PerilakuController extends Controller
{
    /**
     * Tampilkan daftar laporan perilaku siswa
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();

        // Tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaranId = $tahunAjaranAktif ? $tahunAjaranAktif->id_tahun_ajaran : 1;

        // Kelas wali dan kelas yang diajar
        $kelasWali = Kelas::where('wali_kelas_id', $guru->id_guru)->get();
        $kelasWaliIds = $kelasWali->pluck('id_kelas')->toArray();
        $isWaliKelas = !empty($kelasWaliIds);

        $kelasMengajarIds = JadwalPelajaran::where('guru_id', $guru->id_guru)->pluck('kelas_id')->unique()->toArray();
        $allKelasIds = array_unique(array_merge($kelasWaliIds, $kelasMengajarIds));

        if (empty($allKelasIds)) {
            $allKelasIds = Kelas::pluck('id_kelas')->toArray();
        }

        $daftarKelas = Kelas::whereIn('id_kelas', $allKelasIds)->orderBy('nama_kelas')->get();

        // Filter request
        $jenisFilter = $request->get('jenis', 'Semua');
        $statusFilter = $request->get('status', 'Semua');
        $kelasFilter = $request->get('kelas_id');
        $search = $request->get('search');

        // Siswa IDs yang ada dalam scope kelas
        $selectedKelasIds = $kelasFilter ? [$kelasFilter] : $allKelasIds;
        
        $siswaIdsDariKelas = SiswaKelas::whereIn('kelas_id', $selectedKelasIds)
            ->where('status', 'Aktif')
            ->pluck('siswa_id')
            ->toArray();
        $siswaIdsDirect = Siswa::whereIn('kelas_id', $selectedKelasIds)->pluck('id_siswa')->toArray();
        $siswaIds = array_unique(array_merge($siswaIdsDariKelas, $siswaIdsDirect));

        // Daftar siswa untuk dropdown input (diambil dari semua kelas yang dijangkau)
        $allSiswaIds = array_unique(array_merge(
            SiswaKelas::whereIn('kelas_id', $allKelasIds)->where('status', 'Aktif')->pluck('siswa_id')->toArray(),
            Siswa::whereIn('kelas_id', $allKelasIds)->pluck('id_siswa')->toArray()
        ));
        $daftarSiswa = Siswa::whereIn('id_siswa', $allSiswaIds)
            ->with(['kelas', 'kelasAktif'])
            ->orderBy('nama_lengkap')
            ->get();

        // Query Laporan Perilaku
        $query = LaporanPerilaku::whereIn('siswa_id', $siswaIds)
            ->with(['siswa.kelas', 'siswa.kelasAktif', 'pelapor']);

        if ($jenisFilter && $jenisFilter !== 'Semua') {
            if ($jenisFilter === 'Positif') {
                $query->whereIn('jenis', ['Prestasi', 'Catatan Positif']);
            } elseif ($jenisFilter === 'Negatif') {
                $query->whereIn('jenis', ['Pelanggaran', 'Catatan Negatif']);
            } else {
                $query->where('jenis', $jenisFilter);
            }
        }

        if ($statusFilter && $statusFilter !== 'Semua') {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sq) use ($search) {
                      $sq->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nisn', 'like', "%{$search}%");
                  });
            });
        }

        // Summary Statistik (berdasarkan seluruh siswa dalam scope guru)
        $summaryQuery = LaporanPerilaku::whereIn('siswa_id', $allSiswaIds);
        $totalPrestasi = (clone $summaryQuery)->whereIn('jenis', ['Prestasi', 'Catatan Positif'])->count();
        $totalPelanggaran = (clone $summaryQuery)->whereIn('jenis', ['Pelanggaran', 'Catatan Negatif'])->count();
        $totalLaporanBaru = (clone $summaryQuery)->where('status', 'Baru')->count();
        $totalPoin = (clone $summaryQuery)->sum('poin');

        $laporanList = $query->orderBy('tanggal_kejadian', 'desc')
            ->orderBy('id_laporan', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('Guru.perilaku', compact(
            'guru',
            'isWaliKelas',
            'kelasWali',
            'daftarKelas',
            'daftarSiswa',
            'laporanList',
            'jenisFilter',
            'statusFilter',
            'kelasFilter',
            'search',
            'totalPrestasi',
            'totalPelanggaran',
            'totalLaporanBaru',
            'totalPoin'
        ));
    }

    /**
     * Simpan laporan perilaku baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id_siswa',
            'jenis' => 'required|in:Prestasi,Pelanggaran,Catatan Positif,Catatan Negatif',
            'judul' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'lokasi' => 'nullable|string|max:150',
            'tingkat_severity' => 'nullable|in:Ringan,Sedang,Berat',
            'poin' => 'nullable|integer',
            'tindak_lanjut' => 'nullable|string',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaranId = $tahunAjaranAktif ? $tahunAjaranAktif->id_tahun_ajaran : 1;

        // Hitung poin: jika jenis pelanggaran / negatif, buat nilainya negatif
        $rawPoin = abs((int)$request->input('poin', 0));
        $jenis = $request->jenis;
        if (in_array($jenis, ['Pelanggaran', 'Catatan Negatif'])) {
            $poin = -$rawPoin;
        } else {
            $poin = $rawPoin;
        }

        // Upload bukti foto jika ada
        $buktiPath = null;
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $buktiPath = $file->storeAs('laporan_perilaku', $filename, 'public');
        }

        LaporanPerilaku::create([
            'siswa_id' => $request->siswa_id,
            'pelapor_id' => Auth::id(),
            'tahun_ajaran_id' => $tahunAjaranId,
            'jenis' => $jenis,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'lokasi' => $request->lokasi,
            'tingkat_severity' => $request->tingkat_severity ?? 'Ringan',
            'poin' => $poin,
            'bukti_foto' => $buktiPath,
            'tindak_lanjut' => $request->tindak_lanjut,
            'status' => 'Baru',
            'notifikasi_ortu' => true,
        ]);

        return redirect()->back()->with('success', 'Catatan perilaku siswa berhasil ditambahkan.');
    }

    /**
     * Update status tindak lanjut laporan perilaku
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Baru,Ditindaklanjuti,Selesai',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $laporan = LaporanPerilaku::findOrFail($id);
        $laporan->status = $request->status;
        if ($request->filled('tindak_lanjut')) {
            $laporan->tindak_lanjut = $request->tindak_lanjut;
        }
        $laporan->save();

        return redirect()->back()->with('success', 'Status laporan perilaku berhasil diperbarui.');
    }

    /**
     * Hapus laporan perilaku
     */
    public function destroy($id)
    {
        $laporan = LaporanPerilaku::findOrFail($id);
        
        // Hapus foto jika ada
        if ($laporan->bukti_foto && Storage::disk('public')->exists($laporan->bukti_foto)) {
            Storage::disk('public')->delete($laporan->bukti_foto);
        }

        $laporan->delete();

        return redirect()->back()->with('success', 'Laporan perilaku berhasil dihapus.');
    }
}
