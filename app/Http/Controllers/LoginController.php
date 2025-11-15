<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function login()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('login');
    }

    /**
     * Proses autentikasi login
     */
    public function authenticate(Request $request)
    {
        // Validasi input
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email/NIP/NISN wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $identifier = $request->login;
        $password = $request->password;

        // Cari user berdasarkan email, NIP (guru), atau NISN (siswa)
        $user = $this->findUserByIdentifier($identifier);

        if (!$user) {
            return back()->withErrors([
                'login' => 'Email/NIP/NISN tidak ditemukan.',
            ])->onlyInput('login');
        }

        // Cek apakah user aktif
        if (!$user->is_active) {
            return back()->withErrors([
                'login' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->onlyInput('login');
        }

        // Attempt login dengan email (karena Auth menggunakan email)
        if (Auth::attempt(['email' => $user->email, 'password' => $password], $request->remember)) {
            $request->session()->regenerate();

            // Update last login
            $user->update(['last_login' => now()]);

            // Redirect berdasarkan role
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'login' => 'Email/NIP/NISN atau password salah.',
        ])->onlyInput('login');
    }

    /**
     * Cari user berdasarkan email, NIP, atau NISN
     */
    private function findUserByIdentifier($identifier)
    {
        // Coba cari berdasarkan email
        $user = User::where('email', $identifier)->first();
        
        if ($user) {
            return $user;
        }

        // Coba cari berdasarkan NIP (guru)
        $guru = DB::table('guru')->where('nip', $identifier)->first();
        if ($guru && $guru->user_id) {
            return User::find($guru->user_id);
        }

        // Coba cari berdasarkan NISN (siswa)
        $siswa = DB::table('siswa')->where('nisn', $identifier)->first();
        if ($siswa && $siswa->user_id) {
            return User::find($siswa->user_id);
        }

        // Coba cari berdasarkan NIS (siswa - alternatif)
        $siswa = DB::table('siswa')->where('nis', $identifier)->first();
        if ($siswa && $siswa->user_id) {
            return User::find($siswa->user_id);
        }

        return null;
    }

    /**
     * Redirect user berdasarkan role
     */
    private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('admin.tahun_ajaran'))->with('success', 'Selamat datang, ' . $user->name);
            
            case 'guru':
                return redirect()->intended(route('guru.beranda'))->with('success', 'Selamat datang, ' . $user->getFullName());
            
            case 'siswa':
                return redirect()->intended(route('siswa.beranda'))->with('success', 'Selamat datang, ' . $user->getFullName());
            
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Role tidak dikenali');
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Anda telah berhasil logout');
    }
}

    
