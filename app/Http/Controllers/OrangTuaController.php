<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Siswa;
use App\Models\User;
use App\Models\DetailAbsensi;
use App\Models\DetailTugas;
use App\Models\Tugas;
use App\Models\Materi;
use App\Models\PembayaranSpp;
use App\Models\Pengumuman;
use App\Models\PengumumanDibaca;
use App\Models\JadwalPelajaran;
use App\Models\LaporanPerilaku;
use App\Models\Perizinan;
use App\Models\Raport;
use App\Helpers\LogHelper;

class OrangTuaController extends Controller
{
    // ==================== WEB ROUTES ====================
    public function beranda()
    {
        $user = Auth::user();
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas', 'user'])->find($user->reference_id);
        }
        return view('OrangTua.beranda', compact('siswa'));
    }

    public function presensiRealtime()
    {
        $user = Auth::user();
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas'])->find($user->reference_id);
        }
        return view('OrangTua.presensi-realtime', compact('siswa'));
    }

    public function monitoringNilai()
    {
        $user = Auth::user();
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas'])->find($user->reference_id);
        }
        return view('OrangTua.monitoring-nilai', compact('siswa'));
    }

    public function jadwalPelajaran()
    {
        $user = Auth::user();
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas'])->find($user->reference_id);
        }
        return view('OrangTua.jadwal-pelajaran', compact('siswa'));
    }

    public function detailTugasMateri()
    {
        $user = Auth::user();
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas'])->find($user->reference_id);
        }
        return view('OrangTua.detail-tugas-materi', compact('siswa'));
    }

    public function laporanPerilaku()
    {
        $user = Auth::user();
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas'])->find($user->reference_id);
        }
        return view('OrangTua.laporan-perilaku', compact('siswa'));
    }

    public function riwayatPembayaranSpp()
    {
        $user = Auth::user();
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas'])->find($user->reference_id);
        }
        return view('OrangTua.riwayat-pembayaran-spp', compact('siswa'));
    }

    public function perizinanOnline()
    {
        $user = Auth::user();
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas'])->find($user->reference_id);
        }
        return view('OrangTua.perizinan-online', compact('siswa'));
    }

    public function notifikasiPengumuman()
    {
        $user = Auth::user();
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas'])->find($user->reference_id);
        }
        return view('OrangTua.notifikasi-pengumuman', compact('siswa'));
    }

    // ==================== API ROUTES ====================
    
    /**
     * Dashboard Orang Tua - Ringkasan aktivitas anak
     * GET /api/orangtua/dashboard
     */
    public function apiDashboard()
    {
        try {
            $user = Auth::user();
            $siswaId = $user->reference_id;

            if (!$siswaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }

            $siswa = Siswa::with(['kelas', 'user'])->find($siswaId);

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }

            // Statistik Kehadiran (30 hari terakhir)
            $rekapAbsensi = DB::table('detail_absensi')
                ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
                ->where('detail_absensi.siswa_id', $siswaId)
                ->where('pertemuan.tanggal_pertemuan', '>=', now()->subDays(30))
                ->select(
                    DB::raw("COUNT(*) as total_pertemuan"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Izin' THEN 1 ELSE 0 END) as izin"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) as sakit"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Alpa' THEN 1 ELSE 0 END) as alpa")
                )
                ->first();

            // Tugas Pending
            $tugasPending = DetailTugas::whereHas('tugas', function($q) {
                $q->where('deadline', '>=', now());
            })
            ->where('siswa_id', $siswaId)
            ->where('status', 'Belum Dikumpulkan')
            ->count();

            // Pembayaran SPP
            $pembayaranTerbaru = PembayaranSpp::where('siswa_id', $siswaId)
                ->orderBy('tgl_bayar', 'desc')
                ->first();

            $totalTunggakan = PembayaranSpp::where('siswa_id', $siswaId)
                ->where('status', 'Belum Lunas')
                ->sum('jumlah_bayar');

            // Pengumuman Terbaru (belum dibaca)
            $pengumumanBelumDibaca = Pengumuman::where('status', 'aktif')
                ->whereNotIn('id_pengumuman', function($query) use ($user) {
                    $query->select('pengumuman_id')
                          ->from('pengumuman_dibaca')
                          ->where('user_id', $user->id);
                })
                ->count();

            // Laporan Perilaku Terbaru
            $laporanPerilakuTerbaru = LaporanPerilaku::where('siswa_id', $siswaId)
                ->orderBy('tanggal_kejadian', 'desc')
                ->limit(5)
                ->get();

            // Total Poin Perilaku
            $totalPoinPerilaku = LaporanPerilaku::where('siswa_id', $siswaId)
                ->sum('poin');

            $data = [
                'siswa' => [
                    'id' => $siswa->id_siswa,
                    'nama' => $siswa->nama_lengkap,
                    'nis' => $siswa->nis,
                    'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : null,
                    'foto' => $siswa->user ? $siswa->user->foto : null,
                ],
                'kehadiran' => [
                    'total_pertemuan' => $rekapAbsensi->total_pertemuan ?? 0,
                    'hadir' => $rekapAbsensi->hadir ?? 0,
                    'izin' => $rekapAbsensi->izin ?? 0,
                    'sakit' => $rekapAbsensi->sakit ?? 0,
                    'alpa' => $rekapAbsensi->alpa ?? 0,
                    'persentase_kehadiran' => $rekapAbsensi->total_pertemuan > 0 
                        ? round(($rekapAbsensi->hadir / $rekapAbsensi->total_pertemuan) * 100, 2) 
                        : 0
                ],
                'akademik' => [
                    'tugas_pending' => $tugasPending,
                ],
                'keuangan' => [
                    'pembayaran_terakhir' => $pembayaranTerbaru,
                    'total_tunggakan' => $totalTunggakan ?? 0,
                ],
                'notifikasi' => [
                    'pengumuman_belum_dibaca' => $pengumumanBelumDibaca,
                ],
                'perilaku' => [
                    'total_poin' => $totalPoinPerilaku ?? 0,
                    'laporan_terbaru' => $laporanPerilakuTerbaru,
                ]
            ];

            LogHelper::log('view', 'Dashboard Orang Tua diakses', 'dashboard', null, $user->id);

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
     * Presensi Real-time - Data kehadiran anak
     * GET /api/orangtua/presensi
     */
    public function apiPresensi(Request $request)
    {
        try {
            $user = Auth::user();
            $siswaId = $user->reference_id;

            $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));

            $presensi = DB::table('detail_absensi')
                ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
                ->join('jadwal_pelajaran', 'pertemuan.jadwal_id', '=', 'jadwal_pelajaran.jadwal_id')
                ->join('mata_pelajaran', 'jadwal_pelajaran.mapel_id', '=', 'mata_pelajaran.mapel_id')
                ->join('guru', 'jadwal_pelajaran.guru_id', '=', 'guru.guru_id')
                ->where('detail_absensi.siswa_id', $siswaId)
                ->whereBetween('pertemuan.tanggal_pertemuan', [$startDate, $endDate])
                ->select(
                    'detail_absensi.*',
                    'pertemuan.tanggal_pertemuan',
                    'pertemuan.waktu_mulai',
                    'pertemuan.waktu_selesai',
                    'mata_pelajaran.nama_mapel',
                    'guru.nama_lengkap as nama_guru'
                )
                ->orderBy('pertemuan.tanggal_pertemuan', 'desc')
                ->orderBy('pertemuan.waktu_mulai', 'desc')
                ->get();

            // Statistik
            $stats = [
                'total' => $presensi->count(),
                'hadir' => $presensi->where('status_kehadiran', 'Hadir')->count(),
                'izin' => $presensi->where('status_kehadiran', 'Izin')->count(),
                'sakit' => $presensi->where('status_kehadiran', 'Sakit')->count(),
                'alpa' => $presensi->where('status_kehadiran', 'Alpa')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $presensi,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Monitoring Nilai - Raport & perkembangan akademik
     * GET /api/orangtua/nilai
     */
    public function apiNilai(Request $request)
    {
        try {
            $user = Auth::user();
            $siswaId = $user->reference_id;

            $tahunAjaranId = $request->input('tahun_ajaran_id');
            $semester = $request->input('semester');

            $query = Raport::where('siswa_id', $siswaId)
                ->with(['mataPelajaran', 'tahunAjaran']);

            if ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            }

            if ($semester) {
                $query->where('semester', $semester);
            }

            $nilai = $query->get();

            // Hitung rata-rata
            $rataRata = $nilai->avg('nilai_akhir');

            // Grafik perkembangan (per mapel)
            $grafikPerkembangan = $nilai->groupBy('mapel_id')->map(function($items) {
                return [
                    'mapel' => $items->first()->mataPelajaran->nama_mapel,
                    'nilai' => $items->pluck('nilai_akhir'),
                    'periode' => $items->pluck('semester')
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'nilai' => $nilai,
                    'rata_rata' => round($rataRata, 2),
                    'grafik_perkembangan' => $grafikPerkembangan
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
     * Jadwal Pelajaran Anak
     * GET /api/orangtua/jadwal
     */
    public function apiJadwal()
    {
        try {
            $user = Auth::user();
            $siswaId = $user->reference_id;
            $siswa = Siswa::find($siswaId);

            if (!$siswa || !$siswa->kelas_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kelas siswa tidak ditemukan'
                ], 404);
            }

            $jadwal = JadwalPelajaran::where('kelas_id', $siswa->kelas_id)
                ->with(['mataPelajaran', 'guru', 'kelas'])
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();

            // Group by hari
            $jadwalPerHari = $jadwal->groupBy('hari');

            return response()->json([
                'success' => true,
                'data' => $jadwalPerHari
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detail Tugas & Materi
     * GET /api/orangtua/tugas-materi
     */
    public function apiTugasMateri(Request $request)
    {
        try {
            $user = Auth::user();
            $siswaId = $user->reference_id;

            $status = $request->input('status'); // 'pending', 'selesai', 'terlambat'

            // Ambil Tugas
            $queryTugas = DetailTugas::where('siswa_id', $siswaId)
                ->with(['tugas.jadwalPelajaran.mataPelajaran', 'tugas.jadwalPelajaran.guru']);

            if ($status === 'pending') {
                $queryTugas->where('status', 'Belum Dikumpulkan')
                           ->whereHas('tugas', function($q) {
                               $q->where('deadline', '>=', now());
                           });
            } elseif ($status === 'selesai') {
                $queryTugas->where('status', 'Sudah Dinilai');
            } elseif ($status === 'terlambat') {
                $queryTugas->where('status', 'Terlambat');
            }

            $tugas = $queryTugas->orderBy('created_at', 'desc')->get();

            // Ambil Materi (berdasarkan kelas siswa)
            $siswa = Siswa::find($siswaId);
            $materi = [];

            if ($siswa && $siswa->kelas_id) {
                $materi = Materi::whereHas('pertemuan.jadwalPelajaran', function($q) use ($siswa) {
                    $q->where('kelas_id', $siswa->kelas_id);
                })
                ->with(['pertemuan.jadwalPelajaran.mataPelajaran', 'pertemuan.jadwalPelajaran.guru'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'tugas' => $tugas,
                    'materi' => $materi
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
     * Riwayat Pembayaran SPP
     * GET /api/orangtua/pembayaran
     */
    public function apiPembayaran(Request $request)
    {
        try {
            $user = Auth::user();
            $siswaId = $user->reference_id;

            $status = $request->input('status'); // 'Lunas', 'Belum Lunas'

            $query = PembayaranSpp::where('siswa_id', $siswaId)
                ->with(['siswa', 'tahunAjaran']);

            if ($status) {
                $query->where('status', $status);
            }

            $pembayaran = $query->orderBy('tgl_bayar', 'desc')->get();

            // Statistik
            $totalBayar = PembayaranSpp::where('siswa_id', $siswaId)
                ->where('status', 'Lunas')
                ->sum('jumlah_bayar');

            $totalTunggakan = PembayaranSpp::where('siswa_id', $siswaId)
                ->where('status', 'Belum Lunas')
                ->sum('jumlah_bayar');

            return response()->json([
                'success' => true,
                'data' => [
                    'pembayaran' => $pembayaran,
                    'total_bayar' => $totalBayar,
                    'total_tunggakan' => $totalTunggakan
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
     * Laporan Perilaku
     * GET /api/orangtua/perilaku
     */
    public function apiPerilaku(Request $request)
    {
        try {
            $user = Auth::user();
            $siswaId = $user->reference_id;

            $jenis = $request->input('jenis'); // 'Positif', 'Negatif'

            $query = LaporanPerilaku::where('siswa_id', $siswaId)
                ->with(['siswa', 'pelapor']);

            if ($jenis) {
                $query->where('jenis', $jenis);
            }

            $laporan = $query->orderBy('tanggal_kejadian', 'desc')->get();

            // Total Poin
            $totalPoin = LaporanPerilaku::where('siswa_id', $siswaId)->sum('poin');
            $totalPositif = LaporanPerilaku::where('siswa_id', $siswaId)->whereIn('jenis', ['Prestasi', 'Catatan Positif'])->sum('poin');
            $totalNegatif = LaporanPerilaku::where('siswa_id', $siswaId)->whereIn('jenis', ['Pelanggaran', 'Catatan Negatif'])->sum('poin');

            return response()->json([
                'success' => true,
                'data' => [
                    'laporan' => $laporan,
                    'total_poin' => $totalPoin,
                    'total_positif' => $totalPositif,
                    'total_negatif' => abs($totalNegatif)
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
     * Notifikasi Pengumuman
     * GET /api/orangtua/pengumuman
     */
    public function apiPengumuman()
    {
        try {
            $user = Auth::user();

            $pengumuman = Pengumuman::where('status', 'aktif')
                ->whereIn('target_role', ['orangtua', 'semua'])
                ->orderBy('tgl_publikasi', 'desc')
                ->get();

            // Mark yang sudah dibaca
            $dibaca = PengumumanDibaca::where('user_id', $user->id)
                ->pluck('pengumuman_id')
                ->toArray();

            $pengumuman->each(function($item) use ($dibaca) {
                $item->sudah_dibaca = in_array($item->id_pengumuman, $dibaca);
            });

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
     * Mark pengumuman sebagai sudah dibaca
     * POST /api/orangtua/pengumuman/{id}/read
     */
    public function apiMarkPengumumanRead($id)
    {
        try {
            $user = Auth::user();

            PengumumanDibaca::firstOrCreate([
                'pengumuman_id' => $id,
                'user_id' => $user->id,
            ], [
                'dibaca_pada' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman ditandai sudah dibaca'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Perizinan Online - List perizinan
     * GET /api/orangtua/perizinan
     */
    public function apiPerizinan()
    {
        try {
            $user = Auth::user();
            $siswaId = $user->reference_id;

            $perizinan = Perizinan::where('siswa_id', $siswaId)
                ->with(['siswa', 'approver'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $perizinan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Perizinan Online - Ajukan izin
     * POST /api/orangtua/perizinan
     */
    public function apiAjukanIzin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_izin' => 'required|in:Sakit,Izin,Lainnya',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'alasan' => 'required|string',
                'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $siswaId = $user->reference_id;

            $data = [
                'siswa_id' => $siswaId,
                'orang_tua_id' => $user->id,
                'jenis_izin' => $request->jenis_izin,
                'tgl_mulai' => $request->tanggal_mulai,
                'tgl_selesai' => $request->tanggal_selesai,
                'keterangan' => $request->alasan,
                'status' => 'Menunggu',
            ];

            // Upload dokumen jika ada
            if ($request->hasFile('dokumen')) {
                $file = $request->file('dokumen');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('perizinan', $filename, 'public');
                $data['file_bukti'] = $path;
            }

            $perizinan = Perizinan::create($data);

            LogHelper::log('create', 'Perizinan baru diajukan', 'perizinan', $perizinan->id_izin, $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Perizinan berhasil diajukan',
                'data' => $perizinan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Grafik Perkembangan - Visualisasi progress akademik
     * GET /api/orangtua/grafik-perkembangan
     */
    public function apiGrafikPerkembangan(Request $request)
    {
        try {
            $user = Auth::user();
            $siswaId = $user->reference_id;

            $tahunAjaranId = $request->input('tahun_ajaran_id');

            // Nilai per semester
            $nilaiPerSemester = DB::table('nilai')
                ->where('siswa_id', $siswaId)
                ->when($tahunAjaranId, function($q) use ($tahunAjaranId) {
                    return $q->where('tahun_ajaran_id', $tahunAjaranId);
                })
                ->select('semester', DB::raw('AVG(nilai_akhir) as rata_rata'))
                ->groupBy('semester')
                ->orderBy('semester')
                ->get();

            // Kehadiran per bulan (6 bulan terakhir)
            $kehadiranPerBulan = DB::table('detail_absensi')
                ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
                ->where('detail_absensi.siswa_id', $siswaId)
                ->where('pertemuan.tanggal_pertemuan', '>=', now()->subMonths(6))
                ->select(
                    DB::raw("DATE_FORMAT(pertemuan.tanggal_pertemuan, '%Y-%m') as bulan"),
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir")
                )
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get()
                ->map(function($item) {
                    $item->persentase = $item->total > 0 ? round(($item->hadir / $item->total) * 100, 2) : 0;
                    return $item;
                });

            // Progress Tugas per bulan
            $progressTugas = DB::table('detail_tugas')
                ->join('tugas', 'detail_tugas.tugas_id', '=', 'tugas.tugas_id')
                ->where('detail_tugas.siswa_id', $siswaId)
                ->where('tugas.created_at', '>=', now()->subMonths(6))
                ->select(
                    DB::raw("DATE_FORMAT(tugas.created_at, '%Y-%m') as bulan"),
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN detail_tugas.status = 'Sudah Dinilai' THEN 1 ELSE 0 END) as selesai")
                )
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'nilai_per_semester' => $nilaiPerSemester,
                    'kehadiran_per_bulan' => $kehadiranPerBulan,
                    'progress_tugas' => $progressTugas
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
