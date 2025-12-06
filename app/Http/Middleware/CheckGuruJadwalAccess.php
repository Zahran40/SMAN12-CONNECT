<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Guru;
use Symfony\Component\HttpFoundation\Response;

class CheckGuruJadwalAccess
{
    /**
     * Handle an incoming request.
     * Cek apakah guru bisa mengakses jadwal menggunakan fn_guru_can_access_jadwal
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Ambil ID jadwal dari route parameter
        $jadwalId = $request->route('jadwal_id') ?? $request->route('jadwalId');
        
        if (!$jadwalId) {
            return $next($request);
        }
        
        // Ambil data guru
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan');
        }
        
        // Cek akses menggunakan function fn_guru_can_access_jadwal
        $result = DB::select('SELECT fn_guru_can_access_jadwal(?, ?) as can_access', [
            $guru->id_guru,
            $jadwalId
        ]);
        
        $canAccess = $result[0]->can_access ?? 0;
        
        if (!$canAccess) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini');
        }
        
        return $next($request);
    }
}

