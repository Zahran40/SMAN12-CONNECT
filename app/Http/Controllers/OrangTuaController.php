<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
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
use App\Models\TahunAjaran;
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
                    'message' => 'Data anak tidak terhubung dengan akun ini'
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

            $totalPertemuan = $rekapAbsensi->total_pertemuan ?? 0;
            $hadir = $rekapAbsensi->hadir ?? 0;
            $persentaseKehadiran = $totalPertemuan > 0 ? round(($hadir / $totalPertemuan) * 100) : 100;

            // Tugas Pending: tugas kelas siswa yang deadline belum lewat dan belum dikumpulkan
            $tugasPending = 0;
            if ($siswa->kelas_id) {
                $tugasPending = Tugas::whereHas('jadwal', function($q) use ($siswa) {
                    $q->where('kelas_id', $siswa->kelas_id);
                })
                ->where('deadline', '>=', now())
                ->whereDoesntHave('detailTugas', function($q) use ($siswaId) {
                    $q->where('siswa_id', $siswaId)->whereNotNull('tgl_kumpul');
                })
                ->count();
            }

            // Rata-rata Nilai
            $avgNilai = Raport::where('siswa_id', $siswaId)->avg('nilai_akhir');
            $rataRataNilai = $avgNilai !== null ? round((float)$avgNilai, 1) : '-';

            // Pembayaran SPP
            $pembayaranTerbaru = PembayaranSpp::where('siswa_id', $siswaId)
                ->orderBy('tgl_bayar', 'desc')
                ->first();

            $totalTunggakan = PembayaranSpp::where('siswa_id', $siswaId)
                ->where('status', 'Belum Lunas')
                ->sum('jumlah_bayar');

            $statusSpp = $totalTunggakan > 0 ? 'Belum Lunas' : 'Lunas';

            // Pengumuman Terbaru (belum dibaca)
            $pengumumanBelumDibaca = Pengumuman::where('status', 'aktif')
                ->whereIn(DB::raw('LOWER(target_role)'), ['orangtua', 'semua'])
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
                // Top-level fields used directly by beranda.blade.php
                'persentase_kehadiran' => $persentaseKehadiran,
                'kehadiran' => $persentaseKehadiran,
                'rata_rata_nilai' => $rataRataNilai,
                'nilai_rata_rata' => $rataRataNilai,
                'tugas_belum_selesai' => $tugasPending,
                'tugas_pending' => $tugasPending,
                'status_spp' => $statusSpp,
                'spp' => $statusSpp,

                'siswa' => [
                    'id' => $siswa->id_siswa,
                    'nama' => $siswa->nama_lengkap,
                    'nis' => $siswa->nis,
                    'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : null,
                    'foto' => $siswa->user ? $siswa->user->foto : null,
                ],
                'detail_kehadiran' => [
                    'total_pertemuan' => $totalPertemuan,
                    'hadir' => $hadir,
                    'izin' => $rekapAbsensi->izin ?? 0,
                    'sakit' => $rekapAbsensi->sakit ?? 0,
                    'alpa' => $rekapAbsensi->alpa ?? 0,
                    'persentase_kehadiran' => $persentaseKehadiran
                ],
                'akademik' => [
                    'tugas_pending' => $tugasPending,
                    'rata_rata_nilai' => $rataRataNilai
                ],
                'keuangan' => [
                    'pembayaran_terakhir' => $pembayaranTerbaru,
                    'total_tunggakan' => (float)$totalTunggakan,
                    'status_spp' => $statusSpp,
                ],
                'notifikasi' => [
                    'pengumuman_belum_dibaca' => $pengumumanBelumDibaca,
                ],
                'perilaku' => [
                    'total_poin' => (int)$totalPoinPerilaku,
                    'laporan_terbaru' => $laporanPerilakuTerbaru,
                ]
            ];

            if (class_exists(LogHelper::class)) {
                LogHelper::log('view', 'Dashboard Orang Tua diakses', 'dashboard', null, $user->id);
            }

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

            if (!$siswaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }

            if ($request->filled('bulan')) {
                $startDate = Carbon::parse($request->bulan . '-01')->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::parse($request->bulan . '-01')->endOfMonth()->format('Y-m-d');
            } else {
                $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
                $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
            }

            $presensi = DB::table('detail_absensi')
                ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
                ->join('jadwal_pelajaran', 'pertemuan.jadwal_id', '=', 'jadwal_pelajaran.id_jadwal')
                ->join('mata_pelajaran', 'jadwal_pelajaran.mapel_id', '=', 'mata_pelajaran.id_mapel')
                ->join('guru', 'jadwal_pelajaran.guru_id', '=', 'guru.id_guru')
                ->where('detail_absensi.siswa_id', $siswaId)
                ->whereBetween('pertemuan.tanggal_pertemuan', [$startDate, $endDate])
                ->select(
                    'detail_absensi.*',
                    'pertemuan.tanggal_pertemuan',
                    'pertemuan.tanggal_pertemuan as tanggal',
                    'pertemuan.waktu_mulai',
                    'pertemuan.waktu_selesai',
                    'mata_pelajaran.nama_mapel',
                    'mata_pelajaran.nama_mapel as mata_pelajaran',
                    'guru.nama_lengkap as nama_guru'
                )
                ->orderBy('pertemuan.tanggal_pertemuan', 'desc')
                ->orderBy('pertemuan.waktu_mulai', 'desc')
                ->get();

            // Statistik
            $total = $presensi->count();
            $hadir = $presensi->where('status_kehadiran', 'Hadir')->count();
            $izin = $presensi->where('status_kehadiran', 'Izin')->count();
            $sakit = $presensi->where('status_kehadiran', 'Sakit')->count();
            $alpa = $presensi->where('status_kehadiran', 'Alpa')->count();
            $persentase = $total > 0 ? round(($hadir / $total) * 100) : 100;

            $summary = [
                'total' => $total,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'persentase_kehadiran' => $persentase,
                'persentase' => $persentase,
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'presensi' => $presensi,
                    'detail' => $presensi,
                    'items' => $presensi,
                    'summary' => $summary,
                    'ringkasan' => $summary,
                    'stats' => $summary
                ],
                'summary' => $summary,
                'items' => $presensi
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
            $avgValue = $nilai->avg('nilai_akhir');
            $rataRata = $avgValue !== null ? round((float)$avgValue, 2) : 0;

            $tertinggi = $nilai->max('nilai_akhir') ?? 0;
            $terendah = $nilai->min('nilai_akhir') ?? 0;

            // Format items for monitoring-nilai.blade.php
            $items = $nilai->map(function($item) {
                $row = $item->toArray();
                $namaMapel = $item->mataPelajaran->nama_mapel ?? '-';
                $row['nama_mapel'] = $namaMapel;
                $row['mata_pelajaran'] = $namaMapel;
                $row['nilai_akhir'] = (float)($item->nilai_akhir ?? 0);
                $row['nilai_huruf'] = $item->nilai_huruf ?? '-';
                return $row;
            });

            // Grafik perkembangan (per mapel)
            $grafikPerkembangan = $nilai->groupBy('mapel_id')->map(function($mapelItems) {
                $first = $mapelItems->first();
                return [
                    'mapel' => $first->mataPelajaran->nama_mapel ?? '-',
                    'nilai' => $mapelItems->pluck('nilai_akhir'),
                    'periode' => $mapelItems->pluck('semester')
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'nilai' => $items,
                    'items' => $items,
                    'rata_rata' => $rataRata,
                    'tertinggi' => $tertinggi,
                    'terendah' => $terendah,
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
                ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                ->orderBy('jam_mulai')
                ->get();

            // Flat list matching what jadwal-pelajaran.blade.php expects (allJadwal.filter(...))
            $flatJadwal = $jadwal->map(function($item) {
                $namaMapel = $item->mataPelajaran->nama_mapel ?? '-';
                $namaGuru = $item->guru->nama_lengkap ?? '-';
                $ruangan = $item->kelas->nama_kelas ?? '-';

                return [
                    'id_jadwal' => $item->id_jadwal,
                    'hari' => $item->hari,
                    'jam_mulai' => $item->jam_mulai ? substr($item->jam_mulai, 0, 5) : '-',
                    'jam_selesai' => $item->jam_selesai ? substr($item->jam_selesai, 0, 5) : '-',
                    'nama_mapel' => $namaMapel,
                    'mata_pelajaran' => $namaMapel,
                    'nama_guru' => $namaGuru,
                    'guru' => $namaGuru,
                    'ruangan' => $ruangan,
                    'kelas' => $ruangan,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $flatJadwal,
                'jadwal' => $flatJadwal,
                'jadwal_per_hari' => $jadwal->groupBy('hari')
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
            $siswa = Siswa::find($siswaId);

            if (!$siswa || !$siswa->kelas_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kelas siswa tidak ditemukan'
                ], 404);
            }

            $statusFilter = $request->input('status'); // 'pending', 'selesai', 'terlambat'

            // Ambil semua tugas untuk kelas siswa
            $allTugas = Tugas::whereHas('jadwal', function($q) use ($siswa) {
                $q->where('kelas_id', $siswa->kelas_id);
            })
            ->with(['jadwal.mataPelajaran', 'jadwal.guru', 'detailTugas' => function($q) use ($siswaId) {
                $q->where('siswa_id', $siswaId);
            }])
            ->orderBy('id_tugas', 'desc')
            ->get();

            $formattedTugas = $allTugas->map(function($t) {
                $detail = $t->detailTugas->first();
                $namaMapel = $t->jadwal->mataPelajaran->nama_mapel ?? '-';
                $namaGuru = $t->jadwal->guru->nama_lengkap ?? '-';

                $status = 'Belum Dikerjakan';
                if ($detail) {
                    if ($detail->nilai !== null) {
                        $status = 'Dinilai';
                    } elseif ($detail->tgl_kumpul !== null) {
                        $status = 'Sudah Dikerjakan';
                    }
                }

                $deadlineStr = $t->deadline ? Carbon::parse($t->deadline)->format('d M Y H:i') : '-';

                return [
                    'id_tugas' => $t->id_tugas,
                    'id' => $t->id_tugas,
                    'judul' => $t->judul_tugas,
                    'judul_tugas' => $t->judul_tugas,
                    'deskripsi' => $t->deskripsi,
                    'nama_mapel' => $namaMapel,
                    'mata_pelajaran' => $namaMapel,
                    'nama_guru' => $namaGuru,
                    'guru' => $namaGuru,
                    'deadline' => $deadlineStr,
                    'tgl_deadline' => $deadlineStr,
                    'raw_deadline' => $t->deadline,
                    'status' => $status,
                    'nilai' => $detail ? $detail->nilai : null,
                    'komentar_guru' => $detail ? $detail->komentar_guru : null,
                    'file_path' => $detail && $detail->file_path ? asset('storage/' . $detail->file_path) : null,
                ];
            });

            // Filter jika diminta
            if ($statusFilter === 'pending') {
                $formattedTugas = $formattedTugas->filter(function($t) {
                    return $t['status'] === 'Belum Dikerjakan' && (!$t['raw_deadline'] || Carbon::parse($t['raw_deadline'])->isFuture());
                })->values();
            } elseif ($statusFilter === 'selesai') {
                $formattedTugas = $formattedTugas->filter(function($t) {
                    return in_array($t['status'], ['Sudah Dikerjakan', 'Dinilai']);
                })->values();
            } elseif ($statusFilter === 'terlambat') {
                $formattedTugas = $formattedTugas->filter(function($t) {
                    return $t['status'] === 'Belum Dikerjakan' && $t['raw_deadline'] && Carbon::parse($t['raw_deadline'])->isPast();
                })->values();
            }

            // Ambil Materi (berdasarkan kelas siswa)
            $materi = Materi::whereHas('jadwal', function($q) use ($siswa) {
                $q->where('kelas_id', $siswa->kelas_id);
            })
            ->with(['jadwal.mataPelajaran', 'jadwal.guru'])
            ->orderBy('id_materi', 'desc')
            ->limit(20)
            ->get()
            ->map(function($m) {
                $namaMapel = $m->jadwal->mataPelajaran->nama_mapel ?? '-';
                $namaGuru = $m->jadwal->guru->nama_lengkap ?? '-';
                $tglUpload = $m->tgl_upload ? Carbon::parse($m->tgl_upload)->format('d M Y') : '-';

                return [
                    'id_materi' => $m->id_materi,
                    'id' => $m->id_materi,
                    'judul' => $m->judul_materi,
                    'judul_materi' => $m->judul_materi,
                    'deskripsi' => $m->deskripsi,
                    'nama_mapel' => $namaMapel,
                    'mata_pelajaran' => $namaMapel,
                    'nama_guru' => $namaGuru,
                    'guru' => $namaGuru,
                    'tanggal' => $tglUpload,
                    'created_at' => $tglUpload,
                    'file_path' => $m->file_path ? asset('storage/' . $m->file_path) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'tugas' => $formattedTugas,
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

            $pembayaranRaw = $query->orderBy('id_pembayaran', 'desc')->get();

            // Total Bayar & Total Tunggakan
            $totalBayar = PembayaranSpp::where('siswa_id', $siswaId)
                ->where('status', 'Lunas')
                ->sum('jumlah_bayar');

            $totalTunggakan = PembayaranSpp::where('siswa_id', $siswaId)
                ->where('status', 'Belum Lunas')
                ->sum('jumlah_bayar');

            $items = $pembayaranRaw->map(function($p) {
                $tahunAjaranStr = $p->tahunAjaran 
                    ? ($p->tahunAjaran->tahun_mulai . '/' . $p->tahunAjaran->tahun_selesai) 
                    : ($p->tahun ?? '-');

                $tglBayarStr = $p->tgl_bayar ? Carbon::parse($p->tgl_bayar)->format('d M Y') : '-';

                return [
                    'id_pembayaran' => $p->id_pembayaran,
                    'nama_tagihan' => $p->nama_tagihan ?? ('SPP ' . ($p->bulan ?? '')),
                    'bulan' => $p->bulan ?? '-',
                    'tahun' => $p->tahun ?? '-',
                    'tahun_ajaran' => $tahunAjaranStr,
                    'jumlah_bayar' => (float)$p->jumlah_bayar,
                    'nominal' => (float)$p->jumlah_bayar,
                    'status' => $p->status,
                    'tgl_bayar' => $tglBayarStr,
                    'metode_pembayaran' => $p->metode_pembayaran ?? '-',
                    'metode' => $p->metode_pembayaran ?? '-',
                    'bukti_pembayaran' => $p->bukti_pembayaran ? asset('storage/' . $p->bukti_pembayaran) : null,
                ];
            });

            $summary = [
                'total_lunas' => (float)$totalBayar,
                'total_belum_lunas' => (float)$totalTunggakan,
                'total_tunggakan' => (float)$totalTunggakan,
                'total' => (float)($totalBayar + $totalTunggakan)
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'pembayaran' => $items,
                    'items' => $items,
                    'summary' => $summary,
                    'ringkasan' => $summary,
                    'total_bayar' => (float)$totalBayar,
                    'total_tunggakan' => (float)$totalTunggakan
                ],
                'summary' => $summary,
                'pembayaran' => $items
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

            $laporanRaw = $query->orderBy('tanggal_kejadian', 'desc')->get();

            // Total Poin
            $totalPoin = LaporanPerilaku::where('siswa_id', $siswaId)->sum('poin');
            $totalPositif = LaporanPerilaku::where('siswa_id', $siswaId)->whereIn('jenis', ['Prestasi', 'Catatan Positif', 'Positif'])->sum('poin');
            $totalNegatif = LaporanPerilaku::where('siswa_id', $siswaId)->whereIn('jenis', ['Pelanggaran', 'Catatan Negatif', 'Negatif'])->sum('poin');

            $items = $laporanRaw->map(function($item) {
                $namaGuru = $item->pelapor->nama_lengkap ?? ($item->pelapor->name ?? '-');
                $tglStr = $item->tanggal_kejadian ? Carbon::parse($item->tanggal_kejadian)->format('d M Y') : '';

                return [
                    'id_laporan' => $item->id_laporan,
                    'jenis' => $item->jenis,
                    'kategori' => $item->jenis,
                    'judul' => $item->judul,
                    'deskripsi' => $item->deskripsi,
                    'keterangan' => $item->deskripsi,
                    'poin' => (int)$item->poin,
                    'tanggal' => $tglStr,
                    'created_at' => $tglStr,
                    'guru' => $namaGuru,
                    'lokasi' => $item->lokasi,
                    'tindak_lanjut' => $item->tindak_lanjut,
                    'status' => $item->status,
                ];
            });

            $summary = [
                'positif' => (int)$totalPositif,
                'negatif' => abs((int)$totalNegatif),
                'total_poin' => (int)$totalPoin,
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'laporan' => $items,
                    'items' => $items,
                    'summary' => $summary,
                    'ringkasan' => $summary,
                    'total_poin' => (int)$totalPoin,
                    'total_positif' => (int)$totalPositif,
                    'total_negatif' => abs((int)$totalNegatif)
                ],
                'summary' => $summary,
                'laporan' => $items
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
                ->whereIn(DB::raw('LOWER(target_role)'), ['orangtua', 'semua'])
                ->orderBy('tgl_publikasi', 'desc')
                ->get();

            // Mark yang sudah dibaca
            $dibaca = PengumumanDibaca::where('user_id', $user->id)
                ->pluck('pengumuman_id')
                ->toArray();

            $items = $pengumuman->map(function($item) use ($dibaca) {
                $isRead = in_array($item->id_pengumuman, $dibaca);
                $row = $item->toArray();
                $row['isi'] = $item->isi_pengumuman;
                $row['tgl_publikasi'] = $item->tgl_publikasi ? Carbon::parse($item->tgl_publikasi)->format('d M Y') : '-';
                $row['dibaca'] = $isRead;
                $row['is_read'] = $isRead;
                $row['sudah_dibaca'] = $isRead;
                return $row;
            });

            return response()->json([
                'success' => true,
                'data' => $items
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
                ->orderBy('id_izin', 'desc')
                ->get();

            $items = $perizinan->map(function($p) {
                $tglMulai = $p->tgl_mulai ? Carbon::parse($p->tgl_mulai)->format('d M Y') : '-';
                $tglSelesai = $p->tgl_selesai ? Carbon::parse($p->tgl_selesai)->format('d M Y') : '-';
                $tanggal = ($tglMulai === $tglSelesai || !$p->tgl_selesai) ? $tglMulai : ($tglMulai . ' s/d ' . $tglSelesai);

                return [
                    'id_izin' => $p->id_izin,
                    'id' => $p->id_izin,
                    'jenis' => $p->jenis_izin,
                    'jenis_izin' => $p->jenis_izin,
                    'keterangan' => $p->keterangan,
                    'tanggal' => $tanggal,
                    'tanggal_izin' => $tanggal,
                    'tgl_mulai' => $p->tgl_mulai,
                    'tgl_selesai' => $p->tgl_selesai,
                    'status' => $p->status,
                    'file_bukti' => $p->file_bukti ? asset('storage/' . $p->file_bukti) : null,
                    'approver' => $p->approver ? ($p->approver->name ?? $p->approver->nama_lengkap) : null,
                    'approval_date' => $p->approval_date ? Carbon::parse($p->approval_date)->format('d M Y H:i') : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $items
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
                'jenis' => 'required_without:jenis_izin|nullable|string',
                'jenis_izin' => 'required_without:jenis|nullable|string',
                'tanggal' => 'nullable|date',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'keterangan' => 'required_without:alasan|nullable|string',
                'alasan' => 'required_without:keterangan|nullable|string',
                'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'file_bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $siswaId = $user->reference_id;

            if (!$siswaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }

            $jenis = $request->input('jenis') ?: $request->input('jenis_izin', 'Izin');
            // Validasi format jenis izin enum('Sakit','Izin','Lainnya')
            if (!in_array($jenis, ['Sakit', 'Izin', 'Lainnya'])) {
                $jenis = ucfirst(strtolower($jenis));
                if (!in_array($jenis, ['Sakit', 'Izin', 'Lainnya'])) {
                    $jenis = 'Izin';
                }
            }

            $tglMulai = $request->input('tanggal_mulai') ?: $request->input('tanggal', now()->format('Y-m-d'));
            $tglSelesai = $request->input('tanggal_selesai') ?: $tglMulai;
            $keterangan = $request->input('keterangan') ?: $request->input('alasan', '');

            // Dapatkan tahun ajaran aktif
            $tahunAjaran = TahunAjaran::where('status', 'Aktif')->first() ?? TahunAjaran::first();
            $tahunAjaranId = $tahunAjaran ? $tahunAjaran->id_tahun_ajaran : 1;

            $data = [
                'siswa_id' => $siswaId,
                'orang_tua_id' => $user->id,
                'tahun_ajaran_id' => $tahunAjaranId,
                'jenis_izin' => $jenis,
                'tgl_mulai' => $tglMulai,
                'tgl_selesai' => $tglSelesai,
                'keterangan' => $keterangan,
                'status' => 'Menunggu',
            ];

            // Upload dokumen jika ada
            if ($request->hasFile('dokumen')) {
                $file = $request->file('dokumen');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('perizinan', $filename, 'public');
                $data['file_bukti'] = $path;
            } elseif ($request->hasFile('file_bukti')) {
                $file = $request->file('file_bukti');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('perizinan', $filename, 'public');
                $data['file_bukti'] = $path;
            }

            $perizinan = Perizinan::create($data);

            if (class_exists(LogHelper::class)) {
                LogHelper::log('create', 'Perizinan baru diajukan', 'perizinan', $perizinan->id_izin, $user->id);
            }

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
                ->join('tugas', 'detail_tugas.tugas_id', '=', 'tugas.id_tugas')
                ->where('detail_tugas.siswa_id', $siswaId)
                ->where('tugas.created_at', '>=', now()->subMonths(6))
                ->select(
                    DB::raw("DATE_FORMAT(tugas.created_at, '%Y-%m') as bulan"),
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN detail_tugas.nilai IS NOT NULL THEN 1 ELSE 0 END) as selesai")
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
