<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Halaman utama Manajemen Pembayaran SPP
     */
    public function index(Request $request)
    {
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranId = $request->get('tahun_ajaran');
        $semester = $request->get('semester', 'Ganjil');
        $kelasId = $request->get('kelas');
        $status = $request->get('status'); // 'Lunas' atau 'Belum Lunas'

        // Default tahun ajaran aktif
        if (!$tahunAjaranId && $tahunAjaranList->count() > 0) {
            $tahunAjaran = TahunAjaran::where('status', 'Aktif')->first();
            if ($tahunAjaran) {
                $tahunAjaranId = $tahunAjaran->id_tahun_ajaran;
            } else {
                $tahunAjaranId = $tahunAjaranList->first()->id_tahun_ajaran;
            }
        }

        // Get kelas list
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        // Query pembayaran
        $query = PembayaranSpp::with(['siswa.kelas', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $tahunAjaranId);

        if ($kelasId) {
            $query->whereHas('siswa', function($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $pembayaranList = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistics
        $totalTagihan = PembayaranSpp::where('tahun_ajaran_id', $tahunAjaranId)->count();
        $totalLunas = PembayaranSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'Lunas')->count();
        $totalBelumLunas = PembayaranSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'Belum Lunas')->count();
        $totalNominal = PembayaranSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'Lunas')
            ->sum('jumlah_bayar');

        return view('Admin.pembayaran', compact(
            'pembayaranList',
            'tahunAjaranList',
            'tahunAjaranId',
            'semester',
            'kelasList',
            'kelasId',
            'status',
            'totalTagihan',
            'totalLunas',
            'totalBelumLunas',
            'totalNominal'
        ));
    }

    /**
     * Form buat tagihan baru
     */
    public function create(Request $request)
    {
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranId = $request->get('tahun_ajaran');
        
        if (!$tahunAjaranId && $tahunAjaranList->count() > 0) {
            $tahunAjaran = TahunAjaran::where('status', 'Aktif')->first();
            $tahunAjaranId = $tahunAjaran ? $tahunAjaran->id_tahun_ajaran : $tahunAjaranList->first()->id_tahun_ajaran;
        }

        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return view('Admin.buatPembayaran', compact('tahunAjaranList', 'tahunAjaranId', 'kelasList'));
    }

    /**
     * Simpan tagihan baru (bulk create)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'kelas_id' => 'nullable|exists:kelas,id_kelas',
            'nama_tagihan' => 'required|string|max:250',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2100',
            'jumlah_bayar' => 'required|numeric|min:0',
            'target_siswa' => 'required|in:semua,kelas_tertentu',
        ], [
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih',
            'nama_tagihan.required' => 'Nama tagihan wajib diisi',
            'bulan.required' => 'Bulan wajib dipilih',
            'tahun.required' => 'Tahun wajib diisi',
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi',
        ]);

        try {
            DB::beginTransaction();

            // Get siswa berdasarkan target
            if ($validated['target_siswa'] === 'semua') {
                // Ambil SEMUA siswa aktif, termasuk yang belum punya kelas
                $siswaList = Siswa::where(function($q) use ($validated) {
                    // Siswa yang punya kelas di tahun ajaran ini
                    $q->whereHas('kelas', function($subQ) use ($validated) {
                        $subQ->where('tahun_ajaran_id', $validated['tahun_ajaran_id']);
                    })
                    // ATAU siswa yang belum punya kelas sama sekali
                    ->orWhereNull('kelas_id');
                })->get();
            } else {
                if (empty($validated['kelas_id'])) {
                    return back()->with('error', 'Kelas harus dipilih untuk target kelas tertentu')->withInput();
                }
                $siswaList = Siswa::where('kelas_id', $validated['kelas_id'])->get();
            }

            if ($siswaList->isEmpty()) {
                return back()->with('error', 'Tidak ada siswa yang ditemukan')->withInput();
            }

            $created = 0;
            $skipped = 0;

            foreach ($siswaList as $siswa) {
                // Check if tagihan already exists
                $exists = PembayaranSpp::where('siswa_id', $siswa->id_siswa)
                    ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
                    ->where('bulan', $validated['bulan'])
                    ->where('tahun', $validated['tahun'])
                    ->exists();

                if (!$exists) {
                    PembayaranSpp::create([
                        'siswa_id' => $siswa->id_siswa,
                        'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
                        'nama_tagihan' => $validated['nama_tagihan'],
                        'bulan' => $validated['bulan'],
                        'tahun' => $validated['tahun'],
                        'jumlah_bayar' => $validated['jumlah_bayar'],
                        'status' => 'Belum Lunas',
                    ]);
                    $created++;
                } else {
                    $skipped++;
                }
            }

            DB::commit();

            $message = "Berhasil membuat {$created} tagihan";
            if ($skipped > 0) {
                $message .= " ({$skipped} tagihan sudah ada sebelumnya)";
            }

            return redirect()->route('admin.pembayaran.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat tagihan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Detail pembayaran siswa
     */
    public function show($id)
    {
        $pembayaran = PembayaranSpp::with(['siswa.kelas', 'tahunAjaran'])
            ->findOrFail($id);

        return view('Admin.detailPembayaran', compact('pembayaran'));
    }

    /**
     * Update status pembayaran manual (untuk pembayaran tunai)
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Lunas,Belum Lunas',
            'metode_pembayaran' => 'nullable|in:Tunai,Transfer,Kartu,E-Wallet',
            'nomor_va' => 'nullable|string|max:40',
            'tgl_bayar' => 'nullable|date',
        ]);

        try {
            $pembayaran = PembayaranSpp::findOrFail($id);

            $updateData = [
                'status' => $validated['status'],
            ];

            if ($validated['status'] === 'Lunas') {
                $updateData['tgl_bayar'] = $validated['tgl_bayar'] ?? now();
                $updateData['metode_pembayaran'] = $validated['metode_pembayaran'] ?? 'Tunai';
                if (!empty($validated['nomor_va'])) {
                    $updateData['nomor_va'] = $validated['nomor_va'];
                }
            }

            $pembayaran->update($updateData);

            return redirect()->back()->with('success', 'Status pembayaran berhasil diupdate');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    /**
     * Hapus tagihan
     */
    public function destroy($id)
    {
        try {
            $pembayaran = PembayaranSpp::findOrFail($id);
            
            if ($pembayaran->status === 'Lunas') {
                return back()->with('error', 'Tidak dapat menghapus tagihan yang sudah lunas');
            }

            $pembayaran->delete();

            return redirect()->route('admin.pembayaran.index')->with('success', 'Tagihan berhasil dihapus');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Export laporan pembayaran
     */
    public function export(Request $request)
    {
        // TODO: Implement export to Excel/PDF
        return back()->with('info', 'Fitur export akan segera tersedia');
    }
}
