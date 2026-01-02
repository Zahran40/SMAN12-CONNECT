<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BendaharaController extends Controller
{
    public function beranda()
    {
        // Statistik keuangan untuk dashboard
        $bulanIni = Carbon::now()->format('Y-m');
        
        $stats = [
            'total_pemasukan_bulan_ini' => PembayaranSpp::where('status', 'lunas')
                ->whereRaw("DATE_FORMAT(tanggal_bayar, '%Y-%m') = ?", [$bulanIni])
                ->sum('jumlah'),
            
            'total_tunggakan' => PembayaranSpp::where('status', 'belum bayar')
                ->sum('jumlah'),
            
            'pembayaran_pending' => PembayaranSpp::where('status', 'pending')->count(),
            
            'total_lunas_bulan_ini' => PembayaranSpp::where('status', 'lunas')
                ->whereRaw("DATE_FORMAT(tanggal_bayar, '%Y-%m') = ?", [$bulanIni])
                ->count(),
        ];
        
        return view('Bendahara.beranda', compact('stats'));
    }
}
