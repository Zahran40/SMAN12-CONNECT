<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktivitas;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Hanya log untuk user yang sudah login dan bukan request AJAX polling
        if (Auth::check() && !$request->ajax() && $request->method() !== 'GET') {
            $this->logActivity($request);
        }

        return $response;
    }

    /**
     * Log aktivitas user
     */
    private function logActivity(Request $request)
    {
        try {
            $user = Auth::user();
            $route = $request->route();
            $routeName = $route ? $route->getName() : null;
            $method = $request->method();
            
            // Tentukan jenis aktivitas berdasarkan method dan route
            $jenisAktivitas = $this->determineActivityType($method, $routeName, $request->path());
            
            // Tentukan deskripsi aktivitas
            $deskripsi = $this->generateDescription($method, $routeName, $request->path(), $request);

            LogAktivitas::create([
                'jenis_aktivitas' => $jenisAktivitas,
                'deskripsi' => $deskripsi,
                'user_id' => $user->id,
                'role' => $user->role,
                'nama_tabel' => $this->extractTableName($request->path()),
                'id_referensi' => $this->extractReferenceId($route),
                'aksi' => strtoupper($method),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silent fail - jangan ganggu flow aplikasi jika log gagal
            @error_log('Failed to log user activity: ' . $e->getMessage());
        }
    }

    /**
     * Tentukan tipe aktivitas
     */
    private function determineActivityType($method, $routeName, $path)
    {
        if (str_contains($path, 'login')) return 'Login';
        if (str_contains($path, 'logout')) return 'Logout';
        
        switch ($method) {
            case 'POST':
                return str_contains($path, 'import') ? 'Import' : 'Create';
            case 'PUT':
            case 'PATCH':
                return 'Update';
            case 'DELETE':
                return 'Delete';
            default:
                return 'Action';
        }
    }

    /**
     * Generate deskripsi aktivitas
     */
    private function generateDescription($method, $routeName, $path, $request)
    {
        $user = Auth::user();
        $userName = $user->name ?? 'User';
        $ip = $request->ip();
        
        // Extract module dari path
        $pathParts = explode('/', $path);
        $module = $pathParts[0] ?? 'System';
        
        $action = match($method) {
            'POST' => 'membuat',
            'PUT', 'PATCH' => 'mengubah',
            'DELETE' => 'menghapus',
            default => 'mengakses'
        };

        return "{$userName} {$action} data pada module {$module} dari IP {$ip}";
    }

    /**
     * Extract nama tabel dari path
     */
    private function extractTableName($path)
    {
        $pathParts = explode('/', $path);
        // Ambil segment kedua atau pertama sebagai indikasi tabel
        return $pathParts[1] ?? $pathParts[0] ?? null;
    }

    /**
     * Extract ID referensi dari route parameters
     */
    private function extractReferenceId($route)
    {
        if (!$route) return null;
        
        $parameters = $route->parameters();
        
        // Coba ambil parameter yang mengandung 'id'
        foreach ($parameters as $key => $value) {
            if (str_contains(strtolower($key), 'id') && is_numeric($value)) {
                return $value;
            }
        }
        
        // Jika tidak ada, ambil parameter numerik pertama
        foreach ($parameters as $value) {
            if (is_numeric($value)) {
                return $value;
            }
        }
        
        return null;
    }
}
