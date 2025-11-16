<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Pertemuan;
use App\Models\DetailAbsensi;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function beranda()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        // Get presensi yang sedang berlangsung hari ini
        $hari = Carbon::now()->locale('id')->dayName;
        $today = Carbon::now()->toDateString();
        
        $presensiAktif = Pertemuan::with(['jadwal.mataPelajaran', 'jadwal.guru'])
            ->whereHas('jadwal', function($q) use ($siswa, $hari) {
                $q->where('kelas_id', $siswa->kelas_id)
                  ->where('hari', $hari);
            })
            ->whereDate('tanggal_pertemuan', $today)
            ->whereNotNull('waktu_absen_dibuka')
            ->whereNotNull('waktu_absen_ditutup')
            ->get()
            ->filter(function($pertemuan) {
                return $pertemuan->isAbsensiOpen();
            });
        
        // Cek status absensi masing-masing
        foreach ($presensiAktif as $pertemuan) {
            $pertemuan->sudah_absen = DetailAbsensi::where('pertemuan_id', $pertemuan->id_pertemuan)
                ->where('siswa_id', $siswa->id_siswa)
                ->exists();
        }
        
        return view('siswa.beranda', compact('siswa', 'presensiAktif'));
    }
    
    public function profil()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        return view('siswa.profil', compact('siswa'));
    }
}
