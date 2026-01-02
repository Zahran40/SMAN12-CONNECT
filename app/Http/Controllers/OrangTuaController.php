<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\User;

class OrangTuaController extends Controller
{
    public function beranda()
    {
        $user = Auth::user();
        
        // Ambil data siswa yang terkait dengan orang tua ini
        // Asumsi: reference_id di user orangtua = siswa_id
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
}
