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
        DetailAbsensi::updateOrCreate(
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
}
