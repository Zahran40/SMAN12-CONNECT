<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;

class GuruController extends Controller
{
    public function beranda()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        // Get current day in Indonesian
        $hariIni = Carbon::now()->locale('id')->dayName;
        
        // Get all days for tabs
        $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        // Get all schedules for the week (grouped by day) - USING VIEW
        $jadwalPerHari = [];
        foreach ($allDays as $hari) {
            $jadwalPerHari[$hari] = DB::table('view_jadwal_mengajar')
                ->where('guru_id', $guru->id_guru)
                ->where('hari', $hari)
                ->get();
        }
        
        // Get today's schedule - USING VIEW
        $jadwalHariIni = DB::table('view_jadwal_mengajar')
            ->where('guru_id', $guru->id_guru)
            ->where('hari', $hariIni)
            ->get();
        
        // Ambil pengumuman aktif menggunakan sp_get_pengumuman_aktif
        $pengumuman = DB::select('CALL sp_get_pengumuman_aktif(?)', ['guru']);
        
        return view('Guru.beranda', compact('guru', 'hariIni', 'allDays', 'jadwalPerHari', 'jadwalHariIni', 'pengumuman'));
    }
    
    public function profil()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        return view('Guru.profil', compact('guru'));
    }
}
