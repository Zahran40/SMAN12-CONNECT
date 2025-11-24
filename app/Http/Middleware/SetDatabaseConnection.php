<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login, set connection berdasarkan role
        if (Auth::check()) {
            $user = Auth::user();
            $connection = $this->getConnectionByRole($user->role);
            
            // Set default connection berdasarkan role
            Config::set('database.default', $connection);
            
            // Reconnect dengan connection baru
            DB::purge($connection);
            DB::reconnect($connection);
        }

        return $next($request);
    }

    /**
     * Get database connection name based on user role
     */
    private function getConnectionByRole(string $role): string
    {
        return match($role) {
            'admin' => 'mysql_admin',
            'guru' => 'mysql_guru',
            'siswa' => 'mysql_siswa',
            default => 'mysql', // fallback ke root connection
        };
    }
}
