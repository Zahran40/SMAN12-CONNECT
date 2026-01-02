<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;

class OrangTuaController extends Controller
{
    public function beranda()
    {
        $user = auth()->user();
        
        // Ambil data siswa yang terkait dengan orang tua ini
        // Asumsi: reference_id di user orangtua = siswa_id
        $siswa = null;
        if ($user->reference_id) {
            $siswa = Siswa::with(['kelas', 'user'])->find($user->reference_id);
        }
        
        return view('OrangTua.beranda', compact('siswa'));
    }
}
