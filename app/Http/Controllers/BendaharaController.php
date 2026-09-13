<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\DiskonBeasiswa;
use App\Models\RefundPembayaran;
use App\Models\RekonsiliasiBank;
use App\Models\RekonsiliasiItem;
use App\Models\PaymentReminder;
use App\Services\MidtransService;
use App\Helpers\LogHelper;
use Carbon\Carbon;

class BendaharaController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    protected $monthMap = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    protected function parseMonthToNumber($month)
    {
        if (is_numeric($month)) {
            $m = (int) $month;
            return ($m >= 1 && $m <= 12) ? $m : (int) date('n');
        }
        $reversed = array_change_key_case(array_flip($this->monthMap), CASE_LOWER);
        $key = strtolower(trim((string) $month));
        return $reversed[$key] ?? (int) date('n');
    }

    protected function getMonthName($monthNum)
    {
        return $this->monthMap[(int) $monthNum] ?? (string) $monthNum;
    }

    // ==================== WEB ROUTES ====================
    public function beranda()
    {
        // Statistik keuangan untuk dashboard
        $bulanIni = Carbon::now()->format('Y-m');
        
        $stats = [
            'total_pemasukan_bulan_ini' => PembayaranSpp::where('status', 'Lunas')
                ->whereRaw("DATE_FORMAT(tgl_bayar, '%Y-%m') = ?", [$bulanIni])
                ->sum('jumlah_bayar'),
            
            'total_tunggakan' => PembayaranSpp::where('status', 'Belum Lunas')
                ->sum('jumlah_bayar'),
            
            'pembayaran_pending' => PembayaranSpp::where('status', 'Belum Lunas')->count(),
            
            'total_lunas_bulan_ini' => PembayaranSpp::where('status', 'Lunas')
                ->whereRaw("DATE_FORMAT(tgl_bayar, '%Y-%m') = ?", [$bulanIni])
                ->count(),
        ];
        
        return view('Bendahara.beranda', compact('stats'));
    }

    public function manajemenTagihanSpp()
    {
        return view('Bendahara.manajemen-tagihan-spp');
    }

    public function verifikasiPembayaran()
    {
        return view('Bendahara.verifikasi-pembayaran');
    }

    public function tunggakanReminder()
    {
        return view('Bendahara.tunggakan-reminder');
    }

    public function rekonsiliasiBankbank()
    {
        return view('Bendahara.rekonsiliasi-bank');
    }

    public function rekapPembayaran()
    {
        return view('Bendahara.rekap-pembayaran');
    }

    public function multiPaymentMethod()
    {
        return view('Bendahara.multi-payment-method');
    }

    public function refundManagement()
    {
        return view('Bendahara.refund-management');
    }

    public function manajemenDiskonBeasiswa()
    {
        return view('Bendahara.manajemen-diskon-beasiswa');
    }

    // ==================== API ROUTES ====================

    /**
     * Dashboard Keuangan - Overview pemasukan/pengeluaran
     * GET /api/bendahara/dashboard
     */
    public function apiDashboard()
    {
        try {
            $tahun = date('Y');
            $bulan = date('m');

            // Total Pemasukan Tahun Ini
            $totalPemasukanTahunIni = (float) PembayaranSpp::where('status', 'Lunas')
                ->whereYear('tgl_bayar', $tahun)
                ->sum('jumlah_bayar');

            // Total Pemasukan Bulan Ini
            $totalPemasukanBulanIni = (float) PembayaranSpp::where('status', 'Lunas')
                ->whereYear('tgl_bayar', $tahun)
                ->whereMonth('tgl_bayar', $bulan)
                ->sum('jumlah_bayar');

            // Total Tunggakan
            $totalTunggakan = (float) PembayaranSpp::where('status', 'Belum Lunas')
                ->sum('jumlah_bayar');

            // Jumlah Siswa Menunggak
            $jumlahSiswaMenunggak = PembayaranSpp::where('status', 'Belum Lunas')
                ->distinct('siswa_id')
                ->count('siswa_id');

            // Pembayaran Hari Ini
            $pembayaranHariIni = PembayaranSpp::where('status', 'Lunas')
                ->whereDate('tgl_bayar', today())
                ->count();

            // Total Transaksi per Metode Pembayaran
            $transaksiPerMetode = PembayaranSpp::where('status', 'Lunas')
                ->whereYear('tgl_bayar', $tahun)
                ->select('metode_pembayaran', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(jumlah_bayar) as total'))
                ->groupBy('metode_pembayaran')
                ->get()
                ->map(function($item) {
                    return [
                        'metode_pembayaran' => $item->metode_pembayaran ?: 'Lainnya',
                        'jumlah' => (int) $item->jumlah,
                        'total' => (float) $item->total,
                    ];
                });

            // Grafik Pemasukan per Bulan (12 bulan terakhir lengkap)
            $startPeriod = now()->subMonths(11)->startOfMonth();
            $pemasukanRaw = DB::table('pembayaran_spp')
                ->where('status', 'Lunas')
                ->whereNotNull('tgl_bayar')
                ->where('tgl_bayar', '>=', $startPeriod)
                ->select(
                    DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m') as bulan"),
                    DB::raw('SUM(jumlah_bayar) as total')
                )
                ->groupBy(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"))
                ->pluck('total', 'bulan')
                ->all();

            $grafikPemasukan = [];
            for ($i = 11; $i >= 0; $i--) {
                $m = now()->subMonths($i)->format('Y-m');
                $grafikPemasukan[] = [
                    'bulan' => $m,
                    'total' => (float) ($pemasukanRaw[$m] ?? 0),
                ];
            }

            // Diskon/Beasiswa Aktif
            $diskonAktif = DiskonBeasiswa::where('status', 'Aktif')->count();

            // Refund Pending
            $refundPending = RefundPembayaran::where('status', 'Pending')->count();

            // Rekonsiliasi Pending
            $rekonsiliaPending = RekonsiliasiBank::where('status', 'Pending')->count();

            $data = [
                'pemasukan' => [
                    'tahun_ini' => $totalPemasukanTahunIni,
                    'bulan_ini' => $totalPemasukanBulanIni,
                    'hari_ini' => $pembayaranHariIni,
                ],
                'tunggakan' => [
                    'total_nominal' => $totalTunggakan,
                    'jumlah_siswa' => $jumlahSiswaMenunggak,
                ],
                'transaksi_per_metode' => $transaksiPerMetode,
                'grafik_pemasukan' => $grafikPemasukan,
                'pending_tasks' => [
                    'diskon_aktif' => $diskonAktif,
                    'refund_pending' => $refundPending,
                    'rekonsiliasi_pending' => $rekonsiliaPending,
                ]
            ];

            if (class_exists(LogHelper::class)) {
                LogHelper::log('view', 'Dashboard Bendahara diakses', 'dashboard', null, Auth::id());
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
     * Manajemen Tagihan SPP - List tagihan
     * GET /api/bendahara/tagihan
     */
    public function apiTagihan(Request $request)
    {
        try {
            $status = $request->input('status');
            $kelasId = $request->input('kelas_id');
            $search = $request->input('search');

            $query = PembayaranSpp::with(['siswa.kelas', 'tahunAjaran']);

            if ($status) {
                $query->where('status', $status);
            }

            if ($kelasId) {
                $query->whereHas('siswa', function($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            }

            if ($search) {
                $query->whereHas('siswa', function($q) use ($search) {
                    $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('nis', 'LIKE', "%{$search}%");
                });
            }

            $tagihan = $query->orderBy('id_pembayaran', 'desc')->paginate(50);

            $tagihan->getCollection()->transform(function($t) {
                $bulanName = $this->getMonthName($t->bulan);
                $t->nama_bulan = $bulanName;
                $t->tanggal_jatuh_tempo = '10 ' . $bulanName . ' ' . ($t->tahun ?? date('Y'));
                $t->bulan = $bulanName; // For clean display in views
                return $t;
            });

            return response()->json([
                'success' => true,
                'data' => $tagihan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buat Tagihan SPP Baru
     * POST /api/bendahara/tagihan
     */
    public function apiCreateTagihan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'siswa_id' => 'required|exists:siswa,id_siswa',
                'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
                'bulan' => 'required',
                'jumlah_bayar' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            $bulanNum = $this->parseMonthToNumber($request->bulan);
            $bulanName = $this->getMonthName($bulanNum);
            $tahun = (int) ($request->input('tahun') ?: date('Y'));

            $existing = PembayaranSpp::where('siswa_id', $request->siswa_id)
                ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where('bulan', $bulanNum)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => "Tagihan untuk siswa ini pada bulan {$bulanName} sudah ada.",
                ], 422);
            }

            $tagihan = PembayaranSpp::create([
                'siswa_id' => $request->siswa_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'nama_tagihan' => 'SPP ' . $bulanName,
                'bulan' => $bulanNum,
                'tahun' => $tahun,
                'jumlah_bayar' => $request->jumlah_bayar,
                'status' => 'Belum Lunas',
            ]);

            $tagihan->bulan = $bulanName;
            $tagihan->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo', '10 ' . $bulanName . ' ' . $tahun);

            if (class_exists(LogHelper::class)) {
                LogHelper::log('create', 'Tagihan SPP baru dibuat', 'pembayaran_spp', $tagihan->id_pembayaran, Auth::id());
            }

            return response()->json([
                'success' => true,
                'message' => 'Tagihan berhasil dibuat',
                'data' => $tagihan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifikasi Pembayaran Manual
     * PUT /api/bendahara/tagihan/{id}/verify
     */
    public function apiVerifyPembayaran($id, Request $request)
    {
        try {
            $pembayaran = PembayaranSpp::find($id);

            if (!$pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:Lunas,Belum Lunas',
                'tgl_bayar' => 'nullable|date',
                'metode_pembayaran' => 'nullable|string',
                'bukti_transfer' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            $pembayaran->status = $request->status;
            if ($request->status === 'Lunas') {
                $pembayaran->tgl_bayar = $request->input('tgl_bayar') ?: now()->format('Y-m-d');
                $rawMetode = $request->input('metode_pembayaran') ?: 'Tunai';
                $metodeMap = [
                    'cash' => 'Tunai',
                    'tunai' => 'Tunai',
                    'transfer bank' => 'Transfer',
                    'transfer' => 'Transfer',
                    'kartu' => 'Kartu',
                    'qris' => 'E-Wallet',
                    'e-wallet' => 'E-Wallet'
                ];
                $cleanMetode = strtolower(trim($rawMetode));
                $pembayaran->metode_pembayaran = $metodeMap[$cleanMetode] ?? 'Tunai';

                if ($request->filled('bukti_transfer')) {
                    $pembayaran->bukti_pembayaran = $request->bukti_transfer;
                }
            } else {
                $pembayaran->tgl_bayar = null;
            }
            $pembayaran->save();

            if (class_exists(LogHelper::class)) {
                LogHelper::log('update', 'Pembayaran diverifikasi', 'pembayaran_spp', $id, Auth::id());
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diverifikasi',
                'data' => $pembayaran
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rekap Pembayaran - Laporan harian/bulanan/tahunan
     * GET /api/bendahara/rekap-pembayaran
     */
    public function apiRekapPembayaran(Request $request)
    {
        try {
            $tipeRekap = $request->input('tipe', 'bulanan');
            $tahun = $request->input('tahun', date('Y'));
            $bulan = $request->input('bulan', date('m'));
            $tanggal = $request->input('tanggal', date('Y-m-d'));

            $data = [];
            $summary = [];

            if ($tipeRekap === 'harian') {
                $data = PembayaranSpp::where('status', 'Lunas')
                    ->whereDate('tgl_bayar', $tanggal)
                    ->with(['siswa', 'tahunAjaran'])
                    ->get();

                $summary = [
                    'total_transaksi' => $data->count(),
                    'total_nominal' => (float) $data->sum('jumlah_bayar'),
                ];
            } elseif ($tipeRekap === 'bulanan') {
                $data = PembayaranSpp::where('status', 'Lunas')
                    ->whereYear('tgl_bayar', $tahun)
                    ->whereMonth('tgl_bayar', $bulan)
                    ->with(['siswa', 'tahunAjaran'])
                    ->get();

                $summary = [
                    'total_transaksi' => $data->count(),
                    'total_nominal' => (float) $data->sum('jumlah_bayar'),
                    'per_metode' => $data->groupBy('metode_pembayaran')->map(function($items) {
                        return [
                            'jumlah' => $items->count(),
                            'total' => (float) $items->sum('jumlah_bayar')
                        ];
                    })
                ];
            } elseif ($tipeRekap === 'tahunan') {
                $data = DB::table('pembayaran_spp')
                    ->where('status', 'Lunas')
                    ->whereYear('tgl_bayar', $tahun)
                    ->select(
                        DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m') as bulan"),
                        DB::raw('COUNT(*) as jumlah_transaksi'),
                        DB::raw('SUM(jumlah_bayar) as total_nominal')
                    )
                    ->groupBy(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"))
                    ->orderBy('bulan')
                    ->get();

                $summary = [
                    'total_transaksi' => $data->sum('jumlah_transaksi'),
                    'total_nominal' => (float) $data->sum('total_nominal'),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'summary' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tunggakan & Reminder
     * GET /api/bendahara/tunggakan
     */
    public function apiTunggakan(Request $request)
    {
        try {
            $kelasId = $request->input('kelas_id');

            $query = PembayaranSpp::where('status', 'Belum Lunas')
                ->with(['siswa.kelas', 'tahunAjaran']);

            if ($kelasId) {
                $query->whereHas('siswa', function($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            }

            $tunggakan = $query->orderBy('id_pembayaran', 'desc')->get();

            // Group by siswa
            $tunggakanPerSiswa = $tunggakan->groupBy('siswa_id')->map(function($items) {
                $first = $items->first();
                $siswa = $first ? $first->siswa : null;
                return [
                    'siswa_id' => $siswa ? $siswa->id_siswa : $first->siswa_id,
                    'nama_siswa' => $siswa ? $siswa->nama_lengkap : ('Siswa #' . $first->siswa_id),
                    'nis' => $siswa ? $siswa->nis : '-',
                    'kelas' => ($siswa && $siswa->kelas) ? $siswa->kelas->nama_kelas : '-',
                    'jumlah_bulan_tunggak' => $items->count(),
                    'total_tunggakan' => (float) $items->sum('jumlah_bayar'),
                    'detail_tunggakan' => $items
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $tunggakanPerSiswa
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send Auto Reminder (WhatsApp/Email)
     * POST /api/bendahara/send-reminder
     */
    public function apiSendReminder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'siswa_ids' => 'required|array',
                'siswa_ids.*' => 'exists:siswa,id_siswa',
                'jenis_reminder' => 'nullable|string',
                'channel' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            $siswaIds = $request->siswa_ids;
            $channel = in_array($request->channel, ['WhatsApp', 'Email', 'SMS', 'Notifikasi App']) ? $request->channel : 'WhatsApp';

            $sent = 0;

            foreach ($siswaIds as $siswaId) {
                $tunggakan = PembayaranSpp::where('siswa_id', $siswaId)
                    ->where('status', 'Belum Lunas')
                    ->get();

                if ($tunggakan->count() > 0) {
                    $totalTunggakan = $tunggakan->sum('jumlah_bayar');
                    $siswa = Siswa::find($siswaId);
                    $tujuan = $siswa ? ($siswa->no_telepon ?: ($siswa->email ?: '081234567890')) : '081234567890';
                    
                    $pesan = "Yth. Orang Tua/Wali Siswa,\n\n";
                    $pesan .= "Terdapat tunggakan pembayaran SPP sejumlah Rp " . number_format($totalTunggakan, 0, ',', '.') . "\n";
                    $pesan .= "Untuk " . $tunggakan->count() . " bulan.\n\n";
                    $pesan .= "Mohon segera melakukan pembayaran.\n";
                    $pesan .= "Terima kasih.";

                    foreach ($tunggakan as $tagihan) {
                        PaymentReminder::create([
                            'siswa_id' => $siswaId,
                            'pembayaran_id' => $tagihan->id_pembayaran,
                            'metode' => $channel,
                            'tujuan' => $tujuan,
                            'waktu_kirim' => now(),
                            'status' => 'Terkirim',
                            'pesan' => $pesan,
                            'is_auto' => false,
                            'dikirim_oleh' => Auth::id(),
                        ]);
                    }

                    $sent++;
                }
            }

            if (class_exists(LogHelper::class)) {
                LogHelper::log('create', "Reminder terkirim ke {$sent} siswa", 'payment_reminder', null, Auth::id());
            }

            return response()->json([
                'success' => true,
                'message' => "Reminder berhasil dikirim ke {$sent} siswa",
                'data' => ['sent' => $sent]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manajemen Diskon/Beasiswa
     * GET /api/bendahara/diskon-beasiswa
     */
    public function apiDiskonBeasiswa(Request $request)
    {
        try {
            $status = $request->input('status');
            $jenis = $request->input('jenis');

            $query = DiskonBeasiswa::with(['siswa', 'pembuat']);

            if ($status) {
                $query->where('status', $status);
            }

            if ($jenis) {
                $query->where('jenis', $jenis);
            }

            $diskon = $query->orderBy('id_diskon', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $diskon
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buat Diskon/Beasiswa Baru
     * POST /api/bendahara/diskon-beasiswa
     */
    public function apiCreateDiskonBeasiswa(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis' => 'required|in:Diskon,Beasiswa',
                'nama_program' => 'required|string',
                'tipe_potongan' => 'required|in:Persentase,Nominal',
                'nilai_potongan' => 'required|numeric|min:0',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'catatan' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            $diskon = DiskonBeasiswa::create([
                'jenis' => $request->jenis,
                'nama_program' => $request->nama_program,
                'tipe_potongan' => $request->tipe_potongan,
                'nilai_potongan' => $request->nilai_potongan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => 'Aktif',
                'deskripsi' => $request->input('catatan') ?: $request->input('deskripsi'),
                'dibuat_oleh' => Auth::id(),
            ]);

            // Attach siswa via pivot if provided
            if ($request->has('siswa_id')) {
                $diskon->siswa()->sync((array)$request->siswa_id);
            }

            if (class_exists(LogHelper::class)) {
                LogHelper::log('create', 'Diskon/Beasiswa baru dibuat', 'diskon_beasiswa', $diskon->id_diskon, Auth::id());
            }

            return response()->json([
                'success' => true,
                'message' => 'Diskon/Beasiswa berhasil dibuat',
                'data' => $diskon
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refund Management
     * GET /api/bendahara/refund
     */
    public function apiRefund(Request $request)
    {
        try {
            $status = $request->input('status');

            $query = RefundPembayaran::with(['siswa', 'pembayaran', 'penyetuju']);

            if ($status) {
                $query->where('status', $status);
            }

            $refund = $query->orderBy('id_refund', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $refund
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Proses Refund
     * PUT /api/bendahara/refund/{id}/process
     */
    public function apiProcessRefund($id, Request $request)
    {
        try {
            $refund = RefundPembayaran::find($id);

            if (!$refund) {
                return response()->json([
                    'success' => false,
                    'message' => 'Refund tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:Diproses,Selesai,Ditolak',
                'catatan' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            $refund->status = $request->status;
            $refund->tanggal_persetujuan = now();
            $refund->disetujui_oleh = Auth::id();
            $refund->catatan_bendahara = $request->catatan;
            $refund->save();

            if (class_exists(LogHelper::class)) {
                LogHelper::log('update', 'Refund diproses', 'refund_pembayaran', $id, Auth::id());
            }

            return response()->json([
                'success' => true,
                'message' => 'Refund berhasil diproses',
                'data' => $refund
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rekonsiliasi Bank
     * GET /api/bendahara/rekonsiliasi
     */
    public function apiRekonsiliasi(Request $request)
    {
        try {
            $status = $request->input('status');

            $query = RekonsiliasiBank::with(['pembuat', 'items']);

            if ($status) {
                $query->where('status', $status);
            }

            $rekonsiliasi = $query->orderBy('id_rekonsiliasi', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $rekonsiliasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buat Rekonsiliasi Bank Baru
     * POST /api/bendahara/rekonsiliasi
     */
    public function apiCreateRekonsiliasi(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_bank' => 'required|string',
                'nomor_rekening' => 'required|string',
                'saldo_awal' => 'required|numeric',
                'total_pemasukan' => 'required|numeric',
                'total_pengeluaran' => 'required|numeric',
                'file_bank_statement' => 'nullable|file|mimes:pdf,xlsx,csv',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            $saldoAkhir = $request->saldo_awal + $request->total_pemasukan - $request->total_pengeluaran;
            $saldoSistem = PembayaranSpp::where('status', 'Lunas')->sum('jumlah_bayar');
            $selisih = $saldoSistem - $saldoAkhir;

            $filePath = null;
            if ($request->hasFile('file_bank_statement')) {
                $file = $request->file('file_bank_statement');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('rekonsiliasi', $filename, 'public');
            }

            $rekonsiliasi = RekonsiliasiBank::create([
                'tanggal_rekonsiliasi' => now(),
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'saldo_awal' => $request->saldo_awal,
                'total_pemasukan' => $request->total_pemasukan,
                'total_pengeluaran' => $request->total_pengeluaran,
                'saldo_akhir' => $saldoAkhir,
                'saldo_sistem' => $saldoSistem,
                'selisih' => $selisih,
                'status' => abs($selisih) < 1 ? 'Match' : 'Selisih',
                'file_statement' => $filePath,
                'dibuat_oleh' => Auth::id(),
            ]);

            if (class_exists(LogHelper::class)) {
                LogHelper::log('create', 'Rekonsiliasi bank baru dibuat', 'rekonsiliasi_bank', $rekonsiliasi->id_rekonsiliasi, Auth::id());
            }

            return response()->json([
                'success' => true,
                'message' => 'Rekonsiliasi berhasil dibuat',
                'data' => $rekonsiliasi
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Financial Forecasting - Prediksi cash flow
     * GET /api/bendahara/forecasting
     */
    public function apiForecasting(Request $request)
    {
        try {
            $bulan = (int) $request->input('bulan', 6);

            $rataRataPerBulan = DB::table('pembayaran_spp')
                ->where('status', 'Lunas')
                ->where('tgl_bayar', '>=', now()->subMonths(6))
                ->select(
                    DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m') as bulan"),
                    DB::raw('SUM(jumlah_bayar) as total')
                )
                ->groupBy(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"))
                ->orderBy('bulan')
                ->get()
                ->avg('total') ?? 0;

            $proyeksi = [];
            for ($i = 1; $i <= $bulan; $i++) {
                $proyeksi[] = [
                    'bulan' => now()->addMonths($i)->format('Y-m'),
                    'proyeksi_pemasukan' => round($rataRataPerBulan, 2),
                ];
            }

            $tunggakanAktif = (float) PembayaranSpp::where('status', 'Belum Lunas')->sum('jumlah_bayar');

            $data = [
                'rata_rata_per_bulan' => round($rataRataPerBulan, 2),
                'proyeksi_6_bulan' => $proyeksi,
                'tunggakan_aktif' => $tunggakanAktif,
                'potensi_total_pemasukan' => round($rataRataPerBulan * $bulan, 2),
            ];

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
     * Revenue Analytics - Grafik pemasukan per kategori
     * GET /api/bendahara/revenue-analytics
     */
    public function apiRevenueAnalytics(Request $request)
    {
        try {
            $tahun = $request->input('tahun', date('Y'));

            $perMetode = PembayaranSpp::where('status', 'Lunas')
                ->whereYear('tgl_bayar', $tahun)
                ->select('metode_pembayaran', DB::raw('SUM(jumlah_bayar) as total'))
                ->groupBy('metode_pembayaran')
                ->get()
                ->map(function($m) {
                    return [
                        'metode_pembayaran' => $m->metode_pembayaran ?: 'Lainnya',
                        'total' => (float) $m->total
                    ];
                });

            $perKelas = DB::table('pembayaran_spp')
                ->join('siswa', 'pembayaran_spp.siswa_id', '=', 'siswa.id_siswa')
                ->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id_kelas')
                ->where('pembayaran_spp.status', 'Lunas')
                ->whereYear('pembayaran_spp.tgl_bayar', $tahun)
                ->select(DB::raw("COALESCE(kelas.nama_kelas, 'Lainnya') as nama_kelas"), DB::raw('SUM(pembayaran_spp.jumlah_bayar) as total'))
                ->groupBy(DB::raw("COALESCE(kelas.nama_kelas, 'Lainnya')"))
                ->get();

            $trendRaw = DB::table('pembayaran_spp')
                ->where('status', 'Lunas')
                ->whereNotNull('tgl_bayar')
                ->whereYear('tgl_bayar', $tahun)
                ->select(
                    DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m') as bulan"),
                    DB::raw('SUM(jumlah_bayar) as total')
                )
                ->groupBy(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"))
                ->pluck('total', 'bulan')
                ->all();

            $trendBulanan = [];
            for ($m = 1; $m <= 12; $m++) {
                $key = sprintf('%s-%02d', $tahun, $m);
                $trendBulanan[] = [
                    'bulan' => $key,
                    'total' => (float) ($trendRaw[$key] ?? 0),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'per_metode' => $perMetode,
                    'per_kelas' => $perKelas,
                    'trend_bulanan' => $trendBulanan,
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
     * Generate QR Code Payment
     * POST /api/bendahara/generate-qr
     */
    public function apiGenerateQR(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pembayaran_id' => 'required|exists:pembayaran_spp,id_pembayaran',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            $pembayaran = PembayaranSpp::find($request->pembayaran_id);

            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode("PAY:{$pembayaran->id_pembayaran}:{$pembayaran->jumlah_bayar}");

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil dibuat',
                'data' => [
                    'qr_code_url' => $qrCodeUrl,
                    'pembayaran_id' => $pembayaran->id_pembayaran,
                    'jumlah' => $pembayaran->jumlah_bayar
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

