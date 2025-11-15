<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Guru;

class GuruController extends Controller
{
    public function beranda()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        return view('Guru.beranda', compact('guru'));
    }
    
    public function profil()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        return view('Guru.profil', compact('guru'));
    }
}
