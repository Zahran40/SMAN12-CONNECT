<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pertemuan;
use App\Models\DetailAbsensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * Tampilkan daftar mata pelajaran (card view seperti materi)
     */
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();

        // Get semua jadwal pelajaran di kelas siswa
        $jadwalList = DB::table('jadwal_pelajaran')
            ->join('mata_pelajaran', 'jadwal_pelajaran.mapel_id', '=', 'mata_pelajaran.id_mapel')
            ->join('guru', 'jadwal_pelajaran.guru_id', '=', 'guru.id_guru')
            ->where('jadwal_pelajaran.kelas_id', $siswa->kelas_id)
            ->select(
                'jadwal_pelajaran.id_jadwal',
                'mata_pelajaran.nama_mapel',
                'mata_pelajaran.id_mapel',
                'guru.nama_lengkap as nama_guru',
                'jadwal_pelajaran.hari',
                'jadwal_pelajaran.jam_mulai',
                'jadwal_pelajaran.jam_selesai'
            )
            ->orderBy('jadwal_pelajaran.hari')
            ->orderBy('jadwal_pelajaran.jam_mulai')
            ->get();

        return view('siswa.presensi', compact('jadwalList', 'siswa'));
    }

    /**
     * Tampilkan list pertemuan AKTIF untuk mapel tertentu
     */
    public function listPertemuan($jadwalId)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        
        $jadwal = DB::table('jadwal_pelajaran')
            ->join('mata_pelajaran', 'jadwal_pelajaran.mapel_id', '=', 'mata_pelajaran.id_mapel')
            ->join('guru', 'jadwal_pelajaran.guru_id', '=', 'guru.id_guru')
            ->join('kelas', 'jadwal_pelajaran.kelas_id', '=', 'kelas.id_kelas')
            ->where('jadwal_pelajaran.id_jadwal', $jadwalId)
            ->where('jadwal_pelajaran.kelas_id', $siswa->kelas_id)
            ->select(
                'jadwal_pelajaran.*',
                'mata_pelajaran.nama_mapel',
                'guru.nama_lengkap as nama_guru',
                'kelas.nama_kelas'
            )
            ->first();

        if (!$jadwal) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini');
        }

        $now = Carbon::now();

                // Get semua pertemuan yang sudah dijadwalkan (tanggal_pertemuan sudah diisi)
        $now = Carbon::now();
        $pertemuanAktif = DB::table('pertemuan')
            ->where('pertemuan.jadwal_id', $jadwalId)
            ->whereNotNull('pertemuan.tanggal_pertemuan')
            ->leftJoin('detail_absensi', function($join) use ($siswa) {
                $join->on('pertemuan.id_pertemuan', '=', 'detail_absensi.pertemuan_id')
                     ->where('detail_absensi.siswa_id', '=', $siswa->id_siswa);
            })
            ->select(
                'pertemuan.*',
                'detail_absensi.status_kehadiran',
                'detail_absensi.dicatat_pada'
            )
            ->orderBy('pertemuan.nomor_pertemuan')
            ->get();

        return view('siswa.listpresensi', compact('jadwal', 'pertemuanAktif', 'siswa'));
    }

    /**
     * Tampilkan detail presensi untuk satu pertemuan
     */
    public function detail($pertemuanId)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        
        $pertemuan = Pertemuan::with(['jadwal.mataPelajaran', 'jadwal.guru', 'jadwal.kelas'])
            ->findOrFail($pertemuanId);

        // Cek apakah siswa ada di kelas ini
        if ($pertemuan->jadwal->kelas_id !== $siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke pertemuan ini');
        }

        // Get absensi record
        $absensi = DetailAbsensi::where('pertemuan_id', $pertemuanId)
            ->where('siswa_id', $siswa->id_siswa)
            ->first();

        return view('siswa.detailpresensi', compact('pertemuan', 'absensi', 'siswa'));
    }

    /**
     * Siswa melakukan absensi (one-click "Hadir")
     */
    public function absen($pertemuanId)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        
        $pertemuan = Pertemuan::with('jadwal')->findOrFail($pertemuanId);

        // Cek apakah siswa ada di kelas ini
        if ($pertemuan->jadwal->kelas_id !== $siswa->kelas_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pertemuan ini');
        }

        // Cek apakah waktu absensi sudah dibuka
        if (!$pertemuan->isAbsensiOpen()) {
            $status = 'belum dibuka';
            if ($pertemuan->waktu_absen_dibuka && now()->lessThan($pertemuan->waktu_absen_dibuka)) {
                $status = 'belum dibuka';
            } else if ($pertemuan->waktu_absen_ditutup && now()->greaterThan($pertemuan->waktu_absen_ditutup)) {
                $status = 'sudah ditutup';
            }
            return redirect()->back()->with('error', "Waktu absensi $status");
        }

        // Cek apakah sudah absen
        $existingAbsensi = DetailAbsensi::where('pertemuan_id', $pertemuanId)
            ->where('siswa_id', $siswa->id_siswa)
            ->first();

        if ($existingAbsensi) {
            return redirect()->back()->with('info', 'Anda sudah melakukan absensi sebelumnya');
        }

        // Create absensi dengan status Hadir
        DetailAbsensi::create([
            'pertemuan_id' => $pertemuanId,
            'siswa_id' => $siswa->id_siswa,
            'status_kehadiran' => 'Hadir',
            'keterangan' => null,
            'dicatat_pada' => now(),
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil! Status: Hadir');
    }
}
