<?php

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

if (!function_exists('log_activity')) {
    /**
     * Log aktivitas user dengan IP address
     * 
     * @param string $jenisAktivitas Jenis aktivitas (Login, Logout, Create, Update, Delete, dll)
     * @param string $deskripsi Deskripsi detail aktivitas
     * @param string|null $namaTabel Nama tabel yang terkait
     * @param int|null $idReferensi ID record yang terkait
     * @param string|null $aksi Aksi yang dilakukan (CREATE, UPDATE, DELETE, dll)
     * @return void
     */
    function log_activity(
        string $jenisAktivitas,
        string $deskripsi,
        ?string $namaTabel = null,
        ?int $idReferensi = null,
        ?string $aksi = null
    ) {
        try {
            $user = Auth::user();
            
            LogAktivitas::create([
                'jenis_aktivitas' => $jenisAktivitas,
                'deskripsi' => $deskripsi,
                'user_id' => $user ? $user->id : null,
                'role' => $user ? $user->role : 'Guest',
                'nama_tabel' => $namaTabel,
                'id_referensi' => $idReferensi,
                'aksi' => $aksi,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silent fail - jangan ganggu flow aplikasi
            @error_log('Failed to log activity: ' . $e->getMessage());
        }
    }
}

if (!function_exists('log_login')) {
    /**
     * Log aktivitas login
     * 
     * @param \App\Models\User $user
     * @param bool $success
     * @return void
     */
    function log_login($user, bool $success = true)
    {
        $status = $success ? 'berhasil' : 'gagal';
        $ip = Request::ip();
        
        log_activity(
            'Login',
            "User {$user->name} login {$status} dari IP {$ip}",
            'users',
            $user->id,
            'LOGIN'
        );
    }
}

if (!function_exists('log_logout')) {
    /**
     * Log aktivitas logout
     * 
     * @return void
     */
    function log_logout()
    {
        $user = Auth::user();
        if ($user) {
            $ip = Request::ip();
            
            log_activity(
                'Logout',
                "User {$user->name} logout dari IP {$ip}",
                'users',
                $user->id,
                'LOGOUT'
            );
        }
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * Get real client IP address (considering proxy/load balancer)
     * 
     * @return string
     */
    function get_client_ip()
    {
        $ip = Request::ip();
        
        // Check for proxy headers
        if (Request::header('X-Forwarded-For')) {
            $ips = explode(',', Request::header('X-Forwarded-For'));
            $ip = trim($ips[0]);
        } elseif (Request::header('X-Real-IP')) {
            $ip = Request::header('X-Real-IP');
        } elseif (Request::header('CF-Connecting-IP')) {
            // Cloudflare
            $ip = Request::header('CF-Connecting-IP');
        }
        
        return $ip;
    }
}

if (!function_exists('format_user_agent')) {
    /**
     * Format user agent untuk ditampilkan
     * 
     * @param string|null $userAgent
     * @return array
     */
    function format_user_agent(?string $userAgent)
    {
        if (!$userAgent) {
            return [
                'browser' => 'Unknown',
                'platform' => 'Unknown',
                'device' => 'Unknown'
            ];
        }

        // Detect browser
        $browser = 'Unknown';
        if (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            $browser = 'Opera';
        }

        // Detect platform
        $platform = 'Unknown';
        if (preg_match('/Windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iOS|iPhone|iPad/i', $userAgent)) {
            $platform = 'iOS';
        }

        // Detect device
        $device = 'Desktop';
        if (preg_match('/Mobile/i', $userAgent)) {
            $device = 'Mobile';
        } elseif (preg_match('/Tablet|iPad/i', $userAgent)) {
            $device = 'Tablet';
        }

        return [
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device
        ];
    }
}
