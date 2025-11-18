<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        $stats = [
            'total_siswa' => Siswa::count(),
            'total_guru' => Guru::count(),
            'total_kelas' => Kelas::count(),
            'total_mapel' => MataPelajaran::count(),
            'tahun_ajaran_aktif' => $tahunAjaranAktif,
        ];

        if ($tahunAjaranAktif) {
            $kelasIds = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)->pluck('id_kelas');
            $stats['siswa_aktif'] = Siswa::whereIn('kelas_id', $kelasIds)->count();
            $stats['kelas_aktif'] = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)->count();
        }

        return view('Admin.dashboard', $stats);
    }

    /**
     * Halaman profil admin
     */
    public function profil()
    {
        $admin = auth()->user();
        return view('Admin.profil', compact('admin'));
    }

    /**
     * Update profil admin
     */
    public function updateProfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
