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
        
        // Get tahun ajaran aktif
        $tahunAjaranAktif = DB::table('tahun_ajaran')
            ->where('status', 'Aktif')
            ->where('is_archived', false)
            ->first();
        
        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif');
        }
        
        // Cari kelas siswa - jika tahun ajaran aktif adalah Genap, cari dari semester Ganjil yang sama tahunnya
        $tahunAjaranIdForQuery = $tahunAjaranAktif->id_tahun_ajaran;
        
        if ($tahunAjaranAktif->semester === 'Genap') {
            $semesterGanjil = DB::table('tahun_ajaran')
                ->where('tahun_mulai', $tahunAjaranAktif->tahun_mulai)
                ->where('tahun_selesai', $tahunAjaranAktif->tahun_selesai)
                ->where('semester', 'Ganjil')
                ->where('is_archived', false)
                ->first();
            
            if ($semesterGanjil) {
                $tahunAjaranIdForQuery = $semesterGanjil->id_tahun_ajaran;
            }
        }
        
        // Get kelas siswa dari relasi siswa_kelas
        $siswaKelas = DB::table('siswa_kelas as sk')
            ->join('kelas as k', 'sk.kelas_id', '=', 'k.id_kelas')
            ->where('sk.siswa_id', $siswa->id_siswa)
            ->where('sk.tahun_ajaran_id', $tahunAjaranIdForQuery)
            ->where('sk.status', 'Aktif')
            ->select('sk.kelas_id', 'k.nama_kelas')
            ->first();
        
        if (!$siswaKelas) {
            // Siswa belum punya kelas - inisialisasi jadwal kosong untuk semua hari
            $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $jadwalPerHari = [];
            foreach ($allDays as $hari) {
                $jadwalPerHari[$hari] = collect(); // Empty collection
            }
            
            return view('siswa.beranda', [
                'siswa' => $siswa,
                'kelasNama' => null,
                'presensiAktif' => collect(),
                'hariIni' => Carbon::now()->locale('id')->dayName,
                'allDays' => $allDays,
                'jadwalPerHari' => $jadwalPerHari
            ]);
        }
        
        // Get current day in Indonesian
        $hariIni = Carbon::now()->locale('id')->dayName;
        $today = Carbon::now()->toDateString();
        
        // Get all days for tabs
        $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        // Get all schedules for the week (grouped by day) - USING VIEW
        $jadwalPerHari = [];
        foreach ($allDays as $hari) {
            $jadwalPerHari[$hari] = DB::table('view_jadwal_siswa')
                ->where('kelas_id', $siswaKelas->kelas_id)
                ->where('hari', $hari)
                ->get();
        }
        
        // Get presensi yang sedang berlangsung hari ini - USING VIEW
        // View sudah filter berdasarkan tanggal hari ini (CURDATE), tidak perlu filter hari lagi
        $presensiAktif = DB::table('view_presensi_aktif')
            ->where('kelas_id', $siswaKelas->kelas_id)
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
        
        // Get nama kelas
        $kelasNama = $siswaKelas->nama_kelas;
        
        // Get statistik dashboard menggunakan view_dashboard_siswa
        $dashboardStats = DB::table('view_dashboard_siswa')
            ->where('id_siswa', $siswa->id_siswa)
            ->first();
        
        // Hitung total SPP yang sudah dibayar tahun ini menggunakan fn_total_spp_siswa
        $totalSppDibayar = 0;
        $result = DB::select('SELECT fn_total_spp_siswa(?, ?) as total', [
            $siswa->id_siswa,
            date('Y')
        ]);
        $totalSppDibayar = $result[0]->total ?? 0;
        
        // Ambil pengumuman aktif menggunakan sp_get_pengumuman_aktif
        $pengumuman = DB::select('CALL sp_get_pengumuman_aktif(?)', ['siswa']);
        
        return view('siswa.beranda', compact('siswa', 'kelasNama', 'presensiAktif', 'hariIni', 'allDays', 'jadwalPerHari', 'dashboardStats', 'totalSppDibayar', 'pengumuman'));
    }
    
    public function profil()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        // Get tahun ajaran aktif
        $tahunAjaranAktif = DB::table('tahun_ajaran')
            ->where('status', 'Aktif')
            ->where('is_archived', false)
            ->first();
        
        if (!$tahunAjaranAktif) {
            $kelasNama = null;
        } else {
            // Cari kelas siswa - jika tahun ajaran aktif adalah Genap, cari dari semester Ganjil yang sama tahunnya
            $tahunAjaranIdForQuery = $tahunAjaranAktif->id_tahun_ajaran;
            
            if ($tahunAjaranAktif->semester === 'Genap') {
                $semesterGanjil = DB::table('tahun_ajaran')
                    ->where('tahun_mulai', $tahunAjaranAktif->tahun_mulai)
                    ->where('tahun_selesai', $tahunAjaranAktif->tahun_selesai)
                    ->where('semester', 'Ganjil')
                    ->where('is_archived', false)
                    ->first();
                
                if ($semesterGanjil) {
                    $tahunAjaranIdForQuery = $semesterGanjil->id_tahun_ajaran;
                }
            }
            
            // Get kelas siswa dari relasi siswa_kelas
            $siswaKelas = DB::table('siswa_kelas as sk')
                ->join('kelas as k', 'sk.kelas_id', '=', 'k.id_kelas')
                ->where('sk.siswa_id', $siswa->id_siswa)
                ->where('sk.tahun_ajaran_id', $tahunAjaranIdForQuery)
                ->where('sk.status', 'Aktif')
                ->select('k.nama_kelas')
                ->first();
            
            $kelasNama = $siswaKelas->nama_kelas ?? null;
        }
        
        return view('siswa.profil', compact('siswa', 'kelasNama'));
    }
}
