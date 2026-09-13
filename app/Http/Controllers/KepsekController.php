<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\PembayaranSpp;
use App\Models\Pengumuman;
use App\Models\EvaluasiKinerjaGuru;
use App\Models\TargetSekolah;
use App\Models\DokumenDigital;
use Illuminate\Support\Facades\DB;

class KepsekController extends Controller
{
    /**
     * Dashboard Kepala Sekolah - Executive KPI Overview
     */
    public function beranda()
    {
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();

        // KPI Dasar
        $stats = [
            'total_siswa' => Siswa::count(),
            'total_guru' => Guru::count(),
            'total_kelas' => Kelas::count(),
            'tahun_ajaran' => $tahunAjaranAktif
                ? $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai . ' - ' . $tahunAjaranAktif->semester
                : 'Tidak ada',
        ];

        // KPI Kehadiran Siswa (30 hari terakhir)
        $rekapKehadiran = DB::table('detail_absensi')
            ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
            ->where('pertemuan.tanggal_pertemuan', '>=', now()->subDays(30))
            ->select(
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir")
            )
            ->first();

        $stats['persentase_kehadiran'] = $rekapKehadiran->total > 0
            ? round(($rekapKehadiran->hadir / $rekapKehadiran->total) * 100, 2)
            : 0;

        // KPI Keuangan
        $stats['total_pendapatan_tahun_ini'] = PembayaranSpp::where('status', 'Lunas')
            ->whereYear('tgl_bayar', date('Y'))
            ->sum('jumlah_bayar');

        $stats['total_tunggakan'] = PembayaranSpp::where('status', 'Belum Lunas')
            ->sum('jumlah_bayar');

        // KPI Nilai Rata-rata
        if ($tahunAjaranAktif) {
            $stats['nilai_rata_rata'] = round(
                DB::table('nilai')
                    ->where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
                    ->avg('nilai_akhir') ?? 0,
                2
            );

            // Target Sekolah
            $stats['target_berjalan'] = TargetSekolah::where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
                ->where('status', 'Sedang Berjalan')->count();
            $stats['target_tercapai'] = TargetSekolah::where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
                ->where('status', 'Tercapai')->count();
        } else {
            $stats['nilai_rata_rata'] = 0;
            $stats['target_berjalan'] = 0;
            $stats['target_tercapai'] = 0;
        }

        // Pengumuman terbaru
        $pengumumanTerbaru = Pengumuman::where('status', 'aktif')
            ->orderBy('tgl_publikasi', 'desc')
            ->limit(5)
            ->get();

        // Dokumen menunggu tanda tangan
        $dokumenPending = DokumenDigital::where('status', 'Menunggu Tanda Tangan')->count();

        return view('Kepsek.beranda', compact('stats', 'tahunAjaranAktif', 'pengumumanTerbaru', 'dokumenPending'));
    }

    /**
     * Laporan Akademik Global - Rekap nilai per kelas & mapel
     */
    public function laporanAkademikGlobal()
    {
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $daftarTahunAjaran = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('Kepsek.laporan-akademik-global', compact(
            'tahunAjaranAktif',
            'daftarTahunAjaran',
            'daftarKelas'
        ));
    }

    /**
     * Monitoring Presensi - Statistik kehadiran seluruh sekolah
     */
    public function monitoringPresensi()
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        // Ringkasan kehadiran hari ini
        $kehadiranHariIni = DB::table('detail_absensi')
            ->join('pertemuan', 'detail_absensi.pertemuan_id', '=', 'pertemuan.id_pertemuan')
            ->whereDate('pertemuan.tanggal_pertemuan', today())
            ->select(
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Izin' THEN 1 ELSE 0 END) as izin"),
                DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) as sakit"),
                DB::raw("SUM(CASE WHEN detail_absensi.status_kehadiran = 'Alpa' THEN 1 ELSE 0 END) as alpa")
            )
            ->first();

        return view('Kepsek.monitoring-presensi', compact('daftarKelas', 'kehadiranHariIni'));
    }

    /**
     * Laporan Keuangan - Rekap pembayaran SPP
     */
    public function laporanKeuangan()
    {
        $tahun = date('Y');

        // Ringkasan keuangan
        $ringkasan = [
            'total_pemasukan' => PembayaranSpp::where('status', 'Lunas')
                ->whereYear('tgl_bayar', $tahun)
                ->sum('jumlah_bayar'),
            'total_tunggakan' => PembayaranSpp::where('status', 'Belum Lunas')
                ->sum('jumlah_bayar'),
            'total_transaksi' => PembayaranSpp::where('status', 'Lunas')
                ->whereYear('tgl_bayar', $tahun)
                ->count(),
            'siswa_menunggak' => PembayaranSpp::where('status', 'Belum Lunas')
                ->distinct('siswa_id')
                ->count('siswa_id'),
        ];

        return view('Kepsek.laporan-keuangan', compact('ringkasan'));
    }

    /**
     * Analisis Trending - Grafik perkembangan per periode
     */
    public function analisisTrending()
    {
        $daftarTahunAjaran = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        return view('Kepsek.analisis-trending', compact('daftarTahunAjaran'));
    }

    /**
     * Export Reports - PDF/Excel
     */
    public function exportReports()
    {
        $daftarTahunAjaran = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        return view('Kepsek.export-reports', compact('daftarTahunAjaran'));
    }

    /**
     * Evaluasi Kinerja Guru - Penilaian & ranking guru
     */
    public function evaluasiKinerjaGuru()
    {
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $daftarGuru = Guru::orderBy('nama_lengkap')->get();
        $daftarTahunAjaran = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        // Top 5 guru berdasarkan kehadiran
        $topGuru = [];
        if ($tahunAjaranAktif) {
            $topGuru = EvaluasiKinerjaGuru::with(['guru'])
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
                ->orderBy('skor_total', 'desc')
                ->limit(5)
                ->get();
        }

        return view('Kepsek.evaluasi-kinerja-guru', compact(
            'tahunAjaranAktif',
            'daftarGuru',
            'daftarTahunAjaran',
            'topGuru'
        ));
    }

    /**
     * Manajemen Pengumuman - Approve & publish
     */
    public function manajemenPengumuman()
    {
        $pengumumanAktif = Pengumuman::where('status', 'aktif')
            ->orderBy('tgl_publikasi', 'desc')
            ->get();

        $pengumumanPending = Pengumuman::where('status', 'nonaktif')
            ->orderBy('tgl_publikasi', 'desc')
            ->get();

        return view('Kepsek.manajemen-pengumuman', compact('pengumumanAktif', 'pengumumanPending'));
    }
}
