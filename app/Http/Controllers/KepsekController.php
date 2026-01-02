<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\DB;

class KepsekController extends Controller
{
    public function beranda()
    {
        // Statistik sekolah untuk dashboard
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        
        $stats = [
            'total_siswa' => Siswa::count(),
            'total_guru' => Guru::count(),
            'total_kelas' => Kelas::count(),
            'tahun_ajaran' => $tahunAjaranAktif ? $tahunAjaranAktif->tahun . ' - ' . $tahunAjaranAktif->semester : 'Tidak ada',
        ];
        
        return view('Kepsek.beranda', compact('stats', 'tahunAjaranAktif'));
    }
}
