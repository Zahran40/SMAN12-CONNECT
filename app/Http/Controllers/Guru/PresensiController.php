<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pertemuan;
use App\Models\DetailAbsensi;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class PresensiController extends Controller
{
    /**
     * Tampilkan list kelas dengan presensi aktif
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();

        // Get filter hari dari request (opsional)
        $hariFilter = $request->get('hari');
        
        // Daftar hari
        $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        // Tentukan tahun ajaran mana yang digunakan untuk query jadwal
        // Jika yang aktif adalah Genap, cari jadwal dari Ganjil (karena jadwal dibuat di Ganjil)
        $tahunAjaranForQuery = $tahunAjaranAktif->id_tahun_ajaran;
        
        if ($tahunAjaranAktif->semester === 'Genap') {
            $semesterGanjil = TahunAjaran::where('tahun_mulai', $tahunAjaranAktif->tahun_mulai)
                ->where('tahun_selesai', $tahunAjaranAktif->tahun_selesai)
                ->where('semester', 'Ganjil')
                ->first();
            
            if ($semesterGanjil) {
                $tahunAjaranForQuery = $semesterGanjil->id_tahun_ajaran;
            }
        }
        
        // Get jadwal guru untuk tahun ajaran aktif
        $query = JadwalPelajaran::with(['mataPelajaran', 'kelas', 'pertemuan'])
            ->where('guru_id', $guru->id_guru)
            ->where('tahun_ajaran_id', $tahunAjaranForQuery);
        
        // Terapkan filter hari jika ada
        if ($hariFilter) {
            $query->where('hari', $hariFilter);
        }
        
        $jadwalList = $query->orderBy('hari')->orderBy('jam_mulai')->get();

        return view('guru.presensi', compact('jadwalList', 'guru', 'hariFilter', 'daftarHari'));
    }

    /**
     * Tampilkan detail presensi untuk satu pertemuan
     */
    public function detail($pertemuanId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();
        
        $pertemuan = Pertemuan::with(['jadwal.mataPelajaran', 'jadwal.guru', 'jadwal.kelas'])
            ->findOrFail($pertemuanId);

        // Cek apakah guru mengajar kelas ini
        if ($pertemuan->jadwal->guru_id !== $guru->id_guru) {
            abort(403, 'Anda tidak memiliki akses ke pertemuan ini');
        }

        // Get all siswa di kelas dengan status absensi menggunakan siswa_kelas
        $siswaList = DB::table('siswa_kelas as sk')
            ->join('siswa as s', 'sk.siswa_id', '=', 's.id_siswa')
            ->leftJoin('detail_absensi as da', function($join) use ($pertemuanId) {
                $join->on('s.id_siswa', '=', 'da.siswa_id')
                     ->where('da.pertemuan_id', '=', $pertemuanId);
            })
            ->where('sk.kelas_id', $pertemuan->jadwal->kelas_id)
            ->where('sk.tahun_ajaran_id', $pertemuan->jadwal->tahun_ajaran_id)
            ->where('sk.status', 'Aktif')
            ->select(
                's.id_siswa',
                's.nisn',
                's.nama_lengkap',
                'da.id_detail_absensi',
                'da.status_kehadiran',
                'da.keterangan',
                'da.dicatat_pada',
                'da.latitude',
                'da.longitude',
                'da.alamat_lengkap'
            )
            ->orderBy('s.nama_lengkap')
            ->get();

        return view('guru.detailpresensi', compact('pertemuan', 'siswaList', 'guru'));
    }

    /**
     * Update status absensi siswa
     */
    public function updateStatus(Request $request, $pertemuanId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();
        
        $pertemuan = Pertemuan::with('jadwal')->findOrFail($pertemuanId);

        // Cek akses
        if ($pertemuan->jadwal->guru_id !== $guru->id_guru && $user->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        // Cek apakah sudah di-submit (hanya admin yang bisa edit setelah submit)
        if (!$pertemuan->canEditAbsensi($user)) {
            return redirect()->back()->with('error', 'Absensi sudah di-submit. Hanya admin yang bisa mengubah.');
        }

        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id_siswa',
            'status_kehadiran' => 'required|in:Hadir,Sakit,Izin,Alfa',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Cek apakah siswa ada di kelas yang benar
        $siswa = DB::table('siswa')
            ->where('id_siswa', $validated['siswa_id'])
            ->where('kelas_id', $pertemuan->jadwal->kelas_id)
            ->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan di kelas ini');
        }

        // Update or create absensi
        $absensi = DetailAbsensi::updateOrCreate(
            [
                'pertemuan_id' => $pertemuanId,
                'siswa_id' => $validated['siswa_id']
            ],
            [
                'status_kehadiran' => $validated['status_kehadiran'],
                'keterangan' => $validated['keterangan'],
                'dicatat_pada' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Status absensi berhasil diperbarui');
    }

    /**
     * Submit absensi (lock it)
     */
    public function submit($pertemuanId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();
        
        $pertemuan = Pertemuan::with('jadwal')->findOrFail($pertemuanId);

        // Cek akses
        if ($pertemuan->jadwal->guru_id !== $guru->id_guru) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        // Cek apakah sudah di-submit
        if ($pertemuan->is_submitted) {
            return redirect()->back()->with('info', 'Absensi sudah di-submit sebelumnya');
        }

        // Submit absensi
        $pertemuan->update([
            'is_submitted' => true,
            'submitted_at' => now(),
            'submitted_by' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil di-submit. Data terkunci.');
    }

    /**
     * Buka kembali absensi (unlock) - hanya untuk admin
     */
    public function unlock($pertemuanId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang bisa membuka kembali absensi');
        }

        $pertemuan = Pertemuan::findOrFail($pertemuanId);

        $pertemuan->update([
            'is_submitted' => false,
            'submitted_at' => null,
            'submitted_by' => null,
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil dibuka kembali');
    }

    /**
     * Update waktu/tanggal pertemuan
     */
    public function updateWaktu(Request $request, $pertemuanId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();
        
        $pertemuan = Pertemuan::with('jadwal')->findOrFail($pertemuanId);

        // Cek akses
        if ($pertemuan->jadwal->guru_id !== $guru->id_guru && $user->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        $validated = $request->validate([
            'tanggal_pertemuan' => 'required|date',
            'jam_absen_buka' => 'required|date_format:H:i',
            'jam_absen_tutup' => 'required|date_format:H:i|after:jam_absen_buka',
        ]);

        // Update pertemuan
        $pertemuan->update([
            'tanggal_pertemuan' => $validated['tanggal_pertemuan'],
            'tanggal_absen_dibuka' => $validated['tanggal_pertemuan'],
            'tanggal_absen_ditutup' => $validated['tanggal_pertemuan'],
            'jam_absen_buka' => $validated['jam_absen_buka'],
            'jam_absen_tutup' => $validated['jam_absen_tutup'],
            'waktu_absen_dibuka' => $validated['tanggal_pertemuan'] . ' ' . $validated['jam_absen_buka'],
            'waktu_absen_ditutup' => $validated['tanggal_pertemuan'] . ' ' . $validated['jam_absen_tutup'],
        ]);

        return redirect()->back()->with('success', 'Waktu pertemuan berhasil diperbarui');
    }

    /**
     * Buat pertemuan baru untuk jadwal tertentu
     */
    public function buatPertemuan(Request $request, $jadwalId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();
        
        $jadwal = JadwalPelajaran::findOrFail($jadwalId);

        // Cek akses
        if ($jadwal->guru_id !== $guru->id_guru) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        $validated = $request->validate([
            'nomor_pertemuan' => 'required|integer|min:1|max:16',
            'tanggal_pertemuan' => 'required|date',
            'jam_absen_buka' => 'required|date_format:H:i',
            'jam_absen_tutup' => 'required|date_format:H:i|after:jam_absen_buka',
            'topik_bahasan' => 'nullable|string|max:250',
        ]);

        // Cari pertemuan yang sudah ada (auto-generated)
        $pertemuan = Pertemuan::where('jadwal_id', $jadwalId)
            ->where('nomor_pertemuan', $validated['nomor_pertemuan'])
            ->first();

        if (!$pertemuan) {
            return redirect()->back()->with('error', 'Slot pertemuan tidak ditemukan');
        }

        // Cek apakah pertemuan ini sudah diisi (punya tanggal)
        if ($pertemuan->tanggal_pertemuan) {
            return redirect()->back()->with('error', 'Pertemuan ke-' . $validated['nomor_pertemuan'] . ' sudah terisi. Silakan pilih nomor pertemuan lain.');
        }

        // Update pertemuan yang sudah ada
        $pertemuan->update([
            'tanggal_pertemuan' => $validated['tanggal_pertemuan'],
            'tanggal_absen_dibuka' => $validated['tanggal_pertemuan'],
            'tanggal_absen_ditutup' => $validated['tanggal_pertemuan'],
            'jam_absen_buka' => $validated['jam_absen_buka'],
            'jam_absen_tutup' => $validated['jam_absen_tutup'],
            'waktu_absen_dibuka' => $validated['tanggal_pertemuan'] . ' ' . $validated['jam_absen_buka'],
            'waktu_absen_ditutup' => $validated['tanggal_pertemuan'] . ' ' . $validated['jam_absen_tutup'],
            'topik_bahasan' => $validated['topik_bahasan'],
        ]);

        return redirect()->route('guru.detail_presensi', $pertemuan->id_pertemuan)
            ->with('success', 'Pertemuan berhasil dibuat dan absensi dibuka');
    }

    /**
     * Tampilkan list 16 pertemuan (slot) untuk jadwal tertentu
     */
    public function listPertemuan($jadwalId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();
        
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwalId);

        // Cek akses
        if ($jadwal->guru_id !== $guru->id_guru) {
            abort(403, 'Tidak memiliki akses');
        }

        // Get semua pertemuan yang sudah terisi
        $pertemuanTerisi = Pertemuan::where('jadwal_id', $jadwalId)
            ->orderBy('nomor_pertemuan')
            ->get()
            ->keyBy('nomor_pertemuan');

        // Buat array 16 slot pertemuan
        $slotPertemuan = [];
        for ($i = 1; $i <= 16; $i++) {
            $slotPertemuan[$i] = $pertemuanTerisi->get($i);
        }

        return view('guru.listpertemuan', compact('jadwal', 'slotPertemuan', 'guru'));
    }

    /**
     * Get slot pertemuan yang tersedia (kosong) untuk jadwal tertentu
     */
    public function getSlotTersedia($jadwalId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();
        
        $jadwal = JadwalPelajaran::findOrFail($jadwalId);

        // Cek akses
        if ($jadwal->guru_id !== $guru->id_guru) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get pertemuan yang sudah terisi (punya tanggal)
        $pertemuanTerisi = Pertemuan::where('jadwal_id', $jadwalId)
            ->whereNotNull('tanggal_pertemuan')
            ->pluck('nomor_pertemuan')
            ->toArray();

        // Buat array slot kosong (1-16 yang belum punya tanggal)
        $slotKosong = [];
        for ($i = 1; $i <= 16; $i++) {
            if (!in_array($i, $pertemuanTerisi)) {
                $slotKosong[] = $i;
            }
        }

        return response()->json([
            'slotKosong' => $slotKosong,
            'slotTerisi' => $pertemuanTerisi,
            'totalKosong' => count($slotKosong),
            'totalTerisi' => count($pertemuanTerisi)
        ]);
    }

    /**
     * Rekap Absensi Kelas menggunakan Stored Procedure (MSBD)
     */
    public function rekapAbsensiKelas($jadwalId)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas', 'guru'])->findOrFail($jadwalId);
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        // Tanggal awal dan akhir (periode 1 semester)
        $tanggalAwal = $tahunAjaranAktif->tanggal_mulai;
        $tanggalAkhir = $tahunAjaranAktif->tanggal_selesai;
        
        // Panggil Stored Procedure sp_rekap_absensi_kelas (MSBD)
        try {
            $rekapAbsensi = DB::select('CALL sp_rekap_absensi_kelas(?)', [$jadwal->id_jadwal]);
            
            // Tambahkan persentase kehadiran menggunakan Function fn_hadir_persen (MSBD)
            foreach ($rekapAbsensi as $rekap) {
                try {
                    $persentase = DB::select('SELECT fn_hadir_persen(?, ?) as persen', [
                        $rekap->id_siswa,
                        $tahunAjaranAktif->id_tahun_ajaran
                    ]);
                    $rekap->persen_hadir = $persentase[0]->persen ?? 0;
                } catch (\Exception $e) {
                    // Fallback: hitung manual
                    $rekap->persen_hadir = $rekap->total_pertemuan > 0 
                        ? ($rekap->hadir / $rekap->total_pertemuan) * 100 
                        : 0;
                }
            }
        } catch (\Exception $e) {
            // Fallback jika SP error
            Log::warning("SP sp_rekap_absensi_kelas error: " . $e->getMessage());
            
            $siswaList = DB::table('siswa')
                ->where('kelas_id', $jadwal->kelas_id)
                ->select('id_siswa', 'nis', 'nama_lengkap')
                ->get();
            
            $rekapAbsensi = [];
            foreach ($siswaList as $siswa) {
                $totalPertemuan = DB::table('pertemuan')
                    ->where('jadwal_id', $jadwal->id_jadwal)
                    ->where('is_submitted', 1)
                    ->count();
                
                $statusAbsensi = DB::table('detail_absensi as da')
                    ->join('pertemuan as p', 'da.pertemuan_id', '=', 'p.id_pertemuan')
                    ->where('da.siswa_id', $siswa->id_siswa)
                    ->where('p.jadwal_id', $jadwal->id_jadwal)
                    ->where('p.is_submitted', 1)
                    ->select(
                        DB::raw('SUM(CASE WHEN da.status_kehadiran = "Hadir" THEN 1 ELSE 0 END) as hadir'),
                        DB::raw('SUM(CASE WHEN da.status_kehadiran = "Izin" THEN 1 ELSE 0 END) as izin'),
                        DB::raw('SUM(CASE WHEN da.status_kehadiran = "Sakit" THEN 1 ELSE 0 END) as sakit'),
                        DB::raw('SUM(CASE WHEN da.status_kehadiran = "Alfa" THEN 1 ELSE 0 END) as alfa')
                    )
                    ->first();
                
                $hadir = $statusAbsensi->hadir ?? 0;
                $persenHadir = $totalPertemuan > 0 ? ($hadir / $totalPertemuan) * 100 : 0;
                
                $rekapAbsensi[] = (object)[
                    'id_siswa' => $siswa->id_siswa,
                    'nis' => $siswa->nis,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'total_pertemuan' => $totalPertemuan,
                    'hadir' => $statusAbsensi->hadir ?? 0,
                    'izin' => $statusAbsensi->izin ?? 0,
                    'sakit' => $statusAbsensi->sakit ?? 0,
                    'alfa' => $statusAbsensi->alfa ?? 0,
                    'persen_hadir' => $persenHadir
                ];
            }
        }
        
        return view('Guru.rekapAbsensi', compact('jadwal', 'rekapAbsensi', 'tahunAjaranAktif', 'tanggalAwal', 'tanggalAkhir'));
    }
    
    /**
     * Export Rekap Absensi ke Excel
     */
    public function exportRekapAbsensi($jadwalId)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas', 'guru'])->findOrFail($jadwalId);
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        // Tanggal awal dan akhir
        $tanggalAwal = $tahunAjaranAktif->tanggal_mulai;
        $tanggalAkhir = $tahunAjaranAktif->tanggal_selesai;
        
        // Panggil Stored Procedure sp_rekap_absensi_kelas (MSBD)
        try {
            $rekapAbsensi = DB::select('CALL sp_rekap_absensi_kelas(?)', [$jadwal->id_jadwal]);
            
            // Tambahkan persentase kehadiran menggunakan Function fn_hadir_persen (MSBD)
            foreach ($rekapAbsensi as $rekap) {
                try {
                    $persentase = DB::select('SELECT fn_hadir_persen(?, ?) as persen', [
                        $rekap->id_siswa,
                        $tahunAjaranAktif->id_tahun_ajaran
                    ]);
                    $rekap->persen_hadir = $persentase[0]->persen ?? 0;
                } catch (\Exception $e) {
                    $rekap->persen_hadir = $rekap->total_pertemuan > 0 
                        ? ($rekap->hadir / $rekap->total_pertemuan) * 100 
                        : 0;
                }
            }
        } catch (\Exception $e) {
            // Fallback jika SP error
            $siswaList = DB::table('siswa')
                ->where('kelas_id', $jadwal->kelas_id)
                ->select('id_siswa', 'nis', 'nama_lengkap')
                ->get();
            
            $rekapAbsensi = [];
            foreach ($siswaList as $siswa) {
                $totalPertemuan = DB::table('pertemuan')
                    ->where('jadwal_id', $jadwal->id_jadwal)
                    ->where('is_submitted', 1)
                    ->count();
                
                $statusAbsensi = DB::table('detail_absensi as da')
                    ->join('pertemuan as p', 'da.pertemuan_id', '=', 'p.id_pertemuan')
                    ->where('da.siswa_id', $siswa->id_siswa)
                    ->where('p.jadwal_id', $jadwal->id_jadwal)
                    ->where('p.is_submitted', 1)
                    ->select(
                        DB::raw('SUM(CASE WHEN da.status_kehadiran = "Hadir" THEN 1 ELSE 0 END) as hadir'),
                        DB::raw('SUM(CASE WHEN da.status_kehadiran = "Izin" THEN 1 ELSE 0 END) as izin'),
                        DB::raw('SUM(CASE WHEN da.status_kehadiran = "Sakit" THEN 1 ELSE 0 END) as sakit'),
                        DB::raw('SUM(CASE WHEN da.status_kehadiran = "Alfa" THEN 1 ELSE 0 END) as alfa')
                    )
                    ->first();
                
                $hadir = $statusAbsensi->hadir ?? 0;
                $persenHadir = $totalPertemuan > 0 ? ($hadir / $totalPertemuan) * 100 : 0;
                
                $rekapAbsensi[] = (object)[
                    'id_siswa' => $siswa->id_siswa,
                    'nis' => $siswa->nis,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'total_pertemuan' => $totalPertemuan,
                    'hadir' => $statusAbsensi->hadir ?? 0,
                    'izin' => $statusAbsensi->izin ?? 0,
                    'sakit' => $statusAbsensi->sakit ?? 0,
                    'alfa' => $statusAbsensi->alfa ?? 0,
                    'persen_hadir' => $persenHadir
                ];
            }
        }
        
        // Buat CSV file
        $filename = 'Rekap_Absensi_' . $jadwal->mataPelajaran->nama_mapel . '_' . $jadwal->kelas->nama_kelas . '_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];
        
        $callback = function() use ($rekapAbsensi, $jadwal, $tanggalAwal, $tanggalAkhir) {
            $file = fopen('php://output', 'w');
            
            // BOM untuk UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header Info
            fputcsv($file, ['REKAP ABSENSI SISWA']);
            fputcsv($file, ['Mata Pelajaran', $jadwal->mataPelajaran->nama_mapel]);
            fputcsv($file, ['Kelas', $jadwal->kelas->nama_kelas]);
            fputcsv($file, ['Guru Pengampu', $jadwal->guru->nama_lengkap]);
            fputcsv($file, ['Periode', \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y')]);
            fputcsv($file, ['']);
            
            // Header Tabel
            fputcsv($file, ['No', 'NIS', 'Nama Siswa', 'Total Pertemuan', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'Persentase Kehadiran']);
            
            // Data
            foreach ($rekapAbsensi as $index => $rekap) {
                fputcsv($file, [
                    $index + 1,
                    $rekap->nis,
                    $rekap->nama_lengkap,
                    $rekap->total_pertemuan ?? 0,
                    $rekap->hadir ?? 0,
                    $rekap->izin ?? 0,
                    $rekap->sakit ?? 0,
                    $rekap->alfa ?? 0,
                    number_format($rekap->persen_hadir ?? 0, 2) . '%'
                ]);
            }
            
            // Footer
            fputcsv($file, ['']);
            fputcsv($file, ['Keterangan:']);
            fputcsv($file, ['Rumus Persentase Kehadiran', '(Total Hadir / Total Pertemuan) x 100']);
            fputcsv($file, ['Kategori Sangat Baik', '>= 80%']);
            fputcsv($file, ['Kategori Cukup', '60-79%']);
            fputcsv($file, ['Kategori Perlu Perhatian', '< 60%']);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
