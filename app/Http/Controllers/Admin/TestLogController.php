<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestLogController extends Controller
{
    /**
     * Test logging dengan IP address
     */
    public function testLog()
    {
        // Test apakah helper functions sudah loaded
        if (!function_exists('log_activity')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Helper log_activity tidak ditemukan. Jalankan: composer dump-autoload'
            ]);
        }

        try {
            // Test log activity
            log_activity(
                'Test',
                'Testing IP address logging dari IP ' . request()->ip(),
                'test_table',
                999,
                'TEST'
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Log berhasil dibuat!',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
