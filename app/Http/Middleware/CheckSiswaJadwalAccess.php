<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use Symfony\Component\HttpFoundation\Response;

class CheckSiswaJadwalAccess
{
    /**
     * Handle an incoming request.
     * Cek apakah siswa bisa mengakses jadwal menggunakan fn_siswa_can_access_jadwal
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Ambil ID jadwal dari route parameter
        $jadwalId = $request->route('jadwal_id') ?? $request->route('jadwalId');
        
        if (!$jadwalId) {
            return $next($request);
        }
        
        // Ambil data siswa
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }
        
        // Cek akses menggunakan function fn_siswa_can_access_jadwal
        $result = DB::select('SELECT fn_siswa_can_access_jadwal(?, ?) as can_access', [
            $siswa->id_siswa,
            $jadwalId
        ]);
        
        $canAccess = $result[0]->can_access ?? 0;
        
        if (!$canAccess) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini');
        }
        
        return $next($request);
    }
}

