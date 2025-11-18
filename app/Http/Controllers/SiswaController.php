<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Pertemuan;
use App\Models\DetailAbsensi;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function beranda()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        // Get current day in Indonesian
        $hariIni = Carbon::now()->locale('id')->dayName;
        $today = Carbon::now()->toDateString();
        
        // Get all days for tabs
        $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        // Get all schedules for the week (grouped by day) - USING VIEW
        $jadwalPerHari = [];
        foreach ($allDays as $hari) {
            $jadwalPerHari[$hari] = DB::table('view_jadwal_siswa')
                ->where('kelas_id', $siswa->kelas_id)
                ->where('hari', $hari)
                ->get();
        }
        
        // Get presensi yang sedang berlangsung hari ini - USING VIEW
        $presensiAktif = DB::table('view_presensi_aktif')
            ->where('kelas_id', $siswa->kelas_id)
            ->where('hari', $hariIni)
            ->where('is_open', 1)
            ->get()
            ->map(function($pertemuan) use ($siswa) {
                // Cek apakah siswa sudah absen
                $sudahAbsen = DetailAbsensi::where('pertemuan_id', $pertemuan->id_pertemuan)
                    ->where('siswa_id', $siswa->id_siswa)
                    ->exists();
                
                $pertemuan->sudah_absen = $sudahAbsen;
                return $pertemuan;
            });
        
        return view('siswa.beranda', compact('siswa', 'presensiAktif', 'hariIni', 'allDays', 'jadwalPerHari'));
    }
    
    public function profil()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        return view('siswa.profil', compact('siswa'));
    }
}
