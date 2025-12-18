<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogAktivitasController extends Controller
{
    /**
     * Halaman utama Log Aktivitas dengan filter
     */
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('waktu', 'desc');

        // Filter berdasarkan jenis aktivitas
        if ($request->filled('jenis')) {
            $query->where('jenis_aktivitas', $request->jenis);
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan IP Address
        if ($request->filled('ip')) {
            $query->where('ip_address', $request->ip);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('waktu', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ]);
        }

        // Filter berdasarkan search (deskripsi atau IP)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->paginate(50);

        // Statistik log
        $statistik = [
            'total' => LogAktivitas::count(),
            'hari_ini' => LogAktivitas::whereDate('waktu', today())->count(),
            'minggu_ini' => LogAktivitas::whereBetween('waktu', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'bulan_ini' => LogAktivitas::whereMonth('waktu', now()->month)->whereYear('waktu', now()->year)->count(),
        ];

        // Aktivitas per jenis
        $aktivitasPerJenis = LogAktivitas::select('jenis_aktivitas', DB::raw('count(*) as total'))
            ->groupBy('jenis_aktivitas')
            ->get()
            ->pluck('total', 'jenis_aktivitas');

        // Aktivitas per role
        $aktivitasPerRole = LogAktivitas::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get()
            ->pluck('total', 'role');

        // Get unique IP addresses untuk filter
        $ipAddresses = LogAktivitas::select('ip_address')
            ->distinct()
            ->whereNotNull('ip_address')
            ->orderBy('ip_address')
            ->pluck('ip_address');

        return view('Admin.logAktivitas', compact('logs', 'statistik', 'aktivitasPerJenis', 'aktivitasPerRole', 'ipAddresses'));
    }

    /**
     * Hapus semua log lebih dari X hari
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'hari' => 'required|integer|min:1|max:365'
        ]);

        $tanggalBatas = now()->subDays($request->hari);
        $deleted = LogAktivitas::where('waktu', '<', $tanggalBatas)->delete();

        return back()->with('success', "Berhasil menghapus {$deleted} log aktivitas yang lebih dari {$request->hari} hari");
    }

    /**
     * Export log ke CSV
     */
    public function export(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('waktu', 'desc');

        // Terapkan filter yang sama
        if ($request->filled('jenis')) {
            $query->where('jenis_aktivitas', $request->jenis);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('ip')) {
            $query->where('ip_address', $request->ip);
        }
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('waktu', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ]);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->get();

        $filename = 'log_aktivitas_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['Waktu', 'Jenis Aktivitas', 'Deskripsi', 'User', 'Role', 'Tabel', 'ID Referensi', 'Aksi', 'IP Address', 'User Agent']);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->waktu,
                    $log->jenis_aktivitas,
                    $log->deskripsi,
                    $log->user ? $log->user->name : '-',
                    $log->role,
                    $log->nama_tabel,
                    $log->id_referensi,
                    $log->aksi,
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
