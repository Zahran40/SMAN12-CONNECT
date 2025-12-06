<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Halaman utama Manajemen Pembayaran SPP
     */
    public function index(Request $request)
    {
        $tahunAjaranList = TahunAjaran::active()->orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranId = $request->get('tahun_ajaran');
        $semester = $request->get('semester', 'Ganjil');
        $kelasId = $request->get('kelas');
        $status = $request->get('status'); // 'Lunas' atau 'Belum Lunas'

        // Default tahun ajaran aktif
        if (!$tahunAjaranId && $tahunAjaranList->count() > 0) {
            $tahunAjaran = TahunAjaran::where('status', 'Aktif')->where('is_archived', false)->first();
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

        // Query pembayaran menggunakan view_pembayaran_spp untuk efisiensi
        $query = DB::table('view_pembayaran_spp')
            ->where('id_tahun_ajaran', $tahunAjaranId);

        if ($kelasId) {
            $query->where('id_kelas', $kelasId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $pembayaranList = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(20)
            ->through(function($item) {
                // Convert stdClass dari view menjadi object yang punya struktur seperti Eloquent
                $pembayaran = new \stdClass();
                $pembayaran->id_pembayaran = $item->id_pembayaran;
                $pembayaran->nama_tagihan = $item->nama_tagihan;
                $pembayaran->bulan = $item->bulan;
                $pembayaran->tahun = $item->tahun;
                $pembayaran->jumlah_bayar = $item->jumlah_bayar;
                $pembayaran->status = $item->status;
                
                // Buat object siswa
                $pembayaran->siswa = new \stdClass();
                $pembayaran->siswa->id_siswa = $item->id_siswa;
                $pembayaran->siswa->nama_lengkap = $item->nama_siswa;
                $pembayaran->siswa->nis = $item->nis;
                $pembayaran->siswa->nisn = $item->nisn;
                $pembayaran->siswa->nama_kelas = $item->nama_kelas; // Langsung dari view
                
                return $pembayaran;
            });

        // Statistics
        $totalTagihan = PembayaranSpp::where('tahun_ajaran_id', $tahunAjaranId)->count();
        $totalLunas = PembayaranSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'Lunas')->count();
        $totalBelumLunas = PembayaranSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'Belum Lunas')->count();
        $totalNominal = PembayaranSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'Lunas')
            ->sum('jumlah_bayar');
        
        // Get tahun ajaran aktif untuk tombol rekap
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')
            ->where('is_archived', false)
            ->first();

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
            'totalNominal',
            'tahunAjaranAktif'
        ));
    }

    /**
     * Form buat tagihan baru dengan bulk selection
     */
    public function create(Request $request)
    {
        $tahunAjaranList = TahunAjaran::active()->orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranId = $request->get('tahun_ajaran');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $statusFilter = $request->get('status_filter'); // 'sudah' atau 'belum'
        
        if (!$tahunAjaranId && $tahunAjaranList->count() > 0) {
            $tahunAjaran = TahunAjaran::where('status', 'Aktif')->where('is_archived', false)->first();
            $tahunAjaranId = $tahunAjaran ? $tahunAjaran->id_tahun_ajaran : $tahunAjaranList->first()->id_tahun_ajaran;
        }

        // Tentukan tahun ajaran untuk query kelas (selalu gunakan semester Ganjil untuk kelas)
        $tahunAjaranDipilih = TahunAjaran::find($tahunAjaranId);
        $kelasAjaranIdForQuery = $tahunAjaranId;
        
        if ($tahunAjaranDipilih && $tahunAjaranDipilih->semester === 'Genap') {
            // Jika semester Genap, ambil kelas dari semester Ganjil yang sama tahunnya
            $semesterGanjil = TahunAjaran::where('tahun_mulai', $tahunAjaranDipilih->tahun_mulai)
                ->where('tahun_selesai', $tahunAjaranDipilih->tahun_selesai)
                ->where('semester', 'Ganjil')
                ->where('is_archived', false)
                ->first();
            
            if ($semesterGanjil) {
                $kelasAjaranIdForQuery = $semesterGanjil->id_tahun_ajaran;
            }
        }

        $kelasList = Kelas::where('tahun_ajaran_id', $kelasAjaranIdForQuery)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        // Get siswa list untuk bulk selection
        $siswaList = collect();
        if ($bulan && $tahun) {
            $query = Siswa::query()
                ->whereHas('kelasHistory', function($q) use ($kelasAjaranIdForQuery) {
                    $q->where('siswa_kelas.tahun_ajaran_id', $kelasAjaranIdForQuery)
                      ->where('siswa_kelas.status', 'Aktif');
                })
                ->with(['kelasHistory' => function($q) use ($kelasAjaranIdForQuery) {
                    $q->where('siswa_kelas.tahun_ajaran_id', $kelasAjaranIdForQuery)
                      ->where('siswa_kelas.status', 'Aktif');
                }]);

            // Filter berdasarkan status pembayaran
            if ($statusFilter === 'sudah') {
                $query->whereHas('pembayaranSpp', function($q) use ($bulan, $tahun, $tahunAjaranId) {
                    $q->where('bulan', $bulan)
                      ->where('tahun', $tahun)
                      ->where('tahun_ajaran_id', $tahunAjaranId)
                      ->where('status', 'Lunas');
                });
            } elseif ($statusFilter === 'belum') {
                $query->where(function($q) use ($bulan, $tahun, $tahunAjaranId) {
                    // Belum ada tagihan ATAU tagihan belum lunas
                    $q->whereDoesntHave('pembayaranSpp', function($subQ) use ($bulan, $tahun, $tahunAjaranId) {
                        $subQ->where('bulan', $bulan)
                             ->where('tahun', $tahun)
                             ->where('tahun_ajaran_id', $tahunAjaranId);
                    })
                    ->orWhereHas('pembayaranSpp', function($subQ) use ($bulan, $tahun, $tahunAjaranId) {
                        $subQ->where('bulan', $bulan)
                             ->where('tahun', $tahun)
                             ->where('tahun_ajaran_id', $tahunAjaranId)
                             ->where('status', 'Belum Lunas');
                    });
                });
            }

            $siswaList = $query->orderBy('nama_lengkap')->get();
        }

        return view('Admin.buatPembayaran', compact(
            'tahunAjaranList', 
            'tahunAjaranId', 
            'kelasList',
            'siswaList',
            'bulan',
            'tahun',
            'statusFilter'
        ));
    }

    /**
     * Simpan tagihan baru (bulk create with ACID transaction)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'nama_tagihan' => 'required|string|max:250',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2100',
            'jumlah_bayar' => 'required|numeric|min:0',
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswa,id_siswa',
            'deskripsi_batch' => 'nullable|string|max:500',
        ], [
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih',
            'nama_tagihan.required' => 'Nama tagihan wajib diisi',
            'bulan.required' => 'Bulan wajib dipilih',
            'tahun.required' => 'Tahun wajib diisi',
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi',
            'siswa_ids.required' => 'Minimal pilih 1 siswa',
            'siswa_ids.min' => 'Minimal pilih 1 siswa',
        ]);

        try {
            DB::beginTransaction();

            // Create batch record untuk audit trail
            $batch = DB::table('tagihan_batch')->insertGetId([
                'admin_id' => Auth::id(),
                'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'jumlah_siswa' => count($validated['siswa_ids']),
                'total_nominal' => $validated['jumlah_bayar'] * count($validated['siswa_ids']),
                'deskripsi' => $validated['deskripsi_batch'] ?? $validated['nama_tagihan'],
                'created_at' => now(),
            ]);

            $created = 0;
            $skipped = 0;
            $skippedSiswa = []; // Track nama siswa yang dilewati

            foreach ($validated['siswa_ids'] as $siswaId) {
                // Check if tagihan already exists
                $exists = PembayaranSpp::where('siswa_id', $siswaId)
                    ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
                    ->where('bulan', $validated['bulan'])
                    ->where('tahun', $validated['tahun'])
                    ->exists();

                if (!$exists) {
                    PembayaranSpp::create([
                        'batch_id' => $batch,
                        'siswa_id' => $siswaId,
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
                    $siswa = Siswa::find($siswaId);
                    if ($siswa) {
                        $skippedSiswa[] = $siswa->nama_lengkap . ' (' . $siswa->nisn . ')';
                    }
                }
            }

            // Jika semua tagihan sudah ada, rollback
            if ($created === 0) {
                DB::rollBack();
                $bulanText = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $periode = $bulanText[$validated['bulan']] . ' ' . $validated['tahun'];
                
                return back()
                    ->with('error', 'Semua siswa yang dipilih sudah memiliki tagihan untuk periode ' . $periode)
                    ->with('skipped_siswa', $skippedSiswa)
                    ->withInput();
            }

            DB::commit();

            $message = "Berhasil membuat {$created} tagihan (Batch #{$batch})";
            if ($skipped > 0) {
                $message .= " • {$skipped} tagihan dilewati (sudah ada)";
            }

            return redirect()->route('admin.pembayaran.index')
                ->with('success', $message)
                ->with('skipped_siswa', $skippedSiswa);

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
    
    /**
     * Rekap SPP per tahun ajaran menggunakan Stored Procedure
     */
    public function rekapPerTahunAjaran($tahunAjaranId)
    {
        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);
        
        // Ambil rekap menggunakan sp_rekap_spp_tahun
        $rekapSiswa = DB::select('CALL sp_rekap_spp_tahun(?)', [$tahunAjaranId]);
        
        // Hitung total keseluruhan
        $totalPendapatan = collect($rekapSiswa)->sum('total_bayar');
        $siswaLunas = collect($rekapSiswa)->where('bulan_belum_lunas', 0)->count();
        $siswaBelumLunas = collect($rekapSiswa)->where('bulan_belum_lunas', '>', 0)->count();
        
        return view('Admin.rekap_spp_tahun', compact(
            'rekapSiswa',
            'tahunAjaran',
            'totalPendapatan',
            'siswaLunas',
            'siswaBelumLunas'
        ));
    }

    /**
     * Cetak rekap pembayaran per siswa dengan detail bulan
     */
    public function cetakPerSiswa($tahunAjaranId, $siswaId)
    {
        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);
        $siswa = Siswa::with(['kelasHistory' => function($q) {
            $q->where('siswa_kelas.status', 'Aktif');
        }])->findOrFail($siswaId);
        
        // Ambil semua pembayaran siswa untuk tahun ajaran ini
        $pembayaranList = PembayaranSpp::where('siswa_id', $siswaId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();
        
        // Hitung statistik
        $totalLunas = $pembayaranList->where('status', 'Lunas')->count();
        $totalBelumLunas = $pembayaranList->where('status', 'Belum Lunas')->count();
        $totalBayar = $pembayaranList->where('status', 'Lunas')->sum('jumlah_bayar');
        $totalTagihan = $pembayaranList->sum('jumlah_bayar');
        
        return view('Admin.cetak_spp_siswa', compact(
            'tahunAjaran',
            'siswa',
            'pembayaranList',
            'totalLunas',
            'totalBelumLunas',
            'totalBayar',
            'totalTagihan'
        ));
    }
}


