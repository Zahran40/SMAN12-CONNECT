<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetDatabaseSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Set session variables untuk trigger database
            DB::statement('SET @current_user_id = ?', [$user->id]);
            DB::statement('SET @current_user_role = ?', [$user->role]);
            DB::statement('SET @current_ip_address = ?', [$request->ip()]);
            DB::statement('SET @current_user_agent = ?', [$request->userAgent()]);
        }

        return $next($request);
    }
}
