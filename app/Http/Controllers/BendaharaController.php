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

    public function manajemenTagihanSpp()
    {
        return view('Bendahara.manajemen-tagihan-spp');
    }

    public function verifikasiPembayaran()
    {
        return view('Bendahara.verifikasi-pembayaran');
    }

    public function tunggakanReminder()
    {
        return view('Bendahara.tunggakan-reminder');
    }

    public function rekonsiliasiBankbank()
    {
        return view('Bendahara.rekonsiliasi-bank');
    }

    public function rekapPembayaran()
    {
        return view('Bendahara.rekap-pembayaran');
    }

    public function multiPaymentMethod()
    {
        return view('Bendahara.multi-payment-method');
    }

    public function refundManagement()
    {
        return view('Bendahara.refund-management');
    }

    public function manajemenDiskonBeasiswa()
    {
        return view('Bendahara.manajemen-diskon-beasiswa');
    }
}
