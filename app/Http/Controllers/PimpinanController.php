<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Pengumuman;
use App\Models\EvaluasiKinerjaGuru;
use App\Models\TargetSekolah;
use App\Models\DokumenDigital;
use App\Helpers\LogHelper;

class PimpinanController extends Controller
{
    /**
     * Dashboard Executive - KPI sekolah
     * GET /api/pimpinan/dashboard
     */
    public function apiDashboard()
    {
        try {
            $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();

            if (!$tahunAjaranAktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada tahun ajaran aktif'
                ], 404);
            }

            // KPI Siswa
            $totalSiswa = Siswa::count();
            $siswaAktif = Siswa::whereHas('user', function($q) {
                $q->where('is_active', true);
            })->count();

            // KPI Kehadiran Siswa (30 hari terakhir)
            $rekapKehadiranSiswa = DB::table('detail_absensi')
                ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
                ->where('pertemuan.tanggal_pertemuan', '>=', now()->subDays(30))
                ->select(
                    DB::raw("COUNT(*) as total_pertemuan"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir")
                )
                ->first();

            $persentaseKehadiranSiswa = $rekapKehadiranSiswa->total_pertemuan > 0 
                ? round(($rekapKehadiranSiswa->hadir / $rekapKehadiranSiswa->total_pertemuan) * 100, 2) 
                : 0;

            // KPI Guru
            $totalGuru = Guru::count();
            $guruAktif = Guru::whereHas('user', function($q) {
                $q->where('is_active', true);
            })->count();

            // KPI Nilai Rata-rata
            $nilaiRataRata = DB::table('nilai')
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
                ->avg('nilai_akhir');

            // KPI Keuangan
            $totalPendapatan = PembayaranSpp::where('status', 'Lunas')
                ->whereYear('tgl_bayar', date('Y'))
                ->sum('jumlah_bayar');

            $totalTunggakan = PembayaranSpp::where('status', 'Belum Lunas')
                ->sum('jumlah_bayar');

            $pembayaranBulanIni = PembayaranSpp::where('status', 'Lunas')
                ->whereMonth('tgl_bayar', date('m'))
                ->whereYear('tgl_bayar', date('Y'))
                ->sum('jumlah_bayar');

            // Persentase Pembayaran
            $totalTagihan = PembayaranSpp::whereYear('created_at', date('Y'))->sum('jumlah_bayar');
            $persentasePembayaran = $totalTagihan > 0 
                ? round(($totalPendapatan / $totalTagihan) * 100, 2) 
                : 0;

            // Target Sekolah
            $targetBerjalan = TargetSekolah::where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
                ->where('status', 'Sedang Berjalan')
                ->count();

            $targetTercapai = TargetSekolah::where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
                ->where('status', 'Tercapai')
                ->count();

            // Evaluasi Kinerja Guru Terbaru
            $evaluasiTerbaru = EvaluasiKinerjaGuru::with(['guru'])
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $data = [
                'tahun_ajaran' => $tahunAjaranAktif,
                'kpi_siswa' => [
                    'total_siswa' => $totalSiswa,
                    'siswa_aktif' => $siswaAktif,
                    'persentase_kehadiran' => $persentaseKehadiranSiswa,
                    'nilai_rata_rata' => round($nilaiRataRata, 2),
                ],
                'kpi_guru' => [
                    'total_guru' => $totalGuru,
                    'guru_aktif' => $guruAktif,
                ],
                'kpi_keuangan' => [
                    'total_pendapatan_tahun_ini' => $totalPendapatan,
                    'total_tunggakan' => $totalTunggakan,
                    'pembayaran_bulan_ini' => $pembayaranBulanIni,
                    'persentase_pembayaran' => $persentasePembayaran,
                ],
                'target_sekolah' => [
                    'target_berjalan' => $targetBerjalan,
                    'target_tercapai' => $targetTercapai,
                ],
                'evaluasi_guru_terbaru' => $evaluasiTerbaru,
            ];

            LogHelper::log('view', 'Dashboard Pimpinan diakses', 'dashboard', null, Auth::id());

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Laporan Akademik Global - Rekap nilai per kelas
     * GET /api/pimpinan/laporan-akademik
     */
    public function apiLaporanAkademik(Request $request)
    {
        try {
            $tahunAjaranId = $request->input('tahun_ajaran_id');
            $semester = $request->input('semester');

            $query = DB::table('nilai')
                ->join('siswa', 'nilai.siswa_id', '=', 'siswa.id_siswa')
                ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id_kelas')
                ->join('mata_pelajaran', 'nilai.mapel_id', '=', 'mata_pelajaran.id_mapel');

            if ($tahunAjaranId) {
                $query->where('nilai.tahun_ajaran_id', $tahunAjaranId);
            }

            if ($semester) {
                $query->where('nilai.semester', $semester);
            }

            $rekapPerKelas = $query->select(
                    'kelas.nama_kelas',
                    'kelas.id_kelas as kelas_id',
                    DB::raw('COUNT(DISTINCT nilai.siswa_id) as jumlah_siswa'),
                    DB::raw('AVG(nilai.nilai_akhir) as rata_rata_nilai'),
                    DB::raw('MAX(nilai.nilai_akhir) as nilai_tertinggi'),
                    DB::raw('MIN(nilai.nilai_akhir) as nilai_terendah')
                )
                ->groupBy('kelas.nama_kelas', 'kelas.id_kelas')
                ->orderBy('kelas.nama_kelas')
                ->get();

            // Rekap per Mata Pelajaran
            $rekapPerMapel = DB::table('nilai')
                ->join('mata_pelajaran', 'nilai.mapel_id', '=', 'mata_pelajaran.id_mapel')
                ->when($tahunAjaranId, function($q) use ($tahunAjaranId) {
                    return $q->where('nilai.tahun_ajaran_id', $tahunAjaranId);
                })
                ->when($semester, function($q) use ($semester) {
                    return $q->where('nilai.semester', $semester);
                })
                ->select(
                    'mata_pelajaran.nama_mapel',
                    DB::raw('COUNT(nilai.siswa_id) as jumlah_siswa'),
                    DB::raw('AVG(nilai.nilai_akhir) as rata_rata_nilai'),
                    DB::raw('MAX(nilai.nilai_akhir) as nilai_tertinggi'),
                    DB::raw('MIN(nilai.nilai_akhir) as nilai_terendah')
                )
                ->groupBy('mata_pelajaran.nama_mapel')
                ->orderBy('mata_pelajaran.nama_mapel')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'rekap_per_kelas' => $rekapPerKelas,
                    'rekap_per_mapel' => $rekapPerMapel,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Monitoring Presensi - Statistik kehadiran
     * GET /api/pimpinan/monitoring-presensi
     */
    public function apiMonitoringPresensi(Request $request)
    {
        try {
            $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));
            $kelasId = $request->input('kelas_id');

            $query = DB::table('detail_absensi')
                ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
                ->join('jadwal_pelajaran', 'pertemuan.jadwal_id', '=', 'jadwal_pelajaran.jadwal_id')
                ->join('kelas', 'jadwal_pelajaran.kelas_id', '=', 'kelas.kelas_id')
                ->whereBetween('pertemuan.tanggal_pertemuan', [$startDate, $endDate]);

            if ($kelasId) {
                $query->where('jadwal_pelajaran.kelas_id', $kelasId);
            }

            $rekapPresensi = $query->select(
                    'kelas.nama_kelas',
                    DB::raw("COUNT(*) as total_pertemuan"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Izin' THEN 1 ELSE 0 END) as izin"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) as sakit"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Alpa' THEN 1 ELSE 0 END) as alpa")
                )
                ->groupBy('kelas.nama_kelas')
                ->get()
                ->map(function($item) {
                    $item->persentase_kehadiran = $item->total_pertemuan > 0 
                        ? round(($item->hadir / $item->total_pertemuan) * 100, 2) 
                        : 0;
                    return $item;
                });

            // Presensi per hari (grafik)
            $presensiPerHari = DB::table('detail_absensi')
                ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
                ->whereBetween('pertemuan.tanggal_pertemuan', [$startDate, $endDate])
                ->select(
                    'pertemuan.tanggal_pertemuan',
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir")
                )
                ->groupBy('pertemuan.tanggal_pertemuan')
                ->orderBy('pertemuan.tanggal_pertemuan')
                ->get()
                ->map(function($item) {
                    $item->persentase = $item->total > 0 ? round(($item->hadir / $item->total) * 100, 2) : 0;
                    return $item;
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'rekap_per_kelas' => $rekapPresensi,
                    'presensi_per_hari' => $presensiPerHari,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Laporan Keuangan - Rekap pembayaran SPP
     * GET /api/pimpinan/laporan-keuangan
     */
    public function apiLaporanKeuangan(Request $request)
    {
        try {
            $tahun = $request->input('tahun', date('Y'));
            $bulan = $request->input('bulan');

            // Total Pembayaran per Status
            $statusQuery = PembayaranSpp::query();
            if ($tahun) {
                $statusQuery->where(function($q) use ($tahun) {
                    $q->whereYear('tgl_bayar', $tahun)
                      ->orWhere(function($q2) use ($tahun) {
                          $q2->whereNull('tgl_bayar')->whereYear('created_at', $tahun);
                      });
                });
            }
            if ($bulan) {
                $statusQuery->where(function($q) use ($bulan) {
                    $q->whereMonth('tgl_bayar', $bulan)
                      ->orWhere(function($q2) use ($bulan) {
                          $q2->whereNull('tgl_bayar')->whereMonth('created_at', $bulan);
                      });
                });
            }

            $rekapPerStatus = (clone $statusQuery)
                ->select(
                    'status',
                    DB::raw('COUNT(*) as jumlah_transaksi'),
                    DB::raw('SUM(jumlah_bayar) as total_nominal')
                )
                ->groupBy('status')
                ->get();

            // Pembayaran per Bulan (12 Bulan Lengkap untuk $tahun)
            $rawBulan = DB::table('pembayaran_spp')
                ->where('status', 'Lunas')
                ->whereNotNull('tgl_bayar')
                ->whereYear('tgl_bayar', $tahun)
                ->select(
                    DB::raw("MONTH(tgl_bayar) as bulan_num"),
                    DB::raw("COUNT(*) as jumlah_transaksi"),
                    DB::raw("SUM(jumlah_bayar) as total_nominal")
                )
                ->groupBy(DB::raw("MONTH(tgl_bayar)"))
                ->get()
                ->keyBy('bulan_num');

            $namaBulanIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $perBulan = [];
            for ($m = 1; $m <= 12; $m++) {
                $item = $rawBulan->get($m);
                $tot = $item ? (float) $item->total_nominal : 0.0;
                $cnt = $item ? (int) $item->jumlah_transaksi : 0;
                $perBulan[] = [
                    'bulan' => $m,
                    'nama_bulan' => $namaBulanIndo[$m],
                    'tahun' => (int) $tahun,
                    'periode' => sprintf('%04d-%02d', $tahun, $m),
                    'total' => $tot,
                    'total_nominal' => $tot,
                    'pemasukan' => $tot,
                    'jumlah_transaksi' => $cnt,
                ];
            }

            // Pembayaran per Kelas
            $pembayaranPerKelas = DB::table('pembayaran_spp')
                ->join('siswa', 'pembayaran_spp.siswa_id', '=', 'siswa.id_siswa')
                ->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id_kelas')
                ->where('pembayaran_spp.status', 'Lunas')
                ->whereNotNull('pembayaran_spp.tgl_bayar')
                ->whereYear('pembayaran_spp.tgl_bayar', $tahun)
                ->select(
                    DB::raw("COALESCE(kelas.nama_kelas, 'Lainnya') as nama_kelas"),
                    DB::raw('COUNT(*) as jumlah_transaksi'),
                    DB::raw('SUM(pembayaran_spp.jumlah_bayar) as total_nominal')
                )
                ->groupBy(DB::raw("COALESCE(kelas.nama_kelas, 'Lainnya')"))
                ->orderBy('nama_kelas')
                ->get();

            // Top 10 Siswa dengan Tunggakan Terbesar
            $tunggakanTerbesar = DB::table('pembayaran_spp')
                ->join('siswa', 'pembayaran_spp.siswa_id', '=', 'siswa.id_siswa')
                ->where('pembayaran_spp.status', 'Belum Lunas')
                ->select(
                    'siswa.id_siswa as siswa_id',
                    'siswa.nama_lengkap',
                    'siswa.nis',
                    DB::raw('SUM(pembayaran_spp.jumlah_bayar) as total_tunggakan')
                )
                ->groupBy('siswa.id_siswa', 'siswa.nama_lengkap', 'siswa.nis')
                ->orderBy('total_tunggakan', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'per_bulan' => $perBulan,
                    'pembayaran_per_bulan' => $perBulan,
                    'rekap_per_status' => $rekapPerStatus,
                    'pembayaran_per_kelas' => $pembayaranPerKelas,
                    'tunggakan_terbesar' => $tunggakanTerbesar,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Evaluasi Kinerja Guru
     * GET /api/pimpinan/evaluasi-guru
     */
    public function apiEvaluasiGuru(Request $request)
    {
        try {
            $tahunAjaranId = $request->input('tahun_ajaran_id');
            $guruId = $request->input('guru_id');

            $query = EvaluasiKinerjaGuru::with(['guru', 'tahunAjaran']);

            if ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            }

            if ($guruId) {
                $query->where('guru_id', $guruId);
            }

            $evaluasi = $query->orderBy('created_at', 'desc')->get();

            // Ranking Guru berdasarkan persentase kehadiran
            $rankingGuru = EvaluasiKinerjaGuru::with(['guru'])
                ->when($tahunAjaranId, function($q) use ($tahunAjaranId) {
                    return $q->where('tahun_ajaran_id', $tahunAjaranId);
                })
                ->orderBy('skor_total', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'evaluasi' => $evaluasi,
                    'ranking_guru' => $rankingGuru,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manajemen Pengumuman - List pengumuman
     * GET /api/pimpinan/pengumuman
     */
    public function apiPengumuman()
    {
        try {
            $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $pengumuman
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve/Publish Pengumuman
     * PUT /api/pimpinan/pengumuman/{id}/approve
     */
    public function apiApprovePengumuman($id)
    {
        try {
            $pengumuman = Pengumuman::find($id);

            if (!$pengumuman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengumuman tidak ditemukan'
                ], 404);
            }

            $pengumuman->status = 'aktif';
            $pengumuman->save();

            LogHelper::log('update', 'Pengumuman disetujui', 'pengumuman', $id, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil disetujui',
                'data' => $pengumuman
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analisis Trending - Grafik perkembangan per periode
     * GET /api/pimpinan/analisis-trending
     */
    public function apiAnalisisTrending(Request $request)
    {
        try {
            $tahun = $request->input('tahun', date('Y'));

            // Trend Kehadiran per Bulan
            $trendKehadiran = DB::table('detail_absensi')
                ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
                ->whereYear('pertemuan.tanggal_pertemuan', $tahun)
                ->select(
                    DB::raw("DATE_FORMAT(pertemuan.tanggal_pertemuan, '%Y-%m') as bulan"),
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir")
                )
                ->groupBy(DB::raw("DATE_FORMAT(pertemuan.tanggal_pertemuan, '%Y-%m')"))
                ->orderBy('bulan')
                ->get()
                ->map(function($item) {
                    $item->persentase = $item->total > 0 ? round(($item->hadir / $item->total) * 100, 2) : 0;
                    return $item;
                });

            // Trend Nilai Rata-rata per Semester
            $trendNilai = DB::table('nilai')
                ->join('tahun_ajaran', 'nilai.tahun_ajaran_id', '=', 'tahun_ajaran.id_tahun_ajaran')
                ->where('tahun_ajaran.tahun_mulai', $tahun)
                ->select(
                    'nilai.semester',
                    DB::raw('AVG(nilai.nilai_akhir) as rata_rata_nilai')
                )
                ->groupBy('nilai.semester')
                ->orderBy('nilai.semester')
                ->get();

            // Trend Pembayaran per Bulan
            $trendPembayaran = DB::table('pembayaran_spp')
                ->where('status', 'Lunas')
                ->whereNotNull('tgl_bayar')
                ->whereYear('tgl_bayar', $tahun)
                ->select(
                    DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m') as bulan"),
                    DB::raw('SUM(jumlah_bayar) as total_pembayaran')
                )
                ->groupBy(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"))
                ->orderBy('bulan')
                ->get();

            // Comparative Analysis - Perbandingan Kelas
            $comparativeKelas = DB::table('nilai')
                ->join('siswa', 'nilai.siswa_id', '=', 'siswa.id_siswa')
                ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id_kelas')
                ->select(
                    'kelas.nama_kelas',
                    DB::raw('AVG(nilai.nilai_akhir) as rata_rata_nilai')
                )
                ->groupBy('kelas.nama_kelas')
                ->orderBy('rata_rata_nilai', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'trend_kehadiran' => $trendKehadiran,
                    'trend_nilai' => $trendNilai,
                    'trend_pembayaran' => $trendPembayaran,
                    'comparative_kelas' => $comparativeKelas,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Target Sekolah - Goal Tracking
     * GET /api/pimpinan/target-sekolah
     */
    public function apiTargetSekolah(Request $request)
    {
        try {
            $tahunAjaranId = $request->input('tahun_ajaran_id');
            $kategori = $request->input('kategori');

            $query = TargetSekolah::with(['tahunAjaran', 'pembuat']);

            if ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            }

            if ($kategori) {
                $query->where('kategori', $kategori);
            }

            $target = $query->orderBy('tanggal_target')->get();

            // Statistik
            $stats = [
                'total_target' => $target->count(),
                'tercapai' => $target->where('status', 'Tercapai')->count(),
                'berjalan' => $target->where('status', 'Sedang Berjalan')->count(),
                'tertunda' => $target->where('status', 'Tidak Tercapai')->count(),
                'rata_rata_pencapaian' => $target->avg('persentase_pencapaian'),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'target' => $target,
                    'statistik' => $stats,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Reports - Generate PDF/Excel
     * GET /api/pimpinan/export-reports
     */
    public function apiExportReports(Request $request)
    {
        try {
            $tipe = $request->input('tipe'); // 'akademik', 'keuangan', 'presensi', 'kinerja_guru'
            $format = $request->input('format', 'pdf'); // 'pdf', 'excel'
            $tahunAjaranId = $request->input('tahun_ajaran_id');

            // TODO: Implementasi export PDF/Excel menggunakan library seperti:
            // - DomPDF atau Laravel-snappy untuk PDF
            // - Laravel Excel (Maatwebsite) untuk Excel
            
            // Placeholder response
            return response()->json([
                'success' => true,
                'message' => 'Fitur export sedang dalam pengembangan',
                'data' => [
                    'tipe' => $tipe,
                    'format' => $format,
                    'tahun_ajaran_id' => $tahunAjaranId
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Digital Signature - Approve dokumen
     * POST /api/pimpinan/dokumen/{id}/approve
     */
    public function apiApproveDokumen($id)
    {
        try {
            $dokumen = DokumenDigital::find($id);

            if (!$dokumen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan'
                ], 404);
            }

            $dokumen->status = 'Ditandatangani';
            $dokumen->ditandatangani_oleh = Auth::id();
            $dokumen->tanggal_ttd = now();
            $dokumen->save();

            LogHelper::log('update', 'Dokumen disetujui', 'dokumen_digital', $id, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil disetujui',
                'data' => $dokumen
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
